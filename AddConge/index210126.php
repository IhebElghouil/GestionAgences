<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/constants.php';
require_once __DIR__ . '/includes/conges.functions.php';

/* =========================
   Données nécessaires à la vue
========================= */

// exemple : récupérées depuis DB ou session
$mecano   = $_GET['mecano'] ?? '';
$solde   = $_GET['solde'] ?? 0;
$jrepos  = $_GET['jrepos'] ?? 6; // vendredi par défaut

$joursSemaine = [
    0 => 'الأحد',
    1 => 'الإثنين',
    2 => 'الثلاثاء',
    3 => 'الأربعاء',
    4 => 'الخميس',
    5 => 'الجمعة',
    6 => 'السبت'
];
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>Gestion des congés</title>

    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <script>
        /* Variables globales nécessaires au JS */
        window.mecano = "<?= htmlspecialchars($mecano) ?>";
    </script>
</head>
<body>

<div class="container mt-4">

    <h4 class="mb-4 text-center">إضافة عطلة / رخصة</h4>

    <!-- 🔔 ALERT -->
    <div id="success-alert" class="alert alert-danger text-center" style="display:none"></div>

    <!-- 📋 FORM -->
    <form id="formConge">

        <div class="row mb-3">
            <div class="col-md-4">
                <label>من</label>
                <input type="date" id="DateDebut" class="form-control">
            </div>

            <div class="col-md-4">
                <label>إلى</label>
                <input type="date" id="DateFin" class="form-control">
            </div>

            <div class="col-md-4">
                <label>نوع الرخصة</label>
                <select id="TypeRepos" class="form-control">
                    <?php foreach ($TYPE_REPOS as $id => $label): ?>
                        <option value="<?= $id ?>"><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>يوم الراحة الأسبوعية</label>
                <select id="JourDeReposFixe" class="form-control">
                    <?php foreach ($joursSemaine as $i => $label): ?>
                        <option value="<?= $i ?>" <?= ($i == $jrepos ? 'selected' : '') ?>>
                            <?= $label ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-4">
                <label>عدد الأيام</label>
                <input type="text" id="NbJoursConge" class="form-control" readonly value="0">
            </div>

            <div class="col-md-4">
                <label>الرصيد المتبقي</label>
                <input type="text" id="NbJoursResteSolde" class="form-control" readonly value="<?= $solde ?>">
                <input type="hidden" id="Solde" value="<?= $solde ?>">
            </div>
        </div>

        <div class="text-center mt-4" id="buttonStyle" style="display:none">
            <button type="button" id="SaveData" class="btn btn-success" disabled>
                حفظ
            </button>
        </div>

    </form>

</div>

<!-- JS -->
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/conges.js"></script>

</body>
</html>
