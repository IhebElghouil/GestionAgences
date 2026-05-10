<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <style>
        table, th, td {
            direction: rtl;
            line-height: 2.5;
            font-size: 16px;
        }
        .left {
            direction: rtl;
            font-weight: bold;
        }
        table {
            width: 100%;
        }
        td {
            text-align: right;
        }
        @media print {
            header, footer {
                display: none;
            }
        }
    </style>
</head>
<body>
<?php
/**
 * Fonction pour convertir un nombre en mots (arabe)
 */
function numToWordsRec($number) {
    $words = array(
        0 => 'صفر', 1 => 'يوم واحد', 2 => 'يومان',
        3 => 'ثلاثة', 4 => 'أربعة', 5 => 'خمسة',
        6 => 'ستّة', 7 => 'سبعة', 8 => 'ثمانية',
        9 => 'تسعة', 10 => 'عشرة', 11 => 'أحدى عشر',
        12 => 'إثنى عشر', 13 => 'ثلاثة عشر', 
        14 => 'أربعة عشر', 15 => 'خمسة عشر',
        16 => 'ستّة عشر', 17 => 'سبعة عشر', 18 => 'ثمانية عشر',
        19 => 'تسعة عشر', 20 => 'عشرون', 30 => 'ثلاثون',
        40 => 'أربعون', 50 => 'خمسون', 60 => 'ستّون',
        70 => 'سبعون', 80 => 'ثمانون', 90 => 'تسعون'
    );

    if ($number < 3) {
        return $words[$number] . ' (' . $number . ')';
    }

    if ($number < 11) {
        return $words[$number] . ' (' . $number . ') أيّام';
    }
    
    if ($number < 20) {
        return $words[$number] . ' (' . $number . ') يوما';
    }

    if ($number < 100) {
        return $words[$number % 10] . ' و ' . $words[10 * floor($number / 10)] . ' (' . $number . ') يوما';
    }

    return numToWordsRec(floor($number / 1000000)) . ' مليون ' . numToWordsRec($number % 1000000);
}

/**
 * Fonction principale de traitement - Retourne un tableau de champs vides
 */
function validateCongeRequest() {
    $emptyFields = [];
    
    // Vérifier les paramètres obligatoires pour tous les types
    if (empty($_GET['mecano'])) {
        $emptyFields[] = 'رقم الميكانيكي (mecano)';
    }
    
    if (empty($_GET['DateDebut'])) {
        $emptyFields[] = 'تاريخ البداية (DateDebut)';
    }
    
    if (empty($_GET['DateFin'])) {
        $emptyFields[] = 'تاريخ النهاية (DateFin)';
    }
    
    if (!isset($_GET['TypeRepos']) || $_GET['TypeRepos'] === '') {
        $emptyFields[] = 'نوع الراحة (TypeRepos)';
    } else {
        $typeRepos = intval($_GET['TypeRepos']);
        
        // Pour les congés annuels, JourDeReposFixe est requis
        if ($typeRepos === 0) {
            if (!isset($_GET['JourDeReposFixe']) || $_GET['JourDeReposFixe'] === '') {
                $emptyFields[] = 'يوم الراحة الثابت (JourDeReposFixe)';
            }
        }
    }
    
    // Vérifier d'autres champs importants selon votre logique métier
    if (empty($_GET['NbJoursConge'])) {
        $emptyFields[] = 'عدد أيام الراحة (NbJoursConge)';
    }
    
    if (empty($_GET['NomPrenom'])) {
        $emptyFields[] = 'الاسم الكامل (NomPrenom)';
    }
    
    return $emptyFields;
}

/**
 * Gestion des types de repos
 */
function getTypeReposInfo($typeRepos, $typeReposType) {
    $typeInfo = ['type' => $typeRepos, 'type2' => 0];
    
    switch ($typeRepos) {
        case 13:
            $typeInfo['type'] = 1;
            $typeInfo['type2'] = 1;
            break;
        case 14:
            $typeInfo['type'] = 1;
            $typeInfo['type2'] = 2;
            break;
        case 1:
            $typeInfo['type2'] = 3;
            break;
    }
    
    // Gestion des sous-types pour AT (Accident de travail)
    if ($typeRepos == 6) {
        $typeInfo['type2'] = match($typeReposType) {
            'prolongation' => 9,
            'rechute' => 10,
            'accident' => 11,
            default => 0
        };
    }
    
    return $typeInfo;
}

