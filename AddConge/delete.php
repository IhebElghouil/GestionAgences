<?php
// Start session and set security headers BEFORE any HTML output
session_start();
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");

require_once(__DIR__ . "/DbConnexion.php");

// Validate and sanitize inputs
$ID = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$Nbjours = filter_input(INPUT_GET, 'Nbjours', FILTER_VALIDATE_INT);
$mecano = filter_input(INPUT_GET, 'mecano', FILTER_SANITIZE_STRING);
$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);

// Check if all required parameters are valid
if (!$ID || !$mecano || !in_array($type, ['conge', 'autreconge'])) {
    // Output HTML only after headers
    ?>
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>حذف الإجازة</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                direction: rtl;
                text-align: right;
                padding: 0;
                margin: 0;
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            
            .message-container {
                width: 90%;
                max-width: 500px;
                margin: 20px;
                animation: fadeIn 0.5s ease-out;
            }
            
            .message-card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                transform: translateY(0);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            
            .message-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            }
            
            .message-header {
                padding: 25px 30px 10px;
                text-align: center;
            }
            
            .message-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 15px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 36px;
            }
            
            .message-content {
                padding: 0 30px 30px;
                text-align: center;
            }
            
            .message-title {
                font-size: 24px;
                margin: 0 0 15px 0;
                font-weight: 600;
                color: #333;
            }
            
            .message-text {
                font-size: 16px;
                line-height: 1.6;
                color: #666;
                margin: 0 0 25px 0;
            }
            
            .success .message-icon {
                background: linear-gradient(135deg, #d4edda, #a8e6a3);
                color: #155724;
            }
            
            .error .message-icon {
                background: linear-gradient(135deg, #f8d7da, #ffcdd2);
                color: #721c24;
            }
            
            .success {
                border-top: 5px solid #28a745;
            }
            
            .error {
                border-top: 5px solid #dc3545;
            }
            
            .redirect-info {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 10px;
                margin-top: 20px;
                font-size: 14px;
                color: #6c757d;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }
            
            .spinner {
                width: 16px;
                height: 16px;
                border: 2px solid #ddd;
                border-top: 2px solid #28a745;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            .print-only {
                display: none;
            }
            
            @media (max-width: 768px) {
                .message-card {
                    margin: 15px;
                }
                
                .message-header {
                    padding: 20px 20px 10px;
                }
                
                .message-content {
                    padding: 0 20px 20px;
                }
                
                .message-title {
                    font-size: 20px;
                }
            }
            
            @media print {
                body {
                    background: white;
                }
                
                .message-card {
                    box-shadow: none;
                    border: 1px solid #ddd;
                }
                
                .no-print {
                    display: none;
                }
                
                .print-only {
                    display: block;
                    text-align: center;
                    padding: 20px;
                    font-size: 12px;
                    color: #666;
                    border-top: 1px solid #eee;
                    margin-top: 20px;
                }
            }
        </style>
    </head>
    <body>
    <div class="message-container">
        <div class="message-card error">
            <div class="message-header">
                <div class="message-icon">⚠️</div>
            </div>
            <div class="message-content">
                <h2 class="message-title">خطأ في البيانات</h2>
                <p class="message-text">البيانات المرسلة غير صالحة أو ناقصة</p>
                <div class="redirect-info no-print">
                    <div class="spinner"></div>
                    <span>سيتم إعادة التوجيه خلال 3 ثوانٍ...</span>
                </div>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            let mecano = "<?php echo htmlspecialchars($mecano, ENT_QUOTES, 'UTF-8'); ?>";
            window.location.href = "index.php?mecano=" + encodeURIComponent(mecano);
        }, 3000);
    </script>
    </body>
    </html>
    <?php
    exit();
}

