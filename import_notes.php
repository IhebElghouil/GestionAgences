<?php
session_start();
require('connection.php');

// Vérifier si l'utilisateur est connecté et a les droits admin
if (!isset($_SESSION['congidGA']) || $_SESSION['departement'] != "admin") {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استيراد الأعداد المهنية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
            direction: rtl;
        }
        
        .container-main {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .upload-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }
        
        .upload-area {
            border: 2px dashed var(--secondary-color);
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            background: #f8f9fa;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .upload-area:hover {
            border-color: var(--primary-color);
            background: #e9f7fe;
        }
        
        .upload-area i {
            font-size: 48px;
            color: var(--secondary-color);
            margin-bottom: 15px;
        }
        
        .preview-table {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .preview-table table {
            margin-bottom: 0;
        }
        
        .preview-table thead th {
            background-color: var(--primary-color);
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .badge-success {
            background-color: var(--success-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
        }
        
        .badge-danger {
            background-color: var(--danger-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
        }
        
        .progress {
            height: 10px;
            border-radius: 5px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .stat-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container-main">
        <div class="header-section">
            <h2 class="text-center mb-3">
                <i class="fas fa-file-excel me-2"></i>
                استيراد الأعداد المهنية من ملف Excel
            </h2>
            <p class="text-center mb-0">
                قم برفع ملف Excel يحتوي على الأعداد المهنية للموظفين
            </p>
        </div>
        
        <div class="upload-card">
            <div class="upload-area" id="uploadArea" onclick="document.getElementById('fileInput').click()">
                <i class="fas fa-cloud-upload-alt"></i>
                <h5>اختر ملف Excel</h5>
                <p class="text-muted mb-2">أو اسحب وأفلت الملف هنا</p>
                <p class="text-muted small">الملفات المسموحة: .xlsx, .xls (الحجم الأقصى: 10MB)</p>
                <input type="file" id="fileInput" accept=".xlsx,.xls" style="display: none;">
            </div>
            
            <div id="fileInfo" style="display: none;" class="mt-3">
                <div class="alert alert-info">
                    <i class="fas fa-file-excel me-2"></i>
                    <span id="fileName"></span>
                    <button class="btn btn-sm btn-outline-danger me-2" onclick="resetUpload()">
                        <i class="fas fa-times"></i> إلغاء
                    </button>
                </div>
            </div>
            
            <div id="previewSection" style="display: none;">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-value" id="totalRows">0</div>
                        <div class="stat-label">إجمالي السجلات</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="validRows">0</div>
                        <div class="stat-label">السجلات الصالحة</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value" id="invalidRows">0</div>
                        <div class="stat-label">السجلات غير الصالحة</div>
                    </div>
                </div>
                
                <div class="preview-table" id="previewTable"></div>
                
                <div class="progress mt-3" style="display: none;" id="importProgress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" 
                         role="progressbar" style="width: 0%"></div>
                </div>
                
                <div class="text-center mt-3">
                    <button class="btn btn-success" id="importBtn" onclick="importData()" disabled>
                        <i class="fas fa-upload me-2"></i>
                        بدء الاستيراد
                    </button>
                    <a href="import_notes.php" class="btn btn-secondary">
                        <i class="fas fa-redo me-2"></i>
                        إعادة تعيين
                    </a>
                    <a href="javascript:window.close()" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>
                        إغلاق
                    </a>
                </div>
            </div>
        </div>
        
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>ملاحظة:</strong> يجب أن يحتوي ملف Excel على الأعمدة التالية بالترتيب:
            <ul class="mb-0 mt-2">
                <li><strong>الرقم الآلي (mecano)</strong> - إجباري</li>
                <li><strong>السنة (annee)</strong> - إجباري (مثال: 2024)</li>
                <li><strong>العدد (note)</strong> - إجباري (من 0 إلى 20)</li>
                <li><strong>الملاحظات (observation)</strong> - اختياري</li>
            </ul>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    
    <script>
        let excelData = null;
        let validRows = [];
        let invalidRows = [];
        
        document.getElementById('fileInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileInfo').style.display = 'block';
                readExcelFile(file);
            }
        });
        
        function resetUpload() {
            document.getElementById('fileInput').value = '';
            document.getElementById('fileInfo').style.display = 'none';
            document.getElementById('previewSection').style.display = 'none';
            excelData = null;
            validRows = [];
            invalidRows = [];
        }
        
        function readExcelFile(file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                try {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, { type: 'array' });
                    const firstSheet = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheet];
                    const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                    
                    // Supprimer la première ligne (en-têtes)
                    jsonData.shift();
                    
                    excelData = jsonData;
                    processExcelData(jsonData);
                    
                } catch (error) {
                    alert('خطأ في قراءة الملف: ' + error.message);
                }
            };
            
            reader.readAsArrayBuffer(file);
        }
        
        function processExcelData(data) {
            validRows = [];
            invalidRows = [];
            
            data.forEach((row, index) => {
                if (row.length >= 3) { // Au moins mecano, annee, note
                    const mecano = row[0] ? row[0].toString().trim() : '';
                    const annee = row[1] ? parseInt(row[1]) : null;
                    const note = row[2] ? parseFloat(row[2].toString().replace(',', '.')) : null;
                    const observation = row[3] ? row[3].toString().trim() : '';
                    
                    // Validation
                    const isValid = mecano && annee && !isNaN(annee) && annee >= 1900 && annee <= 2100 && 
                                   note !== null && !isNaN(note) && note >= 0 && note <= 20;
                    
                    const rowData = {
                        rowNumber: index + 2,
                        mecano: mecano,
                        annee: annee,
                        note: note,
                        observation: observation,
                        isValid: isValid
                    };
                    
                    if (isValid) {
                        validRows.push(rowData);
                    } else {
                        invalidRows.push(rowData);
                    }
                } else {
                    invalidRows.push({
                        rowNumber: index + 2,
                        mecano: row[0] || '',
                        annee: row[1] || '',
                        note: row[2] || '',
                        observation: row[3] || '',
                        isValid: false,
                        error: 'بيانات ناقصة'
                    });
                }
            });
            
            displayPreview();
            document.getElementById('previewSection').style.display = 'block';
            document.getElementById('importBtn').disabled = validRows.length === 0;
        }
        
        function displayPreview() {
            document.getElementById('totalRows').textContent = excelData.length;
            document.getElementById('validRows').textContent = validRows.length;
            document.getElementById('invalidRows').textContent = invalidRows.length;
            
            let html = '<table class="table table-hover">';
            html += '<thead><tr><th>السطر</th><th>الرقم الآلي</th><th>السنة</th><th>العدد</th><th>الملاحظات</th><th>الحالة</th></tr></thead><tbody>';
            
            // Afficher les 50 premiers enregistrements valides
            validRows.slice(0, 50).forEach(row => {
                html += `<tr>
                    <td>${row.rowNumber}</td>
                    <td><span class="badge badge-success">${row.mecano}</span></td>
                    <td>${row.annee}</td>
                    <td><span class="badge badge-success">${row.note.toFixed(2)}/20</span></td>
                    <td>${row.observation || '-'}</td>
                    <td><span class="badge badge-success">صالح</span></td>
                </tr>`;
            });
            
            // Afficher les invalides
            invalidRows.slice(0, 20).forEach(row => {
                html += `<tr class="table-danger">
                    <td>${row.rowNumber}</td>
                    <td>${row.mecano}</td>
                    <td>${row.annee}</td>
                    <td>${row.note !== null ? row.note.toFixed(2) : '-'}</td>
                    <td>${row.observation || '-'}</td>
                    <td><span class="badge badge-danger">غير صالح</span></td>
                </tr>`;
            });
            
            if (excelData.length > 50) {
                html += `<tr><td colspan="6" class="text-center text-muted">... و ${excelData.length - 50} سجل آخر</td></tr>`;
            }
            
            html += '</tbody></table>';
            document.getElementById('previewTable').innerHTML = html;
        }
        
        function importData() {
            if (validRows.length === 0) return;
            
            const progressBar = document.querySelector('#importProgress .progress-bar');
            const progressDiv = document.getElementById('importProgress');
            const importBtn = document.getElementById('importBtn');
            
            progressDiv.style.display = 'block';
            importBtn.disabled = true;
            
            let imported = 0;
            const total = validRows.length;
            
            function importNext() {
                if (imported < total) {
                    const row = validRows[imported];
                    
                    fetch('save-note.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            mecano: row.mecano,
                            annee: row.annee,
                            note: row.note,
                            observation: row.observation
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        imported++;
                        const percent = (imported / total) * 100;
                        progressBar.style.width = percent + '%';
                        progressBar.textContent = Math.round(percent) + '%';
                        
                        if (imported < total) {
                            setTimeout(importNext, 100);
                        } else {
                            setTimeout(() => {
                                alert('تم استيراد ' + imported + ' سجل بنجاح');
                                window.location.reload();
                            }, 500);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur:', error);
                        alert('خطأ في استيراد السجل: ' + row.mecano);
                        importBtn.disabled = false;
                        progressDiv.style.display = 'none';
                    });
                }
            }
            
            importNext();
        }
        
        // Drag and drop
        const uploadArea = document.getElementById('uploadArea');
        
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#27ae60';
            uploadArea.style.backgroundColor = '#e9f7fe';
        });
        
        uploadArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#3498db';
            uploadArea.style.backgroundColor = '#f8f9fa';
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#3498db';
            uploadArea.style.backgroundColor = '#f8f9fa';
            
            const file = e.dataTransfer.files[0];
            if (file && (file.name.endsWith('.xlsx') || file.name.endsWith('.xls'))) {
                document.getElementById('fileInput').files = e.dataTransfer.files;
                document.getElementById('fileName').textContent = file.name;
                document.getElementById('fileInfo').style.display = 'block';
                readExcelFile(file);
            } else {
                alert('الرجاء اختيار ملف Excel صالح');
            }
        });
    </script>
</body>
</html>