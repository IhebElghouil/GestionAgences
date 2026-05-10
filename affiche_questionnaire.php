<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام الإستجوابات</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- TinyMCE CDN without API key -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .header {
            background-color: #dc3545;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .form-container {
            background-color: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .required-field::after {
            content: " *";
            color: red;
        }
        .btn-custom {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            margin-top: 15px;
        }
        .btn-custom:hover {
            background-color: #c82333;
        }
        .form-label {
            font-weight: bold;
        }
        .tox-tinymce {
            border-radius: 5px;
            border: 1px solid #ced4da !important;
        }
    </style>
</head>
<body>
    <div class="container">
                
        <div class="form-container">
            <form id="questionnaireForm" action="addquestionnaire.php" method="POST">
                <?php
                session_start();
                require('connection.php');
                
                if (isset($_SESSION['congidGA'])) {
                    $departement = $_SESSION['departement'];
                    
                    $stmt = mysqli_prepare($connection, 
                        "SELECT nom, libellet FROM stuf, titres 
                         WHERE titres.id = stuf.titre AND mecano = ?");
                    
                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "s", $_POST['myInput']);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        
                        if (mysqli_num_rows($result) > 0) {
                            while($rNom = mysqli_fetch_row($result)) {
                                $nom = $rNom[0];
                                $mecano = $_POST['myInput'];
                                $grade = $rNom[1];
                            }
                            
                            echo '
                            <div class="employee-info mb-4 p-3 bg-light rounded">
                                <h3>معلومات العون</h3>
                                <p><strong>الاسم:</strong> '.htmlspecialchars($nom).'</p>
                                <p><strong>الرقم الآلي :</strong> '.htmlspecialchars($mecano).'</p>
                                <p><strong>الرتبة:</strong> '.htmlspecialchars($grade).'</p>
                                <input type="hidden" value="'.htmlspecialchars($nom).'" name="nom">
                                <input type="hidden" value="'.htmlspecialchars($mecano).'" name="mecano">
                                <input type="hidden" value="'.htmlspecialchars($grade).'" name="grade">
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="daterapport" class="form-label required-field">تاريخ التقرير</label>
                                    <input type="date" class="form-control" id="daterapport" name="daterapport" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="nrapport" class="form-label required-field">رقم التقرير</label>
                                    <input type="number" class="form-control" id="nrapport" name="nrapport" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="datereceptionrapport" class="form-label required-field">تاريخ الإستلام</label>
                                    <input type="date" class="form-control" id="datereceptionrapport" name="datereceptionrapport" required>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="faute" class="form-label">تاريخ المخالفة</label>
                                    <input type="date" class="form-control" id="faute" name="faute">
                                </div>
                                <div class="col-md-6">
                                    <label for="questionnaire" class="form-label">تاريخ الإستجواب</label>
                                    <input type="date" class="form-control" id="questionnaire" name="questionnaire">
                                </div>
                            </div>
                            
                            <!-- Dans votre formulaire HTML, remplacez la section description par : -->
<div class="mb-3">
    <label for="description" class="form-label">وصف الإستجواب</label>
    
 
    <div class="border rounded p-2 bg-light mb-2">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText(\'bold\')" title="عريض">
            <strong>B</strong>
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText(\'italic\')" title="مائل">
            <em>I</em>
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="formatText(\'underline\')" title="تحته خط">
            <u>U</u>
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertLineBreak()" title="سطر جديد">
            ↵ سطر جديد
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="insertBulletList()" title="قائمة نقطية">
            • قائمة
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="showHelp()" title="مساعدة">
            ؟ مساعدة
        </button>
    </div>
    
    <textarea id="description" name="description" class="form-control" rows="10" 
              placeholder="اكتب وصف الإستجواب هنا...
يمكنك استخدام:
• **النص العريض**
• *النص المائل* 
• ↵ للأسطر الجديدة"></textarea>
    
    <small class="form-text text-muted">
        استخدم الأزرار أعلاه لتنسيق النص. سيتم حفظ التنسيق وعرضه عند الطباعة.
    </small>
</div>
                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-custom">تسجيل الإستجواب</button>
                            </div>';
                        } else {
                            echo '<div class="alert alert-warning text-center" role="alert">
                                <h2>الرجاء التثبت من المعطيات</h2>
                            </div>';
                        }
                        
                        mysqli_stmt_close($stmt);
                    } else {
                        echo '<div class="alert alert-danger text-center" role="alert">
                            <h2>خطأ في الاتصال بقاعدة البيانات</h2>
                        </div>';
                    }
                } else {
                    echo '<div class="alert alert-warning text-center" role="alert">
                        <h2>الرجاء التثبت من المعطيات</h2>
                    </div>';
                }
                
                mysqli_close($connection);
                ?>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
<!-- Alternative avec éditeur basique -->




<script>
function formatText(command) {
    const textarea = document.getElementById('description');
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const selectedText = textarea.value.substring(start, end);
    
    let formattedText = selectedText;
    let tag = '';
    
    switch(command) {
        case 'bold':
            tag = 'strong';
            formattedText = '<' + tag + '>' + selectedText + '</' + tag + '>';
            break;
        case 'italic':
            tag = 'em';
            formattedText = '<' + tag + '>' + selectedText + '</' + tag + '>';
            break;
        case 'underline':
            tag = 'u';
            formattedText = '<' + tag + '>' + selectedText + '</' + tag + '>';
            break;
    }
    
    textarea.value = textarea.value.substring(0, start) + formattedText + textarea.value.substring(end);
    textarea.focus();
    
    // Restaurer la sélection
    textarea.setSelectionRange(start, start + formattedText.length);
}

function insertLineBreak() {
    const textarea = document.getElementById('description');
    const start = textarea.selectionStart;
    textarea.value = textarea.value.substring(0, start) + '<br>' + textarea.value.substring(start);
    textarea.focus();
    textarea.setSelectionRange(start + 4, start + 4);
}

function insertBulletList() {
    const textarea = document.getElementById('description');
    const start = textarea.selectionStart;
    const listItem = '<br>• ';
    textarea.value = textarea.value.substring(0, start) + listItem + textarea.value.substring(start);
    textarea.focus();
    textarea.setSelectionRange(start + listItem.length, start + listItem.length);
}

function showHelp() {
    alert(`دليل التنسيق:
• استخدم زر B للنص العريض
• استخدم زر I للنص المائل  
• استخدم زر U للنص تحته خط
• استخدم زر ↵ لإدراج سطر جديد
• استخدم زر • لإنشاء قائمة نقطية

سيظهر التنسيق بشكل صحيح عند الطباعة.`);
}

// Raccourcis clavier
document.getElementById('description').addEventListener('keydown', function(e) {
    if (e.ctrlKey) {
        switch(e.key) {
            case 'b':
                e.preventDefault();
                formatText('bold');
                break;
            case 'i':
                e.preventDefault();
                formatText('italic');
                break;
            case 'u':
                e.preventDefault();
                formatText('underline');
                break;
        }
    }
});
</script>
</body>
</html>