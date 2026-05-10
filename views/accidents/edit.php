<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .modal-header {
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            border: none;
            padding: 15px 20px;
        }
        
        .modal-header .modal-title {
            font-weight: 700;
            font-size: 1.2rem;
        }
        
        .modal-header .btn-close {
            background-color: white;
            opacity: 0.8;
            transition: all 0.3s ease;
        }
        
        .modal-header .btn-close:hover {
            opacity: 1;
            transform: scale(1.1);
        }
        
        .modal-body {
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .modal-footer {
            border-top: 1px solid #e9ecef;
            padding: 15px 20px;
            direction: ltr;
        }
        
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            display: block;
        }
        
        .form-control, .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 10px 12px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
            outline: none;
        }
        
        .form-control[disabled] {
            background-color: #f8f9fa;
            color: #6c757d;
        }
        
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }
        
        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }
        
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: scale(0.8);
        }
        
        .modal.show .modal-dialog {
            transform: scale(1);
        }
        
        /* Style pour les options du select */
        .type-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .type-badge {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-left: 8px;
        }
        
        .type-badge.primary { background-color: #3498db; }
        .type-badge.warning { background-color: #f39c12; }
        .type-badge.danger { background-color: #e74c3c; }
        .type-badge.secondary { background-color: #6c757d; }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<div class="modal-header">
    <h5 class="modal-title">
        <i class="fas fa-edit me-2"></i>
        تعديل حادث شغل
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<!-- Formulaire principal pour les informations générales -->
<form method="POST" action="index.php?action=accidents_update" id="mainForm">
    <div class="modal-body">
        <input type="hidden" name="id" value="<?php echo $accident['id']; ?>">
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    <i class="fas fa-id-card text-primary me-1"></i>
                    الرقم الآلي
                </label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($accident['mecano']); ?>" disabled>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    <i class="fas fa-user text-primary me-1"></i>
                    الإسم و اللقب
                </label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($accident['nom']); ?>" disabled>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    <i class="fas fa-calendar-alt text-primary me-1"></i>
                    تاريخ البداية
                </label>
                <input type="text" class="form-control" value="<?php echo htmlspecialchars($accident['datedebut']); ?>" disabled>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">
                    <i class="fas fa-calendar-check text-danger me-1"></i>
                    تاريخ النهاية
                </label>
                <input type="date" name="datefin" class="form-control" value="<?php echo htmlspecialchars($accident['datefin']); ?>" required>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label">
                <i class="fas fa-tag text-primary me-1"></i>
                نوع الحادث
            </label>
            <select name="type2" class="form-select" id="typeSelect">
                <?php foreach ($typeMapping as $key => $type): ?>
                    <option value="<?php echo $key; ?>" 
                            data-badge-class="<?php echo $type['badge_class']; ?>"
                            data-color="<?php echo $type['color']; ?>"
                            <?php echo ($accident['type2'] == $key) ? 'selected' : ''; ?>>
                        <span class="type-badge <?php echo $type['badge_class']; ?>"></span>
                        <?php echo $type['name']; ?>
                    </option>
                <?php endforeach; ?>
                <option value="0" <?php echo ($accident['type2'] == 0 || $accident['type2'] == null) ? 'selected' : ''; ?>>
                    <span class="type-badge secondary"></span>
                    غير محدد
                </option>
            </select>
        </div>
        
        <div class="mb-3">
            <label class="form-label">
                <i class="fas fa-comment text-primary me-1"></i>
                الملاحظات
            </label>
            <textarea name="commentaire" class="form-control" rows="3" placeholder="أدخل الملاحظات هنا..."><?php echo htmlspecialchars($accident['commentaire']); ?></textarea>
        </div>
        
        <div class="mb-3">
            <label class="form-label">
                <i class="fas fa-check-circle text-primary me-1"></i>
                الحالة
            </label>
            <select name="valide" class="form-select">
                <option value="1" <?php echo $accident['valide'] == 1 ? 'selected' : ''; ?>>
                    <i class="fas fa-check-circle text-success"></i> صالحة
                </option>
                <option value="0" <?php echo $accident['valide'] == 0 ? 'selected' : ''; ?>>
                    <i class="fas fa-times-circle text-danger"></i> غير صالحة
                </option>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-1"></i> إلغاء
        </button>
        <button type="submit" class="btn btn-primary" id="saveBtn">
            <i class="fas fa-save me-1"></i> حفظ التعديلات
        </button>
    </div>
</form>

<script>
$(document).ready(function() {
    // Sauvegarde via AJAX pour le type d'accident
    $('#saveBtn').on('click', function(e) {
        e.preventDefault();
        
        var formData = $('#mainForm').serialize();
        var typeValue = $('#typeSelect').val();
        
        // Ajouter le type2 aux données
        formData += '&type2=' + typeValue;
        
        $.ajax({
            url: 'index.php?action=accidents_update_type',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Fermer le modal
                    $('#editModal').modal('hide');
                    // Recharger la page
                    location.reload();
                } else {
                    alert('Erreur: ' + response.message);
                }
            },
            error: function() {
                // Fallback: soumission normale
                $('#mainForm').off('submit').submit();
            }
        });
    });
    
    $('#editModal').on('shown.bs.modal', function () {
        $('.modal-body input:first').focus();
    });
    
    // Validation du formulaire
    $('#mainForm').on('submit', function(e) {
        var dateFin = $('input[name="datefin"]').val();
        if (!dateFin) {
            e.preventDefault();
            alert('يرجى إدخال تاريخ النهاية');
            return false;
        }
    });
});
</script>

</body>
</html>