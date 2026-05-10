<?php
// controllers/EmployeeController.php
require_once __DIR__ . '/../models/EmployeeModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class EmployeeController {
    private $employeeModel;
    private $userModel;
    private $db;
    
    public function __construct() {
        session_start();
        $this->employeeModel = new EmployeeModel();
        $this->userModel = new UserModel();
        $this->db = Database::getInstance()->getConnection();
        
        // Vérification de l'authentification
        if (!isset($_SESSION['congidGA'])) {
            header('Location: index.php');
            exit();
        }
    }
    
    /**
     * Affiche la liste des employés
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
        $data = $this->employeeModel->getEmployeesList($departements, $isAdmin);
        $departments = $this->employeeModel->getDepartments($departements, $isAdmin);
        
        // Chargement de la vue
        $this->render('employees/index', [
            'employees' => $data['employees'],
            'stats' => $data['stats'],
            'departments' => $departments,
            'isAdmin' => $isAdmin,
            'statusMapping' => EmployeeModel::STATUS_MAPPING
        ]);
    }
    
    /**
     * Affiche le formulaire d'ajout d'employé (AJAX)
     */
    public function add() {
        // Récupérer les données nécessaires pour les formulaires
        $titres = $this->employeeModel->getTitres();
        $services = $this->employeeModel->getServices();
        $departments = $this->employeeModel->getDepartments(null, true);
        
        $this->render('employees/add', [
            'titres' => $titres,
            'services' => $services,
            'departments' => $departments
        ]);
    }
    
    /**
     * Affiche le formulaire de modification d'employé (AJAX)
     */
    public function edit() {
        $mecano = isset($_GET['mecano']) ? intval($_GET['mecano']) : 0;
        
        if ($mecano == 0) {
            echo '<div class="alert alert-danger">Paramètres invalides</div>';
            return;
        }
        
        $employeeInfo = $this->employeeModel->getEmployeeInfo($mecano);
        $titres = $this->employeeModel->getTitres();
        $services = $this->employeeModel->getServices();
        $departments = $this->employeeModel->getDepartments(null, true);
        
        $this->render('employees/edit', [
            'employee' => $employeeInfo,
            'titres' => $titres,
            'services' => $services,
            'departments' => $departments
        ]);
    }
    
    /**
     * Sauvegarde un nouvel employé (AJAX)
     */
/**
 * Sauvegarde un nouvel employé (AJAX)
 */
/**
 * Sauvegarde un nouvel employé (Version avec session et redirection)
 */
