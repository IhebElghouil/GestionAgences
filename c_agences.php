<?php 
session_start();
if (headers_sent($file, $line)) {
    die("Headers already sent in $file on line $line");
}
require('DbConnexion.php');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="refresh" content="1800">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
            --sanctions-color: #e74c3c;
            --sanctions-dark: #c0392b;
            --detachment-color: #8e44ad;
            --detachment-dark: #6c3483;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 20px;
            padding: 30px;
            margin: 20px auto 30px;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
            max-width: 1400px;
        }

        .header-section::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
        }

        .header-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
            position: relative;
        }

        .header-subtitle {
            font-size: 1.3rem;
            opacity: 0.9;
            text-align: center;
            position: relative;
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Notifications Grid */
        .notifications-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .notification-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
            border-left: 5px solid;
            position: relative;
            overflow: hidden;
        }

        .notification-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }

        .notification-card::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 4px;
        }

        .notification-card.accident {
            border-left-color: var(--danger-color);
        }

        .notification-card.accident::before {
            background: var(--danger-color);
        }

        .notification-card.professional {
            border-left-color: var(--warning-color);
        }

        .notification-card.professional::before {
            background: var(--warning-color);
        }

        .notification-card.driving {
            border-left-color: var(--info-color);
        }

        .notification-card.driving::before {
            background: var(--info-color);
        }

        .notification-card.medical {
            border-left-color: var(--success-color);
        }

        .notification-card.medical::before {
            background: var(--success-color);
        }

        .notification-card.retirement {
            border-left-color: #9b59b6;
        }

        .notification-card.retirement::before {
            background: #9b59b6;
        }

        .notification-card.promotion {
            border-left-color: #1abc9c;
        }

        .notification-card.promotion::before {
            background: #1abc9c;
        }

        .notification-card.career {
            border-left-color: #34495e;
        }

        .notification-card.career::before {
            background: #34495e;
        }

        .notification-card.recruitment {
            border-left-color: #e67e22;
        }

        .notification-card.recruitment::before {
            background: #e67e22;
        }

        .notification-card.sanctions {
            border-left-color: var(--sanctions-color);
        }

        .notification-card.sanctions::before {
            background: var(--sanctions-color);
        }

        .notification-card.detachment {
            border-left-color: var(--detachment-color);
        }

        .notification-card.detachment::before {
            background: var(--detachment-color);
        }

        .notification-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-left: 15px;
            color: white;
        }

        .notification-card.accident .notification-icon {
            background: linear-gradient(135deg, var(--danger-color) 0%, #c0392b 100%);
        }

        .notification-card.professional .notification-icon {
            background: linear-gradient(135deg, var(--warning-color) 0%, #e67e22 100%);
        }

        .notification-card.driving .notification-icon {
            background: linear-gradient(135deg, var(--info-color) 0%, #138d75 100%);
        }

        .notification-card.medical .notification-icon {
            background: linear-gradient(135deg, var(--success-color) 0%, #229954 100%);
        }

        .notification-card.retirement .notification-icon {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        }

        .notification-card.promotion .notification-icon {
            background: linear-gradient(135deg, #1abc9c 0%, #16a085 100%);
        }

        .notification-card.career .notification-icon {
            background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
        }

        .notification-card.recruitment .notification-icon {
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
        }

        .notification-card.sanctions .notification-icon {
            background: linear-gradient(135deg, var(--sanctions-color) 0%, var(--sanctions-dark) 100%);
        }

        .notification-card.detachment .notification-icon {
            background: linear-gradient(135deg, var(--detachment-color) 0%, var(--detachment-dark) 100%);
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-size: 1.2rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--primary-color);
        }

        .notification-count {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 10px;
            line-height: 1;
        }

        .notification-card.accident .notification-count {
            color: var(--danger-color);
        }

        .notification-card.professional .notification-count {
            color: var(--warning-color);
        }

        .notification-card.driving .notification-count {
            color: var(--info-color);
        }

        .notification-card.medical .notification-count {
            color: var(--success-color);
        }

        .notification-card.retirement .notification-count {
            color: #9b59b6;
        }

        .notification-card.promotion .notification-count {
            color: #1abc9c;
        }

        .notification-card.career .notification-count {
            color: #34495e;
        }

        .notification-card.recruitment .notification-count {
            color: #e67e22;
        }

        .notification-card.sanctions .notification-count {
            color: var(--sanctions-color);
        }

        .notification-card.detachment .notification-count {
            color: var(--detachment-color);
        }

        .notification-description {
            color: #6c757d;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .notification-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .notification-card.accident .notification-action {
            background: var(--danger-color);
            color: white;
        }

        .notification-card.professional .notification-action {
            background: var(--warning-color);
            color: white;
        }

        .notification-card.driving .notification-action {
            background: var(--info-color);
            color: white;
        }

        .notification-card.medical .notification-action {
            background: var(--success-color);
            color: white;
        }

        .notification-card.retirement .notification-action {
            background: #9b59b6;
            color: white;
        }

        .notification-card.promotion .notification-action {
            background: #1abc9c;
            color: white;
        }

        .notification-card.career .notification-action {
            background: #34495e;
            color: white;
        }

        .notification-card.recruitment .notification-action {
            background: #e67e22;
            color: white;
        }

        .notification-card.sanctions .notification-action {
            background: var(--sanctions-color);
            color: white;
        }

        .notification-card.detachment .notification-action {
            background: var(--detachment-color);
            color: white;
        }

        .notification-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            color: white;
        }

        /* Employee List Styles */
        .employee-list {
            max-height: 200px;
            overflow-y: auto;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 10px;
            background: var(--light-bg);
        }

        .employee-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            margin-bottom: 8px;
            background: white;
            border-radius: 8px;
            border-left: 3px solid;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .employee-item:hover {
            transform: translateX(-5px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .employee-item:last-child {
            margin-bottom: 0;
        }

        .employee-name {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.9rem;
            flex: 1;
        }

        .employee-date {
            font-size: 0.8rem;
            color: #6c757d;
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 500;
        }

        .employee-promotion {
            font-size: 0.8rem;
            color: white;
            background: var(--success-color);
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .employee-leave-balance {
            font-size: 0.8rem;
            color: white;
            background: #e74c3c;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .promotion-exceptional {
            font-size: 0.8rem;
            color: white;
            background: #9b59b6;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        .career-change {
            font-size: 0.8rem;
            color: var(--primary-color);
            background: #e9ecef;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 500;
            text-align: center;
            direction: ltr;
        }

        .recruitment-date {
            font-size: 0.8rem;
            color: white;
            background: #e67e22;
            padding: 4px 8px;
            border-radius: 6px;
            font-weight: 600;
        }

        /* Sanctions badges */
        .sanction-badge {
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .sanction-badge.open {
            background: #f39c12;
            color: white;
        }

        .sanction-badge.pending {
            background: #3498db;
            color: white;
        }

        .sanction-badge.review {
            background: #9b59b6;
            color: white;
        }

        .sanction-badge.closed {
            background: #27ae60;
            color: white;
        }

        .sanction-discipline {
            font-size: 0.7rem;
            color: #e74c3c;
            font-weight: 600;
            margin-right: 5px;
        }

        .sanction-jours {
            font-size: 0.7rem;
            color: #e67e22;
            font-weight: 600;
        }

        /* Detachment badges */
        .detachment-badge {
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .detachment-badge.active {
            background: #27ae60;
            color: white;
        }

        .detachment-badge.pending {
            background: #f39c12;
            color: white;
        }

        .detachment-badge.expiring {
            background: #e74c3c;
            color: white;
        }

        .detachment-badge.normal {
            background: #3498db;
            color: white;
        }

        .detachment-source {
            font-size: 0.7rem;
            color: #7f8c8d;
            background: #f8f9fa;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .detachment-days {
            font-size: 0.7rem;
            color: #e67e22;
            font-weight: 600;
        }

        .detachment-days.urgent {
            color: #e74c3c;
            font-weight: 700;
        }

       /* Specific border colors for different card types */
        .notification-card.retirement .employee-item {
            border-left-color: #9b59b6;
        }

        .notification-card.promotion .employee-item {
            border-left-color: #1abc9c;
        }

        .notification-card.career .employee-item {
            border-left-color: #34495e;
        }

        .notification-card.recruitment .employee-item {
            border-left-color: #e67e22;
        }

        .notification-card.sanctions .employee-item {
            border-left-color: var(--sanctions-color);
        }

        .notification-card.detachment .employee-item {
            border-left-color: var(--detachment-color);
        }

        /* Scrollbar styling for employee list */
        .employee-list::-webkit-scrollbar {
            width: 6px;
        }

        .employee-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .employee-list::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .employee-list::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1100;
        }

        .toast {
            border-radius: 10px;
            box-shadow: var(--hover-shadow);
            margin-bottom: 10px;
            border: none;
        }

        .toast-header {
            border-radius: 10px 10px 0 0;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #bdc3c7;
            margin-bottom: 20px;
        }

        .empty-state-title {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-weight: 600;
        }

        .empty-state-description {
            color: #6c757d;
            font-size: 1.1rem;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-title {
                font-size: 2rem;
            }
            
            .header-subtitle {
                font-size: 1.1rem;
            }
            
            .notifications-grid {
                grid-template-columns: 1fr;
            }
            
            .notification-card {
                padding: 20px;
            }
            
            .notification-count {
                font-size: 2rem;
            }
            
            .employee-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            
            .employee-name {
                font-size: 0.85rem;
            }
            
            .employee-date,
            .employee-promotion,
            .career-change,
            .recruitment-date {
                align-self: flex-end;
                font-size: 0.75rem;
            }
            
            .employee-list {
                max-height: 150px;
            }
        }

        @media (max-width: 576px) {
            .header-section {
                padding: 20px;
                margin: 10px;
            }
            
            .main-container {
                padding: 0 10px;
            }
            
            .notification-header {
                flex-direction: column;
                text-align: center;
            }
            
            .notification-icon {
                margin-left: 0;
                margin-bottom: 10px;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .notification-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .notification-card:nth-child(1) { animation-delay: 0.1s; }
        .notification-card:nth-child(2) { animation-delay: 0.2s; }
        .notification-card:nth-child(3) { animation-delay: 0.3s; }
        .notification-card:nth-child(4) { animation-delay: 0.4s; }
        .notification-card:nth-child(5) { animation-delay: 0.5s; }
        .notification-card:nth-child(6) { animation-delay: 0.6s; }
        .notification-card:nth-child(7) { animation-delay: 0.7s; }
        .notification-card:nth-child(8) { animation-delay: 0.8s; }
        .notification-card:nth-child(9) { animation-delay: 0.9s; }
        .notification-card:nth-child(10) { animation-delay: 1s; }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .employee-item {
            animation: slideInRight 0.3s ease-out;
        }

        .employee-item:nth-child(1) { animation-delay: 0.1s; }
        .employee-item:nth-child(2) { animation-delay: 0.2s; }
        .employee-item:nth-child(3) { animation-delay: 0.3s; }
        .employee-item:nth-child(4) { animation-delay: 0.4s; }
        .employee-item:nth-child(5) { animation-delay: 0.5s; }

        /* Badge for urgent notifications */
        .urgent-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: var(--danger-color);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 600;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        /* Loading Skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 4px;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
    <title>نظام الإشعارات والتنبيهات</title>
</head>

<body>
<?php



// Configuration
define('ADMIN_EMAIL', 'admin@example.com');
define('SYSTEM_EMAIL', 'system@example.com');
define('EMAIL_ENABLED', false);

// Cache des requêtes fréquentes
function getCachedData($key, $ttl = 60) {
    $cache_file = sys_get_temp_dir() . '/cache_' . md5($key) . '.tmp';
    
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < $ttl) {
        return unserialize(file_get_contents($cache_file));
    }
    
    return false;
}

function setCachedData($key, $data) {
    $cache_file = sys_get_temp_dir() . '/cache_' . md5($key) . '.tmp';
    file_put_contents($cache_file, serialize($data));
}

// Fonction optimisée pour envoyer des emails
function envoyerEmailNotification($sujet, $message, $destinataire = ADMIN_EMAIL) {
    if (!EMAIL_ENABLED) return true;
    
    $headers = "From: " . SYSTEM_EMAIL . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    if (function_exists('popen')) {
        $cmd = "/usr/sbin/sendmail -t -i";
        $mailpipe = popen($cmd, "w");
        if ($mailpipe) {
            fwrite($mailpipe, "To: $destinataire\n");
            fwrite($mailpipe, "Subject: $sujet\n");
            fwrite($mailpipe, "$headers\n");
            fwrite($mailpipe, "\n$message\n");
            pclose($mailpipe);
            return true;
        }
    }
    
    return mail($destinataire, $sujet, $message, $headers);
}

// Vérification de sécurité
if (empty($_SESSION['congidGA'])) {
    header("Location: index.php");
    exit();
}

// Initialisation des variables
$nbAT = $nbCP = $nbPermis = $nMedTrav = 0;
$nbRetirement = $nbPromotion = $nbCareer = $nbRecruitment = 0;
$nbSanctions = 0;
$nbDetachment = 0; // Nouvelle variable pour les détachements
$departmentName = '';
$notifications = [];
$upcomingEvents = [];
$currentEvents = [];
$newRecruits = [];
$openSanctions = [];
$detachmentFiles = []; // Nouvelle variable pour les fichiers de détachement
$isAdmin = ($_SESSION['departement'] === "admin");

// Handle department array properly
if ($isAdmin) {
    $departements = [];
} else {
    $departements = is_array($_SESSION['departement']) ? $_SESSION['departement'] : [$_SESSION['departement']];
}

// Create placeholders and types for prepared statements
if (!$isAdmin && !empty($departements)) {
    $placeholders = implode(',', array_fill(0, count($departements), '?'));
    $types = str_repeat('i', count($departements));
} else {
    $placeholders = '?';
    $types = 'i';
}

// Configuration des requêtes
$queries = [
    'accidents' => [
        'query' => $isAdmin ? 
            "SELECT COUNT(autreconge.mecano) FROM autreconge
             LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
             WHERE type = 6 AND contrastage IN (0, 1, 3) 
             AND datefin < CURDATE() AND valide = 1" :
            "SELECT COUNT(autreconge.mecano) FROM autreconge
             LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
             WHERE type = 6 AND contrastage IN (0, 1, 3) 
             AND stuf.dep IN ($placeholders) AND datefin < CURDATE() AND valide = 1",
        'name' => 'حوادث الشغل',
        'link' => 'c_AccTravail.php',
        'icon' => 'bi-clipboard-pulse',
        'class' => 'accident'
    ],
        
    'professional_cards' => [
        'query' => $isAdmin ?
            "SELECT COUNT(DISTINCT cartes.mecano) 
             FROM cartes
             LEFT JOIN stuf ON cartes.mecano = stuf.mecano
             WHERE contrastage IN (0, 1, 3) 
             AND stuf.titre IN (6, 7, 8) 
             AND cartes.type = 0 
             AND (finvalidite < CURDATE() OR finvalidite = '0000-00-00')" :
            "SELECT COUNT(DISTINCT cartes.mecano) 
             FROM cartes
             LEFT JOIN stuf ON cartes.mecano = stuf.mecano
             WHERE stuf.dep IN ($placeholders)
             AND contrastage IN (0, 1, 3) 
             AND stuf.titre IN (6, 7, 8) 
             AND cartes.type = 0 
             AND (finvalidite < CURDATE() OR finvalidite = '0000-00-00')",
        'name' => 'البطاقات المهنيّة',
        'link' => 'Badges.php?type=0',
        'icon' => 'bi-credit-card-2-front',
        'class' => 'professional'
    ],
        
    'driving_licenses' => [
        'query' => $isAdmin ?
            "SELECT COUNT(DISTINCT cartes.mecano) 
             FROM cartes
             LEFT JOIN stuf ON cartes.mecano = stuf.mecano
             WHERE contrastage IN (0, 1, 3) 
             AND stuf.titre IN (6, 7, 8) 
             AND cartes.type = 1 
             AND (finvalidite < CURDATE() OR finvalidite = '0000-00-00')" :
            "SELECT COUNT(DISTINCT cartes.mecano) 
             FROM cartes
             LEFT JOIN stuf ON cartes.mecano = stuf.mecano
             WHERE stuf.dep IN ($placeholders) 
             AND contrastage IN (0, 1, 3) 
             AND stuf.titre IN (6, 7, 8) 
             AND cartes.type = 1 
             AND (finvalidite < CURDATE() OR finvalidite = '0000-00-00')",
        'name' => 'رخص السياقة',
        'link' => 'Badges.php?type=1',
        'icon' => 'bi-person-vcard',
        'class' => 'driving'
    ],
        
    'medical_certs' => [
        'query' => $isAdmin ?
            "SELECT COUNT(DISTINCT certificats.mecano) 
             FROM certificats
             LEFT JOIN stuf ON certificats.mecano = stuf.mecano
             WHERE certificats.Etat = 1
             AND contrastage IN (0, 1, 3)
             AND (DateFin < CURDATE() OR DateFin = '0000-00-00')" :
            "SELECT COUNT(DISTINCT certificats.mecano) 
             FROM certificats
             LEFT JOIN stuf ON certificats.mecano = stuf.mecano
             WHERE stuf.dep IN ($placeholders) 
             AND certificats.Etat = 1
             AND contrastage IN (0, 1, 3)
             AND (DateFin < CURDATE() OR DateFin = '0000-00-00')",
        'name' => 'الشهادات الطبية',
        'link' => 'c_certifmedtravail.php',
        'icon' => 'bi-heart-pulse',
        'class' => 'medical'
    ],
    
    'retirement' => [
        'query' => $isAdmin ?
            "SELECT COUNT(*) FROM stuf 
             WHERE contrastage IN (0, 1, 3) 
             AND DATE_ADD(daten, INTERVAL 60 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)" :
            "SELECT COUNT(*) FROM stuf 
             WHERE dep IN ($placeholders) 
             AND contrastage IN (0, 1, 3) 
             AND DATE_ADD(daten, INTERVAL 60 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)",
        'name' => 'التقاعد',
        'link' => 'departure.php?tab=retraite',
        'icon' => 'bi-person-walking',
        'class' => 'retirement'
    ],
    
    'promotion' => [
        'query' => $isAdmin ?
            "SELECT COUNT(*) FROM stuf 
             WHERE contrastage IN (0, 1, 3) 
             AND (DATE_ADD(daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
             OR DATE_ADD(daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
             OR DATE_ADD(daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH))" :
            "SELECT COUNT(*) FROM stuf 
             WHERE dep IN ($placeholders) 
             AND contrastage IN (0, 1, 3) 
             AND (DATE_ADD(daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
             OR DATE_ADD(daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
             OR DATE_ADD(daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH))",
        'name' => 'الترقيات',
        'link' => 'promotion_list.php',
        'icon' => 'bi-graph-up-arrow',
        'class' => 'promotion'
    ],
    
    'career' => [
        'query' => $isAdmin ?
            "SELECT COUNT(*) FROM carriere 
             WHERE dateeffet >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)" :
            "SELECT COUNT(*) FROM carriere c
             LEFT JOIN stuf s ON c.mecano = s.mecano
             WHERE s.dep IN ($placeholders) 
             AND c.dateeffet >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)",
        'name' => 'تغييرات المسار الوظيفي',
        'link' => 'career_changes.php',
        'icon' => 'bi-briefcase',
        'class' => 'career'
    ],
    
    'recruitment' => [
        'query' => $isAdmin ?
            "SELECT COUNT(*) FROM stuf 
             WHERE contrastage IN (0, 1, 3) 
             AND daterec >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)" :
            "SELECT COUNT(*) FROM stuf 
             WHERE dep IN ($placeholders) 
             AND contrastage IN (0, 1, 3) 
             AND daterec >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)",
        'name' => 'المنتدبون الجدد',
        'link' => 'new_recruits.php',
        'icon' => 'bi-person-plus',
        'class' => 'recruitment'
    ],
    
    'sanctions' => [
        'query' => $isAdmin ?
            "SELECT COUNT(*) FROM sanctions 
             WHERE statut != 'closed'" :
            "SELECT COUNT(*) FROM sanctions s
             LEFT JOIN stuf ON s.mecano = stuf.mecano
             WHERE stuf.dep IN ($placeholders) 
             AND s.statut != 'closed'",
        'name' => 'العقوبات غير المغلقة',
        'link' => 'sanctions.php',
        'icon' => 'bi-exclamation-triangle',
        'class' => 'sanctions'
    ],
    
    'detachment' => [
        'query' => $isAdmin ?
            "SELECT COUNT(*) FROM detachement 
             WHERE statut != 0" : // Non-closed detachment files (statut = 1 for active, 2 for pending, etc.)
            "SELECT COUNT(*) FROM detachement d
             LEFT JOIN stuf s ON d.mecano = s.mecano
             WHERE s.dep IN ($placeholders) 
             AND d.statut != 0",
        'name' => 'ملفات الإلحاق غير المغلقة',
        'link' => 'departure.php?tab=detachement',
        'icon' => 'bi-box-arrow-right',
        'class' => 'detachment'
    ]
];

// Récupération du nom du département
if (!$isAdmin && !empty($departements)) {
    $cache_key = 'department_' . implode('_', $departements);
    $departmentName = getCachedData($cache_key);
    
    if ($departmentName === false) {
        // Handle multiple departments - show first one or concatenate
        $firstDept = $departements[0];
        $stmt = mysqli_prepare($connection, "SELECT depar FROM dep WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $firstDept);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $deptName);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        
        $departmentName = count($departements) > 1 ? 
            $deptName . ' و ' . (count($departements) - 1) . ' أقسام أخرى' : 
            $deptName;
        
        setCachedData($cache_key, $departmentName);
    }
} else {
    $departmentName = 'بجميع الوكالات و الورشات';
}

// Fonction helper pour exécuter les requêtes avec paramètres multiples
function executeQueryWithParams($connection, $query, $params = []) {
    $stmt = mysqli_prepare($connection, $query);
    
    if (!$stmt) {
        return 0;
    }
    
    if (!empty($params)) {
        $types = str_repeat('i', count($params));
        $bindParams = [$stmt, $types];
        foreach ($params as &$param) {
            $bindParams[] = &$param;
        }
        call_user_func_array('mysqli_stmt_bind_param', $bindParams);
    }
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    
    return $count;
}

// Exécution des requêtes
foreach ($queries as $key => $config) {
    $cache_key = 'notification_' . $key . '_' . ($isAdmin ? 'admin' : implode('_', $departements));
    $count = getCachedData($cache_key, 30);
    
    if ($count === false) {
        if ($isAdmin) {
            $count = executeQueryWithParams($connection, $config['query']);
        } else {
            $count = executeQueryWithParams($connection, $config['query'], $departements);
        }
        
        setCachedData($cache_key, $count);
    }
    
    switch($key) {
        case 'accidents': $nbAT = $count; break;
        case 'professional_cards': $nbCP = $count; break;
        case 'driving_licenses': $nbPermis = $count; break;
        case 'medical_certs': $nMedTrav = $count; break;
        case 'retirement': $nbRetirement = $count; break;
        case 'promotion': $nbPromotion = $count; break;
        case 'career': $nbCareer = $count; break;
        case 'recruitment': $nbRecruitment = $count; break;
        case 'sanctions': $nbSanctions = $count; break;
        case 'detachment': $nbDetachment = $count; break;
    }
    
    if ($count > 0) {
        $notifications[$key] = [
            'count' => $count,
            'name' => $config['name'],
            'link' => $config['link'],
            'icon' => $config['icon'],
            'class' => $config['class']
        ];
        
        // Notification email
        $email_cache_key = 'email_sent_' . $key . '_' . ($isAdmin ? 'admin' : implode('_', $departements));
        if (!getCachedData($email_cache_key, 3600)) {
            $sujet = "تنبيه: هناك " . $count . " " . $config['name'] . " منتهية الصلاحية";
            $message = "هناك " . $count . " " . $config['name'] . " منتهية الصلاحية" . 
                      ($isAdmin ? " في النظام." : " في قسم " . $departmentName . ".") . "<br>";
            $message .= "الرجاء مراجعة <a href='http://votre-site.com/" . $config['link'] . "'>هذا الرابط</a> لاتخاذ الإجراء اللازم.";
            
            if (envoyerEmailNotification($sujet, $message)) {
                setCachedData($email_cache_key, true);
            }
        }
    }
}

// Calcul du total des notifications
$totalNotifications = $nbAT + $nbCP + $nbPermis + $nMedTrav + $nbRetirement + $nbPromotion + $nbCareer + $nbRecruitment + $nbSanctions + $nbDetachment;

// Récupérer les détails des événements à venir avec le solde de congé
if ($nbRetirement > 0) {
    $query = $isAdmin ?
        "SELECT s.mecano, s.nom, DATE_ADD(s.daten, INTERVAL 60 YEAR) as retirement_date, 
                COALESCE(nc.rest, 0) as leave_balance
         FROM stuf s
         LEFT JOIN nbconge nc ON s.mecano = nc.mecano
         WHERE s.contrastage IN (0, 1, 3) 
         AND DATE_ADD(s.daten, INTERVAL 60 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
         ORDER BY retirement_date ASC
         LIMIT 10" :
        "SELECT s.mecano, s.nom, DATE_ADD(s.daten, INTERVAL 60 YEAR) as retirement_date,
                COALESCE(nc.rest, 0) as leave_balance
         FROM stuf s
         LEFT JOIN nbconge nc ON s.mecano = nc.mecano
         WHERE s.dep IN ($placeholders) 
         AND s.contrastage IN (0, 1, 3) 
         AND DATE_ADD(s.daten, INTERVAL 60 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
         ORDER BY retirement_date ASC
         LIMIT 10";
    
    $upcomingEvents['retirement'] = executeDetailedQuery($connection, $query, $isAdmin ? [] : $departements);
}

if ($nbPromotion > 0) {
    $query = $isAdmin ?
        "SELECT s.mecano, s.nom, 
                CASE 
                    WHEN DATE_ADD(s.daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN '20 سنة (ترقية ثانية)'
                    WHEN DATE_ADD(s.daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN '10 سنوات (ترقية أولى)'
                    WHEN DATE_ADD(s.daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN '57 سنة (ترقية استثنائية)'
                END as promotion_type,
                CASE 
                    WHEN DATE_ADD(s.daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN DATE_ADD(s.daterec, INTERVAL 20 YEAR)
                    WHEN DATE_ADD(s.daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN DATE_ADD(s.daterec, INTERVAL 10 YEAR)
                    WHEN DATE_ADD(s.daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN DATE_ADD(s.daten, INTERVAL 57 YEAR)
                END as promotion_date
         FROM stuf s
         WHERE s.contrastage IN (0, 1, 3) 
         AND (DATE_ADD(s.daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
         OR DATE_ADD(s.daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
         OR DATE_ADD(s.daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH))
         ORDER BY promotion_date ASC
         LIMIT 20" :
        "SELECT s.mecano, s.nom, 
                CASE 
                    WHEN DATE_ADD(s.daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN '20 سنة'
                    WHEN DATE_ADD(s.daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN '10 سنوات'
                    WHEN DATE_ADD(s.daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN '57 سنة (ترقية استثنائية)'
                END as promotion_type,
                CASE 
                    WHEN DATE_ADD(s.daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN DATE_ADD(s.daterec, INTERVAL 20 YEAR)
                    WHEN DATE_ADD(s.daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN DATE_ADD(s.daterec, INTERVAL 10 YEAR)
                    WHEN DATE_ADD(s.daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN DATE_ADD(s.daten, INTERVAL 57 YEAR)
                END as promotion_date
         FROM stuf s
         WHERE s.dep IN ($placeholders) 
         AND s.contrastage IN (0, 1, 3) 
         AND (DATE_ADD(s.daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
         OR DATE_ADD(s.daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
         OR DATE_ADD(s.daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH))
         ORDER BY promotion_date ASC
         LIMIT 20";
    
    $upcomingEvents['promotion'] = executeDetailedQuery($connection, $query, $isAdmin ? [] : $departements);
}

// Récupérer les détails des changements de carrière récents
if ($nbCareer > 0) {
    $query = $isAdmin ?
        "SELECT c.mecano, s.nom, c.ancienrang, c.nouveaurang, c.dateeffet, c.commission
         FROM carriere c
         LEFT JOIN stuf s ON c.mecano = s.mecano
         WHERE c.dateeffet >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
         ORDER BY c.dateeffet DESC
         LIMIT 10" :
        "SELECT c.mecano, s.nom, c.ancienrang, c.nouveaurang, c.dateeffet, c.commission
         FROM carriere c
         LEFT JOIN stuf s ON c.mecano = s.mecano
         WHERE s.dep IN ($placeholders) 
         AND c.dateeffet >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
         ORDER BY c.dateeffet DESC
         LIMIT 10";
    
    $currentEvents['career'] = executeDetailedQuery($connection, $query, $isAdmin ? [] : $departements);
}

// Récupérer les détails des nouveaux recrus
if ($nbRecruitment > 0) {
    $query = $isAdmin ?
        "SELECT s.mecano, s.nom, s.daterec, s.titre
         FROM stuf s
         WHERE s.contrastage IN (0, 1, 3) 
         AND s.daterec >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
         ORDER BY s.daterec DESC
         LIMIT 10" :
        "SELECT s.mecano, s.nom, s.daterec, s.titre
         FROM stuf s
         WHERE s.dep IN ($placeholders) 
         AND s.contrastage IN (0, 1, 3) 
         AND s.daterec >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
         ORDER BY s.daterec DESC
         LIMIT 10";
    
    $newRecruits = executeDetailedQuery($connection, $query, $isAdmin ? [] : $departements);
}

// Récupérer les détails des sanctions non clôturées
if ($nbSanctions > 0) {
    $query = $isAdmin ?
        "SELECT s.idsanction, s.mecano, s.nom, s.grade, s.faute, s.sanction, 
                s.datefaute, s.datesanction, s.statut, s.conseil_discipline,
                DATEDIFF(CURDATE(), s.datefaute) as jours_ecoules
         FROM sanctions s
         WHERE s.statut != 'closed'
         ORDER BY 
            CASE s.statut 
                WHEN 'open' THEN 1 
                WHEN 'pending' THEN 2 
                WHEN 'review' THEN 3 
                ELSE 4 
            END,
            s.datefaute ASC
         LIMIT 15" :
        "SELECT s.idsanction, s.mecano, s.nom, s.grade, s.faute, s.sanction, 
                s.datefaute, s.datesanction, s.statut, s.conseil_discipline,
                DATEDIFF(CURDATE(), s.datefaute) as jours_ecoules
         FROM sanctions s
         LEFT JOIN stuf st ON s.mecano = st.mecano
         WHERE st.dep IN ($placeholders) 
         AND s.statut != 'closed'
         ORDER BY 
            CASE s.statut 
                WHEN 'open' THEN 1 
                WHEN 'pending' THEN 2 
                WHEN 'review' THEN 3 
                ELSE 4 
            END,
            s.datefaute ASC
         LIMIT 15";
    
    $openSanctions = executeDetailedQuery($connection, $query, $isAdmin ? [] : $departements);
}

// Récupérer les détails des dossiers de détachement non clôturés
if ($nbDetachment > 0) {
    $query = $isAdmin ?
        "SELECT d.id, d.mecano, d.nomprenom, d.affectation, d.source, d.situation, 
                d.datedetachement, d.periode, d.renouvellemnt1, d.renouvellemnt2, 
                d.renouvellemnt3, d.dossier, d.observations, d.statut,
                DATE_ADD(
                    d.datedetachement, 
                    INTERVAL (
                        COALESCE(NULLIF(TRIM(d.periode), ''), 0) +
                        COALESCE(NULLIF(TRIM(d.renouvellemnt1), ''), 0) +
                        COALESCE(NULLIF(TRIM(d.renouvellemnt2), ''), 0) +
                        COALESCE(NULLIF(TRIM(d.renouvellemnt3), ''), 0)
                    ) YEAR
                ) AS date_fin,
                DATEDIFF(
                    DATE_ADD(
                        d.datedetachement, 
                        INTERVAL (
                            COALESCE(NULLIF(TRIM(d.periode), ''), 0) +
                            COALESCE(NULLIF(TRIM(d.renouvellemnt1), ''), 0) +
                            COALESCE(NULLIF(TRIM(d.renouvellemnt2), ''), 0) +
                            COALESCE(NULLIF(TRIM(d.renouvellemnt3), ''), 0)
                        ) YEAR
                    ),
                    CURDATE()
                ) as jours_restants
         FROM detachement d
         WHERE d.statut != 0
         ORDER BY 
            CASE WHEN d.statut = 1 THEN 0 ELSE 1 END,
            jours_restants ASC
         LIMIT 15" :
        "SELECT d.id, d.mecano, d.nomprenom, d.affectation, d.source, d.situation, 
                d.datedetachement, d.periode, d.renouvellemnt1, d.renouvellemnt2, 
                d.renouvellemnt3, d.dossier, d.observations, d.statut,
                DATE_ADD(
                    d.datedetachement, 
                    INTERVAL (
                        COALESCE(NULLIF(TRIM(d.periode), ''), 0) +
                        COALESCE(NULLIF(TRIM(d.renouvellemnt1), ''), 0) +
                        COALESCE(NULLIF(TRIM(d.renouvellemnt2), ''), 0) +
                        COALESCE(NULLIF(TRIM(d.renouvellemnt3), ''), 0)
                    ) YEAR
                ) AS date_fin,
                DATEDIFF(
                    DATE_ADD(
                        d.datedetachement, 
                        INTERVAL (
                            COALESCE(NULLIF(TRIM(d.periode), ''), 0) +
                            COALESCE(NULLIF(TRIM(d.renouvellemnt1), ''), 0) +
                            COALESCE(NULLIF(TRIM(d.renouvellemnt2), ''), 0) +
                            COALESCE(NULLIF(TRIM(d.renouvellemnt3), ''), 0)
                        ) YEAR
                    ),
                    CURDATE()
                ) as jours_restants
         FROM detachement d
         LEFT JOIN stuf s ON d.mecano = s.mecano
         WHERE s.dep IN ($placeholders) 
         AND d.statut != 0
         ORDER BY 
            CASE WHEN d.statut = 1 THEN 0 ELSE 1 END,
            jours_restants ASC
         LIMIT 15";
    
    $detachmentFiles = executeDetailedQuery($connection, $query, $isAdmin ? [] : $departements);
}

// Fonction pour exécuter les requêtes détaillées
function executeDetailedQuery($connection, $query, $params = []) {
    $stmt = mysqli_prepare($connection, $query);
    
    if (!$stmt) {
        return [];
    }
    
    if (!empty($params)) {
        $types = str_repeat('i', count($params));
        $bindParams = [$stmt, $types];
        foreach ($params as &$param) {
            $bindParams[] = &$param;
        }
        call_user_func_array('mysqli_stmt_bind_param', $bindParams);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }
    
    mysqli_stmt_close($stmt);
    return $data;
}
?>

<div class="main-container">
    <!-- Header Section -->
    <div class="header-section">
        <h1 class="header-title">نظام الإشعارات والتنبيهات</h1>
        <p class="header-subtitle"><?= htmlspecialchars($departmentName) ?></p>
    </div>

    <!-- Notifications Grid -->
    <?php if (!empty($notifications)): ?>
        <div class="notifications-grid">
            <?php foreach ($notifications as $key => $notification): ?>
                <div class="notification-card <?= $notification['class'] ?>">
                    <?php if ($notification['count'] > 5 && $key != 'sanctions' && $key != 'detachment'): ?>
                        <span class="urgent-badge">عاجل</span>
                    <?php elseif (($key == 'sanctions' && $notification['count'] > 3) || ($key == 'detachment' && $notification['count'] > 3)): ?>
                        <span class="urgent-badge">مهم</span>
                    <?php endif; ?>
                    
                    <div class="notification-header">
                        <div class="notification-icon">
                            <i class="bi <?= $notification['icon'] ?>"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title"><?= $notification['name'] ?></h3>
                            <div class="notification-count"><?= $notification['count'] ?></div>
                        </div>
                    </div>
                    
                    <p class="notification-description">
                        <?php if ($key=='promotion'): ?>
                            عدد من الأعوان سيتحصلون على   <?= $notification['name'] ?> في الأشهر الثلاثة القادمة
                        <?php elseif ($key=='retirement'): ?>
                            عدد من الأعوان سيحالون على   <?= $notification['name'] ?> في الأشهر الثلاثة القادمة
                        <?php elseif ($key == 'career'): ?>
                            عدد من التغييرات في المسار الوظيفي تمت خلال الشهر الماضي
                        <?php elseif ($key == 'recruitment'): ?>
                            عدد من المنتدبين الجدد تم توظيفهم خلال الأشهر الثلاثة الماضية
                        <?php elseif ($key == 'sanctions'): ?>
                            عدد العقوبات التي لم يتم إغلاقها بعد وتحتاج إلى متابعة
                        <?php elseif ($key == 'detachment'): ?>
                            عدد ملفات الإلحاق الجارية التي لم يتم إغلاقها بعد
                        <?php else: ?>
                            صلاحية عدد من <?= $notification['name'] ?> منتهية وتحتاج إلى مراجعة فورية
                        <?php endif; ?>
                    </p>
                    
                    <a href="<?= $notification['link'] ?>" class="notification-action">
                        <i class="bi bi-arrow-left"></i>
                        الانتقال للمراجعة
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-check-circle"></i>
            </div>
            <h3 class="empty-state-title">لا توجد تنبيهات حالياً</h3>
            <p class="empty-state-description">
                جميع الوثائق والإجازات سارية المفعول. تمت إدارة جميع المهام بنجاح.
            </p>
        </div>
    <?php endif; ?>

    <!-- Upcoming Events Section -->
    <?php if (!empty($upcomingEvents)): ?>
        <div class="section-header retirement">
            <div class="section-icon">
                <i class="bi bi-calendar-event"></i>
            </div>
            <h2 class="section-title">التغييرات القادمة</h2>
        </div>

        <div class="notifications-grid">
            <?php if (isset($upcomingEvents['retirement'])): ?>
                <div class="notification-card retirement">
                    <div class="notification-header">
                        <div class="notification-icon">
                            <i class="bi bi-person-walking"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">التقاعد خلال الأشهر الثلاثة القادمة</h3>
                            <div class="notification-count"><?= $nbRetirement ?></div>
                        </div>
                    </div>
                    
                    <div class="employee-list">
                        <?php foreach ($upcomingEvents['retirement'] as $employee): ?>
                            <div class="employee-item">
                                <span class="employee-name"><?= htmlspecialchars($employee['nom']) ?></span>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="employee-date"><?= date('Y-m-d', strtotime($employee['retirement_date'])) ?></span>
                                    
                                        <span class="employee-leave-balance mt-1"><?= $employee['leave_balance'] ?> يوم إجازة</span>
                                   
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($upcomingEvents['promotion'])): ?>
                <div class="notification-card promotion">
                    <div class="notification-header">
                        <div class="notification-icon">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">الترقيات المتوقّعة خلال الثلاثة أشهر القادمة</h3>
                            <div class="notification-count"><?= $nbPromotion ?></div>
                        </div>
                    </div>
                    
                    <div class="employee-list">
                        <?php foreach ($upcomingEvents['promotion'] as $employee): ?>
                            <div class="employee-item">
                                <span class="employee-name"><?= htmlspecialchars($employee['nom']) ?></span>
                                <?php if (strpos($employee['promotion_type'], 'استثنائية') !== false): ?>
                                    <span class="promotion-exceptional"><?= $employee['promotion_type'] ?></span>
                                <?php else: ?>
                                    <span class="employee-promotion"><?= $employee['promotion_type'] ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Current Events Section -->
    <?php if (!empty($currentEvents) || !empty($newRecruits) || !empty($openSanctions) || !empty($detachmentFiles)): ?>
        <div class="section-header career">
            <div class="section-icon">
                <i class="bi bi-clock-history"></i>
            </div>
            <h2 class="section-title">التغييرات الحالية</h2>
        </div>

        <div class="notifications-grid">
            <?php if (isset($currentEvents['career'])): ?>
                <div class="notification-card career">
                    <div class="notification-header">
                        <div class="notification-icon">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">التغييرات الأخيرة في المسار المهني</h3>
                            <div class="notification-count"><?= $nbCareer ?></div>
                        </div>
                    </div>
                    
                    <div class="employee-list">
                        <?php foreach ($currentEvents['career'] as $change): ?>
                            <div class="employee-item">
                                <span class="employee-name"><?= htmlspecialchars($change['nom']) ?></span>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="career-change"><?= $change['ancienrang'] ?> → <?= $change['nouveaurang'] ?></span>
                                    <small class="employee-date mt-1"><?= date('Y-m-d', strtotime($change['dateeffet'])) ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($newRecruits)): ?>
                <div class="notification-card recruitment">
                    <div class="notification-header">
                        <div class="notification-icon">
                            <i class="bi bi-person-plus"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">المنتدبون خلال الثلاثة أشهر الماضية</h3>
                            <div class="notification-count"><?= $nbRecruitment ?></div>
                        </div>
                    </div>
                    
                    <div class="employee-list">
                        <?php foreach ($newRecruits as $recruit): ?>
                            <div class="employee-item">
                                <span class="employee-name"><?= htmlspecialchars($recruit['nom']) ?></span>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="recruitment-date"><?= date('Y-m-d', strtotime($recruit['daterec'])) ?></span>
                                    <?php if (!empty($recruit['fonction'])): ?>
                                        <small class="employee-date mt-1"><?= htmlspecialchars($recruit['fonction']) ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Section des sanctions non clôturées -->
            <?php if (isset($openSanctions) && !empty($openSanctions)): ?>
                <div class="notification-card sanctions">
                    <div class="notification-header">
                        <div class="notification-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">تفاصيل العقوبات الجارية</h3>
                            <div class="notification-count"><?= count($openSanctions) ?></div>
                        </div>
                    </div>
                    
                    <div class="employee-list">
                        <?php foreach ($openSanctions as $sanction): 
                            $statut_text = '';
                            $statut_class = '';
                            
                            switch($sanction['statut']) {
                                case 'open':
                                    $statut_text = 'مفتوحة';
                                    $statut_class = 'open';
                                    break;
                                case 'pending':
                                    $statut_text = 'قيد الانتظار';
                                    $statut_class = 'pending';
                                    break;
                                case 'review':
                                    $statut_text = 'مراجعة';
                                    $statut_class = 'review';
                                    break;
                                default:
                                    $statut_text = $sanction['statut'];
                                    $statut_class = '';
                            }
                        ?>
                            <div class="employee-item">
                                <div class="d-flex flex-column w-100">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="employee-name"><?= htmlspecialchars($sanction['nom']) ?></span>
                                        <span class="sanction-badge <?= $statut_class ?>"><?= $statut_text ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="text-muted"><?= htmlspecialchars(mb_substr($sanction['faute'], 0, 30)) ?>...</small>
                                        <?php if ($sanction['conseil_discipline']): ?>
                                            <span class="sanction-discipline">
                                                <i class="bi bi-gavel"></i> مجلس تأديب
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="employee-date">
                                            <i class="bi bi-calendar"></i> 
                                            <?= date('Y-m-d', strtotime($sanction['datefaute'])) ?>
                                        </small>
                                        <?php if ($sanction['jours_ecoules'] > 0): ?>
                                            <span class="sanction-jours">
                                                <i class="bi bi-hourglass"></i> 
                                                <?= $sanction['jours_ecoules'] ?> يوم
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if ($nbSanctions > count($openSanctions)): ?>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                و <?= ($nbSanctions - count($openSanctions)) ?> عقوبات أخرى...
                            </small>
                        </div>
                    <?php endif; ?>
                    
                    <a href="sanctions.php?statut=open,pending,review" class="notification-action mt-2">
                        <i class="bi bi-arrow-left"></i>
                        عرض كل العقوبات
                    </a>
                </div>
            <?php endif; ?>

            <!-- Section des dossiers de détachement non clôturés -->
            <?php if (isset($detachmentFiles) && !empty($detachmentFiles)): ?>
                <div class="notification-card detachment">
                    <div class="notification-header">
                        <div class="notification-icon">
                            <i class="bi bi-box-arrow-right"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">ملفات الإلحاق الجارية</h3>
                            <div class="notification-count"><?= count($detachmentFiles) ?></div>
                        </div>
                    </div>
                    
                    <div class="employee-list">
                        <?php foreach ($detachmentFiles as $detachment): 
                            $statut_text = '';
                            $statut_class = '';
                            $days_class = '';
                            
                            switch($detachment['statut']) {
                                case 1:
                                    $statut_text = 'نشط';
                                    $statut_class = 'active';
                                    break;
                                case 2:
                                    $statut_text = 'قيد الانتظار';
                                    $statut_class = 'pending';
                                    break;
                                default:
                                    $statut_text = 'أخرى';
                                    $statut_class = 'normal';
                            }
                            
                            // Determine if expiring soon (less than 30 days)
                            $jours_restants = isset($detachment['jours_restants']) ? intval($detachment['jours_restants']) : 0;
                            if ($jours_restants <= 30 && $jours_restants > 0) {
                                $days_class = 'urgent';
                            }
                        ?>
                            <div class="employee-item">
                                <div class="d-flex flex-column w-100">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="employee-name"><?= htmlspecialchars($detachment['nomprenom']) ?></span>
                                        <span class="detachment-badge <?= $statut_class ?>"><?= $statut_text ?></span>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="text-muted">
                                            <i class="bi bi-building"></i> 
                                            <?= htmlspecialchars($detachment['affectation'] ?? 'غير محدد') ?>
                                        </small>
                                        <?php if (!empty($detachment['source'])): ?>
                                            <span class="detachment-source">
                                                <i class="bi bi-diagram-3"></i> 
                                                <?= htmlspecialchars($detachment['source']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                        <small class="employee-date">
                                            <i class="bi bi-calendar"></i> 
                                            بداية: <?= date('Y-m-d', strtotime($detachment['datedetachement'])) ?>
                                        </small>
                                        <?php if (!empty($detachment['date_fin']) && $detachment['date_fin'] != '0000-00-00'): ?>
                                            <small class="employee-date">
                                                <i class="bi bi-calendar-check"></i> 
                                                نهاية: <?= date('Y-m-d', strtotime($detachment['date_fin'])) ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if ($jours_restants > 0): ?>
                                        <div class="d-flex justify-content-end mt-1">
                                            <span class="detachment-days <?= $days_class ?>">
                                                <i class="bi bi-hourglass-split"></i> 
                                                <?= $jours_restants ?> يوم متبقي
                                            </span>
                                        </div>
                                    <?php elseif ($jours_restants <= 0 && $jours_restants != 0): ?>
                                        <div class="d-flex justify-content-end mt-1">
                                            <span class="detachment-days urgent">
                                                <i class="bi bi-exclamation-triangle"></i> 
                                                منتهي الصلاحية
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($detachment['observations'])): ?>
                                        <small class="text-muted mt-1">
                                            <i class="bi bi-chat"></i> 
                                            <?= htmlspecialchars(mb_substr($detachment['observations'], 0, 30)) ?>...
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if ($nbDetachment > count($detachmentFiles)): ?>
                        <div class="text-center mt-2">
                            <small class="text-muted">
                                و <?= ($nbDetachment - count($detachmentFiles)) ?> ملفات أخرى...
                            </small>
                        </div>
                    <?php endif; ?>
                    
                    <a href="c_retraite.php?statut=1,2" class="notification-action mt-2">
                        <i class="bi bi-arrow-left"></i>
                        عرض كل ملفات الانتداب
                    </a>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Include Menu -->
    <?php include('menu.php'); ?>
</div>

<script>
// Animation et interactions
document.addEventListener("DOMContentLoaded", function(){
    // Initialize toasts
    var toastElements = document.querySelectorAll(".toast");
    toastElements.forEach(function(element){
        new bootstrap.Toast(element, {
            autohide: true,
            delay: 200
        });
    });

    // Add click animation to notification cards
    document.querySelectorAll('.notification-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (e.target.tagName !== 'A') {
                const link = this.querySelector('a');
                if (link) {
                    link.style.transform = 'scale(0.95)';
                    setTimeout(() => {
                        link.style.transform = '';
                    }, 150);
                }
            }
        });
    });

    // Auto-refresh notifications every 30 seconds
    setInterval(() => {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                console.log('Page refreshed for new notifications');
            })
            .catch(err => console.log('Auto-refresh error:', err));
    }, 30000);
});
</script>
</body>
</html>