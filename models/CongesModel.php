<?php
// models/CongesModel.php
require_once __DIR__ . '/../config/Database.php';

class CongesModel {
    private $db;
    
    // Types de congés
    const TYPE_EXCEPTIONNEL = 0;
    const TYPE_FORMATION = 1;
    const TYPE_RTT = 2;
    const TYPE_MISSION = 3;
    const TYPE_MALADIE = 5;
    
    // Mapping des types
    const TYPE_MAPPING = [
        self::TYPE_EXCEPTIONNEL => [
            'name' => 'الرخص الإستثنائيّة',
            'subtitle' => 'نظام متابعة الرخص الإستثنائية للموظفين',
            'icon' => 'fa-calendar-alt',
            'color' => 'primary',
            'type2_value' => 0
        ],
        self::TYPE_FORMATION => [
            'name' => 'حلقات التكوين',
            'subtitle' => 'نظام متابعة حلقات التكوين والدورات التدريبية',
            'icon' => 'fa-chalkboard-user',
            'color' => 'info',
            'type2_value' => 1
        ],
        self::TYPE_RTT => [
            'name' => 'الرّاحات التعويضيّة',
            'subtitle' => 'نظام متابعة الراحات التعويضية (RTT) للموظفين',
            'icon' => 'fa-clock',
            'color' => 'rtt',
            'type2_value' => null
        ],
        self::TYPE_MISSION => [
            'name' => 'المهام',
            'subtitle' => 'نظام متابعة وتتبع المهام والأعمال',
            'icon' => 'fa-briefcase',
            'color' => 'warning',
            'type2_value' => 3
        ],
        self::TYPE_MALADIE => [
            'name' => 'الرّخص المرضيّة',
            'subtitle' => 'نظام متكامل لمتابعة وإدارة الرخص المرضية للموظفين',
            'icon' => 'fa-heartbeat',
            'color' => 'medical',
            'type2_value' => null
        ]
    ];
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Récupère la requête SQL en fonction du type et des départements
     */
    public function getCongesQuery($type, $departements, $isAdmin = false) {
        $typeInfo = self::TYPE_MAPPING[$type];
        
        // Sélection des colonnes selon le type
        if ($type == self::TYPE_MALADIE) {
            $columns = "
                autreconge.mecano, 
                nom, 
                datedebut, 
                datefin, 
                (DATEDIFF(datefin, datedebut) + 1) as nbj,
                dep.depar, 
                anne
            ";
        } elseif ($type == self::TYPE_RTT) {
            $columns = "
                autreconge.mecano, 
                nom, 
                datedebut, 
                datefin, 
                dep.depar, 
                commentaire, 
                anne
            ";
        } else {
            $columns = "
                autreconge.mecano, 
                nom, 
                datedebut, 
                datefin, 
                nbj,
                dep.depar, 
                commentaire, 
                anne
            ";
        }
        
        // Construction de la clause WHERE
        if ($type == self::TYPE_MALADIE) {
            $whereClause = "type = " . self::TYPE_MALADIE;
        } elseif ($type == self::TYPE_RTT) {
            $whereClause = "type = 7";
        } else {
            $type2Value = $typeInfo['type2_value'];
            $whereClause = "type = 0 AND type2 = " . $type2Value;
        }
        
        // Ajout du filtre département si non admin
        $departmentClause = "";
        $params = [];
        $types = "";
        
        if (!$isAdmin && !empty($departements)) {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $departmentClause = " AND stuf.dep IN ($placeholders)";
            $params = $departements;
            $types = str_repeat('i', count($departements));
        }
        
        $query = "
            SELECT $columns
            FROM autreconge
            LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
            LEFT JOIN dep ON stuf.dep = dep.id
            WHERE $whereClause AND contrastage IN (0,1,3)
            $departmentClause
            ORDER BY anne DESC, datedebut DESC
        ";
        
        return [$query, $params, $types];
    }
    
