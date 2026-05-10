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

<title>الإجازات السنوية - <?php echo $selectedYear; ?></title>

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

.year-selector {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: var(--card-shadow);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    flex-wrap: wrap;
}

.year-selector label {
    font-weight: 700;
    color: var(--primary-color);
}

.year-selector select {
    border-radius: 12px;
    padding: 10px 15px;
    border: 2px solid #e9ecef;
    min-width: 120px;
}

.year-selector select:focus {
    border-color: var(--secondary-color);
    outline: none;
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
    color: var(--primary-color);
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

.badge-days {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.85rem;
    background: var(--gradient-success);
    color: white;
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
</style>
</head>
<body>

<?php include(__DIR__ . '/../../menu.php'); ?>

<div class="header-section">
    <h1 class="header-title">متابعة الإجازات السنوية</h1>
    <p class="header-subtitle">عرض شامل للإجازات السنوية للموظفين حسب السنة</p>
</div>

<div class="year-selector">
    <label for="yearSelect">اختر السنة:</label>
    <select id="yearSelect" class="form-select">
        <?php foreach ($availableYears as $year): ?>
            <option value="<?php echo $year; ?>" <?php echo $year == $selectedYear ? 'selected' : ''; ?>>
                <?php echo $year; ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<div class="mobile-message">
    <i class="fas fa-info-circle me-2"></i>
    لرؤية كافة الأعمدة، يرجى التمرير أفقيًا ← →
</div>

<!-- Statistics Cards -->
<div class="stats-cards">
    <div class="stat-card">
        <div class="stat-value" id="totalConges"><?php echo $stats['total']; ?></div>
        <div class="stat-label">إجمالي الإجازات</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="totalEmployees"><?php echo $stats['employees']; ?></div>
        <div class="stat-label">عدد الموظفين</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="avgDays"><?php echo $stats['averageDays']; ?></div>
        <div class="stat-label">متوسط أيام الإجازة</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="totalDays"><?php echo $stats['totalDays']; ?></div>
        <div class="stat-label">إجمالي أيام الإجازة</div>
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
            <input type="text" id="filterDateDebut" class="form-control filter-input" data-column="2" placeholder="البحث بالتاريخ من...">
        </div>
        <div class="col-md-3">
            <input type="text" id="filterDateFin" class="form-control filter-input" data-column="3" placeholder="البحث بالتاريخ إلى...">
        </div>
        <div class="col-md-3">
            <input type="text" id="filterJours" class="form-control filter-input" data-column="4" placeholder="البحث بعدد الأيام...">
        </div>
        <div class="col-md-3">
            <input type="text" id="filterDepartement" class="form-control filter-input" data-column="5" placeholder="البحث بوحدة الإرتباط...">
        </div>
    </div>
</div>

<div class="table-container">
    <table id="myTable" dir="rtl">
        <thead>
            <tr>
                <th>الرقم الآلي</th>
                <th>الإسم و اللقب</th>
                <th>التاريخ من</th>
                <th>التاريخ إلى</th>
                <th>عدد الأيام</th>
                <th>وحدة الإرتباط</th>
                <th>السنة</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($leaves as $leave): ?>
            <tr>
                <td><?php echo htmlspecialchars($leave['mecano']); ?></td>
                <td><?php echo htmlspecialchars($leave['nom']); ?></td>
                <td><?php echo htmlspecialchars($leave['datedebut']); ?></td>
                <td><?php echo htmlspecialchars($leave['datefin']); ?></td>
                <td><span class="badge-days"><?php echo htmlspecialchars($leave['nbjours']); ?></span></td>
                <td><?php echo htmlspecialchars($leave['depar']); ?></td>
                <td><?php echo htmlspecialchars($leave['annee']); ?></td>
            </tr>
            <?php endforeach; ?>
            
            <?php if (empty($leaves)): ?>
            <tr>
                <td colspan="7" class="text-center py-4">
                    <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                    لا توجد بيانات للإجازات لهذه السنة
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
    var totalDays = 0;
    var employees = [];
    
    $('#myTable tbody tr').each(function() {
        var showRow = true;
        var cells = $(this).find('td');
        
        if (cells.length < 6) return;
        
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
            var days = parseInt(cells.eq(4).text()) || 0;
            totalDays += days;
            var mecano = cells.eq(0).text();
            if (!employees.includes(mecano)) employees.push(mecano);
        }
    });
    
    document.getElementById("totalConges").textContent = visibleRows;
    document.getElementById("totalEmployees").textContent = employees.length;
    document.getElementById("avgDays").textContent = visibleRows > 0 ? (totalDays / visibleRows).toFixed(1) : 0;
    document.getElementById("totalDays").textContent = totalDays;
}

$('.filter-input').on('keyup change', filterTable);

$('#clearFilters').on('click', function() {
    $('.filter-input').val('');
    filterTable();
});

// Changement d'année
$('#yearSelect').on('change', function() {
    var year = $(this).val();
    window.location.href = 'index.php?action=annual_leaves&year=' + year;
});

// Export Excel
$('#exportExcel').on('click', function() {
    window.location.href = 'index.php?action=export_annual_leaves&year=<?php echo $selectedYear; ?>';
});

// Impression
$('#printTable').on('click', function() {
    var printWindow = window.open('', '_blank');
    var content = document.getElementById('myTable').cloneNode(true);
    var year = $('#yearSelect').val();
    
    var printContent = `
    <!DOCTYPE html>
    <html dir="rtl" lang="ar">
    <head>
        <meta charset="UTF-8">
        <title>الإجازات السنوية <?php echo $selectedYear; ?></title>
        <style>
            body { font-family: 'Segoe UI', sans-serif; margin: 20px; }
            .print-header { text-align: center; margin-bottom: 20px; }
            .print-title { font-size: 24px; font-weight: bold; }
            .print-subtitle { font-size: 16px; color: #666; }
            table { width: 100%; border-collapse: collapse; }
            th { background: #2c3e50; color: white; padding: 10px; border: 1px solid #ddd; }
            td { padding: 8px; border: 1px solid #ddd; text-align: center; }
            .badge-days { background: #27ae60; color: white; padding: 4px 8px; border-radius: 12px; }
            @media print { @page { size: landscape; } }
        </style>
    </head>
    <body>
        <div class="print-header">
            <div class="print-title">متابعة الإجازات السنوية</div>
            <div class="print-subtitle">السنة: ${year}</div>
        </div>
        ${content.outerHTML}
    </body>
    </html>`;
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.print();
});

// Animation des lignes
document.querySelectorAll('#myTable tbody tr').forEach((row, index) => {
    row.style.animationDelay = (index * 0.05) + 's';
});
</script>

</body>
</html>