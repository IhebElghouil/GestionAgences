<?php
session_start();
require('connection.php');

if (isset($_GET['mecano'])) {
    $mecano = mysqli_real_escape_string($connection, $_GET['mecano']);
    
    // Récupérer les informations de l'employé
    $query = "SELECT DISTINCT social.mecano, cin, nomfr,observations, rib
            FROM stuf
            LEFT JOIN dep ON stuf.dep = dep.id
            LEFT JOIN social ON social.mecano = stuf.mecano
            LEFT JOIN titres ON titres.id = stuf.titre
            LEFT JOIN cartes ON stuf.mecano = cartes.mecano
            WHERE social.mecano = ?
            GROUP BY stuf.mecano
            ORDER BY stuf.mecano ASC";
    $stmt = mysqli_prepare($connection, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $mecano);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $mecano, $cin, $nomfr, $observations, $rib);
        
        if (mysqli_stmt_fetch($stmt)) {
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <form id="updateRIBForm">
                <input type="hidden" name="mecano" value="<?php echo htmlspecialchars($mecano); ?>">
                
                <div class="mb-3">
                    <label class="form-label">الرقم الآلي</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($mecano); ?>" readonly>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">رقم بطاقة التعريف الوطنيّة</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($cin); ?>" readonly>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">الإسم واللقب</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($nomfr); ?>" readonly>
                </div>
                
                <div class="mb-3">
                    <label for="observations" class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                    <select class="form-select" id="observations" name="observations" required>
                        <option value="">-- اختر طريقة الدفع --</option>
                        <option value="virement bancaire" <?php echo ($observations == 'Virement bancaire') ? 'selected' : ''; ?>>تحويل بنكي</option>
                        <option value="carte STB" <?php echo ($observations == 'carte STB') ? 'selected' : ''; ?>>بطاقة STB</option>
                    </select>
                    <div class="form-text">اختر طريقة الدفع المناسبة للعون</div>
                </div>
                
                <div class="mb-3">
                    <label for="rib" class="form-label">رقم الحساب البنكي (RIB) 
                        <span class="text-danger">*</span>
                        <small class="text-muted" id="ribRequiredText"></small>
                    </label>
                    <input type="text" class="form-control" id="rib" name="rib" value="<?php echo htmlspecialchars($rib); ?>" 
                           placeholder="أدخل رقم الحساب البنكي" maxlength="20">
                    <div class="form-text">يجب أن يتكون RIB من 20 رقمًا (مطلوب للتحويل البنكي)</div>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Fonction pour gérer la visibilité du champ RIB
    function toggleRIBField() {
        const observations = $('#observations').val();
        const ribField = $('#rib');
        const ribRequiredText = $('#ribRequiredText');
        
        if (observations === 'virement bancaire') {
            ribField.prop('required', true);
            ribRequiredText.text('(مطلوب)');
            ribField.parent().show();
        } else if (observations === 'carte STB') {
            ribField.prop('required', false);
            ribRequiredText.text('(اختياري)');
            ribField.parent().show();
        } else {
            ribField.prop('required', false);
            ribRequiredText.text('');
            ribField.parent().show();
        }
    }
    
    // Initialiser l'état du champ RIB
    toggleRIBField();
    
    // Écouter les changements du select observations
    $('#observations').on('change', function() {
        toggleRIBField();
    });
    
    // Validation du formulaire
    $('#updateRIBForm').on('submit', function(e) {
        const observations = $('#observations').val();
        const rib = $('#rib').val();
        
        if (observations === 'virement bancaire') {
            if (!rib) {
                e.preventDefault();
                alert('رقم الحساب البنكي مطلوب للتحويل البنكي');
                $('#rib').focus();
                return false;
            }
            
            if (!/^\d{20}$/.test(rib)) {
                e.preventDefault();
                alert('يجب أن يتكون RIB من 20 رقمًا exactly');
                $('#rib').focus();
                return false;
            }
        }
        
        if (observations === 'carte STB' && rib && !/^\d{20}$/.test(rib)) {
            e.preventDefault();
            alert('إذا قمت بإدخال RIB، يجب أن يتكون من 20 رقمًا exactly');
            $('#rib').focus();
            return false;
        }
        
        return true;
    });
});
</script>
<?php
        } else {
            echo '<div class="alert alert-danger">لم يتم العثور على العون</div>';
        }
        mysqli_stmt_close($stmt);
    } else {
        echo '<div class="alert alert-danger">خطأ في الاستعلام</div>';
    }
} else {
    echo '<div class="alert alert-danger">لم يتم تحديد رقم آلي</div>';
}

mysqli_close($connection);
?>