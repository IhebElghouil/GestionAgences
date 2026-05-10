<?php
session_start();

// Session check for security
if (!isset($_SESSION['congidGA'])) {
    $_SESSION['congidGA'] = 1;
    $_SESSION['departement'] = 'admin';
}

require_once('connection.php');

// ======================== AJAX HANDLER - MUST BE FIRST ========================
$isAjaxRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                 strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($isAjaxRequest) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    header('Content-Type: application/json');
    
    $action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');
    $mecano = isset($_POST['mecano']) ? trim($_POST['mecano']) : (isset($_GET['mecano']) ? trim($_GET['mecano']) : '');
    
    $connection = $GLOBALS['connection'] ?? null;
    
    if (!$connection) {
        echo json_encode(['success' => false, 'message' => 'خطأ في الاتصال بقاعدة البيانات']);
        exit;
    }
    
    // Handle different AJAX actions
    switch($action) {
        case 'delete_career':
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            if ($id) {
                $stmt = mysqli_prepare($connection, "DELETE FROM carriere WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "i", $id);
                if (mysqli_stmt_execute($stmt)) {
                    echo json_encode(['success' => true, 'message' => 'تم حذف السجل بنجاح']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'خطأ في الحذف']);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo json_encode(['success' => false, 'message' => 'معرّف غير صحيح']);
            }
            exit;
            
        case 'get_career':
            $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
            if ($id) {
                $stmt = mysqli_prepare($connection, "SELECT * FROM carriere WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "i", $id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $career = mysqli_fetch_assoc($result);
                if ($career) {
                    echo json_encode(['success' => true, 'data' => $career]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'لم يتم العثور على البيانات']);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo json_encode(['success' => false, 'message' => 'معرّف غير صحيح']);
            }
            exit;
            
        case 'update_career':
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $decision_number = isset($_POST['decision_number']) ? $_POST['decision_number'] : '';
            $issue_date = isset($_POST['issue_date']) ? $_POST['issue_date'] : '';
            $ancienrang_id = isset($_POST['ancienrang_id']) ? intval($_POST['ancienrang_id']) : 0;
            $nouveaurang_id = isset($_POST['nouveaurang_id']) ? intval($_POST['nouveaurang_id']) : 0;
            $ancienneechelle = isset($_POST['ancienneechelle']) ? $_POST['ancienneechelle'] : '';
            $nouvelechelle = isset($_POST['nouvelechelle']) ? $_POST['nouvelechelle'] : '';
            $anciennegrade = isset($_POST['anciennegrade']) ? $_POST['anciennegrade'] : '';
            $nouveaugrade = isset($_POST['nouveaugrade']) ? $_POST['nouveaugrade'] : '';
            $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
            $dateeffet = isset($_POST['dateeffet']) ? $_POST['dateeffet'] : '';
            $commission = isset($_POST['commission']) ? $_POST['commission'] : '';
            
            $stmt = mysqli_prepare($connection, 
                "UPDATE carriere SET decision_number=?, issue_date=?, ancienrang_id=?, nouveaurang_id=?, 
                 ancienneechelle=?, nouvelechelle=?, anciennegrade=?, nouveaugrade=?, notes=?, dateeffet=?, commission=? 
                 WHERE id=?");
            mysqli_stmt_bind_param($stmt, "ssiisssssssi", 
                $decision_number, $issue_date, $ancienrang_id, $nouveaurang_id,
                $ancienneechelle, $nouvelechelle, $anciennegrade, $nouveaugrade, 
                $notes, $dateeffet, $commission, $id);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true, 'message' => 'تم تحديث البيانات بنجاح']);
            } else {
                echo json_encode(['success' => false, 'message' => 'خطأ في التحديث: ' . mysqli_error($connection)]);
            }
            mysqli_stmt_close($stmt);
            exit;
            
        case 'delete_skill':
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            if ($id) {
                $stmt = mysqli_prepare($connection, "DELETE FROM competences WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "i", $id);
                if (mysqli_stmt_execute($stmt)) {
                    echo json_encode(['success' => true, 'message' => 'تم حذف المهارة بنجاح']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'خطأ في الحذف']);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo json_encode(['success' => false, 'message' => 'معرّف غير صحيح']);
            }
            exit;
            
        case 'delete_certification':
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            if ($id) {
                $stmt = mysqli_prepare($connection, "DELETE FROM certifications WHERE id = ?");
                mysqli_stmt_bind_param($stmt, "i", $id);
                if (mysqli_stmt_execute($stmt)) {
                    echo json_encode(['success' => true, 'message' => 'تم حذف الشهادة بنجاح']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'خطأ في الحذف']);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo json_encode(['success' => false, 'message' => 'معرّف غير صحيح']);
            }
            exit;
            
        case 'save_skill':
            $mecano = isset($_POST['mecano']) ? $_POST['mecano'] : '';
            $nom_competence = isset($_POST['nom_competence']) ? $_POST['nom_competence'] : '';
            $categorie = isset($_POST['categorie']) ? $_POST['categorie'] : '';
            $niveau = isset($_POST['niveau']) ? intval($_POST['niveau']) : 1;
            
            if ($mecano && $nom_competence) {
                $stmt = mysqli_prepare($connection, 
                    "INSERT INTO competences (mecano, nom_competence, categorie, niveau) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "sssi", $mecano, $nom_competence, $categorie, $niveau);
                if (mysqli_stmt_execute($stmt)) {
                    echo json_encode(['success' => true, 'message' => 'تم حفظ المهارة بنجاح']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'خطأ في الحفظ']);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo json_encode(['success' => false, 'message' => 'بيانات غير مكتملة']);
            }
            exit;
            
        case 'save_certification':
            $mecano = isset($_POST['mecano']) ? $_POST['mecano'] : '';
            $nom_certification = isset($_POST['nom_certification']) ? $_POST['nom_certification'] : '';
            $organisme = isset($_POST['organisme']) ? $_POST['organisme'] : '';
            $date_obtention = isset($_POST['date_obtention']) ? $_POST['date_obtention'] : '';
            
            if ($mecano && $nom_certification) {
                $stmt = mysqli_prepare($connection, 
                    "INSERT INTO certifications (mecano, nom_certification, organisme, date_obtention) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "ssss", $mecano, $nom_certification, $organisme, $date_obtention);
                if (mysqli_stmt_execute($stmt)) {
                    echo json_encode(['success' => true, 'message' => 'تم حفظ الشهادة بنجاح']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'خطأ في الحفظ']);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo json_encode(['success' => false, 'message' => 'بيانات غير مكتملة']);
            }
            exit;
            
        case 'save_training':
            $mecano = isset($_POST['mecano']) ? $_POST['mecano'] : '';
            $nom_formation = isset($_POST['nom_formation']) ? $_POST['nom_formation'] : '';
            $organisme_formateur = isset($_POST['organisme_formateur']) ? $_POST['organisme_formateur'] : '';
            $date_debut = isset($_POST['date_debut']) ? $_POST['date_debut'] : '';
            $date_fin = isset($_POST['date_fin']) ? $_POST['date_fin'] : '';
            $statut = isset($_POST['statut']) ? $_POST['statut'] : 'en_cours';
            $progression = isset($_POST['progression']) ? intval($_POST['progression']) : 0;
            
            if ($mecano && $nom_formation) {
                $stmt = mysqli_prepare($connection, 
                    "INSERT INTO formations (mecano, nom_formation, organisme_formateur, date_debut, date_fin, statut, progression) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "ssssssi", $mecano, $nom_formation, $organisme_formateur, $date_debut, $date_fin, $statut, $progression);
                if (mysqli_stmt_execute($stmt)) {
                    echo json_encode(['success' => true, 'message' => 'تم حفظ التكوين بنجاح']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'خطأ في الحفظ']);
                }
                mysqli_stmt_close($stmt);
            } else {
                echo json_encode(['success' => false, 'message' => 'بيانات غير مكتملة']);
            }
            exit;
            
        default:
            // Default: Get employee data
            if (empty($mecano)) {
                echo json_encode(['success' => false, 'message' => 'الرقم الآلي مطلوب']);
                exit;
            }
            
            $isAdmin = ($_SESSION['departement'] === "admin");
            $escapedMecano = mysqli_real_escape_string($connection, $mecano);
            
            // 1) Employee info + leave balance
            $sqlFind = "SELECT stuf.mecano, stuf.nom, stuf.daten, stuf.daterec, titres.libellet, 
                               nbconge.nbj1, nbconge.nbj2, nbconge.rest, ((nbj1+nbj2)-rest) as consomme 
                        FROM stuf 
                        LEFT JOIN titres ON stuf.titre = titres.id 
                        LEFT JOIN nbconge ON nbconge.mecano = stuf.mecano 
                        WHERE stuf.mecano = '$escapedMecano'";
            
            if (!$isAdmin) {
                $sqlFind .= " AND stuf.dep = " . intval($_SESSION['departement']);
            }
            
            $resFind = mysqli_query($connection, $sqlFind);
            
            if (mysqli_num_rows($resFind) == 0) {
                echo json_encode(['success' => false, 'message' => 'لا توجد بيانات لهذا الرقم الآلي']);
                exit;
            }
            
            $emp = mysqli_fetch_assoc($resFind);
            
            // 2) Annual leaves
            $sqlConge = "SELECT id, datedebut, datefin, nbjours FROM conge WHERE mecano = '$escapedMecano' ORDER BY datefin DESC";
            $congeRes = mysqli_query($connection, $sqlConge);
            $conges = [];
            while ($row = mysqli_fetch_assoc($congeRes)) {
                $conges[] = $row;
            }
            
            // 3) Special leaves
            $sqlAutre = "SELECT id, datedebut, datefin, commentaire, type, type2 FROM autreconge WHERE mecano = '$escapedMecano' ORDER BY datedebut DESC";
            $autreRes = mysqli_query($connection, $sqlAutre);
            $autres = [];
            while ($row = mysqli_fetch_assoc($autreRes)) {
                $autres[] = $row;
            }
            
            // 4) Professional scores
            $sqlNotes = "SELECT annee, note, observation, date_saisie FROM notes WHERE mecano = '$escapedMecano' ORDER BY annee DESC";
            $notesRes = mysqli_query($connection, $sqlNotes);
            $notes = [];
            while ($row = mysqli_fetch_assoc($notesRes)) {
                $notes[] = $row;
            }
            
            // 5) Career path
            $careers = [];
            if ($isAdmin) {
                $sqlCar = "SELECT c.*, t1.libellet as ancien_lib, t2.libellet as nouveau_lib 
                           FROM carriere c
                           LEFT JOIN titres t1 ON c.ancienrang_id = t1.id
                           LEFT JOIN titres t2 ON c.nouveaurang_id = t2.id
                           WHERE c.mecano = '$escapedMecano' 
                           ORDER BY c.dateeffet DESC";
                $carRes = mysqli_query($connection, $sqlCar);
                while ($row = mysqli_fetch_assoc($carRes)) {
                    $docCountQuery = mysqli_query($connection, "SELECT COUNT(*) as cnt FROM career_documents WHERE career_id = '".$row['id']."'");
                    $docCount = 0;
                    if ($docCountQuery) {
                        $docRow = mysqli_fetch_assoc($docCountQuery);
                        $docCount = $docRow['cnt'];
                    }
                    $row['doc_count'] = $docCount;
                    $careers[] = $row;
                }
            }
            
            // 6) Skills
            $skills = [];
            $sqlSkills = "SELECT * FROM competences WHERE mecano = '$escapedMecano' ORDER BY categorie, niveau DESC";
            $skillRes = mysqli_query($connection, $sqlSkills);
            $totalSkills = 0;
            $totalLevel = 0;
            while ($row = mysqli_fetch_assoc($skillRes)) {
                $skills[] = $row;
                $totalSkills++;
                $totalLevel += $row['niveau'];
            }
            
            // 7) Certifications
            $certs = [];
            $sqlCerts = "SELECT * FROM certifications WHERE mecano = '$escapedMecano' ORDER BY date_obtention DESC";
            $certRes = mysqli_query($connection, $sqlCerts);
            $certCount = 0;
            while ($row = mysqli_fetch_assoc($certRes)) {
                $certs[] = $row;
                $certCount++;
            }
            
            // 8) Trainings
            $trainings = [];
            $sqlTrain = "SELECT * FROM formations WHERE mecano = '$escapedMecano' ORDER BY date_debut DESC";
            $trainRes = mysqli_query($connection, $sqlTrain);
            $completedTrainings = 0;
            while ($row = mysqli_fetch_assoc($trainRes)) {
                $trainings[] = $row;
                if ($row['statut'] == 'completed') $completedTrainings++;
            }
            
            // Get titles for selects
            $titles = [];
            $titlesRes = mysqli_query($connection, "SELECT * FROM titres ORDER BY libellet");
            while ($t = mysqli_fetch_assoc($titlesRes)) {
                $titles[] = $t;
            }
            
            ob_start();
            ?>
            
            <style>
                .scrollable-container {
                    max-height: 400px;
                    overflow-y: auto;
                    border-radius: 12px;
                }
                .scrollable-container::-webkit-scrollbar {
                    width: 8px;
                }
                .scrollable-container::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 4px;
                }
                .scrollable-container::-webkit-scrollbar-thumb {
                    background: var(--secondary-color, #3498db);
                    border-radius: 4px;
                }
                .badge-custom {
                    padding: 6px 12px;
                    border-radius: 20px;
                    font-weight: 500;
                    font-size: 0.85rem;
                }
                .highlight-number {
                    color: #e74c3c;
                    font-weight: 700;
                    font-size: 1.1rem;
                }
                .type-indicator {
                    display: inline-block;
                    width: 12px;
                    height: 12px;
                    border-radius: 50%;
                    margin-left: 8px;
                }
                .type-formation { background-color: #9b59b6; }
                .type-mission { background-color: #e67e22; }
                .type-reunion { background-color: #3498db; }
                .type-exceptionnelle { background-color: #e74c3c; }
                .type-culturelle { background-color: #1abc9c; }
                .type-syndicale { background-color: #d35400; }
                .type-maladie { background-color: #95a5a6; }
                .type-accident { background-color: #c0392b; }
                .type-compensation { background-color: #f1c40f; }
                .type-maternite { background-color: #8e44ad; }
                .type-sans-solde { background-color: #7f8c8d; }
                .type-suspension { background-color: #2c3e50; }
                .type-naissance { background-color: #e84393; }
                .type-paternite { background-color: #0984e3; }
                .action-btn {
                    width: 32px;
                    height: 32px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    border-radius: 8px;
                    margin: 0 2px;
                    transition: all 0.3s ease;
                    border: none;
                    cursor: pointer;
                }
                .action-btn:hover {
                    transform: scale(1.1);
                }
                .career-actions-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 15px;
                    flex-wrap: wrap;
                    gap: 10px;
                }
                .skill-chart-container {
                    background: white;
                    border-radius: 12px;
                    padding: 20px;
                    box-shadow: var(--card-shadow);
                    margin-bottom: 20px;
                }
                .upload-area {
                    border: 2px dashed var(--secondary-color);
                    border-radius: 8px;
                    padding: 20px;
                    text-align: center;
                    margin: 15px 0;
                    background: var(--light-bg);
                    transition: all 0.3s ease;
                }
                .upload-area:hover {
                    border-color: var(--primary-color);
                    background: #e9f7fe;
                }
                .document-list {
                    max-height: 300px;
                    overflow-y: auto;
                    border: 1px solid #dee2e6;
                    border-radius: 8px;
                    padding: 10px;
                    margin-top: 15px;
                }
                .document-item {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 12px;
                    border-bottom: 1px solid #e9ecef;
                }
                .document-item:last-child {
                    border-bottom: none;
                }
                .modal-header {
                    background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
                    color: white;
                    border-bottom: none;
                }
                .modal-header .btn-close {
                    filter: brightness(0) invert(1);
                }
                .stats-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                    gap: 15px;
                    margin-bottom: 20px;
                }
                .skill-category {
                    font-weight: 600;
                    margin-top: 15px;
                    margin-bottom: 10px;
                    color: var(--primary-color);
                    border-right: 3px solid var(--secondary-color);
                    padding-right: 10px;
                }
                .skill-level {
                    background-color: #e9ecef;
                    border-radius: 10px;
                    height: 8px;
                    overflow: hidden;
                    margin-top: 5px;
                }
                .skill-level-fill {
                    height: 100%;
                    border-radius: 10px;
                    transition: width 0.3s ease;
                }
                .skill-level-beginner { background-color: #f39c12; }
                .skill-level-intermediate { background-color: #3498db; }
                .skill-level-advanced { background-color: #27ae60; }
                .skill-level-expert { background-color: #8e44ad; }
                .certification-item {
                    padding: 12px;
                    border-bottom: 1px solid #e9ecef;
                    margin-bottom: 10px;
                }
                .training-progress {
                    background-color: #e9ecef;
                    border-radius: 10px;
                    height: 6px;
                    overflow: hidden;
                }
                .training-progress-bar {
                    height: 100%;
                    background-color: var(--secondary-color);
                    border-radius: 10px;
                    transition: width 0.3s ease;
                }
                .toast-notification {
                    position: fixed;
                    top: 20px;
                    left: 20px;
                    z-index: 10000;
                    min-width: 300px;
                }
            </style>

            <!-- Toast notification container -->
            <div class="toast-notification" id="toastContainer"></div>

            <!-- Carte informations générales -->
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-user-circle"></i> معلومات عامّة
					<button class="btn btn-sm btn-outline-light ms-2" onclick="confirmAndRedirect('<?php echo $mecano; ?>')">
    <i class="fas fa-sync-alt"></i> تحديث الوضعيّة الإداريّة
</button>
                </div>
                <div class="card-body-custom">
                    <div class="employee-id-pill">
                        <i class="fas fa-id-card me-2"></i>الرقم الآلي: <?php echo htmlspecialchars($emp['mecano']); ?>
                    </div>
                    
                    <div class="stat-grid">
                        <div class="stat-item">
                            <div class="stat-value"><?php echo htmlspecialchars($emp['nom']); ?></div>
                            <div class="stat-label">الإسم و اللقب</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo htmlspecialchars($emp['daten']); ?></div>
                            <div class="stat-label">تاريخ الولادة</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo htmlspecialchars($emp['daterec']); ?></div>
                            <div class="stat-label">تاريخ الإنتداب</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo htmlspecialchars($emp['libellet']); ?></div>
                            <div class="stat-label">الرتبة</div>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>أيام الراحة للسنتين السابقتين</th>
                                    <th>أيام الراحة للسنة الحاليّة</th>
                                    <th>الأيام المتمتّع بها</th>
                                    <th>رصيد الإجازات الحالي</th>
                                   </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="highlight-number"><?php echo intval($emp['nbj1']); ?> يوم</span></td>
                                    <td><span class="highlight-number"><?php echo intval($emp['nbj2']); ?> يوم</span></td>
                                    <td><span class="highlight-number"><?php echo intval($emp['consomme']); ?> يوم</span></td>
                                    <td><span class="highlight-number"><?php echo intval($emp['rest']); ?> يوم</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Section carrière (admin seulement) -->
            <?php if ($isAdmin): ?>
            <div class="info-card" id="careerSection">
                <div class="card-header-custom">
                    <i class="fas fa-briefcase"></i> متابعة الحياة المهنيّة
                    <button class="btn btn-sm btn-outline-light ms-auto" onclick="openAddCareerModal(<?php echo $emp['mecano']; ?>)">
                        <i class="fas fa-plus"></i> إضافة بيانات
                    </button>
					<a href="import_evolution_carriere.php" target=_blank class="btn btn-sm btn-outline-light ms-2">
                                <i class="fas fa-sync-alt"></i> استيراد التطور الوظيفي
                            </a>
                </div>
                <div class="card-body-custom">
                    <div class="scrollable-container">
                        <table class="table custom-table" id="careerTable">
                            <thead>
                                <tr>
                                    <th>عدد المقرر</th>
                                    <th>تاريخ الإصدار</th>
                                    <th>الرتبة القديمة</th>
                                    <th>الرتبة الجديدة</th>
                                    <th>السلم القديم</th>
                                    <th>السلم الجديد</th>
                                    <th>الدرجة القديمة</th>
                                    <th>الدرجة الجديدة</th>
                                    <th>الملاحظات</th>
                                    <th>تاريخ الفاعليّة</th>
                                    <th>اللّجنة</th>
                                    <th>المستندات</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="careerTableBody">
                            <?php if (count($careers) > 0): ?>
                                <?php foreach ($careers as $car): ?>
                                <tr data-career-id="<?php echo $car['id']; ?>">
                                    <td><?php echo htmlspecialchars($car['decision_number'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($car['issue_date'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($car['ancien_lib'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($car['nouveau_lib'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($car['ancienneechelle'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($car['nouvelechelle'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($car['anciennegrade'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($car['nouveaugrade'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($car['notes'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($car['dateeffet'] ?? '-'); ?></td>
                                    <td><?php echo htmlspecialchars($car['commission'] ?? '-'); ?></td>
                                    <td>
                                        <button class="btn btn-info btn-sm action-btn" onclick="viewDocuments(<?php echo $car['id']; ?>)">
                                            <i class="fas fa-file-alt"></i>
                                            <?php if ($car['doc_count'] > 0): ?>
                                                <span class="badge bg-success" style="font-size: 0.6em;"><?php echo $car['doc_count']; ?></span>
                                            <?php endif; ?>
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm action-btn" onclick="editCareer(<?php echo $car['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm action-btn" onclick="deleteCareer(<?php echo $car['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="noCareerData">
                                    <td colspan="13" class="text-center text-muted py-4">لا توجد بيانات للعرض</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Section compétences -->
            <div class="info-card" id="skillsSection">
                <div class="card-header-custom">
                    <i class="fas fa-chart-bar"></i> إدارة المهارات والكفاءات
                </div>
                <div class="card-body-custom">
                    <div class="career-actions-header">
                        <h5 class="mb-0">الملف التقني والكفاءات</h5>
                        <div>
                            <button class="btn btn-success me-2" onclick="openAddSkillModal(<?php echo $emp['mecano']; ?>)">
                                <i class="fas fa-plus me-1"></i>إضافة مهارة
                            </button>
                            <button class="btn btn-primary me-2" onclick="openAddCertificationModal(<?php echo $emp['mecano']; ?>)">
                                <i class="fas fa-certificate me-1"></i>إضافة شهادة
                            </button>
                            <button class="btn btn-info" onclick="openAddTrainingModal(<?php echo $emp['mecano']; ?>)">
                                <i class="fas fa-graduation-cap me-1"></i>إضافة تكوين
                            </button>
                        </div>
                    </div>
                    
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-value" id="totalSkillsCount"><?php echo $totalSkills; ?></div>
                            <div class="stat-label">إجمالي المهارات</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="avgSkillLevel"><?php echo ($totalSkills > 0 ? number_format($totalLevel / $totalSkills, 1) : '0'); ?>/5</div>
                            <div class="stat-label">متوسط مستوى الكفاءة</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="certCount"><?php echo $certCount; ?></div>
                            <div class="stat-label">عدد الشهادات</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value" id="completedTrainingsCount"><?php echo $completedTrainings; ?></div>
                            <div class="stat-label">التكوينات المكتملة</div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-7">
                            <div class="skill-chart-container">
                                <h6 class="mb-3"><i class="fas fa-list-alt me-2"></i>المهارات التقنية</h6>
                                <div id="skillsList">
                                <?php if (count($skills) > 0): ?>
                                    <?php 
                                    $grouped = [];
                                    foreach ($skills as $sk) {
                                        $grouped[$sk['categorie']][] = $sk;
                                    }
                                    foreach ($grouped as $cat => $items): ?>
                                        <div class="skill-category"><?php echo htmlspecialchars($cat); ?> <span class="badge bg-light text-dark"><?php echo count($items); ?></span></div>
                                        <?php foreach ($items as $item): 
                                            $percent = ($item['niveau'] / 5) * 100;
                                            $levelClass = '';
                                            if ($item['niveau'] <= 2) $levelClass = 'skill-level-beginner';
                                            elseif ($item['niveau'] <= 3) $levelClass = 'skill-level-intermediate';
                                            elseif ($item['niveau'] <= 4) $levelClass = 'skill-level-advanced';
                                            else $levelClass = 'skill-level-expert';
                                        ?>
                                        <div class="d-flex justify-content-between align-items-center mb-2" data-skill-id="<?php echo $item['id']; ?>">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between">
                                                    <span><?php echo htmlspecialchars($item['nom_competence']); ?></span>
                                                    <span class="text-muted"><?php echo $item['niveau']; ?>/5</span>
                                                </div>
                                                <div class="skill-level">
                                                    <div class="skill-level-fill <?php echo $levelClass; ?>" style="width: <?php echo $percent; ?>%"></div>
                                                </div>
                                            </div>
                                            <div class="ms-3">
                                                <button class="btn btn-danger btn-sm action-btn" onclick="deleteSkill(<?php echo $item['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-center text-muted py-3" id="noSkillsMessage"><i class="fas fa-info-circle me-2"></i>لا توجد مهارات مسجلة</p>
                                <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-5">
                            <div class="skill-chart-container">
                                <h6 class="mb-3"><i class="fas fa-certificate me-2"></i>الشهادات المحصل عليها</h6>
                                <div id="certificationsList">
                                <?php if (count($certs) > 0): ?>
                                    <?php foreach ($certs as $cert): ?>
                                    <div class="certification-item" data-cert-id="<?php echo $cert['id']; ?>">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <strong><?php echo htmlspecialchars($cert['nom_certification']); ?></strong>
                                                <br><small class="text-muted"><?php echo htmlspecialchars($cert['organisme']); ?></small>
                                                <br><small class="text-muted"><?php echo $cert['date_obtention']; ?></small>
                                            </div>
                                            <div>
                                                <button class="btn btn-danger btn-sm action-btn" onclick="deleteCertification(<?php echo $cert['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-center text-muted py-3" id="noCertificationsMessage"><i class="fas fa-info-circle me-2"></i>لا توجد شهادات مسجلة</p>
                                <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="skill-chart-container mt-3">
                                <h6 class="mb-3"><i class="fas fa-graduation-cap me-2"></i>التكوينات</h6>
                                <div id="trainingsList">
                                <?php if (count($trainings) > 0): ?>
                                    <?php foreach ($trainings as $tr): 
                                        $progress = $tr['progression'] ?? 0;
                                    ?>
                                    <div class="mb-3" data-training-id="<?php echo $tr['id']; ?>">
                                        <div class="d-flex justify-content-between">
                                            <strong><?php echo htmlspecialchars($tr['nom_formation']); ?></strong>
                                            <span class="badge <?php echo ($tr['statut'] == 'completed') ? 'bg-success' : 'bg-warning'; ?>"><?php echo $tr['statut'] ?? 'قيد التنفيذ'; ?></span>
                                        </div>
                                        <small class="text-muted"><?php echo htmlspecialchars($tr['organisme_formateur']); ?></small>
                                        <div class="training-progress mt-1">
                                            <div class="training-progress-bar" style="width: <?php echo $progress; ?>%"></div>
                                        </div>
                                        <small><?php echo $progress; ?>% مكتمل</small>
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-center text-muted py-3" id="noTrainingsMessage"><i class="fas fa-info-circle me-2"></i>لا توجد تكوينات مسجلة</p>
                                <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section الإجازات السنوية -->
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-calendar-alt"></i> متابعة الإجازات السنويّة والأعداد المهنية
                </div>
                <div class="card-body-custom">
                    <div class="row">
                        <div class="col-md-7">
                            <h6 class="mb-3"><i class="fas fa-calendar-check me-2"></i>الإجازات السنوية</h6>
                            <div class="scrollable-container" style="max-height: 300px;">
                                <table class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th>رقم الإجازة</th>
                                            <th>بداية الإجازة</th>
                                            <th>إنتهاء الإجازة</th>
                                            <th>عدد الأيام</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (count($conges) > 0): ?>
                                        <?php foreach ($conges as $c): ?>
                                        <tr>
                                            <td><span><?php echo htmlspecialchars($c['id']); ?></span></td>
                                            <td><?php echo htmlspecialchars($c['datedebut']); ?></td>
                                            <td><?php echo htmlspecialchars($c['datefin']); ?></td>
                                            <td><span class="highlight-number"><?php echo htmlspecialchars($c['nbjours']); ?></span></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">لا توجد إجازات مسجلة</span></td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
									</table>
                                </div>
                            </div>
                       
                        <div class="col-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0"><i class="fas fa-chart-line me-2"></i>الأعداد المهنية</h6>
                                <a href="import_notes.php" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-excel me-1"></i> استيراد الأعداد المهنية
                                </a>
                            </div>
                            <div class="scrollable-container" style="max-height: 300px;">
                                <table class="table custom-table">
                                    <thead>
                                        <tr>
                                            <th>السنة</th>
                                            <th>العدد</th>
                                            <th>الملاحظات</th>
                                            <th>تاريخ الإدخال</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (count($notes) > 0): ?>
                                        <?php foreach ($notes as $n): 
                                            $noteColor = 'success';
                                            if ($n['note'] < 10) $noteColor = 'danger';
                                            elseif ($n['note'] < 14) $noteColor = 'warning';
                                            elseif ($n['note'] < 17) $noteColor = 'info';
                                            else $noteColor = 'success';
                                        ?>
                                        <tr>
                                            <td><span><?php echo htmlspecialchars($n['annee']); ?></span></td>
                                            <td><span class="<?php echo $noteColor; ?>"><?php echo number_format($n['note'], 2); ?>/20</span></td>
                                            <td><?php echo htmlspecialchars($n['observation'] ?? '-'); ?></td>
                                            <td><?php echo isset($n['date_saisie']) ? date('Y-m-d', strtotime($n['date_saisie'])) : '-'; ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-3">لا توجد أعداد مهنية مسجلة</span></span></td>
                                        </tr>
                                    <?php endif; ?>
                                    </tbody>
									</table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section باقي الراحات -->
            <div class="info-card">
                <div class="card-header-custom">
                    <i class="fas fa-umbrella-beach"></i> متابعة باقي الراحات
                </div>
                <div class="card-body-custom">
                    <div class="scrollable-container">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>رقم الراحة</th>
                                    <th>نوع الراحة</th>
                                    <th>بداية الراحة</th>
                                    <th>إنتهاء الراحة</th>
                                    <th>الملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (count($autres) > 0): ?>
                                <?php foreach ($autres as $a): 
                                    $code = '';
                                    $typec = '';
                                    $typeClass = 'type-formation';
                                    
                                    switch($a['type']) {
                                        case 1:
                                            if($a['type2'] == 1) { $code = 'CMi.F'; $typec = 'دورة تكوينيّة'; $typeClass = 'type-formation'; }
                                            elseif($a['type2'] == 3) { $code = 'CMi.M'; $typec = 'مهمّة'; $typeClass = 'type-mission'; }
                                            elseif($a['type2'] == 2) { $code = 'CMi.R'; $typec = 'إجتماع'; $typeClass = 'type-reunion'; }
                                            break;
                                        case 2: $code = 'CEx'; $typec = 'راحة إستثنائية'; $typeClass = 'type-exceptionnelle'; break;
                                        case 3: $code = 'CِCul'; $typec = 'رخصة ثقافيّة'; $typeClass = 'type-culturelle'; break;
                                        case 4: $code = 'CSy'; $typec = 'رخصة نقابيّة'; $typeClass = 'type-syndicale'; break;
                                        case 5: $code = 'CMa'; $typec = 'رخصة مرضيّة'; $typeClass = 'type-maladie'; break;
                                        case 6: $code = 'CAt'; $typec = 'حادث شغل'; $typeClass = 'type-accident'; break;
                                        case 7: $code = 'CRr'; $typec = 'راحة تعويضيّة'; $typeClass = 'type-compensation'; break;
                                        case 8: $code = 'CRr'; $typec = 'عطلة أمومة'; $typeClass = 'type-maternite'; break;
                                        case 9: $code = 'CRr'; $typec = 'عطلة بدون أجر'; $typeClass = 'type-sans-solde'; break;
                                        case 10: $code = 'CRr'; $typec = 'إيقاف عن العمل'; $typeClass = 'type-suspension'; break;
                                        case 11: $code = 'CNaiss'; $typec = 'عطلة ولادة'; $typeClass = 'type-naissance'; break;
                                        case 12: $code = 'CPat'; $typec = 'عطلة أبوّة'; $typeClass = 'type-paternite'; break;
                                        default: $code = 'CGen'; $typec = 'راحة عامة'; $typeClass = 'type-formation';
                                    }
                                ?>
                                <tr>
                                    <td><span><?php echo $code . $a['id']; ?></span></span></span></span></td>
                                    <td>
                                        <span class="type-indicator <?php echo $typeClass; ?>"></span>
                                        <?php echo $typec; ?>
                                    </span></td>
                                    <td><?php echo htmlspecialchars($a['datedebut']); ?></span></span></span></span></td>
                                    <td><?php echo htmlspecialchars($a['datefin']); ?></span></span></span></span></td>
                                    <td><?php echo htmlspecialchars($a['commentaire'] ?? '-'); ?></span></span></span></span></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">لا توجد راحات مسجلة</span></span></span></span></td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
							</table>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $htmlOutput = ob_get_clean();
            echo json_encode(['success' => true, 'html' => $htmlOutput]);
            exit;
    }
}

// ======================== NON-AJAX: DISPLAY HTML PAGE ========================
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique Agents</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
            direction: rtl;
        }
        
        .search-container { max-width: 500px; margin: 0 auto; }
        
        .search-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            background: white;
        }
        
        .search-input {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 16px;
        }
        
        .search-input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .info-card {
            background: white;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            color: white;
            padding: 15px 25px;
            font-weight: 600;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .card-body-custom { padding: 25px; }
        
        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .custom-table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 12px;
            text-align: center;
        }
        
        .custom-table tbody td {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
            text-align: center;
        }
        
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-item {
            background: #f8fafc;
            padding: 15px;
            border-radius: 12px;
            text-align: center;
            border: 1px solid #eef2f8;
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .stat-label { font-size: 0.85rem; color: #6c757d; }
        
        .employee-id-pill {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            display: inline-block;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin: 0 2px;
            border: none;
            cursor: pointer;
        }
        
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .loading-spinner-custom {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
        }
        
        .modal {
            z-index: 10050;
        }
        .modal-backdrop {
            z-index: 10040;
        }
        
        @media (max-width: 768px) {
            body { padding: 10px; }
            .card-body-custom { padding: 15px; }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        #contentSection {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        
        #contentSection.show {
            opacity: 1;
        }
        
        .toast-notification {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 10000;
            min-width: 300px;
        }
        
        .toast-custom {
            background: white;
            border-radius: 8px;
            padding: 12px 20px;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            animation: slideInRight 0.3s ease;
            border-right: 4px solid;
        }
        
        .toast-success { border-right-color: #28a745; }
        .toast-error { border-right-color: #dc3545; }
        .toast-info { border-right-color: #17a2b8; }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
    </style>
	<script>

// Fonctions de validation
function validateScaleInput(input) {
    const value = parseInt(input.value);
    const min = parseInt(input.min);
    const max = parseInt(input.max);
    
    if (isNaN(value)) {
        input.value = min;
    } else if (value < min) {
        input.value = min;
        alert('القيمة يجب أن تكون أكبر من أو تساوي ' + min);
    } else if (value > max) {
        input.value = max;
        alert('القيمة يجب أن تكون أصغر من أو تساوي ' + max + ' (حسب سلم الرتبة المختارة)');
    }
}

function validateGradeInput(input) {
    const value = parseInt(input.value);
    const min = parseInt(input.min);
    const max = parseInt(input.max);
    
    if (isNaN(value)) {
        input.value = min;
    } else if (value < min) {
        input.value = min;
        alert('القيمة يجب أن تكون أكبر من أو تساوي ' + min);
    } else if (value > max) {
        input.value = max;
        alert('القيمة يجب أن تكون أصغر من أو تساوي ' + max);
    }
}

// Fonction pour mettre à jour le plafond des échelles
function updatePlafond(selectedValue, inputId) {
    console.log('Selected value:', selectedValue, 'for input:', inputId);
    
    let select;
    
    if (inputId === 'old_scale' || inputId === 'new_scale') {
        select = (inputId === 'old_scale') ? 
                 document.getElementById('old_rank') : 
                 document.getElementById('new_rank');
    } else if (inputId === 'editOldScale' || inputId === 'editNewScale') {
        select = (inputId === 'editOldScale') ? 
                 document.getElementById('editOldRank') : 
                 document.getElementById('editNewRank');
    } else {
        console.error('Unknown input ID:', inputId);
        return;
    }
    
    if (!select) {
        console.error('Select not found');
        return;
    }
    
    const selectedOption = select.options[select.selectedIndex];
    let plafond = 730;
    
    if (selectedOption && selectedOption.value) {
        plafond = selectedOption.getAttribute('data-plafond');
        console.log('Found plafond:', plafond, 'for option:', selectedOption.text);
    } else {
        console.warn('No option selected, using default plafond:', plafond);
    }
    
    const scaleInput = document.getElementById(inputId);
    if (scaleInput) {
        plafond = parseInt(plafond);
        
        scaleInput.max = plafond;
        scaleInput.setAttribute('max', plafond);
        
        validateScaleInput(scaleInput);
        
        const helpText = document.createElement('small');
        helpText.className = 'text-info d-block mt-1';
        helpText.id = inputId + '_help';
        helpText.textContent = 'الحد الأقصى المسموح: ' + plafond;
        
        const oldHelp = document.getElementById(inputId + '_help');
        if (oldHelp) oldHelp.remove();
        
        scaleInput.parentNode.appendChild(helpText);
        
        console.log('Plafond mis à jour pour ' + inputId + ' : ' + plafond);
    } else {
        console.error('Scale input not found:', inputId);
    }
}
function confirmAndRedirect(mecano) {
    if(confirm("هل أنت متأكد من تحديث الحالة?")) {
        // Disable button to prevent multiple clicks
        const btn = event?.target;
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري التحديث...';
        }
        
        // Create form data
        const formData = new FormData();
        formData.append('action', 'update_status');
        formData.append('mecano', mecano);
        
        // Send AJAX request to update_statut.php
        fetch('CareerDependencies/update_statut.php', {  // Changed: now calling update_statut.php directly
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Re-enable button
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-sync-alt"></i> تحديث الوضعيّة الإداريّة';
            }
            
            if (data.success) {
                showToast("✅ تم تحديث الحالة بنجاح", "success");
                
                // Update any status indicators on the page
                updateStatusIndicators(data);
                
                // Refresh the data
                setTimeout(() => {
                    if (typeof searchEmployee === 'function') {
                        searchEmployee();
                    } else {
                        location.reload(); // Fallback to reload if searchEmployee not available
                    }
                }, 1000);
            } else {
                showToast(data.message || "❌ حدث خطأ في تحديث الحالة", "error");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-sync-alt"></i> تحديث الوضعيّة الإداريّة';
            }
            showToast("❌ حدث خطأ في الاتصال بالخادم", "error");
        });
    }
}
// Optional: Function to update status indicators on the page
function updateStatusIndicators(data) {
    // Update any status badges or indicators
    const statusBadge = document.querySelector('.status-badge');
    if (statusBadge) {
        statusBadge.textContent = data.new_status || 'محدث';
        statusBadge.className = `status-badge badge bg-success`;
    }
    
    // Update last update timestamp
    const lastUpdateSpan = document.querySelector('.last-update');
    if (lastUpdateSpan) {
        const now = new Date();
        lastUpdateSpan.textContent = `آخر تحديث: ${now.toLocaleString()}`;
    }
}


</script>
</head>
<body>
<?php include('menu.php'); ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6 search-container">
            <div class="card search-card mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-search me-2"></i>
                        <b>البحث بالرقم الآلي</b>
                    </h5>
                    <div class="input-group">
                        <input type="text" id="myInput" class="form-control search-input" placeholder="أدخل الرقم الآلي" autocomplete="off">
                        <button class="btn btn-primary" type="button" id="searchBtn" style="background: linear-gradient(135deg, #2c3e50, #3498db); border: none;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <small class="text-muted mt-2 d-block">اكتب الرقم الآلي للموظف للبحث عن معلوماته</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-spinner-custom">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;"></div>
        <p class="mt-3">جاري تحميل البيانات...</p>
    </div>
</div>

<div id="contentSection" class="container-fluid"></div>

<!-- Modals Container -->
<div id="modalsContainer"></div>

<script>
// Global variables
let currentCareerId = 0;
let currentMecano = '';

// Toast notification function
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `toast-custom toast-${type}`;
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle')} me-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Search function
function searchEmployee() {
    const mecano = document.getElementById('myInput').value.trim();
    currentMecano = mecano;
    
    if (!mecano) {
        showToast('الرجاء إدخال الرقم الآلي', 'error');
        return;
    }
    
    const loadingOverlay = document.getElementById('loadingOverlay');
    const contentSection = document.getElementById('contentSection');
    
    loadingOverlay.style.display = 'flex';
    contentSection.classList.remove('show');
    
    const formData = new FormData();
    formData.append('mecano', mecano);
    
    fetch(window.location.href, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        loadingOverlay.style.display = 'none';
        
        if (data.success) {
            contentSection.innerHTML = data.html;
            contentSection.classList.add('show');
            loadModals();
            contentSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        } else {
            contentSection.innerHTML = `
                <div class="alert alert-warning text-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${data.message || 'لم يتم العثور على بيانات'}
                </div>
            `;
            contentSection.classList.add('show');
        }
    })
    .catch(error => {
        loadingOverlay.style.display = 'none';
        console.error('Error:', error);
        showToast('حدث خطأ في الاتصال بالخادم', 'error');
    });
}

// Load all modals
function loadModals() {
    const modalsContainer = document.getElementById('modalsContainer');
    
    const modalsHtml = `
        <!-- Documents Modal -->
        <div class="modal fade" id="documentsModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i>المستندات المرفقة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="upload-area">
                            <i class="fas fa-cloud-upload-alt fa-2x mb-2"></i>
                            <p>رفع مستند جديد</p>
                            <input type="file" id="newDocument" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small id="newFileName" class="text-muted d-block mt-2">لم يتم اختيار ملف</small>
                            <button class="btn btn-success mt-3" onclick="uploadCareerDocument()">
                                <i class="fas fa-upload me-1"></i>رفع المستند
                            </button>
                        </div>
                        <div id="documentsList" class="document-list"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Edit Career Modal -->
        <div class="modal fade" id="editCareerModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>تعديل بيانات المسار المهني</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editCareerForm">
                            <input type="hidden" id="editCareerId" name="id">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">عدد المقرر</label>
                                    <input type="text" class="form-control" id="editDecisionNumber" name="decision_number">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">تاريخ الإصدار</label>
                                    <input type="date" class="form-control" id="editIssueDate" name="issue_date">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الرتبة القديمة</label>
                                    <select class="form-control" id="editOldRank" name="ancienrang_id">
                                        <option value="">اختر الرتبة</option>
                                        <?php
                                        $titlesRes = mysqli_query($connection, "SELECT * FROM titres ORDER BY libellet");
                                        while ($t = mysqli_fetch_assoc($titlesRes)) {
                                            echo "<option value='{$t['id']}'>{$t['libellet']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الرتبة الجديدة</label>
                                    <select class="form-control" id="editNewRank" name="nouveaurang_id">
                                        <option value="">اختر الرتبة</option>
                                        <?php
                                        mysqli_data_seek($titlesRes, 0);
                                        while ($t = mysqli_fetch_assoc($titlesRes)) {
                                            echo "<option value='{$t['id']}'>{$t['libellet']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">السلم القديم</label>
                                    <input type="text" class="form-control" id="editOldScale" name="ancienneechelle">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">السلم الجديد</label>
                                    <input type="text" class="form-control" id="editNewScale" name="nouvelechelle">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الدرجة القديمة</label>
                                    <input type="text" class="form-control" id="editOldGrade" name="anciennegrade">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الدرجة الجديدة</label>
                                    <input type="text" class="form-control" id="editNewGrade" name="nouveaugrade">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">الملاحظات</label>
                                    <textarea class="form-control" id="editNotes" name="notes" rows="2"></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">تاريخ الفاعليّة</label>
                                    <input type="date" class="form-control" id="editEffectiveDate" name="dateeffet" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">اللّجنة</label>
                                    <input type="text" class="form-control" id="editCommittee" name="commission">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-primary" onclick="updateCareer()">حفظ التغييرات</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add Skill Modal -->
        <div class="modal fade" id="addSkillModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-plus me-2"></i>إضافة مهارة جديدة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addSkillForm">
                            <input type="hidden" name="mecano" id="skillMecano">
                            <div class="mb-3">
                                <label class="form-label">اسم المهارة</label>
                                <input type="text" class="form-control" name="nom_competence" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">التصنيف</label>
                                <input type="text" class="form-control" name="categorie" placeholder="مثال: تقنية, إدارية, لغوية..." required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">المستوى</label>
                                <input type="range" class="form-range" name="niveau" min="1" max="5" step="1" value="3" oninput="updateSkillStars(this.value)">
                                <div class="text-center mt-2">
                                    <span id="skillStars" style="font-size: 1.5rem; color: #f39c12;">★★★☆☆</span>
                                    <div id="skillLevelText" class="small text-muted">جيد (3/5)</div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-success" onclick="saveSkill()">حفظ</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add Certification Modal -->
        <div class="modal fade" id="addCertificationModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-certificate me-2"></i>إضافة شهادة جديدة</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addCertificationForm">
                            <input type="hidden" name="mecano" id="certMecano">
                            <div class="mb-3">
                                <label class="form-label">اسم الشهادة</label>
                                <input type="text" class="form-control" name="nom_certification" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الجهة المانحة</label>
                                <input type="text" class="form-control" name="organisme" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">تاريخ الحصول</label>
                                <input type="date" class="form-control" name="date_obtention" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-primary" onclick="saveCertification()">حفظ</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add Training Modal -->
        <div class="modal fade" id="addTrainingModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-graduation-cap me-2"></i>إضافة تكوين جديد</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addTrainingForm">
                            <input type="hidden" name="mecano" id="trainingMecano">
                            <div class="mb-3">
                                <label class="form-label">اسم التكوين</label>
                                <input type="text" class="form-control" name="nom_formation" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">المؤسسة المكونة</label>
                                <input type="text" class="form-control" name="organisme_formateur" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">تاريخ البداية</label>
                                    <input type="date" class="form-control" name="date_debut" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">تاريخ النهاية</label>
                                    <input type="date" class="form-control" name="date_fin">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">الحالة</label>
                                <select class="form-control" name="statut">
                                    <option value="en_cours">قيد التنفيذ</option>
                                    <option value="completed">مكتمل</option>
                                    <option value="annule">ملغي</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">نسبة الإنجاز (%)</label>
                                <input type="number" class="form-control" name="progression" min="0" max="100" value="0">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-info" onclick="saveTraining()">حفظ</button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add Career Modal -->
        <div class="modal fade" id="addCareerModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-plus me-2"></i>إضافة مسار مهني جديد</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="addCareerForm">
                            <input type="hidden" name="mecano" id="careerMecano">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">عدد المقرر</label>
                                    <input type="text" class="form-control" name="decision_number">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">تاريخ الإصدار</label>
                                    <input type="date" class="form-control" name="issue_date">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الرتبة القديمة</label>
                                    <!--<select class="form-control" name="ancienrang_id">-->
									 <select class="form-control" id="old_rank" name="old_rank" required onchange="updatePlafond(this.value, 'old_scale')">
                                        <option value="">اختر الرتبة</option>
                                        <?php
                                        $titlesRes2 = mysqli_query($connection, "SELECT * FROM titres ORDER BY libellet");
                                        while ($t = mysqli_fetch_assoc($titlesRes2)) {
                                            echo "<option value='{$t['id']}'>{$t['libellet']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الرتبة الجديدة</label>
                                    <!--<select class="form-control" name="nouveaurang_id">-->
									 <select class="form-control" id="new_rank" name="new_rank" required onchange="updatePlafond(this.value, 'new_scale')">
                                        <option value="">اختر الرتبة</option>
                                        <?php
                                        mysqli_data_seek($titlesRes2, 0);
                                        while ($t = mysqli_fetch_assoc($titlesRes2)) {
                                            echo "<option value='{$t['id']}'>{$t['libellet']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">السلم القديم</label>
                                    <!--<input type="text" class="form-control" name="ancienneechelle">-->
									<input type="number" class="form-control" name="old_scale" id="old_scale" required min="101" max="730" step="1" onblur="validateScaleInput(this)">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">السلم الجديد</label>
                                    <!--<input type="text" class="form-control" name="nouvelechelle">-->
									 <input type="number" class="form-control" name="new_scale" id="new_scale" required min="101" max="730" step="1" onblur="validateScaleInput(this)">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الدرجة القديمة</label>
                                   <!-- <input type="text" class="form-control" name="anciennegrade">-->
									  <input type="number" class="form-control" name="old_grade" required min="1" max="23" step="1" onblur="validateGradeInput(this)">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">الدرجة الجديدة</label>
                                    <!--<input type="text" class="form-control" name="nouveaugrade">-->
									<input type="number" class="form-control" name="new_grade" required min="1" max="23" step="1" onblur="validateGradeInput(this)">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">الملاحظات</label>
                                    <input type="text" class="form-control" name="notes" list="notesList" placeholder="اختر أو أدخل ملاحظات">
                        <datalist id="notesList">
                            <?php
                            $notesQuery = mysqli_query($connection, "SELECT DISTINCT notes FROM carriere WHERE notes IS NOT NULL AND notes != '' ORDER BY notes");
                            if ($notesQuery) {
                                while ($noteRow = mysqli_fetch_assoc($notesQuery)) {
                                    $note = htmlspecialchars($noteRow['notes'], ENT_QUOTES, 'UTF-8');
                                    echo '<option value="' . $note . '">';
                                }
                            }
                            ?>
                        </datalist>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">تاريخ الفاعليّة</label>
                                   <!-- <input type="date" class="form-control" name="dateeffet" required>-->
									<input type="date" class="form-control" name="effective_date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">اللّجنة</label>
                                   <!-- <input type="text" class="form-control" name="commission"> -->
									<input type="text" class="form-control" name="committee" required>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="button" class="btn btn-success" onclick="submitCareerForm()">حفظ</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    modalsContainer.innerHTML = modalsHtml;
}

// Career Management Functions
function openAddCareerModal(mecano) {
    document.getElementById('careerMecano').value = mecano;
    const modal = new bootstrap.Modal(document.getElementById('addCareerModal'));
    modal.show();
}

function submitCareerForm() {
    const form = document.getElementById('addCareerForm');

    if (!form) {
        console.error('Form not found');
        return;
    }

    const formData = new FormData(form);
    formData.append('action', 'save_career');

    fetch('CareerDependencies/save-career-data.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Server error: ' + response.status);
        }
        return response.text(); // ناخذ text قبل JSON
    })
    .then(text => {
        try {
            const data = JSON.parse(text);

            if (data.success) {
                showToast('تم حفظ البيانات بنجاح');

                const modalEl = document.getElementById('addCareerModal');
                const modal = bootstrap.Modal.getInstance(modalEl);

                if (modal) modal.hide();

                form.reset(); // reset الفورم
                searchEmployee(); // تحديث البيانات

            } else {
                showToast(data.message || 'خطأ في الحفظ', 'error');
            }

        } catch (e) {
            console.error('Invalid JSON:', text);
            showToast('رد غير صالح من السيرفر', 'error');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showToast('حدث خطأ في الاتصال بالخادم', 'error');
    });
}


function editCareer(id) {
    fetch(`?action=get_career&id=${id}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const career = data.data;
            document.getElementById('editCareerId').value = career.id;
            document.getElementById('editDecisionNumber').value = career.decision_number || '';
            document.getElementById('editIssueDate').value = career.issue_date || '';
            document.getElementById('editOldRank').value = career.ancienrang_id || '';
            document.getElementById('editNewRank').value = career.nouveaurang_id || '';
            document.getElementById('editOldScale').value = career.ancienneechelle || '';
            document.getElementById('editNewScale').value = career.nouvelechelle || '';
            document.getElementById('editOldGrade').value = career.anciennegrade || '';
            document.getElementById('editNewGrade').value = career.nouveaugrade || '';
            document.getElementById('editNotes').value = career.notes || '';
            document.getElementById('editEffectiveDate').value = career.dateeffet || '';
            document.getElementById('editCommittee').value = career.commission || '';
            
            const modal = new bootstrap.Modal(document.getElementById('editCareerModal'));
            modal.show();
        } else {
            showToast(data.message || 'خطأ في تحميل البيانات', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('حدث خطأ في الاتصال بالخادم', 'error');
    });
}

function updateCareer() {
    const form = document.getElementById('editCareerForm');
    const formData = new FormData(form);
    formData.append('action', 'update_career');
    
    fetch(window.location.href, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('تم تحديث البيانات بنجاح');
            bootstrap.Modal.getInstance(document.getElementById('editCareerModal')).hide();
            searchEmployee(); // Reload data
        } else {
            showToast(data.message || 'خطأ في التحديث', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('حدث خطأ في الاتصال بالخادم', 'error');
    });
}

function deleteCareer(id) {
    if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
        const formData = new FormData();
        formData.append('action', 'delete_career');
        formData.append('id', id);
        
        fetch(window.location.href, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('تم حذف السجل بنجاح');
                // Remove row from table without reload
                const row = document.querySelector(`tr[data-career-id="${id}"]`);
                if (row) {
                    row.remove();
                    const tbody = document.getElementById('careerTableBody');
                    if (tbody && tbody.children.length === 0) {
                        tbody.innerHTML = '<tr id="noCareerData"><td colspan="13" class="text-center text-muted py-4">لا توجد بيانات للعرض</td></tr>';
                    }
                }
            } else {
                showToast(data.message || 'خطأ في الحذف', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('حدث خطأ في الاتصال بالخادم', 'error');
        });
    }
}

// Skills Management Functions
function openAddSkillModal(mecano) {
    document.getElementById('skillMecano').value = mecano;
    const modal = new bootstrap.Modal(document.getElementById('addSkillModal'));
    modal.show();
}

function updateSkillStars(level) {
    const stars = document.getElementById('skillStars');
    const levelText = document.getElementById('skillLevelText');
    
    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
        starsHtml += i <= level ? '★' : '☆';
    }
    stars.innerHTML = starsHtml;
    
    const levels = {
        1: 'مبتدئ (1/5)',
        2: 'متوسط (2/5)',
        3: 'جيد (3/5)',
        4: 'ممتاز (4/5)',
        5: 'خبير (5/5)'
    };
    levelText.textContent = levels[level] || 'ممتاز (5/5)';
}

function saveSkill() {
    const form = document.getElementById('addSkillForm');
    const formData = new FormData(form);
    formData.append('action', 'save_skill');
    
    fetch(window.location.href, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('تم حفظ المهارة بنجاح');
            bootstrap.Modal.getInstance(document.getElementById('addSkillModal')).hide();
            searchEmployee(); // Reload data
        } else {
            showToast(data.message || 'خطأ في الحفظ', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('حدث خطأ في الاتصال بالخادم', 'error');
    });
}

function deleteSkill(skillId) {
    if (confirm('هل أنت متأكد من حذف هذه المهارة؟')) {
        const formData = new FormData();
        formData.append('action', 'delete_skill');
        formData.append('id', skillId);
        
        fetch(window.location.href, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('تم حذف المهارة بنجاح');
                // Remove skill from DOM
                const skillElement = document.querySelector(`[data-skill-id="${skillId}"]`);
                if (skillElement) skillElement.remove();
                // Update stats
                updateSkillStats();
            } else {
                showToast(data.message || 'خطأ في الحذف', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('حدث خطأ في الاتصال بالخادم', 'error');
        });
    }
}

// Certifications Management Functions
function openAddCertificationModal(mecano) {
    document.getElementById('certMecano').value = mecano;
    const modal = new bootstrap.Modal(document.getElementById('addCertificationModal'));
    modal.show();
}

function saveCertification() {
    const form = document.getElementById('addCertificationForm');
    const formData = new FormData(form);
    formData.append('action', 'save_certification');
    
    fetch(window.location.href, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('تم حفظ الشهادة بنجاح');
            bootstrap.Modal.getInstance(document.getElementById('addCertificationModal')).hide();
            searchEmployee(); // Reload data
        } else {
            showToast(data.message || 'خطأ في الحفظ', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('حدث خطأ في الاتصال بالخادم', 'error');
    });
}

function deleteCertification(certId) {
    if (confirm('هل أنت متأكد من حذف هذه الشهادة؟')) {
        const formData = new FormData();
        formData.append('action', 'delete_certification');
        formData.append('id', certId);
        
        fetch(window.location.href, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('تم حذف الشهادة بنجاح');
                const certElement = document.querySelector(`[data-cert-id="${certId}"]`);
                if (certElement) certElement.remove();
                updateCertStats();
            } else {
                showToast(data.message || 'خطأ في الحذف', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('حدث خطأ في الاتصال بالخادم', 'error');
        });
    }
}

// Trainings Management Functions
function openAddTrainingModal(mecano) {
    document.getElementById('trainingMecano').value = mecano;
    const modal = new bootstrap.Modal(document.getElementById('addTrainingModal'));
    modal.show();
}

function saveTraining() {
    const form = document.getElementById('addTrainingForm');
    const formData = new FormData(form);
    formData.append('action', 'save_training');
    
    fetch(window.location.href, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('تم حفظ التكوين بنجاح');
            bootstrap.Modal.getInstance(document.getElementById('addTrainingModal')).hide();
            searchEmployee(); // Reload data
        } else {
            showToast(data.message || 'خطأ في الحفظ', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('حدث خطأ في الاتصال بالخادم', 'error');
    });
}

// Document Management Functions
function viewDocuments(careerId) {
    currentCareerId = careerId;
    loadDocuments(careerId);
    const modal = new bootstrap.Modal(document.getElementById('documentsModal'));
    modal.show();
}
function viewDocument(docId) {
    window.open('CareerDependencies/view-document.php?id=' + docId, '_blank');
}
function loadDocuments(careerId) {
    const documentsList = document.getElementById('documentsList');
    documentsList.innerHTML = '<div class="text-center py-3"><i class="fas fa-spinner fa-spin"></i> جاري تحميل المستندات...</div>';
    
    fetch('CareerDependencies/get-documents.php?career_id=' + careerId)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.documents) {
                if (data.documents.length > 0) {
                    let html = '';
                    data.documents.forEach(doc => {
                        html += `
                            <div class="document-item">
                                <div>
                                    <i class="fas fa-file-alt me-2"></i>
                                    <strong>${escapeHtml(doc.file_name)}</strong>
                                    <br><small class="text-muted">${doc.upload_date}</small>
                                </div>
                                <div>
								    <button class="btn btn-sm btn-outline-primary me-1" title="عرض" onclick="viewDocument(${doc.id})">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-success me-1" title="تحميل" onclick="downloadDocument(${doc.id})">
                                    <i class="fas fa-download"></i>
                                </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteDocument(${doc.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                    documentsList.innerHTML = html;
                } else {
                    documentsList.innerHTML = '<div class="text-center py-3 text-muted">لا توجد مستندات مرفقة</div>';
                }
            } else {
                documentsList.innerHTML = '<div class="text-center py-3 text-danger">خطأ في تحميل المستندات</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            documentsList.innerHTML = '<div class="text-center py-3 text-danger">خطأ في الاتصال بالخادم</div>';
        });
}

function uploadCareerDocument() {
    const fileInput = document.getElementById('newDocument');
    if (fileInput.files.length === 0) {
        showToast('يرجى اختيار ملف', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('career_id', currentCareerId);
    formData.append('document', fileInput.files[0]);
    formData.append('document_type', 'career');
    
    fetch('CareerDependencies/upload-career-document.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('تم رفع الملف بنجاح');
            fileInput.value = '';
            loadDocuments(currentCareerId);
        } else {
            showToast(data.message || 'خطأ في رفع الملف', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('حدث خطأ في الاتصال بالخادم', 'error');
    });
}


function downloadDocument(docId, fileName) {
    const downloadLink = document.createElement('a');
    downloadLink.href = 'CareerDependencies/download-document.php?id=' + docId;
    downloadLink.download = fileName;
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
function deleteDocument(docId) {
    if (confirm('هل أنت متأكد من حذف هذا المستند؟')) {
        fetch('CareerDependencies/delete-document.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + docId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('تم حذف المستند بنجاح');
                loadDocuments(currentCareerId);
            } else {
                showToast(data.message || 'خطأ في الحذف', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('حدث خطأ في الاتصال بالخادم', 'error');
        });
    }
}

// Utility Functions
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function updateSkillStats() {
    const skillItems = document.querySelectorAll('[data-skill-id]');
    const totalSkills = skillItems.length;
    let totalLevel = 0;
    
    skillItems.forEach(item => {
        const levelText = item.querySelector('.text-muted')?.innerText || '0/5';
        const level = parseInt(levelText.split('/')[0]) || 0;
        totalLevel += level;
    });
    
    const avgLevel = totalSkills > 0 ? (totalLevel / totalSkills).toFixed(1) : '0';
    
    const totalSkillsEl = document.getElementById('totalSkillsCount');
    const avgLevelEl = document.getElementById('avgSkillLevel');
    
    if (totalSkillsEl) totalSkillsEl.textContent = totalSkills;
    if (avgLevelEl) avgLevelEl.textContent = avgLevel + '/5';
    
    if (totalSkills === 0 && !document.getElementById('noSkillsMessage')) {
        const skillsList = document.getElementById('skillsList');
        if (skillsList && skillsList.children.length === 0) {
            skillsList.innerHTML = '<p class="text-center text-muted py-3" id="noSkillsMessage"><i class="fas fa-info-circle me-2"></i>لا توجد مهارات مسجلة</p>';
        }
    }
}

function updateCertStats() {
    const certItems = document.querySelectorAll('[data-cert-id]');
    const certCount = certItems.length;
    const certCountEl = document.getElementById('certCount');
    if (certCountEl) certCountEl.textContent = certCount;
    
    if (certCount === 0 && !document.getElementById('noCertificationsMessage')) {
        const certsList = document.getElementById('certificationsList');
        if (certsList && certsList.children.length === 0) {
            certsList.innerHTML = '<p class="text-center text-muted py-3" id="noCertificationsMessage"><i class="fas fa-info-circle me-2"></i>لا توجد شهادات مسجلة</p>';
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set focus on the input with id "myInput"
    const myInput = document.getElementById('myInput');
    if (myInput) {
        myInput.focus();
        
        // Set up search on Enter key
        myInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                searchEmployee();
            }
        });
    }
    
    // Set up search button click event
    const searchBtn = document.getElementById('searchBtn');
    if (searchBtn) {
        searchBtn.addEventListener('click', searchEmployee);
    }
    
    // Load all modals
    loadModals();
});
</script>

</body>
</html>