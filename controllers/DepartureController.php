<?php
// controllers/DepartureController.php
require_once __DIR__ . '/../models/DepartureModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class DepartureController {
    private $departureModel;
    private $userModel;
    
    public function __construct() {
        session_start();
        $this->departureModel = new DepartureModel();
        $this->userModel = new UserModel();
        
        if (!isset($_SESSION['congidGA'])) {
            header('Location: index.php');
            exit();
        }
    }
    
    public function index() {
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
        
        $departs = $this->departureModel->getDepartList($departements, $isAdmin);
        $detachements = $this->departureModel->getDetachementList($departements, $isAdmin);
        $stats = $this->departureModel->getStats($departements, $isAdmin);
        $employees = $this->departureModel->getEmployees();
        $years = $this->departureModel->getYears();
        $causes = $this->departureModel->getDepartCauses();
        $dossiers = $this->departureModel->getDossierOptions();
        $situations = $this->departureModel->getSituations();
        
        $this->render('departure/index', [
            'departs' => $departs,
            'detachements' => $detachements,
            'stats' => $stats,
            'employees' => $employees,
            'years' => $years,
            'causes' => $causes,
            'dossiers' => $dossiers,
            'situations' => $situations,
            'isAdmin' => $isAdmin
        ]);
    }
    
    public function getUserInfo() {
        header('Content-Type: application/json');
        $mecano = isset($_GET['mecano']) ? intval($_GET['mecano']) : 0;
        
        if ($mecano == 0) {
            echo json_encode(['success' => false, 'error' => 'Paramètre invalide']);
            exit();
        }
        
        $userInfo = $this->departureModel->getUserInfo($mecano);
        
        if ($userInfo) {
            echo json_encode([
                'success' => true,
                'nom' => $userInfo['nom'],
                'departement' => $userInfo['departement'],
                'daterecrutement' => $userInfo['daterec'],
                'restconge' => $userInfo['restconge'],
                'dateretraite' => $userInfo['dateretraite']
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'موظف غير موجود']);
        }
        exit();
    }
    
    public function addDepart() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit();
        }
        
        $data = [
            'mecano' => intval($_POST['mecano'] ?? 0),
            'datedepart' => $_POST['datedepart'] ?? '',
            'restconge' => intval($_POST['restconge'] ?? 0),
            'ministere' => intval($_POST['ministere'] ?? 0),
            'anneedepart' => intval($_POST['anneedepart'] ?? date('Y')),
            'cause' => $_POST['cause'] ?? ''
        ];
        
        if (empty($data['mecano']) || empty($data['datedepart']) || empty($data['cause'])) {
            echo json_encode(['success' => false, 'message' => 'الرجاء تعبئة جميع الحقول الإلزامية']);
            exit();
        }
        
        $result = $this->departureModel->addDepart($data);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'تم إضافة المغادر بنجاح', 'tab' => 'depart']);
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء الإضافة']);
        }
        exit();
    }
    
    public function saveDetachement() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit();
        }
        
        $data = [
            'id' => intval($_POST['id'] ?? 0),
            'mecano' => intval($_POST['mecano'] ?? 0),
            'nomprenom' => $_POST['nomprenom'] ?? '',
            'affectation' => $_POST['affectation'] ?? '',
            'source' => $_POST['source'] ?? '',
            'situation' => $_POST['situation'] ?? '',
            'datedetachement' => $_POST['datedetachement'] ?? '',
            'periode' => intval($_POST['periode'] ?? 0),
            'renouvellemnt1' => intval($_POST['renouvellemnt1'] ?? 0),
            'renouvellemnt2' => intval($_POST['renouvellemnt2'] ?? 0),
            'renouvellemnt3' => intval($_POST['renouvellemnt3'] ?? 0),
            'dossier' => $_POST['dossier'] ?? '',
            'observations' => $_POST['observations'] ?? '',
            'statut' => intval($_POST['statut'] ?? 0)
        ];
        
        if (empty($data['mecano']) || empty($data['nomprenom']) || empty($data['affectation']) || 
            empty($data['source']) || empty($data['situation']) || empty($data['datedetachement'])) {
            echo json_encode(['success' => false, 'message' => 'الرجاء تعبئة جميع الحقول الإلزامية']);
            exit();
        }
        
        $result = $this->departureModel->saveDetachement($data);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'تم حفظ البيانات بنجاح', 'tab' => 'detachement']);
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء الحفظ']);
        }
        exit();
    }
    
    public function deleteDetachement() {
        header('Content-Type: application/json');
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        
        if ($id == 0) {
            echo json_encode(['success' => false, 'message' => 'Paramètre invalide']);
            exit();
        }
        
        $result = $this->departureModel->deleteDetachement($id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'تم الحذف بنجاح', 'tab' => 'detachement']);
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء الحذف']);
        }
        exit();
    }
    
    public function closeDetachement() {
        header('Content-Type: application/json');
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        
        if ($id == 0) {
            echo json_encode(['success' => false, 'message' => 'Paramètre invalide']);
            exit();
        }
        
        $result = $this->departureModel->closeDetachement($id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'تم إغلاق الملف بنجاح', 'tab' => 'detachement']);
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء إغلاق الملف']);
        }
        exit();
    }
    
    public function getDocuments() {
        header('Content-Type: application/json');
        $detachementId = isset($_GET['detachement_id']) ? intval($_GET['detachement_id']) : 0;
        
        if ($detachementId == 0) {
            echo json_encode(['success' => false, 'message' => 'Paramètre invalide']);
            exit();
        }
        
        $documents = $this->departureModel->getDocuments($detachementId);
        echo json_encode(['success' => true, 'documents' => $documents]);
        exit();
    }
    
    public function getDocumentsCount() {
        header('Content-Type: application/json');
        $detachementId = isset($_GET['detachement_id']) ? intval($_GET['detachement_id']) : 0;
        
        if ($detachementId == 0) {
            echo json_encode(['success' => false, 'count' => 0]);
            exit();
        }
        
        $count = $this->departureModel->getDocumentsCount($detachementId);
        echo json_encode(['success' => true, 'count' => $count]);
        exit();
    }
    
    public function uploadDocument() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit();
        }
        
        $detachementId = isset($_POST['detachement_id']) ? intval($_POST['detachement_id']) : 0;
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        
        if ($detachementId == 0 || !isset($_FILES['document']) || $_FILES['document']['error'] != 0) {
            echo json_encode(['success' => false, 'message' => 'Paramètres invalides']);
            exit();
        }
        
        $file = $_FILES['document'];
        $fileName = basename($file['name']);
        $fileSize = $file['size'];
        $fileTmp = $file['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $allowed = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'xls', 'xlsx'];
        if (!in_array($fileExt, $allowed)) {
            echo json_encode(['success' => false, 'message' => 'نوع الملف غير مسموح']);
            exit();
        }
        
        if ($fileSize > 10 * 1024 * 1024) {
            echo json_encode(['success' => false, 'message' => 'حجم الملف يتجاوز 10MB']);
            exit();
        }
        
        $uploadDir = __DIR__ . '/../uploads/detachement_docs/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $newFileName = time() . '_' . uniqid() . '.' . $fileExt;
        $filePath = 'uploads/detachement_docs/' . $newFileName;
        $fullPath = $uploadDir . $newFileName;
        
        if (move_uploaded_file($fileTmp, $fullPath)) {
            $userId = $_SESSION['congidGA'] ?? 0;
            $result = $this->departureModel->addDocument($detachementId, $fileName, $filePath, $fileSize, $fileExt, $description, $userId);
            
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'تم رفع المستند بنجاح', 'tab' => 'detachement']);
            } else {
                unlink($fullPath);
                echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء حفظ البيانات']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء رفع الملف']);
        }
        exit();
    }
    
    public function deleteDocument() {
        header('Content-Type: application/json');
        $documentId = isset($_POST['document_id']) ? intval($_POST['document_id']) : 0;
        
        if ($documentId == 0) {
            echo json_encode(['success' => false, 'message' => 'Paramètre invalide']);
            exit();
        }
        
        $result = $this->departureModel->deleteDocument($documentId);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'تم حذف المستند بنجاح', 'tab' => 'detachement']);
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء الحذف']);
        }
        exit();
    }
    
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
        
        $type = isset($_GET['type']) ? $_GET['type'] : 'all';
        $departs = [];
        $detachements = [];
        
        if ($type == 'depart' || $type == 'all') {
            $departs = $this->departureModel->getDepartList($departements, $isAdmin);
        }
        if ($type == 'detachement' || $type == 'all') {
            $detachements = $this->departureModel->getDetachementList($departements, $isAdmin);
        }
        
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="departure_list_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');
        
        echo '<!DOCTYPE html><html dir="rtl" lang="ar"><head><meta charset="UTF-8"><title>قائمة المغادرين والملحقين</title>
        <style>body{font-family:Arial;margin:20px}h2{text-align:center;color:#2c3e50}table{border-collapse:collapse;width:100%;margin-top:20px}th{background:#2c3e50;color:white;padding:10px;border:1px solid #34495e}td{padding:8px;border:1px solid #ddd;text-align:center}</style>
        </head><body>';
        
        if ($type == 'depart' || $type == 'all') {
            echo '<h2>قائمة المغادرين</h2><table border="1"><thead><tr><th>الرقم الآلي</th><th>الإسم</th><th>وحدة الإرتباط</th><th>تاريخ المغادرة</th><th>الرصيد</th><th>باقي الوزارة</th><th>السنة</th><th>السبب</th></tr></thead><tbody>';
            foreach ($departs as $d) {
                echo '<tr><td>' . htmlspecialchars($d['mecano']) . '</td>
                          <td>' . htmlspecialchars($d['nom']) . '</td>
                          <td>' . htmlspecialchars($d['depar']) . '</td>
                          <td>' . htmlspecialchars($d['datedepart']) . '</td>
                          <td>' . $d['rest'] . '</td>
                          <td>' . $d['cministere'] . '</td>
                          <td>' . $d['annee'] . '</td>
                          <td>' . htmlspecialchars($d['observation']) . '</td></tr>';
            }
            echo '</tbody></table>';
        }
        
        if ($type == 'detachement' || $type == 'all') {
            echo '<h2>قائمة الملحقين</h2><table border="1">
			<thead>
			<tr><th>الرقم الآلي</th><th>الإسم</th><th>جهة الإلحاق</th><th>المؤسّسة الأصلية</th><th>الوضعية</th><th>تاريخ الإلحاق</th><th>الفترة</th><th>الملف</th></tr>
			</thead>
			<tbody>';
            foreach ($detachements as $d) {
                echo '<tr><td>' . htmlspecialchars($d['mecano']) . '</td>
                          <td>' . htmlspecialchars($d['nomprenom']) . '</td>
                          <td>' . htmlspecialchars($d['affectation']) . '</td>
                          <td>' . htmlspecialchars($d['source']) . '</td>
                          <td>' . htmlspecialchars($d['situation']) . '</td>
                          <td>' . htmlspecialchars($d['datedetachement']) . '</td>
                          <td>' . $d['periode'] . '</td>
                          <td>' . htmlspecialchars($d['dossier']) . '</td></tr>';
            }
            echo '</tbody></table>';
        }
        
        echo '</body></html>';
        exit();
    }
    
    private function render($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
?>