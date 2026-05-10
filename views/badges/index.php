<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $typeInfo['title']; ?></title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    
    <!-- JavaScript Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --warning-color: #f39c12;
            --success-color: #27ae60;
            --expired-color: #e54342;
            --expiring-color: #ffa500;
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
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        
        .container-main {
            max-width: 95%;
            margin: 0 auto;
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
        }
        
        .header-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
        }
        
        .header-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            text-align: center;
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
            cursor: pointer;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
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
        
        .stat-expired .stat-value { color: var(--expired-color); }
        .stat-expiring .stat-value { color: var(--warning-color); }
        .stat-valid .stat-value { color: var(--success-color); }
        .stat-total .stat-value { color: var(--primary-color); }
        
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
            justify-content: center;
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
            background: var(--success-color);
            color: white;
        }
        
        .btn-print {
            background: var(--primary-color);
            color: white;
        }
        
        .action-buttons {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
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
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
            text-align: center;
            vertical-align: middle;
        }
        
        .custom-table tbody tr {
            transition: all 0.3s ease;
        }
        
        .custom-table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
            transform: scale(1.01);
        }
        
        .status-expired {
            background-color: rgba(229, 67, 66, 0.1) !important;
            border-right: 4px solid var(--expired-color);
        }
        
        .status-expiring {
            background-color: rgba(255, 165, 0, 0.1) !important;
            border-right: 4px solid var(--expiring-color);
        }
        
        .badge-custom {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .badge-expired {
            background-color: var(--expired-color);
            color: white;
        }
        
        .badge-expiring {
            background-color: var(--warning-color);
            color: white;
        }
        
        .badge-valid {
            background-color: var(--success-color);
            color: white;
        }
        
        .badge-primary {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .tooltip-container {
            position: relative;
            display: inline-block;
        }
        
        .tooltip-content {
            display: none;
            position: absolute;
            z-index: 100;
            width: 250px;
            padding: 10px;
            background: #fffAF0;
            border: 1px solid #DCA;
            border-radius: 4px;
            box-shadow: 5px 5px 8px #CCC;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 10px;
        }
        
        .tooltip-container:hover .tooltip-content {
            display: block;
        }
        
        .mobile-message {
            display: none;
            background-color: var(--secondary-color);
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .expiration-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }
        
        .indicator-expired { background-color: var(--expired-color); }
        .indicator-expiring { background-color: var(--warning-color); }
        .indicator-valid { background-color: var(--success-color); }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .print-header {
            display: none;
        }
        
        @media print {
            body { background: white; padding: 0; }
            .controls-section, .filter-section, .stats-section, .mobile-message, .btn, .action-buttons { display: none; }
            .table-container { box-shadow: none; }
            .custom-table thead th { background-color: #2c3e50 !important; color: white; }
            .print-header { display: block; text-align: center; margin-bottom: 20px; }
        }
        
        @media (max-width: 768px) {
            body { padding: 10px; }
            .mobile-message { display: block; }
            .stats-section { grid-template-columns: repeat(2, 1fr); }
            .btn-modern span { display: none; }
            .btn-modern { padding: 10px; }
        }
        
        @media (max-width: 576px) {
            .stats-section { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="container-main">
    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="editModalContent"></div>
        </div>
    </div>

    <?php include(__DIR__ . '/../../menu.php'); ?>

    <div class="header-section">
        <h1 class="header-title">
            <i class="fas <?php echo $typeInfo['icon']; ?> me-2"></i>
            <?php echo $typeInfo['title']; ?>
        </h1>
        <p class="header-subtitle"><?php echo $typeInfo['subtitle']; ?></p>
    </div>

    <!-- Print Header -->
    <div class="print-header">
        <div class="print-title"><?php echo $typeInfo['title']; ?></div>
        <div class="print-subtitle">قائمة الموظفين</div>
        <div class="print-date">تاريخ الطباعة: <?php echo date('Y-m-d'); ?></div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-section">
        <div class="stat-card stat-total" data-filter="all">
            <div class="stat-value"><?php echo $stats['total']; ?></div>
            <div class="stat-label">إجمالي الوثائق</div>
        </div>
        <div class="stat-card stat-valid" data-filter="valid">
            <div class="stat-value"><?php echo $stats['valid']; ?></div>
            <div class="stat-label">سارية المفعول</div>
        </div>
        <div class="stat-card stat-expiring" data-filter="expiring">
            <div class="stat-value"><?php echo $stats['expiring']; ?></div>
            <div class="stat-label">قريبة الإنتهاء</div>
        </div>
        <div class="stat-card stat-expired" data-filter="expired">
            <div class="stat-value"><?php echo $stats['expired']; ?></div>
            <div class="stat-label">منتهية الصلاحية</div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filter-section">
        <div class="row g-3">
            <div class="col-md-3">
                <input type="text" id="filterMecano" class="form-control filter-input column-filter" data-column="0" placeholder="البحث بالرقم الآلي...">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterNom" class="form-control filter-input column-filter" data-column="1" placeholder="البحث بالإسم أو اللقب...">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterTitre" class="form-control filter-input column-filter" data-column="2" placeholder="البحث بالرتبة...">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterNumCarte" class="form-control filter-input column-filter" data-column="3" placeholder="البحث برقم الرخصة...">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterDateEmission" class="form-control filter-input column-filter" data-column="4" placeholder="البحث بتاريخ الإصدار...">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterFinValidite" class="form-control filter-input column-filter" data-column="5" placeholder="البحث بنهاية الصلوحيّة...">
            </div>
            <div class="col-md-3">
                <select id="filterDepar" class="form-control filter-input column-filter" data-column="6">
                    <option value="">كل الوكالات</option>
                    <?php foreach ($departments as $dep): ?>
                        <option value="<?php echo htmlspecialchars($dep); ?>"><?php echo htmlspecialchars($dep); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <select id="filterStatus" class="form-control filter-input status-filter">
                    <option value="">كل الحالات</option>
                    <option value="valid">سارية المفعول</option>
                    <option value="expiring">قريبة الإنتهاء</option>
                    <option value="expired">منتهية الصلاحية</option>
                </select>
            </div>
        </div>
    </div>

    <div class="controls-section">
        <div class="action-buttons">
            <button id="clearFilters" class="btn-modern btn-clear">
                <i class="fas fa-trash-alt"></i>
                <span>مسح كل الفلاتر</span>
            </button>
            
            <div class="d-flex gap-3">
                <button id="exportExcel" class="btn-modern btn-export">
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
                    <th>رقم الرخصة</th>
                    <th>تاريخ الإصدار</th>
                    <th>نهاية الصلوحيّة</th>
                    <th>وحدة الإرتباط</th>
                    <?php if ($isAdmin): ?>
                        <th>الإجراءات</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($badges as $badge): ?>
                <tr class="<?php echo $badge['statusClass']; ?> fade-in" data-status="<?php echo $badge['status']; ?>">
                    <td>
                        <div class="tooltip-container">
                            <span class="badge-custom <?php echo $badge['statusBadge']; ?>"><?php echo htmlspecialchars($badge['mecano']); ?></span>
                            <span class="expiration-indicator <?php echo $badge['statusIndicator']; ?>"></span>
                            <?php if ($badge['status'] == 'expired'): ?>
                                <div class="tooltip-content">
                                    <strong>إنتهت صلوحية البطاقة</strong>
                                </div>
                            <?php elseif ($badge['status'] == 'expiring'): ?>
                                <div class="tooltip-content">
                                    <strong>تنتهي صلوحية البطاقة خلال <?php echo $badge['jours_restants']; ?> يومًا</strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($badge['nom']); ?></td>
                    <td><span class="badge-custom badge-primary"><?php echo htmlspecialchars($badge['libellet']); ?> <?php echo htmlspecialchars($badge['situation']); ?></span></td>
                    <td><?php echo htmlspecialchars($badge['numcarte']); ?></td>
                    <td><?php echo htmlspecialchars($badge['dateemission']); ?></td>
                    <td><strong><?php echo htmlspecialchars($badge['finvalidite']); ?></strong></td>
                    <td><?php echo htmlspecialchars($badge['depar']); ?></td>
                    <?php if ($isAdmin): ?>
                    <td>
                        <a href="#" class="btn btn-sm btn-outline-primary edit-user-btn" data-mecano="<?php echo $badge['mecano']; ?>" data-type="<?php echo $type; ?>">
                            <i class="fas fa-edit"></i> تعديل
                        </a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($badges)): ?>
                <tr>
                    <td colspan="<?php echo $isAdmin ? 8 : 7; ?>" class="text-center py-4">
                        <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                        لا توجد بيانات للعرض
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Modal functionality
$(document).ready(function() {
    $('.edit-user-btn').on('click', function(e) {
        e.preventDefault();
        const mecano = $(this).data('mecano');
        const type = $(this).data('type');

        $('#editModalContent').load('index.php?action=badges_edit&mecano=' + encodeURIComponent(mecano) + '&type=' + encodeURIComponent(type), function() {
            $('#editModal').modal('show');
        });
    });
});

// Filter table function
function filterTable() {
    var filters = {};
    $('.column-filter').each(function() {
        if ($(this).val()) {
            filters[$(this).data('column')] = $(this).val().toLowerCase();
        }
    });
    
    var statusFilter = $('#filterStatus').val();
    
    $('#myTable tbody tr').each(function() {
        var showRow = true;
        var cells = $(this).find('td');
        
        for (var col in filters) {
            var cellText = cells.eq(col).text().toLowerCase();
            if (cellText.indexOf(filters[col]) === -1) {
                showRow = false;
                break;
            }
        }
        
        if (showRow && statusFilter) {
            var rowStatus = $(this).data('status');
            if (rowStatus !== statusFilter) {
                showRow = false;
            }
        }
        
        $(this).toggle(showRow);
    });
}

$('.column-filter, .status-filter').on('keyup change', filterTable);

$('#clearFilters').on('click', function() {
    $('.column-filter, .status-filter').val('');
    $('#filterStatus').val('');
    filterTable();
});

// Export Excel
$('#exportExcel').on('click', function() {
    window.location.href = 'index.php?action=badges_export&type=<?php echo $type; ?>';
});

// Print
$('#printTable').on('click', function() {
    window.print();
});

// Stat cards filter
$('.stat-card').on('click', function() {
    var filter = $(this).data('filter');
    if (filter && filter !== 'all') {
        $('#filterStatus').val(filter);
        filterTable();
    } else {
        $('#filterStatus').val('');
        filterTable();
    }
});

// Animations
document.querySelectorAll('#myTable tbody tr').forEach((row, index) => {
    row.style.animationDelay = (index * 0.05) + 's';
});
</script>

</body>
</html>