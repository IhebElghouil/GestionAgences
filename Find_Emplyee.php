<html>
<?php
session_start();
?>
<head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Ajouter Bootstrap JS pour la gestion du modal -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    // Fonction pour mettre à jour le fil en fonction du grade sélectionné
    function updateFilFromGrade() {
        var selectedGrade = $('#gradeSelect').val();
        if (selectedGrade && selectedGrade !== '') {
            $.ajax({
                type: 'POST',
                url: 'get_grade_class.php',
                data: { grade_id: selectedGrade },
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.classe) {
                        $('#numpers').val(response.classe);
                        console.log('Fil updated to: ' + response.classe);
                    } else {
                        console.log('No classe found for grade: ' + selectedGrade);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching grade class:', error);
                }
            });
        }
    }
    
    // Écouter les changements sur le select grade
    $(document).on('change', '#gradeSelect', function() {
        updateFilFromGrade();
    });
    
    // Au chargement de la page, définir fil en fonction du grade actuel
    updateFilFromGrade();
    
    // Gestion de la soumission AJAX
    $(document).on('submit', '#updateForm', function(e) {
        e.preventDefault();
        console.log('Form submitted'); // Debug

        // Désactiver le bouton de soumission
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> جاري الحفظ...');

        // Récupérer les données du formulaire
        var formData = $(this).serialize();
        console.log('Form data:', formData);

        $.ajax({
            type: 'POST',
            url: 'save_update.php',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log('Response:', response);
                
                // Réactiver le bouton
                submitBtn.prop('disabled', false).html(originalText);
                
                if (response.success) {
                    // Fermer le modal correctement
                    var modalElement = document.getElementById('editModal');
                    if (modalElement) {
                        var modal = bootstrap.Modal.getInstance(modalElement);
                        if (modal) {
                            modal.hide();
                        } else {
                            // Si l'instance n'existe pas, la créer et la fermer
                            var newModal = new bootstrap.Modal(modalElement);
                            newModal.hide();
                        }
                    }
                    
                    // Afficher le message de succès
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح',
                        text: response.message,
                        confirmButtonText: 'موافق',
                        confirmButtonColor: '#28a745',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: true
                    }).then((result) => {
                        if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                            // Recharger la page parente
                            window.parent.location.reload();
                            // Ou si c'est dans un iframe, fermer et recharger
                            if (window.parent !== window) {
                                window.parent.location.reload();
                            } else {
                                location.reload();
                            }
                        }
                    });
                } else {
                    // Afficher le message d'erreur
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: response.message || 'حدث خطأ في حفظ البيانات',
                        confirmButtonText: 'موافق',
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', xhr.status, xhr.statusText);
                console.error('Response:', xhr.responseText);
                
                // Réactiver le bouton
                submitBtn.prop('disabled', false).html(originalText);
                
                let errorMessage = 'حدث خطأ في الاتصال بالخادم. الرجاء المحاولة مرة أخرى.';
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.message) {
                        errorMessage = response.message;
                    }
                } catch(e) {
                    console.log('Response is not JSON');
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ في الاتصال',
                    text: errorMessage,
                    confirmButtonText: 'موافق'
                });
            }
        });
    });
});
</script>
</head>
<body>
<?php

require('connection.php');

$mecano = isset($_GET['mecano']) ? mysqli_real_escape_string($connection, $_GET['mecano']) : '';

// Vérifier que $mecano n'est pas vide
if (empty($mecano)) {
    echo "<div class='alert alert-danger'>رقم الموظف غير صحيح</div>";
    exit;
}