// For 'conge' type, Nbjours is required
if ($type === 'conge' && !$Nbjours) {
    ?>
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>حذف الإجازة</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                direction: rtl;
                text-align: right;
                padding: 0;
                margin: 0;
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            
            .message-container {
                width: 90%;
                max-width: 500px;
                margin: 20px;
                animation: fadeIn 0.5s ease-out;
            }
            
            .message-card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                transform: translateY(0);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            
            .message-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            }
            
            .message-header {
                padding: 25px 30px 10px;
                text-align: center;
            }
            
            .message-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 15px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 36px;
            }
            
            .message-content {
                padding: 0 30px 30px;
                text-align: center;
            }
            
            .message-title {
                font-size: 24px;
                margin: 0 0 15px 0;
                font-weight: 600;
                color: #333;
            }
            
            .message-text {
                font-size: 16px;
                line-height: 1.6;
                color: #666;
                margin: 0 0 25px 0;
            }
            
            .success .message-icon {
                background: linear-gradient(135deg, #d4edda, #a8e6a3);
                color: #155724;
            }
            
            .error .message-icon {
                background: linear-gradient(135deg, #f8d7da, #ffcdd2);
                color: #721c24;
            }
            
            .success {
                border-top: 5px solid #28a745;
            }
            
            .error {
                border-top: 5px solid #dc3545;
            }
            
            .redirect-info {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 10px;
                margin-top: 20px;
                font-size: 14px;
                color: #6c757d;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }
            
            .spinner {
                width: 16px;
                height: 16px;
                border: 2px solid #ddd;
                border-top: 2px solid #28a745;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            .print-only {
                display: none;
            }
            
            @media (max-width: 768px) {
                .message-card {
                    margin: 15px;
                }
                
                .message-header {
                    padding: 20px 20px 10px;
                }
                
                .message-content {
                    padding: 0 20px 20px;
                }
                
                .message-title {
                    font-size: 20px;
                }
            }
            
            @media print {
                body {
                    background: white;
                }
                
                .message-card {
                    box-shadow: none;
                    border: 1px solid #ddd;
                }
                
                .no-print {
                    display: none;
                }
                
                .print-only {
                    display: block;
                    text-align: center;
                    padding: 20px;
                    font-size: 12px;
                    color: #666;
                    border-top: 1px solid #eee;
                    margin-top: 20px;
                }
            }
        </style>
    </head>
    <body>
    <div class="message-container">
        <div class="message-card error">
            <div class="message-header">
                <div class="message-icon">❌</div>
            </div>
            <div class="message-content">
                <h2 class="message-title">بيانات ناقصة</h2>
                <p class="message-text">عدد أيام الإجازة مطلوب لحذف الإجازة السنوية</p>
                <div class="redirect-info no-print">
                    <div class="spinner"></div>
                    <span>سيتم إعادة التوجيه خلال 3 ثوانٍ...</span>
                </div>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            let mecano = "<?php echo htmlspecialchars($mecano, ENT_QUOTES, 'UTF-8'); ?>";
            window.location.href = "index.php?mecano=" + encodeURIComponent(mecano);
        }, 3000);
    </script>
    </body>
    </html>
    <?php
    exit();
}

// Begin transaction for atomic operations
$conn->begin_transaction();

