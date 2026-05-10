<?php
require 'Dbconnexion.php';

// ==========================
// FONCTION POUR LE LIBELLÉ DE CONTRASTAGE
// ==========================
function getContrastageLibelle($code) {
    switch($code) {
        case 0: return '📝 مترسم (Titulaire)';
        case 1: return '🔰 متربص (Stagiaire)';
        case 3: return '🤝 ملحق لدى الشركة (Détaché entreprise)';
        case 5: return '🌍 ملحق خارج الشركة (Détaché externe)';
        case 6: return '⚕️ إحالة على عدم المباشرة الخاصّة (Congé spécial)';
        default: return 'Autre';
    }
}

function getContrastageClass($code) {
    switch($code) {
        case 0: return 'badge-contrastage-0';
        case 1: return 'badge-contrastage-1';
        case 3: return 'badge-contrastage-3';
        case 5: return 'badge-contrastage-5';
        case 6: return 'badge-contrastage-6';
        default: return 'badge-contrastage-default';
    }
}

// ==========================
// FONCTION POUR LA CLASSE SELON L'ÉCHELLE
// ==========================
function getClasseLibelle($echelle) {
    if ($echelle >= 500) return '&#128084 إطارات (Cadres)';
    if ($echelle >= 300) return '&#128202 تسيير (Maîtrise)';
    if ($echelle < 300) return '&#128295 تنفيذ (Exécution)';
    return 'Non défini';
}

function getClasseClass($echelle) {
    if ($echelle >= 500) return 'badge-cadre';
    if ($echelle >= 300) return 'badge-maitrise';
    if ($echelle < 300) return 'badge-execution';
    return 'badge-default';
}

// ==========================
// FONCTIONS POUR L'ANCIENNETÉ
// ==========================
function getAncienneteLibelle($annees) {
    if ($annees < 5) return '&#127381 Moins de 5 ans';
    if ($annees < 10) return '&#128197 5-10 ans';
    if ($annees < 15) return '&#128197 10-15 ans';
    if ($annees < 20) return '&#128197 15-20 ans';
    if ($annees < 25) return '&#128197 20-25 ans';
    if ($annees < 30) return '&#128197 25-30 ans';
    return '⭐ Plus de 30 ans';
}

function getAncienneteClass($annees) {
    if ($annees < 5) return 'anciennete-debut';
    if ($annees < 10) return 'anciennete-moyenne';
    if ($annees < 15) return 'anciennete-confirmee';
    if ($annees < 20) return 'anciennete-experimentee';
    if ($annees < 25) return 'anciennete-senior';
    if ($annees < 30) return 'anciennete-veteran';
    return 'anciennete-expert';
}

function getAncienneteColor($annees) {
    if ($annees < 5) return '#4cc9f0';
    if ($annees < 10) return '#4361ee';
    if ($annees < 15) return '#7209b7';
    if ($annees < 20) return '#f72585';
    if ($annees < 25) return '#f8961e';
    if ($annees < 30) return '#f9844a';
    return '#ef476f';
}

// ==========================
// NOUVELLES FONCTIONS POUR ABSENCES/FORMATIONS
// ==========================
function getAbsenceTypeLibelle($type, $type2 = null) {
    if ($type == 6) return '🚑 Accident de travail';
    if ($type == 5) return '🤒 Maladie';
    if ($type == 1 && $type2 == 1) return '📚 Formation';
    return 'Autre';
}

function getAbsenceTypeColor($type, $type2 = null) {
    if ($type == 6) return '#dc3545';
    if ($type == 5) return '#ffc107';
    if ($type == 1 && $type2 == 1) return '#28a745';
    return '#6c757d';
}

function getAbsenceTypeClass($type, $type2 = null) {
    if ($type == 6) return 'badge-accident';
    if ($type == 5) return 'badge-maladie';
    if ($type == 1 && $type2 == 1) return 'badge-formation';
    return 'badge-default';
}

// ==========================
// KPI GLOBAL
// ==========================
$sql_kpi = "
SELECT 
    COUNT(*) AS total,
    SUM(CASE WHEN contrastage = 0 THEN 1 ELSE 0 END) AS titulaire,
    SUM(CASE WHEN contrastage = 1 THEN 1 ELSE 0 END) AS stagiaire,
    SUM(CASE WHEN contrastage = 3 THEN 1 ELSE 0 END) AS detache_entreprise,
    SUM(CASE WHEN contrastage = 5 THEN 1 ELSE 0 END) AS detache_externe,
    SUM(CASE WHEN contrastage = 6 THEN 1 ELSE 0 END) AS conge_special
FROM stuf
where contrastage in (0,1,3,5,6)
";
$res_kpi = $conn->query($sql_kpi);
$kpi = $res_kpi->fetch_assoc();

// ==========================
// TABLEAU PAR REGION
// ==========================
$sql_region = "
SELECT d.depar AS region, COUNT(s.mecano) AS total
FROM stuf s
LEFT JOIN dep d ON s.dep = d.id
where contrastage in (0,1,3,5,6)
GROUP BY d.depar
ORDER BY total DESC
";
$res_region = $conn->query($sql_region);

// ==========================
// TABLEAU PAR SERVICE
// ==========================
$sql_service = "
SELECT sv.libellet AS service, COUNT(s.mecano) AS total
FROM stuf s
LEFT JOIN service sv ON s.idservice = sv.id
where contrastage in (0,1,3,5,6)
GROUP BY sv.libellet
ORDER BY total DESC
";
$res_service = $conn->query($sql_service);

// ==========================
// TABLEAU PAR numpers (Type de personnel)
// ==========================
$sql_numpers = "
SELECT 
    s.numpers,
    CASE 
        WHEN s.numpers = 'EC' THEN '&#128652 سواق'
        WHEN s.numpers = 'ER' THEN '&#128176 قباض'
        WHEN s.numpers = 'E' THEN '&#128203 إداري إستغلال'
        WHEN s.numpers = 'T' THEN '&#128295 تقني'
        WHEN s.numpers = 'A' THEN '&#128084 إداري'
        ELSE s.numpers
    END as type_personnel,
    COUNT(*) AS total,
    SUM(CASE WHEN s.contrastage = 0 THEN 1 ELSE 0 END) AS titulaire,
    SUM(CASE WHEN s.contrastage = 1 THEN 1 ELSE 0 END) AS stagiaire,
    SUM(CASE WHEN s.contrastage IN (3,5) THEN 1 ELSE 0 END) AS detache,
    SUM(CASE WHEN s.contrastage = 6 THEN 1 ELSE 0 END) AS conge_special,
    ROUND(AVG(TIMESTAMPDIFF(YEAR, s.daten, CURDATE())), 1) AS age_moyen
FROM stuf s
WHERE s.numpers IS NOT NULL AND s.numpers != '' AND contrastage in (0,1,3,5,6)
GROUP BY s.numpers
ORDER BY total DESC
";
$res_numpers = $conn->query($sql_numpers);

// ==========================
// TABLEAU PAR CONTRASTAGE
// ==========================
$sql_contrastage = "
SELECT 
    contrastage,
    COUNT(*) AS total,
    SUM(CASE WHEN sexe = 'M' THEN 1 ELSE 0 END) AS hommes,
    SUM(CASE WHEN sexe = 'F' THEN 1 ELSE 0 END) AS femmes,
    ROUND(AVG(TIMESTAMPDIFF(YEAR, daten, CURDATE())), 1) AS age_moyen
FROM stuf
WHERE contrastage IS NOT NULL AND contrastage IN (0,1,3,5,6)
GROUP BY contrastage
ORDER BY contrastage
";
$res_contrastage = $conn->query($sql_contrastage);

// ==========================
// TABLEAU PAR CLASSE (selon échelle)
// ==========================
$sql_classe = "
SELECT 
    CASE 
        WHEN echelle >= 500 THEN 'إطارات (Cadres)'
        WHEN echelle >= 300 AND echelle < 500 THEN 'تسيير (Maîtrise)'
        WHEN echelle < 300 THEN 'تنفيذ (Exécution)'
        ELSE 'Non défini'
    END as classe,
    COUNT(*) as total,
    SUM(CASE WHEN sexe = 'M' THEN 1 ELSE 0 END) as hommes,
    SUM(CASE WHEN sexe = 'F' THEN 1 ELSE 0 END) as femmes,
    ROUND(AVG(echelle), 1) as echelle_moyenne,
    ROUND(AVG(TIMESTAMPDIFF(YEAR, daten, CURDATE())), 1) as age_moyen,
    SUM(CASE WHEN contrastage = 0 THEN 1 ELSE 0 END) as titulaire,
    SUM(CASE WHEN contrastage = 1 THEN 1 ELSE 0 END) as stagiaire,
    SUM(CASE WHEN contrastage IN (3,5) THEN 1 ELSE 0 END) as detache,
    SUM(CASE WHEN contrastage = 6 THEN 1 ELSE 0 END) as conge_special
FROM stuf
WHERE echelle IS NOT NULL AND contrastage in (0,1,3,5,6)
GROUP BY classe
ORDER BY 
    CASE classe
        WHEN 'إطارات (Cadres)' THEN 1
        WHEN 'تسيير (Maîtrise)' THEN 2
        WHEN 'تنفيذ (Exécution)' THEN 3
        ELSE 4
    END
";
$res_classe = $conn->query($sql_classe);

// ==========================
// affectation PAR CLASSE
// ==========================
$sql_region_classe = "
SELECT 
    d.depar as region,
    SUM(CASE WHEN s.echelle >= 500 THEN 1 ELSE 0 END) as cadres,
    SUM(CASE WHEN s.echelle >= 300 AND s.echelle < 500 THEN 1 ELSE 0 END) as maitrise,
    SUM(CASE WHEN s.echelle < 300 THEN 1 ELSE 0 END) as execution,
    COUNT(*) as total
FROM stuf s
LEFT JOIN dep d ON s.dep = d.id
WHERE s.echelle IS NOT NULL AND contrastage in (0,1,3,5,6)
GROUP BY d.depar
ORDER BY total DESC
";
$res_region_classe = $conn->query($sql_region_classe);

// ==========================
// numpers PAR CLASSE
// ==========================
$sql_numpers_classe = "
SELECT 
    s.numpers,
    CASE 
        WHEN s.numpers = 'EC' THEN '&#128652 سواق'
        WHEN s.numpers = 'ER' THEN '&#128176 قباض'
        WHEN s.numpers = 'E' THEN '&#128203 إداري إستغلال'
        WHEN s.numpers = 'T' THEN '&#128295 تقني'
        WHEN s.numpers = 'A' THEN '&#128084 إداري'
    END as type,
    SUM(CASE WHEN s.echelle >= 500 THEN 1 ELSE 0 END) as cadres,
    SUM(CASE WHEN s.echelle >= 300 AND s.echelle < 500 THEN 1 ELSE 0 END) as maitrise,
    SUM(CASE WHEN s.echelle < 300 THEN 1 ELSE 0 END) as execution,
    COUNT(*) as total
FROM stuf s
WHERE s.numpers IS NOT NULL AND s.numpers != '' AND s.echelle IS NOT NULL AND contrastage in (0,1,3,5,6)
GROUP BY s.numpers
ORDER BY s.numpers
";
$res_numpers_classe = $conn->query($sql_numpers_classe);

// ==========================
// TABLEAU COMBINE REGION + CONTRASTAGE
// ==========================
$sql_combined = "
SELECT 
    d.depar AS region,
    SUM(CASE WHEN s.contrastage = 0 THEN 1 ELSE 0 END) AS titulaire,
    SUM(CASE WHEN s.contrastage = 1 THEN 1 ELSE 0 END) AS stagiaire,
    SUM(CASE WHEN s.contrastage = 3 THEN 1 ELSE 0 END) AS detache_entreprise,
    SUM(CASE WHEN s.contrastage = 5 THEN 1 ELSE 0 END) AS detache_externe,
    SUM(CASE WHEN s.contrastage = 6 THEN 1 ELSE 0 END) AS conge_special,
    COUNT(s.mecano) AS total
FROM stuf s
LEFT JOIN dep d ON s.dep = d.id
WHERE contrastage in (0,1,3,5,6)
GROUP BY d.depar
ORDER BY d.depar
";
$res_combined = $conn->query($sql_combined);

// ==========================
// GET FILTER OPTIONS
// ==========================
$regions = $conn->query("SELECT id, depar FROM dep ORDER BY depar");
$services = $conn->query("SELECT id, libellet FROM service ORDER BY libellet");
$years = $conn->query("SELECT DISTINCT YEAR(daterec) as annee FROM stuf WHERE daterec IS NOT NULL ORDER BY annee DESC");
$numpers_list = $conn->query("SELECT DISTINCT numpers FROM stuf WHERE numpers IS NOT NULL AND numpers != '' ORDER BY numpers");
$contrastage_list = $conn->query("SELECT DISTINCT contrastage FROM stuf WHERE contrastage IS NOT NULL ORDER BY contrastage");

// ==========================
// ÉVOLUTION DES DÉPARTS (Départs passés)
// ==========================
$sql_departs_passes = "
SELECT 
    YEAR(dateretraite) as annee,
    COUNT(*) as total_departs
FROM depart
WHERE dateretraite IS NOT NULL
GROUP BY YEAR(dateretraite)
ORDER BY annee
";
$res_departs_passes = $conn->query($sql_departs_passes);

// ==========================
// PRÉVISION DES DÉPARTS FUTURS (Âge de 60 ans)
// ==========================
$sql_departs_futurs = "
SELECT 
    YEAR(DATE_ADD(daten, INTERVAL 60 YEAR)) as annee_depart,
    COUNT(*) as total_prevision,
    SUM(CASE WHEN sexe = 'M' THEN 1 ELSE 0 END) as hommes,
    SUM(CASE WHEN sexe = 'F' THEN 1 ELSE 0 END) as femmes
FROM stuf
WHERE daten IS NOT NULL 
    AND contrastage = 0
    AND YEAR(DATE_ADD(daten, INTERVAL 60 YEAR)) >= YEAR(CURDATE())
GROUP BY YEAR(DATE_ADD(daten, INTERVAL 60 YEAR))
ORDER BY annee_depart
";
$res_departs_futurs = $conn->query($sql_departs_futurs);

// ==========================
// COMBINAISON DÉPARTS PASSÉS + FUTURS
// ==========================
$sql_departs_combined = "
SELECT 
    annee,
    SUM(passes) as passes,
    SUM(futurs) as futurs
FROM (
    SELECT 
        YEAR(dateretraite) as annee,
        COUNT(*) as passes,
        0 as futurs
    FROM depart
    WHERE dateretraite IS NOT NULL
    GROUP BY YEAR(dateretraite)
    
    UNION ALL
    
    SELECT 
        YEAR(DATE_ADD(daten, INTERVAL 60 YEAR)) as annee,
        0 as passes,
        COUNT(*) as futurs
    FROM stuf
    WHERE daten IS NOT NULL 
        AND contrastage = 0
        AND YEAR(DATE_ADD(daten, INTERVAL 60 YEAR)) >= YEAR(CURDATE()) - 1
    GROUP BY YEAR(DATE_ADD(daten, INTERVAL 60 YEAR))
) combined
GROUP BY annee
ORDER BY annee
";
$res_departs_combined = $conn->query($sql_departs_combined);

// ==========================
// COMPARAISON RECRUTEMENTS VS DÉPARTS (AVEC PRÉVISIONS) - HISTORIQUE N-5
// ==========================
$annee_courante = date('Y');
$annee_limite = $annee_courante - 5;

$sql_comparatif_complet = "
SELECT 
    annee,
    SUM(recrutements) as recrutements,
    SUM(departs) as departs,
    SUM(recrutements) - SUM(departs) as solde
FROM (
    SELECT 
        YEAR(daterec) as annee,
        COUNT(*) as recrutements,
        0 as departs
    FROM stuf
    WHERE daterec IS NOT NULL
    AND YEAR(daterec) >= $annee_limite
    GROUP BY YEAR(daterec)
    
    UNION ALL
    
    SELECT 
        YEAR(dateretraite) as annee,
        0 as recrutements,
        COUNT(*) as departs
    FROM depart
    WHERE dateretraite IS NOT NULL
    AND YEAR(dateretraite) >= $annee_limite
    GROUP BY YEAR(dateretraite)
    
    UNION ALL
    
    SELECT 
        YEAR(DATE_ADD(daten, INTERVAL 60 YEAR)) as annee,
        0 as recrutements,
        COUNT(*) as departs
    FROM stuf
    WHERE daten IS NOT NULL 
        AND contrastage = 0
        AND YEAR(DATE_ADD(daten, INTERVAL 60 YEAR)) >= $annee_courante
        AND YEAR(DATE_ADD(daten, INTERVAL 60 YEAR)) <= $annee_courante + 5
    GROUP BY YEAR(DATE_ADD(daten, INTERVAL 60 YEAR))
) combined
GROUP BY annee
ORDER BY annee
";
$res_comparatif_complet = $conn->query($sql_comparatif_complet);

// ==========================
// PYRAMIDE DES ÂGES
// ==========================
$sql_age_pyramid = "
SELECT 
    CASE 
        WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 20 AND 29 THEN '20-29 ans'
        WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 30 AND 39 THEN '30-39 ans'
        WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 40 AND 49 THEN '40-49 ans'
        WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 50 AND 59 THEN '50-59 ans'
        ELSE '60+ ans'
    END as tranche_age,
    SUM(CASE WHEN sexe = 'M' THEN 1 ELSE 0 END) as hommes,
    SUM(CASE WHEN sexe = 'F' THEN 1 ELSE 0 END) as femmes,
    COUNT(*) as total
FROM stuf
WHERE daten IS NOT NULL AND contrastage in (0,1,3,5,6)
GROUP BY tranche_age
ORDER BY FIELD(tranche_age, '20-29 ans', '30-39 ans', '40-49 ans', '50-59 ans', '60+ ans')
";
$res_age_pyramid = $conn->query($sql_age_pyramid);

// ==========================
// ÉVOLUTION RECRUTEMENT
// ==========================
$sql_recruitment = "
SELECT 
    YEAR(daterec) as annee,
    COUNT(*) as total
FROM stuf
WHERE daterec IS NOT NULL
GROUP BY YEAR(daterec)
ORDER BY annee
";
$res_recruitment = $conn->query($sql_recruitment);

// ==========================
// ANALYSE PAR TRANCHE D'ÂGE ET numpers
// ==========================
$sql_age_numpers = "
SELECT 
    numpers,
    CASE 
        WHEN numpers = 'EC' THEN '&#128652 سواق'
        WHEN numpers = 'ER' THEN '&#128176 قباض'
        WHEN numpers = 'E' THEN '&#128203 إداري إستغلال'
        WHEN numpers = 'T' THEN '&#128295 تقني'
        WHEN numpers = 'A' THEN '&#128084 إداري'
    END as type,
    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) < 30 THEN 1 ELSE 0 END) as moins_30,
    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 30 AND 39 THEN 1 ELSE 0 END) as trente_39,
    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 40 AND 49 THEN 1 ELSE 0 END) as quarante_49,
    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) BETWEEN 50 AND 59 THEN 1 ELSE 0 END) as cinquante_59,
    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, daten, CURDATE()) >= 60 THEN 1 ELSE 0 END) as soixante_plus,
    COUNT(*) as total
FROM stuf
WHERE numpers IS NOT NULL AND numpers != '' AND  contrastage in (0,1,3,5,6)
GROUP BY numpers
ORDER BY numpers
";
$res_age_numpers = $conn->query($sql_age_numpers);

// ==========================
// ANALYSE CONTRASTAGE PAR numpers
// ==========================
$sql_contrastage_numpers = "
SELECT 
    numpers,
    CASE 
        WHEN numpers = 'EC' THEN '&#128652 سواق'
        WHEN numpers = 'ER' THEN '&#128176 قباض'
        WHEN numpers = 'E' THEN '&#128203 إداري إستغلال'
        WHEN numpers = 'T' THEN '&#128295 تقني'
        WHEN numpers = 'A' THEN '&#128084 إداري'
    END as type,
    SUM(CASE WHEN contrastage = 0 THEN 1 ELSE 0 END) as titulaire,
    SUM(CASE WHEN contrastage = 1 THEN 1 ELSE 0 END) as stagiaire,
    SUM(CASE WHEN contrastage = 3 THEN 1 ELSE 0 END) as detache_entreprise,
    SUM(CASE WHEN contrastage = 5 THEN 1 ELSE 0 END) as detache_externe,
    SUM(CASE WHEN contrastage = 6 THEN 1 ELSE 0 END) as conge_special,
    COUNT(*) as total
FROM stuf
WHERE numpers IS NOT NULL AND numpers != '' AND  contrastage in (0,1,3,5,6)
GROUP BY numpers
ORDER BY numpers
";
$res_contrastage_numpers = $conn->query($sql_contrastage_numpers);

// ==========================
// affectation PAR TYPE DE PERSONNEL
// ==========================
$sql_region_type = "
SELECT 
    d.depar as region,
    SUM(CASE WHEN s.numpers = 'EC' THEN 1 ELSE 0 END) as EC,
    SUM(CASE WHEN s.numpers = 'ER' THEN 1 ELSE 0 END) as ER,
    SUM(CASE WHEN s.numpers = 'E' THEN 1 ELSE 0 END) as E,
    SUM(CASE WHEN s.numpers = 'T' THEN 1 ELSE 0 END) as T,
    SUM(CASE WHEN s.numpers = 'A' THEN 1 ELSE 0 END) as A,
    COUNT(*) as total
FROM stuf s
LEFT JOIN dep d ON s.dep = d.id
WHERE s.numpers IS NOT NULL AND s.numpers != '' AND contrastage in (0,1,3,5,6)
GROUP BY d.depar
ORDER BY total DESC
";
$res_region_type = $conn->query($sql_region_type);

// ==========================
// affectation PAR SITUATION
// ==========================
$sql_region_situation = "
SELECT 
    d.depar as region,
    SUM(CASE WHEN s.contrastage = 0 THEN 1 ELSE 0 END) as titulaire,
    SUM(CASE WHEN s.contrastage = 1 THEN 1 ELSE 0 END) as stagiaire,
    SUM(CASE WHEN s.contrastage = 3 THEN 1 ELSE 0 END) as detache_entreprise,
    SUM(CASE WHEN s.contrastage = 5 THEN 1 ELSE 0 END) as detache_externe,
    SUM(CASE WHEN s.contrastage = 6 THEN 1 ELSE 0 END) as conge_special,
    COUNT(*) as total
FROM stuf s
LEFT JOIN dep d ON s.dep = d.id
WHERE contrastage in (0,1,3,5,6)
GROUP BY d.depar
ORDER BY total DESC
";
$res_region_situation = $conn->query($sql_region_situation);

// ==========================
// ANALYSE GLOBALE DE L'ANCIENNETÉ
// ==========================
$sql_anciennete_globale = "
SELECT 
    CASE 
        WHEN TIMESTAMPDIFF(YEAR, daterec, CURDATE()) < 5 THEN '&#127381 Moins de 5 ans'
        WHEN TIMESTAMPDIFF(YEAR, daterec, CURDATE()) BETWEEN 5 AND 9 THEN '&#128197 5-10 ans'
        WHEN TIMESTAMPDIFF(YEAR, daterec, CURDATE()) BETWEEN 10 AND 14 THEN '&#128197 10-15 ans'
        WHEN TIMESTAMPDIFF(YEAR, daterec, CURDATE()) BETWEEN 15 AND 19 THEN '&#128197 15-20 ans'
        WHEN TIMESTAMPDIFF(YEAR, daterec, CURDATE()) BETWEEN 20 AND 24 THEN '&#128197 20-25 ans'
        WHEN TIMESTAMPDIFF(YEAR, daterec, CURDATE()) BETWEEN 25 AND 29 THEN '&#128197 25-30 ans'
        ELSE '⭐ Plus de 30 ans'
    END as tranche_anciennete,
    COUNT(*) as total,
    SUM(CASE WHEN sexe = 'M' THEN 1 ELSE 0 END) as hommes,
    SUM(CASE WHEN sexe = 'F' THEN 1 ELSE 0 END) as femmes,
    ROUND(AVG(TIMESTAMPDIFF(YEAR, daterec, CURDATE())), 1) as anciennete_moyenne,
    ROUND(AVG(echelle), 1) as echelle_moyenne,
    SUM(CASE WHEN contrastage = 0 THEN 1 ELSE 0 END) as titulaires,
    SUM(CASE WHEN contrastage = 1 THEN 1 ELSE 0 END) as stagiaires
