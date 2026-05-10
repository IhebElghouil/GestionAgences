<?php
// controllers/BadgesController.php
require_once __DIR__ . '/../models/BadgesModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class BadgesController {
    private $badgesModel;
    private $userModel;
    
    public function __construct() {
        session_start();
        $this->badgesModel = new BadgesModel();
        $this->userModel = new UserModel();
        
        // Vérification de l'authentification
        if (!isset($_SESSION['congidGA'])) {
            header('Location: index.php');
            exit();
        }
    }
    
    /**
     * Affiche la liste des badges
     */
    public function index() {
        $type = isset($_GET['type']) ? intval($_GET['type']) : BadgesModel::TYPE_CARTE_PROFESSIONNELLE;
        
        // Validation du type
        if (!isset(BadgesModel::TYPE_MAPPING[$type])) {
            $type = BadgesModel::TYPE_CARTE_PROFESSIONNELLE;
        }
        
        $userId = $_SESSION['congidGA'];
        $isAdmin = ($_SESSION['departement'] === "admin");
        
        // Récupération des départements de l'utilisateur
        $departements = [];
        if (!$isAdmin) {
            $departements = is_array($_SESSION['departement']) ? 
                           $_SESSION['departement'] : 
                           [$this->userModel->getUserDepartments($userId)];
            if (isset($departements[0]) && is_array($departements[0])) {
                $departements = $departements[0];
            }
        }
        
        // Récupération des données
        $data = $this->badgesModel->getBadgesList($type, $departements, $isAdmin);
        $departments = $this->badgesModel->getDepartments();
        $typeInfo = BadgesModel::TYPE_MAPPING[$type];
        
        // Chargement de la vue
        $this->render('badges/index', [
            'type' => $type,
            'typeInfo' => $typeInfo,
            'badges' => $data['badges'],
            'stats' => $data['stats'],
            'departments' => $departments,
            'isAdmin' => $isAdmin
        ]);
    }
    
    /**
     * Affiche le formulaire de modification
     */
    public function edit() {
        $mecano = isset($_GET['mecano']) ? intval($_GET['mecano']) : 0;
        $type = isset($_GET['type']) ? intval($_GET['type']) : BadgesModel::TYPE_CARTE_PROFESSIONNELLE;
        
        if ($mecano == 0) {
            echo '<div class="alert alert-danger">Paramètres invalides</div>';
            return;
        }
        
        $cardInfo = $this->badgesModel->getCardInfo($mecano, $type);
        $typeInfo = BadgesModel::TYPE_MAPPING[$type];
        
        $this->render('badges/edit', [
            'mecano' => $mecano,
            'type' => $type,
            'typeInfo' => $typeInfo,
            'cardInfo' => $cardInfo
        ]);
    }
    
    /**
     * Enregistre les modifications d'une carte
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=badges&type=' . ($_POST['type'] ?? 0));
            exit();
        }
        
        $mecano = isset($_POST['mecano']) ? intval($_POST['mecano']) : 0;
        $type = isset($_POST['type']) ? intval($_POST['type']) : BadgesModel::TYPE_CARTE_PROFESSIONNELLE;
        $numcarte = isset($_POST['numcarte']) ? trim($_POST['numcarte']) : '';
        $dateemission = isset($_POST['dateemission']) ? $_POST['dateemission'] : '';
        $finvalidite = isset($_POST['finvalidite']) ? $_POST['finvalidite'] : '';
        
        $result = $this->badgesModel->updateCard($mecano, $type, $numcarte, $dateemission, $finvalidite);
        
        if ($result) {
            $_SESSION['success_message'] = 'تم تحديث المعلومات بنجاح';
        } else {
            $_SESSION['error_message'] = 'حدث خطأ أثناء تحديث المعلومات';
        }
        
        header('Location: index.php?action=badges&type=' . $type);
        exit();
    }
    

/**
 * Exporte les données en Excel (Version HTML avec styles)
 */