try {
    // Determine the table name based on type
    $tableName = ($type === 'autreconge') ? 'autreconge' : 'conge';
    
    // Prepare and execute DELETE statement
    $resDelete = $conn->prepare("DELETE FROM $tableName WHERE id = ? AND mecano = ?");
    if ($resDelete === false) {
        throw new Exception('خطأ في إعداد استعلام الحذف: ' . $conn->error);
    }
    
    $resDelete->bind_param("is", $ID, $mecano);
    $resDelete->execute();
    $rowsDeleted = $resDelete->affected_rows;

    // If no rows were deleted, rollback and show error
    if ($rowsDeleted != 1) {
        throw new Exception('لم يتم العثور على الإجازة المحددة أو حدث خطأ أثناء الحذف');
    }

    // Only update nbconge for regular 'conge' type
    if ($type === 'conge') {
        $resUpdate = $conn->prepare("UPDATE nbconge SET rest = rest + ? WHERE mecano = ?");
        if ($resUpdate === false) {
            throw new Exception('خطأ في إعداد استعلام التحديث: ' . $conn->error);
        }
        
        $resUpdate->bind_param("is", $Nbjours, $mecano);
        $resUpdate->execute();
    }

    // Commit the transaction if all operations succeeded
    $conn->commit();

    // Success message with redirect
    $vacationType = ($type === 'conge') ? 'الإجازة السنوية' : 'الإجازة';
    ?>
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>حذف الإجازة</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                direction: rtl;
                text-align: right;
                padding: 0;
                margin: 0;
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            
            .message-container {
                width: 90%;
                max-width: 500px;
                margin: 20px;
                animation: fadeIn 0.5s ease-out;
            }
            
            .message-card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                transform: translateY(0);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            
            .message-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            }
            
            .message-header {
                padding: 25px 30px 10px;
                text-align: center;
            }
            
            .message-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 15px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 36px;
            }
            
            .message-content {
                padding: 0 30px 30px;
                text-align: center;
            }
            
            .message-title {
                font-size: 24px;
                margin: 0 0 15px 0;
                font-weight: 600;
                color: #333;
            }
            
            .message-text {
                font-size: 16px;
                line-height: 1.6;
                color: #666;
                margin: 0 0 25px 0;
            }
            
            .success .message-icon {
                background: linear-gradient(135deg, #d4edda, #a8e6a3);
                color: #155724;
            }
            
            .error .message-icon {
                background: linear-gradient(135deg, #f8d7da, #ffcdd2);
                color: #721c24;
            }
            
            .success {
                border-top: 5px solid #28a745;
            }
            
            .error {
                border-top: 5px solid #dc3545;
            }
            
            .redirect-info {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 10px;
                margin-top: 20px;
                font-size: 14px;
                color: #6c757d;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }
            
            .spinner {
                width: 16px;
                height: 16px;
                border: 2px solid #ddd;
                border-top: 2px solid #28a745;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            .print-only {
                display: none;
            }
            
            @media (max-width: 768px) {
                .message-card {
                    margin: 15px;
                }
                
                .message-header {
                    padding: 20px 20px 10px;
                }
                
                .message-content {
                    padding: 0 20px 20px;
                }
                
                .message-title {
                    font-size: 20px;
                }
            }
            
            @media print {
                body {
                    background: white;
                }
                
                .message-card {
                    box-shadow: none;
                    border: 1px solid #ddd;
                }
                
                .no-print {
                    display: none;
                }
                
                .print-only {
                    display: block;
                    text-align: center;
                    padding: 20px;
                    font-size: 12px;
                    color: #666;
                    border-top: 1px solid #eee;
                    margin-top: 20px;
                }
            }
        </style>
    </head>
    <body>
    <div class="message-container">
        <div class="message-card success">
            <div class="message-header">
                <div class="message-icon">✓</div>
            </div>
            <div class="message-content">
                <h2 class="message-title">تم الحذف بنجاح</h2>
                <p class="message-text">تم حذف <?php echo $vacationType; ?> بنجاح</p>
                <div class="redirect-info no-print">
                    <div class="spinner"></div>
                    <span>سيتم إعادة التوجيه تلقائياً خلال ثانيتين...</span>
                </div>
                <div class="print-only">
                    تم حذف <?php echo $vacationType; ?> - تاريخ الطباعة: <?php echo date('Y-m-d H:i:s'); ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            let mecano = "<?php echo htmlspecialchars($mecano, ENT_QUOTES, 'UTF-8'); ?>";
            window.location.href = "index.php?mecano=" + encodeURIComponent(mecano);
        }, 2000);
    </script>
    </body>
    </html>
    <?php

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    
    // Error message with redirect
    ?>
    <!DOCTYPE html>
    <html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <title>حذف الإجازة</title>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                direction: rtl;
                text-align: right;
                padding: 0;
                margin: 0;
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            
            .message-container {
                width: 90%;
                max-width: 500px;
                margin: 20px;
                animation: fadeIn 0.5s ease-out;
            }
            
            .message-card {
                background: white;
                border-radius: 16px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                transform: translateY(0);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }
            
            .message-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            }
            
            .message-header {
                padding: 25px 30px 10px;
                text-align: center;
            }
            
            .message-icon {
                width: 80px;
                height: 80px;
                margin: 0 auto 15px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 36px;
            }
            
            .message-content {
                padding: 0 30px 30px;
                text-align: center;
            }
            
            .message-title {
                font-size: 24px;
                margin: 0 0 15px 0;
                font-weight: 600;
                color: #333;
            }
            
            .message-text {
                font-size: 16px;
                line-height: 1.6;
                color: #666;
                margin: 0 0 25px 0;
            }
            
            .success .message-icon {
                background: linear-gradient(135deg, #d4edda, #a8e6a3);
                color: #155724;
            }
            
            .error .message-icon {
                background: linear-gradient(135deg, #f8d7da, #ffcdd2);
                color: #721c24;
            }
            
            .success {
                border-top: 5px solid #28a745;
            }
            
            .error {
                border-top: 5px solid #dc3545;
            }
            
            .redirect-info {
                background: #f8f9fa;
                padding: 15px;
                border-radius: 10px;
                margin-top: 20px;
                font-size: 14px;
                color: #6c757d;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }
            
            .spinner {
                width: 16px;
                height: 16px;
                border: 2px solid #ddd;
                border-top: 2px solid #28a745;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            .print-only {
                display: none;
            }
            
            @media (max-width: 768px) {
                .message-card {
                    margin: 15px;
                }
                
                .message-header {
                    padding: 20px 20px 10px;
                }
                
                .message-content {
                    padding: 0 20px 20px;
                }
                
                .message-title {
                    font-size: 20px;
                }
            }
            
            @media print {
                body {
                    background: white;
                }
                
                .message-card {
                    box-shadow: none;
                    border: 1px solid #ddd;
                }
                
                .no-print {
                    display: none;
                }
                
                .print-only {
                    display: block;
                    text-align: center;
                    padding: 20px;
                    font-size: 12px;
                    color: #666;
                    border-top: 1px solid #eee;
                    margin-top: 20px;
                }
            }
        </style>
    </head>
    <body>
    <div class="message-container">
        <div class="message-card error">
            <div class="message-header">
                <div class="message-icon">✗</div>
            </div>
            <div class="message-content">
                <h2 class="message-title">فشل في العملية</h2>
                <p class="message-text"><?php echo htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="message-text">يرجى إعادة المحاولة</p>
                <div class="redirect-info no-print">
                    <div class="spinner"></div>
                    <span>سيتم إعادة التوجيه خلال 3 ثوانٍ...</span>
                </div>
            </div>
        </div>
    </div>
    <script>
        setTimeout(function() {
            let mecano = "<?php echo htmlspecialchars($mecano, ENT_QUOTES, 'UTF-8'); ?>";
            window.location.href = "index.php?mecano=" + encodeURIComponent(mecano);
        }, 3000);
    </script>
    </body>
    </html>
    <?php
} finally {
    // Close statements if they exist
    if (isset($resDelete)) $resDelete->close();
    if (isset($resUpdate)) $resUpdate->close();
    $conn->close();
}
?>