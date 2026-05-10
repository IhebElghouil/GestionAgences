<!DOCTYPE html>
<?php
session_start();
?>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>استيراد التطور الوظيفي</title>
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="StyleSheet.css">
    <link rel="stylesheet" href="CSS/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --info-color: #00bcd4;
        }
        
        body {
            font-family: 'Segoe UI', 'Tahoma', 'Arial', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 20px;
            direction: rtl;
        }
        
        .import-container {
            max-width: 1300px;
            margin: 0 auto;
        }
        
        .import-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            background: white;
        }
        
        .import-header {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .import-header h2 {
            margin: 15px 0 5px;
            font-size: 2rem;
            font-weight: 600;
        }
        
        .import-header i {
            font-size: 4rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .import-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .import-body {
            padding: 35px;
        }
        
        .upload-area {
            border: 3px dashed #cbd5e0;
            border-radius: 15px;
            padding: 45px;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .upload-area:hover,
        .upload-area.dragover {
            border-color: var(--secondary-color);
            background: #ebf5ff;
            transform: translateY(-2px);
        }
        
        .upload-area i {
            font-size: 5rem;
            color: var(--secondary-color);
            margin-bottom: 20px;
        }
        
        .upload-area h3 {
            color: #2d3748;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .upload-area p {
            color: #718096;
            margin-bottom: 25px;
        }
        
        .file-info {
            display: none;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 25px;
        }
        
        .file-info i {
            color: white;
            font-size: 2rem;
        }
        
        .supported-formats {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 15px 0;
        }
        
        .format-badge {
            background: #e9ecef;
            color: #495057;
            padding: 8px 15px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .format-badge i {
            font-size: 1rem;
            margin-left: 5px;
        }
        
        .format-badge.ods {
            background: #d4edda;
            color: #155724;
        }
        
        .format-badge.csv {
            background: #cce5ff;
            color: #004085;
        }
        
        .fields-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 15px;
            margin: 25px 0;
            padding: 20px;
            background: #f7f9fc;
            border-radius: 12px;
        }
        
        .field-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 12px;
            border-right: 4px solid var(--secondary-color);
        }
        
        .field-item i {
            color: var(--secondary-color);
            font-size: 1.2rem;
        }
        
        .field-item .field-name {
            color: #4a5568;
            font-weight: 600;
            font-size: 1rem;
        }
        
        .field-item .field-desc {
            color: #718096;
            font-size: 0.85rem;
            margin-right: auto;
        }
        
        .field-item.required .field-name:after {
            content: "*";
            color: var(--danger-color);
            margin-right: 5px;
            font-weight: bold;
        }
        
        .preview-section {
            margin-top: 35px;
            display: none;
        }
        
        .progress {
            height: 12px;
            border-radius: 6px;
            margin: 25px 0;
            display: none;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, var(--secondary-color), var(--success-color));
        }
        
        .stats-badge {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        
        .table-preview {
            max-height: 500px;
            overflow-y: auto;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .table-preview table {
            margin-bottom: 0;
        }
        
        .table-preview th {
            background: #2d3748;
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
            padding: 15px 10px;
            font-size: 0.95rem;
            font-weight: 600;
        }
        
        .table-preview td {
            padding: 12px 10px;
            font-size: 0.95rem;
            vertical-align: middle;
        }
        
        .table-preview td .arabic-text {
            font-family: 'Arial', 'Tahoma', sans-serif;
        }
        
        .btn-import {
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-import:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(46, 204, 113, 0.3);
            color: white;
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, #95a5a6, #7f8c8d);
            color: white;
            border: none;
            padding: 14px 35px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(149, 165, 166, 0.3);
            color: white;
        }
        
        .format-sample {
            background: linear-gradient(135deg, #f6f9fc, #edf2f7);
            border-radius: 12px;
            padding: 20px;
            margin-top: 25px;
            border-right: 4px solid var(--info-color);
        }
        
        .format-sample pre {
            margin: 15px 0 0;
            background: #1a202c;
            color: #e2e8f0;
            padding: 15px;
            border-radius: 8px;
            direction: ltr;
            text-align: left;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }
        
        .result-message {
            margin-top: 25px;
            padding: 18px;
            border-radius: 12px;
            display: none;
        }
        
        .result-message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .result-message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .stat-card.success {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
        }
        
        .stat-card.error {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }
        
        .stat-card.info {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
        }
        
        .stat-card h3 {
            font-size: 2rem;
            margin: 10px 0 5px;
        }
        
        @media (max-width: 768px) {
            .import-body {
                padding: 20px;
            }
            
            .upload-area {
                padding: 25px;
            }
            
            .fields-grid {
                grid-template-columns: 1fr;
            }
            
            .stat-cards {
                grid-template-columns: 1fr;
            }
            
            .supported-formats {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>

<?php 

require('connection.php');

// Vérification des droits admin
if (!isset($_SESSION['congidGA']) || $_SESSION['departement'] !== "admin") {
    header('Location: index.php');
    exit();
}
?>

<!-- Navigation -->
<?php include('menu.php'); ?>

<div class="container import-container mt-4">
    <div class="import-card">
        <div class="import-header">
            <i class="fas fa-chart-line"></i>
            <h2>استيراد التطور الوظيفي</h2>
            <p>رفع ملف CSV أو ODS لاستيراد بيانات التطور الوظيفي للموظفين</p>
        </div>
        
        <div class="import-body">
            <!-- Zone d'upload -->
            <div class="upload-area" id="uploadArea">
                <i class="fas fa-cloud-upload-alt"></i>
                <h3>اسحب وأفلت الملف هنا</h3>
                <p>أو</p>
                <button class="btn btn-primary btn-lg" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-folder-open me-2"></i>اختر ملف
                </button>
                <input type="file" id="fileInput" accept=".csv, .txt, .ods, .xlsx" style="display: none;">
                
                <!-- Formats supportés -->
                <div class="supported-formats">
                    <span class="format-badge csv"><i class="fas fa-file-csv"></i> CSV</span>
                    <span class="format-badge ods"><i class="fas fa-file-excel"></i> ODS</span>
                    <span class="format-badge csv"><i class="fas fa-file-excel"></i> XLSX</span>
                </div>
                
                <!-- Informations du fichier sélectionné -->
                <div class="file-info" id="fileInfo">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-alt fa-3x me-3"></i>
                        <div class="text-end">
                            <strong id="fileName" style="font-size: 1.2rem;"></strong><br>
                            <small id="fileSize"></small>
                            <small id="fileType" class="d-block"></small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Liste des champs requis -->
            <div class="fields-grid">
                <div class="field-item required">
                    <i class="fas fa-hashtag"></i>
                    <span class="field-name">mecano</span>
                    <span class="field-desc">الرقم الآلي</span>
                </div>
                <div class="field-item">
                    <i class="fas fa-file-signature"></i>
                    <span class="field-name">decision_number</span>
                    <span class="field-desc">عدد المقرر</span>
                </div>
                <div class="field-item">
                    <i class="fas fa-calendar"></i>
                    <span class="field-name">issue_date</span>
                    <span class="field-desc">تاريخ الإصدار</span>
                </div>
                <div class="field-item required">
                    <i class="fas fa-star"></i>
                    <span class="field-name">ancienrang</span>
                    <span class="field-desc">الرتبة القديمة</span>
                </div>
                <div class="field-item required">
                    <i class="fas fa-star"></i>
                    <span class="field-name">nouveaurang</span>
                    <span class="field-desc">الرتبة الجديدة</span>
                </div>
                <div class="field-item required">
                    <i class="fas fa-layer-group"></i>
                    <span class="field-name">ancienneechelle</span>
                    <span class="field-desc">السلم القديم</span>
                </div>
                <div class="field-item required">
                    <i class="fas fa-layer-group"></i>
                    <span class="field-name">nouvelechelle</span>
                    <span class="field-desc">السلم الجديد</span>
                </div>
                <div class="field-item required">
                    <i class="fas fa-level-up-alt"></i>
                    <span class="field-name">anciennegrade</span>
                    <span class="field-desc">الدرجة القديمة</span>
                </div>
                <div class="field-item required">
                    <i class="fas fa-level-up-alt"></i>
                    <span class="field-name">nouveaugrade</span>
                    <span class="field-desc">الدرجة الجديدة</span>
                </div>
                <div class="field-item">
                    <i class="fas fa-sticky-note"></i>
                    <span class="field-name">notes</span>
                    <span class="field-desc">الملاحظات</span>
                </div>
                <div class="field-item required">
                    <i class="fas fa-calendar-check"></i>
                    <span class="field-name">dateeffet</span>
                    <span class="field-desc">تاريخ الفاعليّة</span>
                </div>
                <div class="field-item">
                    <i class="fas fa-users"></i>
                    <span class="field-name">commission</span>
                    <span class="field-desc">اللّجنة</span>
                </div>
            </div>
            
            <!-- Instructions pour ODS -->
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <strong>دعم ملفات ODS:</strong> يمكنك الآن استيراد ملفات ODS مباشرة من LibreOffice أو OpenOffice
            </div>
            
            <!-- Format d'exemple -->
            <div class="format-sample">
                <i class="fas fa-info-circle text-primary me-2"></i>
                <strong>صيغة الملف المطلوبة:</strong>
                <pre>mecano;decision_number;issue_date;ancienrang;nouveaurang;ancienneechelle;nouvelechelle;anciennegrade;nouveaugrade;notes;dateeffet;commission</pre>
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>مثال بمحتوى عربي:</strong><br>
                    <code class="d-block mt-2 p-2 bg-light">
                        AG001;2023/001;15/01/2023;تقني;تقني رئيسي;10;11;5;6;ترقية استثنائية;01/02/2023;اللجنة الإدارية
                    </code>
                </div>
            </div>
            
            <!-- Barre de progression -->
            <div class="progress" id="progressBar">
                <div class="progress-bar progress-bar-striped progress-bar-animated" 
                     role="progressbar" style="width: 0%"></div>
            </div>
            
            <!-- Aperçu des données -->
            <div class="preview-section" id="previewSection">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="stats-badge">
                        <i class="fas fa-database me-2"></i>
                        <span id="recordCount">0</span> سجل للاستيراد
                    </span>
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshPreview()">
                        <i class="fas fa-sync-alt me-2"></i>تحديث المعاينة
                    </button>
                </div>
                
                <div class="table-preview" id="tablePreview"></div>
                
                <div class="d-flex justify-content-between mt-4">
                    <button class="btn btn-cancel" onclick="resetUpload()">
                        <i class="fas fa-times me-2"></i>إلغاء
                    </button>
                    <button class="btn btn-import" id="importBtn" onclick="importData()" disabled>
                        <i class="fas fa-upload me-2"></i>بدء الاستيراد
                    </button>
                </div>
            </div>
            
            <!-- Message de résultat -->
            <div class="result-message" id="resultMessage"></div>
        </div>
    </div>
    
    <!-- Statistiques -->
    <div class="stats-cards" id="statsSection" style="display: none;">
        <div class="stat-card success">
            <i class="fas fa-check-circle fa-3x"></i>
            <h3 id="successCount">0</h3>
            <p>تم الاستيراد بنجاح</p>
        </div>
        <div class="stat-card error">
            <i class="fas fa-exclamation-circle fa-3x"></i>
            <h3 id="errorCount">0</h3>
            <p>فشل الاستيراد</p>
        </div>
        <div class="stat-card info">
            <i class="fas fa-clock fa-3x"></i>
            <h3 id="importTime">0s</h3>
            <p>مدة الاستيراد</p>
        </div>
    </div>
    
    <!-- Liste des erreurs détaillées -->
    <div class="card mt-4" id="errorsSection" style="display: none;">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-exclamation-triangle me-2"></i>
            تفاصيل الأخطاء
        </div>
        <div class="card-body" id="errorsList">
        </div>
    </div>
</div>

<script src="JS/jquery.min.js"></script>
<script src="JS/bootstrap.min.js"></script>

<script>
let selectedFile = null;
let csvData = [];
let importInProgress = false;

// Initialisation
$(document).ready(function() {
    initializeDragAndDrop();
    bindEvents();
});

function bindEvents() {
    $('#fileInput').on('change', function(e) {
        handleFileSelect(e.target.files[0]);
    });
    
    $('#importBtn').on('click', importData);
}

function initializeDragAndDrop() {
    const uploadArea = document.getElementById('uploadArea');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.add('dragover');
        }, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, () => {
            uploadArea.classList.remove('dragover');
        }, false);
    });
    
    uploadArea.addEventListener('drop', function(e) {
        const dt = e.dataTransfer;
        const file = dt.files[0];
        handleFileSelect(file);
    }, false);
}

function handleFileSelect(file) {
    if (!file) return;
    
    // Vérification du type de fichier
    const fileExt = file.name.split('.').pop().toLowerCase();
    const validExtensions = ['csv', 'txt', 'ods', 'xlsx'];
    
    if (!validExtensions.includes(fileExt)) {
        showResult('يرجى اختيار ملف صالح (CSV, ODS, XLSX)', 'error');
        return;
    }
    
    // Vérification de la taille (max 20MB pour ODS)
    const maxSize = fileExt === 'ods' || fileExt === 'xlsx' ? 20 * 1024 * 1024 : 10 * 1024 * 1024;
    if (file.size > maxSize) {
        showResult(`حجم الملف كبير جداً. الحد الأقصى ${maxSize/(1024*1024)} ميجابايت`, 'error');
        return;
    }
    
    selectedFile = file;
    
    // Afficher les informations du fichier
    $('#fileName').text(file.name);
    $('#fileSize').text(formatFileSize(file.size));
    
    // Afficher l'icône appropriée
    let fileIcon = 'fa-file-alt';
    if (fileExt === 'csv') fileIcon = 'fa-file-csv';
    else if (fileExt === 'ods') fileIcon = 'fa-file-excel';
    else if (fileExt === 'xlsx') fileIcon = 'fa-file-excel';
    
    $('#fileInfo i').removeClass().addClass(`fas ${fileIcon} fa-3x me-3`);
    $('#fileType').text(`النوع: ${fileExt.toUpperCase()}`);
    $('#fileInfo').fadeIn(300);
    
    // Lire et prévisualiser le fichier
    previewFile(file);
}

function previewFile(file) {
    const fileExt = file.name.split('.').pop().toLowerCase();
    
    if (fileExt === 'csv' || fileExt === 'txt') {
        previewCSV(file);
    } else if (fileExt === 'ods' || fileExt === 'xlsx') {
        previewODS(file);
    }
}

function previewCSV(file) {
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const content = e.target.result;
        parseCSV(content);
    };
    
    reader.readAsText(file, 'UTF-8');
}

function previewODS(file) {
    const formData = new FormData();
    formData.append('file', file);
    formData.append('action', 'preview');
    
    $.ajax({
        url: 'process_import_ods.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            try {
                const result = typeof response === 'string' ? JSON.parse(response) : response;
                if (result.success) {
                    parseODSData(result.data);
                } else {
                    showResult(result.message || 'خطأ في قراءة الملف', 'error');
                }
            } catch (e) {
                console.error('Parse error:', e);
                showResult('خطأ في تحليل الملف', 'error');
            }
        },
        error: function() {
            showResult('خطأ في الاتصال بالخادم', 'error');
        }
    });
}

