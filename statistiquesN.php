<!DOCTYPE html>
<html lang="ar" dir="rtl">
<?php
session_start();
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الإحصائيات - نظام إدارة الأعوان</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
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
        
        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            border-right: 4px solid var(--secondary-color);
        }
        
        .stats-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .stats-title i {
            color: var(--secondary-color);
            margin-left: 10px;
        }
        
        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            height: 400px;
        }
        
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
            overflow-x: auto;
        }
        
        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .custom-table th {
            background-color: var(--primary-color);
            color: white;
            padding: 12px;
            font-weight: 600;
            text-align: center;
        }
        
        .custom-table td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }
        
        .custom-table tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .badge-category {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
            min-width: 40px;
            text-align: center;
        }
        
        .badge-cadre {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            color: white;
        }
        
        .badge-tahsir {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
            color: white;
        }
        
        .badge-tanfidh {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
        }
        
        .badge-secondary {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
            color: white;
        }
        
        .text-muted {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .filter-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: var(--card-shadow);
        }
        
        .btn-modern {
            border-radius: 12px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--hover-shadow);
        }
        
        .btn-primary {
            background: var(--secondary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: #2980b9;
        }
        
        .btn-secondary {
            background: #95a5a6;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #7f8c8d;
        }
        
        .stat-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        
        .summary-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: var(--card-shadow);
            border-top: 4px solid var(--secondary-color);
        }
        
        .summary-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .summary-label {
            font-size: 1rem;
            color: #6c757d;
        }
        
        .float-start {
            float: left;
        }
        
        @media (max-width: 768px) {
            .chart-container {
                height: 300px;
            }
        }
    </style>
</head>