/**
 * Vérifie les conflits de dates
 */
function checkDateConflicts($conn, $mecano, $dateDebut, $dateFin) {
    $conflicts = false;
    
    // Vérifier dans la table conge (congés annuels)
    $stmt = $conn->prepare("SELECT COUNT(*) FROM conge WHERE mecano = ? AND datedebut < ? AND datefin > ?");
    $stmt->bind_param("iss", $mecano, $dateFin, $dateDebut);
    $stmt->execute();
    $stmt->bind_result($countConge);
    $stmt->fetch();
    $stmt->close();
    
    // Vérifier dans la table autreconge (autres congés)
    $stmt = $conn->prepare("SELECT COUNT(*) FROM autreconge WHERE mecano = ? AND datedebut < ? AND datefin > ?");
    $stmt->bind_param("iss", $mecano, $dateFin, $dateDebut);
    $stmt->execute();
    $stmt->bind_result($countAutreConge);
    $stmt->fetch();
    $stmt->close();
    
    return ($countConge > 0 || $countAutreConge > 0);
}

// Fonction pour afficher la liste des champs vides en arabe
function formatEmptyFieldsMessage($emptyFields) {
    if (empty($emptyFields)) {
        return "";
    }
    
    $message = "الحقول التالية فارغة:\n";
    $message .= "• " . implode("\n• ", $emptyFields);
    $message .= "\n\nيرجى ملء جميع الحقول المطلوبة.";
    
    return $message;
}