function parseCSV(content) {
    const lines = content.split('\n').filter(line => line.trim() !== '');
    csvData = [];
    
    // Détection du séparateur
    const firstLine = lines[0] || '';
    const separator = firstLine.includes(';') ? ';' : ',';
    
    // Noms des colonnes
    const headers = [
        'mecano', 'decision_number', 'issue_date', 'ancienrang', 'nouveaurang',
        'ancienneechelle', 'nouvelechelle', 'anciennegrade', 'nouveaugrade',
        'notes', 'dateeffet', 'commission'
    ];
    
    // Limiter à 50 lignes pour l'aperçu
    for (let i = 0; i < Math.min(lines.length, 50); i++) {
        const values = lines[i].split(separator).map(val => val.trim());
        
        // Compléter les valeurs manquantes
        while (values.length < headers.length) {
            values.push('');
        }
        
        csvData.push(values);
    }
    
    displayPreview(headers);
}

function parseODSData(data) {
    csvData = data;
    
    // Noms des colonnes
    const headers = [
        'mecano', 'decision_number', 'issue_date', 'ancienrang', 'nouveaurang',
        'ancienneechelle', 'nouvelechelle', 'anciennegrade', 'nouveaugrade',
        'notes', 'dateeffet', 'commission'
    ];
    
    displayPreview(headers);
}

