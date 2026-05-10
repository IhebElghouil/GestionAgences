<?php
// models/BadgesModel.php
require_once __DIR__ . '/../config/Database.php';

class BadgesModel {
    private $db;
    
    // Types de documents
    const TYPE_CARTE_PROFESSIONNELLE = 0;
    const TYPE_PERMIS_CONDUIRE = 1;
    
    const TYPE_MAPPING = [
        self::TYPE_CARTE_PROFESSIONNELLE => [
            'name' => 'البطاقات المهنيّة',
            'subtitle' => 'متابعة البطاقات المهنية للموظفين',
            'icon' => 'fa-id-card',
            'color' => 'primary',
            'title' => 'متابعة البطاقات المهنيّة'
        ],
        self::TYPE_PERMIS_CONDUIRE => [
            'name' => 'رخص السياقة',
            'subtitle' => 'متابعة رخص السياقة للموظفين',
            'icon' => 'fa-id-card',
            'color' => 'warning',
            'title' => 'متابعة رخص السياقة'
        ]
    ];
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Récupère la liste des badges (cartes professionnelles ou permis de conduire)
     */
    public function getBadgesList($type, $departements, $isAdmin = false) {
        $typeValue = ($type == self::TYPE_CARTE_PROFESSIONNELLE) ? 0 : 1;
        
        if ($isAdmin) {
            $query = "
                SELECT stuf.mecano, nom, titres.libellet, numcarte, dateemission, finvalidite, 
                       dep.depar, DATEDIFF(finvalidite, CURRENT_DATE()) as jours_restants, contrastage
                FROM stuf 
                LEFT JOIN dep ON stuf.dep = dep.id 
                LEFT JOIN titres ON titres.id = stuf.titre 
                LEFT JOIN cartes ON stuf.mecano = cartes.mecano 
                WHERE stuf.titre IN (6, 7, 8) AND contrastage IN (0, 1, 3) AND cartes.type = ? 
                ORDER BY finvalidite, stuf.mecano ASC
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $typeValue);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $types = str_repeat('i', count($departements)) . 'i';
            
            $query = "
                SELECT stuf.mecano, nom, titres.libellet, numcarte, dateemission, finvalidite, 
                       dep.depar, DATEDIFF(finvalidite, CURRENT_DATE()) as jours_restants, contrastage
                FROM stuf 
                LEFT JOIN dep ON stuf.dep = dep.id 
                LEFT JOIN titres ON titres.id = stuf.titre 
                LEFT JOIN cartes ON stuf.mecano = cartes.mecano 
                WHERE stuf.dep IN ($placeholders) AND contrastage IN (0, 1, 3) 
                  AND stuf.titre IN (6, 7, 8) AND cartes.type = ? 
                ORDER BY finvalidite, stuf.mecano ASC
            ";
            $stmt = $this->db->prepare($query);
            $params = array_merge($departements, [$typeValue]);
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $badges = [];
        $totalCount = 0;
        $expiredCount = 0;
        $expiringCount = 0;
        $validCount = 0;
        $currentDate = date('Y-m-d');
        
        // Mapping des situations
        $situations = [
            0 => 'مترسم',
            1 => 'متربص',
            2 => 'متعاقد',
            3 => 'ملحق'
        ];
        
        while ($row = $result->fetch_assoc()) {
            $totalCount++;
            
            $expirationDate = $row['finvalidite'];
            $joursRestants = intval($row['jours_restants']);
            
            // Déterminer le statut
            $status = '';
            $statusClass = '';
            $statusBadge = '';
            $statusIndicator = '';
            
            if ($expirationDate < $currentDate) {
                $status = 'expired';
                $statusClass = 'status-expired';
                $statusBadge = 'badge-expired';
                $statusIndicator = 'indicator-expired';
                $expiredCount++;
            } elseif ($joursRestants < 60) {
                $status = 'expiring';
                $statusClass = 'status-expiring';
                $statusBadge = 'badge-expiring';
                $statusIndicator = 'indicator-expiring';
                $expiringCount++;
            } else {
                $status = 'valid';
                $statusClass = '';
                $statusBadge = 'badge-valid';
                $statusIndicator = 'indicator-valid';
                $validCount++;
            }
            
            $badges[] = [
                'mecano' => $row['mecano'],
                'nom' => $row['nom'],
                'libellet' => $row['libellet'],
                'situation' => $situations[$row['contrastage']] ?? '',
                'numcarte' => $row['numcarte'],
                'dateemission' => $row['dateemission'],
                'finvalidite' => $row['finvalidite'],
                'depar' => $row['depar'],
                'jours_restants' => $joursRestants,
                'status' => $status,
                'statusClass' => $statusClass,
                'statusBadge' => $statusBadge,
                'statusIndicator' => $statusIndicator
            ];
        }
        
        $stmt->close();
        
        return [
            'badges' => $badges,
            'stats' => [
                'total' => $totalCount,
                'valid' => $validCount,
                'expiring' => $expiringCount,
                'expired' => $expiredCount
            ]
        ];
    }
    
    /**
     * Récupère les départements pour le filtre
     */
    public function getDepartments() {
        $query = "SELECT DISTINCT depar FROM dep ORDER BY depar";
        $result = $this->db->query($query);
        
        $departments = [];
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row['depar'];
        }
        
        return $departments;
    }
    
    /**
     * Met à jour une carte
     */
    public function updateCard($mecano, $type, $numcarte, $dateemission, $finvalidite) {
        $typeValue = ($type == self::TYPE_CARTE_PROFESSIONNELLE) ? 0 : 1;
        
        // Vérifier si la carte existe
        $checkQuery = "SELECT id FROM cartes WHERE mecano = ? AND type = ?";
        $checkStmt = $this->db->prepare($checkQuery);
        $checkStmt->bind_param("ii", $mecano, $typeValue);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            // Mettre à jour
            $query = "UPDATE cartes SET numcarte = ?, dateemission = ?, finvalidite = ? WHERE mecano = ? AND type = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("sssii", $numcarte, $dateemission, $finvalidite, $mecano, $typeValue);
        } else {
            // Insérer
            $query = "INSERT INTO cartes (mecano, type, numcarte, dateemission, finvalidite) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("iisss", $mecano, $typeValue, $numcarte, $dateemission, $finvalidite);
        }
        
        $result = $stmt->execute();
        $stmt->close();
        $checkStmt->close();
        
        return $result;
    }
    
    /**
     * Récupère les informations d'une carte pour modification
     */
    public function getCardInfo($mecano, $type) {
        $typeValue = ($type == self::TYPE_CARTE_PROFESSIONNELLE) ? 0 : 1;
        
        $query = "
            SELECT stuf.mecano, stuf.nom, cartes.numcarte, cartes.dateemission, cartes.finvalidite
            FROM stuf
            LEFT JOIN cartes ON stuf.mecano = cartes.mecano AND cartes.type = ?
            WHERE stuf.mecano = ?
        ";
        
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $typeValue, $mecano);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $cardInfo = $result->fetch_assoc();
        $stmt->close();
        
        return $cardInfo;
    }
}
?>