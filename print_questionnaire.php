<?php
session_start();
require('DbConnexion.php');

// Récupérer l'ID du questionnaire
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Récupérer les données du questionnaire
    $sql = "SELECT * FROM sanctions WHERE idsanction = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $questionnaire = mysqli_fetch_assoc($result);
    
    if (!$questionnaire) {
        die("Questionnaire non trouvé");
    }
} else {
    die("ID invalide");
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة الإستجواب - <?php echo htmlspecialchars($questionnaire['nom']); ?></title>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-container, .print-container * {
                visibility: visible;
            }
            .print-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
            @page {
                margin: 10mm;
            }
        }
        
        body {
            font-family: 'Traditional Arabic', 'Times New Roman', serif;
            line-height: 1.6;
            margin: 5px;
            padding: 10px;
            background-color: #f5f5f5;
            direction: rtl;
        }
        
        .print-container {
            background: white;
            padding: 2px;
            margin: 0 auto;
            max-width: 800px;
                   }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
            margin-bottom: 5px;
        }
        
        .header h1 {
            color: #dc3545;
            font-size: 22px;
            margin: 5px 0;
        }
        
        .info-section {
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-right: 3px solid #dc3545;
        }
        
        .info-section h3 {
            margin: 0 0 10px 0;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            font-size: 16px;
        }
        
        .info-grid {
            display: flex;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            border: 1px solid #ccc;
            padding: 8px;
            border-radius: 5px;
        }
        
        .info-item {
            white-space: nowrap;
            font-size: 14px;
        }

        .info-label {
            font-weight: bold;
            margin-left: 5px;
        }
        
        .info-value {
            color: #333;
        }
        
        .description-section {
            margin: 15px 0;
            padding: 15px;
            border: 1px solid #ddd;
            background: #fff;
            min-height: 200px;
        }
        
        .response-section {
            margin: 10px 0;
            padding: 5px;
            border: 1px solid #ddd;
            background: #fff;
            min-height: 250px;
        }
        
        .description-content, .response-content {
            line-height: 1.8;
            font-size: 20px;
            min-height: 180px;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 5px;
            color: #333;
            font-size: 17px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
			font-weight: bold;
        }
        
        .signature-section {
            margin-top: 10px;
            text-align: left;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            width: 250px;
            margin-top: 10px;
            padding-top: 5px;
            text-align: center;
            font-size: 14px;
        }
        
        .stamp {
            float: left;
            text-align: center;
            margin-top: 10px;
        }
        
        .stamp-box {
            border: 2px solid #333;
            padding: 8px 15px;
            display: inline-block;
            font-size: 14px;
        }
        
        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        
        .btn-print {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .btn-print:hover {
            background: #c82333;
        }
        
        /* Styles pour le contenu formaté */
        .description-content strong,
        .description-content b,
        .response-content strong,
        .response-content b {
            font-weight: bold;
        }
        
        .description-content em,
        .description-content i,
        .response-content em,
        .response-content i {
            font-style: italic;
        }
        
        .description-content u,
        .response-content u {
            text-decoration: underline;
        }
        
        .description-content ul,
        .description-content ol,
        .response-content ul,
        .response-content ol {
            margin-right: 15px;
            margin-bottom: 10px;
        }
        
        .description-content li,
        .response-content li {
            margin-bottom: 4px;
        }
        
        .description-content p,
        .response-content p {
            margin-bottom: 12px;
        }
        
        .description-content div,
        .response-content div {
            margin-bottom: 8px;
        }
        
        .response-placeholder {
            color: #666;
            font-style: italic;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 15px;">
        <button class="btn-print" onclick="window.print()">🖨️ طباعة الإستجواب</button>
        
    </div>
    
    <div class="print-container">
        <!-- En-tête -->
        <div class="header">
            <h1>الشركة الجهوية للنقل بقابس</h1>
            <h1>إستجواب إداري</h1>
        </div>
        
        <!-- Informations de base -->
        <div class="info-section">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">الاسم واللقب:</span>
                    <span class="info-value"><?php echo htmlspecialchars($questionnaire['nom']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">الرقم الآلي :</span>
                    <span class="info-value"><?php echo htmlspecialchars($questionnaire['mecano']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">الرتبة:</span>
                    <span class="info-value"><?php echo htmlspecialchars($questionnaire['grade']); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Informations du rapport -->
       <!-- <div class="info-section">
            <h3>معلومات التقرير</h3>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">رقم التقرير:</span>
                    <span class="info-value"><?php //echo htmlspecialchars($questionnaire['report_number']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">تاريخ التقرير:</span>
                    <span class="info-value"><?php //echo date('d/m/Y', strtotime($questionnaire['report_date'])); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">تاريخ الإستلام:</span>
                    <span class="info-value"><?php //echo date('d/m/Y', strtotime($questionnaire['datereception'])); ?></span>
                </div>
                <?php //if (!empty($questionnaire['faute'])): ?>
                <div class="info-item">
                    <span class="info-label">تاريخ المخالفة:</span>
                    <span class="info-value"><?php //echo date('d/m/Y', strtotime($questionnaire['datefaute'])); ?></span>
                </div>
                <?php //endif; ?>
                <?php //if (!empty($questionnaire['questionnaire'])): ?>
                <div class="info-item">
                    <span class="info-label">تاريخ الإستجواب:</span>
                    <span class="info-value"><?php //echo date('d/m/Y', strtotime($questionnaire['datequestionnaire'])); ?></span>
                </div>
                <?php //endif; ?>
            </div>
        </div> -->
        
        <!-- Description du questionnaire -->
        <div class="description-section">
            <div class="section-title">نصّ الإستجواب</div>
            <div class="description-content">
                <?php echo $questionnaire['faute']; ?>
            </div>
			<p style="text-decoration: underline; font-weight: bold;">*إن عدم الإجابة عن الاستجواب في ظرف ثلاثة أيام من تاريخ تسلمه لا يمنع من مواصلة التتبعات الإدارية وذلك طبقا للفصل 40 من النظام الأساسي الخاص.</p>
        </div>
        <div class="footer">
            حرّر هذا الإستجواب في : <?php echo date('Y/m/d', strtotime($questionnaire['datequestionnaire'])); ?>
        </div>
        <!-- Zone de réponse -->
        <div class="response-section">
            <div class="section-title">الإجابة</div>
            <div class="response-content">
                <?php if (!empty($questionnaire['reponse'])): ?>
                    <?php echo $questionnaire['reponse']; ?>
                <?php else: ?>
                    <div class="response-placeholder">
                       ...............................................................................................................................<br>
                       ...............................................................................................................................<br>
					   ...............................................................................................................................<br>
					   ...............................................................................................................................<br>
					   ...............................................................................................................................<br>
					   ...............................................................................................................................<br>
                        <br>
                        <strong>التوقيع:</strong> ...................................... &nbsp;&nbsp;&nbsp; 
                        <strong>التاريخ:</strong> ...../...../......
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Signatures -->
        <div class="signature-section">
           <!-- <div style="float: left; width: 30%;">
                <div class="signature-line">
                    مقترح الرئيس المباشر
                </div>
            </div> -->
            
            <div style="float: right; width: 30%; text-align: left;">
                <div class="signature-line">
                    مقترح رئيس المصلحة/الدائرة
                </div>
            </div>
			
			<div style="float: left; width: 30%; text-align: left;">
                <div class="signature-line">
                    قرار الإدارة العامّة
                </div>
            </div>
            <div style="clear: both;"></div>
        </div>
        
        <!-- Cachet 
        <div class="stamp">
            <div class="stamp-box">
                قرار الإدارة العامة
            </div>
        </div>
        
        <!-- Pied de page 
        <div class="footer">
            تم إنشاء هذا الإستجواب في: <?php //echo date('d/m/Y H:i', strtotime($questionnaire['Datestamp'])); ?>
        </div>-->
    </div>

    <script>
        // Impression automatique après 1 seconde
        setTimeout(function() {
            window.print();
        }, 1000);
        
        // Redirection après impression (optionnel)
        window.onafterprint = function() {
            // window.close(); // Décommentez pour fermer automatiquement après impression
        };
    </script>
</body>
</html>

<?php
mysqli_close($connection);
?>