function displayPreview(headers) {
    if (csvData.length === 0) {
        showResult('الملف فارغ أو غير صالح', 'error');
        return;
    }
    
    // Traductions pour l'affichage
    const headerTranslations = {
        'mecano': 'الرقم الآلي',
        'decision_number': 'عدد المقرر',
        'issue_date': 'تاريخ الإصدار',
        'ancienrang': 'الرتبة القديمة',
        'nouveaurang': 'الرتبة الجديدة',
        'ancienneechelle': 'السلم القديم',
        'nouvelechelle': 'السلم الجديد',
        'anciennegrade': 'الدرجة القديمة',
        'nouveaugrade': 'الدرجة الجديدة',
        'notes': 'الملاحظات',
        'dateeffet': 'تاريخ الفاعليّة',
        'commission': 'اللّجنة'
    };
    
    let html = '<table class="table table-hover table-bordered">';
    
    // En-têtes avec traduction
    html += '<thead><tr>';
    headers.forEach(header => {
        const displayName = headerTranslations[header] || header;
        html += `<th>${displayName}</th>`;
    });
    html += '</tr></thead><tbody>';
    
    // Données
    for (let i = 0; i < csvData.length; i++) {
        html += '<tr>';
        for (let j = 0; j < csvData[i].length; j++) {
            const value = csvData[i][j] || '';
            
            // Détecter si le texte contient des caractères arabes
            const hasArabic = /[\u0600-\u06FF]/.test(value);
            
            // Mettre en évidence les champs numériques et dates
            if (j === 2 || j === 10) { // Colonnes de dates
                html += `<td><span class="badge bg-light text-dark">${value}</span></td>`;
            } else if (j === 5 || j === 6 || j === 7 || j === 8) { // Colonnes numériques
                html += `<td><span class="badge bg-primary text-white">${value}</span></td>`;
            } else if (hasArabic) {
                html += `<td><span class="arabic-text">${value}</span></td>`;
            } else {
                html += `<td>${value}</td>`;
            }
        }
        html += '</tr>';
    }
    
    html += '</tbody></table>';
    
    $('#tablePreview').html(html);
    $('#recordCount').text(csvData.length);
    $('#previewSection').fadeIn(300);
    $('#importBtn').prop('disabled', false);
}

