<?php
// index.php - Routeur principal
require_once 'controllers/CongesController.php';
require_once 'controllers/BadgesController.php';
require_once 'controllers/AccidentController.php';
require_once 'controllers/EmployeeController.php';
require_once 'controllers/DashboardController.php';
require_once 'controllers/MedicalCertificateController.php';
require_once 'controllers/DepartureController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

// Routes pour le dashboard
if ($action === 'dashboard') {
    $controller = new DashboardController();
    $controller->index();
}
// Routes pour les employés
elseif (strpos($action, 'employees') === 0) {
    $controller = new EmployeeController();
    
    switch ($action) {
        case 'employees':
            $controller->index();
            break;
        case 'employees_add':
            $controller->add();
            break;
        case 'employees_edit':
            $controller->edit();
            break;
        case 'employees_save':
            $controller->save();
            break;
        case 'employees_update':
            $controller->update();
            break;
        case 'employees_export':
            $controller->export();
            break;
        default:
            $controller->index();
            break;
    }
}
// Routes pour les accidents
elseif (strpos($action, 'accidents') === 0) {
    $controller = new AccidentController();
    
    switch ($action) {
        case 'accidents':
            $controller->index();
            break;
        case 'accidents_edit':
            $controller->edit();
            break;
        case 'accidents_update':
            $controller->update();
            break;
        case 'accidents_update_type':
            $controller->updateType();
            break;
        case 'accidents_export':
            $controller->export();
            break;
        default:
            $controller->index();
            break;
    }
}
// Routes pour les badges
elseif (strpos($action, 'badges') === 0) {
    $controller = new BadgesController();
    
    switch ($action) {
        case 'badges':
            $controller->index();
            break;
        case 'badges_edit':
            $controller->edit();
            break;
        case 'badges_update':
            $controller->update();
            break;
        case 'badges_export':
            $controller->export();
            break;
        default:
            $controller->index();
            break;
    }
}

// Routes pour les certificats médicaux
elseif (strpos($action, 'medical') === 0) {
    $controller = new MedicalCertificateController();
    
    switch ($action) {
        case 'medical_certificates':
            $controller->index();
            break;
        case 'medical_add_form':
            $controller->addForm();
            break;
        case 'medical_get_name':
            $controller->getEmployeeName();
            break;
        case 'medical_add':
            $controller->add();
            break;
        case 'medical_edit':
            $controller->edit();
            break;
        case 'medical_update':
            $controller->update();
            break;
        case 'medical_export':
            $controller->export();
            break;
        default:
            $controller->index();
            break;
    }
}
// Routes pour les départs et détachements
// Routes pour les départs et détachements
elseif (strpos($action, 'departure') === 0) {
    $controller = new DepartureController();
    
    switch ($action) {
        case 'departure':
            $controller->index();
            break;
        case 'departure_get_user':
            $controller->getUserInfo();
            break;
        case 'departure_add':
            $controller->addDepart();
            break;
        case 'departure_save_detachement':
            $controller->saveDetachement();
            break;
        case 'departure_delete_detachement':
            $controller->deleteDetachement();
            break;
        case 'departure_close_detachement':
            $controller->closeDetachement();
            break;
        case 'departure_get_documents':
            $controller->getDocuments();
            break;
        case 'departure_get_documents_count':
            $controller->getDocumentsCount();
            break;
        case 'departure_upload_document':
            $controller->uploadDocument();
            break;
        case 'departure_delete_document':
            $controller->deleteDocument();
            break;
        case 'departure_export':
            $controller->export();
            break;
        default:
            $controller->index();
            break;
    }
}
// Routes pour les congés
else {
    $controller = new CongesController();
    
    switch ($action) {
        case 'export':
            $controller->exportExcel();
            break;
        case 'export_annual_leaves':
            $controller->exportAnnualLeaves();
            break;
        case 'export_remaining_leaves':
            $controller->exportRemainingLeaves();
            break;
        case 'annual_leaves':
            $controller->annualLeaves();
            break;
        case 'remaining_leaves':
            $controller->remainingLeaves();
            break;
        default:
            $controller->index();
            break;
    }
}
?>