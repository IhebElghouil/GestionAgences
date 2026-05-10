<?php
// controllers/DashboardController.php - Modifié
require_once __DIR__ . '/../models/DashboardModel.php';
require_once __DIR__ . '/../models/UserModel.php';

class DashboardController {
    private $dashboardModel;
    private $userModel;
    
    public function __construct() {
        session_start();
        $this->dashboardModel = new DashboardModel();
        $this->userModel = new UserModel();
        
        // Vérification de l'authentification
        if (!isset($_SESSION['congidGA'])) {
            header('Location: index.php');
            exit();
        }
    }
    
    /**
     * Affiche le tableau de bord avec toutes les notifications
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
        
        // Récupération des statistiques principales
        $stats = [
            'accidents' => $this->dashboardModel->getExpiredAccidentsCount($departements, $isAdmin),
            'professional_cards' => $this->dashboardModel->getExpiredProfessionalCardsCount($departements, $isAdmin),
            'driving_licenses' => $this->dashboardModel->getExpiredDrivingLicensesCount($departements, $isAdmin),
            'medical_certs' => $this->dashboardModel->getExpiredMedicalCertsCount($departements, $isAdmin),
            'retirement' => $this->dashboardModel->getUpcomingRetirementsCount($departements, $isAdmin),
            'promotion' => $this->dashboardModel->getUpcomingPromotionsCount($departements, $isAdmin),
            'career' => $this->dashboardModel->getCareerChangesCount($departements, $isAdmin),
            'recruitment' => $this->dashboardModel->getNewRecruitsCount($departements, $isAdmin),
            'sanctions' => $this->dashboardModel->getOpenSanctionsCount($departements, $isAdmin),
            'detachment' => $this->dashboardModel->getDetachmentFilesCount($departements, $isAdmin)
        ];
        
        // Récupération des détails
        $retirementDetails = $this->dashboardModel->getRetirementDetails($departements, $isAdmin);
        $promotionDetails = $this->dashboardModel->getPromotionDetails($departements, $isAdmin);
        $careerDetails = $this->dashboardModel->getCareerDetails($departements, $isAdmin);
        $newRecruits = $this->dashboardModel->getNewRecruitsDetails($departements, $isAdmin);
        $openSanctions = $this->dashboardModel->getOpenSanctionsDetails($departements, $isAdmin);
        $detachmentFiles = $this->dashboardModel->getDetachmentFilesDetails($departements, $isAdmin);
        
        // Nom du département
        $departmentName = $this->dashboardModel->getDepartmentName($departements, $isAdmin);
        
        // Construction des notifications - TOUTES les notifications maintenant
        $notifications = [];
        
        // Notifications d'expiration
        $expirationKeys = ['accidents', 'professional_cards', 'driving_licenses', 'medical_certs'];
        foreach ($expirationKeys as $key) {
            if ($stats[$key] > 0) {
                $notifications[$key] = [
                    'count' => $stats[$key],
                    'name' => $this->getNotificationName($key),
                    'link' => $this->getNotificationLink($key),
                    'icon' => $this->getNotificationIcon($key),
                    'class' => $this->getNotificationClass($key),
                    'description' => $this->getNotificationDescription($key, $stats[$key])
                ];
            }
        }
        
        // Notifications de retraite et promotion
        if ($stats['retirement'] > 0) {
            $notifications['retirement'] = [
                'count' => $stats['retirement'],
                'name' => 'التقاعد',
                'link' => 'departure.php?tab=retraite',
                'icon' => 'bi-person-walking',
                'class' => 'retirement',
                'description' => "عدد من الأعوان سيحالون على التقاعد في الأشهر الثلاثة القادمة"
            ];
        }
        
        if ($stats['promotion'] > 0) {
            $notifications['promotion'] = [
                'count' => $stats['promotion'],
                'name' => 'الترقيات',
                'link' => 'promotion_list.php',
                'icon' => 'bi-graph-up-arrow',
                'class' => 'promotion',
                'description' => "عدد من الأعوان سيتحصلون على الترقيات في الأشهر الثلاثة القادمة"
            ];
        }
        
        // Nouveaux recrutements
        if ($stats['recruitment'] > 0) {
            $notifications['recruitment'] = [
                'count' => $stats['recruitment'],
                'name' => 'المنتدبون الجدد',
                'link' => 'new_recruits.php',
                'icon' => 'bi-person-plus',
                'class' => 'recruitment',
                'description' => "عدد من المنتدبين الجدد تم توظيفهم خلال الأشهر الثلاثة الماضية"
            ];
        }
        
        // Sanctions
        if ($stats['sanctions'] > 0) {
            $notifications['sanctions'] = [
                'count' => $stats['sanctions'],
                'name' => 'العقوبات غير المغلقة',
                'link' => 'sanctions.php',
                'icon' => 'bi-exclamation-triangle',
                'class' => 'sanctions',
                'description' => "عدد العقوبات التي لم يتم إغلاقها بعد وتحتاج إلى متابعة"
            ];
        }
        
        // Détachement
        if ($stats['detachment'] > 0) {
            $notifications['detachment'] = [
                'count' => $stats['detachment'],
                'name' => 'ملفات الإلحاق غير المغلقة',
                'link' => 'departure.php?tab=detachement',
                'icon' => 'bi-box-arrow-right',
                'class' => 'detachment',
                'description' => "عدد ملفات الإلحاق الجارية التي لم يتم إغلاقها بعد"
            ];
        }
        
        // Changements de carrière
        $currentEvents = [];
        if ($stats['career'] > 0) {
            $currentEvents['career'] = [
                'count' => $stats['career'],
                'name' => 'تغييرات المسار الوظيفي',
                'link' => 'career_changes.php',
                'icon' => 'bi-briefcase',
                'class' => 'career'
            ];
        }
        
        // Calcul du total
        $totalNotifications = array_sum($stats);
        
        // Chargement de la vue
        $this->render('dashboard/index', [
            'departmentName' => $departmentName,
            'notifications' => $notifications,
            'currentEvents' => $currentEvents,
            'totalNotifications' => $totalNotifications,
            'retirementDetails' => $retirementDetails,
            'promotionDetails' => $promotionDetails,
            'careerDetails' => $careerDetails,
            'newRecruits' => $newRecruits,
            'openSanctions' => $openSanctions,
            'detachmentFiles' => $detachmentFiles,
            'stats' => $stats,
            'isAdmin' => $isAdmin
        ]);
    }
    
    private function getNotificationName($key) {
        $names = [
            'accidents' => 'حوادث الشغل',
            'professional_cards' => 'البطاقات المهنيّة',
            'driving_licenses' => 'رخص السياقة',
            'medical_certs' => 'الشهادات الطبية'
        ];
        return $names[$key] ?? $key;
    }
    
    private function getNotificationLink($key) {
        $links = [
            'accidents' => 'index.php?action=accidents',
            'professional_cards' => 'index.php?action=badges&type=0',
            'driving_licenses' => 'index.php?action=badges&type=1',
            'medical_certs' => 'c_certifmedtravail.php'
        ];
        return $links[$key] ?? '#';
    }
    
    private function getNotificationIcon($key) {
        $icons = [
            'accidents' => 'bi-clipboard-pulse',
            'professional_cards' => 'bi-credit-card-2-front',
            'driving_licenses' => 'bi-person-vcard',
            'medical_certs' => 'bi-heart-pulse'
        ];
        return $icons[$key] ?? 'bi-bell';
    }
    
    private function getNotificationClass($key) {
        $classes = [
            'accidents' => 'accident',
            'professional_cards' => 'professional',
            'driving_licenses' => 'driving',
            'medical_certs' => 'medical'
        ];
        return $classes[$key] ?? 'default';
    }
    
    private function getNotificationDescription($key, $count) {
        $descriptions = [
            'accidents' => "عدد من حوادث الشغل منتهية الصلاحية وتحتاج إلى مراجعة فورية",
            'professional_cards' => "صلاحية عدد من البطاقات المهنية منتهية وتحتاج إلى مراجعة فورية",
            'driving_licenses' => "صلاحية عدد من رخص السياقة منتهية وتحتاج إلى مراجعة فورية",
            'medical_certs' => "صلاحية عدد من الشهادات الطبية منتهية وتحتاج إلى مراجعة فورية"
        ];
        return $descriptions[$key] ?? '';
    }
    
    private function render($view, $data = []) {
        extract($data);
        require_once __DIR__ . '/../views/' . $view . '.php';
    }
}
?>