FROM stuf
WHERE daterec IS NOT NULL AND contrastage in (0,1,3,5,6)
GROUP BY tranche_anciennete
ORDER BY 
    CASE 
        WHEN tranche_anciennete LIKE 'Moins%' THEN 1
        WHEN tranche_anciennete LIKE '5-10%' THEN 2
        WHEN tranche_anciennete LIKE '10-15%' THEN 3
        WHEN tranche_anciennete LIKE '15-20%' THEN 4
        WHEN tranche_anciennete LIKE '20-25%' THEN 5
        WHEN tranche_anciennete LIKE '25-30%' THEN 6
        ELSE 7
    END
";
$res_anciennete_globale = $conn->query($sql_anciennete_globale);

// ==========================
// ANCIENNETÉ PAR affectation
// ==========================
$sql_anciennete_region = "
SELECT 
    d.depar as region,
    ROUND(AVG(TIMESTAMPDIFF(YEAR, s.daterec, CURDATE())), 1) as anciennete_moyenne,
    MIN(TIMESTAMPDIFF(YEAR, s.daterec, CURDATE())) as anciennete_min,
    MAX(TIMESTAMPDIFF(YEAR, s.daterec, CURDATE())) as anciennete_max,
    COUNT(*) as total
FROM stuf s
LEFT JOIN dep d ON s.dep = d.id
WHERE s.daterec IS NOT NULL AND contrastage in (0,1,3,5,6)
GROUP BY d.depar
ORDER BY anciennete_moyenne DESC
";
$res_anciennete_region = $conn->query($sql_anciennete_region);

// ==========================
// ANCIENNETÉ PAR TYPE DE PERSONNEL
// ==========================
$sql_anciennete_numpers = "
SELECT 
    s.numpers,
    CASE 
        WHEN s.numpers = 'EC' THEN '&#128652 سواق'
        WHEN s.numpers = 'ER' THEN '&#128176 قباض'
        WHEN s.numpers = 'E' THEN '&#128203 إداري إستغلال'
        WHEN s.numpers = 'T' THEN '&#128295 تقني'
        WHEN s.numpers = 'A' THEN '&#128084 إداري'
        ELSE s.numpers
    END as type,
    ROUND(AVG(TIMESTAMPDIFF(YEAR, s.daterec, CURDATE())), 1) as anciennete_moyenne,
    MIN(TIMESTAMPDIFF(YEAR, s.daterec, CURDATE())) as anciennete_min,
    MAX(TIMESTAMPDIFF(YEAR, s.daterec, CURDATE())) as anciennete_max,
    COUNT(*) as total,
    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, s.daterec, CURDATE()) >= 20 THEN 1 ELSE 0 END) as senior_20_plus,
    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, s.daterec, CURDATE()) < 5 THEN 1 ELSE 0 END) as junior_moins_5
FROM stuf s
WHERE s.daterec IS NOT NULL AND s.numpers IS NOT NULL AND s.numpers != '' AND contrastage in (0,1,3,5,6)
GROUP BY s.numpers
ORDER BY anciennete_moyenne DESC
";
$res_anciennete_numpers = $conn->query($sql_anciennete_numpers);

// ==========================
// ANCIENNETÉ PAR CLASSE (ÉCHELLE)
// ==========================
$sql_anciennete_classe = "
SELECT 
    CASE 
        WHEN s.echelle >= 500 THEN '&#128084 إطارات (Cadres)'
        WHEN s.echelle >= 300 AND s.echelle < 500 THEN '&#128202 تسيير (Maîtrise)'
        WHEN s.echelle < 300 THEN '&#128295 تنفيذ (Exécution)'
        ELSE 'Non défini'
    END as classe,
    ROUND(AVG(TIMESTAMPDIFF(YEAR, s.daterec, CURDATE())), 1) as anciennete_moyenne,
    MIN(TIMESTAMPDIFF(YEAR, s.daterec, CURDATE())) as anciennete_min,
    MAX(TIMESTAMPDIFF(YEAR, s.daterec, CURDATE())) as anciennete_max,
    COUNT(*) as total,
    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, s.daterec, CURDATE()) >= 20 THEN 1 ELSE 0 END) as anciens,
    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, s.daterec, CURDATE()) < 5 THEN 1 ELSE 0 END) as recents
FROM stuf s
WHERE s.daterec IS NOT NULL AND s.echelle IS NOT NULL AND contrastage in (0,1,3,5,6)
GROUP BY classe
";
$res_anciennete_classe = $conn->query($sql_anciennete_classe);

// ==========================
// TOP 10 DES PLUS ANCIENS EMPLOYÉS
// ==========================
$sql_top_anciens = "
SELECT 
    s.mecano,
    s.nom,
    d.depar as region,
    sv.libellet as service,
    s.numpers,
    s.echelle,
    s.daterec,
    TIMESTAMPDIFF(YEAR, s.daterec, CURDATE()) as anciennete_ans,
    TIMESTAMPDIFF(MONTH, s.daterec, CURDATE()) as anciennete_mois
FROM stuf s
LEFT JOIN dep d ON s.dep = d.id
LEFT JOIN service sv ON s.idservice = sv.id
WHERE s.daterec IS NOT NULL AND contrastage in (0,1,3,5,6)
ORDER BY s.daterec ASC
LIMIT 10
";
$res_top_anciens = $conn->query($sql_top_anciens);

// ==========================
// STATISTIQUES GLOBALES ANCIENNETÉ
// ==========================
$sql_stats_anciennete = "
SELECT 
    ROUND(AVG(TIMESTAMPDIFF(YEAR, daterec, CURDATE())), 1) as anciennete_moyenne,
    MIN(TIMESTAMPDIFF(YEAR, daterec, CURDATE())) as anciennete_min,
    MAX(TIMESTAMPDIFF(YEAR, daterec, CURDATE())) as anciennete_max,
    COUNT(*) as total_avec_anciennete,
    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, daterec, CURDATE()) >= 20 THEN 1 ELSE 0 END) as total_20_plus,
    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, daterec, CURDATE()) >= 25 THEN 1 ELSE 0 END) as total_25_plus,
    SUM(CASE WHEN TIMESTAMPDIFF(YEAR, daterec, CURDATE()) >= 30 THEN 1 ELSE 0 END) as total_30_plus
FROM stuf
WHERE daterec IS NOT NULL
";
$res_stats_anciennete = $conn->query($sql_stats_anciennete);
$stats_anciennete = $res_stats_anciennete->fetch_assoc();

// ==========================
// ÉVOLUTION DE L'ANCIENNETÉ MOYENNE PAR ANNÉE
// ==========================
$sql_evolution_anciennete = "
SELECT 
    YEAR(daterec) as annee_recrutement,
    COUNT(*) as recrutes,
    ROUND(AVG(TIMESTAMPDIFF(YEAR, daterec, CURDATE())), 1) as anciennete_moyenne_actuelle
FROM stuf
WHERE daterec IS NOT NULL
GROUP BY YEAR(daterec)
ORDER BY annee_recrutement
";
$res_evolution_anciennete = $conn->query($sql_evolution_anciennete);

// ==========================
// NOUVELLES REQUÊTES POUR ABSENCES ET FORMATIONS
// ==========================

// Statistiques globales des absences/formations
$sql_absences_kpi = "
SELECT 
    COUNT(DISTINCT mecano) as personnes_concernees,
    COUNT(*) as total_absences,
    SUM(nbj) as total_jours,
    AVG(nbj) as duree_moyenne,
    SUM(CASE WHEN type = 6 THEN 1 ELSE 0 END) as accidents,
    SUM(CASE WHEN type = 5 THEN 1 ELSE 0 END) as maladies,
    SUM(CASE WHEN type = 1 AND type2 = 1 THEN 1 ELSE 0 END) as formations,
    SUM(CASE WHEN type = 6 THEN nbj ELSE 0 END) as jours_accidents,
    SUM(CASE WHEN type = 5 THEN nbj ELSE 0 END) as jours_maladies,
    SUM(CASE WHEN type = 1 AND type2 = 1 THEN nbj ELSE 0 END) as jours_formations
FROM autreconge
WHERE valide = 1
AND anne >= YEAR(CURDATE()) - 2
";
$res_absences_kpi = $conn->query($sql_absences_kpi);
$absences_kpi = $res_absences_kpi->fetch_assoc();

// Évolution mensuelle des absences
$sql_absences_mensuelles = "
SELECT 
    YEAR(datedebut) as annee,
    MONTH(datedebut) as mois,
    DATE_FORMAT(datedebut, '%Y-%m') as mois_annee,
    SUM(CASE WHEN type = 6 THEN 1 ELSE 0 END) as accidents,
    SUM(CASE WHEN type = 5 THEN 1 ELSE 0 END) as maladies,
    SUM(CASE WHEN type = 1 AND type2 = 1 THEN 1 ELSE 0 END) as formations,
    COUNT(*) as total,
    SUM(nbj) as total_jours
FROM autreconge
WHERE valide = 1
AND datedebut >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH)
GROUP BY YEAR(datedebut), MONTH(datedebut)
ORDER BY annee DESC, mois DESC
";
$res_absences_mensuelles = $conn->query($sql_absences_mensuelles);

// Top 10 des absents
$sql_top_absents = "
SELECT 
    a.mecano,
    s.nom,
    d.depar as region,
    sv.libellet as service,
    s.numpers,
    COUNT(*) as nb_absences,
    SUM(a.nbj) as total_jours,
    AVG(a.nbj) as duree_moyenne,
    SUM(CASE WHEN a.type = 6 THEN a.nbj ELSE 0 END) as jours_accidents,
    SUM(CASE WHEN a.type = 5 THEN a.nbj ELSE 0 END) as jours_maladies
FROM autreconge a
LEFT JOIN stuf s ON a.mecano = s.mecano
LEFT JOIN dep d ON s.dep = d.id
LEFT JOIN service sv ON s.idservice = sv.id
WHERE a.valide = 1
AND a.anne >= YEAR(CURDATE()) - 1
GROUP BY a.mecano
ORDER BY total_jours DESC
LIMIT 10
";
$res_top_absents = $conn->query($sql_top_absents);

// Absences par affectation
$sql_absences_region = "
SELECT 
    d.depar as region,
    COUNT(DISTINCT a.mecano) as personnes,
    COUNT(*) as nb_absences,
    SUM(a.nbj) as total_jours,
    AVG(a.nbj) as duree_moyenne,
    SUM(CASE WHEN a.type = 6 THEN 1 ELSE 0 END) as accidents,
    SUM(CASE WHEN a.type = 5 THEN 1 ELSE 0 END) as maladies,
    SUM(CASE WHEN a.type = 1 AND a.type2 = 1 THEN 1 ELSE 0 END) as formations,
    ROUND(SUM(a.nbj) / COUNT(DISTINCT s.mecano), 1) as taux_absence
FROM autreconge a
LEFT JOIN stuf s ON a.mecano = s.mecano
LEFT JOIN dep d ON s.dep = d.id
WHERE a.valide = 1
AND a.anne >= YEAR(CURDATE()) - 1
GROUP BY d.depar
ORDER BY total_jours DESC
";
$res_absences_region = $conn->query($sql_absences_region);

// Absences par type de personnel
$sql_absences_numpers = "
SELECT 
    s.numpers,
    CASE 
        WHEN s.numpers = 'EC' THEN '&#128652 سواق'
        WHEN s.numpers = 'ER' THEN '&#128176 قباض'
        WHEN s.numpers = 'E' THEN '&#128203 إداري إستغلال'
        WHEN s.numpers = 'T' THEN '&#128295 تقني'
        WHEN s.numpers = 'A' THEN '&#128084 إداري'
    END as type_personnel,
    COUNT(DISTINCT a.mecano) as personnes,
    COUNT(*) as nb_absences,
    SUM(a.nbj) as total_jours,
    ROUND(AVG(a.nbj), 1) as duree_moyenne,
    SUM(CASE WHEN a.type = 6 THEN a.nbj ELSE 0 END) as jours_accidents,
    SUM(CASE WHEN a.type = 5 THEN a.nbj ELSE 0 END) as jours_maladies
FROM autreconge a
LEFT JOIN stuf s ON a.mecano = s.mecano
WHERE a.valide = 1
AND a.anne >= YEAR(CURDATE()) - 1
AND s.numpers IS NOT NULL
GROUP BY s.numpers
ORDER BY total_jours DESC
";
$res_absences_numpers = $conn->query($sql_absences_numpers);

// Formations par mois
$sql_formations = "
SELECT 
    YEAR(datedebut) as annee,
    MONTH(datedebut) as mois,
    DATE_FORMAT(datedebut, '%Y-%m') as mois_annee,
    COUNT(DISTINCT a.mecano) as participants,
    COUNT(*) as nb_formations,
    SUM(a.nbj) as total_jours_formation,
    AVG(a.nbj) as duree_moyenne,
    GROUP_CONCAT(DISTINCT s.numpers) as types_personnel
FROM autreconge a
LEFT JOIN stuf s ON a.mecano = s.mecano
WHERE a.valide = 1
AND a.type = 1 AND a.type2 = 1
AND a.datedebut >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH)
GROUP BY YEAR(datedebut), MONTH(datedebut)
ORDER BY annee DESC, mois DESC
";
$res_formations = $conn->query($sql_formations);

// Répartition des absences par tranche de durée
$sql_absences_duree = "
SELECT 
    CASE 
        WHEN nbj = 1 THEN '1 jour'
        WHEN nbj BETWEEN 2 AND 3 THEN '2-3 jours'
        WHEN nbj BETWEEN 4 AND 7 THEN '4-7 jours'
        WHEN nbj BETWEEN 8 AND 15 THEN '8-15 jours'
        WHEN nbj BETWEEN 16 AND 30 THEN '16-30 jours'
        ELSE '30+ jours'
    END as tranche_duree,
    COUNT(*) as nb_absences,
    SUM(CASE WHEN type = 6 THEN 1 ELSE 0 END) as accidents,
    SUM(CASE WHEN type = 5 THEN 1 ELSE 0 END) as maladies,
    SUM(CASE WHEN type = 1 AND type2 = 1 THEN 1 ELSE 0 END) as formations
FROM autreconge
WHERE valide = 1
AND anne >= YEAR(CURDATE()) - 1
GROUP BY tranche_duree
ORDER BY 
    CASE tranche_duree
        WHEN '1 jour' THEN 1
        WHEN '2-3 jours' THEN 2
        WHEN '4-7 jours' THEN 3
        WHEN '8-15 jours' THEN 4
        WHEN '16-30 jours' THEN 5
        ELSE 6
    END
";
$res_absences_duree = $conn->query($sql_absences_duree);

// Taux d'absentéisme par mois
$sql_taux_absenteisme = "
SELECT 
    DATE_FORMAT(a.datedebut, '%Y-%m') as mois,
    SUM(a.nbj) as jours_absences,
    (SELECT COUNT(*) FROM stuf) * 20 as jours_theoriques,
    ROUND((SUM(a.nbj) * 100.0) / ((SELECT COUNT(*) FROM stuf) * 20), 2) as taux_absenteisme
FROM autreconge a
WHERE a.valide = 1
AND a.type IN (5,6)
AND a.datedebut >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
GROUP BY DATE_FORMAT(a.datedebut, '%Y-%m')
ORDER BY mois DESC
";
$res_taux_absenteisme = $conn->query($sql_taux_absenteisme);

// Formations par type de personnel
$sql_formations_par_type = "
SELECT 
    s.numpers,
    CASE 
        WHEN s.numpers = 'EC' THEN '&#128652 سواق'
        WHEN s.numpers = 'ER' THEN '&#128176 قباض'
        WHEN s.numpers = 'E' THEN '&#128203 إداري إستغلال'
        WHEN s.numpers = 'T' THEN '&#128295 تقني'
        WHEN s.numpers = 'A' THEN '&#128084 إداري'
    END as type_personnel,
    COUNT(DISTINCT a.mecano) as personnes_formees,
    COUNT(*) as nb_formations,
    SUM(a.nbj) as jours_formation,
    ROUND(COUNT(DISTINCT a.mecano) * 100.0 / COUNT(DISTINCT s2.mecano), 1) as taux_participation
FROM autreconge a
LEFT JOIN stuf s ON a.mecano = s.mecano
LEFT JOIN stuf s2 ON s2.numpers = s.numpers
WHERE a.valide = 1
AND a.type = 1 AND a.type2 = 1
AND a.anne >= YEAR(CURDATE()) - 1
AND s.numpers IS NOT NULL
GROUP BY s.numpers
ORDER BY jours_formation DESC
";
$res_formations_par_type = $conn->query($sql_formations_par_type);

// Tableau croisé affectation vs type d'absence
$sql_pivot_absence = "
SELECT 
    d.depar as region,
    SUM(CASE WHEN a.type = 6 THEN 1 ELSE 0 END) as accidents,
    SUM(CASE WHEN a.type = 5 THEN 1 ELSE 0 END) as maladies,
    SUM(CASE WHEN a.type = 1 AND a.type2 = 1 THEN 1 ELSE 0 END) as formations,
    COUNT(*) as total_absences,
    SUM(a.nbj) as total_jours
FROM autreconge a
LEFT JOIN stuf s ON a.mecano = s.mecano
LEFT JOIN dep d ON s.dep = d.id
WHERE a.valide = 1
AND a.anne >= YEAR(CURDATE()) - 2
GROUP BY d.depar
ORDER BY total_absences DESC
";
$res_pivot_absence = $conn->query($sql_pivot_absence);

// Préparer les données pour JavaScript
$region_data = [];
$res_region->data_seek(0);
while($row = $res_region->fetch_assoc()) {
    $region_data[] = $row;
}

$age_data = [];
$res_age_pyramid->data_seek(0);
while($row = $res_age_pyramid->fetch_assoc()) {
    $age_data[] = $row;
}

$recruitment_data = [];
$res_recruitment->data_seek(0);
while($row = $res_recruitment->fetch_assoc()) {
    $recruitment_data[] = $row;
}

$departs_passes_data = [];
$res_departs_passes->data_seek(0);
while($row = $res_departs_passes->fetch_assoc()) {
    $departs_passes_data[] = $row;
}

$departs_futurs_data = [];
$res_departs_futurs->data_seek(0);
while($row = $res_departs_futurs->fetch_assoc()) {
    $departs_futurs_data[] = $row;
}

$departs_combined_data = [];
$res_departs_combined->data_seek(0);
while($row = $res_departs_combined->fetch_assoc()) {
    $departs_combined_data[] = $row;
}

$comparatif_data = [];
$res_comparatif_complet->data_seek(0);
while($row = $res_comparatif_complet->fetch_assoc()) {
    $comparatif_data[] = $row;
}

$contrastage_data = [];
$res_contrastage->data_seek(0);
while($row = $res_contrastage->fetch_assoc()) {
    $contrastage_data[] = [
        'code' => $row['contrastage'],
        'libelle' => getContrastageLibelle($row['contrastage']),
        'total' => $row['total'],
        'hommes' => $row['hommes'],
        'femmes' => $row['femmes'],
        'age_moyen' => $row['age_moyen']
    ];
}

$classe_data = [];
$res_classe->data_seek(0);
while($row = $res_classe->fetch_assoc()) {
    $classe_data[] = $row;
}

$region_classe_data = [];
$res_region_classe->data_seek(0);
while($row = $res_region_classe->fetch_assoc()) {
    $region_classe_data[] = $row;
}

$numpers_classe_data = [];
$res_numpers_classe->data_seek(0);
while($row = $res_numpers_classe->fetch_assoc()) {
    $numpers_classe_data[] = $row;
}

$region_type_data = [];
$res_region_type->data_seek(0);
while($row = $res_region_type->fetch_assoc()) {
    $region_type_data[] = $row;
}

$region_situation_data = [];
$res_region_situation->data_seek(0);
while($row = $res_region_situation->fetch_assoc()) {
    $region_situation_data[] = $row;
}

$anciennete_globale_data = [];
$res_anciennete_globale->data_seek(0);
while($row = $res_anciennete_globale->fetch_assoc()) {
    $anciennete_globale_data[] = $row;
}

$anciennete_region_data = [];
$res_anciennete_region->data_seek(0);
while($row = $res_anciennete_region->fetch_assoc()) {
    $anciennete_region_data[] = $row;
}

$anciennete_numpers_data = [];
$res_anciennete_numpers->data_seek(0);
while($row = $res_anciennete_numpers->fetch_assoc()) {
    $anciennete_numpers_data[] = $row;
}

$anciennete_classe_data = [];
$res_anciennete_classe->data_seek(0);
while($row = $res_anciennete_classe->fetch_assoc()) {
    $anciennete_classe_data[] = $row;
}

$evolution_anciennete_data = [];
$res_evolution_anciennete->data_seek(0);
while($row = $res_evolution_anciennete->fetch_assoc()) {
    $evolution_anciennete_data[] = $row;
}

// Nouvelles données pour absences
$absences_mensuelles_data = [];
$res_absences_mensuelles->data_seek(0);
while($row = $res_absences_mensuelles->fetch_assoc()) {
    $absences_mensuelles_data[] = $row;
}

$formations_data = [];
$res_formations->data_seek(0);
while($row = $res_formations->fetch_assoc()) {
    $formations_data[] = $row;
}

$absences_region_data = [];
$res_absences_region->data_seek(0);
while($row = $res_absences_region->fetch_assoc()) {
    $absences_region_data[] = $row;
}

$absences_duree_data = [];
$res_absences_duree->data_seek(0);
while($row = $res_absences_duree->fetch_assoc()) {
    $absences_duree_data[] = $row;
}

