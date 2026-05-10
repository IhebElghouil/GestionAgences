<?php
session_start();
require('connection.php');

header('Content-Type: application/json; charset=utf-8');

// Vérification des droits admin
if (!isset($_SESSION['congidGA']) || $_SESSION['departement'] !== "admin") {
    die(json_encode(['success' => false, 'message' => 'Accès non autorisé']));
}

// Vérification du fichier
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    die(json_encode(['success' => false, 'message' => 'Erreur lors de l\'upload du fichier']));
}

$file = $_FILES['file'];
$fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$action = $_POST['action'] ?? 'import';

// Vérification de l'extension
$validExtensions = ['csv', 'txt', 'ods', 'xlsx'];
if (!in_array($fileExt, $validExtensions)) {
    die(json_encode(['success' => false, 'message' => 'Format de fichier non supporté']));
}

// Traitement selon le type de fichier
if ($fileExt === 'csv' || $fileExt === 'txt') {
    $data = processCSV($file['tmp_name']);
} else if ($fileExt === 'ods') {
    $data = processODS($file['tmp_name']);
} else if ($fileExt === 'xlsx') {
    $data = processXLSX($file['tmp_name']);
}

if (!$data['success']) {
    die(json_encode($data));
}

// Si c'est juste un aperçu, retourner les données
if ($action === 'preview') {
    echo json_encode([
        'success' => true,
        'data' => $data['data']
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// Sinon, importer dans la base de données
$result = importToDatabase($data['data']);

echo json_encode($result, JSON_UNESCAPED_UNICODE);

/**
 * Traite un fichier CSV
 */
function processCSV($filepath) {
    $content = file_get_contents($filepath);
    
    // Détection de l'encodage
    $arabicEncodings = ['UTF-8', 'CP1256', 'ISO-8859-6', 'WINDOWS-1256'];
    $encoding = mb_detect_encoding($content, $arabicEncodings, true);
    
    if ($encoding && $encoding !== 'UTF-8') {
        $content = mb_convert_encoding($content, 'UTF-8', $encoding);
    }
    
    // Supprimer le BOM
    if (substr($content, 0, 3) == "\xEF\xBB\xBF") {
        $content = substr($content, 3);
    }
    
    $lines = explode("\n", $content);
    $data = [];
    $separator = (strpos($lines[0], ';') !== false) ? ';' : ',';
    
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) continue;
        
        $values = str_getcsv($line, $separator);
        $values = array_map('trim', $values);
        $data[] = $values;
    }
    
    return ['success' => true, 'data' => $data];
}

/**
 * Traite un fichier ODS
 */
function processODS($filepath) {
    if (!class_exists('ZipArchive')) {
        return ['success' => false, 'message' => 'La bibliothèque ZipArchive n\'est pas disponible'];
    }
    
    $zip = new ZipArchive();
    if ($zip->open($filepath) !== true) {
        return ['success' => false, 'message' => 'Impossible d\'ouvrir le fichier ODS'];
    }
    
    // Lire le contenu XML
    $content = $zip->getFromName('content.xml');
    $zip->close();
    
    if (!$content) {
        return ['success' => false, 'message' => 'Fichier ODS invalide'];
    }
    
    // Parser le XML
    $xml = simplexml_load_string($content);
    $namespaces = $xml->getNamespaces(true);
    
    $data = [];
    $rows = $xml->xpath('//table:table-row');
    
    foreach ($rows as $row) {
        $cells = $row->xpath('table:table-cell');
        $rowData = [];
        
        foreach ($cells as $cell) {
            $value = '';
            $cellType = (string)$cell['office:value-type'];
            
            if ($cellType === 'string' || $cellType === '') {
                $texts = $cell->xpath('text:p');
                $value = !empty($texts) ? (string)$texts[0] : '';
            } else if ($cellType === 'float' || $cellType === 'currency') {
                $value = (string)$cell['office:value'];
            } else if ($cellType === 'date') {
                $dateValue = (string)$cell['office:date-value'];
                if (!empty($dateValue)) {
                    $date = new DateTime($dateValue);
                    $value = $date->format('d/m/Y');
                }
            }
            
            $rowData[] = trim($value);
        }
        
        // Vérifier si la ligne n'est pas vide
        $nonEmpty = array_filter($rowData, function($v) {
            return !empty($v);
        });
        
        if (!empty($nonEmpty)) {
            $data[] = $rowData;
        }
    }
    
    return ['success' => true, 'data' => $data];
}

/**
 * Traite un fichier XLSX (via conversion simple)
 */
function processXLSX($filepath) {
    return ['success' => false, 'message' => 'Le format XLSX nécessite une bibliothèque supplémentaire. Veuillez utiliser ODS ou CSV.'];
}

/**
 * Importe les données dans la base de données
 */
function importToDatabase($data) {
    global $connection;
    
    $success_count = 0;
    $error_count = 0;
    $errors = [];
    $lineNumber = 0;
    
    mysqli_set_charset($connection, 'utf8mb4');
    mysqli_begin_transaction($connection);
    
    try {
        foreach ($data as $row) {
            $lineNumber++;
            
            // Ignorer la première ligne si c'est l'en-tête
            if ($lineNumber === 1) {
                $firstCell = trim($row[0] ?? '');
                if ($firstCell === 'mecano' || $firstCell === 'MECANO' || $firstCell === 'الرقم الآلي') {
                    continue;
                }
            }
            
            // Vérifier le nombre de colonnes (14 colonnes attendues avec les IDs)
            if (count($row) < 14) {
                $errors[] = "السطر $lineNumber: عدد الأعمدة غير كاف (" . count($row) . "/14)";
                $error_count++;
                continue;
            }
            
            // Nettoyage des données
            // Colonnes: 
            // 0: mecano, 1: decision_number, 2: issue_date, 3: ancienrang, 4: ancienrang_id,
            // 5: nouveaurang, 6: nouveaurang_id, 7: ancienneechelle, 8: nouvelechelle,
            // 9: anciennegrade, 10: nouveaugrade, 11: notes, 12: dateeffet, 13: commission
            
            $mecano = mysqli_real_escape_string($connection, trim($row[0] ?? ''));
            $decision_number = mysqli_real_escape_string($connection, trim($row[1] ?? ''));
            $issue_date = trim($row[2] ?? '');
            $ancienrang = mysqli_real_escape_string($connection, trim($row[3] ?? ''));
            $ancienrang_id = intval($row[4] ?? 0);
            $nouveaurang = mysqli_real_escape_string($connection, trim($row[5] ?? ''));
            $nouveaurang_id = intval($row[6] ?? 0);
            
            // Conversion des valeurs numériques
            $ancienneechelle = intval(preg_replace('/[^0-9]/', '', $row[7] ?? 0));
            $nouvelechelle = intval(preg_replace('/[^0-9]/', '', $row[8] ?? 0));
            $anciennegrade = intval(preg_replace('/[^0-9]/', '', $row[9] ?? 0));
            $nouveaugrade = intval(preg_replace('/[^0-9]/', '', $row[10] ?? 0));
            
            $notes = mysqli_real_escape_string($connection, trim($row[11] ?? ''));
            $dateeffet = trim($row[12] ?? '');
            $commission = mysqli_real_escape_string($connection, trim($row[13] ?? ''));
            
            // Validation des champs obligatoires
            if (empty($mecano)) {
                $errors[] = "السطر $lineNumber: الرقم الآلي إجباري";
                $error_count++;
                continue;
            }
            
            if (empty($ancienrang)) {
                $errors[] = "السطر $lineNumber: الرتبة القديمة إجبارية";
                $error_count++;
                continue;
            }
            
            if ($ancienrang_id <= 0) {
                $errors[] = "السطر $lineNumber: معرف الرتبة القديمة غير صالح";
                $error_count++;
                continue;
            }
            
            if (empty($nouveaurang)) {
                $errors[] = "السطر $lineNumber: الرتبة الجديدة إجبارية";
                $error_count++;
                continue;
            }
            
            if ($nouveaurang_id <= 0) {
                $errors[] = "السطر $lineNumber: معرف الرتبة الجديدة غير صالح";
                $error_count++;
                continue;
            }
            
            if ($ancienneechelle <= 0) {
                $errors[] = "السطر $lineNumber: السلم القديم يجب أن يكون رقماً صحيحاً موجباً";
                $error_count++;
                continue;
            }
            
            if ($nouvelechelle <= 0) {
                $errors[] = "السطر $lineNumber: السلم الجديد يجب أن يكون رقماً صحيحاً موجباً";
                $error_count++;
                continue;
            }
            
            if ($anciennegrade <= 0) {
                $errors[] = "السطر $lineNumber: الدرجة القديمة يجب أن تكون رقماً صحيحاً موجباً";
                $error_count++;
                continue;
            }
            
            if ($nouveaugrade <= 0) {
                $errors[] = "السطر $lineNumber: الدرجة الجديدة يجب أن تكون رقماً صحيحاً موجباً";
                $error_count++;
                continue;
            }
            
            if (empty($dateeffet)) {
                $errors[] = "السطر $lineNumber: تاريخ الفاعلية إجباري";
                $error_count++;
                continue;
            }
            
            // Formatage des dates
            $issue_date_formatted = !empty($issue_date) ? formatDate($issue_date) : null;
            $dateeffet_formatted = formatDate($dateeffet);
            
            if (!empty($issue_date) && !$issue_date_formatted) {
                $errors[] = "السطر $lineNumber: تنسيق تاريخ الإصدار غير صالح";
                $error_count++;
                continue;
            }
            
            if (!$dateeffet_formatted) {
                $errors[] = "السطر $lineNumber: تنسيق تاريخ الفاعلية غير صالح";
                $error_count++;
                continue;
            }
            
            // Vérification si l'enregistrement existe déjà
            $check_query = "SELECT id FROM carriere WHERE mecano = '$mecano' AND dateeffet = '$dateeffet_formatted'";
            if (!empty($decision_number)) {
                $check_query .= " AND decision_number = '$decision_number'";
            }
            
            $check_result = mysqli_query($connection, $check_query);
            
            if (mysqli_num_rows($check_result) > 0) {
                // Mise à jour
                $query = "UPDATE carriere SET 
                          decision_number = " . (!empty($decision_number) ? "'$decision_number'" : "NULL") . ",
                          issue_date = " . ($issue_date_formatted ? "'$issue_date_formatted'" : "NULL") . ",
                          ancienrang = '$ancienrang',
                          ancienrang_id = $ancienrang_id,
                          nouveaurang = '$nouveaurang',
                          nouveaurang_id = $nouveaurang_id,
                          ancienneechelle = $ancienneechelle,
                          nouvelechelle = $nouvelechelle,
                          anciennegrade = $anciennegrade,
                          nouveaugrade = $nouveaugrade,
                          notes = " . (!empty($notes) ? "'$notes'" : "NULL") . ",
                          commission = " . (!empty($commission) ? "'$commission'" : "NULL") . ",
                          updated_at = NOW()
                          WHERE mecano = '$mecano' AND dateeffet = '$dateeffet_formatted'";
                          
                if (!empty($decision_number)) {
                    $query .= " AND decision_number = '$decision_number'";
                }
            } else {
                // Insertion
                $query = "INSERT INTO carriere 
                          (mecano, decision_number, issue_date, ancienrang, ancienrang_id, 
                           nouveaurang, nouveaurang_id, ancienneechelle, nouvelechelle, 
                           anciennegrade, nouveaugrade, notes, dateeffet, commission, created_at)
                          VALUES 
                          ('$mecano', " . (!empty($decision_number) ? "'$decision_number'" : "NULL") . ", " . 
                          ($issue_date_formatted ? "'$issue_date_formatted'" : "NULL") . ", 
                          '$ancienrang', $ancienrang_id,
                          '$nouveaurang', $nouveaurang_id,
                          $ancienneechelle, $nouvelechelle, $anciennegrade, $nouveaugrade,
                          " . (!empty($notes) ? "'$notes'" : "NULL") . ", 
                          '$dateeffet_formatted', " . (!empty($commission) ? "'$commission'" : "NULL") . ", 
                          NOW())";
            }
            
            if (mysqli_query($connection, $query)) {
                $success_count++;
            } else {
                $errors[] = "السطر $lineNumber: خطأ في قاعدة البيانات - " . mysqli_error($connection);
                $error_count++;
            }
        }
        
        mysqli_commit($connection);
        
    } catch (Exception $e) {
        mysqli_rollback($connection);
        $errors[] = "خطأ عام: " . $e->getMessage();
        $error_count++;
    }
    
    return [
        'success' => true,
        'success_count' => $success_count,
        'error_count' => $error_count,
        'errors' => $errors,
        'message' => "تم الاستيراد. $success_count سجل بنجاح، $error_count فشل."
    ];
}

/**
 * Formate une date en YYYY-MM-DD
 */
function formatDate($date) {
    $date = trim($date);
    if (empty($date)) return null;
    
    // Remplacer les séparateurs
    $date = str_replace(['/', '\\', '.'], '-', $date);
    
    // Essayer différents formats
    $formats = ['Y-m-d', 'd-m-Y', 'm-d-Y', 'Y/m/d', 'd/m/Y', 'm/d/Y', 'Y.m.d', 'd.m.Y', 'm.d.Y'];
    
    foreach ($formats as $format) {
        $d = DateTime::createFromFormat($format, $date);
        if ($d && $d->format($format) === $date) {
            return $d->format('Y-m-d');
        }
    }
    
    // Dernier essai avec strtotime
    $timestamp = strtotime($date);
    if ($timestamp !== false && $timestamp > 0) {
        return date('Y-m-d', $timestamp);
    }
    
    return null;
}
?>