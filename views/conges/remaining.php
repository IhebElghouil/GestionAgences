<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<title>رصيد الراحات المتبقية</title>

<style>
:root {
    --primary-color: #2c3e50;
    --secondary-color: #3498db;
    --success-color: #27ae60;
    --warning-color: #f39c12;
    --danger-color: #e74c3c;
    --info-color: #17a2b8;
    --light-bg: #f8f9fa;
    --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --hover-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
    --gradient-primary: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
    --gradient-success: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    --gradient-warning: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    --gradient-danger: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
}

body {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 20px;
}

.header-section {
    background: var(--gradient-primary);
    color: white;
    border-radius: 20px;
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
}

.header-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    text-align: center;
}

.stats-cards {
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
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.9rem;
    color: #6c757d;
}

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
}

.filter-input:focus {
    border-color: var(--secondary-color);
    box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    outline: none;
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
    background: var(--gradient-success);
    color: white;
}

.btn-print {
    background: var(--gradient-primary);
    color: white;
}

.table-container {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: var(--card-shadow);
    overflow-x: auto;
}

#myTable {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.95rem;
}

#myTable thead {
    background: var(--gradient-primary);
    color: white;
}

#myTable th {
    padding: 16px 12px;
    text-align: center;
    font-weight: 700;
}

#myTable tbody tr {
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

#myTable tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-2px);
}

#myTable td {
    padding: 14px 12px;
    text-align: center;
    vertical-align: middle;
}

.badge-rest {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.85rem;
}

.rest-high {
    background: var(--gradient-success);
    color: white;
}

.rest-medium {
    background: var(--gradient-warning);
    color: white;
}

.rest-low {
    background: var(--gradient-danger);
    color: white;
}

.maladie-cell {
    font-weight: 700;
    color: var(--danger-color);
}

.mobile-message {
    display: none;
    background: var(--warning-color);
    color: white;
    padding: 10px;
    text-align: center;
    border-radius: 10px;
    margin-bottom: 15px;
}

@media (max-width: 768px) {
    body { padding: 15px; }
    .mobile-message { display: block; }
    .header-title { font-size: 1.8rem; }
    .stats-cards { grid-template-columns: repeat(2, 1fr); }
    .btn-modern span { display: none; }
    .btn-modern { padding: 10px; }
}

@media (max-width: 576px) {
    .stats-cards { grid-template-columns: 1fr; }
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

#myTable tbody tr {
    animation: fadeInUp 0.5s ease-out;
}

