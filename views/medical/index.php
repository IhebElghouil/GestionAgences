<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام متابعة الشهادات الطبية</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    
    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --medical-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --expired-color: #c0392b;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }
        
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            margin: 0;
            padding: 20px;
            background: linear-gradient(135deg, #f5f7fa 0%, #e8f5e8 100%);
            min-height: 100vh;
        }
        
        .container-main {
            max-width: 1800px;
            margin: 0 auto;
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--medical-color) 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
        }
        
        .header-section::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
        }
        
        .header-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
            position: relative;
        }
        
        .header-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            text-align: center;
            position: relative;
        }
        
        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
            border-top: 4px solid var(--medical-color);
            cursor: pointer;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--hover-shadow);
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 1rem;
            color: #6c757d;
            font-weight: 500;
        }
        
        .stat-total .stat-value { color: var(--primary-color); }
        .stat-expired .stat-value { color: var(--expired-color); }
        .stat-urgent .stat-value { color: var(--warning-color); }
        .stat-valid .stat-value { color: var(--medical-color); }
        
        .controls-section {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
        }

        .filter-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
        }

        .filter-input {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .filter-input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .btn-modern {
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }

        .btn-clear {
            background: var(--light-bg);
            color: var(--primary-color);
        }

        .btn-export {
            background: var(--medical-color);
            color: white;
        }

        .btn-export:hover {
            background: #229954;
            color: white;
        }
        
        .btn-print {
            background: var(--secondary-color);
            color: white;
        }
        
        .btn-print:hover {
            background: #2980b9;
            color: white;
        }
        
        .add-certificate-btn {
            background: linear-gradient(135deg, var(--medical-color) 0%, #229954 100%);
            color: white;
            border-radius: 50px;
            padding: 12px 25px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border: none;
            margin-bottom: 20px;
        }
        
        .add-certificate-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
            margin-bottom: 25px;
            overflow-x: auto;
        }
        
        .custom-table {
            margin-bottom: 0;
            width: 100%;
        }
        
        .custom-table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 15px 12px;
            text-align: center;
            position: sticky;
            top: 0;
        }
        
        .custom-table tbody td {
            padding: 12px 8px;
            border-bottom: 1px solid #e9ecef;
            text-align: center;
            vertical-align: middle;
        }
        
        .custom-table tbody tr:hover {
            background-color: rgba(39, 174, 96, 0.05);
        }
        
        .status-expired {
            background: linear-gradient(90deg, rgba(231, 76, 60, 0.1) 0%, transparent 100%) !important;
            border-right: 4px solid var(--expired-color);
        }
        
        .status-urgent {
            background: linear-gradient(90deg, rgba(243, 156, 18, 0.1) 0%, transparent 100%) !important;
            border-right: 4px solid var(--warning-color);
        }
        
        .status-today {
            background: linear-gradient(90deg, rgba(241, 196, 15, 0.1) 0%, transparent 100%) !important;
            border-right: 4px solid #f1c40f;
        }
        
        .employee-id {
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }
        
        .date-badge {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .certificate-badge {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .badge-custom {
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.75rem;
        }
        
        .badge-primary {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            color: white;
        }
        
        .badge-medical {
            background: linear-gradient(135deg, var(--medical-color) 0%, #229954 100%);
            color: white;
        }
        
        .observation-box {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 8px 12px;
            border-right: 3px solid var(--medical-color);
            max-width: 300px;
            margin: 0 auto;
            text-align: right;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .observation-box:hover {
            background: #e9ecef;
            transform: translateX(-5px);
        }
        
        .urgency-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
            animation: pulse 2s infinite;
        }
        
        .indicator-expired { background-color: var(--expired-color); }
        .indicator-urgent { background-color: var(--warning-color); }
        .indicator-today { background-color: #f1c40f; }
        
        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.7; }
            50% { transform: scale(1.1); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.7; }
        }
        
        .mobile-message {
            display: none;
            background: linear-gradient(135deg, var(--medical-color) 0%, #229954 100%);
            color: white;
            padding: 12px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .warning-message {
            background: linear-gradient(135deg, var(--warning-color) 0%, #e67e22 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin: 25px 0;
            text-align: center;
            font-weight: 600;
        }
        
        .modal-content {
            border-radius: 15px;
            border: none;
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--medical-color) 0%, #229954 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        
        @media (max-width: 768px) {
            body { padding: 10px; }
            .mobile-message { display: block; }
            .stats-section { grid-template-columns: repeat(2, 1fr); }
            .btn-modern span { display: none; }
            .btn-modern { padding: 10px; }
            .observation-box { max-width: 150px; }
        }
        
        @media (max-width: 576px) {
            .stats-section { grid-template-columns: 1fr; }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
    </style>
</head>

<body>

<?php include(__DIR__ . '/../../menu.php'); ?>

<div class="container-main">
    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="editModalContent"></div>
        </div>
    </div>

    <div class="header-section">
        <h1 class="header-title">نظام متابعة الشهادات الطبية</h1>
        <p class="header-subtitle">بجميع الوكالات و الورشات</p>
    </div>

    <!-- Stats Section -->
    <div class="stats-section" id="statsContainer">
        <div class="stat-card stat-total" data-filter="all">
            <div class="stat-value"><?php echo $stats['total']; ?></div>
            <div class="stat-label">إجمالي الشهادات</div>
        </div>
        <div class="stat-card stat-valid" data-filter="valid">
            <div class="stat-value"><?php echo $stats['valid']; ?></div>
            <div class="stat-label">سارية المفعول</div>
        </div>
        <div class="stat-card stat-expired" data-filter="expired">
            <div class="stat-value"><?php echo $stats['expired']; ?></div>
            <div class="stat-label">منتهية الصلاحية</div>
        </div>
        <div class="stat-card stat-urgent" data-filter="urgent">
            <div class="stat-value"><?php echo $stats['urgent']; ?></div>
            <div class="stat-label">عاجلة (1-10 أيام)</div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="controls-section">
        <div class="d-flex justify-content-between flex-wrap gap-3">
            <button id="clearFilters" class="btn-modern btn-clear">
                <i class="fas fa-trash-alt"></i>
                <span>مسح كل الفلاتر</span>
            </button>
            
            <div class="d-flex gap-2">
                <button id="addCertificateBtn" class="btn-modern" style="background: var(--medical-color); color: white;">
                    <i class="fas fa-plus"></i>
                    <span>إضافة شهادة طبية</span>
                </button>
                
                <button id="exportExcelBtn" class="btn-modern btn-export">
                    <i class="fas fa-file-excel"></i>
                    <span>تصدير إلى Excel</span>
                </button>
                
                <button id="printTable" class="btn-modern btn-print">
                    <i class="fas fa-print"></i>
                    <span>طباعة</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filter-section">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" id="filterMecano" class="form-control filter-input" data-column="0" placeholder="البحث بالرقم الآلي...">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterNom" class="form-control filter-input" data-column="1" placeholder="البحث بالإسم أو اللقب...">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterGrade" class="form-control filter-input" data-column="2" placeholder="البحث بالرتبة...">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterDateCert" class="form-control filter-input" data-column="3" placeholder="تاريخ الشهادة...">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterNumCert" class="form-control filter-input" data-column="4" placeholder="رقم الشهادة...">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterExpiry" class="form-control filter-input" data-column="5" placeholder="إنتهاء الصلاحية...">
            </div>
            <div class="col-md-3">
                <select id="filterAgence" class="form-control filter-input" data-column="7">
                    <option value="">جميع الوكالات</option>
                    <?php foreach ($departments as $dep): ?>
                        <option value="<?php echo htmlspecialchars($dep); ?>"><?php echo htmlspecialchars($dep); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterStatus" class="form-control filter-input">
                    <option value="">جميع الحالات</option>
                    <option value="valid">سارية المفعول</option>
                    <option value="expired">منتهية الصلاحية</option>
                    <option value="urgent">عاجلة (1-10 أيام)</option>
                    <option value="today">تنتهي اليوم</option>
                </select>
            </div>
        </div>
    </div>

    <div class="mobile-message">
        <i class="fas fa-mobile-alt me-2"></i>
        لرؤية كافة الأعمدة، يرجى التمرير أفقيًا ← →
    </div>

    <!-- Table Section -->
    <div class="table-container">
        <table id="myTable" class="custom-table">
            <thead>
                <tr>
                    <th>الرقم الآلي</th>
                    <th>الإسم و اللقب</th>
                    <th>الرتبة</th>
                    <th>تاريخ الشهادة</th>
                    <th>رقم الشهادة</th>
                    <th>إنتهاء الصلاحية</th>
                    <th>الملاحظات</th>
                    <th>وحدة الإرتباط</th>
                    <?php if ($isAdmin): ?>
                        <th>الإجراءات</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($certificates as $cert): ?>
                <tr class="<?php echo $cert['statusClass']; ?> fade-in" data-status="<?php echo $cert['statusType']; ?>">
                    <td>
                        <span class="employee-id"><?php echo htmlspecialchars($cert['mecano']); ?></span>
                        <?php if ($cert['urgencyIndicator']): ?>
                            <span class="urgency-indicator <?php echo $cert['urgencyIndicator']; ?>"></span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?php echo htmlspecialchars($cert['nom']); ?></strong></td>
                    <td><span class="badge-custom badge-primary"><?php echo htmlspecialchars($cert['libellet']); ?></span></td>
                    <td><span class="date-badge"><?php echo htmlspecialchars($cert['datecertificat']); ?></span></td>
                    <td><span class="certificate-badge"><?php echo htmlspecialchars($cert['numcertifcat']); ?></span></td>
                    <td><span class="date-badge"><?php echo htmlspecialchars($cert['datefin']); ?></span></td>
                    <td>
                        <?php if (!empty(trim($cert['observation']))): ?>
                            <div class="observation-box" title="<?php echo htmlspecialchars($cert['observation']); ?>">
                                <?php echo mb_substr(htmlspecialchars($cert['observation']), 0, 60); ?>
                                <?php if (strlen($cert['observation']) > 60): ?>...<?php endif; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">لا توجد ملاحظات</span>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge-custom badge-medical"><?php echo htmlspecialchars($cert['depar']); ?></span></td>
                    <?php if ($isAdmin): ?>
                    <td>
                        <button class="btn btn-sm btn-outline-primary edit-cert-btn" data-id="<?php echo $cert['id']; ?>">
                            <i class="fas fa-edit"></i>
                        </button>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($certificates)): ?>
                <tr>
                    <td colspan="<?php echo $isAdmin ? 9 : 8; ?>" class="text-center py-4">
                        <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                        لا توجد بيانات للعرض
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Warning Message -->
    <div class="warning-message">
        <i class="fas fa-exclamation-triangle me-2"></i>
        يرجى إعادة عرض الأعوان اللذين انتهت صلوحية الشهادات الطبية الصادرة عن طبيب الشغل في شأنهم على أنظار طبيب الشغل
    </div>
</div>

<script>
$(document).ready(function() {
    // Modal pour modification
    $('.edit-cert-btn').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        $('#editModalContent').load('index.php?action=medical_edit&id=' + encodeURIComponent(id), function() {
            $('#editModal').modal('show');
        });
    });
    
    // Modal pour ajout
    $('#addCertificateBtn').on('click', function() {
        $('#editModalContent').load('index.php?action=medical_add_form', function() {
            $('#editModal').modal('show');
        });
    });
});

// Filter table function
function filterTable() {
    var filters = {};
    $('.filter-input').each(function() {
        var val = $(this).val();
        if (val && val.trim() !== '') {
            var column = $(this).data('column');
            if (column !== undefined) {
                filters[column] = val.toLowerCase().trim();
            }
        }
    });
    
    var statusFilter = $('#filterStatus').val();
    
    $('#myTable tbody tr').each(function() {
        var showRow = true;
        var cells = $(this).find('td');
        var rowStatus = $(this).data('status');
        
        for (var col in filters) {
            var colIndex = parseInt(col);
            if (!isNaN(colIndex) && cells.length > colIndex) {
                var cellText = cells.eq(colIndex).text().toLowerCase().trim();
                if (cellText.indexOf(filters[col]) === -1) {
                    showRow = false;
                    break;
                }
            }
        }
        
        if (showRow && statusFilter && statusFilter !== '') {
            if (rowStatus !== statusFilter) {
                showRow = false;
            }
        }
        
        $(this).toggle(showRow);
    });
    
    updateFilteredStats();
}

function updateFilteredStats() {
    var total = 0, valid = 0, expired = 0, urgent = 0;
    
    $('#myTable tbody tr:visible').each(function() {
        total++;
        var status = $(this).data('status');
        if (status === 'valid') valid++;
        if (status === 'expired') expired++;
        if (status === 'urgent') urgent++;
    });
    
    $('#statsContainer').html(`
        <div class="stat-card stat-total" data-filter="all">
            <div class="stat-value">${total}</div>
            <div class="stat-label">إجمالي الشهادات</div>
        </div>
        <div class="stat-card stat-valid" data-filter="valid">
            <div class="stat-value">${valid}</div>
            <div class="stat-label">سارية المفعول</div>
        </div>
        <div class="stat-card stat-expired" data-filter="expired">
            <div class="stat-value">${expired}</div>
            <div class="stat-label">منتهية الصلاحية</div>
        </div>
        <div class="stat-card stat-urgent" data-filter="urgent">
            <div class="stat-value">${urgent}</div>
            <div class="stat-label">عاجلة (1-10 أيام)</div>
        </div>
    `);
}

$('.filter-input').on('keyup change', filterTable);

$('#clearFilters').on('click', function() {
    $('.filter-input').val('');
    $('#filterStatus').val('');
    filterTable();
});

// Stat cards filter
$('.stat-card').on('click', function() {
    var filter = $(this).data('filter');
    if (filter === 'all') {
        $('#filterStatus').val('');
        filterTable();
    } else {
        $('#filterStatus').val(filter);
        filterTable();
    }
});

// Export Excel
$('#exportExcelBtn').on('click', function() {
    window.location.href = 'index.php?action=medical_export';
});

// Print
$('#printTable').on('click', function() {
    window.print();
});

// Animations
document.querySelectorAll('#myTable tbody tr').forEach((row, index) => {
    row.style.animationDelay = (index * 0.05) + 's';
});
</script>

</body>
</html>