<div class="modal-header">
    <h5 class="modal-title">
        <i class="fas fa-edit me-2"></i>
        تعديل <?php echo $typeInfo['name']; ?>
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form method="POST" action="index.php?action=badges_update">
    <div class="modal-body">
        <input type="hidden" name="mecano" value="<?php echo $mecano; ?>">
        <input type="hidden" name="type" value="<?php echo $type; ?>">
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">الرقم الآلي</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($mecano); ?>" disabled>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">الإسم و اللقب</label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($cardInfo['nom'] ?? ''); ?>" disabled>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">رقم <?php echo $typeInfo['name']; ?></label>
                <input type="text" name="numcarte" class="form-control" value="<?php echo htmlspecialchars($cardInfo['numcarte'] ?? ''); ?>" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">تاريخ الإصدار</label>
                <input type="date" name="dateemission" class="form-control" value="<?php echo htmlspecialchars($cardInfo['dateemission'] ?? ''); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">نهاية الصلوحيّة</label>
                <input type="date" name="finvalidite" class="form-control" value="<?php echo htmlspecialchars($cardInfo['finvalidite'] ?? ''); ?>" required>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
        <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
    </div>
</form>

<script>
// Date validation
document.querySelector('form').addEventListener('submit', function(e) {
    var dateEmission = document.querySelector('input[name="dateemission"]').value;
    var dateFin = document.querySelector('input[name="finvalidite"]').value;
    
    if (dateEmission && dateFin && dateEmission > dateFin) {
        e.preventDefault();
        alert('تاريخ الإصدار لا يمكن أن يكون بعد تاريخ نهاية الصلاحية');
    }
});
</script>