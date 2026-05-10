<div class="modal-header">
    <h5 class="modal-title">
        <i class="fas fa-user-edit me-2"></i>
        تعديل بيانات عون
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
</div>
<div class="modal-body">
    <form id="employeeForm" action="index.php?action=employees_update" method="POST">
        <input type="hidden" name="mecano" value="<?php echo htmlspecialchars($employee['mecano']); ?>">
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">الرقم الآلي :</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($employee['mecano']); ?>" disabled>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">الإسم و اللقب : <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nomprenom" value="<?php echo htmlspecialchars($employee['nom']); ?>" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">الحالة العائليّة و المهنيّة :</label>
                <select name="statut" class="form-control">
                    <option value="">غير محدّد</option>
                    <option value="MariéExecution" <?php echo ($employee['statut'] == 'MariéExecution') ? 'selected' : ''; ?>>متزوّج/تنفيذ</option>
                    <option value="MariéMaitrise" <?php echo ($employee['statut'] == 'MariéMaitrise') ? 'selected' : ''; ?>>متزوّج/تسيير</option>
                    <option value="CélibExecution" <?php echo ($employee['statut'] == 'CélibExecution') ? 'selected' : ''; ?>>أعزب/تنفيذ</option>
                    <option value="CélibMaitrise" <?php echo ($employee['statut'] == 'CélibMaitrise') ? 'selected' : ''; ?>>أعزب/تسيير</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">رقم بطاقة التعريف : <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="cin" value="<?php echo htmlspecialchars($employee['cin']); ?>" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">تاريخ الولادة : <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="Dnaissance" value="<?php echo htmlspecialchars($employee['daten']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">تاريخ الانتداب : <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="Drecrutement" value="<?php echo htmlspecialchars($employee['daterec']); ?>" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">الرتبة :</label>
                <select name="grade" class="form-control">
                    <?php 
                    foreach ($titres as $titre): ?>
                        <option value="<?php echo $titre['id']; ?>" <?php echo ($employee['titre'] == $titre['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($titre['libellet']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">المصلحة :</label>
                <select name="service" class="form-control">
                    <?php 
                    foreach ($services as $service): ?>
                        <option value="<?php echo $service['id']; ?>" <?php echo ($employee['idservice'] == $service['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($service['libelle']); ?>
                        </option>
                    <?php endforeach; ?>
                    <option value="0" <?php echo ($employee['idservice'] == 0) ? 'selected' : ''; ?>>بدون مصلحة</option>
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">القسم :</label>
                <select name="dep" class="form-control">
                    <?php 
                    foreach ($departments as $dep): ?>
                        <option value="<?php echo $dep['id']; ?>" <?php echo ($employee['dep'] == $dep['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($dep['depar']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">السلم :</label>
                <input type="text" class="form-control" name="echelle" value="<?php echo htmlspecialchars($employee['echelle']); ?>">
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">الدرجة :</label>
                <input type="text" class="form-control" name="echelon" value="<?php echo htmlspecialchars($employee['degree']); ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">الصفة :</label>
                <select name="etat" class="form-control">
                    <option value=""> </option>
                    <option value="0" <?php echo ($employee['contrastage'] == 0) ? 'selected' : ''; ?>>مترسم</option>
                    <option value="1" <?php echo ($employee['contrastage'] == 1) ? 'selected' : ''; ?>>متربص</option>
                    <option value="2" <?php echo ($employee['contrastage'] == 2) ? 'selected' : ''; ?>>متعاقد</option>
                    <option value="3" <?php echo ($employee['contrastage'] == 3) ? 'selected' : ''; ?>>ملحق</option>
                    <option value="4" <?php echo ($employee['contrastage'] == 4) ? 'selected' : ''; ?>>متقاعد</option>
                    <option value="5" <?php echo ($employee['contrastage'] == 5) ? 'selected' : ''; ?>>ملحق خارج الشركة</option>
                    <option value="6" <?php echo ($employee['contrastage'] == 6) ? 'selected' : ''; ?>>إحالة على عدم المباشرة</option>
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">السلك :</label>
                <select name="fil" class="form-control">
                    <option value="A" <?php echo ($employee['fil'] == 'A') ? 'selected' : ''; ?>>إداري</option>
                    <option value="T" <?php echo ($employee['fil'] == 'T') ? 'selected' : ''; ?>>تقني</option>
                    <option value="E" <?php echo ($employee['fil'] == 'E') ? 'selected' : ''; ?>>إستغلال</option>
                    <option value="EC" <?php echo ($employee['fil'] == 'EC') ? 'selected' : ''; ?>>سائق</option>
                    <option value="ER" <?php echo ($employee['fil'] == 'ER') ? 'selected' : ''; ?>>قابض</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">التصنيف :</label>
                <select name="numpers" class="form-control">
                    <option value="A" <?php echo ($employee['numpers'] == 'A') ? 'selected' : ''; ?>>إداري</option>
                    <option value="T" <?php echo ($employee['numpers'] == 'T') ? 'selected' : ''; ?>>تقني</option>
                    <option value="EC" <?php echo ($employee['numpers'] == 'EC') ? 'selected' : ''; ?>>سائق</option>
                    <option value="ER" <?php echo ($employee['numpers'] == 'ER') ? 'selected' : ''; ?>>قابض</option>
                    <option value="TN" <?php echo ($employee['numpers'] == 'TN') ? 'selected' : ''; ?>>تنظيف</option>
                    <option value="EA" <?php echo ($employee['numpers'] == 'EA') ? 'selected' : ''; ?>>إداري إستغلال</option>
                    <option value="ECT" <?php echo ($employee['numpers'] == 'ECT') ? 'selected' : ''; ?>>مراقبة</option>
                    <option value="AG" <?php echo ($employee['numpers'] == 'AG') ? 'selected' : ''; ?>>حراسة</option>
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">باقي رصيد إجازات N-2 :</label>
                <input type="number" class="form-control" name="restconge" value="<?php echo htmlspecialchars($employee['anneeprec'] ?? 0); ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">رصيد إجازات السّنة الحاليّة :</label>
                <input type="number" class="form-control" name="soldeconge" value="<?php echo htmlspecialchars($employee['anneeactu'] ?? 0); ?>">
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">يوم الراحة الأسبوعية :</label>
                <select name="Jourrepos" class="form-control">
                    <option value="10" <?php echo ($employee['jrepos'] == 10) ? 'selected' : ''; ?>>اداري سبت و احد</option>
                    <option value="0" <?php echo ($employee['jrepos'] == 0) ? 'selected' : ''; ?>>احد</option>
                    <option value="1" <?php echo ($employee['jrepos'] == 1) ? 'selected' : ''; ?>>اثنين</option>
                    <option value="2" <?php echo ($employee['jrepos'] == 2) ? 'selected' : ''; ?>>ثلاثاء</option>
                    <option value="3" <?php echo ($employee['jrepos'] == 3) ? 'selected' : ''; ?>>اربعاء</option>
                    <option value="4" <?php echo ($employee['jrepos'] == 4) ? 'selected' : ''; ?>>خميس</option>
                    <option value="5" <?php echo ($employee['jrepos'] == 5) ? 'selected' : ''; ?>>جمعة</option>
                    <option value="6" <?php echo ($employee['jrepos'] == 6) ? 'selected' : ''; ?>>سبت</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">تسجيل الحضور :</label>
                <select name="pointage" id="pointageSelect" class="form-control">
                    <option value="0" <?php echo ($employee['pointagemachine'] == 0) ? 'selected' : ''; ?>>غير معنيّ</option>
                    <option value="1" <?php echo ($employee['pointagemachine'] == 1) ? 'selected' : ''; ?>>معنيّ</option>
                </select>
            </div>
        </div>
        
        <div class="row" id="pointageDateRow">
            <div class="col-md-12 mb-3">
                <label class="form-label">بداية تسجيل الحضور :</label>
                <input type="date" class="form-control" name="Dpointage" id="DpointageInput" value="<?php echo htmlspecialchars($employee['date_debut_pointage'] ?? ''); ?>">
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">رقم الضمان الإجتماعي :</label>
                <input type="text" class="form-control" name="codesocial" value="<?php echo htmlspecialchars($employee['ncnss'] ?? ''); ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">رقم التأمين الجماعي :</label>
                <input type="text" class="form-control" name="codeassurance" value="<?php echo htmlspecialchars($employee['nassurance'] ?? ''); ?>">
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">يتمتّع بالحليب :</label>
                <select name="lait" class="form-control">
                    <option value="1" <?php echo ($employee['lait'] == 1) ? 'selected' : ''; ?>>نعم</option>
                    <option value="0" <?php echo ($employee['lait'] == 0) ? 'selected' : ''; ?>>لا</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">الجنس :</label>
                <select name="sexe" class="form-control">
                    <option value="M" <?php echo ($employee['sexe'] == 'M') ? 'selected' : ''; ?>>ذكر</option>
                    <option value="F" <?php echo ($employee['sexe'] == 'F') ? 'selected' : ''; ?>>أنثى</option>
                </select>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-primary" id="submitBtn">حفظ التعديلات</button>
        </div>
    </form>
</div>

<script>
// Initialisation après le chargement du modal
$(document).ready(function() {
    // Récupérer les éléments
    var pointageSelect = document.getElementById('pointageSelect');
    var pointageDateRow = document.getElementById('pointageDateRow');
    var dpointageInput = document.getElementById('DpointageInput');
    
    // Fonction pour afficher/masquer le champ date de pointage
    function togglePointageDate() {
        if (pointageSelect && pointageSelect.value === '1') {
            pointageDateRow.style.display = 'block';
            if (dpointageInput) {
                dpointageInput.required = true;
            }
        } else {
            pointageDateRow.style.display = 'none';
            if (dpointageInput) {
                dpointageInput.required = false;
            }
        }
    }
    
    // Initialiser l'état
    if (pointageSelect) {
        pointageSelect.addEventListener('change', togglePointageDate);
        togglePointageDate();
    }
});
</script>