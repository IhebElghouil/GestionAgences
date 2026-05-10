<?php
session_start();
include('Cnx_Include.php');

header('Content-Type: application/json');

// Fonction de validation et de nettoyage
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fonction pour nettoyer le nom du fichier tout en préservant l'arabe
function sanitize_filename($filename) {
    // Séparer le nom et l'extension
    $path_parts = pathinfo($filename);
    $name = $path_parts['filename'];
    $extension = isset($path_parts['extension']) ? '.' . $path_parts['extension'] : '';
    
    // Remplacer les caractères problématiques (hors lettres arabes, chiffres, points, tirets)
    // Permettre: lettres arabes, lettres latines, chiffres, espaces, tirets, underscores
    $name = preg_replace('/[^\p{Arabic}\p{Latin}\p{N}\s\-_]/u', '', $name);
    
    // Remplacer les espaces par des underscores
    $name = preg_replace('/\s+/', '_', $name);
    
    // Limiter la longueur (optionnel)
    $name = mb_substr($name, 0, 100);
    
    return $name . $extension;
}

// Vérifier l'authentification
if (!isset($_SESSION['congidGA'])) {
    echo json_encode(['success' => false, 'message' => 'غير مصرح بالوصول']);
    exit;
}

// Vérifier les droits admin
if ($_SESSION['departement'] !== "admin") {
    echo json_encode(['success' => false, 'message' => 'غير مصرح بالوصول - صلاحيات admin مطلوبة']);
    exit;
}

// Vérifier les données POST
if (!isset($_POST['detachement_id']) || empty($_POST['detachement_id'])) {
    echo json_encode(['success' => false, 'message' => 'معرف الملحق مطلوب']);
    exit;
}

if (!isset($_FILES['document']) || $_FILES['document']['error'] == UPLOAD_ERR_NO_FILE) {
    echo json_encode(['success' => false, 'message' => 'الرجاء اختيار ملف']);
    exit;
}

$detachement_id = intval($_POST['detachement_id']);
$description = isset($_POST['description']) ? sanitize_input($_POST['description']) : '';
$uploaded_by = $_SESSION['congidGA'];

// Configuration
$base_path = dirname(__DIR__);
$upload_dir_absolute = $base_path . '/uploads/detachement_docs/';
$upload_dir_relative = 'uploads/detachement_docs/';
$max_file_size = 10 * 1024 * 1024; // 10MB
$allowed_types = [
    'pdf' => 'application/pdf',
    'doc' => 'application/msword',
    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'xls' => 'application/vnd.ms-excel',
    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
];

// Créer le dossier d'upload
if (!file_exists($upload_dir_absolute)) {
    if (!mkdir($upload_dir_absolute, 0755, true)) {
        echo json_encode(['success' => false, 'message' => 'لا يمكن إنشاء مجلد الرفع']);
        exit;
    }
}

// Vérifier que le détachement existe
$check_stmt = mysqli_prepare($connection, "SELECT id, nomprenom FROM detachement WHERE id = ?");
mysqli_stmt_bind_param($check_stmt, "i", $detachement_id);
mysqli_stmt_execute($check_stmt);
$check_result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($check_result) == 0) {
    echo json_encode(['success' => false, 'message' => 'الملحق غير موجود']);
    mysqli_stmt_close($check_stmt);
    exit;
}
$detachement = mysqli_fetch_assoc($check_result);
mysqli_stmt_close($check_stmt);

$file = $_FILES['document'];
$file_name = basename($file['name']);
$file_tmp = $file['tmp_name'];
$file_size = $file['size'];
$file_error = $file['error'];

// Vérifier l'erreur d'upload
if ($file_error !== UPLOAD_ERR_OK) {
    $errors = [
        UPLOAD_ERR_INI_SIZE => 'الملف كبير جداً',
        UPLOAD_ERR_FORM_SIZE => 'الملف كبير جداً',
        UPLOAD_ERR_PARTIAL => 'تم رفع الملف جزئياً',
        UPLOAD_ERR_NO_FILE => 'لم يتم رفع ملف',
        UPLOAD_ERR_NO_TMP_DIR => 'مجلد مؤقت مفقود',
        UPLOAD_ERR_CANT_WRITE => 'فشل كتابة الملف',
        UPLOAD_ERR_EXTENSION => 'نوع الملف غير مسموح'
    ];
    echo json_encode(['success' => false, 'message' => $errors[$file_error] ?? 'خطأ غير معروف']);
    exit;
}

// Vérifier la taille
if ($file_size > $max_file_size) {
    echo json_encode(['success' => false, 'message' => 'حجم الملف يتجاوز 10 ميغابايت']);
    exit;
}

// Vérifier l'extension
$extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
if (!array_key_exists($extension, $allowed_types)) {
    echo json_encode(['success' => false, 'message' => 'نوع الملف غير مسموح']);
    exit;
}

// Vérifier le type MIME réel
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $file_tmp);
finfo_close($finfo);

if ($mime_type !== $allowed_types[$extension]) {
    echo json_encode(['success' => false, 'message' => 'نوع الملف لا يتطابق مع الامتداد']);
    exit;
}

// Nettoyer le nom du fichier original (préserver l'arabe)
$safe_name = sanitize_filename($file_name);

// Générer un nom unique pour le stockage (préserver le nom original dans la BDD)
$timestamp = date('Ymd_His');
$unique_id = uniqid();
$new_file_name = 'det_' . $detachement_id . '_' . $timestamp . '_' . $unique_id . '.' . $extension;
$file_path_absolute = $upload_dir_absolute . $new_file_name;
$file_path_relative = $upload_dir_relative . $new_file_name;

// Déplacer le fichier
if (!move_uploaded_file($file_tmp, $file_path_absolute)) {
    echo json_encode(['success' => false, 'message' => 'فشل في حفظ الملف']);
    exit;
}

// Insérer dans la base de données
$insert_stmt = mysqli_prepare($connection, 
    "INSERT INTO detachement_documents (detachement_id, document_name, file_path, file_size, document_type, description, uploaded_by, upload_date) 
     VALUES (?, ?, ?, ?, ?, ?, ?, NOW())"
);

mysqli_stmt_bind_param($insert_stmt, "ississs", 
    $detachement_id, 
    $safe_name,  // Nom original nettoyé (avec arabe)
    $file_path_relative,
    $file_size, 
    $extension, 
    $description, 
    $uploaded_by
);

if (mysqli_stmt_execute($insert_stmt)) {
    $document_id = mysqli_insert_id($connection);
    
    echo json_encode([
        'success' => true,
        'message' => 'تم رفع المستند بنجاح',
        'document_id' => $document_id,
        'file_name' => $safe_name,
        'original_name' => $file_name,
        'file_path' => $file_path_relative
    ]);
} else {
    // Nettoyer le fichier en cas d'erreur
    if (file_exists($file_path_absolute)) {
        unlink($file_path_absolute);
    }
    echo json_encode(['success' => false, 'message' => 'خطأ في حفظ البيانات: ' . mysqli_error($connection)]);
}

mysqli_stmt_close($insert_stmt);
?>