<body>
    <div class="container-main">
        <div class="header-section">
            <h1 class="header-title">الإحصائيات والتحليلات</h1>
            <p class="header-subtitle">إحصائيات مفصلة حول الأعوان حسب مختلف المعايير</p>
        </div>

        <?php
        
        include('menu.php');
        require('connection.php');

        // Vérifier la connexion
        if (!$connection) {
            die("Erreur de connexion: " . mysqli_connect_error());
        }

        // Récupérer tous les employés avec leurs informations
        $query = "
            SELECT 
                stuf.mecano,
                stuf.nom,
                stuf.daten,
                stuf.daterec,
                stuf.titre,
                stuf.dep as departement_id,
                stuf.numPers,
                stuf.contrastage,
                stuf.echelle,
                dep.depar as nom_departement,
                titres.libellet as grade,
                TIMESTAMPDIFF(YEAR, stuf.daten, CURDATE()) as age,
                TIMESTAMPDIFF(YEAR, stuf.daterec, CURDATE()) as anciennete
            FROM stuf
            LEFT JOIN dep ON stuf.dep = dep.id
            LEFT JOIN titres ON stuf.titre = titres.id
            WHERE stuf.contrastage IN (0,1,3)
            ORDER BY stuf.mecano ASC
        ";

        $result = mysqli_query($connection, $query);

        if (!$result) {
            die("Erreur dans la requête: " . mysqli_error($connection));
        }

        // Initialisation des tableaux de statistiques
        $stats = [
            'type' => [
                'A' => ['count' => 0, 'label' => 'إداري', 'employees' => []],
                'E' => ['count' => 0, 'label' => 'إداري إستغلال', 'employees' => []],
                'EC' => ['count' => 0, 'label' => 'سائق حافلة', 'employees' => []],
                'ER' => ['count' => 0, 'label' => 'قابض', 'employees' => []],
                'T' => ['count' => 0, 'label' => 'تقني', 'employees' => []],
                'Autre' => ['count' => 0, 'label' => 'آخر', 'employees' => []]
            ],
            'service' => [],
            'age' => [
                'moins_30' => ['count' => 0, 'label' => 'أقل من 30 سنة', 'min' => 0, 'max' => 29],
                '30_40' => ['count' => 0, 'label' => '30 - 40 سنة', 'min' => 30, 'max' => 40],
                '41_50' => ['count' => 0, 'label' => '41 - 50 سنة', 'min' => 41, 'max' => 50],
                '51_60' => ['count' => 0, 'label' => '51 - 60 سنة', 'min' => 51, 'max' => 60],
                'plus_60' => ['count' => 0, 'label' => 'أكثر من 60 سنة', 'min' => 61, 'max' => 150]
            ],
            'anciennete' => [
                'moins_5' => ['count' => 0, 'label' => 'أقل من 5 سنوات', 'min' => 0, 'max' => 4],
                '5_10' => ['count' => 0, 'label' => '5 - 10 سنوات', 'min' => 5, 'max' => 10],
                '11_15' => ['count' => 0, 'label' => '11 - 15 سنة', 'min' => 11, 'max' => 15],
                '16_20' => ['count' => 0, 'label' => '16 - 20 سنة', 'min' => 16, 'max' => 20],
                'plus_20' => ['count' => 0, 'label' => 'أكثر من 20 سنة', 'min' => 21, 'max' => 100]
            ],
            'grade' => [
                'cadres' => ['count' => 0, 'label' => 'إطارات (echelle ≥ 500)', 'employees' => []],
                'tahsir' => ['count' => 0, 'label' => 'تسيير (300 ≤ echelle < 500)', 'employees' => []],
                'tanfidh' => ['count' => 0, 'label' => 'تنفيذ (echelle < 300)', 'employees' => []],
                'non_defini' => ['count' => 0, 'label' => 'غير محدد', 'employees' => []]
            ],
            'combinaisons' => []
        ];

        // Traitement des données
        $total_employes = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            $total_employes++;
            
            // 1. Statistiques par type (numPers)
            $type = $row['numPers'] ?? 'Autre';
            if (!isset($stats['type'][$type])) {
                $type = 'Autre';
            }
            $stats['type'][$type]['count']++;
            $stats['type'][$type]['employees'][] = $row;

            // 2. Statistiques par service
            $service = $row['nom_departement'] ?? 'غير محدد';
            if (!isset($stats['service'][$service])) {
                $stats['service'][$service] = [
                    'count' => 0,
                    'employees' => [],
                    'by_type' => ['A' => 0, 'E' => 0, 'EC' => 0, 'ER' => 0, 'T' => 0, 'Autre' => 0],
                    'by_grade' => ['cadres' => 0, 'tahsir' => 0, 'tanfidh' => 0, 'non_defini' => 0]
                ];
            }
            $stats['service'][$service]['count']++;
            $stats['service'][$service]['employees'][] = $row;
            
            // Mise à jour des sous-catégories par service
            $type_key = isset($stats['type'][$type]) ? $type : 'Autre';
            $stats['service'][$service]['by_type'][$type_key]++;

            // 3. Statistiques par tranche d'âge
            $age = $row['age'];
            foreach ($stats['age'] as $key => &$age_range) {
                if ($age >= $age_range['min'] && $age <= $age_range['max']) {
                    $age_range['count']++;
                    break;
                }
            }

            // 4. Statistiques par ancienneté
            $anciennete = $row['anciennete'];
            foreach ($stats['anciennete'] as $key => &$anc_range) {
                if ($anciennete >= $anc_range['min'] && $anciennete <= $anc_range['max']) {
                    $anc_range['count']++;
                    break;
                }
            }

            // 5. Statistiques par grade (basé sur l'échelle)
            $echelle = $row['echelle'] ?? 0;
            if ($echelle >= 500) {
                $stats['grade']['cadres']['count']++;
                $stats['grade']['cadres']['employees'][] = $row;
                $grade_categorie = 'cadres';
            } elseif ($echelle >= 300 && $echelle < 500) {
                $stats['grade']['tahsir']['count']++;
                $stats['grade']['tahsir']['employees'][] = $row;
                $grade_categorie = 'tahsir';
            } elseif ($echelle < 300 && $echelle > 0) {
                $stats['grade']['tanfidh']['count']++;
                $stats['grade']['tanfidh']['employees'][] = $row;
                $grade_categorie = 'tanfidh';
            } else {
                $stats['grade']['non_defini']['count']++;
                $stats['grade']['non_defini']['employees'][] = $row;
                $grade_categorie = 'non_defini';
            }

            // Mise à jour des sous-catégories par service pour les grades
            if (isset($stats['service'][$service])) {
                $stats['service'][$service]['by_grade'][$grade_categorie]++;
            }

            // 6. Combinaisons (Type + Grade)
            $comb_key = $type . '_' . $grade_categorie;
            if (!isset($stats['combinaisons'][$comb_key])) {
                $stats['combinaisons'][$comb_key] = [
                    'type' => $type,
                    'type_label' => $stats['type'][$type]['label'],
                    'grade' => $grade_categorie,
                    'grade_label' => $stats['grade'][$grade_categorie]['label'],
                    'count' => 0,
                    'employees' => []
                ];
            }
            $stats['combinaisons'][$comb_key]['count']++;
            $stats['combinaisons'][$comb_key]['employees'][] = $row;
        }

        // Trier les services par nombre d'employés
        uasort($stats['service'], function($a, $b) {
            return $b['count'] - $a['count'];
        });

        ?>

        <!-- Résumé général -->
        <div class="stat-summary">
            <div class="summary-card">
                <div class="summary-value"><?= $total_employes ?></div>
                <div class="summary-label">إجمالي الأعوان</div>
            </div>
            <div class="summary-card">
                <div class="summary-value"><?= count($stats['service']) ?></div>
                <div class="summary-label">عدد المصالح</div>
            </div>
            <div class="summary-card">
                <div class="summary-value"><?= $stats['grade']['cadres']['count'] ?></div>
                <div class="summary-label">إطارات</div>
            </div>
            <div class="summary-card">
                <div class="summary-value"><?= $stats['grade']['tahsir']['count'] ?></div>
                <div class="summary-label">تسيير</div>
            </div>
            <div class="summary-card">
                <div class="summary-value"><?= $stats['grade']['tanfidh']['count'] ?></div>
                <div class="summary-label">تنفيذ</div>
            </div>
        </div>

        <!-- Filtres -->
        <div class="filter-section">
            <div class="row">
                <div class="col-md-12">
                    <h5><i class="fas fa-filter"></i> تصفية البيانات</h5>
                </div>
                <div class="col-md-3">
                    <select id="filterType" class="form-control">
                        <option value="">جميع الأنواع</option>
                        <?php foreach ($stats['type'] as $key => $type): ?>
                            <option value="<?= $key ?>"><?= $type['label'] ?> (<?= $type['count'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filterGrade" class="form-control">
                        <option value="">جميع الفئات</option>
                        <?php foreach ($stats['grade'] as $key => $grade): ?>
                            <option value="<?= $key ?>"><?= $grade['label'] ?> (<?= $grade['count'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filterService" class="form-control">
                        <option value="">جميع المصالح</option>
                        <?php foreach ($stats['service'] as $service => $data): ?>
                            <option value="<?= htmlspecialchars($service) ?>"><?= htmlspecialchars($service) ?> (<?= $data['count'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <button id="applyFilters" class="btn btn-modern btn-primary">
                        <i class="fas fa-check"></i> تطبيق
                    </button>
                    <button id="resetFilters" class="btn btn-modern btn-secondary">
                        <i class="fas fa-undo"></i> إعادة تعيين
                    </button>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="row">
            <div class="col-md-6">
                <div class="chart-container">
                    <canvas id="chartType"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <canvas id="chartGrade"></canvas>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="chart-container">
                    <canvas id="chartAge"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container">
                    <canvas id="chartAnciennete"></canvas>
                </div>
            </div>
        </div>

        <!-- Tableau par service avec détails -->
        <div class="stats-card">
            <h3 class="stats-title">
                <i class="fas fa-building"></i>
                توزيع الأعوان حسب المصالح
            </h3>
            <div class="table-container">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>المصلحة</th>
                            <th>العدد</th>
                            <th>النسبة</th>
                            <th>إداري</th>
                            <th>إداري إستغلال</th>
                            <th>سائق حافلة</th>
                            <th>قابض</th>
                            <th>تقني</th>
                            <th>إطارات</th>
                            <th>تسيير</th>
                            <th>تنفيذ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['service'] as $service => $data): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($service) ?></strong></td>
                                <td><?= $data['count'] ?></td>
                                <td><?= round(($data['count'] / $total_employes) * 100, 2) ?>%</td>
                                <td><?= $data['by_type']['A'] ?></td>
                                <td><?= $data['by_type']['E'] ?></td>
                                <td><?= $data['by_type']['EC'] ?></td>
                                <td><?= $data['by_type']['ER'] ?></td>
                                <td><?= $data['by_type']['T'] ?></td>
                                <td><span class="badge-category badge-cadre"><?= $data['by_grade']['cadres'] ?></span></td>
                                <td><span class="badge-category badge-tahsir"><?= $data['by_grade']['tahsir'] ?></span></td>
                                <td><span class="badge-category badge-tanfidh"><?= $data['by_grade']['tanfidh'] ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tableau des combinaisons (Type + Grade) -->
        <div class="stats-card">
            <h3 class="stats-title">
                <i class="fas fa-chart-pie"></i>
                جميع التركيبات (النوع + الفئة)
            </h3>
            <div class="table-container">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>النوع</th>
                            <th>إطارات</th>
                            <th>تسيير</th>
                            <th>تنفيذ</th>
                            <th>العدد</th>
                            <th>النسبة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // Initialiser un tableau pour regrouper par type
                        $combinaisons_par_type = [];
                        
                        // Parcourir toutes les combinaisons
                        foreach ($stats['combinaisons'] as $comb) {
                            $type = $comb['type'];
                            $type_label = $comb['type_label'];
                            
                            if (!isset($combinaisons_par_type[$type])) {
                                $combinaisons_par_type[$type] = [
                                    'type_label' => $type_label,
                                    'cadres' => 0,
                                    'tahsir' => 0,
                                    'tanfidh' => 0,
                                    'total' => 0
                                ];
                            }
                            
                            // Ajouter le compte selon le grade
                            switch($comb['grade']) {
                                case 'cadres':
                                    $combinaisons_par_type[$type]['cadres'] = $comb['count'];
                                    break;
                                case 'tahsir':
                                    $combinaisons_par_type[$type]['tahsir'] = $comb['count'];
                                    break;
                                case 'tanfidh':
                                    $combinaisons_par_type[$type]['tanfidh'] = $comb['count'];
                                    break;
                            }
                            
                            // Ajouter au total
                            $combinaisons_par_type[$type]['total'] += $comb['count'];
                        }
                        
                        // Trier par type
                        ksort($combinaisons_par_type);
                        
                        // Afficher les lignes du tableau
                        foreach ($combinaisons_par_type as $type => $data): 
                            $total_type = $data['total'];
                        ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($data['type_label']) ?></strong></td>
                                <td>
                                    <?php if ($data['cadres'] > 0): ?>
                                        <span class="badge-category badge-cadre"><?= $data['cadres'] ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($data['tahsir'] > 0): ?>
                                        <span class="badge-category badge-tahsir"><?= $data['tahsir'] ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($data['tanfidh'] > 0): ?>
                                        <span class="badge-category badge-tanfidh"><?= $data['tanfidh'] ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td><strong><?= $total_type ?></strong></td>
                                <td><?= round(($total_type / $total_employes) * 100, 2) ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                        
                        <!-- Ligne du total général -->
                        <tr style="background-color: #f8f9fa; font-weight: bold;">
                            <td><strong>المجموع</strong></td>
                            <td><span class="badge-category badge-cadre"><?= $stats['grade']['cadres']['count'] ?></span></td>
                            <td><span class="badge-category badge-tahsir"><?= $stats['grade']['tahsir']['count'] ?></span></td>
                            <td><span class="badge-category badge-tanfidh"><?= $stats['grade']['tanfidh']['count'] ?></span></td>
                            <td><strong><?= $total_employes ?></strong></td>
                            <td>100%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Liste détaillée des employés -->
        <div class="stats-card">
            <h3 class="stats-title">
                <i class="fas fa-list"></i>
                قائمة الأعوان التفصيلية
                <button id="toggleEmployeeList" class="btn btn-sm btn-primary float-start">
                    <i class="fas fa-eye"></i> عرض القائمة
                </button>
            </h3>
            <div id="employeeList" style="display: none;">
                <div class="table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>الرقم الآلي</th>
                                <th>الإسم</th>
                                <th>المصلحة</th>
                                <th>النوع</th>
                                <th>الدرجة</th>
                                <th>الإطار</th>
                                <th>العمر</th>
                                <th>الأقدمية</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Réexécuter la requête pour la liste détaillée
                            mysqli_data_seek($result, 0);
                            while ($row = mysqli_fetch_assoc($result)):
                                $type_label = $stats['type'][$row['numPers'] ?? 'Autre']['label'];
                                $grade_label = '';
                                $badge_class = '';
                                $echelle = $row['echelle'] ?? 0;
                                
                                if ($echelle >= 500) {
                                    $grade_label = 'إطارات';
                                    $badge_class = 'badge-cadre';
                                } elseif ($echelle >= 300) {
                                    $grade_label = 'تسيير';
                                    $badge_class = 'badge-tahsir';
                                } elseif ($echelle > 0) {
                                    $grade_label = 'تنفيذ';
                                    $badge_class = 'badge-tanfidh';
                                } else {
                                    $grade_label = 'غير محدد';
                                    $badge_class = 'badge-secondary';
                                }
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['mecano']) ?></td>
                                    <td><?= htmlspecialchars($row['nom']) ?></td>
                                    <td><?= htmlspecialchars($row['nom_departement'] ?? 'غير محدد') ?></td>
                                    <td><?= $type_label ?></td>
                                    <td><?= htmlspecialchars($row['grade'] ?? '-') ?></td>
                                    <td><span class="badge-category <?= $badge_class ?>"><?= $grade_label ?></span></td>
                                    <td><?= $row['age'] ?> سنة</td>
                                    <td><?= $row['anciennete'] ?> سنة</td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Données pour les graphiques
        const statsData = {
            type: {
                labels: [<?php foreach ($stats['type'] as $type): ?>'<?= $type['label'] ?>',<?php endforeach; ?>],
                counts: [<?php foreach ($stats['type'] as $type): ?><?= $type['count'] ?>,<?php endforeach; ?>],
                colors: ['#3498db', '#2ecc71', '#f39c12', '#9b59b6', '#e74c3c', '#95a5a6']
            },
            grade: {
                labels: [<?php foreach ($stats['grade'] as $grade): ?>'<?= $grade['label'] ?>',<?php endforeach; ?>],
                counts: [<?php foreach ($stats['grade'] as $grade): ?><?= $grade['count'] ?>,<?php endforeach; ?>],
                colors: ['#27ae60', '#f39c12', '#e74c3c', '#95a5a6']
            },
            age: {
                labels: [<?php foreach ($stats['age'] as $age): ?>'<?= $age['label'] ?>',<?php endforeach; ?>],
                counts: [<?php foreach ($stats['age'] as $age): ?><?= $age['count'] ?>,<?php endforeach; ?>],
                colors: ['#3498db', '#2ecc71', '#f39c12', '#e74c3c', '#9b59b6']
            },
            anciennete: {
                labels: [<?php foreach ($stats['anciennete'] as $anc): ?>'<?= $anc['label'] ?>',<?php endforeach; ?>],
                counts: [<?php foreach ($stats['anciennete'] as $anc): ?><?= $anc['count'] ?>,<?php endforeach; ?>],
                colors: ['#3498db', '#2ecc71', '#f39c12', '#e74c3c', '#9b59b6']
            }
        };

        // Initialisation des graphiques
        document.addEventListener('DOMContentLoaded', function() {
            // Graphique par type
            new Chart(document.getElementById('chartType'), {
                type: 'pie',
                data: {
                    labels: statsData.type.labels,
                    datasets: [{
                        data: statsData.type.counts,
                        backgroundColor: statsData.type.colors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: true, text: 'توزيع الأعوان حسب النوع' }
                    }
                }
            });

            // Graphique par grade
            new Chart(document.getElementById('chartGrade'), {
                type: 'doughnut',
                data: {
                    labels: statsData.grade.labels,
                    datasets: [{
                        data: statsData.grade.counts,
                        backgroundColor: statsData.grade.colors,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' },
                        title: { display: true, text: 'توزيع الأعوان حسب الفئة' }
                    }
                }
            });

            // Graphique par âge
            new Chart(document.getElementById('chartAge'), {
                type: 'bar',
                data: {
                    labels: statsData.age.labels,
                    datasets: [{
                        label: 'عدد الأعوان',
                        data: statsData.age.counts,
                        backgroundColor: statsData.age.colors,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        title: { display: true, text: 'توزيع الأعوان حسب الفئات العمرية' }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { display: false } }
                    }
                }
            });

            // Graphique par ancienneté
            new Chart(document.getElementById('chartAnciennete'), {
                type: 'bar',
                data: {
                    labels: statsData.anciennete.labels,
                    datasets: [{
                        label: 'عدد الأعوان',
                        data: statsData.anciennete.counts,
                        backgroundColor: statsData.anciennete.colors,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        title: { display: true, text: 'توزيع الأعوان حسب الأقدمية' }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { display: false } }
                    }
                }
            });
        });

        // Gestion des filtres
        document.getElementById('applyFilters').addEventListener('click', function() {
            const filterType = document.getElementById('filterType').value;
            const filterGrade = document.getElementById('filterGrade').value;
            const filterService = document.getElementById('filterService').value;
            
            // Filtrer les lignes du tableau des employés
            const rows = document.querySelectorAll('#employeeList tbody tr');
            rows.forEach(row => {
                let show = true;
                
                if (filterType && !row.cells[3].textContent.includes(getTypeLabel(filterType))) {
                    show = false;
                }
                
                if (filterGrade) {
                    const gradeCell = row.cells[5].textContent;
                    if (!gradeCell.includes(getGradeLabel(filterGrade))) {
                        show = false;
                    }
                }
                
                if (filterService && !row.cells[2].textContent.includes(filterService)) {
                    show = false;
                }
                
                row.style.display = show ? '' : 'none';
            });
        });

        document.getElementById('resetFilters').addEventListener('click', function() {
            document.getElementById('filterType').value = '';
            document.getElementById('filterGrade').value = '';
            document.getElementById('filterService').value = '';
            
            const rows = document.querySelectorAll('#employeeList tbody tr');
            rows.forEach(row => row.style.display = '');
        });

        // Toggle liste des employés
        document.getElementById('toggleEmployeeList').addEventListener('click', function() {
            const list = document.getElementById('employeeList');
            const icon = this.querySelector('i');
            
            if (list.style.display === 'none') {
                list.style.display = 'block';
                this.innerHTML = '<i class="fas fa-eye-slash"></i> إخفاء القائمة';
            } else {
                list.style.display = 'none';
                this.innerHTML = '<i class="fas fa-eye"></i> عرض القائمة';
            }
        });

        // Fonctions utilitaires
        function getTypeLabel(typeKey) {
            const types = {
                'A': 'إداري',
                'E': 'إداري إستغلال',
                'EC': 'سائق حافلة',
                'ER': 'قابض',
                'T': 'تقني',
                'Autre': 'آخر'
            };
            return types[typeKey] || typeKey;
        }

        function getGradeLabel(gradeKey) {
            const grades = {
                'cadres': 'إطارات',
                'tahsir': 'تسيير',
                'tanfidh': 'تنفيذ',
                'non_defini': 'غير محدد'
            };
            return grades[gradeKey] || gradeKey;
        }
    </script>

    <?php mysqli_close($connection); ?>
</body>
</html>