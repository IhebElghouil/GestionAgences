<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<title>حوادث الشغل</title>

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
    cursor: pointer;
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

.controls-section, .filter-section {
    background: white;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: var(--card-shadow);
}

.filter-input, .filter-select {
    border-radius: 12px;
    padding: 12px 15px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.filter-input:focus, .filter-select:focus {
    border-color: var(--secondary-color);
    outline: none;
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
    background: var(--gradient-success);
    color: white;
}

.btn-print {
    background: var(--gradient-info);
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

.tr-expired {
    background: linear-gradient(135deg, #e54342 0%, #c0392b 100%) !important;
    color: white;
}

.tr-expiring {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    color: white;
}

.tr-today {
    background: linear-gradient(135deg, #f1c40f 0%, #f39c12 100%) !important;
    color: #333;
}

.badge-accident {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.85rem;
    color: white;
}

.badge-primary { background: var(--gradient-primary); }
.badge-warning { background: var(--gradient-warning); }
.badge-danger { background: var(--gradient-danger); }
.badge-secondary { background: #6c757d; }


.comment-cell {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.3s ease;
}

.comment-cell:hover {
    white-space: normal;
    overflow: visible;
    position: relative;
    z-index: 10;
   
}

/* Supprimer complètement ces règles si elles existent :
.comment-cell:hover {
    background: var(--light-bg);
    border-radius: 8px;
    padding: 10px;
    box-shadow: var(--card-shadow);
}
*/

.tooltip-wrapper {
    position: relative;
    display: inline-block;
}

.tooltip-content {
    visibility: hidden;
    width: 250px;
    background: white;
    color: #333;
    text-align: center;
    border-radius: 8px;
    padding: 12px;
    position: absolute;
    z-index: 1000;
    bottom: 125%;
    right: 50%;
    transform: translateX(50%);
    box-shadow: var(--hover-shadow);
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip-wrapper:hover .tooltip-content {
    visibility: visible;
    opacity: 1;
}

.btn-edit {
    background: var(--gradient-info);
    color: blue;
    border: none;
    border-radius: 8px;
    padding: 8px 12px;
    transition: all 0.3s ease;
}

.btn-edit:hover {
    transform: scale(1.1);
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

#myTable tbody tr {
    animation: fadeInUp 0.5s ease-out;
}

@media (max-width: 768px) {
    .stats-cards { grid-template-columns: repeat(2, 1fr); }
    .btn-modern span { display: none; }
    .btn-modern { padding: 10px; }
    .comment-cell { max-width: 150px; }
}

@media (max-width: 576px) {
    .stats-cards { grid-template-columns: 1fr; }
    .comment-cell { max-width: 100px; }
}
</style>
</head>
<body>

<?php include(__DIR__ . '/../../menu.php'); ?>

<div class="header-section">
    <h1 class="header-title">متابعة حوادث الشغل</h1>
    <p class="header-subtitle">عرض شامل لحوادث الشغل والرخص الطبية المرتبطة بها</p>
</div>

<div class="mobile-message">
    <i class="fas fa-info-circle me-2"></i>
    لرؤية كافة الأعمدة، يرجى التمرير أفقيًا ← →
</div>

<!-- Statistics Cards -->
<div class="stats-cards">
    <div class="stat-card stat-total" data-filter="all">
        <div class="stat-value" id="totalAccidents"><?php echo $stats['total']; ?></div>
        <div class="stat-label">إجمالي الحوادث</div>
    </div>
    <div class="stat-card stat-expired" data-filter="expired">
        <div class="stat-value" id="expiredCount"><?php echo $stats['expired']; ?></div>
        <div class="stat-label">منتهية الصلاحية</div>
    </div>
    <div class="stat-card stat-expiring" data-filter="expiring">
        <div class="stat-value" id="expiringCount"><?php echo $stats['expiring']; ?></div>
        <div class="stat-label">قريبة الإنتهاء</div>
    </div>
    <div class="stat-card">
        <div class="stat-value"><?php echo $currentYear; ?></div>
        <div class="stat-label">السنة الحالية</div>
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
            <input type="date" id="filterDateDebut" class="form-control filter-input" data-column="3" placeholder="التاريخ من...">
        </div>
        <div class="col-md-3">
            <input type="date" id="filterDateFin" class="form-control filter-input" data-column="4" placeholder="التاريخ إلى...">
        </div>
        <div class="col-md-3">
            <input type="text" id="filterNcnss" class="form-control filter-input" data-column="6" placeholder="رقم الضمان الإجتماعي...">
        </div>
        <div class="col-md-3">
            <select id="filterAgence" class="form-control filter-select" data-column="7">
                <option value="">جميع الوحدات</option>
                <?php foreach ($departments as $dep): ?>
                    <option value="<?php echo htmlspecialchars($dep['depar']); ?>"><?php echo htmlspecialchars($dep['depar']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select id="filterTypeAccident" class="form-control filter-select" data-column="8">
                <option value="">جميع أنواع الحوادث</option>
                <option value="حادث أولي">حادث أولي</option>
                <option value="تمديد">تمديد</option>
                <option value="انتكاسة">انتكاسة</option>
            </select>
        </div>
        <div class="col-md-3">
            <input type="text" id="filterAnnee" class="form-control filter-input" data-column="10" placeholder="البحث بالسنة...">
        </div>
        <div class="col-md-3">
            <select id="filterValidite" class="form-control filter-select">
                <option value="">جميع الحالات</option>
                <option value="status-valid">صالحة</option>
                <option value="status-expiring">قريبة الإنتهاء</option>
                <option value="status-expired">منتهية الصلاحية</option>
            </select>
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
                <th>التاريخ من</th>
                <th>التاريخ إلى</th>
                <th>عدد الأيام</th>
                <th>رقم الضمان الإجتماعي</th>
                <th>وحدة الإرتباط</th>
                <th>نوع الحادث</th>
                <th>ملاحظات</th>
                <th>السنة</th>
                <?php if ($isAdmin): ?>
                    <th>إجراءات</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($accidents as $accident): ?>
            <tr class="<?php echo $accident['rowClass']; ?>" data-status="<?php echo $accident['statusClass']; ?>">
                <td>
                    <div class="tooltip-wrapper">
                        <span class="spnDetails"><?php echo htmlspecialchars($accident['mecano']); ?></span>
                        <?php if ($accident['tooltipText']): ?>
                        <div class="tooltip-content">
                            <strong>تنبيه</strong>
                            <?php echo $accident['tooltipText']; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </td>
                <td><?php echo htmlspecialchars($accident['nom']); ?></td>
                <td><?php echo htmlspecialchars($accident['libellet']); ?></td>
                <td><?php echo htmlspecialchars($accident['datedebut']); ?></td>
                <td><?php echo htmlspecialchars($accident['datefin']); ?></td>
                <td><span class="badge-accident badge-secondary"><?php echo $accident['daydiff']; ?></span></td>
                <td><?php echo htmlspecialchars($accident['ncnss']); ?></td>
                <td><?php echo htmlspecialchars($accident['depar']); ?></td>
                <td><span class="badge-accident <?php echo $accident['type_badge_class']; ?>"><?php echo $accident['type_nom']; ?></span></td>
                <td class="comment-cell"><?php echo htmlspecialchars($accident['commentaire']); ?></td>
                <td><?php echo htmlspecialchars($accident['anne']); ?></td>
                <?php if ($isAdmin): ?>
                <td>
                    <a href="#" class="btn-edit edit-accident-btn" data-id="<?php echo $accident['id']; ?>">
                        <i class="fas fa-edit"></i>
                    </a>
                </td>
                <?php endif; ?>
            </tr>
            <?php endforeach; ?>
            
            <?php if (empty($accidents)): ?>
            <tr>
                <td colspan="<?php echo $isAdmin ? 12 : 11; ?>" class="text-center py-4">
                    <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                    لا توجد بيانات للعرض
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="editModalContent"></div>
    </div>
</div>

<script>
// Modal functionality
$(document).ready(function() {
    $('.edit-accident-btn').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        
        $('#editModalContent').load('index.php?action=accidents_edit&id=' + encodeURIComponent(id), function() {
            $('#editModal').modal('show');
        });
    });
});

// Filter table function
function filterTable() {
    var filters = {};
    $('.filter-input, .filter-select').each(function() {
        if ($(this).val()) {
            filters[$(this).data('column')] = $(this).val().toLowerCase();
        }
    });
    
    var validiteFilter = $('#filterValidite').val();
    var typeAccidentFilter = $('#filterTypeAccident').val().toLowerCase();
    
    $('#myTable tbody tr').each(function() {
        var showRow = true;
        var cells = $(this).find('td');
        
        // Filtres de colonnes standards
        for (var col in filters) {
            if (filters.hasOwnProperty(col)) {
                var colIndex = parseInt(col);
                // Vérifier si la colonne existe
                if (cells.length > colIndex) {
                    var cellText = cells.eq(colIndex).text().toLowerCase();
                    if (cellText.indexOf(filters[col]) === -1) {
                        showRow = false;
                        break;
                    }
                }
            }
        }
        
        // Filtre par type d'accident (colonne 8)
        if (showRow && typeAccidentFilter && typeAccidentFilter !== '') {
            var typeText = cells.eq(8).text().toLowerCase();
            if (typeText !== typeAccidentFilter) {
                showRow = false;
            }
        }
        
        // Filtre par validité - IMPORTANT: gérer le cas "toutes les valeurs"
        if (showRow && validiteFilter && validiteFilter !== '') {
            var rowStatus = $(this).data('status');
            // Si le filtre n'est pas vide, on applique le filtre
            if (rowStatus !== validiteFilter) {
                showRow = false;
            }
        }
        // Si validiteFilter est vide ou "toutes les valeurs", on affiche tout
        
        $(this).toggle(showRow);
    });
    
    updateFilteredStats();
}

function updateFilteredStats() {
    var visibleRows = $('#myTable tbody tr:visible');
    var totalAccidents = visibleRows.length;
    var expiredCount = 0;
    var expiringCount = 0;
    
    visibleRows.each(function() {
        if ($(this).hasClass('tr-expired')) {
            expiredCount++;
        } else if ($(this).hasClass('tr-expiring') || $(this).hasClass('tr-today')) {
            expiringCount++;
        }
    });
    
    document.getElementById("totalAccidents").textContent = totalAccidents;
    document.getElementById("expiredCount").textContent = expiredCount;
    document.getElementById("expiringCount").textContent = expiringCount;
}

$('.filter-input, .filter-select').on('keyup change', filterTable);

$('#clearFilters').on('click', function() {
    $('.filter-input, .filter-select').val('');
    $('#filterValidite').val('');
    filterTable();
});

// Export Excel
$('#exportExcel').on('click', function() {
    window.location.href = 'index.php?action=accidents_export';
});

// Print
$('#printTable').on('click', function() {
    window.print();
});

// Stat cards filter
$('.stat-card').on('click', function() {
    var filter = $(this).data('filter');
    if (filter === 'expired') {
        $('#filterValidite').val('status-expired');
        filterTable();
    } else if (filter === 'expiring') {
        $('#filterValidite').val('status-expiring');
        filterTable();
    } else {
        $('#filterValidite').val('');
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