    /**
     * Exécute la requête et retourne les résultats avec statistiques
     */
    public function getCongesList($type, $departements, $isAdmin = false) {
        list($query, $params, $types) = $this->getCongesQuery($type, $departements, $isAdmin);
        
        $stmt = $this->db->prepare($query);
        
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $conges = [];
        $totalConges = 0;
        $totalEmployees = [];
        $totalDays = 0;
        $currentYearLeaves = 0;
        $uniqueDepartments = [];
        $uniqueYears = [];
        $currentDate = date('Y-m-d');
        $currentYear = date('Y');
        
        while ($row = $result->fetch_assoc()) {
            $totalConges++;
            
            if (!in_array($row['mecano'], $totalEmployees)) {
                $totalEmployees[] = $row['mecano'];
            }
            
            if (!in_array($row['depar'], $uniqueDepartments) && $row['depar']) {
                $uniqueDepartments[] = $row['depar'];
            }
            
            if (!in_array($row['anne'], $uniqueYears) && $row['anne']) {
                $uniqueYears[] = $row['anne'];
            }
            
            if ($row['anne'] == $currentYear) {
                $currentYearLeaves++;
            }
            
            // Calcul des jours pour maladie
            if ($type == self::TYPE_MALADIE && isset($row['nbj'])) {
                $totalDays += intval($row['nbj']);
            } elseif ($type != self::TYPE_RTT && isset($row['nbj'])) {
                $totalDays += intval($row['nbj']);
            }
            
            // Détermination du statut pour RTT
            $status = null;
            if ($type == self::TYPE_RTT) {
                if ($currentDate >= $row['datedebut'] && $currentDate <= $row['datefin']) {
                    $status = 'current';
                } elseif ($row['datedebut'] > $currentDate) {
                    $status = 'upcoming';
                }
            }
            
            // Détermination si congé en cours (pour maladie)
            $isCurrentLeave = ($type == self::TYPE_MALADIE && 
                               $currentDate >= $row['datedebut'] && 
                               $currentDate <= $row['datefin']);
            
            $conges[] = [
                'mecano' => $row['mecano'],
                'nom' => $row['nom'],
                'datedebut' => $row['datedebut'],
                'datefin' => $row['datefin'],
                'nbj' => $row['nbj'] ?? null,
                'depar' => $row['depar'] ?? '',
                'commentaire' => $row['commentaire'] ?? '',
                'anne' => $row['anne'],
                'status' => $status,
                'isCurrentLeave' => $isCurrentLeave
            ];
        }
        
        $stmt->close();
        
        $averageDays = $totalConges > 0 ? round($totalDays / $totalConges, 1) : 0;
        
        return [
            'conges' => $conges,
            'stats' => [
                'total' => $totalConges,
                'employees' => count($totalEmployees),
                'currentYearLeaves' => $currentYearLeaves,
                'averageDays' => $averageDays,
                'uniqueDepartments' => count($uniqueDepartments),
                'uniqueYears' => array_unique($uniqueYears),
                'latestYear' => !empty($uniqueYears) ? max($uniqueYears) : $currentYear
            ]
        ];
    }
    
