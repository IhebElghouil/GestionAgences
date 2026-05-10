<?php
// controllers/CongesController.php
require_once __DIR__ . '/../models/CongesModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class CongesController {
    private $congesModel;
    private $userModel;
    
    public function __construct() {
        session_start();
        $this->congesModel = new CongesModel();
        $this->userModel = new UserModel();
        
        // Vérification de l'authentification
        if (!isset($_SESSION['congidGA'])) {
            header('Location: index.php');
            exit();
        }
    }
    
    public function index() {
        $type = isset($_GET['typeSuivie']) ? intval($_GET['typeSuivie']) : CongesModel::TYPE_EXCEPTIONNEL;
        
        // Validation du type
        if (!isset(CongesModel::TYPE_MAPPING[$type])) {
            $type = CongesModel::TYPE_EXCEPTIONNEL;
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
        $data = $this->congesModel->getCongesList($type, $departements, $isAdmin);
        
        $typeInfo = CongesModel::TYPE_MAPPING[$type];
        
        // Chargement de la vue
        $this->render('conges/index', [
            'type' => $type,
            'typeInfo' => $typeInfo,
            'conges' => $data['conges'],
            'stats' => $data['stats'],
            'isAdmin' => $isAdmin,
            'currentYear' => date('Y')
        ]);
    }
    
    /**
     * Affiche les congés annuels
     */
    public function annualLeaves() {
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
        
        // Année sélectionnée
        $selectedYear = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
        
        // Récupération des années disponibles
        $availableYears = $this->congesModel->getAvailableYears();
        
        // Récupération des données
        $data = $this->congesModel->getAnnualLeaves($selectedYear, $departements, $isAdmin);
        
        $this->render('conges/annual', [
            'leaves' => $data['leaves'],
            'stats' => $data['stats'],
            'selectedYear' => $selectedYear,
            'availableYears' => $availableYears,
            'isAdmin' => $isAdmin
        ]);
    }
    
    /**
     * Affiche le solde des congés restants
     */
    public function remainingLeaves() {
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
        $data = $this->congesModel->getRemainingLeaves($departements, $isAdmin);
        
        $this->render('conges/remaining', [
            'remainingLeaves' => $data['remaining_leaves'],
            'stats' => $data['stats']
        ]);
    }
    
    /**
     * Exporte les données en Excel
     */
    public function exportExcel() {
        $type = isset($_GET['type']) ? intval($_GET['type']) : CongesModel::TYPE_EXCEPTIONNEL;
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
        
        $data = $this->congesModel->getCongesList($type, $departements, $isAdmin);
        $typeInfo = CongesModel::TYPE_MAPPING[$type];
        
        // Génération du CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="conges_' . $typeInfo['name'] . '_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM for UTF-8
        
        // En-têtes
        $headers = ['الرقم الآلي', 'الإسم و اللقب', 'التاريخ من', 'التاريخ إلى'];
        if ($type != CongesModel::TYPE_RTT) {
            $headers[] = 'عدد الأيام';
        }
        $headers[] = 'وحدة الإرتباط';
        if ($type != CongesModel::TYPE_MALADIE && $type != CongesModel::TYPE_RTT) {
            $headers[] = 'ملاحظات';
        }
        $headers[] = 'السنة';
        
        fputcsv($output, $headers);
        
        // Données
        foreach ($data['conges'] as $conge) {
            $row = [
                $conge['mecano'],
                $conge['nom'],
                $conge['datedebut'],
                $conge['datefin']
            ];
            
            if ($type != CongesModel::TYPE_RTT) {
                $row[] = $conge['nbj'] ?? '';
            }
            
            $row[] = $conge['depar'];
            
            if ($type != CongesModel::TYPE_MALADIE && $type != CongesModel::TYPE_RTT) {
                $row[] = $conge['commentaire'];
            }
            
            $row[] = $conge['anne'];
            
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit();
    }
    
    /**
     * Exporte les congés annuels en Excel
     */
    public function exportAnnualLeaves() {
        $userId = $_SESSION['congidGA'];
        $isAdmin = ($_SESSION['departement'] === "admin");
        $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
        
        $departements = [];
        if (!$isAdmin) {
            $departements = is_array($_SESSION['departement']) ? 
                           $_SESSION['departement'] : 
                           [$this->userModel->getUserDepartments($userId)];
            if (isset($departements[0]) && is_array($departements[0])) {
                $departements = $departements[0];
            }
        }
        
        $data = $this->congesModel->getAnnualLeaves($year, $departements, $isAdmin);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="conges_annuels_' . $year . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        $headers = ['الرقم الآلي', 'الإسم و اللقب', 'التاريخ من', 'التاريخ إلى', 'عدد الأيام', 'وحدة الإرتباط', 'السنة'];
        fputcsv($output, $headers);
        
        foreach ($data['leaves'] as $leave) {
            $row = [
                $leave['mecano'],
                $leave['nom'],
                $leave['datedebut'],
                $leave['datefin'],
                $leave['nbjours'],
                $leave['depar'],
                $leave['annee']
            ];
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit();
    }
    
    /**
     * Exporte le solde des congés en Excel
     */
    public function exportRemainingLeaves() {
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
        
        $data = $this->congesModel->getRemainingLeaves($departements, $isAdmin);
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="solde_conges_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        $headers = ['الرقم الآلي', 'الإسم و اللقب', 'الرتبة', 'باقي الإجازات N-2', 'مستحقات السنة الحالية', 'الإجازات المستهلكة', 'خصم بعنوان المرض', 'الرصيد الحالي', 'وحدة الإرتباط'];
        fputcsv($output, $headers);
        
        foreach ($data['remaining_leaves'] as $item) {
            $row = [
                $item['mecano'],
                $item['nom'],
                $item['libellet'],
                $item['nbj1'],
                $item['nbj2'],
                $item['congepris'],
                $item['maladie_deduction'],
                $item['current_rest'],
                $item['depar']
            ];
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit();
    }
    
    private function render($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
?>