public function save() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?action=employees');
        exit();
    }
    
    try {
        // Récupération et nettoyage des données
        $mecano = trim($_POST['mecano'] ?? '');
        $nomprenom = trim($_POST['nomprenom'] ?? '');
        $cin = trim($_POST['cin'] ?? '');
        $Dnaissance = $_POST['Dnaissance'] ?? '';
        $Drecrutement = $_POST['Drecrutement'] ?? '';
        $grade = $_POST['grade'] ?? '';
        $dep = $_POST['dep'] ?? '';
        $echelle = $_POST['echelle'] ?? '';
        $echelon = $_POST['echelon'] ?? '';
        $service = $_POST['service'] ?? '';
        $etat = $_POST['etat'] ?? '';
        $restconge = $_POST['restconge'] ?? 0;
        $soldeconge = $_POST['soldeconge'] ?? 0;
        $fil = $_POST['fil'] ?? '';
        $Jourrepos = $_POST['Jourrepos'] ?? '10';
        $pointage = $_POST['pointage'] ?? '0';
        $Dpointage = ($pointage == '1') ? ($_POST['Dpointage'] ?? null) : null;
        $lait = $_POST['lait'] ?? '1';
        $codesocial = $_POST['codesocial'] ?? '';
        $codeassurance = $_POST['codeassurance'] ?? '';
        $statut = $_POST['statut'] ?? '';
        $numpers = $_POST['numpers'] ?? '';
        $sexe = $_POST['sexe'] ?? 'M';
        
        // Vérification des données essentielles
        if (empty($mecano) || empty($nomprenom) || empty($cin) || 
            empty($Dnaissance) || empty($Drecrutement)) {
            $_SESSION['error_message'] = 'الرجاء تعبئة جميع الحقول الإلزامية';
            header('Location: index.php?action=employees');
            exit();
        }
        
        // Vérifier si le mecano existe déjà
        $checkQuery = "SELECT COUNT(*) as count FROM stuf WHERE mecano = ? OR cin = ?";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bind_param("ss", $mecano, $cin);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        $row = $result->fetch_assoc();
        $checkStmt->close();
        
        if ($row['count'] > 0) {
            $_SESSION['error_message'] = '⚠️ رقم الأجير أو رقم بطاقة التعريف موجود مسبقاً';
            header('Location: index.php?action=employees');
            exit();
        }
        
        // Démarrer la transaction
        $this->db->begin_transaction();
        
        // Insertion dans stuf
        $query = "INSERT INTO stuf (
            mecano, nom, titre, dep, daten, daterec, cin, statut, numpers, echelle, degree, idservice,
            contrastage, anneeprec, anneeactu, etat, fil, jrepos, pointagemachine, lait, sexe, date_debut_pointage
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $etatValue = 2;
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            "ssssssssssssssssssssss",
            $mecano, $nomprenom, $grade, $dep, 
            $Dnaissance, $Drecrutement, $cin, $statut,
            $numpers, $echelle, $echelon, $service,
            $etat, $restconge, $soldeconge, $etatValue, 
            $fil, $Jourrepos, $pointage, $lait, 
            $sexe, $Dpointage
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'insertion dans stuf: " . $stmt->error);
        }
        $stmt->close();
        
        // Insertion dans cartes si nécessaire
        if (in_array($grade, [6, 7, 8])) {
            $queryCard = "INSERT INTO cartes (mecano, type) VALUES (?, 0), (?, 1)";
            $stmtCard = $this->db->prepare($queryCard);
            $stmtCard->bind_param("ss", $mecano, $mecano);
            if (!$stmtCard->execute()) {
                throw new Exception("Erreur lors de l'insertion dans cartes");
            }
            $stmtCard->close();
        }
        
        // Insertion dans social
        $querySocial = "INSERT INTO social (mecano, ncnss, nassurance) VALUES (?, ?, ?)";
        $stmtSocial = $this->db->prepare($querySocial);
        $stmtSocial->bind_param("sss", $mecano, $codesocial, $codeassurance);
        if (!$stmtSocial->execute()) {
            throw new Exception("Erreur lors de l'insertion dans social");
        }
        $stmtSocial->close();
        
        // Insertion dans nbconge
        $totalSolde = intval($restconge) + intval($soldeconge);
        $queryNbconge = "INSERT INTO nbconge (mecano, nbj1, nbj2, rest) VALUES (?, ?, ?, ?)";
        $stmtNbconge = $this->db->prepare($queryNbconge);
        $stmtNbconge->bind_param("siii", $mecano, $restconge, $soldeconge, $totalSolde);
        if (!$stmtNbconge->execute()) {
            throw new Exception("Erreur lors de l'insertion dans nbconge");
        }
        $stmtNbconge->close();
        
        // Valider la transaction
        $this->db->commit();
        
        $_SESSION['success_message'] = 'تم إضافة العون بنجاح';
        header('Location: index.php?action=employees');
        exit();
        
    } catch (Exception $e) {
        // Annuler la transaction en cas d'erreur
        $this->db->rollback();
        $_SESSION['error_message'] = 'خطأ: ' . $e->getMessage();
        header('Location: index.php?action=employees');
        exit();
    }
}
    
    /**
     * Met à jour un employé (AJAX)
     */
/**
 * Met à jour un employé
 */