// Point d'entrée principal
try {
    require_once(__DIR__ . "/DbConnexion.php");
    
    // Vérifier la connexion
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Valider la requête et obtenir la liste des champs vides
    $emptyFields = validateCongeRequest();
    
    if (!empty($emptyFields)) {
        $errorMessage = formatEmptyFieldsMessage($emptyFields);
        throw new Exception($errorMessage);
    }
    
    // Fonction pour calculer le solde restant
    function calculateRemainingMaladie(int $solde, int $jours): int {
        return max(0, $solde - $jours);
    }

    // Récupérer et valider les données
    $jourDeReposFixe = isset($_GET['JourDeReposFixe']) ? intval($_GET['JourDeReposFixe']) : 0;
    $mecano = intval($_GET['mecano']);
    $nom = htmlspecialchars($_GET['NomPrenom'] ?? '');
    $dateDebut = $_GET['DateDebut'];
    $dateFin = $_GET['DateFin'];
    $nbJoursConge = intval($_GET['NbJoursConge'] ?? 0);
    $nbJoursResteSolde = intval($_GET['NbJoursResteSolde'] ?? 0);
    $SoldeOutOfMaladie = calculateRemainingMaladie(
        (int)($_GET['SoldeOutOfMaladie'] ?? 0),
        (int)($_GET['NbJoursConge'] ?? 0)
    );
    $soldeActuel = intval($_GET['Solde'] ?? 0);
    $typeRepos = intval($_GET['TypeRepos'] ?? 0);
    $typeReposType = $_GET['TypeReposType'] ?? '';
    $textArea = htmlspecialchars($_GET['TextArea'] ?? '');
    
    // Validation supplémentaire pour les valeurs numériques
    if ($mecano <= 0) {
        throw new Exception("رقم الميكانيكي غير صحيح");
    }
    
    if ($nbJoursConge <= 0) {
        throw new Exception("عدد أيام الراحة يجب أن يكون أكبر من صفر");
    }
    
    // Validation des dates
    $dateObjDebut = DateTime::createFromFormat("Y-m-d", $dateDebut);
    $dateObjFin = DateTime::createFromFormat("Y-m-d", $dateFin);
    
    if (!$dateObjDebut || !$dateObjFin) {
        throw new Exception("تاريخ غير صحيح. يرجى استخدام الصيغة YYYY-MM-DD");
    }
    
    if ($dateObjDebut > $dateObjFin) {
        throw new Exception("تاريخ البداية يجب أن يكون قبل تاريخ النهاية");
    }
    
    $annee = $dateObjDebut->format("Y");
    $add = $_SERVER['REMOTE_ADDR'];
    
    // Vérifier les conflits de dates
    if (checkDateConflicts($conn, $mecano, $dateDebut, $dateFin)) {
        throw new Exception("الراحة المطلوبة قد تكون مسجلة سابقا او جزء تابع لراحة مسجلة");
    }
    
    // Traitement selon le type de repos
    $typeInfo = getTypeReposInfo($typeRepos, $typeReposType);
    $typeRepos = $typeInfo['type'];
    $type2 = $typeInfo['type2'];
    
    // Insertion dans la base de données
    if ($typeRepos == 0) {
        // Congé normal (annuel)
        $stmt = $conn->prepare("INSERT INTO conge (mecano, datedebut, datefin, nbjours, annee, LoggedAs, Solde, JRepos) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception("خطأ في إعداد استعلام قاعدة البيانات: " . $conn->error);
        }
        $stmt->bind_param("issiisii", $mecano, $dateDebut, $dateFin, $nbJoursConge, $annee, $add, $nbJoursResteSolde, $jourDeReposFixe);
    } else {
        // Autres types de repos
        if ($typeRepos == 6) {
            // Accident de travail - vérifier la date maximale
            $stmt = $conn->prepare("SELECT MAX(datedebut) FROM autreconge WHERE mecano = ? AND type = 6");
            $stmt->bind_param("i", $mecano);
            $stmt->execute();
            $stmt->bind_result($maxDate);
            $stmt->fetch();
            $stmt->close();
            
            $valide = ($maxDate && $maxDate < $dateDebut) ? 1 : 0;
            
            $stmt = $conn->prepare("INSERT INTO autreconge (mecano, type, datedebut, datefin, nbj, type2, commentaire, anne, valide, LoggedAs) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("خطأ في إعداد استعلام قاعدة البيانات: " . $conn->error);
            }
            $stmt->bind_param("iissiisiss", $mecano, $typeRepos, $dateDebut, $dateFin, $nbJoursConge, $type2, $textArea, $annee, $valide, $add);
        } else {
            $stmt = $conn->prepare("INSERT INTO autreconge (mecano, type, datedebut, datefin, nbj, type2, commentaire, anne, LoggedAs) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                throw new Exception("خطأ في إعداد استعلام قاعدة البيانات: " . $conn->error);
            }
            $stmt->bind_param("iissiisss", $mecano, $typeRepos, $dateDebut, $dateFin, $nbJoursConge, $type2, $textArea, $annee, $add);
        }
    }
    
    // Exécuter l'insertion
    if (!$stmt->execute()) {
        throw new Exception("خطأ في إدخال البيانات: " . $stmt->error);
    }
    
    $lastId = $conn->insert_id;
    $stmt->close();
    
    // Mise à jour des données si nécessaire
    if ($typeRepos == 0) {
        // Mise à jour du solde pour congé normal
        $stmt = $conn->prepare("UPDATE nbconge SET rest = ? WHERE mecano = ?");
        if (!$stmt) {
            throw new Exception("خطأ في تحديث الرصيد: " . $conn->error);
        }
        $stmt->bind_param("ii", $SoldeOutOfMaladie, $mecano);
        $stmt->execute();
        $stmt->close();
        
        $message = "تم اضافة الراحة السنوية بنجاح تحت رقم C" . $lastId;
    } elseif ($typeRepos == 6 && isset($valide) && $valide == 1) {
        // Mettre à jour les AT précédents comme non valides
        $stmt = $conn->prepare("UPDATE autreconge SET valide = 0 WHERE mecano = ? AND id <> ? AND type = 6");
        if (!$stmt) {
            throw new Exception("خطأ في تحديث الحوادث السابقة: " . $conn->error);
        }
        $stmt->bind_param("ii", $mecano, $lastId);
        $stmt->execute();
        $stmt->close();
        
        $message = "تم اضافة الراحة بنجاح تحت رقم " . $lastId;
    } else {
        $message = "تم اضافة الراحة بنجاح تحت رقم " . $lastId;
    }
    
    // Redirection avec message de succès
    echo "<script>
            alert('" . addslashes($message) . "');
            window.open('index.php?mecano=" . $mecano . "', '_top');
          </script>";
    
} catch (Exception $e) {
    // Gestion des erreurs
    $errorMessage = str_replace("'", "\\'", $e->getMessage());
    echo "<script>
            alert('" . $errorMessage . "');
            history.go(-1);
          </script>";
} finally {
    // Fermer la connexion si elle existe
    if (isset($conn) && $conn) {
        $conn->close();
    }
}
?>
</body>
</html>