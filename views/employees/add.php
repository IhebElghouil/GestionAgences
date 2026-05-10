<div class="modal-header">
    <h5 class="modal-title">
        <i class="fas fa-user-plus me-2"></i>
        إضافة عون جديد
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
</div>
<div class="modal-body">
    <form id="employeeForm" action="index.php?action=employees_save" method="POST">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">الرقم الآلي : <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="mecano" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">الإسم و اللقب : <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="nomprenom" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">الحالة العائليّة و المهنيّة :</label>
                <select name="statut" class="form-control">
                    <option value="">غير محدّد</option>
                    <option value="MariéExecution">متزوّج/تنفيذ</option>
                    <option value="MariéMaitrise">متزوّج/تسيير</option>
                    <option value="CélibExecution">أعزب/تنفيذ</option>
                    <option value="CélibMaitrise">أعزب/تسيير</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">رقم بطاقة التعريف : <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="cin" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">تاريخ الولادة : <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="Dnaissance" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">تاريخ الانتداب : <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="Drecrutement" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">الرتبة :</label>
                <select name="grade" class="form-control">
                    <?php 
                    foreach ($titres as $titre): ?>
                        <option value="<?php echo $titre['id']; ?>"><?php echo htmlspecialchars($titre['libellet']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">المصلحة :</label>
                <select name="service" class="form-control">
                    <?php 
                    foreach ($services as $service): ?>
                        <option value="<?php echo $service['id']; ?>"><?php echo htmlspecialchars($service['libellet']); ?></option>
                    <?php endforeach; ?>
                    <option value="0">بدون مصلحة</option>
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">القسم :</label>
                <select name="dep" class="form-control">
                    <?php 
                    foreach ($departments as $dep): ?>
                        <option value="<?php echo $dep['id']; ?>"><?php echo htmlspecialchars($dep['depar']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">السلم :</label>
                <input type="text" class="form-control" name="echelle">
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">الدرجة :</label>
                <input type="text" class="form-control" name="echelon">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">الصفة :</label>
                <select name="etat" class="form-control">
                    <option value=""> </option>
                    <option value="0">مترسم</option>
                    <option value="1">متربص</option>
                    <option value="2">متعاقد</option>
                    <option value="3">ملحق</option>
                    <option value="4">متقاعد</option>
                    <option value="5">ملحق خارج الشركة</option>
                    <option value="6">إحالة على عدم المباشرة</option>
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">السلك :</label>
                <select name="fil" class="form-control">
                    <option value="A">إداري</option>
                    <option value="T">تقني</option>
                    <option value="E">إستغلال</option>
                    <option value="EC">سائق</option>
                    <option value="ER">قابض</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">التصنيف :</label>
                <select name="numpers" class="form-control">
                    <option value="A">إداري</option>
                    <option value="T">تقني</option>
                    <option value="EC">سائق</option>
                    <option value="ER">قابض</option>
                    <option value="TN">تنظيف</option>
                    <option value="EA">إداري إستغلال</option>
                    <option value="ECT">مراقبة</option>
                    <option value="AG">حراسة</option>
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">باقي رصيد إجازات N-2 :</label>
                <input type="number" class="form-control" name="restconge" value="0">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">رصيد إجازات السّنة الحاليّة :</label>
                <input type="number" class="form-control" name="soldeconge" value="0">
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">يوم الراحة الأسبوعية :</label>
                <select name="Jourrepos" class="form-control">
                    <option value="10">اداري سبت و احد</option>
                    <option value="0">احد</option>
                    <option value="1">اثنين</option>
                    <option value="2">ثلاثاء</option>
                    <option value="3">اربعاء</option>
                    <option value="4">خميس</option>
                    <option value="5">جمعة</option>
                    <option value="6">سبت</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">تسجيل الحضور :</label>
                <select name="pointage" id="pointageSelect" class="form-control">
                    <option value="0">غير معنيّ</option>
                    <option value="1">معنيّ</option>
                </select>
            </div>
        </div>
        
        <div class="row" id="pointageDateRow">
            <div class="col-md-12 mb-3">
                <label class="form-label">بداية تسجيل الحضور :</label>
                <input type="date" class="form-control" name="Dpointage" id="DpointageInput">
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">رقم الضمان الإجتماعي :</label>
                <input type="text" class="form-control" name="codesocial">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">رقم التأمين الجماعي :</label>
                <input type="text" class="form-control" name="codeassurance">
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">يتمتّع بالحليب :</label>
                <select name="lait" class="form-control">
                    <option value="1">نعم</option>
                    <option value="0">لا</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">الجنس :</label>
                <select name="sexe" class="form-control">
                    <option value="M">ذكر</option>
                    <option value="F">أنثى</option>
                </select>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-primary" id="submitBtn">حفظ</button>
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
                dpointageInput.value = '';
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