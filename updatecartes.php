<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>نظام الرسائل</title>
    <style>
        /* Styles généraux */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }

        /* Nouveau style pour les alertes */
        .alert-modern {
            position: relative;
            padding: 25px 60px 25px 25px;
            margin: 20px auto;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
            max-width: 600px;
            opacity: 0;
            transform: translateY(-20px);
            animation: fadeInUp 0.5s ease forwards;
            border-right: 5px solid;
            background: white;
            color: #333;
        }

        .alert-modern.success {
            border-color: #10b981;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }

        .alert-modern.error {
            border-color: #ef4444;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        }

        .alert-modern.info {
            border-color: #3b82f6;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        }

        .alert-modern.warning {
            border-color: #f59e0b;
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        }

        .alert-modern .alert-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 28px;
        }

        .alert-modern .alert-content {
            padding-left: 50px;
        }

        .alert-modern .alert-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .alert-modern .alert-message {
            font-size: 16px;
            line-height: 1.5;
        }

        .alert-modern .close-btn {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: #6b7280;
            transition: color 0.3s;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .alert-modern .close-btn:hover {
            background-color: rgba(0, 0, 0, 0.05);
            color: #374151;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animation de sortie */
        .alert-modern.fade-out {
            animation: fadeOutDown 0.5s ease forwards;
        }

        @keyframes fadeOutDown {
            to {
                opacity: 0;
                transform: translateY(20px);
            }
        }

        /* Style pour les icônes */
        .icon-check { color: #10b981; }
        .icon-error { color: #ef4444; }
        .icon-info { color: #3b82f6; }
        .icon-warning { color: #f59e0b; }
    </style>
</head>
<body>

<?php
session_start();
require('connection.php');
?>

<script>
function GoToURL() {
    setTimeout(function() {
        window.history.back();
    }, 300);
}

// Fonction pour fermer l'alerte avec animation
function closeAlert(element) {
    element.parentElement.classList.add('fade-out');
    setTimeout(function() {
        element.parentElement.remove();
    }, 100);
}
</script>

<?php
// Include database connection
include('DbConnexion.php');

// Fetch parameters
$mecano = $_GET['id'];
$type = $_GET['modif'];
$jourrepos = isset($_POST['repos']) ? $_POST['repos'] : null;

// Function to handle success or failure message and redirection
function redirectWithMessage($message, $redirectURL) {
    $alertType = strpos($message, 'نجاح') !== false ? 'success' : 'error';
    $iconClass = $alertType === 'success' ? 'icon-check' : 'icon-error';
    $iconSymbol = $alertType === 'success' ? '✓' : '✕';
    
    echo '<div class="alert-modern ' . $alertType . '" align="center">
            <div class="alert-icon ' . $iconClass . '">' . $iconSymbol . '</div>
            <div class="alert-content">
                <div class="alert-title">
                    ' . ($alertType === 'success' ? 'نجاح العملية' : 'خطأ') . '
                </div>
                <div class="alert-message">' . $message . '</div>
            </div>
            <button class="close-btn" onclick="closeAlert(this)">&times;</button>
          </div>';
    
    echo '<script>
            setTimeout(function() {
                window.location.href = "' . $redirectURL . '";
            }, 800);  // Redirect after 0.8 seconds
          </script>';
}

if ($type == 0 || $type == 1) {
    // Update for 'numpermis' type (type 0 or 1)
    $query = "UPDATE cartes SET numcarte=?, dateemission=?, finvalidite=? WHERE Mecano=? AND type=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssi", $_POST['numpermis'], $_POST['dateemission'], $_POST['finvalidite'], $mecano, $type);
} elseif ($type == 2) {
    // Update for 'stuf' type (type 2)
    $query = "UPDATE stuf SET jrepos=?, pointagemachine=? WHERE Mecano=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $jourrepos, $_POST['pointagemachine'], $mecano);
} elseif ($type == 3) {
    // Update for 'autreconge' type (type 3)
    $valide = isset($_POST['flexCheckChecked']) ? 0 : 1;
    $query = "UPDATE autreconge SET datedebut=?, datefin=?, commentaire=?,type2=?,valide=? WHERE id=? AND type=6";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $_POST['debut'], $_POST['fin'], $_POST['observ'], $_POST['TypeRepos'], $valide, $mecano);
} elseif ($type == 4) {
    // Update for 'certificats' type (type 4)
    $valide = isset($_POST['flexCheckCheckedcertif']) ? 0 : 1;
    $query = "UPDATE certificats SET datecertificat=?, numcertifcat=?, DateFin=?, Observation=?, Etat=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssii", $_POST['debut'], $_POST['numcertif'], $_POST['fin'], $_POST['observ'], $valide, $mecano);
} else {
    // If no valid type
    redirectWithMessage("لم يقع تحيين المعطيات ، الرجاء المحاولة لاحقا", '/GestionAgences/c_permis.php');
    exit;
}

// Execute query and handle success or failure
if ($stmt->execute()) {
    // Determine redirection URL based on 'type'
    $redirectURL = '';
    $message = 'تمّ تحيين المعطيات بنجاح';

    switch ($type) {
        case 1:
            $redirectURL = '/GestionAgences/Badges.php?type=1';
            break;
        case 0:
            $redirectURL = '/GestionAgences/Badges.php?type=0';
            break;
        case 2:
            $redirectURL = '/GestionAgences/c_agents.php';
            break;
        case 3:
            $redirectURL = '/GestionAgences/c_AccTravail.php';
            break;
        case 4:
            $redirectURL = '/GestionAgences/c_certifmedtravail.php';
            break;
    }

    // Redirect after successful update
    redirectWithMessage($message, $redirectURL);
} else {
    // If query fails, show failure message and redirect
    redirectWithMessage("لم يقع تحيين المعطيات ، الرجاء المحاولة لاحقا", '/GestionAgences/c_permis.php');
}

// Close statement and connection
$stmt->close();
$conn->close();
?>

</body>
</html>