@media print {
    body { background: white; padding: 0; }
    .controls-section, .filter-section, .mobile-message, .stats-cards, .btn-modern { display: none; }
    .table-container { box-shadow: none; }
    #myTable thead { background: #2c3e50 !important; color: white; }
    #myTable th, #myTable td { border: 1px solid #ddd; }
    .badge-rest { border: 1px solid #333; background: white !important; color: #333; }
}
</style>
</head>
<body>

<?php include(__DIR__ . '/../../menu.php'); ?>

<div class="header-section">
    <h1 class="header-title">متابعة رصيد الراحات المتبقية</h1>
    <p class="header-subtitle">عرض شامل لرصيد الإجازات والخصومات للموظفين</p>
</div>

<div class="mobile-message">
    <i class="fas fa-info-circle me-2"></i>
    لرؤية كافة الأعمدة، يرجى التمرير أفقيًا ← →
</div>

<!-- Statistics Cards -->
<div class="stats-cards">
    <div class="stat-card">
        <div class="stat-value" id="totalEmployees"><?php echo $stats['totalEmployees']; ?></div>
        <div class="stat-label">إجمالي الموظفين</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="avgRest"><?php echo $stats['avgRest']; ?></div>
        <div class="stat-label">متوسط الرصيد</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="maxRest"><?php echo $stats['maxRest']; ?></div>
        <div class="stat-label">أعلى رصيد</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="minRest"><?php echo $stats['minRest']; ?></div>
        <div class="stat-label">أقل رصيد</div>
    </div>
</div>

<div class="controls-section">
    <div class="d-flex justify-content-between flex-wrap gap-3">
        <button id="clearFilters" class="btn-modern btn-clear">
            <i class="fas fa-trash-alt"></i>
            <span>مسح كل الفلاتر</span>
        </button>
        
        <div class="d-flex gap-2">
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
            <input type="text" id="filterMaladie" class="form-control filter-input" data-column="6" placeholder="البحث بعدد أيام الخصم...">
        </div>
        <div class="col-md-3">
            <input type="text" id="filterRest" class="form-control filter-input" data-column="7" placeholder="البحث بالرصيد...">
        </div>
        <div class="col-md-3">
            <input type="text" id="filterDepartement" class="form-control filter-input" data-column="8" placeholder="البحث بوحدة الإرتباط...">
        </div>
    </div>
</div>

<div class="table-container">
    <table id="myTable" dir="rtl">
        <thead>
            <tr>
                <th>الرقم الآلي</th>
                <th>الإسم و اللقب</th>
                <th>الرتبة</th>
                <th>باقي الإجازات N-2</th>
                <th>مستحقات السنة الحالية</th>
                <th>الإجازات المستهلكة</th>
                <th>خصم بعنوان المرض</th>
                <th>الرصيد الحالي</th>
                <th>وحدة الإرتباط</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($remainingLeaves as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item['mecano']); ?></td>
                <td><?php echo htmlspecialchars($item['nom']); ?></td>
                <td><?php echo htmlspecialchars($item['libellet']); ?></td>
                <td><?php echo htmlspecialchars($item['nbj1']); ?></td>
                <td><?php echo htmlspecialchars($item['nbj2']); ?></td>
                <td><?php echo htmlspecialchars($item['congepris']); ?></td>
                <td class="maladie-cell"><?php echo htmlspecialchars($item['maladie_deduction']); ?></td>
                <td><span class="badge-rest <?php echo $item['badge_class']; ?>"><?php echo htmlspecialchars($item['current_rest']); ?></span></td>
                <td><?php echo htmlspecialchars($item['depar']); ?></td>
            </tr>
            <?php endforeach; ?>
            
            <?php if (empty($remainingLeaves)): ?>
            <tr>
                <td colspan="9" class="text-center py-4">
                    <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                    لا توجد بيانات للعرض
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
function filterTable() {
    var filters = {};
    $('.filter-input').each(function() {
        if ($(this).val()) {
            filters[$(this).data('column')] = $(this).val().toLowerCase();
        }
    });
    
    var visibleRows = 0;
    var totalRest = 0;
    var maxRest = 0;
    var minRest = 1000;
    
    $('#myTable tbody tr').each(function() {
        var showRow = true;
        var cells = $(this).find('td');
        
        if (cells.length < 9) return;
        
        for (var col in filters) {
            var cellText = cells.eq(col).text().toLowerCase();
            if (cellText.indexOf(filters[col]) === -1) {
                showRow = false;
                break;
            }
        }
        
        $(this).toggle(showRow);
        
        if (showRow) {
            visibleRows++;
            var restValue = parseInt(cells.eq(7).text()) || 0;
            totalRest += restValue;
            if (restValue > maxRest) maxRest = restValue;
            if (restValue < minRest) minRest = restValue;
        }
    });
    
    document.getElementById("totalEmployees").textContent = visibleRows;
    document.getElementById("avgRest").textContent = visibleRows > 0 ? (totalRest / visibleRows).toFixed(1) : 0;
    document.getElementById("maxRest").textContent = maxRest;
    document.getElementById("minRest").textContent = minRest == 1000 ? 0 : minRest;
}

$('.filter-input').on('keyup change', filterTable);

$('#clearFilters').on('click', function() {
    $('.filter-input').val('');
    filterTable();
});

// Export Excel
$('#exportExcel').on('click', function() {
    var table = document.getElementById('myTable');
    var ws = XLSX.utils.table_to_sheet(table);
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "رصيد_الإجازات");
    XLSX.writeFile(wb, "رصيد_الإجازات_" + new Date().toISOString().slice(0,10) + ".xlsx");
});

// Impression
$('#printTable').on('click', function() {
    window.print();
});

// Animation des lignes
document.querySelectorAll('#myTable tbody tr').forEach((row, index) => {
    row.style.animationDelay = (index * 0.05) + 's';
});
</script>

</body>
</html>