public function export() {
    $type = isset($_GET['type']) ? intval($_GET['type']) : BadgesModel::TYPE_CARTE_PROFESSIONNELLE;
    $userId = $_SESSION['congidGA'];
    $isAdmin = ($_SESSION['departement'] === "admin");
    
    $departements = [];
    if (!$isAdmin) {
        $departements = is_array($_SESSION['departement']) ? 
                       $_SESSION['departement'] : 
                       [$this->userModel->getUserDepartments($userId)];
        if (isset($departements[0]) && is_array($departements[0])) {
            $departements = $departements[0];
        }
    }
    
    $data = $this->badgesModel->getBadgesList($type, $departements, $isAdmin);
    $typeInfo = BadgesModel::TYPE_MAPPING[$type];
    
    // Headers pour Excel
    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header('Content-Disposition: attachment; filename="badges_' . $typeInfo['name'] . '_' . date('Y-m-d') . '.xls"');
    header('Cache-Control: max-age=0');
    header('Pragma: public');
    
    // Mapping des statuts
    $statusLabels = [
        'valid' => 'سارية المفعول',
        'expiring' => 'قريبة الإنتهاء',
        'expired' => 'منتهية الصلاحية'
    ];
    
    // Couleurs des statuts
    $statusColors = [
        'valid' => '#27ae60',
        'expiring' => '#f39c12',
        'expired' => '#e74c3c'
    ];
    
    // Génération du HTML avec styles
    echo '<!DOCTYPE html>
    <html dir="rtl" lang="ar">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>' . $typeInfo['title'] . '</title>
        <style>
            /* Styles généraux */
            body {
                font-family: "Segoe UI", "Arial", "Tahoma", sans-serif;
                margin: 20px;
                direction: rtl;
            }
            
            /* En-tête */
            .header {
                text-align: center;
                margin-bottom: 20px;
            }
            .header h2 {
                color: #2c3e50;
                margin-bottom: 5px;
                font-size: 24px;
            }
            .header p {
                color: #7f8c8d;
                font-size: 12px;
                margin: 0;
            }
            
            /* Tableau */
            table {
                border-collapse: collapse;
                width: 100%;
                margin-top: 20px;
                font-family: "Segoe UI", "Arial", "Tahoma", sans-serif;
            }
            
            /* En-têtes du tableau */
            th {
                background-color: #2c3e50;
                color: white;
                padding: 10px 8px;
                border: 1px solid #34495e;
                text-align: center;
                font-weight: bold;
                font-size: 13px;
            }
            
            /* Cellules du tableau */
            td {
                padding: 8px;
                border: 1px solid #ddd;
                text-align: center;
                font-size: 12px;
            }
            
            /* Alternance des lignes */
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            
            /* Styles pour les badges de statut */
            .status-valid {
                color: #27ae60;
                font-weight: bold;
                background-color: #e8f8f5;
                display: inline-block;
                padding: 4px 8px;
                border-radius: 4px;
            }
            
            .status-expiring {
                color: #f39c12;
                font-weight: bold;
                background-color: #fef5e7;
                display: inline-block;
                padding: 4px 8px;
                border-radius: 4px;
            }
            
            .status-expired {
                color: #e74c3c;
                font-weight: bold;
                background-color: #fdedec;
                display: inline-block;
                padding: 4px 8px;
                border-radius: 4px;
            }
            
            /* Style pour les dates d\'expiration proches */
            .date-expiring {
                color: #f39c12;
                font-weight: bold;
            }
            
            .date-expired {
                color: #e74c3c;
                font-weight: bold;
            }
            
            /* Style pour les numéros de carte */
            .badge-number {
                font-family: monospace;
                font-size: 11px;
                direction: ltr;
                display: inline-block;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h2>' . $typeInfo['title'] . '</h2>
            <p>تاريخ التصدير: ' . date('Y-m-d H:i:s') . '</p>
        </div>
        <table>
            <thead>
                <tr>
                    <th>الرقم الآلي</th>
                    <th>الإسم و اللقب</th>
                    <th>الرتبة</th>
                    <th>رقم الرخصة</th>
                    <th>تاريخ الإصدار</th>
                    <th>نهاية الصلوحيّة</th>
                    <th>وحدة الإرتباط</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>';
    
    $currentDate = date('Y-m-d');
    
    foreach ($data['badges'] as $badge) {
        $statusLabel = $statusLabels[$badge['status']] ?? '';
        $statusColor = $statusColors[$badge['status']] ?? '#333';
        
        // Déterminer la classe pour la date d'expiration
        $dateClass = '';
        if ($badge['finvalidite'] < $currentDate) {
            $dateClass = 'date-expired';
        } elseif ($badge['jours_restants'] < 60) {
            $dateClass = 'date-expiring';
        }
        
        // Déterminer la classe pour le statut
        $statusClass = '';
        switch ($badge['status']) {
            case 'valid':
                $statusClass = 'status-valid';
                break;
            case 'expiring':
                $statusClass = 'status-expiring';
                break;
            case 'expired':
                $statusClass = 'status-expired';
                break;
        }
        
        echo '<tr>
            <td>' . htmlspecialchars($badge['mecano'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($badge['nom'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($badge['libellet'], ENT_QUOTES, 'UTF-8') . ' ' . htmlspecialchars($badge['situation'], ENT_QUOTES, 'UTF-8') . '</td>
            <td class="badge-number">' . htmlspecialchars($badge['numcarte'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($badge['dateemission'], ENT_QUOTES, 'UTF-8') . '</td>
            <td class="' . $dateClass . '">' . htmlspecialchars($badge['finvalidite'], ENT_QUOTES, 'UTF-8') . '</td>
            <td>' . htmlspecialchars($badge['depar'], ENT_QUOTES, 'UTF-8') . '</td>
            <td class="' . $statusClass . '">' . $statusLabel . '</td>
        </tr>';
    }
    
    echo '    </tbody>
        </table>
        <div style="margin-top: 20px; font-size: 10px; color: #7f8c8d; text-align: center;">
            * هذا التقرير تم إنشاؤه بواسطة نظام متابعة البطاقات
        </div>
    </body>
    </html>';
    exit();
}
    
    private function render($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
?>