<div class="modal-header">
    <h5 class="modal-title">
        <i class="fas fa-edit me-2"></i>
        تعديل شهادة طبية
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
</div>
<div class="modal-body">
    <form id="medicalEditForm" action="index.php?action=medical_update" method="POST">
        <input type="hidden" name="id" value="<?php echo $certificate['id']; ?>">
        
		  <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">الإسم و اللّقب <span class="text-danger">*</span></label>
                <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($certificate['nom'] ?? ''); ?>" readonly>
            </div>
        </div>
		
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">تاريخ الشهادة الطبية <span class="text-danger">*</span></label>
                <input type="date" name="datecertificat" class="form-control" value="<?php echo htmlspecialchars($certificate['datecertificat']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">تاريخ نهاية الصلاحية <span class="text-danger">*</span></label>
                <input type="date" name="datefin" class="form-control" value="<?php echo htmlspecialchars($certificate['DateFin']); ?>" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">رقم الشهادة الطبية <span class="text-danger">*</span></label>
                <input type="text" name="numcertifcat" class="form-control" value="<?php echo htmlspecialchars($certificate['numcertifcat']); ?>" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">الملاحظات</label>
                <textarea name="observation" class="form-control" rows="3"><?php echo htmlspecialchars($certificate['Observation']); ?></textarea>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">الحالة</label>
                <select name="etat" class="form-control">
                    <option value="1" <?php echo $certificate['Etat'] == 1 ? 'selected' : ''; ?>>صالحة</option>
                    <option value="0" <?php echo $certificate['Etat'] == 0 ? 'selected' : ''; ?>>غير صالحة</option>
                </select>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-primary" id="updateBtn">حفظ التعديلات</button>
        </div>
    </form>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    $('#medicalEditForm').on('submit', function(e) {
        e.preventDefault();
        
        var dateCertif = $('input[name="datecertificat"]').val();
        var dateFin = $('input[name="datefin"]').val();
        
        if (dateCertif && dateFin && dateCertif > dateFin) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'تاريخ الشهادة لا يمكن أن يكون بعد تاريخ نهاية الصلاحية',
                confirmButtonText: 'موافق'
            });
            return false;
        }
        
        var numcertif = $('input[name="numcertifcat"]').val();
        if (!numcertif) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'الرجاء إدخال رقم الشهادة الطبية',
                confirmButtonText: 'موافق'
            });
            return false;
        }
        
        // Désactiver le bouton pendant l'envoi
        var updateBtn = $('#updateBtn');
        updateBtn.prop('disabled', true);
        updateBtn.html('<i class="fas fa-spinner fa-spin me-2"></i> جاري الحفظ...');
        
        // Envoyer le formulaire via AJAX
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم بنجاح',
                        text: response.message,
                        confirmButtonText: 'موافق'
                    }).then(() => {
                        $('#editModal').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: response.message,
                        confirmButtonText: 'موافق'
                    });
                    updateBtn.prop('disabled', false);
                    updateBtn.html('حفظ التعديلات');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء حفظ البيانات',
                    confirmButtonText: 'موافق'
                });
                updateBtn.prop('disabled', false);
                updateBtn.html('حفظ التعديلات');
            }
        });
    });
});
</script>