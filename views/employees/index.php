<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة الأعوان</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            --accent-color: #e74c3c;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --info-color: #17a2b8;
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
            background: linear-gradient(135deg, #f5f7fa 0%, #e3e9f7 100%);
            min-height: 100vh;
        }
        
        .container-main {
            max-width: 1800px;
            margin: 0 auto;
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
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
        }
        
        .header-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            text-align: center;
        }
        
        .stats-section {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
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
            border-top: 4px solid;
        }
        
        .stat-card[data-type="all"] { border-top-color: #667eea; }
        .stat-card[data-type="actif"] { border-top-color: #38ef7d; }
        .stat-card[data-type="permanent"] { border-top-color: #3498db; }
        .stat-card[data-type="trainee"] { border-top-color: #f39c12; }
        .stat-card[data-type="attached_internal"] { border-top-color: #2980b9; }
        .stat-card[data-type="attached_external"] { border-top-color: #9b59b6; }
        .stat-card[data-type="indisponible"] { border-top-color: #e74c3c; }
        .stat-card[data-type="filtered"] { border-top-color: #7f8c8d; }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
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
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .filter-input {
            border-radius: 12px;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            width: 100%;
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
            background: var(--success-color);
            color: white;
        }
        
        .action-buttons {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .add-employee-btn {
            background: linear-gradient(135deg, var(--success-color) 0%, #229954 100%);
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
            cursor: pointer;
        }
        
        .add-employee-btn:hover {
            transform: translateY(-3px);
            box-shadow: var(--hover-shadow);
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--card-shadow);
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
            padding: 12px;
            text-align: center;
            position: sticky;
            top: 0;
            font-size: 0.85rem;
            white-space: nowrap;
        }
        
        .custom-table tbody td {
            padding: 10px 8px;
            border-bottom: 1px solid #e9ecef;
            text-align: center;
            vertical-align: middle;
            font-size: 0.85rem;
        }
        
        .custom-table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .badge-custom {
            padding: 4px 8px;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.75rem;
            display: inline-block;
        }
        
        .badge-success { background: linear-gradient(135deg, #27ae60 0%, #229954 100%); color: white; }
        .badge-warning { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); color: white; }
        .badge-info { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); color: white; }
        .badge-danger { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); color: white; }
        .badge-secondary { background: linear-gradient(135deg, #7f8c8d 0%, #6c757d 100%); color: white; }
        .badge-purple { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); color: white; }
        
        .employee-id {
            background: linear-gradient(135deg, var(--primary-color) 0%, #34495e 100%);
            color: white;
            padding: 4px 8px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-block;
        }
        
        .filtered-results {
            background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
            color: white;
            padding: 10px 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: 600;
            display: none;
        }
        
        .mobile-message {
            display: none;
            background: linear-gradient(135deg, var(--secondary-color) 0%, #2980b9 100%);
            color: white;
            padding: 12px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 6px 10px;
            margin: 2px;
            cursor: pointer;
            font-size: 0.75rem;
        }
        
        .btn-attestation {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 6px 10px;
            margin: 2px;
            cursor: pointer;
            font-size: 0.75rem;
        }
        
        @media (max-width: 1200px) {
            .stats-section { grid-template-columns: repeat(4, 1fr); }
        }
        
        @media (max-width: 768px) {
            body { padding: 10px; }
            .stats-section { grid-template-columns: repeat(2, 1fr); }
            .filter-section { grid-template-columns: 1fr; }
            .mobile-message { display: block; }
            .btn-modern span { display: none; }
            .btn-modern { padding: 10px; }
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
        <h1 class="header-title">إدارة الأعوان</h1>
        <p class="header-subtitle">عرض شامل لجميع الأعوان والموظفين</p>
    </div>

    <?php
    // Afficher les messages d'erreur (si existants)
    if (!empty($_SESSION['error_message'])) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "error",
                    title: "خطأ",
                    text: "' . addslashes($_SESSION['error_message']) . '",
                    confirmButtonText: "موافق",
                    confirmButtonColor: "#d33"
                });
            });
        </script>';
        unset($_SESSION['error_message']);
    }

    // Afficher les messages de succès (si existants)
    if (!empty($_SESSION['success_message'])) {
        echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "success",
                    title: "تم بنجاح",
                    text: "' . addslashes($_SESSION['success_message']) . '",
                    confirmButtonText: "موافق",
                    confirmButtonColor: "#28a745"
                });
            });
        </script>';
        unset($_SESSION['success_message']);
    }
    ?>

    <!-- Add Employee Button -->
    <?php if ($isAdmin): ?>
    <button class="add-employee-btn" id="addEmployeeBtn">
        <i class="fa-solid fa-user-plus"></i>
        <span>إضافة عون جديد</span>
    </button>
    <?php endif; ?>

    <!-- Stats Section -->
    <div class="stats-section" id="statsContainer">
        <div class="stat-card" data-type="all">
            <div class="stat-value"><?php echo $stats['total']; ?></div>
            <div class="stat-label">إجمالي الأعوان</div>
        </div>
        <div class="stat-card" data-type="permanent">
            <div class="stat-value"><?php echo $stats['permanent']; ?></div>
            <div class="stat-label">مترسمين</div>
        </div>
        <div class="stat-card" data-type="trainee">
            <div class="stat-value"><?php echo $stats['trainee']; ?></div>
            <div class="stat-label">متربصين</div>
        </div>
        <div class="stat-card" data-type="attached_internal">
            <div class="stat-value"><?php echo $stats['attached_internal']; ?></div>
            <div class="stat-label">ملحقين داخل الشركة</div>
        </div>
        <div class="stat-card" data-type="attached_external">
            <div class="stat-value"><?php echo $stats['attached_external']; ?></div>
            <div class="stat-label">ملحق خارج الشركة</div>
        </div>
        <div class="stat-card" data-type="indisponible">
            <div class="stat-value"><?php echo $stats['unavailable']; ?></div>
            <div class="stat-label">إحالة على عدم المباشرة</div>
        </div>
        <div class="stat-card" data-type="actif">
            <div class="stat-value"><?php echo $stats['active']; ?></div>
            <div class="stat-label">مباشرون</div>
        </div>
        <div class="stat-card" data-type="filtered">
            <div class="stat-value" id="filteredCount"><?php echo $stats['total']; ?></div>
            <div class="stat-label">النتائج المصفاة</div>
        </div>
    </div>

    <!-- Filtered Results Message -->
    <div class="filtered-results" id="filteredResultsMessage">
        <i class="fas fa-filter me-2"></i>
        تم العثور على <span id="resultsCount">0</span> نتيجة من أصل <span id="totalCount">0</span>
    </div>

    <!-- Action Buttons -->
    <div class="controls-section">
        <div class="action-buttons">
            <button id="clearFilters" class="btn-modern btn-clear">
                <i class="fas fa-trash-alt"></i>
                <span>مسح كل الفلاتر</span>
            </button>
            
            <button id="exportExcelBtn" class="btn-modern btn-export">
                <i class="fas fa-file-excel"></i>
                <span>تصدير إلى Excel</span>
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filter-section">
        <input type="text" id="filterMecano" class="filter-input column-filter" data-column="1" placeholder="الرقم الآلي...">
        <input type="text" id="filterNom" class="filter-input column-filter" data-column="2" placeholder="الإسم أو اللقب...">
        <input type="text" id="filterDateNaiss" class="filter-input column-filter" data-column="3" placeholder="تاريخ الولادة...">
        <input type="text" id="filterDateRec" class="filter-input column-filter" data-column="4" placeholder="تاريخ الإنتداب...">
        <input type="text" id="filterTitre" class="filter-input column-filter" data-column="5" placeholder="الرتبة...">
        <input type="text" id="filterEchelle" class="filter-input column-filter" data-column="6" placeholder="السلم...">
        <input type="text" id="filterSilk" class="filter-input column-filter" data-column="7" placeholder="السلك...">
        <input type="text" id="filterCin" class="filter-input column-filter" data-column="8" placeholder="رقم بطاقة وطنية...">
        <input type="text" id="filterAge" class="filter-input column-filter" data-column="9" placeholder="العمر...">
        <input type="text" id="filterAnciennete" class="filter-input column-filter" data-column="10" placeholder="الأقدمية...">
        <input type="text" id="filterNcnss" class="filter-input column-filter" data-column="11" placeholder="ض.إجتماعي...">
        <input type="text" id="filterAssurance" class="filter-input column-filter" data-column="12" placeholder="التأمين...">
        <input type="text" id="filterDepar" class="filter-input column-filter" data-column="13" placeholder="وحدة الإرتباط...">
        <input type="text" id="filterStatus" class="filter-input column-filter" data-column="14" placeholder="الصفة...">
        <input type="text" id="filterSexe" class="filter-input column-filter" data-column="15" placeholder="الجنس...">
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
                    <th style="min-width: 40px;">#</th>
                    <th style="min-width: 90px;">الرقم الآلي</th>
                    <th style="min-width: 180px;">الإسم واللقب</th>
                    <th style="min-width: 110px;">تاريخ الولادة</th>
                    <th style="min-width: 110px;">تاريخ الإنتداب</th>
                    <th style="min-width: 120px;">الرتبة</th>
                    <th style="min-width: 70px;">السلم</th>
                    <th style="min-width: 70px;">السلك</th>
                    <th style="min-width: 100px;">رقم بطاقة وطنية</th>
                    <th style="min-width: 80px;">العمر</th>
                    <th style="min-width: 80px;">الأقدمية</th>
                    <th style="min-width: 100px;">ض.إجتماعي</th>
                    <th style="min-width: 100px;">التأمين</th>
                    <th style="min-width: 150px;">وحدة الإرتباط</th>
                    <th style="min-width: 100px;">الصفة</th>
                    <th style="min-width: 70px;">الجنس</th>
                    <?php if ($isAdmin): ?>
                    <th style="min-width: 120px;">الإجراءات</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php $i = 0; foreach ($employees as $emp): $i++; ?>
                <tr class="fade-in">
                    <td><span class="badge-custom" style="background: linear-gradient(135deg, #3498db, #2980b9); color:white;"><?php echo $i; ?></span></td>
                    <td><span class="employee-id"><?php echo htmlspecialchars($emp['mecano']); ?></span></td>
                    <td><strong><?php echo htmlspecialchars($emp['nom']); ?></strong></td>
                    <td><?php echo htmlspecialchars($emp['daten']); ?></td>
                    <td><?php echo htmlspecialchars($emp['daterec']); ?></td>
                    <td><?php echo htmlspecialchars($emp['titre_libelle']); ?></td>
                    <td><span class="badge-custom <?php echo $emp['echelle_badge']; ?>"><?php echo htmlspecialchars($emp['echelle']); ?></span></td>
                    <td><span class="badge-custom <?php echo $emp['echelle_badge']; ?>"><?php echo htmlspecialchars($emp['silk']); ?></span></td>
                    <td><?php echo htmlspecialchars($emp['cin']); ?></td>
                    <td><span class="badge-custom badge-purple"><?php echo $emp['age']; ?> سنة</span></td>
                    <td><span class="badge-custom" style="background: linear-gradient(135deg, #e67e22, #d35400);"><?php echo $emp['anciennete']; ?> سنة</span></td>
                    <td><span class="badge-custom badge-info"><?php echo htmlspecialchars($emp['ncnss']); ?></span></td>
                    <td><span class="badge-custom badge-info"><?php echo htmlspecialchars($emp['nassurance']); ?></span></td>
                    <td><span class="badge-custom badge-info"><?php echo htmlspecialchars($emp['depar']); ?></span></td>
                    <td><span class="badge-custom <?php echo $emp['status_badge']; ?>"><?php echo htmlspecialchars($emp['status_name']); ?></span></td>
                    <td><?php echo htmlspecialchars($emp['sexe']); ?></td>
                    <?php if ($isAdmin): ?>
                    <td style="white-space: nowrap;">
                        <button class="btn-edit edit-user-btn" data-mecano="<?php echo $emp['mecano']; ?>" title="تعديل">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-attestation attestation-btn-fr" data-mecano="<?php echo $emp['mecano']; ?>" title="شهادة عمل (فرنسية)">
                            FR
                        </button>
                        <button class="btn-attestation attestation-btn-ar" data-mecano="<?php echo $emp['mecano']; ?>" title="شهادة عمل (عربية)">
                            ع
                        </button>
                     </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($employees)): ?>
                <tr>
                    <td colspan="<?php echo $isAdmin ? 17 : 16; ?>" class="text-center py-4">
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
$(document).ready(function() {
    // Modal pour ajout
    $('#addEmployeeBtn').on('click', function(e) {
        e.preventDefault();
        $('#editModalContent').load('index.php?action=employees_add', function() {
            $('#editModal').modal('show');
        });
    });
    
    // Modal pour modification
    $('.edit-user-btn').on('click', function(e) {
        e.preventDefault();
        const mecano = $(this).data('mecano');
        $('#editModalContent').load('index.php?action=employees_edit&mecano=' + encodeURIComponent(mecano), function() {
            $('#editModal').modal('show');
        });
    });
    
    // Attestations
    $('.attestation-btn-fr').on('click', function() {
        let mecano = $(this).data('mecano');
        window.open('attestation.php?mecano=' + mecano, '_blank');
    });
    
    $('.attestation-btn-ar').on('click', function() {
        let mecano = $(this).data('mecano');
        window.open('attestation_ar.php?mecano=' + mecano, '_blank');
    });
});

// Filter table function
function filterTable() {
    var filters = {};
    $('.column-filter').each(function() {
        var val = $(this).val();
        if (val && val.trim() !== '') {
            filters[$(this).data('column')] = val.toLowerCase().trim();
        }
    });
    
    $('#myTable tbody tr').each(function() {
        var showRow = true;
        var cells = $(this).find('td');
        
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
        
        $(this).toggle(showRow);
    });
    
    updateFilteredStats();
}

function updateFilteredStats() {
    var visibleRows = $('#myTable tbody tr:visible');
    var visibleCount = visibleRows.length;
    var totalCount = $('#myTable tbody tr').length;
    
    $('#filteredCount').text(visibleCount);
    $('#resultsCount').text(visibleCount);
    $('#totalCount').text(totalCount);
    
    if (visibleCount === totalCount || visibleCount === 0) {
        $('#filteredResultsMessage').hide();
    } else {
        $('#filteredResultsMessage').show();
    }
}

// Apply filters on input change
$('.column-filter').on('keyup change', filterTable);

// Clear filters
$('#clearFilters').on('click', function() {
    $('.column-filter').val('');
    filterTable();
});

// Stat cards filter
$('.stat-card').on('click', function() {
    var type = $(this).data('type');
    
    // Clear all filters first
    $('.column-filter').val('');
    
    if (type === 'all') {
        $('#myTable tbody tr').show();
    } else if (type === 'permanent') {
        $('#myTable tbody tr').hide();
        $('#myTable tbody tr').each(function() {
            var statusText = $(this).find('td').eq(14).text().trim();
            if (statusText === 'مترسم') {
                $(this).show();
            }
        });
    } else if (type === 'trainee') {
        $('#myTable tbody tr').hide();
        $('#myTable tbody tr').each(function() {
            var statusText = $(this).find('td').eq(14).text().trim();
            if (statusText === 'متربص') {
                $(this).show();
            }
        });
    } else if (type === 'attached_internal') {
        $('#myTable tbody tr').hide();
        $('#myTable tbody tr').each(function() {
            var statusText = $(this).find('td').eq(14).text().trim();
            if (statusText === 'ملحق لدى الشركة') {
                $(this).show();
            }
        });
    } else if (type === 'attached_external') {
        $('#myTable tbody tr').hide();
        $('#myTable tbody tr').each(function() {
            var statusText = $(this).find('td').eq(14).text().trim();
            if (statusText === 'ملحق خارج الشركة') {
                $(this).show();
            }
        });
    } else if (type === 'indisponible') {
        $('#myTable tbody tr').hide();
        $('#myTable tbody tr').each(function() {
            var statusText = $(this).find('td').eq(14).text().trim();
            if (statusText === 'إحالة على عدم المباشرة') {
                $(this).show();
            }
        });
    } else if (type === 'actif') {
        $('#myTable tbody tr').hide();
        $('#myTable tbody tr').each(function() {
            var statusText = $(this).find('td').eq(14).text().trim();
            if (statusText === 'مترسم' || statusText === 'متربص' || statusText === 'ملحق لدى الشركة') {
                $(this).show();
            }
        });
    }
    
    updateFilteredStats();
});

// Export to Excel
$('#exportExcelBtn').on('click', function() {
    var tableData = [];
    
    // Headers
    var headers = [];
    $('#myTable thead th').each(function() {
        headers.push($(this).text().trim());
    });
    tableData.push(headers);
    
    // Data from visible rows
    $('#myTable tbody tr:visible').each(function() {
        var rowData = [];
        $(this).find('td').each(function() {
            rowData.push($(this).text().trim());
        });
        if (rowData.length > 0) {
            tableData.push(rowData);
        }
    });
    
    if (tableData.length <= 1) {
        Swal.fire({
            icon: 'warning',
            title: 'تنبيه',
            text: 'لا توجد بيانات للتصدير',
            confirmButtonText: 'موافق'
        });
        return;
    }
    
    var ws = XLSX.utils.aoa_to_sheet(tableData);
    var wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, 'ListeEmployes');
    
    ws['!cols'] = [
        {wch:5}, {wch:12}, {wch:25}, {wch:12}, {wch:12},
        {wch:15}, {wch:8}, {wch:8}, {wch:15}, {wch:8},
        {wch:8}, {wch:15}, {wch:15}, {wch:20}, {wch:12}, {wch:8}
    ];
    
    XLSX.writeFile(wb, 'liste_employes_' + new Date().toISOString().slice(0,10) + '.xlsx');
    
    Swal.fire({
        icon: 'success',
        title: 'تم التصدير بنجاح',
        text: 'تم تصدير ' + (tableData.length - 1) + ' عون',
        timer: 2000,
        showConfirmButton: false
    });
});

// Animations
document.querySelectorAll('#myTable tbody tr').forEach((row, index) => {
    row.style.animationDelay = (index * 0.03) + 's';
});

// Initial update
updateFilteredStats();
</script>

</body>
</html>