public function update() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?action=employees');
        exit();
    }
    
    try {
        // Récupération et nettoyage des données
        $mecano = trim($_POST['mecano'] ?? '');
        $nomprenom = trim($_POST['nomprenom'] ?? '');
        $cin = trim($_POST['cin'] ?? '');
        $Dnaissance = $_POST['Dnaissance'] ?? '';
        $Drecrutement = $_POST['Drecrutement'] ?? '';
        $grade = $_POST['grade'] ?? '';
        $dep = $_POST['dep'] ?? '';
        $echelle = $_POST['echelle'] ?? '';
        $echelon = $_POST['echelon'] ?? '';
        $service = $_POST['service'] ?? '';
        $etat = $_POST['etat'] ?? '';
        $restconge = $_POST['restconge'] ?? 0;
        $soldeconge = $_POST['soldeconge'] ?? 0;
        $fil = $_POST['fil'] ?? '';
        $Jourrepos = $_POST['Jourrepos'] ?? '10';
        $pointage = $_POST['pointage'] ?? '0';
        $Dpointage = ($pointage == '1') ? ($_POST['Dpointage'] ?? null) : null;
        $lait = $_POST['lait'] ?? '1';
        $codesocial = $_POST['codesocial'] ?? '';
        $codeassurance = $_POST['codeassurance'] ?? '';
        $statut = $_POST['statut'] ?? '';
        $numpers = $_POST['numpers'] ?? '';
        $sexe = $_POST['sexe'] ?? 'M';
        
        // Démarrer la transaction
        $this->db->begin_transaction();
        
        // Mise à jour dans stuf
        $query = "UPDATE stuf SET 
                  nom = ?, titre = ?, dep = ?, daten = ?, daterec = ?, cin = ?, 
                  statut = ?, numpers = ?, echelle = ?, degree = ?, idservice = ?,
                  contrastage = ?, anneeprec = ?, anneeactu = ?, fil = ?, 
                  jrepos = ?, pointagemachine = ?, lait = ?, sexe = ?, date_debut_pointage = ?
                  WHERE mecano = ?";
        
        $etatValue = 2;
        $stmt = $this->db->prepare($query);
        $stmt->bind_param(
            "ssssssssssssssssssssi",
            $nomprenom, $grade, $dep, $Dnaissance, $Drecrutement, $cin, 
            $statut, $numpers, $echelle, $echelon, $service,
            $etat, $restconge, $soldeconge, $fil, 
            $Jourrepos, $pointage, $lait, $sexe, $Dpointage,
            $mecano
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de la mise à jour de stuf");
        }
        $stmt->close();
        
        // Mise à jour dans social
        $querySocial = "UPDATE social SET ncnss = ?, nassurance = ? WHERE mecano = ?";
        $stmtSocial = $this->db->prepare($querySocial);
        $stmtSocial->bind_param("ssi", $codesocial, $codeassurance, $mecano);
        if (!$stmtSocial->execute()) {
            throw new Exception("Erreur lors de la mise à jour de social");
        }
        $stmtSocial->close();
        
        // Mise à jour dans nbconge
        $totalSolde = intval($restconge) + intval($soldeconge);
        $queryNbconge = "UPDATE nbconge SET nbj1 = ?, nbj2 = ?, rest = ? WHERE mecano = ?";
        $stmtNbconge = $this->db->prepare($queryNbconge);
        $stmtNbconge->bind_param("iiii", $restconge, $soldeconge, $totalSolde, $mecano);
        if (!$stmtNbconge->execute()) {
            throw new Exception("Erreur lors de la mise à jour de nbconge");
        }
        $stmtNbconge->close();
        
        // Valider la transaction
        $this->db->commit();
        
        $_SESSION['success_message'] = 'تم تحديث المعلومات بنجاح';
        header('Location: index.php?action=employees');
        exit();
        
    } catch (Exception $e) {
        $this->db->rollback();
        $_SESSION['error_message'] = 'خطأ: ' . $e->getMessage();
        header('Location: index.php?action=employees');
        exit();
    }
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
        
        $data = $this->employeeModel->getEmployeesList($departements, $isAdmin);
        
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="liste_employes_' . date('Y-m-d') . '.xls"');
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        
        echo '<!DOCTYPE html>
        <html dir="rtl" lang="ar">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
            <title>قائمة الأعوان</title>
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
                <h2>قائمة الأعوان</h2>
                <p>تاريخ التصدير: ' . date('Y-m-d H:i:s') . '</p>
            </div>
            <table border="1" cellpadding="5" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الرقم الآلي</th>
                        <th>الإسم واللقب</th>
                        <th>تاريخ الولادة</th>
                        <th>تاريخ الإنتداب</th>
                        <th>الرتبة</th>
                        <th>السلم</th>
                        <th>السلك</th>
                        <th>رقم بطاقة وطنية</th>
                        <th>العمر</th>
                        <th>الأقدمية</th>
                        <th>ض.إجتماعي</th>
                        <th>التأمين</th>
                        <th>وحدة الإرتباط</th>
                        <th>الصفة</th>
                        <th>الجنس</th>
                    </tr>
                </thead>
                <tbody>';
        
        $i = 0;
        foreach ($data['employees'] as $emp) {
            $i++;
            echo '<tr>
                <td>' . $i . '</td>
                <td>' . htmlspecialchars($emp['mecano'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($emp['nom'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($emp['daten'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($emp['daterec'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($emp['titre_libellet'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($emp['echelle'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($emp['silk'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($emp['cin'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . $emp['age'] . ' سنة</td>
                <td>' . $emp['anciennete'] . ' سنة</td>
                <td>' . htmlspecialchars($emp['ncnss'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($emp['nassurance'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($emp['depar'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($emp['status_name'], ENT_QUOTES, 'UTF-8') . '</td>
                <td>' . htmlspecialchars($emp['sexe'], ENT_QUOTES, 'UTF-8') . '</td>
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