<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $typeInfo['name']; ?> - نظام متابعة الرخص</title>
    
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
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #17a2b8;
            --medical-color: #e74c3c;
            --rtt-color: #9b59b6;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --hover-shadow: 0 8px 15px rgba(0, 0, 0, 0.15);
            --gradient-primary: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            --gradient-success: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            --gradient-warning: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            --gradient-danger: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            --gradient-info: linear-gradient(135deg, #17a2b8 0%, #138d75 100%);
            --gradient-medical: linear-gradient(135deg, #e74c3c 0%, #ff6b6b 100%);
            --gradient-rtt: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }
        
        .header-section {
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
            position: relative;
            overflow: hidden;
            color: white;
        }
        
        .header-section.type-primary { background: var(--gradient-primary); }
        .header-section.type-info { background: var(--gradient-info); }
        .header-section.type-warning { background: var(--gradient-warning); }
        .header-section.type-medical { background: var(--gradient-medical); }
        .header-section.type-rtt { background: var(--gradient-rtt); }
        
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
        
        .mobile-message {
            display: none;
            background: var(--warning-color);
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .mobile-message { display: block; }
            body { padding: 15px; }
            .header-title { font-size: 1.8rem; }
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
        
        .stat-card:hover { transform: translateY(-5px); }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
        }
        
        .controls-section, .filter-section {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
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
        
        .filter-input {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .filter-input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
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
        
        .badge-year {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
            background: var(--gradient-info);
            color: white;
        }
        
        .duration-badge {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 700;
        }
        
        .medical-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--medical-color);
            display: inline-block;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }
        
        .compensation-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--rtt-color);
            display: inline-block;
            margin-right: 5px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { transform: scale(0.9); opacity: 0.7; }
            50% { transform: scale(1.2); opacity: 1; }
            100% { transform: scale(0.9); opacity: 0.7; }
        }
        
        .status-current {
            background: linear-gradient(90deg, rgba(39, 174, 96, 0.1) 0%, transparent 100%) !important;
            border-right: 4px solid var(--success-color);
        }
        
        .status-upcoming {
            background: linear-gradient(90deg, rgba(243, 156, 18, 0.1) 0%, transparent 100%) !important;
            border-right: 4px solid var(--warning-color);
        }
        
        .current-leave {
            background: linear-gradient(90deg, rgba(231, 76, 60, 0.08) 0%, transparent 100%);
            border-right: 4px solid var(--medical-color);
        }
        
        .comment-cell {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .comment-cell:hover {
            white-space: normal;
            overflow: visible;
            background: var(--light-bg);
            border-radius: 8px;
            padding: 10px;
            z-index: 10;
            box-shadow: var(--card-shadow);
        }
        
        .quick-filters {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 25px;
        }
        
        .year-filter {
            background: white;
            color: var(--primary-color);
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 10px 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .year-filter:hover, .year-filter.active {
            background: var(--gradient-primary);
            color: white;
            transform: translateY(-2px);
            border-color: var(--secondary-color);
        }
        
        .empty-comment {
            color: #6c757d;
            font-style: italic;
        }
        
        .date-badge {
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            color: white;
            padding: 4px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        #myTable tbody tr {
            animation: fadeInUp 0.5s ease-out;
        }
        
        @media (max-width: 576px) {
            .stats-cards { grid-template-columns: 1fr; }
            .btn-modern span { display: none; }
            .btn-modern { padding: 10px; width: 100%; }
        }
    </style>
</head>
<body>

<?php include(__DIR__ . '/../../menu.php'); ?>

<div class="header-section type-<?php echo $typeInfo['color']; ?>">
    <h1 class="header-title">
        <i class="fas <?php echo $typeInfo['icon']; ?> me-3"></i>
        <?php echo $typeInfo['name']; ?>
    </h1>
    <p class="header-subtitle"><?php echo $typeInfo['subtitle']; ?></p>
</div>

<div class="mobile-message">
    <i class="fas fa-info-circle me-2"></i>
    لرؤية كافة الأعمدة، يرجى التمرير أفقيًا ← →
</div>

<!-- Statistics Cards -->
<div class="stats-cards">
    <div class="stat-card">
        <div class="stat-value" id="totalConges"><?php echo $stats['total']; ?></div>
        <div class="stat-label">إجمالي <?php echo $typeInfo['name']; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="totalEmployees"><?php echo $stats['employees']; ?></div>
        <div class="stat-label">عدد الموظفين</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="currentYear"><?php echo $currentYear; ?></div>
        <div class="stat-label">السنة الحالية</div>
    </div>
    <div class="stat-card">
        <div class="stat-value" id="latestYear">
            <?php echo $type == 5 ? $stats['averageDays'] : $stats['latestYear']; ?>
        </div>
        <div class="stat-label">
            <?php echo $type == 5 ? 'متوسط مدة الرخص' : 'أحدث سنة'; ?>
        </div>
    </div>
</div>

<?php if ($type == 5 || $type == 2): ?>
<div class="quick-filters" id="yearFilters"></div>
<?php endif; ?>

<div class="controls-section">
    <div class="d-flex justify-content-between flex-wrap gap-3">
        <button id="clearFilters" class="btn-modern btn-clear">
            <i class="fas fa-trash-alt"></i>
            <span>مسح كل الفلاتر</span>
        </button>
        
        <a href="index.php?action=export&type=<?php echo $type; ?>" class="btn-modern btn-export">
            <i class="fas fa-file-excel"></i>
            <span>تصدير إلى Excel</span>
        </a>
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
            <input type="date" id="filterDateDebut" class="form-control filter-input" data-column="2" placeholder="التاريخ من...">
        </div>
        <div class="col-md-3">
            <input type="date" id="filterDateFin" class="form-control filter-input" data-column="3" placeholder="التاريخ إلى...">
        </div>
        <div class="col-md-3">
            <input type="text" id="filterAffectation" class="form-control filter-input" data-column="4" placeholder="وحدة الإرتباط...">
        </div>
        <?php if ($type != 5 && $type != 2): ?>
        <div class="col-md-3">
            <input type="text" id="filterComment" class="form-control filter-input" data-column="5" placeholder="الملاحظات...">
        </div>
        <?php elseif ($type == 5): ?>
        <div class="col-md-3">
            <input type="number" id="filterJours" class="form-control filter-input" data-column="5" placeholder="عدد الأيام...">
        </div>
        <?php endif; ?>
        <div class="col-md-3">
            <input type="text" id="filterAnnee" class="form-control filter-input" data-column="<?php echo ($type == 5 || $type == 2) ? '6' : '6'; ?>" placeholder="السنة...">
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
                <?php if ($type != 2): ?>
                <th>عدد الأيام</th>
                <?php endif; ?>
                <th>وحدة الإرتباط</th>
                <?php if ($type != 5 && $type != 2): ?>
                <th>ملاحظات</th>
                <?php endif; ?>
                <th>السنة</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($conges as $conge): 
                $rowClass = '';
                if ($type == 2 && $conge['status']) {
                    $rowClass = 'status-' . $conge['status'];
                } elseif ($type == 5 && $conge['isCurrentLeave']) {
                    $rowClass = 'current-leave';
                }
            ?>
            <tr class="<?php echo $rowClass; ?>">
                <td>
                    <?php echo htmlspecialchars($conge['mecano']); ?>
                    <?php if ($type == 5 && $conge['isCurrentLeave']): ?>
                        <span class="medical-indicator" title="إجازة مرضية حالية"></span>
                    <?php elseif ($type == 2): ?>
                        <span class="compensation-indicator"></span>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($conge['nom']); ?></td>
                <td>
                    <?php if ($type == 2): ?>
                        <span class="date-badge"><?php echo htmlspecialchars($conge['datedebut']); ?></span>
                    <?php else: ?>
                        <?php echo htmlspecialchars($conge['datedebut']); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($type == 2): ?>
                        <span class="date-badge"><?php echo htmlspecialchars($conge['datefin']); ?></span>
                    <?php else: ?>
                        <?php echo htmlspecialchars($conge['datefin']); ?>
                    <?php endif; ?>
                </td>
                <?php if ($type != 2): ?>
                <td>
                    <?php if ($type == 5): ?>
                        <span class="duration-badge"><?php echo htmlspecialchars($conge['nbj']); ?> يوم</span>
                    <?php else: ?>
                        <?php echo htmlspecialchars($conge['nbj'] ?? ''); ?>
                    <?php endif; ?>
                </td>
                <?php endif; ?>
                <td><?php echo htmlspecialchars($conge['depar']); ?></td>
                <?php if ($type != 5 && $type != 2): ?>
                <td class="comment-cell"><?php echo htmlspecialchars($conge['commentaire']); ?></td>
                <?php endif; ?>
                <td><span class="badge-year"><?php echo htmlspecialchars($conge['anne']); ?></span></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
const currentType = <?php echo $type; ?>;
const uniqueYears = <?php echo json_encode($stats['uniqueYears']); ?>;

function filterTable() {
    var filters = {};
    $('.filter-input').each(function() {
        if ($(this).val()) {
            filters[$(this).data('column')] = $(this).val().toLowerCase();
        }
    });
    
    var visibleRows = 0;
    var employees = [];
    var totalDays = 0;
    var latestYear = 0;
    
    $('#myTable tbody tr').each(function() {
        var showRow = true;
        var cells = $(this).find('td');
        var colOffset = (currentType == 2) ? 0 : (currentType == 5 ? 0 : 0);
        
        for (var col in filters) {
            var actualCol = parseInt(col);
            // Ajustement pour les colonnes
            if (currentType == 2 && actualCol >= 4) actualCol++;
            if ((currentType == 5 || currentType == 2) && actualCol == 5) actualCol++;
            
            var cellText = cells.eq(actualCol).text().toLowerCase();
            if (cellText.indexOf(filters[col]) === -1) {
                showRow = false;
                break;
            }
        }
        
        $(this).toggle(showRow);
        
        if (showRow) {
            visibleRows++;
            var mecano = cells.eq(0).text();
            if (!employees.includes(mecano)) employees.push(mecano);
            
            var yearIndex = (currentType == 2 || currentType == 5) ? 6 : 7;
            var year = parseInt(cells.eq(yearIndex).text());
            if (year > latestYear) latestYear = year;
        }
    });
    
    document.getElementById("totalConges").textContent = visibleRows;
    document.getElementById("totalEmployees").textContent = employees.length;
    if (currentType != 5) {
        document.getElementById("latestYear").textContent = latestYear;
    }
}

$('.filter-input').on('keyup change', filterTable);

$('#clearFilters').on('click', function() {
    $('.filter-input').val('');
    $('.year-filter').removeClass('active');
    filterTable();
});

$(document).on('click', '.year-filter', function() {
    $('.year-filter').removeClass('active');
    $(this).addClass('active');
    $('#filterAnnee').val($(this).data('year'));
    filterTable();
});

// Initialisation des filtres d'années pour maladie et RTT
if (currentType == 5 || currentType == 2) {
    let yearFiltersHTML = "";
    uniqueYears.sort().reverse().forEach(year => {
        yearFiltersHTML += `<button class="year-filter" data-year="${year}">${year}</button>`;
    });
    document.getElementById("yearFilters").innerHTML = yearFiltersHTML;
}

// Animation des lignes
document.addEventListener('DOMContentLoaded', function() {
    const rows = document.querySelectorAll('#myTable tbody tr');
    rows.forEach((row, index) => {
        row.style.animationDelay = (index * 0.05) + 's';
    });
});
</script>

</body>
</html>