function importData() {
    if (importInProgress || !selectedFile) return;
    
    importInProgress = true;
    $('#importBtn').prop('disabled', true);
    $('#progressBar').fadeIn(300);
    $('#resultMessage').hide();
    $('#errorsSection').hide();
    
    const formData = new FormData();
    formData.append('file', selectedFile);
    formData.append('action', 'import');
    
    const startTime = Date.now();
    
    $.ajax({
        url: 'process_import_ods.php',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhr: function() {
            const xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    updateProgress(percentComplete);
                }
            }, false);
            return xhr;
        },
        success: function(response) {
            const endTime = Date.now();
            const importTime = ((endTime - startTime) / 1000).toFixed(1);
            
            try {
                const result = typeof response === 'string' ? JSON.parse(response) : response;
                handleImportResult(result, importTime);
            } catch (e) {
                console.error('Parse error:', e);
                handleImportError('خطأ في تحليل استجابة الخادم');
            }
        },
        error: function(xhr, status, error) {
            handleImportError('خطأ في الاتصال بالخادم: ' + error);
        },
        complete: function() {
            importInProgress = false;
            $('#progressBar').fadeOut(300);
            setTimeout(() => {
                updateProgress(0);
            }, 500);
        }
    });
}

function handleImportResult(result, importTime) {
    if (result.success) {
        $('#successCount').text(result.success_count || 0);
        $('#errorCount').text(result.error_count || 0);
        $('#importTime').text(importTime + 's');
        $('#statsSection').fadeIn(300);
        
        showResult(`تم الاستيراد بنجاح! ${result.success_count} سجل تم استيراده`, 'success');
        
        if (result.errors && result.errors.length > 0) {
            displayErrors(result.errors);
        }
        
        // Réinitialiser après 5 secondes
        setTimeout(() => {
            resetUpload();
        }, 5000);
    } else {
        showResult(result.message || 'حدث خطأ أثناء الاستيراد', 'error');
        $('#importBtn').prop('disabled', false);
    }
}

