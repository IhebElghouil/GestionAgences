<!DOCTYPE html>
<html lang="fr" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>إدارة الأعوان - Gestion Des Agents</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <?php  session_start(); ?>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-bg: #f8f9fa;
            --dark-text: #2c3e50;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: var(--dark-text);
            line-height: 1.6;
        }
        
        .header-section {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem 0;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 1.5rem;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-weight: 600;
            padding: 1rem 1.25rem;
            border-radius: 10px 10px 0 0 !important;
        }
        
        .table-container {
            overflow-x: auto;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 0;
        }
        
        thead th {
            background-color: var(--secondary-color);
            color: white;
            font-weight: 600;
            padding: 1rem;
            border: none;
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        tbody tr {
            transition: background-color 0.2s ease;
        }
        
        tbody tr:nth-child(even) {
            background-color: rgba(0,0,0,0.02);
        }
        
        tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.1);
        }
        
        tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            vertical-align: middle;
        }
        
        .btn {
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn i {
            margin-left: 5px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-success:hover {
            background-color: #219653;
            border-color: #219653;
            transform: translateY(-2px);
        }
        
        .btn-outline-secondary {
            color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-outline-secondary:hover {
            background-color: var(--secondary-color);
            color: white;
            transform: translateY(-2px);
        }
        
        .form-control {
            border-radius: 6px;
            border: 1px solid #ddd;
            padding: 0.6rem 0.75rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .mobile-message {
            display: none;
            background-color: #fff9e6;
            border-left: 4px solid var(--warning-color);
            padding: 10px 15px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        
        .stats-card {
            text-align: center;
            padding: 1.5rem 1rem;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .stats-label {
            font-size: 0.9rem;
            color: var(--dark-text);
            opacity: 0.8;
        }
        
        .alert-success {
            border-radius: 8px;
            border-left: 4px solid var(--success-color);
        }
        
        .filter-section {
            background-color: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        }
        
        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }
        
        .modal-header {
            background-color: var(--secondary-color);
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 1.25rem 1.5rem;
        }
        
        .modal-title {
            font-weight: 600;
        }
        
        .modal-footer {
            border-top: 1px solid rgba(0,0,0,0.05);
            padding: 1rem 1.5rem;
        }
        
        .action-btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
        
        /* Style pour le modal de chargement */
        .loading-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .loading-spinner {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
        }
        
        .rib-status {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .rib-present {
            background-color: #d4edda;
            color: #155724;
        }
        
        .rib-absent {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        @media (max-width: 768px) {
            .mobile-message {
                display: block;
            }
            
            .header-section h1 {
                font-size: 1.5rem;
            }
            
            .stats-number {
                font-size: 1.5rem;
            }
            
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            
            .btn-group .btn {
                width: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="mb-0"><i class="fas fa-users me-2"></i>إدارة الأعوان</h1>
                    <p class="mb-0 mt-2 opacity-75">Gestion Des Agents</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <button id="export_button" class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>تصدير إلى Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Stats Section -->
        <div class="row mb-4">
            <div class="col-md-3 col-6">
                <div class="card stats-card">
                    <div class="stats-number" id="totalAgents">0</div>
                    <div class="stats-label">إجمالي الأعوان</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card stats-card">
                    <div class="stats-number" id="activeAgents">0</div>
                    <div class="stats-label">الأعوان النشطين</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card stats-card">
                    <div class="stats-number" id="withRIB">0</div>
                    <div class="stats-label">لديهم RIB</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="card stats-card">
                    <div class="stats-number" id="withoutRIB">0</div>
                    <div class="stats-label">بدون RIB</div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        <?php 
       
        if (!empty($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <!-- Mobile Message -->
        <div class="mobile-message">
            <i class="fas fa-info-circle me-2"></i>
            لرؤية كافة الأعمدة، يرجى التمرير أفقيًا ← →
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0"><i class="fas fa-filter me-2"></i>فلاتر البحث</h5>
                <button id="clearFilters" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-broom me-1"></i>مسح كل الفلاتر
                </button>
            </div>
            
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <label for="filterMecano" class="form-label">الرقم الآلي</label>
                    <input type="text" id="filterMecano" class="form-control column-filter" data-column="1" placeholder="ابحث بالرقم الآلي...">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="filterSocial" class="form-label">رقم بطاقة التعريف الوطنيّة</label>
                    <input type="text" id="filterSocial" class="form-control column-filter" data-column="2" placeholder="ابحث برقم الضمان...">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="filterNom" class="form-label">الإسم واللقب</label>
                    <input type="text" id="filterNom" class="form-control column-filter" data-column="3" placeholder="ابحث بالإسم أو اللقب...">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="filterObservation" class="form-label">الملاحظات</label>
                    <input type="text" id="filterObservation" class="form-control column-filter" data-column="4" placeholder="ابحث بالملاحظات...">
                </div>
                <div class="col-lg-3 col-md-6">
                    <label for="filterRIB" class="form-label">رقم الحساب البنكي</label>
                    <input type="text" id="filterRIB" class="form-control column-filter" data-column="5" placeholder="ابحث برقم الحساب البنكي...">
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-table me-2"></i>قائمة الأعوان</span>
                <span class="badge bg-primary" id="tableCount">0</span>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table id="myTable" class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">N°</th>
                                <th width="10%">Mecano</th>
                                <th width="15%">CIN</th>
                                <th width="20%">Nom & Prénom</th>
                                <th width="15%">Observations</th>
                                <th width="20%">RIB</th>
                                <?php if (isset($_SESSION['departement']) && $_SESSION['departement'] == "admin"): ?>
                                    <th width="5%" class="text-center">الإجراءات</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require('connection.php');
                            
                            $stmt = mysqli_prepare($connection, "
                                SELECT DISTINCT social.mecano, cin, nomfr, observations, rib
                                FROM stuf
                                LEFT JOIN dep ON stuf.dep = dep.id
                                LEFT JOIN social ON social.mecano = stuf.mecano
                                LEFT JOIN titres ON titres.id = stuf.titre
                                LEFT JOIN cartes ON stuf.mecano = cartes.mecano
                                WHERE contrastage IN (0,1,3)
                                GROUP BY stuf.mecano
                                ORDER BY stuf.mecano ASC
                            ");
                            
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_bind_result($stmt, $mecano, $cin, $nom, $observations, $rib);
                            
                            $i = 0;
                            $totalAgents = 0;
                            $withRIB = 0;
                            
                            while (mysqli_stmt_fetch($stmt)) {
                                $i++;
                                $totalAgents++;
                                if (!empty($rib)) $withRIB++;
                                
                                echo '<tr>';
                                echo '<td class="text-center">' . $i . '</td>';
                                echo '<td>' . htmlspecialchars($mecano) . '</td>';
                                echo '<td>' . htmlspecialchars($cin) . '</td>';
                                echo '<td>' . htmlspecialchars($nom) . '</td>';
                                echo '<td>' . htmlspecialchars($observations) . '</td>';
                                echo '<td>';
                                if (!empty($rib)) {
                                    echo '<span class="rib-status rib-present"><i class="fas fa-check-circle me-1"></i>' . htmlspecialchars($rib) . '</span>';
                                } else {
                                    echo '<span class="rib-status rib-absent"><i class="fas fa-times-circle me-1"></i>غير متوفر</span>';
                                }
                                echo '</td>';
                                
                                if (isset($_SESSION['departement']) && $_SESSION['departement'] == "admin") {
                                    echo '<td class="text-center">';
                                    echo '<a href="#" class="btn btn-sm btn-outline-primary action-btn edit-user-btn" data-mecano="' . htmlspecialchars($mecano) . '" title="تعديل">';
                                    echo '<i class="fas fa-edit"></i>';
                                    echo '</a>';
                                    echo '</td>';
                                }
                                
                                echo '</tr>';
                            }
                            
                            mysqli_stmt_close($stmt);
                            mysqli_close($connection);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal Bootstrap -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">تعديل بيانات العون</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="editModalContent">
                    <!-- Le contenu sera chargé dynamiquement -->
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">جاري تحميل البيانات...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de chargement -->
    <div class="loading-modal" id="loadingModal">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 mb-0">جاري التحميل...</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.3/package/dist/xlsx.full.min.js"></script>

    <script>
        $(document).ready(function() {
            // Update stats
            const totalAgents = <?php echo $totalAgents; ?>;
            const withRIB = <?php echo $withRIB; ?>;
            const withoutRIB = totalAgents - withRIB;
            
            $('#totalAgents').text(totalAgents);
            $('#activeAgents').text(totalAgents);
            $('#withRIB').text(withRIB);
            $('#withoutRIB').text(withoutRIB);
            $('#tableCount').text(totalAgents);
            
            // Export to Excel
            $('#export_button').on('click', function() {
                const elt = document.getElementById("myTable");
                const wb = XLSX.utils.table_to_book(elt, { sheet: "Sheet1", raw: true });
                const ws = wb.Sheets["Sheet1"];

                // Colonne RIB : index 5 (0‑based)
                const colIndexRIB = 5;
                const range = XLSX.utils.decode_range(ws["!ref"]);

                for (let R = range.s.r + 1; R <= range.e.r; ++R) {
                    const cellRef = XLSX.utils.encode_cell({ r: R, c: colIndexRIB });
                    const cell = ws[cellRef];
                    if (cell && cell.v != null) {
                        cell.t = 's';
                        cell.v = String(cell.v);
                        cell.z = '@';
                    }
                }

                XLSX.writeFile(wb, "export_rib.xlsx");
            });

            // Edit user modal avec Bootstrap - CORRECTION ICI
            $(document).on('click', '.edit-user-btn', function(e) {
                e.preventDefault();
                const mecano = $(this).data('mecano');
                console.log('Opening modal for mecano:', mecano); // Debug
                
                // Afficher le modal de chargement
                $('#loadingModal').css('display', 'flex');
                
                // Charger le contenu de la modale
                $.ajax({
                    url: 'Find_EmplyeeRIB.php?mecano=' + encodeURIComponent(mecano),
                    method: 'GET',
                    success: function(response) {
                        $('#editModalContent').html(response);
                        $('#loadingModal').hide();
                        
                        // Afficher le modal Bootstrap
                        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                        editModal.show();
                    },
                    error: function(xhr, status, error) {
                        $('#loadingModal').hide();
                        $('#editModalContent').html(
                            '<div class="alert alert-danger">حدث خطأ في تحميل البيانات: ' + error + '</div>'
                        );
                        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                        editModal.show();
                    }
                });
            });

            // Gestion de la soumission du formulaire dans le modal
            $(document).on('submit', '#updateRIBForm', function(e) {
                e.preventDefault();
                
                // Afficher l'indicateur de chargement
                $('#loadingModal').css('display', 'flex');
                
                $.ajax({
                    type: 'POST',
                    url: 'update_rib.php',
                    data: $(this).serialize(),
                    success: function(response) {
                        // Cacher l'indicateur de chargement
                        $('#loadingModal').hide();
                        
                        try {
                            const result = JSON.parse(response);
                            
                            if (result.success) {
                                // Fermer le modal
                                const editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                                editModal.hide();
                                
                                // Afficher le message de succès
                                showAlert('تم تحديث RIB بنجاح!', 'success');
                                
                                // Recharger la page après un court délai
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            } else {
                                showAlert(result.message || 'حدث خطأ أثناء التحديث', 'error');
                            }
                        } catch (e) {
                            showAlert('استجابة غير صالحة من الخادم', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loadingModal').hide();
                        showAlert('حدث خطأ في الاتصال بالخادم: ' + error, 'error');
                    }
                });
            });

            // Fonction pour afficher les alertes
            function showAlert(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const alertHtml = `
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                
                // Ajouter l'alerte en haut de la page
                $('.container').prepend(alertHtml);
                
                // Supprimer automatiquement après 5 secondes
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }

            // Table filtering
            function filterTable() {
                var filters = {};
                $('.column-filter').each(function() {
                    if ($(this).val()) {
                        filters[$(this).data('column')] = $(this).val().toLowerCase();
                    }
                });
                
                var visibleRows = 0;
                $('#myTable tbody tr').each(function() {
                    var showRow = true;
                    var cells = $(this).find('td');
                    
                    for (var col in filters) {
                        if (filters.hasOwnProperty(col)) {
                            var cellText = cells.eq(col).text().toLowerCase();
                            if (cellText.indexOf(filters[col]) === -1) {
                                showRow = false;
                                break;
                            }
                        }
                    }
                    
                    $(this).toggle(showRow);
                    if (showRow) visibleRows++;
                });
                
                $('#tableCount').text(visibleRows);
            }

            $('.column-filter').on('keyup change', filterTable);
            
            $('#clearFilters').on('click', function() {
                $('.column-filter').val('');
                filterTable();
            });
        });
    </script>
</body>
</html>