$taux_absenteisme_data = [];
$res_taux_absenteisme->data_seek(0);
while($row = $res_taux_absenteisme->fetch_assoc()) {
    $taux_absenteisme_data[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Dashboard RH Interactif - Complet</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<!-- Ajoutez ces lignes après les autres scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<!-- Dans la section head, après Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
// Enregistrer le plugin globalement
Chart.register(ChartDataLabels);

// Configuration par défaut pour tous les graphiques
Chart.defaults.set('plugins.datalabels', {
    color: '#000000',
    anchor: 'end',
    align: 'top',
    offset: 4,
    font: {
        weight: 'bold',
        size: 11
    },
    display: 'auto', // Affiche automatiquement si l'espace le permet
    formatter: function(value) {
        return value;
    }
});
</script>
<style>
:root {
    --primary-color: #4361ee;
    --success-color: #28a745;
    --warning-color: #ffc107;
    --info-color: #17a2b8;
    --secondary-color: #6c757d;
    --danger-color: #dc3545;
    --cadre-color: #9c27b0;
    --maitrise-color: #ff9800;
    --execution-color: #2196f3;
    --dark-color: #212529;
    --accident-color: #dc3545;
    --maladie-color: #ffc107;
    --formation-color: #28a745;
}

body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.kpi-card {
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
    border: none;
    border-radius: 15px;
    overflow: hidden;
}

.kpi-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.kpi-card .card-body {
    padding: 1.5rem;
}

.kpi-card h2 {
    font-size: 2.5rem;
    font-weight: bold;
    animation: countUp 0.5s ease-out;
}

@keyframes countUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.filter-section {
    background: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
}

.chart-container {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 25px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    height: 400px;
    position: relative;
}

.chart-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #495057;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.btn-export {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    color: white;
    transition: transform 0.2s;
}

.btn-export:hover {
    transform: scale(1.05);
    color: white;
}

.select2-container--default .select2-selection--multiple {
    border-radius: 10px;
    border: 1px solid #ced4da;
    min-height: 40px;
}

.table-wrapper {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    margin-bottom: 25px;
    overflow-x: auto;
}

.table thead th {
    background: linear-gradient(135deg, #343a40, #212529);
    color: white;
    font-weight: 500;
    border: none;
}

.table tfoot {
    background: #e9ecef;
    font-weight: bold;
}

/* Badges pour contrastage */
.badge-contrastage-0 { background-color: #28a745; color: white; padding: 5px 10px; border-radius: 20px; display: inline-block; }
.badge-contrastage-1 { background-color: #ffc107; color: #212529; padding: 5px 10px; border-radius: 20px; display: inline-block; }
.badge-contrastage-3 { background-color: #17a2b8; color: white; padding: 5px 10px; border-radius: 20px; display: inline-block; }
.badge-contrastage-5 { background-color: #6c757d; color: white; padding: 5px 10px; border-radius: 20px; display: inline-block; }
.badge-contrastage-6 { background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 20px; display: inline-block; }

/* Badges pour classes */
.badge-cadre { background-color: #9c27b0; color: white; padding: 5px 10px; border-radius: 20px; display: inline-block; }
.badge-maitrise { background-color: #ff9800; color: white; padding: 5px 10px; border-radius: 20px; display: inline-block; }
.badge-execution { background-color: #2196f3; color: white; padding: 5px 10px; border-radius: 20px; display: inline-block; }

/* Nouveaux badges pour absences */
.badge-accident { background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 20px; display: inline-block; }
.badge-maladie { background-color: #ffc107; color: #212529; padding: 5px 10px; border-radius: 20px; display: inline-block; }
.badge-formation { background-color: #28a745; color: white; padding: 5px 10px; border-radius: 20px; display: inline-block; }

.badge-numpers {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 20px;
    font-weight: 600;
}

.badge-ec { background-color: #4cc9f0; color: white; }
.badge-er { background-color: #f72585; color: white; }
.badge-e { background-color: #7209b7; color: white; }
.badge-t { background-color: #f8961e; color: white; }
.badge-a { background-color: #43aa8b; color: white; }

.badge-filter {
    background: #e9ecef;
    padding: 8px 15px;
    border-radius: 20px;
    margin-right: 10px;
    margin-bottom: 10px;
    display: inline-block;
    font-size: 0.9rem;
}

.filter-active {
    background: var(--primary-color);
    color: white;
}

.loading {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255,255,255,0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    border-radius: 15px;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
}

.stat-card small {
    opacity: 0.9;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: bold;
}

.nav-tabs {
    margin-bottom: 20px;
    border-bottom: 2px solid #dee2e6;
}

.nav-tabs .nav-link {
    border: none;
    color: #495057;
    font-weight: 500;
    padding: 10px 20px;
}

.nav-tabs .nav-link.active {
    color: var(--primary-color);
    border-bottom: 3px solid var(--primary-color);
    background: transparent;
}

.legend-color {
    width: 20px;
    height: 20px;
    display: inline-block;
    border-radius: 4px;
    margin-right: 5px;
}

.positive-solde {
    color: #28a745;
    font-weight: bold;
}

.negative-solde {
    color: #dc3545;
    font-weight: bold;
}

/* Styles pour l'ancienneté */
.anciennete-debut { background: linear-gradient(135deg, #4cc9f0, #3a8fe0); color: white; }
.anciennete-moyenne { background: linear-gradient(135deg, #4361ee, #3a0ca3); color: white; }
.anciennete-confirmee { background: linear-gradient(135deg, #7209b7, #560bad); color: white; }
.anciennete-experimentee { background: linear-gradient(135deg, #f72585, #b5179e); color: white; }
.anciennete-senior { background: linear-gradient(135deg, #f8961e, #f3722c); color: white; }
.anciennete-veteran { background: linear-gradient(135deg, #f9844a, #f94144); color: white; }
.anciennete-expert { background: linear-gradient(135deg, #ef476f, #d9042b); color: white; }

.badge-anciennete {
    padding: 5px 12px;
    border-radius: 20px;
    font-weight: 600;
    display: inline-block;
    font-size: 0.85rem;
}

.anciennete-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.3s;
}

.anciennete-card:hover {
    transform: translateY(-5px);
}

.anciennete-card .value {
    font-size: 2.5rem;
    font-weight: bold;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.progress-anciennete {
    height: 10px;
    border-radius: 5px;
    background: #e9ecef;
    margin: 10px 0;
}

.progress-anciennete-bar {
    height: 100%;
    border-radius: 5px;
    background: linear-gradient(90deg, #4cc9f0, #4361ee);
    transition: width 1s ease;
}

.star-rating {
    color: #ffd700;
    font-size: 1.2rem;
}

.timeline-item {
    padding: 10px;
    border-left: 3px solid #4361ee;
    margin-bottom: 10px;
    background: #f8f9fa;
    border-radius: 0 10px 10px 0;
}

/* Styles pour les cartes d'absence */
.absence-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    transition: transform 0.3s;
    text-align: center;
    border-left: 5px solid;
}

.absence-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.absence-card .value {
    font-size: 2rem;
    font-weight: bold;
}

.absence-card .label {
    color: #6c757d;
    font-size: 0.9rem;
}
/* Ajoutez ceci dans la section <style> */
@media print {
    body {
        background-color: white;
        padding: 0;
        margin: 0;
    }
    
    .filter-section,
    .btn-export,
    .btn-success,
    .select2-container,
    .loading,
    .nav-tabs,
    .btn,
    .kpi-card {
        break-inside: avoid;
    }
    
    .kpi-card {
        box-shadow: none;
        border: 1px solid #ddd !important;
    }
    
    .chart-container {
        break-inside: avoid;
        page-break-inside: avoid;
        height: 300px !important;
        box-shadow: none;
        border: 1px solid #eee;
    }
    
    .table-wrapper {
        break-inside: avoid;
        page-break-inside: avoid;
        box-shadow: none;
    }
    
    .table thead th {
        background: #333 !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .badge-cadre,
    .badge-maitrise,
    .badge-execution,
    .badge-contrastage-0,
    .badge-contrastage-1,
    .badge-contrastage-3,
    .badge-contrastage-5,
    .badge-contrastage-6 {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .kpi-card {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    
    .no-print {
        display: none !important;
    }
    
    .print-only {
        display: block !important;
    }
    
    @page {
        size: landscape;
        margin: 1cm;
    }
}
</style>
</head>

<body class="bg-light">
<div class="container-fluid mt-4 px-4">

<!-- Header with Export Button -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">&#128202 Dashboard RH - Situation Administrative</h2>
    <div>
        <button class="btn btn-success btn-lg me-2" onclick="printAllStats()">
            🖨️ Imprimer TOUT le rapport
        </button>
        <button class="btn btn-primary btn-lg me-2" onclick="exportToExcel()">
            📥 Export Excel
        </button>
        <button class="btn btn-export btn-lg" onclick="exportToZIP()">
            📦 Export ZIP (Excel + Graphiques)
        </button>
    </div>
</div>

<!-- ================= FILTER SECTION ================= -->
<div class="filter-section">
    <h5 class="mb-3">🔍 Filtres dynamiques</h5>
    <div class="row">
        <div class="col-md-3 mb-3">
            <label class="form-label">affectation</label>
            <select class="form-select select2-multiple" id="regionFilter" multiple="multiple">
                <?php 
                $regions->data_seek(0);
                while($region = $regions->fetch_assoc()): 
                ?>
                <option value="<?= $region['id'] ?>"><?= htmlspecialchars($region['depar']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Service</label>
            <select class="form-select select2-multiple" id="serviceFilter" multiple="multiple">
                <?php 
                $services->data_seek(0);
                while($service = $services->fetch_assoc()): 
                ?>
                <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['libellet']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Année recrutement</label>
            <select class="form-select select2-multiple" id="yearFilter" multiple="multiple">
                <?php 
                $years->data_seek(0);
                while($year = $years->fetch_assoc()): 
                ?>
                <option value="<?= $year['annee'] ?>"><?= $year['annee'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="col-md-3 mb-3">
            <label class="form-label">Situation</label>
            <select class="form-select select2-multiple" id="contrastageFilter" multiple="multiple">
                <?php 
                $contrastage_list->data_seek(0);
                while($code = $contrastage_list->fetch_assoc()): 
                ?>
                <option value="<?= $code['contrastage'] ?>"><?= getContrastageLibelle($code['contrastage']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Type personnel</label>
            <select class="form-select select2-multiple" id="numpersFilter" multiple="multiple">
                <?php 
                $numpers_list->data_seek(0);
                while($numpers = $numpers_list->fetch_assoc()): 
                $label = '';
                switch($numpers['numpers']) {
                    case 'EC': $label = '&#128652 سواق'; break;
                    case 'ER': $label = '&#128176 قباض'; break;
                    case 'E': $label = '&#128203 إداري إستغلال'; break;
                    case 'T': $label = '&#128295 تقني'; break;
                    case 'A': $label = '&#128084 إداري'; break;
                    default: $label = $numpers['numpers'];
                }
                ?>
                <option value="<?= $numpers['numpers'] ?>"><?= $label ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-12">
            <button class="btn btn-primary me-2" onclick="applyFilters()">
                🔄 Appliquer les filtres
            </button>
            <button class="btn btn-outline-secondary" onclick="resetFilters()">
                ✖️ Réinitialiser
            </button>
            <div id="activeFilters" class="mt-3"></div>
        </div>
    </div>
</div>

<!-- ================= KPI CARDS ================= -->
<div class="row text-center mb-4" id="kpiContainer">
    <div class="col-md-2">
        <div class="card kpi-card" style="background: #4361ee;">
            <div class="card-body text-white">
                <h6>Total</h6>
                <h2 id="totalKPI"><?= $kpi['total'] ?></h2>
                <small>مجموع الأعوان</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card kpi-card" style="background: #28a745;">
            <div class="card-body text-white">
                <h6>📝 Titulaires</h6>
                <h2 id="titulaireKPI"><?= $kpi['titulaire'] ?></h2>
                <small><?= round(($kpi['titulaire']/$kpi['total'])*100, 1) ?>%</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card kpi-card" style="background: #ffc107; color: #212529;">
            <div class="card-body">
                <h6>🔰 Stagiaires</h6>
                <h2 id="stagiaireKPI"><?= $kpi['stagiaire'] ?></h2>
                <small><?= round(($kpi['stagiaire']/$kpi['total'])*100, 1) ?>%</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card kpi-card" style="background: #17a2b8;">
            <div class="card-body text-white">
                <h6>🤝 Détachés Int.</h6>
                <h2 id="detacheKPI"><?= $kpi['detache_entreprise']?></h2>
                <small>إلحاق لدى الشركة</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card kpi-card" style="background: #6c757d;">
            <div class="card-body text-white">
                <h6>🌍 Détachés ext.</h6>
                <h2><?= $kpi['detache_externe'] ?></h2>
                <small>إلحاق خارج الشركة</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card kpi-card" style="background: #dc3545;">
            <div class="card-body text-white">
                <h6>⚕️ Congé spécial</h6>
                <h2><?= $kpi['conge_special'] ?></h2>
                <small>إحالة على عدم المباشرة</small>
            </div>
        </div>
    </div>
</div>

<!-- ================= CHARTS ROW 1 ================= -->
<div class="row">
    <div class="col-md-6">
        <div class="chart-container">
            <div class="chart-title">
                <span>&#128202 Répartition par affectation</span>
                <span class="badge bg-info"><?= count($region_data) ?> affectations</span>
            </div>
            <canvas id="barChart"></canvas>
            <div class="loading" style="display: none;" id="barChartLoading">
                <div class="spinner"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="chart-container">
            <div class="chart-title">
                <span>🥧 Répartition par Situation</span>
                <div>
                    <span class="legend-color" style="background: #28a745;"></span> Titulaires
                    <span class="legend-color" style="background: #ffc107;"></span> Stagiaires
                    <span class="legend-color" style="background: #17a2b8;"></span> Détachés
                </div>
            </div>
            <canvas id="pieChart"></canvas>
            <div class="loading" style="display: none;" id="pieChartLoading">
                <div class="spinner"></div>
            </div>
        </div>
    </div>
</div>

<!-- ================= CHARTS ROW 2 ================= -->
<div class="row">
    <div class="col-md-6">
        <div class="chart-container">
            <div class="chart-title">
                <span>📈 Évolution des recrutements</span>
                <span class="badge bg-warning">Total: <?= array_sum(array_column($recruitment_data, 'total')) ?></span>
            </div>
            <canvas id="lineChart"></canvas>
            <div class="loading" style="display: none;" id="lineChartLoading">
                <div class="spinner"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="chart-container">
            <div class="chart-title">
                <span>📉 Évolution des départs (Passés et prévisions)</span>
                <div>
                    <span class="badge bg-danger">Passés: <?= array_sum(array_column($departs_passes_data, 'total_departs')) ?></span>
                    <span class="badge bg-warning">Prévus: <?= array_sum(array_column($departs_futurs_data, 'total_prevision')) ?></span>
                </div>
            </div>
            <canvas id="departsChart"></canvas>
            <div class="loading" style="display: none;" id="departsChartLoading">
                <div class="spinner"></div>
            </div>
        </div>
    </div>
</div>

<!-- ================= CHARTS ROW 3 ================= -->
<div class="row">
    <div class="col-md-6">
        <div class="chart-container">
            <div class="chart-title">
                <span>&#128202 Pyramide des âges</span>
                <span class="badge bg-info">Âge moyen: 
                <?php
                $avg_age = $conn->query("SELECT ROUND(AVG(TIMESTAMPDIFF(YEAR, daten, CURDATE())), 1) as age FROM stuf WHERE daten IS NOT NULL")->fetch_assoc()['age'];
                echo $avg_age;
                ?> ans
                </span>
            </div>
            <canvas id="pyramidChart"></canvas>
            <div class="loading" style="display: none;" id="pyramidChartLoading">
                <div class="spinner"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="chart-container">
            <div class="chart-title">
                <span>&#128202 Comparaison Recrutements vs Départs (N-5 à N+5)</span>
                <div>
                    <span class="badge bg-success">Historique: <?= $annee_limite ?>-<?= $annee_courante-1 ?></span>
                    <span class="badge bg-warning">Prévisions: <?= $annee_courante ?>-<?= $annee_courante+5 ?></span>
                </div>
            </div>
            <canvas id="comparatifChart"></canvas>
            <div class="loading" style="display: none;" id="comparatifChartLoading">
                <div class="spinner"></div>
            </div>
        </div>
    </div>
</div>

<!-- ================= TABLEAU COMPARATIF ================= -->
<div class="table-wrapper mt-3">
    <h4>&#128202 Évolution annuelle - Solde recrutements/départs (N-5 à N+5)</h4>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Année</th>
                <th>Recrutements</th>
                <th>Départs</th>
                <th>Solde</th>
                <th>Évolution</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $res_comparatif_complet->data_seek(0);
            $cumul = 0;
            while($row = $res_comparatif_complet->fetch_assoc()): 
                $cumul += $row['solde'];
                $classe_solde = $row['solde'] >= 0 ? 'positive-solde' : 'negative-solde';
                $statut = ($row['annee'] > $annee_courante) ? 'Prévision' : (($row['annee'] == $annee_courante) ? 'Année en cours' : 'Historique');
                $badge_statut = ($row['annee'] > $annee_courante) ? 'bg-warning' : (($row['annee'] == $annee_courante) ? 'bg-info' : 'bg-secondary');
            ?>
            <tr>
                <td><strong><?= $row['annee'] ?></strong></td>
                <td class="text-success"><?= $row['recrutements'] ?></td>
                <td class="text-danger"><?= $row['departs'] ?></td>
                <td class="<?= $classe_solde ?>"><?= $row['solde'] ?></td>
                <td>
                    <?php if($row['solde'] > 0): ?>
                        <span class="badge bg-success">+<?= $row['solde'] ?> personnes</span>
                    <?php elseif($row['solde'] < 0): ?>
                        <span class="badge bg-danger"><?= $row['solde'] ?> personnes</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">Équilibre</span>
                    <?php endif; ?>
                </td>
                <td><span class="badge <?= $badge_statut ?>"><?= $statut ?></span></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot class="table-primary">
            <tr>
                <th>Cumul (N-5 à N+5)</th>
                <th><?= array_sum(array_column($comparatif_data, 'recrutements')) ?></th>
                <th><?= array_sum(array_column($comparatif_data, 'departs')) ?></th>
                <th class="<?= $cumul >= 0 ? 'positive-solde' : 'negative-solde' ?>"><?= $cumul ?></th>
                <th colspan="2">
                    <?php if($cumul > 0): ?>
                        <span class="badge bg-success">Croissance projetée +<?= $cumul ?></span>
                    <?php elseif($cumul < 0): ?>
                        <span class="badge bg-danger">Décroissance projetée <?= $cumul ?></span>
                    <?php endif; ?>
                </th>
            </tr>
        </tfoot>
    </table>
</div>

<!-- ================= TABLEAUX PAR CLASSE (ÉCHELLE) ================= -->
<div class="row mt-4">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="classeTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="classe-repartition-tab" data-bs-toggle="tab" data-bs-target="#classe-repartition" type="button" role="tab">&#128202 Répartition par classe</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="region-classe-tab" data-bs-toggle="tab" data-bs-target="#region-classe" type="button" role="tab">📍 affectation par classe</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="numpers-classe-tab" data-bs-toggle="tab" data-bs-target="#numpers-classe" type="button" role="tab">👥 Type par classe</button>
            </li>
        </ul>
        
        <div class="tab-content mt-3">
            <!-- Tab 1: Répartition par classe -->
            <div class="tab-pane fade show active" id="classe-repartition" role="tabpanel">
                <div class="table-wrapper">
                    <h4>&#128202 Répartition par classe (selon échelle)</h4>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Classe</th>
                                <th>Total</th>
                                <th>Hommes</th>
                                <th>Femmes</th>
                                <th>Échelle moy.</th>
                                <th>Âge moyen</th>
                                <th>Titulaires</th>
                                <th>Stagiaires</th>
                                <th>Détachés</th>
                                <th>Congé spé.</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_classe = 0;
                            foreach($classe_data as $row): 
                            $total_classe += $row['total'];
                            $badge_class = '';
                            if ($row['classe'] == 'إطارات (Cadres)') $badge_class = 'badge-cadre';
                            elseif ($row['classe'] == 'تسيير (Maîtrise)') $badge_class = 'badge-maitrise';
                            elseif ($row['classe'] == 'تنفيذ (Exécution)') $badge_class = 'badge-execution';
                            ?>
                            <tr>
                                <td><span class="<?= $badge_class ?>"><?= $row['classe'] ?></span></td>
                                <td><strong><?= $row['total'] ?></strong></td>
                                <td><?= $row['hommes'] ?></td>
                                <td><?= $row['femmes'] ?></td>
                                <td><?= $row['echelle_moyenne'] ?></td>
                                <td><?= $row['age_moyen'] ?> ans</td>
                                <td class="text-success"><?= $row['titulaire'] ?></td>
                                <td class="text-warning"><?= $row['stagiaire'] ?></td>
                                <td class="text-info"><?= $row['detache'] ?></td>
                                <td class="text-danger"><?= $row['conge_special'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                                <th>Total Général</th>
                                <th><?= $total_classe ?></th>
                                <th><?= array_sum(array_column($classe_data, 'hommes')) ?></th>
                                <th><?= array_sum(array_column($classe_data, 'femmes')) ?></th>
                                <th>-</th>
                                <th>-</th>
                                <th><?= array_sum(array_column($classe_data, 'titulaire')) ?></th>
                                <th><?= array_sum(array_column($classe_data, 'stagiaire')) ?></th>
                                <th><?= array_sum(array_column($classe_data, 'detache')) ?></th>
                                <th><?= array_sum(array_column($classe_data, 'conge_special')) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <!-- Graphique en camembert pour les classes -->
                    <div class="chart-container mt-3" style="height: 350px;">
                        <div class="chart-title">
                            <span>🥧 Distribution des classes</span>
                        </div>
                        <canvas id="classePieChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Tab 2: affectation par classe -->
            <div class="tab-pane fade" id="region-classe" role="tabpanel">
                <div class="table-wrapper">
                    <h4>📍 Répartition par affectation et Classe</h4>
                    <?php
                    $res_region_classe->data_seek(0);
                    $totaux_region_classe = ['cadres'=>0, 'maitrise'=>0, 'execution'=>0, 'total'=>0];
                    ?>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>affectation</th>
                                <th>&#128084 Cadres</th>
                                <th>&#128202 Maîtrise</th>
                                <th>'&#128295 Exécution</th>
                                <th>Total</th>
                                <th>Classe dominante</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $res_region_classe->fetch_assoc()): 
                                $totaux_region_classe['cadres'] += $row['cadres'];
                                $totaux_region_classe['maitrise'] += $row['maitrise'];
                                $totaux_region_classe['execution'] += $row['execution'];
                                $totaux_region_classe['total'] += $row['total'];
                                
                                $classes = [
                                    'Cadres' => $row['cadres'],
                                    'Maîtrise' => $row['maitrise'],
                                    'Exécution' => $row['execution']
                                ];
                                $dominant = array_search(max($classes), $classes);
                                $pourcentage_dominant = $row['total'] > 0 ? round((max($classes) / $row['total']) * 100, 1) : 0;
                                
                                $badge_dominant = '';
                                switch($dominant) {
                                    case 'Cadres': $badge_dominant = 'badge-cadre'; break;
                                    case 'Maîtrise': $badge_dominant = 'badge-maitrise'; break;
                                    case 'Exécution': $badge_dominant = 'badge-execution'; break;
                                }
                            ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($row['region'] ?? 'Sans affectation') ?></strong></td>
                                <td class="text-purple"><?= $row['cadres'] ?></td>
                                <td class="text-orange"><?= $row['maitrise'] ?></td>
                                <td class="text-blue"><?= $row['execution'] ?></td>
                                <td><strong><?= $row['total'] ?></strong></td>
                                <td>
                                    <?php if($row['total'] > 0): ?>
                                    <span class="<?= $badge_dominant ?>">
                                        <?= $dominant ?> (<?= $pourcentage_dominant ?>%)
                                    </span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                                <th>Total Général</th>
                                <th><?= $totaux_region_classe['cadres'] ?></th>
                                <th><?= $totaux_region_classe['maitrise'] ?></th>
                                <th><?= $totaux_region_classe['execution'] ?></th>
                                <th><?= $totaux_region_classe['total'] ?></th>
                                <th>-</th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <!-- Graphique en barres empilées pour affectation par classe -->
                    <div class="chart-container mt-3" style="height: 400px;">
                        <div class="chart-title">
                            <span>&#128202 Distribution des classes par affectation</span>
                        </div>
                        <canvas id="regionClasseChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Tab 3: Type par classe -->
            <div class="tab-pane fade" id="numpers-classe" role="tabpanel">
                <div class="table-wrapper">
                    <h4>👥 Répartition par Type de personnel et Classe</h4>
                    <?php
                    $res_numpers_classe->data_seek(0);
                    $totaux_numpers_classe = ['cadres'=>0, 'maitrise'=>0, 'execution'=>0, 'total'=>0];
                    ?>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Type</th>
                                <th>&#128084 Cadres</th>
                                <th>&#128202 Maîtrise</th>
                                <th>'&#128295 Exécution</th>
                                <th>Total</th>
                                <th>Classe dominante</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $res_numpers_classe->fetch_assoc()): 
                                $totaux_numpers_classe['cadres'] += $row['cadres'];
                                $totaux_numpers_classe['maitrise'] += $row['maitrise'];
                                $totaux_numpers_classe['execution'] += $row['execution'];
                                $totaux_numpers_classe['total'] += $row['total'];
                                
                                $classes = [
                                    'Cadres' => $row['cadres'],
                                    'Maîtrise' => $row['maitrise'],
                                    'Exécution' => $row['execution']
                                ];
                                $dominant = array_search(max($classes), $classes);
                                $pourcentage_dominant = $row['total'] > 0 ? round((max($classes) / $row['total']) * 100, 1) : 0;
                                
                                $badge_dominant = '';
                                switch($dominant) {
                                    case 'Cadres': $badge_dominant = 'badge-cadre'; break;
                                    case 'Maîtrise': $badge_dominant = 'badge-maitrise'; break;
                                    case 'Exécution': $badge_dominant = 'badge-execution'; break;
                                }
                                
                                $badge_type = '';
                                switch($row['numpers']) {
                                    case 'EC': $badge_type = 'badge-ec'; break;
                                    case 'ER': $badge_type = 'badge-er'; break;
                                    case 'E': $badge_type = 'badge-e'; break;
                                    case 'T': $badge_type = 'badge-t'; break;
                                    case 'A': $badge_type = 'badge-a'; break;
                                }
                            ?>
                            <tr>
                                <td><span class="badge-numpers <?= $badge_type ?>"><?= $row['type'] ?></span></td>
                                <td class="text-purple"><?= $row['cadres'] ?></td>
                                <td class="text-orange"><?= $row['maitrise'] ?></td>
                                <td class="text-blue"><?= $row['execution'] ?></td>
                                <td><strong><?= $row['total'] ?></strong></td>
                                <td>
                                    <?php if($row['total'] > 0): ?>
                                    <span class="<?= $badge_dominant ?>">
                                        <?= $dominant ?> (<?= $pourcentage_dominant ?>%)
                                    </span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                                <th>Total Général</th>
                                <th><?= $totaux_numpers_classe['cadres'] ?></th>
                                <th><?= $totaux_numpers_classe['maitrise'] ?></th>
                                <th><?= $totaux_numpers_classe['execution'] ?></th>
                                <th><?= $totaux_numpers_classe['total'] ?></th>
                                <th>-</th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <!-- Graphique en barres groupées pour type par classe -->
                    <div class="chart-container mt-3" style="height: 400px;">
                        <div class="chart-title">
                            <span>&#128202 Distribution des classes par type de personnel</span>
                        </div>
                        <canvas id="numpersClasseChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= TABLEAUX PAR TYPE DE PERSONNEL ================= -->
<div class="row mt-4">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="personnelTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="contrastage-tab" data-bs-toggle="tab" data-bs-target="#contrastage" type="button" role="tab">&#128203 Situation administrative</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="numpers-tab" data-bs-toggle="tab" data-bs-target="#numpers" type="button" role="tab">👥 Répartition par type</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="age-numpers-tab" data-bs-toggle="tab" data-bs-target="#age-numpers" type="button" role="tab">&#128202 Âge par type</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contrastage-numpers-tab" data-bs-toggle="tab" data-bs-target="#contrastage-numpers" type="button" role="tab">🔄 Situation par type</button>
            </li>
        </ul>
        
        <div class="tab-content mt-3">
            <!-- Tab 1: Situation administrative -->
            <div class="tab-pane fade show active" id="contrastage" role="tabpanel">
                <div class="table-wrapper">
                    <h4>&#128203 Répartition par situation administrative</h4>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Code</th>
                                <th>Situation</th>
                                <th>Total</th>
                                <th>Hommes</th>
                                <th>Femmes</th>
                                <th>Âge moyen</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total_contrastage = 0;
                            foreach($contrastage_data as $row): 
                            $total_contrastage += $row['total'];
                            ?>
                            <tr>
                                <td><span class="<?= getContrastageClass($row['code']) ?>"><?= $row['code'] ?></span></td>
                                <td><?= $row['libelle'] ?></td>
                                <td><strong><?= $row['total'] ?></strong></td>
                                <td><?= $row['hommes'] ?></td>
                                <td><?= $row['femmes'] ?></td>
                                <td><?= $row['age_moyen'] ?> ans</td>
                                <td><?= round(($row['total']/$kpi['total'])*100, 1) ?>%</td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                                <th colspan="2">Total Général</th>
                                <th><?= $total_contrastage ?></th>
                                <th><?= array_sum(array_column($contrastage_data, 'hommes')) ?></th>
                                <th><?= array_sum(array_column($contrastage_data, 'femmes')) ?></th>
                                <th>-</th>
                                <th>100%</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- Tab 2: Répartition par numpers -->
            <div class="tab-pane fade" id="numpers" role="tabpanel">
                <div class="table-wrapper">
                    <h4>👥 Répartition par type de personnel</h4>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Code</th>
                                <th>Type de personnel</th>
                                <th>Total</th>
                                <th>Titulaires</th>
                                <th>Stagiaires</th>
                                <th>Détachés</th>
                                <th>Congé spécial</th>
                                <th>Âge moyen</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $res_numpers->data_seek(0);
                            $total_numpers = 0;
                            while($row = $res_numpers->fetch_assoc()): 
                            $total_numpers += $row['total'];
                            $badge_class = '';
                            switch($row['numpers']) {
                                case 'EC': $badge_class = 'badge-ec'; break;
                                case 'ER': $badge_class = 'badge-er'; break;
                                case 'E': $badge_class = 'badge-e'; break;
                                case 'T': $badge_class = 'badge-t'; break;
                                case 'A': $badge_class = 'badge-a'; break;
                            }
                            ?>
                            <tr>
                                <td><span class="badge-numpers <?= $badge_class ?>"><?= $row['numpers'] ?></span></td>
                                <td><?= $row['type_personnel'] ?></td>
                                <td><strong><?= $row['total'] ?></strong></td>
                                <td class="text-success"><?= $row['titulaire'] ?></td>
                                <td class="text-warning"><?= $row['stagiaire'] ?></td>
                                <td class="text-info"><?= $row['detache'] ?></td>
                                <td class="text-danger"><?= $row['conge_special'] ?></td>
                                <td><?= $row['age_moyen'] ?> ans</td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                                <th colspan="2">Total Général</th>
                                <th><?= $total_numpers ?></th>
                                <th><?= $kpi['titulaire'] ?></th>
                                <th><?= $kpi['stagiaire'] ?></th>
                                <th><?= $kpi['detache_entreprise'] + $kpi['detache_externe'] ?></th>
                                <th><?= $kpi['conge_special'] ?></th>
                                <th>-</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- Tab 3: Âge par numpers -->
            <div class="tab-pane fade" id="age-numpers" role="tabpanel">
                <div class="table-wrapper">
                    <h4>&#128202 Répartition par âge et type de personnel</h4>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Type</th>
                                <th>&lt; 30 ans</th>
                                <th>30-39 ans</th>
                                <th>40-49 ans</th>
                                <th>50-59 ans</th>
                                <th>60+ ans</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $res_age_numpers->data_seek(0);
                            $totals = ['moins_30'=>0, 'trente_39'=>0, 'quarante_49'=>0, 'cinquante_59'=>0, 'soixante_plus'=>0, 'total'=>0];
                            while($row = $res_age_numpers->fetch_assoc()): 
                            $totals['moins_30'] += $row['moins_30'];
                            $totals['trente_39'] += $row['trente_39'];
                            $totals['quarante_49'] += $row['quarante_49'];
                            $totals['cinquante_59'] += $row['cinquante_59'];
                            $totals['soixante_plus'] += $row['soixante_plus'];
                            $totals['total'] += $row['total'];
                            ?>
                            <tr>
                                <td><strong><?= $row['type'] ?></strong></td>
                                <td><?= $row['moins_30'] ?></td>
                                <td><?= $row['trente_39'] ?></td>
                                <td><?= $row['quarante_49'] ?></td>
                                <td><?= $row['cinquante_59'] ?></td>
                                <td><?= $row['soixante_plus'] ?></td>
                                <td><strong><?= $row['total'] ?></strong></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                                <th>Total</th>
                                <th><?= $totals['moins_30'] ?></th>
                                <th><?= $totals['trente_39'] ?></th>
                                <th><?= $totals['quarante_49'] ?></th>
                                <th><?= $totals['cinquante_59'] ?></th>
                                <th><?= $totals['soixante_plus'] ?></th>
                                <th><?= $totals['total'] ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- Tab 4: Situation par numpers -->
            <div class="tab-pane fade" id="contrastage-numpers" role="tabpanel">
                <div class="table-wrapper">
                    <h4>🔄 Situation administrative par type de personnel</h4>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Type</th>
                                <th>Titulaire</th>
                                <th>Stagiaire</th>
                                <th>Détaché entreprise</th>
                                <th>Détaché externe</th>
                                <th>Congé spécial</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $res_contrastage_numpers->data_seek(0);
                            while($row = $res_contrastage_numpers->fetch_assoc()): 
                            ?>
                            <tr>
                                <td><strong><?= $row['type'] ?></strong></td>
                                <td class="text-success"><?= $row['titulaire'] ?></td>
                                <td class="text-warning"><?= $row['stagiaire'] ?></td>
                                <td class="text-info"><?= $row['detache_entreprise'] ?></td>
                                <td class="text-secondary"><?= $row['detache_externe'] ?></td>
                                <td class="text-danger"><?= $row['conge_special'] ?></td>
                                <td><strong><?= $row['total'] ?></strong></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= TABLEAUX COMBINAISON REGION ================= -->
<div class="row mt-4">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="combinaisonTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="region-type-tab" data-bs-toggle="tab" data-bs-target="#region-type" type="button" role="tab">📍 affectation par Type de personnel</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="region-situation-tab" data-bs-toggle="tab" data-bs-target="#region-situation" type="button" role="tab">📍 affectation par Situation</button>
            </li>
        </ul>
        
        <div class="tab-content mt-3">
            <!-- Tab 1: affectation par Type de personnel -->
            <div class="tab-pane fade show active" id="region-type" role="tabpanel">
                <div class="table-wrapper">
                    <h4>📍 Répartition par affectation et Type de personnel</h4>
                    <?php
                    $res_region_type->data_seek(0);
                    $totaux_type = ['EC'=>0, 'ER'=>0, 'E'=>0, 'T'=>0, 'A'=>0, 'total'=>0];
                    ?>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>affectation</th>
                                <th>&#128652 سائق</th>
                                <th>&#128176 قابض</th>
                                <th>&#128203 إداري إستغلال</th>
                                <th>&#128295 تقني</th>
                                <th>&#128084 إداري</th>
                                <th>Total</th>
                                <th>Type dominant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $res_region_type->fetch_assoc()): 
                                $totaux_type['EC'] += $row['EC'];
                                $totaux_type['ER'] += $row['ER'];
                                $totaux_type['E'] += $row['E'];
                                $totaux_type['T'] += $row['T'];
                                $totaux_type['A'] += $row['A'];
                                $totaux_type['total'] += $row['total'];
                                
                                $types = [
                                    'EC' => $row['EC'],
                                    'ER' => $row['ER'],
                                    'E' => $row['E'],
                                    'T' => $row['T'],
                                    'A' => $row['A']
                                ];
                                $dominant = array_search(max($types), $types);
                                $pourcentage_dominant = $row['total'] > 0 ? round((max($types) / $row['total']) * 100, 1) : 0;
                                
                                $badge_dominant = '';
                                switch($dominant) {
                                    case 'EC': $badge_dominant = 'badge-ec'; break;
                                    case 'ER': $badge_dominant = 'badge-er'; break;
                                    case 'E': $badge_dominant = 'badge-e'; break;
                                    case 'T': $badge_dominant = 'badge-t'; break;
                                    case 'A': $badge_dominant = 'badge-a'; break;
                                }
                            ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($row['region'] ?? 'Sans affectation') ?></strong></td>
                                <td><?= $row['EC'] ?></td>
                                <td><?= $row['ER'] ?></td>
                                <td><?= $row['E'] ?></td>
                                <td><?= $row['T'] ?></td>
                                <td><?= $row['A'] ?></td>
                                <td><strong><?= $row['total'] ?></strong></td>
                                <td>
                                    <?php if($row['total'] > 0): ?>
                                    <span class="badge-numpers <?= $badge_dominant ?>">
                                        <?= $dominant ?> (<?= $pourcentage_dominant ?>%)
                                    </span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                                <th>Total Général</th>
                                <th><?= $totaux_type['EC'] ?></th>
                                <th><?= $totaux_type['ER'] ?></th>
                                <th><?= $totaux_type['E'] ?></th>
                                <th><?= $totaux_type['T'] ?></th>
                                <th><?= $totaux_type['A'] ?></th>
                                <th><?= $totaux_type['total'] ?></th>
                                <th>-</th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <!-- Graphique en barres empilées pour affectation par type -->
                    <div class="chart-container mt-3" style="height: 400px;">
                        <div class="chart-title">
                            <span>&#128202 Distribution des types par affectation</span>
                        </div>
                        <canvas id="regionTypeChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Tab 2: affectation par Situation -->
            <div class="tab-pane fade" id="region-situation" role="tabpanel">
                <div class="table-wrapper">
                    <h4>📍 Répartition par affectation et Situation administrative</h4>
                    <?php
                    $res_region_situation->data_seek(0);
                    $totaux_situation = ['titulaire'=>0, 'stagiaire'=>0, 'detache_entreprise'=>0, 'detache_externe'=>0, 'conge_special'=>0, 'total'=>0];
                    ?>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>affectation</th>
                                <th>📝 Titulaire</th>
                                <th>🔰 Stagiaire</th>
                                <th>🤝 Détaché Ent.</th>
                                <th>🌍 Détaché Ext.</th>
                                <th>⚕️ Congé Spécial</th>
                                <th>Total</th>
                                <th>Situation dominante</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $res_region_situation->fetch_assoc()): 
                                $totaux_situation['titulaire'] += $row['titulaire'];
                                $totaux_situation['stagiaire'] += $row['stagiaire'];
                                $totaux_situation['detache_entreprise'] += $row['detache_entreprise'];
                                $totaux_situation['detache_externe'] += $row['detache_externe'];
                                $totaux_situation['conge_special'] += $row['conge_special'];
                                $totaux_situation['total'] += $row['total'];
                                
                                $situations = [
                                    'Titulaire' => $row['titulaire'],
                                    'Stagiaire' => $row['stagiaire'],
                                    'Détaché Ent.' => $row['detache_entreprise'],
                                    'Détaché Ext.' => $row['detache_externe'],
                                    'Congé Spécial' => $row['conge_special']
                                ];
                                $dominant = array_search(max($situations), $situations);
                                $pourcentage_dominant = $row['total'] > 0 ? round((max($situations) / $row['total']) * 100, 1) : 0;
                                
                                $badge_dominant = '';
                                switch($dominant) {
                                    case 'Titulaire': $badge_dominant = 'badge-contrastage-0'; break;
                                    case 'Stagiaire': $badge_dominant = 'badge-contrastage-1'; break;
                                    case 'Détaché Ent.': $badge_dominant = 'badge-contrastage-3'; break;
                                    case 'Détaché Ext.': $badge_dominant = 'badge-contrastage-5'; break;
                                    case 'Congé Spécial': $badge_dominant = 'badge-contrastage-6'; break;
                                }
                            ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($row['region'] ?? 'Sans affectation') ?></strong></td>
                                <td class="text-success"><?= $row['titulaire'] ?></td>
                                <td class="text-warning"><?= $row['stagiaire'] ?></td>
                                <td class="text-info"><?= $row['detache_entreprise'] ?></td>
                                <td class="text-secondary"><?= $row['detache_externe'] ?></td>
                                <td class="text-danger"><?= $row['conge_special'] ?></td>
                                <td><strong><?= $row['total'] ?></strong></td>
                                <td>
                                    <?php if($row['total'] > 0): ?>
                                    <span class="<?= $badge_dominant ?>">
                                        <?= $dominant ?> (<?= $pourcentage_dominant ?>%)
                                    </span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                                <th>Total Général</th>
                                <th><?= $totaux_situation['titulaire'] ?></th>
                                <th><?= $totaux_situation['stagiaire'] ?></th>
                                <th><?= $totaux_situation['detache_entreprise'] ?></th>
                                <th><?= $totaux_situation['detache_externe'] ?></th>
                                <th><?= $totaux_situation['conge_special'] ?></th>
                                <th><?= $totaux_situation['total'] ?></th>
                                <th>-</th>
                            </tr>
                        </tfoot>
                    </table>
                    
                    <!-- Graphique en barres empilées pour affectation par situation -->
                    <div class="chart-container mt-3" style="height: 400px;">
                        <div class="chart-title">
                            <span>&#128202 Distribution des situations par affectation</span>
                        </div>
                        <canvas id="regionSituationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= NOUVELLE SECTION : SUIVI ABSENCES & FORMATIONS ================= -->
<div class="row mt-5">
    <div class="col-12">
        <h3 class="mb-4">&#128203 Suivi des Absences et Formations</h3>
    </div>
</div>

<!-- KPIs Absences -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="absence-card" style="border-left-color: #4361ee;">
            <div class="value"><?= $absences_kpi['total_absences'] ?? 0 ?></div>
            <div class="label">Total absences</div>
            <small><?= $absences_kpi['personnes_concernees'] ?? 0 ?> personnes</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="absence-card" style="border-left-color: #28a745;">
            <div class="value"><?= $absences_kpi['formations'] ?? 0 ?></div>
            <div class="label">Formations</div>
            <small><?= $absences_kpi['jours_formations'] ?? 0 ?> jours</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="absence-card" style="border-left-color: #ffc107;">
            <div class="value"><?= $absences_kpi['maladies'] ?? 0 ?></div>
            <div class="label">Maladies</div>
            <small><?= $absences_kpi['jours_maladies'] ?? 0 ?> jours</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="absence-card" style="border-left-color: #dc3545;">
            <div class="value"><?= $absences_kpi['accidents'] ?? 0 ?></div>
            <div class="label">Accidents travail</div>
            <small><?= $absences_kpi['jours_accidents'] ?? 0 ?> jours</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="absence-card" style="border-left-color: #17a2b8;">
            <div class="value"><?= round($absences_kpi['duree_moyenne'] ?? 0, 1) ?></div>
            <div class="label">Durée moyenne</div>
            <small>jours/absence</small>
        </div>
    </div>
    <div class="col-md-2">
        <div class="absence-card" style="border-left-color: #6c757d;">
            <div class="value"><?= $absences_kpi['total_jours'] ?? 0 ?></div>
            <div class="label">Jours totaux</div>
            <small>d'absence/formation</small>
        </div>
    </div>
</div>

<!-- Tabs pour les absences/formations -->
<div class="row mt-4">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="absencesTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="evolution-absences-tab" data-bs-toggle="tab" data-bs-target="#evolution-absences" type="button" role="tab">📈 Évolution mensuelle</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="formations-tab" data-bs-toggle="tab" data-bs-target="#formations" type="button" role="tab">📚 Suivi formations</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="top-absents-tab" data-bs-toggle="tab" data-bs-target="#top-absents" type="button" role="tab">🔝 Top 10 absents</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="analyse-absences-tab" data-bs-toggle="tab" data-bs-target="#analyse-absences" type="button" role="tab">&#128202 Analyse détaillée</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="taux-absenteisme-tab" data-bs-toggle="tab" data-bs-target="#taux-absenteisme" type="button" role="tab">📉 Taux d'absentéisme</button>
            </li>
        </ul>
        
        <div class="tab-content mt-3">
            <!-- Tab 1: Évolution mensuelle -->
            <div class="tab-pane fade show active" id="evolution-absences" role="tabpanel">
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart-container" style="height: 400px;">
                            <div class="chart-title">
                                <span>📈 Évolution mensuelle des absences/formations</span>
                                <div>
                                    <span class="badge bg-danger">Accidents</span>
                                    <span class="badge bg-warning">Maladies</span>
                                    <span class="badge bg-success">Formations</span>
                                </div>
                            </div>
                            <canvas id="absencesLineChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="chart-container" style="height: 400px;">
                            <div class="chart-title">
                                <span>🥧 Répartition par type</span>
                            </div>
                            <canvas id="absencesPieChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Tableau récapitulatif mensuel -->
                <div class="table-wrapper mt-3">
                    <h5>Détail mensuel des absences</h5>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Période</th>
                                <th>Accidents</th>
                                <th>Maladies</th>
                                <th>Formations</th>
                                <th>Total</th>
                                <th>Jours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $res_absences_mensuelles->data_seek(0);
                            $total_acc = $total_mal = $total_form = $total_abs = $total_jrs = 0;
                            while($row = $res_absences_mensuelles->fetch_assoc()): 
                                $mois_noms = ['', 'Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
                                $total_acc += $row['accidents'];
                                $total_mal += $row['maladies'];
                                $total_form += $row['formations'];
                                $total_abs += $row['total'];
                                $total_jrs += $row['total_jours'];
                            ?>
                            <tr>
                                <td><strong><?= $mois_noms[$row['mois']] ?> <?= $row['annee'] ?></strong></td>
                                <td class="text-danger"><?= $row['accidents'] ?></td>
                                <td class="text-warning"><?= $row['maladies'] ?></td>
                                <td class="text-success"><?= $row['formations'] ?></td>
                                <td><strong><?= $row['total'] ?></strong></td>
                                <td><?= $row['total_jours'] ?> j</td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="table-primary">
                            <tr>
                                <th>Total</th>
                                <th><?= $total_acc ?></th>
                                <th><?= $total_mal ?></th>
                                <th><?= $total_form ?></th>
                                <th><?= $total_abs ?></th>
                                <th><?= $total_jrs ?> j</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <!-- Tab 2: Suivi formations -->
            <div class="tab-pane fade" id="formations" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="chart-container" style="height: 350px;">
                            <div class="chart-title">
                                <span>📚 Évolution des formations</span>
                            </div>
                            <canvas id="formationsLineChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="table-wrapper">
                            <h5>Formations par type de personnel</h5>
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Type</th>
                                        <th>Personnes formées</th>
                                        <th>Nb formations</th>
                                        <th>Jours formation</th>
                                        <th>Taux participation</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $res_formations_par_type->data_seek(0);
                                    while($row = $res_formations_par_type->fetch_assoc()): 
                                    ?>
                                    <tr>
                                        <td><?= $row['type_personnel'] ?></td>
                                        <td><?= $row['personnes_formees'] ?></td>
                                        <td><?= $row['nb_formations'] ?></td>
                                        <td><?= $row['jours_formation'] ?></td>
                                        <td>
                                            <div class="progress">
                                                <div class="progress-bar bg-success" style="width: <?= $row['taux_participation'] ?>%;">
                                                    <?= $row['taux_participation'] ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Détail des formations par mois -->
                <div class="table-wrapper mt-3">
                    <h5>Détail des formations par mois</h5>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Mois</th>
                                <th>Participants</th>
                                <th>Nb formations</th>
                                <th>Jours formation</th>
                                <th>Durée moyenne</th>
                                <th>Types concernés</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $res_formations->data_seek(0);
                            while($row = $res_formations->fetch_assoc()): 
                                $mois_noms = ['', 'Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
                            ?>
                            <tr>
                                <td><strong><?= $mois_noms[$row['mois']] ?> <?= $row['annee'] ?></strong></td>
                                <td><?= $row['participants'] ?></td>
                                <td><?= $row['nb_formations'] ?></td>
                                <td><?= $row['total_jours_formation'] ?></td>
                                <td><?= round($row['duree_moyenne'], 1) ?> j</td>
                                <td>
                                    <?php 
                                    if($row['types_personnel']) {
                                        $types = explode(',', $row['types_personnel']);
                                        $types_uniques = array_unique($types);
                                        foreach($types_uniques as $t) {
                                            $badge = '';
                                            switch($t) {
                                                case 'EC': $badge = 'badge-ec'; break;
                                                case 'ER': $badge = 'badge-er'; break;
                                                case 'E': $badge = 'badge-e'; break;
                                                case 'T': $badge = 'badge-t'; break;
                                                case 'A': $badge = 'badge-a'; break;
                                                default: $badge = 'badge-secondary';
                                            }
                                            echo "<span class='badge-numpers $badge me-1'>$t</span>";
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Tab 3: Top 10 absents -->
            <div class="tab-pane fade" id="top-absents" role="tabpanel">
                <div class="table-wrapper">
                    <h5>🔝 Top 10 des personnes les plus absentes (12 derniers mois)</h5>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Matricule</th>
                                <th>Nom</th>
                                <th>affectation</th>
                                <th>Service</th>
                                <th>Type</th>
                                <th>Nb absences</th>
                                <th>Total jours</th>
                                <th>Dont accidents</th>
                                <th>Dont maladies</th>
                                <th>Répartition</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $res_top_absents->data_seek(0);
                            $rank = 1;
                            while($row = $res_top_absents->fetch_assoc()): 
                            ?>
                            <tr>
                                <td><strong>#<?= $rank++ ?></strong></td>
                                <td><?= $row['mecano'] ?></td>
                                <td><?= htmlspecialchars($row['nom']) ?></td>
                                <td><?= htmlspecialchars($row['region'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['service'] ?? 'N/A') ?></td>
                                <td>
                                    <?php
                                    $badge_type = '';
                                    switch($row['numpers']) {
                                        case 'EC': $badge_type = 'badge-ec'; break;
                                        case 'ER': $badge_type = 'badge-er'; break;
                                        case 'E': $badge_type = 'badge-e'; break;
                                        case 'T': $badge_type = 'badge-t'; break;
                                        case 'A': $badge_type = 'badge-a'; break;
                                        default: $badge_type = 'badge-secondary';
                                    }
                                    ?>
                                    <span class="badge-numpers <?= $badge_type ?>"><?= $row['numpers'] ?></span>
                                </td>
                                <td><?= $row['nb_absences'] ?></td>
                                <td><strong><?= $row['total_jours'] ?> j</strong></td>
                                <td class="text-danger"><?= $row['jours_accidents'] ?> j</td>
                                <td class="text-warning"><?= $row['jours_maladies'] ?> j</td>
                                <td style="width: 150px;">
                                    <?php 
                                    $total = $row['total_jours'];
                                    $acc_pct = $total > 0 ? round(($row['jours_accidents'] / $total) * 100) : 0;
                                    $mal_pct = $total > 0 ? round(($row['jours_maladies'] / $total) * 100) : 0;
                                    ?>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-danger" style="width: <?= $acc_pct ?>%;" title="Accidents"></div>
                                        <div class="progress-bar bg-warning" style="width: <?= $mal_pct ?>%;" title="Maladies"></div>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Tab 4: Analyse détaillée -->
            <div class="tab-pane fade" id="analyse-absences" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-wrapper">
                            <h5>📍 Absences par affectation</h5>
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>affectation</th>
                                        <th>Personnes</th>
                                        <th>Nb absences</th>
                                        <th>Jours totaux</th>
                                        <th>Durée moy.</th>
                                        <th>Taux absence</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($absences_region_data as $row): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($row['region'] ?? 'Sans affectation') ?></strong></td>
                                        <td><?= $row['personnes'] ?></td>
                                        <td><?= $row['nb_absences'] ?></td>
                                        <td><?= $row['total_jours'] ?> j</td>
                                        <td><?= round($row['duree_moyenne'], 1) ?> j</td>
                                        <td>
                                            <?php 
                                            $taux = $row['taux_absence'] ?? 0;
                                            $couleur = $taux > 10 ? 'danger' : ($taux > 5 ? 'warning' : 'success');
                                            ?>
                                            <span class="badge bg-<?= $couleur ?>"><?= $taux ?> j/pers</span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="chart-container mt-3" style="height: 300px;">
                            <div class="chart-title">
                                <span>📍 Carte des absences par affectation</span>
                            </div>
                            <canvas id="absencesRegionChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="table-wrapper">
                            <h5>&#128202 Répartition par durée</h5>
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Durée</th>
                                        <th>Total</th>
                                        <th>Accidents</th>
                                        <th>Maladies</th>
                                        <th>Formations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($absences_duree_data as $row): ?>
                                    <tr>
                                        <td><strong><?= $row['tranche_duree'] ?></strong></td>
                                        <td><?= $row['nb_absences'] ?></td>
                                        <td class="text-danger"><?= $row['accidents'] ?></td>
                                        <td class="text-warning"><?= $row['maladies'] ?></td>
                                        <td class="text-success"><?= $row['formations'] ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="table-wrapper mt-3">
                            <h5>👥 Absences par type personnel</h5>
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Type</th>
                                        <th>Personnes</th>
                                        <th>Nb absences</th>
                                        <th>Jours totaux</th>
                                        <th>Jours/accident</th>
                                        <th>Jours/maladie</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $res_absences_numpers->data_seek(0);
                                    while($row = $res_absences_numpers->fetch_assoc()): 
                                    ?>
                                    <tr>
                                        <td><?= $row['type_personnel'] ?></td>
                                        <td><?= $row['personnes'] ?></td>
                                        <td><?= $row['nb_absences'] ?></td>
                                        <td><strong><?= $row['total_jours'] ?> j</strong></td>
                                        <td class="text-danger"><?= $row['jours_accidents'] ?> j</td>
                                        <td class="text-warning"><?= $row['jours_maladies'] ?> j</td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tab 5: Taux d'absentéisme -->
            <div class="tab-pane fade" id="taux-absenteisme" role="tabpanel">
                <div class="row">
                    <div class="col-md-8">
                        <div class="chart-container" style="height: 400px;">
                            <div class="chart-title">
                                <span>📉 Taux d'absentéisme (maladies + accidents)</span>
                            </div>
                            <canvas id="tauxAbsenteismeChart"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="absence-card mb-3" style="border-left-color: #4361ee;">
                            <div class="value"><?= round(array_sum(array_column($taux_absenteisme_data, 'taux_absenteisme')) / max(1, count($taux_absenteisme_data)), 2) ?>%</div>
                            <div class="label">Taux moyen annuel</div>
                        </div>
                        <div class="absence-card mb-3" style="border-left-color: #28a745;">
                            <div class="value"><?= array_sum(array_column($taux_absenteisme_data, 'jours_absences')) ?></div>
                            <div class="label">Jours perdus (12 mois)</div>
                        </div>
                        <div class="alert alert-info">
                            <strong>💡 Seuils d'absentéisme :</strong>
                            <ul class="mt-2 mb-0">
                                <li>< 3% : Excellent</li>
                                <li>3-5% : Normal</li>
                                <li>5-8% : Surveiller</li>
                                <li>> 8% : Critique</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Tableau détaillé -->
                <div class="table-wrapper mt-3">
                    <h5>Détail mensuel du taux d'absentéisme</h5>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Mois</th>
                                <th>Jours d'absence</th>
                                <th>Jours théoriques</th>
                                <th>Taux d'absentéisme</th>
                                <th>Indicateur</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach($taux_absenteisme_data as $row):
                                $couleur_taux = $row['taux_absenteisme'] < 3 ? 'success' : ($row['taux_absenteisme'] < 5 ? 'warning' : ($row['taux_absenteisme'] < 8 ? 'warning' : 'danger'));
                            ?>
                            <tr>
                                <td><strong><?= $row['mois'] ?></strong></td>
                                <td><?= $row['jours_absences'] ?> j</td>
                                <td><?= $row['jours_theoriques'] ?> j</td>
                                <td>
                                    <span class="badge bg-<?= $couleur_taux ?>"><?= $row['taux_absenteisme'] ?>%</span>
                                </td>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar bg-<?= $couleur_taux ?>" style="width: <?= min($row['taux_absenteisme'] * 10, 100) ?>%;">
                                            <?= $row['taux_absenteisme'] ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= TABLEAUX CROISÉS DYNAMIQUES POUR ABSENCES ================= -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="table-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">&#128202 Tableau croisé : Absences par affectation et type</h4>
            </div>
            
            <?php
            $res_pivot_absence->data_seek(0);
            ?>
            
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>affectation</th>
                        <th>🚑 Accidents</th>
                        <th>🤒 Maladies</th>
                        <th>📚 Formations</th>
                        <th>Total absences</th>
                        <th>Jours totaux</th>
                        <th>Type dominant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $t_acc = $t_mal = $t_form = $t_abs = $t_jrs = 0;
                    while($row = $res_pivot_absence->fetch_assoc()): 
                        $t_acc += $row['accidents'];
                        $t_mal += $row['maladies'];
                        $t_form += $row['formations'];
                        $t_abs += $row['total_absences'];
                        $t_jrs += $row['total_jours'];
                        
                        $types = [
                            'Accidents' => $row['accidents'],
                            'Maladies' => $row['maladies'],
                            'Formations' => $row['formations']
                        ];
                        $dominant = array_search(max($types), $types);
                        $badge_dominant = '';
                        switch($dominant) {
                            case 'Accidents': $badge_dominant = 'badge-accident'; break;
                            case 'Maladies': $badge_dominant = 'badge-maladie'; break;
                            case 'Formations': $badge_dominant = 'badge-formation'; break;
                        }
                    ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($row['region'] ?? 'Sans affectation') ?></strong></td>
                        <td class="text-danger"><?= $row['accidents'] ?></td>
                        <td class="text-warning"><?= $row['maladies'] ?></td>
                        <td class="text-success"><?= $row['formations'] ?></td>
                        <td><strong><?= $row['total_absences'] ?></strong></td>
                        <td><?= $row['total_jours'] ?> j</td>
                        <td>
                            <?php if($row['total_absences'] > 0): ?>
                            <span class="<?= $badge_dominant ?>">
                                <?= $dominant ?>
                            </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot class="table-primary">
                    <tr>
                        <th>Total Général</th>
                        <th><?= $t_acc ?></th>
                        <th><?= $t_mal ?></th>
                        <th><?= $t_form ?></th>
                        <th><?= $t_abs ?></th>
                        <th><?= $t_jrs ?> j</th>
                        <th>-</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- ================= PIVOT TABLE ================= -->
<div class="table-wrapper mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">&#128202 Tableau croisé dynamique</h4>
        <div>
            <select class="form-select" id="pivotRow" style="width: 200px; display: inline-block; margin-right: 10px;">
                <option value="region">Par affectation</option>
                <option value="service">Par Service</option>
                <option value="contrastage">Par Situation</option>
                <option value="numpers">Par Type personnel</option>
            </select>
            <select class="form-select" id="pivotCol" style="width: 200px; display: inline-block;">
                <option value="contrastage">Situation</option>
                <option value="region">affectation</option>
                <option value="service">Service</option>
                <option value="numpers">Type personnel</option>
            </select>
            <button class="btn btn-primary ms-2" onclick="updatePivotTable()">Actualiser</button>
        </div>
    </div>
    <div id="pivotTableContainer">
        <?php
        $res_combined->data_seek(0);
        ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>affectation</th>
                    <th>Titulaire</th>
                    <th>Stagiaire</th>
                    <th>Détaché Ent.</th>
                    <th>Détaché Ext.</th>
                    <th>Congé Spécial</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $t_titulaire = 0; 
                $t_stagiaire = 0; 
                $t_detache_ent = 0;
                $t_detache_ext = 0;
                $t_conge = 0;
                $t_total = 0;
                while($row = $res_combined->fetch_assoc()): 
                $t_titulaire += $row['titulaire']; 
                $t_stagiaire += $row['stagiaire']; 
                $t_detache_ent += $row['detache_entreprise'];
                $t_detache_ext += $row['detache_externe'];
                $t_conge += $row['conge_special'];
                $t_total += $row['total'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($row['region'] ?? 'Sans affectation') ?></td>
                    <td class="text-success"><?= $row['titulaire'] ?></td>
                    <td class="text-warning"><?= $row['stagiaire'] ?></td>
                    <td class="text-info"><?= $row['detache_entreprise'] ?></td>
                    <td class="text-secondary"><?= $row['detache_externe'] ?></td>
                    <td class="text-danger"><?= $row['conge_special'] ?></td>
                    <td><strong><?= $row['total'] ?></strong></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot class="table-primary">
                <tr>
                    <th>Total Général</th>
                    <th id="totalTitulaire"><?= $t_titulaire ?></th>
                    <th id="totalStagiaire"><?= $t_stagiaire ?></th>
                    <th id="totalDetacheEnt"><?= $t_detache_ent ?></th>
                    <th id="totalDetacheExt"><?= $t_detache_ext ?></th>
                    <th id="totalConge"><?= $t_conge ?></th>
                    <th id="totalGeneral"><?= $t_total ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<!-- ================= STATIC TABLES ================= -->
<div class="row">
    <div class="col-md-6">
        <div class="table-wrapper">
            <h4>📍 Tableau par affectation</h4>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>affectation</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $res_region->data_seek(0);
                    $total_region = 0; 
                    ?>
                    <?php while($row = $res_region->fetch_assoc()): 
                    $total_region += $row['total']; ?>
                    <tr>
                        <td><?= htmlspecialchars($row['region'] ?? 'Sans affectation') ?></td>
                        <td><?= $row['total'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot class="table-success">
                    <tr>
                        <th>Total Général</th>
                        <th><?= $total_region ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="col-md-6">
        <div class="table-wrapper">
            <h4>🏢 Tableau par Service</h4>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Service</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $res_service->data_seek(0);
                    $total_service = 0; 
                    ?>
                    <?php while($row = $res_service->fetch_assoc()): 
                    $total_service += $row['total']; ?>
                    <tr>
                        <td><?= htmlspecialchars($row['service'] ?? 'Sans service') ?></td>
                        <td><?= $row['total'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
                <tfoot class="table-success">
                    <tr>
                        <th>Total Général</th>
                        <th><?= $total_service ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- ================= STATISTIQUES RAPIDES ================= -->
<div class="row mt-4">
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <small>Âge moyen</small>
            <div class="stat-value"><?= $avg_age ?> ans</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <small>Départs <?= $annee_courante ?></small>
            <?php
            $departs_annee = 0;
            $res_departs_futurs->data_seek(0);
            while($row = $res_departs_futurs->fetch_assoc()) {
                if($row['annee_depart'] == $annee_courante) $departs_annee = $row['total_prevision'];
            }
            ?>
            <div class="stat-value"><?= $departs_annee ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
            <small>Effectif féminin</small>
            <?php
            $femmes = $conn->query("SELECT COUNT(*) as total FROM stuf WHERE sexe = 'F'")->fetch_assoc()['total'];
            $pourcentage_f = round(($femmes/$kpi['total'])*100, 1);
            ?>
            <div class="stat-value"><?= $femmes ?> (<?= $pourcentage_f ?>%)</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
            <small>Effectif masculin</small>
            <?php
            $hommes = $conn->query("SELECT COUNT(*) as total FROM stuf WHERE sexe = 'M'")->fetch_assoc()['total'];
            $pourcentage_h = round(($hommes/$kpi['total'])*100, 1);
            ?>
            <div class="stat-value"><?= $hommes ?> (<?= $pourcentage_h ?>%)</div>
        </div>
    </div>
</div>

<!-- ================= SECTION ANCIENNETÉ ================= -->
<div class="row mt-5">
    <div class="col-12">
        <h3 class="mb-4">⏱️ Suivi de l'Ancienneté du Personnel</h3>
    </div>
</div>

<!-- Cartes statistiques ancienneté -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="anciennete-card">
            <div class="value"><?= $stats_anciennete['anciennete_moyenne'] ?></div>
            <div class="label">Ancienneté moyenne</div>
            <small class="text-muted">ans</small>
            <div class="progress-anciennete mt-2">
                <div class="progress-anciennete-bar" style="width: <?= ($stats_anciennete['anciennete_moyenne']/40)*100 ?>%"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="anciennete-card">
            <div class="value"><?= $stats_anciennete['anciennete_max'] ?></div>
            <div class="label">Ancienneté maximale</div>
            <small class="text-muted">ans</small>
            <div class="star-rating mt-2">
                <?php for($i=0; $i<min(5, round($stats_anciennete['anciennete_max']/8)); $i++): ?>⭐<?php endfor; ?>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="anciennete-card">
            <div class="value"><?= $stats_anciennete['total_20_plus'] ?></div>
            <div class="label">20+ ans d'ancienneté</div>
            <small class="text-muted"><?= round(($stats_anciennete['total_20_plus']/$kpi['total'])*100, 1) ?>% de l'effectif</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="anciennete-card">
            <div class="value"><?= $stats_anciennete['total_30_plus'] ?></div>
            <div class="label">30+ ans d'ancienneté</div>
            <small class="text-muted">Vétérans de l'entreprise</small>
        </div>
    </div>
</div>

<!-- Tabs pour l'ancienneté -->
<div class="row mt-4">
    <div class="col-md-12">
        <ul class="nav nav-tabs" id="ancienneteTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="distribution-anciennete-tab" data-bs-toggle="tab" data-bs-target="#distribution-anciennete" type="button" role="tab">&#128202 Distribution ancienneté</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="region-anciennete-tab" data-bs-toggle="tab" data-bs-target="#region-anciennete" type="button" role="tab">📍 Ancienneté par affectation</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="type-anciennete-tab" data-bs-toggle="tab" data-bs-target="#type-anciennete" type="button" role="tab">👥 Ancienneté par type</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="classe-anciennete-tab" data-bs-toggle="tab" data-bs-target="#classe-anciennete" type="button" role="tab">📈 Ancienneté par classe</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="evolution-anciennete-tab" data-bs-toggle="tab" data-bs-target="#evolution-anciennete" type="button" role="tab">📉 Évolution ancienneté</button>
            </li>
        </ul>
        
        <div class="tab-content mt-3">
            <!-- Tab 1: Distribution ancienneté -->
            <div class="tab-pane fade show active" id="distribution-anciennete" role="tabpanel">
                <div class="row">
                    <div class="col-md-7">
                        <div class="table-wrapper">
                            <h4>&#128202 Répartition par tranche d'ancienneté</h4>
                            <table class="table table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Tranche d'ancienneté</th>
                                        <th>Total</th>
                                        <th>Hommes</th>
                                        <th>Femmes</th>
                                        <th>Âge moy.</th>
                                        <th>Échelle moy.</th>
                                        <th>%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_anciennete = 0;
                                    foreach($anciennete_globale_data as $row): 
                                    $total_anciennete += $row['total'];
                                    $badge_class = '';
                                    if (strpos($row['tranche_anciennete'], 'Moins') !== false) $badge_class = 'anciennete-debut';
                                    elseif (strpos($row['tranche_anciennete'], '5-10') !== false) $badge_class = 'anciennete-moyenne';
                                    elseif (strpos($row['tranche_anciennete'], '10-15') !== false) $badge_class = 'anciennete-confirmee';
                                    elseif (strpos($row['tranche_anciennete'], '15-20') !== false) $badge_class = 'anciennete-experimentee';
                                    elseif (strpos($row['tranche_anciennete'], '20-25') !== false) $badge_class = 'anciennete-senior';
                                    elseif (strpos($row['tranche_anciennete'], '25-30') !== false) $badge_class = 'anciennete-veteran';
                                    elseif (strpos($row['tranche_anciennete'], 'Plus') !== false) $badge_class = 'anciennete-expert';
                                    ?>
                                    <tr>
                                        <td><span class="badge-anciennete <?= $badge_class ?>"><?= $row['tranche_anciennete'] ?></span></td>
                                        <td><strong><?= $row['total'] ?></strong></td>
                                        <td><?= $row['hommes'] ?></td>
                                        <td><?= $row['femmes'] ?></td>
                                        <td><?= $row['anciennete_moyenne'] ?> ans</td>
                                        <td><?= $row['echelle_moyenne'] ?></td>
                                        <td><?= round(($row['total']/$kpi['total'])*100, 1) ?>%</td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot class="table-primary">
                                    <tr>
                                        <th>Total Général</th>
                                        <th><?= $total_anciennete ?></th>
                                        <th><?= array_sum(array_column($anciennete_globale_data, 'hommes')) ?></th>
                                        <th><?= array_sum(array_column($anciennete_globale_data, 'femmes')) ?></th>
                                        <th><?= $stats_anciennete['anciennete_moyenne'] ?> ans</th>
                                        <th>-</th>
                                        <th>100%</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="chart-container" style="height: 350px;">
                            <div class="chart-title">
                                <span>🥧 Distribution ancienneté</span>
                            </div>
                            <canvas id="anciennetePieChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tab 2: Ancienneté par affectation -->
            <div class="tab-pane fade" id="region-anciennete" role="tabpanel">
                <div class="table-wrapper">
                    <h4>📍 Ancienneté moyenne par affectation</h4>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>affectation</th>
                                <th>Ancienneté moyenne</th>
                                <th>Ancienneté min</th>
                                <th>Ancienneté max</th>
                                <th>Effectif</th>
                                <th>Indice ancienneté</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $max_anciennete_region = max(array_column($anciennete_region_data, 'anciennete_moyenne') ?: [0]);
                            foreach($anciennete_region_data as $row): 
                            $indice = round(($row['anciennete_moyenne'] / ($max_anciennete_region ?: 1)) * 100);
                            $couleur = $indice > 80 ? 'success' : ($indice > 50 ? 'warning' : 'info');
                            ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($row['region'] ?? 'Sans affectation') ?></strong></td>
                                <td>
                                    <span class="badge bg-<?= $couleur ?>"><?= $row['anciennete_moyenne'] ?> ans</span>
                                </td>
                                <td><?= $row['anciennete_min'] ?> ans</td>
                                <td><?= $row['anciennete_max'] ?> ans</td>
                                <td><?= $row['total'] ?></td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-<?= $couleur ?>" role="progressbar" style="width: <?= $indice ?>%;" aria-valuenow="<?= $indice ?>" aria-valuemin="0" aria-valuemax="100">
                                            <?= $indice ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <!-- Graphique barres ancienneté par affectation -->
                    <div class="chart-container mt-3" style="height: 350px;">
                        <div class="chart-title">
                            <span>&#128202 Ancienneté moyenne par affectation</span>
                        </div>
                        <canvas id="ancienneteRegionChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Tab 3: Ancienneté par type -->
            <div class="tab-pane fade" id="type-anciennete" role="tabpanel">
                <div class="table-wrapper">
                    <h4>👥 Ancienneté par type de personnel</h4>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Type</th>
                                <th>Ancienneté moyenne</th>
                                <th>Min - Max</th>
                                <th>Senior (20+ ans)</th>
                                <th>Junior (<5 ans)</th>
                                <th>Effectif</th>
                                <th>Pyramide ancienneté</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($anciennete_numpers_data as $row): 
                            $pourcentage_senior = $row['total'] > 0 ? round(($row['senior_20_plus'] / $row['total']) * 100) : 0;
                            $pourcentage_junior = $row['total'] > 0 ? round(($row['junior_moins_5'] / $row['total']) * 100) : 0;
                            ?>
                            <tr>
                                <td><strong><?= $row['type'] ?></strong></td>
                                <td>
                                    <span class="badge bg-primary"><?= $row['anciennete_moyenne'] ?> ans</span>
                                </td>
                                <td><?= $row['anciennete_min'] ?> - <?= $row['anciennete_max'] ?> ans</td>
                                <td>
                                    <span class="badge bg-danger"><?= $row['senior_20_plus'] ?></span>
                                    <small class="text-muted">(<?= $pourcentage_senior ?>%)</small>
                                </td>
                                <td>
                                    <span class="badge bg-success"><?= $row['junior_moins_5'] ?></span>
                                    <small class="text-muted">(<?= $pourcentage_junior ?>%)</small>
                                </td>
                                <td><?= $row['total'] ?></td>
                                <td style="width: 200px;">
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" style="width: <?= $pourcentage_junior ?>%;" title="Moins de 5 ans"></div>
                                        <div class="progress-bar bg-warning" style="width: <?= 100 - $pourcentage_senior - $pourcentage_junior ?>%;" title="5-20 ans"></div>
                                        <div class="progress-bar bg-danger" style="width: <?= $pourcentage_senior ?>%;" title="20+ ans"></div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Tab 4: Ancienneté par classe -->
            <div class="tab-pane fade" id="classe-anciennete" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-wrapper">
                            <h4>📈 Ancienneté par classe</h4>
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Classe</th>
                                        <th>Anc. moyenne</th>
                                        <th>Anciens (20+)</th>
                                        <th>Récents (<5)</th>
                                        <th>Ratio S/J</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($anciennete_classe_data as $row): 
                                    $ratio = $row['recents'] > 0 ? round($row['anciens'] / $row['recents'], 2) : $row['anciens'];
                                    ?>
                                    <tr>
                                        <td><?= $row['classe'] ?></td>
                                        <td><strong><?= $row['anciennete_moyenne'] ?> ans</strong></td>
                                        <td><?= $row['anciens'] ?></td>
                                        <td><?= $row['recents'] ?></td>
                                        <td>
                                            <?php if($ratio > 2): ?>
                                                <span class="badge bg-danger">Élevé (<?= $ratio ?>)</span>
                                            <?php elseif($ratio > 1): ?>
                                                <span class="badge bg-warning">Moyen (<?= $ratio ?>)</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Faible (<?= $ratio ?>)</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="chart-container" style="height: 300px;">
                            <div class="chart-title">
                                <span>&#128202 Ancienneté par classe</span>
                            </div>
                            <canvas id="ancienneteClasseChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tab 5: Évolution ancienneté -->
            <div class="tab-pane fade" id="evolution-anciennete" role="tabpanel">
                <div class="chart-container" style="height: 400px;">
                    <div class="chart-title">
                        <span>📉 Évolution de l'ancienneté moyenne par année de recrutement</span>
                        <span class="badge bg-info">Tendance générale</span>
                    </div>
                    <canvas id="evolutionAncienneteChart"></canvas>
                </div>
                
                <!-- Top 10 des plus anciens -->
                <div class="table-wrapper mt-3">
                    <h4>⭐ Top 10 des employés avec la plus grande ancienneté</h4>
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>mecano</th>
                                <th>Nom & Prénom</th>
                                <th>affectation</th>
                                <th>Service</th>
                                <th>Type</th>
                                <th>Date recrutement</th>
                                <th>Ancienneté</th>
                                <th>Badge</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $res_top_anciens->data_seek(0);
                            $rank = 1;
                            while($row = $res_top_anciens->fetch_assoc()): 
                            $anciennete_ans = $row['anciennete_ans'];
                            $badge_ancien = '';
                            if ($anciennete_ans >= 30) $badge_ancien = 'anciennete-expert';
                            elseif ($anciennete_ans >= 25) $badge_ancien = 'anciennete-veteran';
                            elseif ($anciennete_ans >= 20) $badge_ancien = 'anciennete-senior';
                            elseif ($anciennete_ans >= 15) $badge_ancien = 'anciennete-experimentee';
                            else $badge_ancien = 'anciennete-confirmee';
                            ?>
                            <tr>
                                <td><strong>#<?= $rank++ ?></strong></td>
                                <td><?= htmlspecialchars($row['mecano']) ?></td>
                                <td><?= htmlspecialchars($row['nom']) ?></td>
                                <td><?= htmlspecialchars($row['region'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['service'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($row['numpers']) ?></td>
                                <td><?= date('d/m/Y', strtotime($row['daterec'])) ?></td>
                                <td>
                                    <strong><?= $row['anciennete_ans'] ?> ans</strong>
                                    <small class="text-muted">(<?= $row['anciennete_mois'] ?> mois)</small>
                                </td>
                                <td><span class="badge-anciennete <?= $badge_ancien ?>">⭐ <?= $anciennete_ans ?> ans</span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Timeline ancienneté -->
<div class="row mt-4">
    <div class="col-12">
        <div class="table-wrapper">
            <h4>⏳ Ligne de temps - Répartition par décennie d'ancienneté</h4>
            <div class="row">
                <?php
                $decennies = [
                    '0-10 ans' => ['min' => 0, 'max' => 10, 'couleur' => '#4cc9f0'],
                    '10-20 ans' => ['min' => 10, 'max' => 20, 'couleur' => '#4361ee'],
                    '20-30 ans' => ['min' => 20, 'max' => 30, 'couleur' => '#7209b7'],
                    '30-40 ans' => ['min' => 30, 'max' => 40, 'couleur' => '#f72585'],
                    '40+ ans' => ['min' => 40, 'max' => 100, 'couleur' => '#ef476f']
                ];
                
                foreach($decennies as $libelle => $infos):
                    $count = $conn->query("
                        SELECT COUNT(*) as total 
                        FROM stuf 
                        WHERE daterec IS NOT NULL 
                        AND TIMESTAMPDIFF(YEAR, daterec, CURDATE()) BETWEEN {$infos['min']} AND {$infos['max']}
                    ")->fetch_assoc()['total'];
                    $pourcentage = round(($count/$kpi['total'])*100, 1);
                ?>
                <div class="col-md-2">
                    <div class="card" style="border-left: 5px solid <?= $infos['couleur'] ?>;">
                        <div class="card-body text-center">
                            <h6><?= $libelle ?></h6>
                            <h3 style="color: <?= $infos['couleur'] ?>;"><?= $count ?></h3>
                            <small><?= $pourcentage ?>% de l'effectif</small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="col-md-2">
                    <div class="card" style="border-left: 5px solid #28a745;">
                        <div class="card-body text-center">
                            <h6>&#128202 Ancienneté moy.</h6>
                            <h3 style="color: #28a745;"><?= $stats_anciennete['anciennete_moyenne'] ?></h3>
                            <small>ans</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

<script>
// Variables globales pour les graphiques
let barChart, pieChart, lineChart, pyramidChart, departsChart, comparatifChart;
let regionTypeChart, regionSituationChart, classePieChart, regionClasseChart, numpersClasseChart;
let anciennetePieChart, ancienneteRegionChart, ancienneteClasseChart, evolutionAncienneteChart;
let absencesLineChart, absencesPieChart, formationsLineChart, absencesRegionChart, tauxAbsenteismeChart;

// Données initiales
const initialData = {
    regions: <?= json_encode($region_data) ?>,
    agePyramid: <?= json_encode($age_data) ?>,
    recruitment: <?= json_encode($recruitment_data) ?>,
    departsCombined: <?= json_encode($departs_combined_data) ?>,
    comparatif: <?= json_encode($comparatif_data) ?>,
    kpi: <?= json_encode($kpi) ?>,
    regionType: <?= json_encode($region_type_data) ?>,
    regionSituation: <?= json_encode($region_situation_data) ?>,
    classe: <?= json_encode($classe_data) ?>,
    regionClasse: <?= json_encode($region_classe_data) ?>,
    numpersClasse: <?= json_encode($numpers_classe_data) ?>,
    ancienneteGlobale: <?= json_encode($anciennete_globale_data) ?>,
    ancienneteRegion: <?= json_encode($anciennete_region_data) ?>,
    ancienneteNumpers: <?= json_encode($anciennete_numpers_data) ?>,
    ancienneteClasse: <?= json_encode($anciennete_classe_data) ?>,
    evolutionAnciennete: <?= json_encode($evolution_anciennete_data) ?>,
    absencesMensuelles: <?= json_encode($absences_mensuelles_data) ?>,
    absencesKpi: <?= json_encode($absences_kpi) ?>,
    formations: <?= json_encode($formations_data) ?>,
    absencesRegion: <?= json_encode($absences_region_data) ?>,
    tauxAbsenteisme: <?= json_encode($taux_absenteisme_data) ?>
};

$(document).ready(function() {
    // Initialize Select2
    $('.select2-multiple').select2({
        placeholder: "Sélectionner...",
        allowClear: true,
        width: '100%'
    });
    
    // Initialize all charts
    initBarChart(initialData.regions);
    initPieChart(initialData.kpi);
    initLineChart(initialData.recruitment);
    initPyramidChart(initialData.agePyramid);
    initDepartsChart(initialData.departsCombined);
    initComparatifChart(initialData.comparatif);
    initRegionTypeChart(initialData.regionType);
    initRegionSituationChart(initialData.regionSituation);
    initClassePieChart(initialData.classe);
    initRegionClasseChart(initialData.regionClasse);
    initNumpersClasseChart(initialData.numpersClasse);
    initAncienneteCharts();
    initAbsencesCharts();
    
    // Animate KPI numbers
    animateNumbers();
});

function animateNumbers() {
    $('.kpi-card h2').each(function() {
        const $this = $(this);
        const target = parseInt($this.text());
        $({ count: 0 }).animate({ count: target }, {
            duration: 1000,
            easing: 'linear',
            step: function() {
                $this.text(Math.floor(this.count));
            },
            complete: function() {
                $this.text(target);
            }
        });
    });
}

function initBarChart(data) {
    const ctx = document.getElementById('barChart').getContext('2d');
    
    if (barChart) barChart.destroy();
    
    barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.region || 'Sans affectation'),
            datasets: [{
                label: 'Effectif par affectation',
                data: data.map(d => parseInt(d.total)),
                backgroundColor: 'rgba(67, 97, 238, 0.7)',
                borderColor: '#4361ee',
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 20
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: { 
                    backgroundColor: '#212529'
                },
                datalabels: {
                    display: true,
                    color: '#000000',
                    anchor: 'end',
                    align: 'top',
                    offset: 4,
                    font: {
                        weight: 'bold',
                        size: 11
                    },
                    formatter: function(value) {
                        return value;
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            }
        }
    });
}

function initPieChart(kpi) {
    const ctx = document.getElementById('pieChart').getContext('2d');
    
    if (pieChart) pieChart.destroy();
    
    const total = kpi.titulaire + kpi.stagiaire + kpi.detache_entreprise + kpi.detache_externe + kpi.conge_special;
    
    pieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Titulaires', 'Stagiaires', 'Détachés Entreprise', 'Détachés Externe', 'Congé Spécial'],
            datasets: [{
                data: [
                    kpi.titulaire, 
                    kpi.stagiaire, 
                    kpi.detache_entreprise,
                    kpi.detache_externe,
                    kpi.conge_special
                ],
                backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#6c757d', '#dc3545'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: { font: { size: 11 } }
                },
                tooltip: { 
                    backgroundColor: '#212529'
                },
                datalabels: {
                    display: 'auto',
                    color: 'white',
                    anchor: 'center',
                    align: 'center',
                    offset: 0,
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    formatter: function(value) {
                        const percentage = Math.round((value / total) * 100);
                        return percentage > 5 ? percentage + '%' : '';
                    }
                }
            },
            cutout: '65%'
        }
    });
}

function initLineChart(data) {
    const ctx = document.getElementById('lineChart').getContext('2d');
    
    if (lineChart) lineChart.destroy();
    
    lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.annee),
            datasets: [{
                label: 'Recrutements',
                data: data.map(d => parseInt(d.total)),
                borderColor: '#4361ee',
                backgroundColor: 'rgba(67, 97, 238, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#4361ee',
                pointBorderColor: 'white',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 20
                }
            },
            plugins: {
                tooltip: { 
                    backgroundColor: '#212529'
                },
                datalabels: {
                    display: true,
                    color: '#000000',
                    anchor: 'end',
                    align: 'top',
                    offset: 4,
                    font: {
                        weight: 'bold',
                        size: 10
                    },
                    formatter: function(value) {
                        return value;
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            }
        }
    });
}

function initPyramidChart(data) {
    const ctx = document.getElementById('pyramidChart').getContext('2d');
    
    if (pyramidChart) pyramidChart.destroy();
    
    const maxValue = Math.max(
        ...data.map(d => Math.max(parseInt(d.hommes), parseInt(d.femmes)))
    );
    
    pyramidChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.tranche_age),
            datasets: [
                {
                    label: 'Hommes',
                    data: data.map(d => -Math.abs(parseInt(d.hommes))),
                    backgroundColor: '#4361ee',
                    borderColor: '#3046b8',
                    borderWidth: 1,
                    borderRadius: 5
                },
                {
                    label: 'Femmes',
                    data: data.map(d => parseInt(d.femmes)),
                    backgroundColor: '#ef476f',
                    borderColor: '#d43f62',
                    borderWidth: 1,
                    borderRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            layout: {
                padding: {
                    left: 20,
                    right: 20
                }
            },
            plugins: {
                tooltip: {
                    backgroundColor: '#212529'
                },
                datalabels: {
                    display: true,
                    color: '#000000',
                    anchor: function(context) {
                        return context.dataset.label === 'Hommes' ? 'start' : 'end';
                    },
                    align: function(context) {
                        return context.dataset.label === 'Hommes' ? 'end' : 'start';
                    },
                    offset: 8,
                    font: {
                        weight: 'bold',
                        size: 11
                    },
                    formatter: function(value) {
                        return Math.abs(value);
                    },
                    clamp: true
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        callback: function(value) {
                            return Math.abs(value);
                        }
                    },
                    max: maxValue + 5
                }
            }
        }
    });
}

function initDepartsChart(data) {
    const ctx = document.getElementById('departsChart').getContext('2d');
    
    if (departsChart) departsChart.destroy();
    
    departsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.annee),
            datasets: [
                {
                    label: 'Départs passés',
                    data: data.map(d => parseInt(d.passes)),
                    backgroundColor: '#ef476f',
                    borderColor: '#d43f62',
                    borderWidth: 1,
                    borderRadius: 5
                },
                {
                    label: 'Prévisions départs',
                    data: data.map(d => parseInt(d.futurs)),
                    backgroundColor: '#ffd166',
                    borderColor: '#e5b84c',
                    borderWidth: 1,
                    borderRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: {
                padding: {
                    top: 20
                }
            },
            plugins: {
                tooltip: {
                    backgroundColor: '#212529'
                },
                datalabels: {
                    display: true,
                    color: '#000000',
                    anchor: 'end',
                    align: 'top',
                    offset: 4,
                    font: {
                        weight: 'bold',
                        size: 10
                    },
                    formatter: function(value) {
                        return value;
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            }
        }
    });
}

function initComparatifChart(data) {
    const ctx = document.getElementById('comparatifChart').getContext('2d');
    
    if (comparatifChart) comparatifChart.destroy();
    
    comparatifChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.annee),
            datasets: [
                {
                    label: 'Recrutements',
                    data: data.map(d => parseInt(d.recrutements)),
                    backgroundColor: '#28a745',
                    borderColor: '#218838',
                    borderWidth: 1,
                    borderRadius: 5
                },
                {
                    label: 'Départs',
                    data: data.map(d => parseInt(d.departs)),
                    backgroundColor: '#dc3545',
                    borderColor: '#c82333',
                    borderWidth: 1,
                    borderRadius: 5
                },
                {
                    label: 'Solde',
                    data: data.map(d => parseInt(d.solde)),
                    type: 'line',
                    borderColor: '#ffc107',
                    backgroundColor: 'transparent',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffc107',
                    pointBorderColor: '#212529',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    backgroundColor: '#212529',
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw + ' personnes';
                        }
                    }
                },
                datalabels: {
                    color: '#000',
                    anchor: 'end',
                    align: 'top',
                    offset: 4,
                    font: { weight: 'bold', size: 10 },
                    formatter: function(value, context) {
                        return value;
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    title: {
                        display: true,
                        text: 'Nombre de personnes'
                    }
                },
                y1: {
                    position: 'right',
                    grid: { display: false },
                    title: {
                        display: true,
                        text: 'Solde'
                    }
                }
            }
        }
    });
}

function initRegionTypeChart(data) {
    const ctx = document.getElementById('regionTypeChart').getContext('2d');
    
    if (regionTypeChart) regionTypeChart.destroy();
    
    regionTypeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.region || 'Sans affectation'),
            datasets: [
                {
                    label: '🚌 EC',
                    data: data.map(d => parseInt(d.EC)),
                    backgroundColor: '#4cc9f0',
                    stack: 'Stack 0'
                },
                {
                    label: '💰 ER',
                    data: data.map(d => parseInt(d.ER)),
                    backgroundColor: '#f72585',
                    stack: 'Stack 0'
                },
                {
                    label: '👔 E',
                    data: data.map(d => parseInt(d.E)),
                    backgroundColor: '#7209b7',
                    stack: 'Stack 0'
                },
                {
                    label: '🔧 T',
                    data: data.map(d => parseInt(d.T)),
                    backgroundColor: '#f8961e',
                    stack: 'Stack 0'
                },
                {
                    label: '👔 A',
                    data: data.map(d => parseInt(d.A)),
                    backgroundColor: '#43aa8b',
                    stack: 'Stack 0'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#212529'
                },
                legend: {
                    position: 'top'
                }
            },
            scales: {
                x: {
                    stacked: true,
                    grid: { display: false }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            }
        }
    });
}

function initRegionSituationChart(data) {
    const ctx = document.getElementById('regionSituationChart').getContext('2d');
    
    if (regionSituationChart) regionSituationChart.destroy();
    
    regionSituationChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.region || 'Sans affectation'),
            datasets: [
                {
                    label: '📝 Titulaire',
                    data: data.map(d => parseInt(d.titulaire)),
                    backgroundColor: '#28a745',
                    stack: 'Stack 0'
                },
                {
                    label: '🔰 Stagiaire',
                    data: data.map(d => parseInt(d.stagiaire)),
                    backgroundColor: '#ffc107',
                    stack: 'Stack 0'
                },
                {
                    label: '🤝 Détaché Ent.',
                    data: data.map(d => parseInt(d.detache_entreprise)),
                    backgroundColor: '#17a2b8',
                    stack: 'Stack 0'
                },
                {
                    label: '🌍 Détaché Ext.',
                    data: data.map(d => parseInt(d.detache_externe)),
                    backgroundColor: '#6c757d',
                    stack: 'Stack 0'
                },
                {
                    label: '⚕️ Congé Spécial',
                    data: data.map(d => parseInt(d.conge_special)),
                    backgroundColor: '#dc3545',
                    stack: 'Stack 0'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#212529'
                },
                legend: {
                    position: 'top'
                }
            },
            scales: {
                x: {
                    stacked: true,
                    grid: { display: false }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            }
        }
    });
}

function initClassePieChart(data) {
    const ctx = document.getElementById('classePieChart').getContext('2d');
    
    if (classePieChart) classePieChart.destroy();
    
    classePieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.map(d => d.classe),
            datasets: [{
                data: data.map(d => parseInt(d.total)),
                backgroundColor: ['#9c27b0', '#ff9800', '#2196f3', '#6c757d'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: { 
                    backgroundColor: '#212529',
                    callbacks: {
                        label: function(context) {
                            let total = context.dataset.data.reduce((a,b) => a + b, 0);
                            let percentage = Math.round((context.raw / total) * 100);
                            return `${context.label}: ${context.raw} personnes (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

function initRegionClasseChart(data) {
    const ctx = document.getElementById('regionClasseChart').getContext('2d');
    
    if (regionClasseChart) regionClasseChart.destroy();
    
    regionClasseChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.region || 'Sans affectation'),
            datasets: [
                {
                    label: '&#128084 Cadres',
                    data: data.map(d => parseInt(d.cadres)),
                    backgroundColor: '#9c27b0',
                    stack: 'Stack 0'
                },
                {
                    label: '&#128202 Maîtrise',
                    data: data.map(d => parseInt(d.maitrise)),
                    backgroundColor: '#ff9800',
                    stack: 'Stack 0'
                },
                {
                    label: '&#128295 Exécution',
                    data: data.map(d => parseInt(d.execution)),
                    backgroundColor: '#2196f3',
                    stack: 'Stack 0'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#212529'
                },
                legend: {
                    position: 'top'
                }
            },
            scales: {
                x: {
                    stacked: true,
                    grid: { display: false }
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            }
        }
    });
}

function initNumpersClasseChart(data) {
    const ctx = document.getElementById('numpersClasseChart').getContext('2d');
    
    if (numpersClasseChart) numpersClasseChart.destroy();
    
    numpersClasseChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.type),
            datasets: [
                {
                    label: '&#128084 Cadres',
                    data: data.map(d => parseInt(d.cadres)),
                    backgroundColor: '#9c27b0'
                },
                {
                    label: '&#128202 Maîtrise',
                    data: data.map(d => parseInt(d.maitrise)),
                    backgroundColor: '#ff9800'
                },
                {
                    label: '&#128295 Exécution',
                    data: data.map(d => parseInt(d.execution)),
                    backgroundColor: '#2196f3'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#212529'
                },
                legend: {
                    position: 'top'
                }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            }
        }
    });
}

function initAncienneteCharts() {
    initAnciennetePieChart(initialData.ancienneteGlobale);
    initAncienneteRegionChart(initialData.ancienneteRegion);
    initAncienneteClasseChart(initialData.ancienneteClasse);
    initEvolutionAncienneteChart(initialData.evolutionAnciennete);
}

function initAnciennetePieChart(data) {
    const ctx = document.getElementById('anciennetePieChart').getContext('2d');
    
    if (anciennetePieChart) anciennetePieChart.destroy();
    
    const colors = ['#4cc9f0', '#4361ee', '#7209b7', '#f72585', '#f8961e', '#f9844a', '#ef476f'];
    
    anciennetePieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.map(d => d.tranche_anciennete),
            datasets: [{
                data: data.map(d => parseInt(d.total)),
                backgroundColor: colors.slice(0, data.length),
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'bottom',
                    labels: { font: { size: 10 } }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let total = context.dataset.data.reduce((a,b) => a + b, 0);
                            let percentage = Math.round((context.raw / total) * 100);
                            return `${context.label}: ${context.raw} personnes (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
}

function initAncienneteRegionChart(data) {
    const ctx = document.getElementById('ancienneteRegionChart').getContext('2d');
    
    if (ancienneteRegionChart) ancienneteRegionChart.destroy();
    
    const regions = data.map(d => d.region || 'Sans affectation');
    const valeurs = data.map(d => parseFloat(d.anciennete_moyenne));
    const colors = valeurs.map(v => {
        if (v >= 20) return '#ef476f';
        if (v >= 15) return '#f8961e';
        if (v >= 10) return '#ffd166';
        return '#4cc9f0';
    });
    
    ancienneteRegionChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: regions,
            datasets: [{
                label: 'Ancienneté moyenne (années)',
                data: valeurs,
                backgroundColor: colors,
                borderColor: colors.map(c => c.replace('0.7', '1')),
                borderWidth: 1,
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Ancienneté moyenne: ${context.raw} ans`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: Math.max(...valeurs) + 5,
                    title: {
                        display: true,
                        text: 'Années'
                    }
                }
            }
        }
    });
}

function initAncienneteClasseChart(data) {
    const ctx = document.getElementById('ancienneteClasseChart').getContext('2d');
    
    if (ancienneteClasseChart) ancienneteClasseChart.destroy();
    
    ancienneteClasseChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.map(d => d.classe),
            datasets: [
                {
                    label: 'Ancienneté moyenne',
                    data: data.map(d => parseFloat(d.anciennete_moyenne)),
                    backgroundColor: ['#9c27b0', '#ff9800', '#2196f3'],
                    borderRadius: 5
                },
                {
                    label: 'Anciens (20+ ans)',
                    data: data.map(d => parseInt(d.anciens)),
                    type: 'line',
                    borderColor: '#ef476f',
                    backgroundColor: 'transparent',
                    borderWidth: 3,
                    pointBackgroundColor: '#ef476f',
                    pointBorderColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Ancienneté (ans)'
                    }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    grid: { display: false },
                    title: {
                        display: true,
                        text: "Nombre d'anciens"
                    }
                }
            }
        }
    });
}

function initEvolutionAncienneteChart(data) {
    const ctx = document.getElementById('evolutionAncienneteChart').getContext('2d');
    
    if (evolutionAncienneteChart) evolutionAncienneteChart.destroy();
    
    evolutionAncienneteChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map(d => d.annee_recrutement),
            datasets: [
                {
                    label: 'Ancienneté moyenne actuelle',
                    data: data.map(d => parseFloat(d.anciennete_moyenne_actuelle)),
                    borderColor: '#4361ee',
                    backgroundColor: 'rgba(67, 97, 238, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#4361ee',
                    pointBorderColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    yAxisID: 'y'
                },
                {
                    label: 'Nombre de recrutés',
                    data: data.map(d => parseInt(d.recrutes)),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#28a745',
                    pointBorderColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (context.dataset.label.includes('Ancienneté')) {
                                return label + ': ' + context.raw + ' ans';
                            }
                            return label + ': ' + context.raw + ' personnes';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Ancienneté (années)'
                    }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    grid: { display: false },
                    title: {
                        display: true,
                        text: 'Nombre de recrutements'
                    }
                }
            }
        }
    });
}

function initAbsencesCharts() {
    initAbsencesLineChart(initialData.absencesMensuelles);
    initAbsencesPieChart(initialData.absencesKpi);
    initFormationsLineChart(initialData.formations);
    initAbsencesRegionChart(initialData.absencesRegion);
    initTauxAbsenteismeChart(initialData.tauxAbsenteisme);
}

function initAbsencesLineChart(data) {
    const ctx = document.getElementById('absencesLineChart').getContext('2d');
    
    if (absencesLineChart) absencesLineChart.destroy();
    
    const labels = data.map(d => {
        const mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        return mois[d.mois-1] + ' ' + d.annee;
    });
    
    absencesLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Accidents',
                    data: data.map(d => parseInt(d.accidents)),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Maladies',
                    data: data.map(d => parseInt(d.maladies)),
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Formations',
                    data: data.map(d => parseInt(d.formations)),
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: "Nombre d'absences"
                    }
                }
            }
        }
    });
}

function initAbsencesPieChart(kpi) {
    const ctx = document.getElementById('absencesPieChart').getContext('2d');
    
    if (absencesPieChart) absencesPieChart.destroy();
    
    absencesPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Accidents', 'Maladies', 'Formations'],
            datasets: [{
                data: [
                    kpi.accidents || 0,
                    kpi.maladies || 0,
                    kpi.formations || 0
                ],
                backgroundColor: ['#dc3545', '#ffc107', '#28a745'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let total = context.dataset.data.reduce((a,b) => a + b, 0);
                            let percentage = Math.round((context.raw / total) * 100);
                            return `${context.label}: ${context.raw} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%'
        }
    });
}

function initFormationsLineChart(data) {
    const ctx = document.getElementById('formationsLineChart').getContext('2d');
    
    if (formationsLineChart) formationsLineChart.destroy();
    
    const labels = data.map(d => {
        const mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];
        return mois[d.mois-1] + ' ' + d.annee;
    });
    
    formationsLineChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Nombre de formations',
                    data: data.map(d => parseInt(d.nb_formations)),
                    backgroundColor: '#28a745',
                    yAxisID: 'y'
                },
                {
                    label: 'Participants',
                    data: data.map(d => parseInt(d.participants)),
                    type: 'line',
                    borderColor: '#17a2b8',
                    backgroundColor: 'transparent',
                    borderWidth: 3,
                    pointBackgroundColor: '#17a2b8',
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Nb formations' }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    grid: { display: false },
                    title: { display: true, text: 'Participants' }
                }
            }
        }
    });
}

function initAbsencesRegionChart(data) {
    const ctx = document.getElementById('absencesRegionChart').getContext('2d');
    
    if (absencesRegionChart) absencesRegionChart.destroy();
    
    const regions = data.map(d => d.region || 'Sans affectation');
    const jours = data.map(d => parseInt(d.total_jours));
    
    absencesRegionChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: regions,
            datasets: [{
                label: "Jours d'absence",
                data: jours,
                backgroundColor: jours.map(j => {
                    if (j > 100) return '#dc3545';
                    if (j > 50) return '#ffc107';
                    return '#28a745';
                }),
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Jours' }
                }
            }
        }
    });
}

function initTauxAbsenteismeChart(data) {
    const ctx = document.getElementById('tauxAbsenteismeChart').getContext('2d');
    
    if (tauxAbsenteismeChart) tauxAbsenteismeChart.destroy();
    
    const labels = data.map(d => d.mois);
    const taux = data.map(d => parseFloat(d.taux_absenteisme));
    
    tauxAbsenteismeChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: "Taux d'absentéisme (%)",
                data: taux,
                borderColor: '#4361ee',
                backgroundColor: 'rgba(67, 97, 238, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: taux.map(t => {
                    if (t > 8) return '#dc3545';
                    if (t > 5) return '#ffc107';
                    return '#28a745';
                }),
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Taux: ${context.raw}%`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 15,
                    title: { display: true, text: 'Taux (%)' }
                }
            }
        }
    });
}

function applyFilters() {
    const regions = $('#regionFilter').val() || [];
    const services = $('#serviceFilter').val() || [];
    const years = $('#yearFilter').val() || [];
    const numpers = $('#numpersFilter').val() || [];
    const contrastage = $('#contrastageFilter').val() || [];
    
    $('.loading').show();
    
    $.ajax({
        url: 'get_filtered_data.php',
        method: 'POST',
        data: {
            regions: regions,
            services: services,
            years: years,
            numpers: numpers,
            contrastage: contrastage
        },
        success: function(response) {
            try {
                const data = JSON.parse(response);
                
                $('#totalKPI').text(data.kpi.total);
                $('#titulaireKPI').text(data.kpi.titulaire);
                $('#stagiaireKPI').text(data.kpi.stagiaire);
                $('#detacheKPI').text(data.kpi.detache_entreprise + data.kpi.detache_externe);
                
                initBarChart(data.regions);
                initPieChart(data.kpi);
                initLineChart(data.recruitment);
                initPyramidChart(data.agePyramid);
                if(data.departsCombined) {
                    initDepartsChart(data.departsCombined);
                }
                if(data.comparatif) {
                    initComparatifChart(data.comparatif);
                }
                if(data.regionType) {
                    initRegionTypeChart(data.regionType);
                }
                if(data.regionSituation) {
                    initRegionSituationChart(data.regionSituation);
                }
                if(data.classe) {
                    initClassePieChart(data.classe);
                }
                if(data.regionClasse) {
                    initRegionClasseChart(data.regionClasse);
                }
                if(data.numpersClasse) {
                    initNumpersClasseChart(data.numpersClasse);
                }
                if(data.ancienneteGlobale) {
                    initAnciennetePieChart(data.ancienneteGlobale);
                }
                if(data.ancienneteRegion) {
                    initAncienneteRegionChart(data.ancienneteRegion);
                }
                if(data.ancienneteClasse) {
                    initAncienneteClasseChart(data.ancienneteClasse);
                }
                if(data.evolutionAnciennete) {
                    initEvolutionAncienneteChart(data.evolutionAnciennete);
                }
                
                updatePivotTableData(data.combined);
                updateActiveFilters(regions, services, years, numpers, contrastage);
                animateNumbers();
            } catch(e) {
                console.error('Error parsing response:', e);
                alert('Erreur lors du traitement des données');
            }
            
            $('.loading').hide();
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
            alert('Erreur lors du filtrage des données');
            $('.loading').hide();
        }
    });
}
async function printAllStats() {
    // Afficher un indicateur de chargement
    const loadingMsg = document.createElement('div');
    loadingMsg.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 30px 50px;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        z-index: 9999;
        font-size: 20px;
        border-left: 8px solid #4361ee;
        text-align: center;
    `;
    loadingMsg.innerHTML = `
        <div style="font-size: 40px; margin-bottom: 15px;">📸</div>
        <div>Préparation de tous les graphiques pour impression...</div>
        <div style="font-size: 14px; margin-top: 10px; color: #666;">Cela peut prendre quelques secondes</div>
        <div class="progress mt-3" style="height: 5px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" style="width: 100%"></div>
        </div>
    `;
    document.body.appendChild(loadingMsg);
    
    // Forcer un rafraîchissement des graphiques avant capture
    await new Promise(resolve => setTimeout(resolve, 500));
    
    // Capturer TOUS les graphiques en préservant les originaux
    const allCharts = [
        // Graphiques principaux
        { id: 'barChart', title: 'Répartition par région' },
        { id: 'pieChart', title: 'Répartition par situation administrative' },
        { id: 'lineChart', title: 'Évolution des recrutements' },
        { id: 'pyramidChart', title: 'Pyramide des âges' },
        { id: 'departsChart', title: 'Évolution des départs (passés et prévisions)' },
        { id: 'comparatifChart', title: 'Comparatif Recrutements/Départs (N-5 à N+5)' },
        
        // Graphiques des classes
        { id: 'classePieChart', title: 'Distribution des classes (Cadres/Maîtrise/Exécution)' },
        { id: 'regionClasseChart', title: 'Distribution des classes par région' },
        { id: 'numpersClasseChart', title: 'Distribution des classes par type de personnel' },
        
        // Graphiques régionaux
        { id: 'regionTypeChart', title: 'Distribution des types de personnel par région' },
        { id: 'regionSituationChart', title: 'Distribution des situations par région' },
        
        // Graphiques d'ancienneté
        { id: 'anciennetePieChart', title: 'Distribution par tranche d\'ancienneté' },
        { id: 'ancienneteRegionChart', title: 'Ancienneté moyenne par région' },
        { id: 'ancienneteClasseChart', title: 'Ancienneté par classe' },
        { id: 'evolutionAncienneteChart', title: 'Évolution de l\'ancienneté moyenne' },
        
        // Graphiques d'absences
        { id: 'absencesLineChart', title: 'Évolution mensuelle des absences' },
        { id: 'absencesPieChart', title: 'Répartition des absences par type' },
        { id: 'formationsLineChart', title: 'Évolution des formations' },
        { id: 'absencesRegionChart', title: 'Absences par région' },
        { id: 'tauxAbsenteismeChart', title: "Taux d'absentéisme mensuel" }
    ];
    
    const chartImages = [];
    
    // Capturer chaque graphique en créant une copie temporaire
    for (const chart of allCharts) {
        const originalCanvas = document.getElementById(chart.id);
        if (originalCanvas) {
            try {
                // Créer un canvas temporaire pour la capture
                const tempCanvas = document.createElement('canvas');
                tempCanvas.width = originalCanvas.width;
                tempCanvas.height = originalCanvas.height;
                const tempCtx = tempCanvas.getContext('2d');
                
                // Dessiner le contenu du canvas original sur le canvas temporaire
                tempCtx.drawImage(originalCanvas, 0, 0);
                
                // Capturer depuis le canvas temporaire
                const imageData = tempCanvas.toDataURL('image/png');
                
                chartImages.push({
                    title: chart.title,
                    image: imageData
                });
                
                // Nettoyer
                tempCanvas.remove();
            } catch (e) {
                console.error(`Erreur capture ${chart.id}:`, e);
            }
        }
        // Petite pause pour ne pas surcharger
        await new Promise(resolve => setTimeout(resolve, 50));
    }
    
    // Récupérer TOUS les tableaux
    const tablesToPrint = [
        // Tableaux de base
        { selector: '#contrastage .table-wrapper table', title: '📝 Situation administrative détaillée' },
        { selector: '#numpers .table-wrapper table', title: '👥 Répartition par type de personnel' },
        { selector: '#age-numpers .table-wrapper table', title: '📊 Âge par type de personnel' },
        { selector: '#contrastage-numpers .table-wrapper table', title: '🔄 Situation par type de personnel' },
        
        // Tableaux des classes
        { selector: '#classe-repartition .table-wrapper table', title: '🏆 Répartition par classe' },
        { selector: '#region-classe .table-wrapper table', title: '📍 Répartition région par classe' },
        { selector: '#numpers-classe .table-wrapper table', title: '👔 Type de personnel par classe' },
        
        // Tableaux régionaux
        { selector: '#region-type .table-wrapper table', title: '📍 Répartition région par type' },
        { selector: '#region-situation .table-wrapper table', title: '📍 Répartition région par situation' },
        
        // Tableaux d'ancienneté
        { selector: '#distribution-anciennete .table-wrapper table', title: '⏱️ Distribution par ancienneté' },
        { selector: '#region-anciennete .table-wrapper table', title: '⏱️ Ancienneté par région' },
        { selector: '#type-anciennete .table-wrapper table', title: '⏱️ Ancienneté par type' },
        { selector: '#classe-anciennete .table-wrapper table', title: '⏱️ Ancienneté par classe' },
        
        // Tableaux d'absences
        { selector: '#evolution-absences .table-wrapper table', title: '🚑 Évolution mensuelle des absences' },
        { selector: '#formations .table-wrapper:first-child table', title: '📚 Formations par type de personnel' },
        { selector: '#formations .table-wrapper:last-child table', title: '📚 Détail des formations par mois' },
        { selector: '#top-absents .table-wrapper table', title: '🔝 Top 10 des absents' },
        { selector: '#analyse-absences .table-wrapper:first-child table', title: '📊 Absences par région' },
        { selector: '#analyse-absences .table-wrapper:last-child table', title: '📊 Répartition par durée' },
        
        // Tableaux croisés
        { selector: '.row.mt-4 .col-md-12 .table-wrapper:first-child table', title: '🔄 Tableau croisé régions vs types' },
        { selector: '#pivotTableContainer table', title: '📊 Tableau croisé dynamique' },
        
        // Tableaux statiques
        { selector: '.row .col-md-6:first-child .table-wrapper table', title: '📍 Tableau par région' },
        { selector: '.row .col-md-6:last-child .table-wrapper table', title: '🏢 Tableau par service' }
    ];
    
    // Récupérer les données PHP via des éléments existants
    const getElementText = (selector) => {
        const el = document.querySelector(selector);
        return el ? el.textContent.trim() : '0';
    };
    
    const phpData = {
        totalKPI: $('#totalKPI').text(),
        titulaireKPI: $('#titulaireKPI').text(),
        stagiaireKPI: $('#stagiaireKPI').text(),
        detacheKPI: $('#detacheKPI').text(),
        detacheExt: $('.col-md-2 .card .card-body h2').eq(4).text() || '0',
        congeSpecial: $('.col-md-2 .card .card-body h2').eq(5).text() || '0',
        avgAge: document.querySelector('.stat-card .stat-value') ? document.querySelector('.stat-card .stat-value').textContent : '<?= $avg_age ?>',
        femmes: document.querySelectorAll('.stat-card .stat-value')[1] ? document.querySelectorAll('.stat-card .stat-value')[1].textContent : '<?= $femmes ?> (<?= $pourcentage_f ?>%)',
        hommes: document.querySelectorAll('.stat-card .stat-value')[2] ? document.querySelectorAll('.stat-card .stat-value')[2].textContent : '<?= $hommes ?> (<?= $pourcentage_h ?>%)',
        ancienneteMoyenne: document.querySelector('.anciennete-card .value') ? document.querySelector('.anciennete-card .value').textContent : '<?= $stats_anciennete["anciennete_moyenne"] ?>',
        ancienneteMax: document.querySelectorAll('.anciennete-card .value')[1] ? document.querySelectorAll('.anciennete-card .value')[1].textContent : '<?= $stats_anciennete["anciennete_max"] ?>',
        total20Plus: document.querySelectorAll('.anciennete-card .value')[2] ? document.querySelectorAll('.anciennete-card .value')[2].textContent : '<?= $stats_anciennete["total_20_plus"] ?>',
        total30Plus: document.querySelectorAll('.anciennete-card .value')[3] ? document.querySelectorAll('.anciennete-card .value')[3].textContent : '<?= $stats_anciennete["total_30_plus"] ?>',
        absencesTotal: document.querySelectorAll('.absence-card .value')[0] ? document.querySelectorAll('.absence-card .value')[0].textContent : '<?= $absences_kpi["total_absences"] ?? 0 ?>',
        absencesJours: document.querySelectorAll('.absence-card .value')[5] ? document.querySelectorAll('.absence-card .value')[5].textContent : '<?= $absences_kpi["total_jours"] ?? 0 ?>',
        formations: document.querySelectorAll('.absence-card .value')[1] ? document.querySelectorAll('.absence-card .value')[1].textContent : '<?= $absences_kpi["formations"] ?? 0 ?>',
        maladies: document.querySelectorAll('.absence-card .value')[2] ? document.querySelectorAll('.absence-card .value')[2].textContent : '<?= $absences_kpi["maladies"] ?? 0 ?>',
        accidents: document.querySelectorAll('.absence-card .value')[3] ? document.querySelectorAll('.absence-card .value')[3].textContent : '<?= $absences_kpi["accidents"] ?? 0 ?>'
    };
    
    // Ouvrir une nouvelle fenêtre
    const printWindow = window.open('', '_blank');
    const date = new Date();
    const dateFormatee = date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    // Générer le HTML complet
    let printContent = `
    <!DOCTYPE html>
    <html>
    <head>
        <title>Dashboard RH - Rapport Complet</title>
        <meta charset="UTF-8">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                padding: 30px;
                background: white;
                font-size: 12px;
            }
            .cover-page {
                text-align: center;
                padding: 50px;
                margin-bottom: 30px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                border-radius: 20px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .cover-page h1 {
                font-size: 48px;
                margin-bottom: 20px;
            }
            .cover-page .date {
                font-size: 18px;
                opacity: 0.9;
            }
            .section-title {
                font-size: 24px;
                color: #4361ee;
                margin: 40px 0 20px;
                border-bottom: 3px solid #4361ee;
                padding-bottom: 10px;
            }
            .subsection-title {
                font-size: 18px;
                color: #495057;
                margin: 30px 0 15px;
                border-bottom: 2px solid #dee2e6;
                padding-bottom: 5px;
            }
            .kpi-grid {
                display: grid;
                grid-template-columns: repeat(6, 1fr);
                gap: 15px;
                margin-bottom: 30px;
            }
            .kpi-item {
                background: #4361ee;
                color: white;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .kpi-item h3 {
                margin: 0;
                font-size: 28px;
                font-weight: bold;
            }
            .kpi-item p {
                margin: 5px 0 0;
                font-size: 13px;
                opacity: 0.9;
            }
            .stats-card {
                background: #f8f9fa;
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 20px;
                border-left: 5px solid #4361ee;
            }
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 15px;
                margin-bottom: 30px;
            }
            .stats-item {
                background: white;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 15px;
                text-align: center;
            }
            .chart-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 20px;
                margin-bottom: 30px;
                page-break-inside: avoid;
            }
            .chart-item {
                background: white;
                border: 1px solid #dee2e6;
                border-radius: 10px;
                padding: 15px;
                break-inside: avoid;
                page-break-inside: avoid;
            }
            .chart-item h3 {
                font-size: 14px;
                margin: 0 0 15px;
                color: #495057;
                text-align: center;
            }
            .chart-image {
                width: 100%;
                height: auto;
                max-height: 250px;
                object-fit: contain;
            }
            .table-section {
                margin-bottom: 30px;
                page-break-inside: avoid;
                break-inside: avoid;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
                font-size: 11px;
            }
            th {
                background: #343a40;
                color: white;
                padding: 8px;
                text-align: left;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            td {
                padding: 6px 8px;
                border: 1px solid #dee2e6;
            }
            .badge {
                padding: 3px 8px;
                border-radius: 12px;
                font-size: 10px;
                font-weight: 600;
                display: inline-block;
            }
            .badge-success { background: #28a745; color: white; }
            .badge-warning { background: #ffc107; color: #212529; }
            .badge-info { background: #17a2b8; color: white; }
            .badge-danger { background: #dc3545; color: white; }
            .badge-cadre { background: #9c27b0; color: white; }
            .badge-maitrise { background: #ff9800; color: white; }
            .badge-execution { background: #2196f3; color: white; }
            .footer {
                text-align: center;
                margin-top: 50px;
                padding-top: 20px;
                border-top: 1px solid #dee2e6;
                font-size: 11px;
                color: #666;
            }
            .page-break {
                page-break-before: always;
                break-before: page;
            }
            .no-break {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            @media print {
                body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            }
        </style>
    </head>
    <body>
        <!-- Page de couverture -->
        <div class="cover-page">
            <h1>📊 RAPPORT RH COMPLET</h1>
            <h2>Situation Administrative du Personnel</h2>
            <div class="date">Généré le : ${dateFormatee}</div>
        </div>
        
        <!-- Résumé exécutif -->
        <div class="stats-card">
            <h3>📋 RÉSUMÉ EXÉCUTIF</h3>
            <p>Effectif total : <strong>${phpData.totalKPI}</strong> agents</p>
            <p>Répartition : ${phpData.titulaireKPI} titulaires, ${phpData.stagiaireKPI} stagiaires, ${phpData.detacheKPI} détachés</p>
            <p>Âge moyen : ${phpData.avgAge} ans | F/H : ${phpData.femmes} / ${phpData.hommes}</p>
            <p>Ancienneté moyenne : ${phpData.ancienneteMoyenne} ans</p>
            <p>Absences (12 mois) : ${phpData.absencesTotal} absences (${phpData.absencesJours} jours)</p>
        </div>
        
        <!-- KPIs -->
        <h2 class="section-title">📈 INDICATEURS CLÉS</h2>
        <div class="kpi-grid">
            <div class="kpi-item" style="background: #4361ee;">
                <h3>${phpData.totalKPI}</h3>
                <p>Total Personnel</p>
            </div>
            <div class="kpi-item" style="background: #28a745;">
                <h3>${phpData.titulaireKPI}</h3>
                <p>Titulaires</p>
            </div>
            <div class="kpi-item" style="background: #ffc107; color: #212529;">
                <h3>${phpData.stagiaireKPI}</h3>
                <p>Stagiaires</p>
            </div>
            <div class="kpi-item" style="background: #17a2b8;">
                <h3>${phpData.detacheKPI}</h3>
                <p>Détachés</p>
            </div>
            <div class="kpi-item" style="background: #6c757d;">
                <h3>${phpData.detacheExt}</h3>
                <p>Détachés Ext.</p>
            </div>
            <div class="kpi-item" style="background: #dc3545;">
                <h3>${phpData.congeSpecial}</h3>
                <p>Congé Spécial</p>
            </div>
        </div>
    `;
    
    // Ajouter les graphiques par lots de 6
    const chartGroups = [
        { charts: chartImages.slice(0, 6), title: 'ANALYSE GRAPHIQUE - PARTIE 1' },
        { charts: chartImages.slice(6, 12), title: 'ANALYSE GRAPHIQUE - PARTIE 2' },
        { charts: chartImages.slice(12, 18), title: 'ANALYSE GRAPHIQUE - PARTIE 3' },
        { charts: chartImages.slice(18), title: 'ANALYSE GRAPHIQUE - PARTIE 4' }
    ];
    
    chartGroups.forEach((group, groupIndex) => {
        if (group.charts.length > 0) {
            if (groupIndex > 0) printContent += `<div class="page-break"></div>`;
            printContent += `<h2 class="section-title">📊 ${group.title}</h2>`;
            printContent += `<div class="chart-grid">`;
            
            group.charts.forEach((chart, index) => {
                if (chart && chart.image) {
                    printContent += `
                        <div class="chart-item">
                            <h3>${chart.title}</h3>
                            <img class="chart-image" src="${chart.image}" alt="${chart.title}">
                        </div>
                    `;
                }
                if ((index + 1) % 2 === 0 && index < group.charts.length - 1) {
                    printContent += `</div><div class="chart-grid">`;
                }
            });
            printContent += `</div>`;
        }
    });
    
    // Ajouter les tableaux
    printContent += `<h2 class="section-title page-break">📋 TABLEAUX STATISTIQUES</h2>`;
    
    // Fonction pour récupérer le HTML d'un tableau
    const getTableHTML = (selector) => {
        try {
            const table = document.querySelector(selector);
            if (!table) return null;
            
            // Cloner pour éviter de modifier l'original
            const clone = table.cloneNode(true);
            return clone.outerHTML;
        } catch (e) {
            console.error('Erreur récupération tableau:', selector, e);
            return null;
        }
    };
    
    // Ajouter tous les tableaux
    tablesToPrint.forEach((table, index) => {
        try {
            const tableHTML = getTableHTML(table.selector);
            if (tableHTML && tableHTML.includes('<table')) {
                printContent += `
                    <div class="table-section">
                        <h3 class="subsection-title">${table.title}</h3>
                        ${tableHTML}
                    </div>
                `;
                
                // Ajouter un saut de page tous les 3 tableaux
                if ((index + 1) % 3 === 0 && index < tablesToPrint.length - 1) {
                    printContent += `<div class="page-break"></div>`;
                }
            }
        } catch (e) {
            console.error('Erreur traitement tableau:', table.selector, e);
        }
    });
    
    // Pied de page
    printContent += `
        <div class="footer">
            <p>Rapport RH complet - Généré le ${dateFormatee}</p>
            <p>Ce document contient l'intégralité des statistiques et graphiques du dashboard RH</p>
        </div>
    </body>
    </html>
    `;
    
    // Écrire dans la nouvelle fenêtre
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // Attendre le chargement puis imprimer
    printWindow.onload = function() {
        setTimeout(() => {
            printWindow.print();
        }, 2000);
    };
    
    // Retirer le message de chargement
    setTimeout(() => {
        document.body.removeChild(loadingMsg);
    }, 1000);
}


function updatePivotTableData(data) {
    let html = `<table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>affectation</th>
                <th>Titulaire</th>
                <th>Stagiaire</th>
                <th>Détaché Ent.</th>
                <th>Détaché Ext.</th>
                <th>Congé Spécial</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>`;
    
    let t_titulaire = 0, t_stagiaire = 0, t_detache_ent = 0, t_detache_ext = 0, t_conge = 0, t_total = 0;
    
    if (data && data.length > 0) {
        data.forEach(row => {
            t_titulaire += parseInt(row.titulaire) || 0;
            t_stagiaire += parseInt(row.stagiaire) || 0;
            t_detache_ent += parseInt(row.detache_entreprise) || 0;
            t_detache_ext += parseInt(row.detache_externe) || 0;
            t_conge += parseInt(row.conge_special) || 0;
            t_total += parseInt(row.total) || 0;
            
            html += `<tr>
                <td>${row.region || 'Sans affectation'}</td>
                <td class="text-success">${row.titulaire || 0}</td>
                <td class="text-warning">${row.stagiaire || 0}</td>
                <td class="text-info">${row.detache_entreprise || 0}</td>
                <td class="text-secondary">${row.detache_externe || 0}</td>
                <td class="text-danger">${row.conge_special || 0}</td>
                <td><strong>${row.total || 0}</strong></td>
            </tr>`;
        });
    }
    
    html += `</tbody>
        <tfoot class="table-primary">
            <tr>
                <th>Total Général</th>
                <th id="totalTitulaire">${t_titulaire}</th>
                <th id="totalStagiaire">${t_stagiaire}</th>
                <th id="totalDetacheEnt">${t_detache_ent}</th>
                <th id="totalDetacheExt">${t_detache_ext}</th>
                <th id="totalConge">${t_conge}</th>
                <th id="totalGeneral">${t_total}</th>
            </tr>
        </tfoot>
    </table>`;
    
    $('#pivotTableContainer').html(html);
}

function updatePivotTable() {
    const row = $('#pivotRow').val();
    const col = $('#pivotCol').val();
    
    $.ajax({
        url: 'get_pivot_data.php',
        method: 'POST',
        data: { row: row, col: col },
        success: function(response) {
            $('#pivotTableContainer').html(response);
        },
        error: function() {
            alert('Erreur lors de la mise à jour du tableau pivot');
        }
    });
}

function updateActiveFilters(regions, services, years, numpers, contrastage) {
    let html = '<div class="badge-filter-group">';
    
    if (regions.length > 0) {
        html += `<span class="badge-filter filter-active">affectations: ${regions.length} sélectionnée(s)</span>`;
    }
    if (services.length > 0) {
        html += `<span class="badge-filter filter-active">Services: ${services.length} sélectionné(s)</span>`;
    }
    if (years.length > 0) {
        html += `<span class="badge-filter filter-active">Années: ${years.length} sélectionnée(s)</span>`;
    }
    if (numpers.length > 0) {
        html += `<span class="badge-filter filter-active">Types: ${numpers.length} sélectionné(s)</span>`;
    }
    if (contrastage.length > 0) {
        html += `<span class="badge-filter filter-active">Situations: ${contrastage.length} sélectionnée(s)</span>`;
    }
    
    if (regions.length === 0 && services.length === 0 && years.length === 0 && numpers.length === 0 && contrastage.length === 0) {
        html += '<span class="badge-filter">Aucun filtre actif</span>';
    }
    
    html += '</div>';
    $('#activeFilters').html(html);
}

function resetFilters() {
    $('#regionFilter').val(null).trigger('change');
    $('#serviceFilter').val(null).trigger('change');
    $('#yearFilter').val(null).trigger('change');
    $('#numpersFilter').val(null).trigger('change');
    $('#contrastageFilter').val(null).trigger('change');
    applyFilters();
}

async function exportToZIP() {
    // Afficher un indicateur de chargement
    const loadingMsg = document.createElement('div');
    loadingMsg.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 30px 50px;
        border-radius: 15px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        z-index: 9999;
        font-size: 20px;
        border-left: 8px solid #28a745;
        text-align: center;
    `;
    loadingMsg.innerHTML = `
        <div style="font-size: 40px; margin-bottom: 15px;">📦</div>
        <div>Préparation du package ZIP...</div>
        <div style="font-size: 14px; margin-top: 10px; color: #666;">Export des données et des graphiques</div>
        <div class="progress mt-3" style="height: 20px; width: 300px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="exportProgress" style="width: 0%">0%</div>
        </div>
    `;
    document.body.appendChild(loadingMsg);
    
    const updateProgress = (percent, message) => {
        const progressBar = document.getElementById('exportProgress');
        if (progressBar) {
            progressBar.style.width = percent + '%';
            progressBar.textContent = percent + '%';
        }
        if (message) {
            loadingMsg.innerHTML = `
                <div style="font-size: 40px; margin-bottom: 15px;">📦</div>
                <div>${message}</div>
                <div class="progress mt-3" style="height: 20px; width: 300px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" id="exportProgress" style="width: ${percent}%">${percent}%</div>
                </div>
            `;
        }
    };

    try {
        const zip = new JSZip();
        const date = new Date();
        const dateFormatee = date.toISOString().slice(0,10).replace(/-/g, '');
        const heureFormatee = date.getHours().toString().padStart(2,'0') + '-' + date.getMinutes().toString().padStart(2,'0');
        const zipFileName = `Dashboard_RH_Complet_${dateFormatee}_${heureFormatee}.zip`;
        
        updateProgress(5, 'Création du fichier Excel...');
        
        // ==================== 1. CRÉATION DU FICHIER EXCEL ====================
        const wb = XLSX.utils.book_new();
        
        // Feuille 1: KPIs et Statistiques globales
        const kpiData = [
            ['INDICATEURS PRINCIPAUX', '', ''],
            ['Indicateur', 'Valeur', 'Pourcentage'],
            ['Total Personnel', document.getElementById('totalKPI')?.textContent || '0', '100%'],
            ['Titulaires', document.getElementById('titulaireKPI')?.textContent || '0', 
                Math.round((parseInt(document.getElementById('titulaireKPI')?.textContent || 0) / parseInt(document.getElementById('totalKPI')?.textContent || 1)) * 100) + '%'],
            ['Stagiaires', document.getElementById('stagiaireKPI')?.textContent || '0', 
                Math.round((parseInt(document.getElementById('stagiaireKPI')?.textContent || 0) / parseInt(document.getElementById('totalKPI')?.textContent || 1)) * 100) + '%'],
            ['Détachés Entreprise', document.querySelectorAll('.kpi-card .card-body h2')[3]?.textContent || '0', 
                Math.round((parseInt(document.querySelectorAll('.kpi-card .card-body h2')[3]?.textContent || 0) / parseInt(document.getElementById('totalKPI')?.textContent || 1)) * 100) + '%'],
            ['Détachés Externe', document.querySelectorAll('.kpi-card .card-body h2')[4]?.textContent || '0', 
                Math.round((parseInt(document.querySelectorAll('.kpi-card .card-body h2')[4]?.textContent || 0) / parseInt(document.getElementById('totalKPI')?.textContent || 1)) * 100) + '%'],
            ['Congé Spécial', document.querySelectorAll('.kpi-card .card-body h2')[5]?.textContent || '0', 
                Math.round((parseInt(document.querySelectorAll('.kpi-card .card-body h2')[5]?.textContent || 0) / parseInt(document.getElementById('totalKPI')?.textContent || 1)) * 100) + '%'],
            
            [],
            ['STATISTIQUES DÉMOGRAPHIQUES'],
            ['Âge moyen', document.querySelector('.stat-card .stat-value')?.textContent || 'N/A'],
            ['Effectif féminin', document.querySelectorAll('.stat-card .stat-value')[1]?.textContent || 'N/A'],
            ['Effectif masculin', document.querySelectorAll('.stat-card .stat-value')[2]?.textContent || 'N/A'],
            
            [],
            ['STATISTIQUES ANCIENNETÉ'],
            ['Ancienneté moyenne', document.querySelector('.anciennete-card .value')?.textContent || 'N/A'],
            ['Ancienneté maximale', document.querySelectorAll('.anciennete-card .value')[1]?.textContent || 'N/A'],
            ['20+ ans d\'ancienneté', document.querySelectorAll('.anciennete-card .value')[2]?.textContent || '0'],
            ['30+ ans d\'ancienneté', document.querySelectorAll('.anciennete-card .value')[3]?.textContent || '0'],
            
            [],
            ['STATISTIQUES ABSENCES'],
            ['Total absences', document.querySelectorAll('.absence-card .value')[0]?.textContent || '0'],
            ['Total jours', document.querySelectorAll('.absence-card .value')[5]?.textContent || '0'],
            ['Formations', document.querySelectorAll('.absence-card .value')[1]?.textContent || '0'],
            ['Maladies', document.querySelectorAll('.absence-card .value')[2]?.textContent || '0'],
            ['Accidents', document.querySelectorAll('.absence-card .value')[3]?.textContent || '0']
        ];
        
        const ws_kpi = XLSX.utils.aoa_to_sheet(kpiData);
        XLSX.utils.book_append_sheet(wb, ws_kpi, 'KPIs_Global');
        
        updateProgress(10, 'Export des tableaux...');
        
        // Fonction pour capturer les tableaux
        const captureTable = (selector, sheetName) => {
            const table = document.querySelector(selector);
            if (table) {
                try {
                    const ws = XLSX.utils.table_to_sheet(table);
                    XLSX.utils.book_append_sheet(wb, ws, sheetName);
                } catch (e) {
                    console.error(`Erreur capture tableau ${sheetName}:`, e);
                }
            }
        };
        
        // Capturer tous les tableaux importants
        const tables = [
            { selector: '#contrastage .table-wrapper table', name: 'Situation_Admin' },
            { selector: '#numpers .table-wrapper table', name: 'Type_Personnel' },
            { selector: '#age-numpers .table-wrapper table', name: 'Age_Type' },
            { selector: '#contrastage-numpers .table-wrapper table', name: 'Situation_Type' },
            { selector: '#classe-repartition .table-wrapper table', name: 'Repartition_Classe' },
            { selector: '#region-classe .table-wrapper table', name: 'Region_Classe' },
            { selector: '#numpers-classe .table-wrapper table', name: 'Type_Classe' },
            { selector: '#region-type .table-wrapper table', name: 'Region_Type' },
            { selector: '#region-situation .table-wrapper table', name: 'Region_Situation' },
            { selector: '#distribution-anciennete .table-wrapper table', name: 'Anciennete' },
            { selector: '#region-anciennete .table-wrapper table', name: 'Anc_Region' },
            { selector: '#type-anciennete .table-wrapper table', name: 'Anc_Type' },
            { selector: '#evolution-absences .table-wrapper table', name: 'Absences_Mensuelles' },
            { selector: '#top-absents .table-wrapper table', name: 'Top_Absents' },
            { selector: '#pivotTableContainer table', name: 'Tableau_Croise' },
            { selector: '.row .col-md-6:first-child .table-wrapper table', name: 'Tableau_Region' },
            { selector: '.row .col-md-6:last-child .table-wrapper table', name: 'Tableau_Service' }
        ];
        
        tables.forEach((table, index) => {
            captureTable(table.selector, table.name);
            updateProgress(10 + Math.round((index + 1) * 30 / tables.length), 'Export des tableaux...');
        });
        
        // Données PHP supplémentaires
        const phpData = [
            ['DONNÉES PHP', ''],
            ['Année courante', '<?= $annee_courante ?>'],
            ['Femmes total', '<?= $femmes ?>'],
            ['Hommes total', '<?= $hommes ?>'],
            ['Pourcentage femmes', '<?= $pourcentage_f ?>%'],
            ['Pourcentage hommes', '<?= $pourcentage_h ?>%']
        ];
        
        const ws_php = XLSX.utils.aoa_to_sheet(phpData);
        XLSX.utils.book_append_sheet(wb, ws_php, 'Donnees_Systeme');
        
        // Générer le buffer Excel
        const excelBuffer = XLSX.write(wb, { bookType: 'xlsx', type: 'array' });
        zip.file(`Données_RH_${dateFormatee}.xlsx`, excelBuffer);
        
        updateProgress(50, 'Capture des graphiques...');
        
        // ==================== 2. CAPTURE DES GRAPHIQUES ====================
        const imgFolder = zip.folder('graphiques');
        
        const charts = [
            { id: 'barChart', name: '01_repartition_region' },
            { id: 'pieChart', name: '02_situation_administrative' },
            { id: 'lineChart', name: '03_evolution_recrutements' },
            { id: 'pyramidChart', name: '04_pyramide_ages' },
            { id: 'departsChart', name: '05_evolution_departs' },
            { id: 'comparatifChart', name: '06_comparatif_recrutements_departs' },
            { id: 'classePieChart', name: '07_distribution_classes' },
            { id: 'regionClasseChart', name: '08_region_classes' },
            { id: 'numpersClasseChart', name: '09_type_classes' },
            { id: 'regionTypeChart', name: '10_region_type_personnel' },
            { id: 'regionSituationChart', name: '11_region_situation' },
            { id: 'anciennetePieChart', name: '12_distribution_anciennete' },
            { id: 'ancienneteRegionChart', name: '13_anciennete_region' },
            { id: 'ancienneteClasseChart', name: '14_anciennete_classe' },
            { id: 'evolutionAncienneteChart', name: '15_evolution_anciennete' },
            { id: 'absencesLineChart', name: '16_evolution_absences' },
            { id: 'absencesPieChart', name: '17_repartition_absences' },
            { id: 'formationsLineChart', name: '18_evolution_formations' },
            { id: 'absencesRegionChart', name: '19_absences_region' },
            { id: 'tauxAbsenteismeChart', name: '20_taux_absenteisme' }
        ];
        
        for (let i = 0; i < charts.length; i++) {
            const chart = charts[i];
            const canvas = document.getElementById(chart.id);
            
            if (canvas) {
                try {
                    // Créer un canvas temporaire pour la capture
                    const tempCanvas = document.createElement('canvas');
                    tempCanvas.width = canvas.width;
                    tempCanvas.height = canvas.height;
                    const tempCtx = tempCanvas.getContext('2d');
                    
                    // Dessiner le contenu du canvas original
                    tempCtx.drawImage(canvas, 0, 0);
                    
                    // Convertir en base64
                    const imgData = tempCanvas.toDataURL('image/png').split(',')[1];
                    imgFolder.file(`${chart.name}.png`, imgData, { base64: true });
                    
                    // Nettoyer
                    tempCanvas.remove();
                } catch (e) {
                    console.error(`Erreur capture ${chart.id}:`, e);
                    // Ajouter un fichier placeholder en cas d'erreur
                    imgFolder.file(`${chart.name}.txt`, `Erreur de capture pour ${chart.name}: ${e.message}`);
                }
            }
            
            updateProgress(50 + Math.round((i + 1) * 40 / charts.length), `Capture des graphiques (${i+1}/${charts.length})...`);
            await new Promise(resolve => setTimeout(resolve, 50));
        }
        
        updateProgress(95, 'Génération du README...');
        
        // ==================== 3. FICHIER README ====================
        const readme = `RAPPORT RH COMPLET - DASHBOARD INTERACTIF
=====================================

Date de génération : ${date.toLocaleDateString('fr-FR')} à ${date.toLocaleTimeString('fr-FR')}

CONTENU DU PACKAGE :
-------------------
1. 📊 Fichier Excel : Données_RH_${dateFormatee}.xlsx
   - Toutes les données statistiques du dashboard
   - 20+ feuilles de calcul avec les tableaux détaillés
   - KPIs, répartitions, analyses croisées

2. 📈 Dossier "graphiques" : ${charts.length} images PNG
   ${charts.map(c => `   - ${c.name}.png`).join('\n')}

STATISTIQUES RÉSUMÉES :
----------------------
- Effectif total : ${document.getElementById('totalKPI')?.textContent || 'N/A'} agents
- Titulaires : ${document.getElementById('titulaireKPI')?.textContent || 'N/A'}
- Stagiaires : ${document.getElementById('stagiaireKPI')?.textContent || 'N/A'}
- Âge moyen : ${document.querySelector('.stat-card .stat-value')?.textContent || 'N/A'}
- Ancienneté moyenne : ${document.querySelector('.anciennete-card .value')?.textContent || 'N/A'}

UTILISATION :
------------
1. Ouvrez le fichier Excel pour analyser les données chiffrées
2. Consultez les images PNG dans le dossier "graphiques" pour visualiser les graphiques
3. Les images sont numérotées pour correspondre à l'ordre du dashboard

Généré automatiquement par le Dashboard RH - Tous droits réservés.
`;
        
        zip.file('README.txt', readme);
        
        // ==================== 4. FICHIER HTML DE PRÉSENTATION ====================
        const htmlPresentation = `<!DOCTYPE html>
<html>
<head>
    <title>Dashboard RH - Package Export</title>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', sans-serif; padding: 40px; background: #f8f9fa; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 15px; margin-bottom: 30px; }
        .section { background: white; padding: 25px; border-radius: 10px; margin-bottom: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        h2 { color: #4361ee; border-bottom: 2px solid #dee2e6; padding-bottom: 10px; }
        .chart-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-top: 20px; }
        .chart-item { background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; }
        .chart-item img { max-width: 100%; height: auto; border: 1px solid #dee2e6; border-radius: 5px; }
        .badge { background: #28a745; color: white; padding: 5px 10px; border-radius: 20px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Dashboard RH - Package d'Export</h1>
            <p>Généré le ${date.toLocaleDateString('fr-FR')}</p>
        </div>
        
        <div class="section">
            <h2>📋 Contenu du package</h2>
            <ul>
                <li><strong>📊 Fichier Excel</strong> : Données_RH_${dateFormatee}.xlsx - Toutes les données statistiques</li>
                <li><strong>📈 ${charts.length} graphiques</strong> dans le dossier "graphiques/"</li>
                <li><strong>📄 README.txt</strong> - Ce fichier d'information</li>
            </ul>
        </div>
        
        <div class="section">
            <h2>📈 Aperçu des graphiques</h2>
            <div class="chart-grid">
                ${charts.slice(0, 9).map(c => `
                    <div class="chart-item">
                        <h4>${c.name.replace(/_/g, ' ')}</h4>
                        <img src="graphiques/${c.name}.png" alt="${c.name}" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAiIGhlaWdodD0iMTAwIiB2aWV3Qm94PSIwIDAgMjQgMjQiIGZpbGw9Im5vbmUiIHN0cm9rZT0iI2RjMzU0NSIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiPjxjaXJjbGUgY3g9IjEyIiBjeT0iMTIiIHI9IjEwIi8+PGxpbmUgeDE9IjE4IiB5MT0iNiIgeDI9IjYiIHkyPSIxOCIvPjxsaW5lIHgxPSI2IiB5MT0iNiIgeDI9IjE4IiB5Mj0iMTgiLz48L3N2Zz4='; this.style.opacity='0.5';">
                    </div>
                `).join('')}
            </div>
            <p style="text-align: center; margin-top: 20px;">
                <span class="badge">${charts.length} graphiques au total</span>
            </p>
        </div>
        
        <div class="section">
            <h2>📊 Comment utiliser ce package</h2>
            <ol>
                <li><strong>Analyse des données</strong> : Ouvrez le fichier Excel pour explorer toutes les données chiffrées (20+ feuilles de calcul)</li>
                <li><strong>Visualisation</strong> : Consultez les images PNG dans le dossier "graphiques" pour voir tous les graphiques du dashboard</li>
                <li><strong>Présentation</strong> : Utilisez ce fichier HTML pour une vue d'ensemble du package</li>
            </ol>
        </div>
    </div>
</body>
</html>`;
        
        zip.file('index.html', htmlPresentation);
        
        updateProgress(100, 'Finalisation...');
        await new Promise(resolve => setTimeout(resolve, 500));
        
        // ==================== 5. TÉLÉCHARGEMENT ====================
        const content = await zip.generateAsync({ 
            type: 'blob',
            compression: 'DEFLATE',
            compressionOptions: { level: 6 }
        });
        
        saveAs(content, zipFileName);
        
        // Message de succès
        loadingMsg.innerHTML = `
            <div style="font-size: 60px; margin-bottom: 15px; color: #28a745;">✅</div>
            <div style="font-size: 24px; font-weight: bold;">Export terminé !</div>
            <div style="margin: 15px 0;">Package ZIP créé avec succès</div>
            <div style="font-size: 14px; color: #666;">${charts.length} graphiques + 1 fichier Excel</div>
            <div style="font-size: 14px; color: #666;">${zipFileName}</div>
            <button onclick="this.parentElement.parentElement.remove()" style="margin-top: 20px; padding: 10px 30px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">Fermer</button>
        `;
        
        setTimeout(() => {
            if (document.body.contains(loadingMsg)) {
                document.body.removeChild(loadingMsg);
            }
        }, 5000);
        
    } catch (error) {
        console.error('Erreur lors de l\'export:', error);
        loadingMsg.innerHTML = `
            <div style="font-size: 60px; margin-bottom: 15px; color: #dc3545;">❌</div>
            <div style="font-size: 20px; font-weight: bold;">Erreur lors de l'export</div>
            <div style="margin: 15px 0; color: #666;">${error.message}</div>
            <button onclick="this.parentElement.parentElement.remove()" style="margin-top: 20px; padding: 10px 30px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;">Fermer</button>
        `;
    }
}

// Remplacer l'appel du bouton existant
function exportToExcel() {
    exportToZIP();
}

// Ajouter aussi un bouton dédié pour l'export ZIP
function exportToZIPOnly() {
    exportToZIP();
}

// Auto-refresh every 60 minutes
setInterval(applyFilters, 18000000);
</script>

</body>
</html>