function handleImportError(error) {
    showResult(error, 'error');
    $('#importBtn').prop('disabled', false);
}

function updateProgress(percent) {
    $('.progress-bar').css('width', percent + '%');
    if (percent >= 100) {
        $('.progress-bar').removeClass('progress-bar-animated');
    }
}

function showResult(message, type) {
    const resultDiv = $('#resultMessage');
    resultDiv.removeClass('success error').addClass(type);
    resultDiv.html(`
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
        ${message}
    `).fadeIn(300);
}

function displayErrors(errors) {
    let errorHtml = '<ul class="list-group">';
    errors.forEach(error => {
        errorHtml += `<li class="list-group-item list-group-item-danger">${error}</li>`;
    });
    errorHtml += '</ul>';
    
    $('#errorsList').html(errorHtml);
    $('#errorsSection').fadeIn(300);
}

function resetUpload() {
    selectedFile = null;
    csvData = [];
    $('#fileInput').val('');
    $('#fileInfo').fadeOut(300);
    $('#previewSection').fadeOut(300);
    $('#importBtn').prop('disabled', true);
    $('#progressBar').fadeOut(300);
    $('#statsSection').fadeOut(300);
    $('#errorsSection').fadeOut(300);
    $('#resultMessage').hide();
    updateProgress(0);
}

function refreshPreview() {
    if (selectedFile) {
        previewFile(selectedFile);
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}
</script>

</body>
</html>