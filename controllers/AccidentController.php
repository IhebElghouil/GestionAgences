<?php
// controllers/AccidentController.php
require_once __DIR__ . '/../models/AccidentModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class AccidentController {
    private $accidentModel;
    private $userModel;
    
    public function __construct() {
        session_start();
        $this->accidentModel = new AccidentModel();
        $this->userModel = new UserModel();
        
        // Vérification de l'authentification
        if (!isset($_SESSION['congidGA'])) {
            header('Location: index.php');
            exit();
        }
    }
    
    /**
     * Affiche la liste des accidents de travail
     */
    public function index() {
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
        $data = $this->accidentModel->getAccidentsList($departements, $isAdmin);
        $departments = $this->accidentModel->getDepartments($departements, $isAdmin);
        
        // Chargement de la vue
        $this->render('accidents/index', [
            'accidents' => $data['accidents'],
            'stats' => $data['stats'],
            'departments' => $departments,
            'isAdmin' => $isAdmin,
            'currentYear' => date('Y')
        ]);
    }
    
    /**
     * Affiche le formulaire de modification
     */
    public function edit() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if ($id == 0) {
            echo '<div class="alert alert-danger">Paramètres invalides</div>';
            return;
        }
        
        $accidentInfo = $this->accidentModel->getAccidentInfo($id);
        
        $this->render('accidents/edit', [
            'accident' => $accidentInfo,
            'typeMapping' => AccidentModel::TYPE_MAPPING
        ]);
    }
    
    /**
     * Enregistre les modifications d'un accident
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=accidents');
            exit();
        }
        
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $datefin = isset($_POST['datefin']) ? $_POST['datefin'] : '';
        $commentaire = isset($_POST['commentaire']) ? trim($_POST['commentaire']) : '';
        $valide = isset($_POST['valide']) ? intval($_POST['valide']) : 0;
        
        $result = $this->accidentModel->updateAccident($id, $datefin, $commentaire, $valide);
        
        if ($result) {
            $_SESSION['success_message'] = 'تم تحديث المعلومات بنجاح';
        } else {
            $_SESSION['error_message'] = 'حدث خطأ أثناء تحديث المعلومات';
        }
        
        header('Location: index.php?action=accidents');
        exit();
    }
    
    /**
     * Enregistre le type d'accident modifié
     */
    public function updateType() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=accidents');
            exit();
        }
        
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $type2 = isset($_POST['type2']) ? intval($_POST['type2']) : 0;
        
        $result = $this->accidentModel->updateAccidentType($id, $type2);
        
        if ($result) {
            $_SESSION['success_message'] = 'تم تحديث نوع الحادث بنجاح';
        } else {
            $_SESSION['error_message'] = 'حدث خطأ أثناء تحديث نوع الحادث';
        }
        
        header('Location: index.php?action=accidents');
        exit();
    }
    
    /**
     * Exporte les données en Excel
     */
    public function export() {
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
        
        $data = $this->accidentModel->getAccidentsList($departements, $isAdmin);
        
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="accidents_travail_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        
        $statusLabels = [
            'status-valid' => 'صالحة',
            'status-expiring' => 'قريبة الإنتهاء',
            'status-expired' => 'منتهية الصلاحية',
            'status-invalid' => 'غير صالحة'
        ];
        
        echo '<!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <title>حوادث الشغل</title>
            <style>
                body { font-family: "Segoe UI", Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h2 { color: #2c3e50; margin-bottom: 5px; }
                .header p { color: #7f8c8d; font-size: 12px; }
                table { border-collapse: collapse; width: 100%; margin-top: 20px; }
                th { background-color: #2c3e50; color: white; padding: 10px; border: 1px solid #34495e; text-align: center; }
                td { padding: 8px; border: 1px solid #ddd; text-align: center; }
                .status-expired { color: #e74c3c; font-weight: bold; }
                .status-expiring { color: #f39c12; font-weight: bold; }
                .status-valid { color: #27ae60; font-weight: bold; }
                .badge-primary { background-color: #3498db; color: white; padding: 4px 8px; border-radius: 12px; }
                .badge-warning { background-color: #f39c12; color: white; padding: 4px 8px; border-radius: 12px; }
                .badge-danger { background-color: #e74c3c; color: white; padding: 4px 8px; border-radius: 12px; }
                .badge-secondary { background-color: #6c757d; color: white; padding: 4px 8px; border-radius: 12px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>متابعة حوادث الشغل</h2>
                <p>تاريخ التصدير: ' . date('Y-m-d H:i:s') . '</p>
            </div>
            <table border="1" cellpadding="5" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>الرقم الآلي</th>
                        <th>الإسم و اللقب</th>
                        <th>الرتبة</th>
                        <th>التاريخ من</th>
                        <th>التاريخ إلى</th>
                        <th>عدد الأيام</th>
                        <th>رقم الضمان الإجتماعي</th>
                        <th>وحدة الإرتباط</th>
                        <th>نوع الحادث</th>
                        <th>ملاحظات</th>
                        <th>السنة</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($data['accidents'] as $accident) {
            $statusLabel = $statusLabels[$accident['statusClass']] ?? '';
            $statusClass = $accident['statusClass'];
            
            echo '<tr>
                <td>' . htmlspecialchars($accident['mecano'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($accident['nom'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($accident['libellet'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($accident['datedebut'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($accident['datefin'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($accident['daydiff'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($accident['ncnss'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($accident['depar'], ENT_QUOTES, 'UTF-8') . '</td>
                <td><span class="' . $accident['type_badge_class'] . '">' . $accident['type_nom'] . '</span></td>
                <td>' . htmlspecialchars($accident['commentaire'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($accident['anne'], ENT_QUOTES, 'UTF-8') . '</td>
                <td class="' . $statusClass . '">' . $statusLabel . '</td>
            </tr>';
        }
        
        echo '    </tbody>
            </table>
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