<?php
session_start();
require('connection.php');

if (isset($_POST['section']) && isset($_POST['mecano'])) {
    $section = $_POST['section'];
    $mecano = $_POST['mecano'];
    
    if ($section === 'career-section') {
        // Générer le contenu de la section carrière
        $requetecar = mysqli_query($connection, "SELECT * FROM carriere WHERE mecano='".mysqli_real_escape_string($connection, $mecano)."' ORDER BY dateeffet DESC");
        
        echo '
        <div class="section-loading" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">جاري التحميل...</span>
            </div>
        </div>
        <div class="card-header card-header-custom">
            <i class="fas fa-briefcase"></i>متابعة الحياة المهنيّة
        </div>
        <div class="card-body-custom">
            <div class="career-actions-header">
                <h5 class="mb-0">التطور الوظيفي</h5>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCareerModal">
                    <i class="fas fa-plus me-1"></i>إضافة بيانات جديدة
                </button>
            </div>
            <div class="scrollable-container">
                <table class="table custom-table">
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
                    <tbody id="career-table-body">';
        
        $careerData = [];
        if ($requetecar) {
            while($rcar = mysqli_fetch_assoc($requetecar)) {
                $careerData[] = $rcar;
                
                $id = isset($rcar['id']) ? $rcar['id'] : '0';
                $ancienrang = isset($rcar['ancienrang']) ? $rcar['ancienrang'] : 'N/A';
                $nouveaurang = isset($rcar['nouveaurang']) ? $rcar['nouveaurang'] : 'N/A';
                $ancienneechelle = isset($rcar['ancienneechelle']) ? $rcar['ancienneechelle'] : 'N/A';
                $nouvelechelle = isset($rcar['nouvelechelle']) ? $rcar['nouvelechelle'] : 'N/A';
                $anciennegrade = isset($rcar['anciennegrade']) ? $rcar['anciennegrade'] : 'N/A';
                $nouveaugrade = isset($rcar['nouveaugrade']) ? $rcar['nouveaugrade'] : 'N/A';
                $notes = isset($rcar['notes']) ? $rcar['notes'] : '-';
                $dateeffet = isset($rcar['dateeffet']) ? $rcar['dateeffet'] : 'N/A';
                $commission = isset($rcar['commission']) ? $rcar['commission'] : '-';
                $decision_number = isset($rcar['decision_number']) ? $rcar['decision_number'] : '-';
                $issue_date = isset($rcar['issue_date']) ? $rcar['issue_date'] : '-';
                
                echo '
                    <tr>
                        <td>' . $decision_number . '</td>
                        <td>' . $issue_date . '</td>
                        <td>' . $ancienrang . '</td>
                        <td>' . $nouveaurang . '</td>
                        <td>' . $ancienneechelle . '</td>
                        <td>' . $nouvelechelle . '</td>
                        <td>' . $anciennegrade . '</td>
                        <td>' . $nouveaugrade . '</td>
                        <td>' . $notes . '</td>
                        <td>' . $dateeffet . '</td>
                        <td>' . $commission . '</td>
                        <td>
                            <button class="btn btn-info btn-sm action-btn" onclick="viewDocuments(' . $id . ')" title="عرض المستندات">
                                <i class="fas fa-file-alt"></i>
                            </button>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm action-btn" onclick="editCareer(' . $id . ')" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-sm action-btn" onclick="deleteCareer(' . $id . ')" title="حذف">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>';
            }
        }
        
        if(empty($careerData)) {
            echo '
                    <tr>
                        <td colspan="13" class="text-center text-muted py-4">
                            <i class="fas fa-info-circle me-2"></i>لا توجد بيانات للعرض
                        </td>
                    </tr>';
        }
        
        echo '
                    </tbody>
                </table>
            </div>
        </div>';
    } elseif ($section === 'skills-section') {
        // Générer le contenu de la section compétences
        // Code similaire à celui de la section carrière
        echo 'Contenu de la section compétences...';
    }
}
?>