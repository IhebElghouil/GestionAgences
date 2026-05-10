<?php
// delete_log.php
session_start();
require('connection.php');

// التحقق من الصلاحيات
if (!isset($_SESSION['congidGA']) || $_SESSION['departement'] != "admin") {
    header("Location: login.php");
    exit();
}

// التحقق من وجود جدول سجل الحذف
$checkTableQuery = "SHOW TABLES LIKE 'sanctions_deletion_log'";
$tableResult = mysqli_query($connection, $checkTableQuery);
$tableExists = mysqli_num_rows($tableResult) > 0;
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سجل حذف الإستجوابات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --success-color: #28a745;
        }
        
        body {
            background: #f5f5f5;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .header {
            background: linear-gradient(135deg, var(--danger-color) 0%, #c82333 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .header::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
        }
        
        .header h1 {
            font-weight: 700;
            position: relative;
        }
        
        .header p {
            opacity: 0.9;
            position: relative;
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
        }
        
        .table th {
            background-color: var(--danger-color);
            color: white;
            font-weight: 600;
            padding: 15px;
            border: none;
            position: sticky;
            top: 0;
        }
        
        .table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }
        
        .table tbody tr:hover {
            background-color: rgba(220, 53, 69, 0.05);
        }
        
        .reason-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 10px 15px;
            border-right: 3px solid var(--danger-color);
            max-width: 300px;
            margin: 0;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .reason-box:hover {
            background: #e9ecef;
            transform: translateX(-5px);
        }
        
        .sanction-badge {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }
        
        .date-badge {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .user-badge {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .btn-custom {
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        @media (max-width: 768px) {
            .table-container {
                overflow-x: auto;
            }
            
            .table {
                font-size: 0.85rem;
            }
            
            .reason-box {
                max-width: 200px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <?php include('menu.php'); ?>
        
        <div class="header">
            <h1><i class="fas fa-history me-2"></i>سجل حذف الإستجوابات</h1>
            <p>عرض جميع عمليات الحذف مع أسبابها وتفاصيلها الكاملة</p>
        </div>
        
        <div class="action-buttons">
            <a href="index.php" class="btn btn-primary btn-custom">
                <i class="fas fa-arrow-right me-2"></i>العودة للقائمة الرئيسية
            </a>
            
            <?php if ($tableExists): ?>
                <button onclick="exportLogToExcel()" class="btn btn-success btn-custom">
                    <i class="fas fa-file-excel me-2"></i>تصدير السجل
                </button>
            <?php endif; ?>
        </div>
        
        <?php if (!$tableExists): ?>
            <div class="empty-state">
                <i class="fas fa-database"></i>
                <h3>لا يوجد سجل حذف</h3>
                <p>لم يتم إنشاء جدول سجل الحذف بعد. سيتم إنشاؤه تلقائياً عند أول عملية حذف.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>الرقم الآلي</th>
                                <th>الاسم</th>
                                <th>الرتبة</th>
                                <th>العقوبة</th>
                                <th>تاريخ الإستجواب</th>
                                <th>سبب الحذف</th>
                                <th>محذوف بواسطة</th>
                                <th>تاريخ الحذف</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM sanctions_deletion_log ORDER BY deleted_at DESC";
                            $result = mysqli_query($connection, $query);
                            
                            if (mysqli_num_rows($result) == 0): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-5">
                                        <div class="empty-state">
                                            <i class="fas fa-inbox"></i>
                                            <h5>لا توجد عمليات حذف مسجلة</h5>
                                            <p>لم يتم حذف أي إستجوابات بعد.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else:
                                $counter = 1;
                                while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr>
                                        <td><?php echo $counter++; ?></td>
                                        <td>
                                            <span class="badge bg-dark"><?php echo htmlspecialchars($row['deleted_mecano']); ?></span>
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($row['deleted_nom']); ?></strong></td>
                                        <td><span class="badge bg-info"><?php echo htmlspecialchars($row['deleted_grade']); ?></span></td>
                                        <td><span class="sanction-badge"><?php echo htmlspecialchars($row['deleted_sanction']); ?></span></td>
                                        <td><span class="date-badge"><?php echo $row['deleted_datequestionnaire']; ?></span></td>
                                        <td>
                                            <div class="reason-box" title="<?php echo htmlspecialchars($row['deletion_reason']); ?>">
                                                <?php 
                                                $shortReason = htmlspecialchars($row['deletion_reason']);
                                                echo (strlen($shortReason) > 50) ? substr($shortReason, 0, 50) . '...' : $shortReason;
                                                ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="user-badge">
                                                <i class="fas fa-user me-1"></i>
                                                <?php echo htmlspecialchars($row['deleted_by_user']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="date-badge">
                                                <i class="fas fa-clock me-1"></i>
                                                <?php echo date('Y-m-d H:i', strtotime($row['deleted_at'])); ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endwhile;
                            endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <?php if (mysqli_num_rows($result) > 0): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>معلومة:</strong> تم تسجيل <?php echo mysqli_num_rows($result); ?> عملية حذف في النظام.
                </div>
            <?php endif; ?>
            
        <?php endif; ?>
    </div>

    <script>
        function exportLogToExcel() {
            // إنشاء جدول مؤقت للتصدير
            var table = document.createElement('table');
            var thead = document.createElement('thead');
            var tbody = document.createElement('tbody');
            
            // نسخ رأس الجدول
            thead.innerHTML = document.querySelector('.table thead').innerHTML;
            
            // نسخ محتوى الجدول
            var rows = document.querySelectorAll('.table tbody tr');
            rows.forEach(function(row) {
                var newRow = document.createElement('tr');
                newRow.innerHTML = row.innerHTML;
                
                // إزالة الأزرار والتنسيقات الداخلية
                newRow.querySelectorAll('.badge, .reason-box, .sanction-badge, .date-badge, .user-badge').forEach(function(el) {
                    el.outerHTML = el.textContent;
                });
                
                tbody.appendChild(newRow);
            });
            
            table.appendChild(thead);
            table.appendChild(tbody);
            
            // استدعاء دالة التصدير
            exportTableToExcel(table, 'سجل_الحذف.xlsx');
        }
        
        function exportTableToExcel(table, filename = ''){
            var downloadLink;
            var dataType = 'application/vnd.ms-excel';
            var tableHTML = table.outerHTML.replace(/ /g, '%20');
            
            filename = filename ? filename + '.xls' : 'excel_data.xls';
            
            downloadLink = document.createElement("a");
            document.body.appendChild(downloadLink);
            
            if (navigator.msSaveOrOpenBlob) {
                var blob = new Blob(['\ufeff', tableHTML], { type: dataType });
                navigator.msSaveOrOpenBlob(blob, filename);
            } else {
                downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
                downloadLink.download = filename;
                downloadLink.click();
            }
        }
        
        // إضافة تأثير hover للجدول
        document.addEventListener('DOMContentLoaded', function() {
            const tableRows = document.querySelectorAll('.table tbody tr');
            tableRows.forEach(row => {
                row.addEventListener('mouseenter', function() {
                    this.style.transition = 'all 0.3s ease';
                });
            });
            
            // إضافة tooltip لأسباب الحذف
            document.querySelectorAll('.reason-box').forEach(function(box) {
                const fullText = box.getAttribute('title');
                if (fullText) {
                    box.setAttribute('data-bs-toggle', 'tooltip');
                    box.setAttribute('data-bs-placement', 'top');
                    box.setAttribute('data-bs-title', fullText);
                }
            });
            
            // تهيئة tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
if ($tableExists) {
    mysqli_free_result($result);
}
mysqli_close($connection);
?>