<div class="modal-header">
    <h5 class="modal-title">
        <i class="fas fa-file-medical me-2"></i>
        إضافة شهادة طبية جديدة
    </h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
</div>
<div class="modal-body">
    <form id="medicalForm" action="index.php?action=medical_add" method="POST">
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">الرقم الآلي <span class="text-danger">*</span></label>
                <select name="statut" id="statut" class="form-control" required>
                    <option value="">اختر الرقم الآلي</option>
                    <?php foreach ($employees as $emp): ?>
                        <option value="<?php echo $emp; ?>"><?php echo $emp; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">الإسم و اللقب</label>
                <input type="text" class="form-control" id="nomprenom" readonly>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">تاريخ الشهادة الطبية <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="datecertif" id="datecertif" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">تاريخ نهاية الصلاحية <span class="text-danger">*</span></label>
                <input type="date" class="form-control" name="finvaliditecertif" id="finvaliditecertif" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">رقم الشهادة الطبية <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="numcertif" id="numcertif" required>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">الملاحظات</label>
                <textarea class="form-control" name="observations" id="observations" rows="3" placeholder="أدخل الملاحظات هنا..."></textarea>
            </div>
        </div>
        
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-success" id="submitBtn">حفظ الشهادة</button>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    // AJAX pour récupérer le nom de l'employé
    $('#statut').on('change', function() {
        var mecano = $(this).val();
        if (mecano) {
            $.ajax({
                url: 'index.php?action=medical_get_name',
                type: 'POST',
                dataType: 'json',
                data: { mecano: mecano },
                success: function(data) {
                    $('#nomprenom').val(data.nomprenom);
                },
                error: function() {
                    $('#nomprenom').val('خطأ في الإتصال');
                }
            });
        } else {
            $('#nomprenom').val('');
        }
    });
    
    // MÊME LOGIQUE QUE edit.php
    $('#medicalForm').on('submit', function(e) {
        e.preventDefault();
        
        var dateCertif = $('#datecertif').val();
        var dateFin = $('#finvaliditecertif').val();
        
        if (dateCertif && dateFin && dateCertif > dateFin) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'تاريخ الشهادة لا يمكن أن يكون بعد تاريخ نهاية الصلاحية',
                confirmButtonText: 'موافق'
            });
            return false;
        }
        
        var mecano = $('#statut').val();
        if (!mecano) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'الرجاء اختيار الرقم الآلي',
                confirmButtonText: 'موافق'
            });
            return false;
        }
        
        var numcertif = $('#numcertif').val();
        if (!numcertif) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'الرجاء إدخال رقم الشهادة الطبية',
                confirmButtonText: 'موافق'
            });
            return false;
        }
        
        // Désactiver le bouton pendant l'envoi (comme dans edit.php)
        var submitBtn = $('#submitBtn');
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i> جاري الحفظ...');
        
        // Envoyer le formulaire via AJAX (comme dans edit.php)
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
                    submitBtn.prop('disabled', false);
                    submitBtn.html('حفظ الشهادة');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'حدث خطأ أثناء حفظ البيانات',
                    confirmButtonText: 'موافق'
                });
                submitBtn.prop('disabled', false);
                submitBtn.html('حفظ الشهادة');
            }
        });
    });
});
</script>