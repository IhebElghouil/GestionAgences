<?php
// controllers/MedicalCertificateController.php
require_once __DIR__ . '/../models/MedicalCertificateModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class MedicalCertificateController {
    private $medicalModel;
    private $userModel;
    
    public function __construct() {
        session_start();
        $this->medicalModel = new MedicalCertificateModel();
        $this->userModel = new UserModel();
        
        // Vérification de l'authentification
        if (!isset($_SESSION['congidGA'])) {
            header('Location: index.php');
            exit();
        }
    }
    
    /**
     * Affiche la liste des certificats médicaux
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
        $data = $this->medicalModel->getCertificatesList($departements, $isAdmin);
        $departments = $this->medicalModel->getDepartments();
        
        // Chargement de la vue
        $this->render('medical/index', [
            'certificates' => $data['certificates'],
            'stats' => $data['stats'],
            'departments' => $departments,
            'isAdmin' => $isAdmin
        ]);
    }
    
    /**
     * Affiche le formulaire d'ajout (AJAX)
     */
    public function addForm() {
        $employees = $this->medicalModel->getEmployees();
        $this->render('medical/add', [
            'employees' => $employees
        ]);
    }
    
    /**
     * Ajoute un certificat médical (AJAX)
     */
    public function add() {
        // Définir le header JSON au tout début
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée'], JSON_UNESCAPED_UNICODE);
            exit();
        }
        
        $data = [
            'mecano' => intval($_POST['statut'] ?? 0),
            'datecertificat' => $_POST['datecertif'] ?? '',
            'numcertifcat' => $_POST['numcertif'] ?? '',
            'datefin' => $_POST['finvaliditecertif'] ?? '',
            'observation' => $_POST['observations'] ?? ''
        ];
        
        // Validation
        if (empty($data['mecano']) || empty($data['datecertificat']) || 
            empty($data['numcertifcat']) || empty($data['datefin'])) {
            echo json_encode(['success' => false, 'message' => 'الرجاء تعبئة جميع الحقول الإلزامية'], JSON_UNESCAPED_UNICODE);
            exit();
        }
        
        $result = $this->medicalModel->addCertificate($data);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'تم إضافة الشهادة الطبية بنجاح'], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء إضافة الشهادة الطبية'], JSON_UNESCAPED_UNICODE);
        }
        exit();
    }
    
    /**
     * Récupère le nom d'un employé (AJAX)
     */
    public function getEmployeeName() {
        header('Content-Type: application/json');
        
        $mecano = isset($_POST['mecano']) ? intval($_POST['mecano']) : 0;
        
        if ($mecano == 0) {
            echo json_encode(['nomprenom' => '']);
            exit();
        }
        
        $name = $this->medicalModel->getEmployeeName($mecano);
        echo json_encode(['nomprenom' => $name]);
        exit();
    }
    
    /**
     * Affiche le formulaire de modification (AJAX)
     */
   public function edit() {
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    if ($id == 0) {
        echo '<div class="alert alert-danger">Paramètres invalides</div>';
        return;
    }
    
    $certificate = $this->medicalModel->getCertificateInfo($id);
    
    // Récupérer le nom de l'employé
    if ($certificate && isset($certificate['mecano'])) {
        $employeeName = $this->medicalModel->getEmployeeName($certificate['mecano']);
        $certificate['nom'] = $employeeName;
    }
    
    $this->render('medical/edit', [
        'certificate' => $certificate
    ]);
}
    
    /**
     * Met à jour un certificat médical (AJAX)
     */
    public function update() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée'], JSON_UNESCAPED_UNICODE);
            exit();
        }
        
        $id = intval($_POST['id'] ?? 0);
        $datecertificat = $_POST['datecertificat'] ?? '';
        $numcertifcat = $_POST['numcertifcat'] ?? '';
        $datefin = $_POST['datefin'] ?? '';
        $observation = $_POST['observation'] ?? '';
        $etat = isset($_POST['etat']) ? intval($_POST['etat']) : 1;
        
        // Validation
        if ($id == 0 || empty($datecertificat) || empty($numcertifcat) || empty($datefin)) {
            echo json_encode(['success' => false, 'message' => 'الرجاء تعبئة جميع الحقول الإلزامية'], JSON_UNESCAPED_UNICODE);
            exit();
        }
        
        $result = $this->medicalModel->updateCertificate($id, $datecertificat, $numcertifcat, $datefin, $observation, $etat);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'تم تحديث الشهادة الطبية بنجاح'], JSON_UNESCAPED_UNICODE);
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء تحديث الشهادة الطبية'], JSON_UNESCAPED_UNICODE);
        }
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
        
        $data = $this->medicalModel->getCertificatesList($departements, $isAdmin);
        
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="certificats_medicaux_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        
        // Mapping des statuts
        $statusLabels = [
            'valid' => 'سارية المفعول',
            'expired' => 'منتهية الصلاحية',
            'urgent' => 'عاجلة (1-10 أيام)',
            'today' => 'تنتهي اليوم'
        ];
        
        echo '<!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <title>الشهادات الطبية</title>
            <style>
                body { font-family: "Segoe UI", Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 20px; }
                .header h2 { color: #2c3e50; margin-bottom: 5px; }
                .header p { color: #7f8c8d; font-size: 12px; }
                table { border-collapse: collapse; width: 100%; margin-top: 20px; }
                th { background-color: #2c3e50; color: white; padding: 10px; border: 1px solid #34495e; text-align: center; }
                td { padding: 8px; border: 1px solid #ddd; text-align: center; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>قائمة الشهادات الطبية</h2>
                <p>تاريخ التصدير: ' . date('Y-m-d H:i:s') . '</p>
            </div>
            <table border="1" cellpadding="5" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>الرقم الآلي</th>
                        <th>الإسم و اللقب</th>
                        <th>الرتبة</th>
                        <th>تاريخ الشهادة</th>
                        <th>رقم الشهادة</th>
                        <th>إنتهاء الصلاحية</th>
                        <th>الملاحظات</th>
                        <th>وحدة الإرتباط</th>
                        <th>الحالة</th>
                    </tr>
                </thead>
                <tbody>';
        
        foreach ($data['certificates'] as $cert) {
            $statusLabel = $statusLabels[$cert['statusType']] ?? '';
            
            echo '<tr>
                <td>' . htmlspecialchars($cert['mecano'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($cert['nom'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($cert['libellet'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($cert['datecertificat'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($cert['numcertifcat'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($cert['datefin'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($cert['observation'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($cert['depar'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . $statusLabel . '</td>
            </td>';
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