    /**
     * Récupère les congés annuels pour une année donnée
     */
    public function getAnnualLeaves($year, $departements, $isAdmin = false) {
        if ($isAdmin) {
            $query = "
                SELECT conge.mecano, nom, datedebut, datefin, dep.depar, annee, nbjours
                FROM conge
                LEFT JOIN stuf ON conge.mecano = stuf.mecano
                LEFT JOIN dep ON stuf.dep = dep.id
                WHERE annee = ? AND contrastage IN (0, 1, 3)
                ORDER BY conge.mecano, datefin DESC
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $year);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $types = str_repeat('i', count($departements));
            
            $query = "
                SELECT conge.mecano, nom, datedebut, datefin, dep.depar, annee, nbjours
                FROM conge
                LEFT JOIN stuf ON conge.mecano = stuf.mecano
                LEFT JOIN dep ON stuf.dep = dep.id
                WHERE annee = ? AND contrastage IN (0, 1, 3) AND stuf.dep IN ($placeholders)
                ORDER BY conge.mecano, datefin DESC
            ";
            $stmt = $this->db->prepare($query);
            $params = array_merge([$year], $departements);
            $stmt->bind_param("i" . $types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $leaves = [];
        $totalDays = 0;
        $employees = [];
        
        while ($row = $result->fetch_assoc()) {
            $leaves[] = $row;
            $totalDays += $row['nbjours'];
            if (!in_array($row['mecano'], $employees)) {
                $employees[] = $row['mecano'];
            }
        }
        
        $stmt->close();
        
        return [
            'leaves' => $leaves,
            'stats' => [
                'total' => count($leaves),
                'employees' => count($employees),
                'totalDays' => $totalDays,
                'averageDays' => count($leaves) > 0 ? round($totalDays / count($leaves), 1) : 0
            ]
        ];
    }
    
    /**
     * Récupère le solde des congés restants pour chaque employé
     */
    public function getRemainingLeaves($departements, $isAdmin = false) {
        // Récupérer l'année active
        $sqlAnnee = "SELECT annee FROM annee LIMIT 1";
        $resultAnnee = $this->db->query($sqlAnnee);
        $rowAnnee = $resultAnnee->fetch_assoc();
        $annee = $rowAnnee ? $rowAnnee['annee'] : date('Y');
        
        $DateDebutRequete = "$annee-01-01";
        $DateFinRequete = "$annee-12-31";
        
        if ($isAdmin) {
            $query = "
                SELECT DISTINCT 
                    stuf.mecano, 
                    nom, 
                    nbconge.rest, 
                    dep.depar, 
                    titres.libellet, 
                    nbj1, 
                    nbj2,
                    (
                        SELECT SUM(DATEDIFF(LEAST(?, ac.datefin), GREATEST(?, ac.datedebut)) + 1)
                        FROM autreconge ac
                        WHERE ac.datedebut <= ? 
                          AND ac.datefin >= ? 
                          AND ac.TYPE = 5 
                          AND ac.mecano = stuf.mecano
                    ) AS TotalJoursMaladie
                FROM stuf
                LEFT JOIN dep ON stuf.dep = dep.id
                LEFT JOIN titres ON titres.id = stuf.titre
                LEFT JOIN nbconge ON nbconge.mecano = stuf.mecano
                WHERE contrastage IN (0, 1, 3)
                ORDER BY nbconge.rest DESC
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ssss", $DateFinRequete, $DateDebutRequete, $DateFinRequete, $DateDebutRequete);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $types = str_repeat('i', count($departements));
            
            $query = "
                SELECT DISTINCT 
                    stuf.mecano, 
                    nom, 
                    nbconge.rest, 
                    dep.depar, 
                    titres.libellet, 
                    nbj1, 
                    nbj2,
                    (
                        SELECT SUM(DATEDIFF(LEAST(?, ac.datefin), GREATEST(?, ac.datedebut)) + 1)
                        FROM autreconge ac
                        WHERE ac.datedebut <= ? 
                          AND ac.datefin >= ? 
                          AND ac.TYPE = 5 
                          AND ac.mecano = stuf.mecano
                    ) AS TotalJoursMaladie
                FROM stuf
                LEFT JOIN dep ON stuf.dep = dep.id
                LEFT JOIN titres ON titres.id = stuf.titre
                LEFT JOIN nbconge ON nbconge.mecano = stuf.mecano
                WHERE contrastage IN (0, 1, 3) AND stuf.dep IN ($placeholders)
                ORDER BY nbconge.rest DESC
            ";
            $stmt = $this->db->prepare($query);
            $params = array_merge([$DateFinRequete, $DateDebutRequete, $DateFinRequete, $DateDebutRequete], $departements);
            $stmt->bind_param("ssss" . $types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $remainingLeaves = [];
        $totalEmployees = 0;
        $totalRest = 0;
        $maxRest = 0;
        $minRest = 1000;
        
        // Barèmes pour les déductions maladie
        $maladieBrackets = [
            [7, 19, 1], [20, 32, 2], [33, 45, 3], [46, 58, 4], [59, 71, 5],
            [72, 84, 6], [85, 97, 7], [98, 110, 8], [111, 123, 9], [124, 136, 10],
            [137, 149, 11], [150, 162, 12], [163, 175, 13], [176, 188, 14], [189, 201, 15],
            [202, 214, 16], [215, 227, 17], [228, 240, 18], [241, 253, 19], [254, 266, 20],
            [267, 279, 21], [280, 292, 22], [293, 305, 23], [306, 318, 24], [319, 331, 25],
            [332, 344, 26], [345, 357, 27], [358, 370, 28]
        ];
        
        while ($row = $result->fetch_assoc()) {
            $TotalMaladie = $row['TotalJoursMaladie'] ?? 0;
            
            $NbJourMaladieSoustract = 0;
            foreach ($maladieBrackets as $bracket) {
                if ($TotalMaladie >= $bracket[0] && $TotalMaladie <= $bracket[1]) {
                    $NbJourMaladieSoustract = $bracket[2];
                    break;
                }
            }
            
            $congepris = (($row['nbj1'] + $row['nbj2']) - $row['rest']);
            $currentRest = $row['rest'] - $NbJourMaladieSoustract;
            
            // Déterminer la classe CSS pour le badge
            $badgeClass = 'rest-';
            if ($currentRest >= 15) {
                $badgeClass .= 'high';
            } elseif ($currentRest >= 5) {
                $badgeClass .= 'medium';
            } else {
                $badgeClass .= 'low';
            }
            
            $remainingLeaves[] = [
                'mecano' => $row['mecano'],
                'nom' => $row['nom'],
                'libellet' => $row['libellet'],
                'nbj1' => $row['nbj1'],
                'nbj2' => $row['nbj2'],
                'congepris' => $congepris,
                'maladie_deduction' => $NbJourMaladieSoustract,
                'current_rest' => $currentRest,
                'depar' => $row['depar'],
                'badge_class' => $badgeClass
            ];
            
            $totalEmployees++;
            $totalRest += $currentRest;
            if ($currentRest > $maxRest) $maxRest = $currentRest;
            if ($currentRest < $minRest) $minRest = $currentRest;
        }
        
        $stmt->close();
        
        return [
            'remaining_leaves' => $remainingLeaves,
            'stats' => [
                'totalEmployees' => $totalEmployees,
                'avgRest' => $totalEmployees > 0 ? round($totalRest / $totalEmployees, 1) : 0,
                'maxRest' => $maxRest,
                'minRest' => $minRest == 1000 ? 0 : $minRest
            ]
        ];
    }
    
    /**
     * Récupère les années disponibles pour les congés annuels
     */
    public function getAvailableYears() {
        $query = "SELECT DISTINCT annee FROM conge ORDER BY annee DESC";
        $result = $this->db->query($query);
        
        $years = [];
        while ($row = $result->fetch_assoc()) {
            $years[] = $row['annee'];
        }
        
        return $years;
    }
}
?>