// Correction de la requête SQL
$re = mysqli_query($connection, "SELECT stuf.*, titres.libellet, titres.classe, dep.depar, nbconge.*, social.* 
                                 FROM stuf 
                                 LEFT JOIN titres ON titres.id = stuf.titre 
                                 LEFT JOIN dep ON dep.id = stuf.dep 
                                 LEFT JOIN nbconge ON nbconge.mecano = stuf.mecano 
                                 LEFT JOIN social ON social.mecano = stuf.mecano 
                                 WHERE stuf.mecano = '$mecano'");

if (!$re) {
    die("Erreur SQL: " . mysqli_error($connection));
}

$r = mysqli_fetch_assoc($re);

// Si pas de résultat, afficher un message
if (!$r) {
    echo "<div class='alert alert-danger'>لم يتم العثور على الموظف</div>";
    exit;
}
?>

<div class="modal-header">
  <h5 class="modal-title">تحيين</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
</div>
<div class="modal-body">
  <form id="updateForm" method="post">
    <input type="hidden" name="recipientname" value="<?php echo htmlspecialchars($mecano); ?>">

    <div class="form-group row" align="right" dir="rtl">
        <label class="col-sm-6 col-form-label">الإسم و اللقب :</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="nomprenom" value="<?php echo htmlspecialchars($r['nom'] ?? ''); ?>" readonly align="right" dir="rtl">
        </div>
    </div>
    
    <div class="form-group row" dir="rtl" style="text-align: right;">
        <label class="col-sm-6 col-form-label">الحالة العائليّة و المهنيّة : </label>
        <div class="col-sm-6">
            <?php
            $statut_actuel = $r['statut'] ?? '';
            $options = [
                "" => "غير محدّد",
                "MariéExecution" => "متزوّج/تنفيذ",
                "MariéMaitrise"  => "متزوّج/تسيير",
                "CélibExecution" => "أعزب/تنفيذ",
                "CélibMaitrise"  => "أعزب/تسيير"
            ];
            ?>
            <select name="statut" id="statut" class="form-control">
                <?php
                foreach ($options as $value => $label) {
                    $selected = ($statut_actuel == $value) ? 'selected' : '';
                    echo "<option value=\"$value\" $selected>$label</option>";
                }
                ?>
            </select>
        </div>
    </div>
         
    <div class="form-group row" align="right" dir="rtl">
        <label class="col-sm-7 col-form-label">رقم بطاقة التعريف :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="cin" value="<?php echo htmlspecialchars($r['cin'] ?? ''); ?>" align="right" dir="rtl">
        </div>
        
        <label class="col-sm-7 col-form-label">تاريخ الولادة :</label>
        <div class="col-sm-5">
            <input type="date" class="form-control" name="Dnaissance" value="<?php echo htmlspecialchars($r['daten'] ?? ''); ?>" align="right" dir="rtl">
        </div>
          
        <label class="col-sm-7 col-form-label">تاريخ الانتداب :</label>
        <div class="col-sm-5">
            <input type="date" class="form-control" name="Drecrutement" value="<?php echo htmlspecialchars($r['daterec'] ?? ''); ?>" align="right" dir="rtl">
        </div>
          
        <label class="col-sm-4 col-form-label">الرتبة :</label>
        <div class="col-sm-8">
            <select name="grade" id="gradeSelect" class="form-control">
                <?php 
                $reqsn = "SELECT * FROM titres GROUP BY libellet";
                $ssn = mysqli_query($connection, $reqsn);
                if ($ssn) {
                    while($rsn = mysqli_fetch_assoc($ssn)) {
                        $selected = (@$r['titre'] == $rsn['id']) ? 'selected="selected"' : '';
                        echo "<option value=\"" . $rsn['id'] . "\" $selected>" . htmlspecialchars($rsn['libellet']) . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <label class="col-sm-5 col-form-label">المصلحة :</label>
        <div class="col-sm-7">
            <select name="service" id="select6" class="form-control">
                <?php 
                $reqsn = "SELECT * FROM service where sb=5";
                $ssn = mysqli_query($connection, $reqsn);
                if ($ssn) {
                    while($rsn = mysqli_fetch_assoc($ssn)) {
                        $selected = (@$r['idservice'] == $rsn['id']) ? 'selected="selected"' : '';
                        echo "<option value=\"" . $rsn['id'] . "\" $selected>" . htmlspecialchars($rsn['libellet']) . "</option>";
                        
                        $reqsn2 = "SELECT * FROM service WHERE iddirection='" . $rsn['id'] . "'";
                        $ssn2 = mysqli_query($connection, $reqsn2);
                        if ($ssn2) {
                            while($rsn2 = mysqli_fetch_assoc($ssn2)) {
                                $selected2 = (@$r['idservice'] == $rsn2['id']) ? 'selected="selected"' : '';
                                echo "<option value=\"" . $rsn2['id'] . "\" $selected2>---> " . htmlspecialchars($rsn2['libellet']) . "</option>";
                                
                                $reqsn3 = "SELECT * FROM service WHERE iddirection='" . $rsn2['id'] . "'";
                                $ssn3 = mysqli_query($connection, $reqsn3);
                                if ($ssn3) {
                                    while($rsn3 = mysqli_fetch_assoc($ssn3)) {
                                        $selected3 = (@$r['idservice'] == $rsn3['id']) ? 'selected="selected"' : '';
                                        echo "<option value=\"" . $rsn3['id'] . "\" $selected3> &nbsp;&nbsp;&nbsp;&nbsp;------> " . htmlspecialchars($rsn3['libellet']) . "</option>";
                                    }
                                }
                            }
                        }
                    }
                }
                ?>
                <option value="0">بدون مصلحة</option> 
            </select>
        </div>
          
        <label class="col-sm-7 col-form-label">القسم :</label>
        <div class="col-sm-5">
            <select name="dep" id="select7" class="form-control">
                <?php 
                $reqsn = "SELECT * FROM dep";
                $ssn = mysqli_query($connection, $reqsn);
                if ($ssn) {
                    while($rsn = mysqli_fetch_assoc($ssn)) {
                        $selected = (@$r['dep'] == $rsn['id']) ? 'selected="selected"' : '';
                        echo "<option value=\"" . $rsn['id'] . "\" $selected>" . htmlspecialchars($rsn['depar']) . "</option>";
                    }
                }
                ?>
            </select>
        </div>
          
        <label class="col-sm-7 col-form-label">السلم :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="echelle" value="<?php echo htmlspecialchars($r['echelle'] ?? ''); ?>" align="right" dir="rtl">
        </div>
          
        <label class="col-sm-7 col-form-label">الدرجة :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="echelon" value="<?php echo htmlspecialchars($r['degree'] ?? ''); ?>" align="right" dir="rtl">
        </div>
           
        <label class="col-sm-7 col-form-label">الصفة :</label>
        <div class="col-sm-5">
            <select name="etat" id="select8" class="form-control">
                <option value=""> </option>
                <option value="0" <?php if(@$r['contrastage']==0) echo 'selected="selected"'; ?>>مترسم</option>
                <option value="1" <?php if(@$r['contrastage']==1) echo 'selected="selected"'; ?>>متربص</option>
                <option value="2" <?php if(@$r['contrastage']==2) echo 'selected="selected"'; ?>>متعاقد</option>
                <option value="3" <?php if(@$r['contrastage']==3) echo 'selected="selected"'; ?>>إلحاق لدى الشركة</option>
                <option value="4" <?php if(@$r['contrastage']==4) echo 'selected="selected"'; ?>>متقاعد</option>
                <option value="5" <?php if(@$r['contrastage']==5) echo 'selected="selected"'; ?>>إلحاق خارج الشركة</option>
                <option value="6" <?php if(@$r['contrastage']==6) echo 'selected="selected"'; ?>>عدم المباشرة الخاصّة</option>
            </select>
        </div>
         
        <label class="col-sm-7 col-form-label">السلك :</label>
        <div class="col-sm-5">
            <select name="numpers" id="numpers" class="form-control">
                <option value="A" <?php if(@$r['numpers']=="A") echo 'selected="selected"'; ?>>إداري</option>
                <option value="T" <?php if(@$r['numpers']=="T") echo 'selected="selected"'; ?>>تقني</option>
                <option value="E" <?php if(@$r['numpers']=="E") echo 'selected="selected"'; ?>>إستغلال</option>
                <option value="EC" <?php if(@$r['numpers']=="EC") echo 'selected="selected"'; ?>>سائق</option>
                <option value="ER" <?php if(@$r['numpers']=="ER") echo 'selected="selected"'; ?>>قابض</option>
            </select>
        </div>
          <label class="col-sm-7 col-form-label">التصنيف :</label>
<div class="col-sm-5">
    <select name="fil" id="fil" class="form-control">
        <option value="A" <?php if(@$r['fil']=="A") echo 'selected="selected"'; ?>>إداري</option>
        <option value="T" <?php if(@$r['fil']=="T") echo 'selected="selected"'; ?>>تقني</option>
        <option value="EC" <?php if(@$r['fil']=="EC") echo 'selected="selected"'; ?>>سائق</option>
        <option value="ER" <?php if(@$r['fil']=="ER") echo 'selected="selected"'; ?>>قابض</option>
        <option value="TN" <?php if(@$r['fil']=="TN") echo 'selected="selected"'; ?>>تنظيف</option>
        <option value="EA" <?php if(@$r['fil']=="EA") echo 'selected="selected"'; ?>>إداري إستغلال</option>
        <option value="ECT" <?php if(@$r['fil']=="ECT") echo 'selected="selected"'; ?>>مراقبة</option>
        <option value="AG" <?php if(@$r['fil']=="AG") echo 'selected="selected"'; ?>>حراسة</option>
    </select>
</div>
        <label class="col-sm-7 col-form-label">باقي رصيد إجازات :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="restconge" value="<?php echo htmlspecialchars($r['anneeprec'] ?? ''); ?>" align="right" dir="rtl">
        </div> 

        <label class="col-sm-7 col-form-label">رصيد إجازات السّنة الحاليّة :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="soldeconge" value="<?php echo htmlspecialchars($r['anneeactu'] ?? ''); ?>" align="right" dir="rtl">
        </div>
          
        <label class="col-sm-7 col-form-label">يوم الراحة الأسبوعية :</label>
        <div class="col-sm-5">
            <select name="Jourrepos" dir="rtl" class="form-control">
                <option value="10" <?php if(@$r['jrepos']==10) echo 'selected="selected"'; ?>>اداري سبت و احد</option>
                <option value="0" <?php if(@$r['jrepos']==0) echo 'selected="selected"'; ?>>احد</option>
                <option value="1" <?php if(@$r['jrepos']==1) echo 'selected="selected"'; ?>>اثنين</option>
                <option value="2" <?php if(@$r['jrepos']==2) echo 'selected="selected"'; ?>>ثلاثاء</option>
                <option value="3" <?php if(@$r['jrepos']==3) echo 'selected="selected"'; ?>>اربعاء</option>
                <option value="4" <?php if(@$r['jrepos']==4) echo 'selected="selected"'; ?>>خميس</option>
                <option value="5" <?php if(@$r['jrepos']==5) echo 'selected="selected"'; ?>>جمعة</option>
                <option value="6" <?php if(@$r['jrepos']==6) echo 'selected="selected"'; ?>>سبت</option>
            </select>
        </div>
        
        <label class="col-sm-7 col-form-label">تسجيل الحضور  :</label>
        <div class="col-sm-5">
            <select name="pointage" id="pointage" dir="rtl" class="form-control">
                <option value="0" <?php if(@$r['pointagemachine']==0) echo 'selected="selected"'; ?>>غير معنيّ</option>
                <option value="1" <?php if(@$r['pointagemachine']==1) echo 'selected="selected"'; ?>>معنيّ</option>
            </select>
        </div>
		<?php if (!empty($r['pointagemachine']) && $r['pointagemachine'] == 1): ?>
    
    <label class="col-sm-7 col-form-label">بداية تسجيل الحضور :</label>
    
    <div class="col-sm-5">
        <input type="date" 
               class="form-control" 
               name="Dpointage" 
               value="<?= htmlspecialchars($r['date_debut_pointage'] ?? '') ?>" 
               align="right" dir="rtl">
    </div>

<?php endif; ?>
        <label class="col-sm-7 col-form-label">رقم الضمان الإجتماعي :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="codesocial" value="<?php echo htmlspecialchars($r['ncnss'] ?? ''); ?>" align="right" dir="rtl">
        </div>
          
        <label class="col-sm-7 col-form-label">رقم التأمين الجماعي :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="codeassurance" value="<?php echo htmlspecialchars($r['nassurance'] ?? ''); ?>" align="right" dir="rtl">
        </div>
        
        <label class="col-sm-7 col-form-label">يتمتّع بالحليب :</label>
        <div class="col-sm-5">
            <select name="lait" dir="rtl" class="form-control">
                <option value="1" <?php if(@$r['lait']==1) echo 'selected="selected"'; ?>>نعم</option>
                <option value="0" <?php if(@$r['lait']==0) echo 'selected="selected"'; ?>>لا</option>
            </select>
        </div>
           
        <label class="col-sm-7 col-form-label">الجنس :</label>
        <div class="col-sm-5">
            <select name="sexe" dir="rtl" class="form-control">
                <option value="M" <?php if(@$r['sexe']=="M") echo 'selected="selected"'; ?>>ذكر</option>
                <option value="F" <?php if(@$r['sexe']=="F") echo 'selected="selected"'; ?>>أنثى</option>
            </select>
        </div>
    </div>
    
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">حفظ التحيينات</button>
    </div>
  </form>
</div>
</body>
</html>