<?php
// models/AccidentModel.php
require_once __DIR__ . '/../config/Database.php';

class AccidentModel {
    private $db;
    
    // Types d'accidents
    const TYPE_ACCIDENT_INITIAL = 11;
    const TYPE_ACCIDENT_EXTENSION = 9;
    const TYPE_ACCIDENT_RECIDIVE = 10;
    
    const TYPE_MAPPING = [
        self::TYPE_ACCIDENT_INITIAL => [
            'name' => 'حادث أولي',
            'badge_class' => 'badge-primary',
            'color' => '#3498db'
        ],
        self::TYPE_ACCIDENT_EXTENSION => [
            'name' => 'تمديد',
            'badge_class' => 'badge-warning',
            'color' => '#f39c12'
        ],
        self::TYPE_ACCIDENT_RECIDIVE => [
            'name' => 'انتكاسة',
            'badge_class' => 'badge-danger',
            'color' => '#e74c3c'
        ]
    ];
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Récupère la liste des accidents de travail avec tri:
     * 1. Par statut: Expired > Expiring > Valide
     * 2. Par année décroissante (année en cours en premier)
     * 3. Par date de fin croissante
     */
    public function getAccidentsList($departements, $isAdmin = false) {
        $currentDate = date('Y-m-d');
        $expiringDate = date('Y-m-d', strtotime('+10 days'));
        
        if ($isAdmin) {
            $query = "
                SELECT 
                    autreconge.mecano, 
                    nom, 
                    datedebut, 
                    datefin, 
                    dep.depar, 
                    commentaire, 
                    anne, 
                    titres.libellet, 
                    autreconge.id, 
                    valide, 
                    DATEDIFF(datefin, CURRENT_DATE()) as jours_restants, 
                    ncnss, 
                    autreconge.type2,
                    -- Priorité de tri: 1=Expiré, 2=Expire bientôt, 3=Valide
                    CASE 
                        WHEN datefin < ? AND valide = 1 THEN 1
                        WHEN datefin <= ? AND valide = 1 THEN 2
                        ELSE 3
                    END as priority
                FROM autreconge
                LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
                LEFT JOIN social ON stuf.mecano = social.mecano
                LEFT JOIN titres ON titres.id = stuf.titre
                LEFT JOIN dep ON stuf.dep = dep.id
                WHERE type = 6 AND contrastage IN (0, 1, 3)
                ORDER BY 
                    priority ASC,           -- Expired en premier, puis Expiring, puis Valide
                    anne DESC,              -- Année décroissante (2025, 2024, 2023...)
                    datefin ASC             -- Date de fin croissante dans chaque groupe
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("ss", $currentDate, $expiringDate);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $types = str_repeat('i', count($departements)) . 'ss';
            
            $query = "
                SELECT 
                    autreconge.mecano, 
                    nom, 
                    datedebut, 
                    datefin, 
                    dep.depar, 
                    commentaire, 
                    anne, 
                    titres.libellet, 
                    autreconge.id, 
                    valide, 
                    DATEDIFF(datefin, CURRENT_DATE()) as jours_restants, 
                    ncnss, 
                    autreconge.type2,
                    CASE 
                        WHEN datefin < ? AND valide = 1 THEN 1
                        WHEN datefin <= ? AND valide = 1 THEN 2
                        ELSE 3
                    END as priority
                FROM autreconge
                LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
                LEFT JOIN social ON stuf.mecano = social.mecano
                LEFT JOIN titres ON titres.id = stuf.titre
                LEFT JOIN dep ON stuf.dep = dep.id
                WHERE type = 6 AND contrastage IN (0, 1, 3) AND stuf.dep IN ($placeholders)
                ORDER BY 
                    priority ASC,           -- Expired en premier, puis Expiring, puis Valide
                    anne DESC,              -- Année décroissante (2025, 2024, 2023...)
                    datefin ASC             -- Date de fin croissante dans chaque groupe
            ";
            $stmt = $this->db->prepare($query);
            $params = array_merge($departements, [$currentDate, $expiringDate]);
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $accidents = [];
        $totalAccidents = 0;
        $expiredCount = 0;
        $expiringCount = 0;
        $validCount = 0;
        
        while ($row = $result->fetch_assoc()) {
            $totalAccidents++;
            
            $datedebut = $row['datedebut'];
            $datefin = $row['datefin'];
            $valide = $row['valide'];
            $joursRestants = intval($row['jours_restants']);
            
            // Calcul du nombre de jours
            if ($datedebut && $datefin) {
                $daydiff = floor((abs(strtotime($datefin) - strtotime($datedebut)) / (60 * 60 * 24))) + 1;
            } else {
                $daydiff = 0;
            }
            
            // Déterminer le type d'accident
            $typeInfo = self::TYPE_MAPPING[$row['type2']] ?? [
                'name' => 'غير محدد',
                'badge_class' => 'badge-secondary',
                'color' => '#6c757d'
            ];
            
            // Déterminer le statut et la classe CSS
            $rowClass = '';
            $statusClass = '';
            $tooltipText = '';
            
            if ($datefin < $currentDate && $valide == 1) {
                $rowClass = 'tr-expired';
                $statusClass = 'status-expired';
                $tooltipText = 'إنتهت صلوحية الشهادة الطبية لحادث الشغل';
                $expiredCount++;
            } elseif ($datefin == $currentDate && $valide == 1) {
                $rowClass = 'tr-today';
                $statusClass = 'status-expiring';
                $tooltipText = 'تنتهي صلوحية الشهادة الطبية لحادث الشغل اليوم';
                $expiringCount++;
            } elseif ($joursRestants >= 1 && $joursRestants <= 10 && $valide == 1) {
                $rowClass = 'tr-expiring';
                $statusClass = 'status-expiring';
                $jlettre = ($joursRestants < 11) ? "أيّام" : "يوما";
                $tooltipText = 'تنتهي صلوحية الشهادة الطبية لحادث الشغل خلال ' . $joursRestants . ' ' . $jlettre;
                $expiringCount++;
            } elseif ($valide == 1) {
                $statusClass = 'status-valid';
                $validCount++;
            } else {
                $statusClass = 'status-invalid';
            }
            
            $accidents[] = [
                'id' => $row['id'],
                'mecano' => $row['mecano'],
                'nom' => $row['nom'],
                'libellet' => $row['libellet'],
                'datedebut' => $row['datedebut'],
                'datefin' => $row['datefin'],
                'daydiff' => $daydiff,
                'ncnss' => $row['ncnss'] ?? '',
                'depar' => $row['depar'],
                'type2' => $row['type2'],
                'type_nom' => $typeInfo['name'],
                'type_badge_class' => $typeInfo['badge_class'],
                'commentaire' => $row['commentaire'],
                'anne' => $row['anne'],
                'valide' => $valide,
                'jours_restants' => $joursRestants,
                'rowClass' => $rowClass,
                'statusClass' => $statusClass,
                'tooltipText' => $tooltipText
            ];
        }
        
        $stmt->close();
        
        return [
            'accidents' => $accidents,
            'stats' => [
                'total' => $totalAccidents,
                'expired' => $expiredCount,
                'expiring' => $expiringCount,
                'valid' => $validCount
            ]
        ];
    }
    
    /**
     * Récupère les départements pour le filtre
     */
    public function getDepartments($departements = null, $isAdmin = false) {
        if ($isAdmin) {
            $query = "SELECT id, depar FROM dep ORDER BY depar";
            $result = $this->db->query($query);
        } else {
            if (empty($departements)) {
                return [];
            }
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $types = str_repeat('i', count($departements));
            $query = "SELECT id, depar FROM dep WHERE id IN ($placeholders) ORDER BY depar";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param($types, ...$departements);
            $stmt->execute();
            $result = $stmt->get_result();
        }
        
        $departments = [];
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
        
        return $departments;
    }
    
    /**
     * Met à jour un accident de travail (date fin, commentaire, validité)
     */
    public function updateAccident($id, $datefin, $commentaire, $valide) {
        $query = "UPDATE autreconge SET datefin = ?, commentaire = ?, valide = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssii", $datefin, $commentaire, $valide, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Met à jour le type d'accident
     */
    public function updateAccidentType($id, $type2) {
        $query = "UPDATE autreconge SET type2 = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ii", $type2, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Met à jour à la fois les informations générales et le type d'accident
     */
    public function updateFullAccident($id, $datefin, $commentaire, $valide, $type2) {
        $query = "UPDATE autreconge SET datefin = ?, commentaire = ?, valide = ?, type2 = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssiii", $datefin, $commentaire, $valide, $type2, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Récupère les informations d'un accident pour modification
     */
    public function getAccidentInfo($id) {
        $query = "
            SELECT autreconge.id, autreconge.mecano, stuf.nom, autreconge.datedebut, 
                   autreconge.datefin, autreconge.commentaire, autreconge.valide, autreconge.type2,
                   autreconge.anne
            FROM autreconge
            LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
            WHERE autreconge.id = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $accidentInfo = $result->fetch_assoc();
        $stmt->close();
        return $accidentInfo;
    }
    
    /**
     * Exporte les données au format CSV
     */
    public function exportToCsv($accidents) {
        $output = fopen('php://temp', 'w');
        
        // Ajouter le BOM UTF-8
        fwrite($output, "\xEF\xBB\xBF");
        
        // En-têtes
        $headers = [
            'الرقم الآلي',
            'الإسم و اللقب',
            'الرتبة',
            'تاريخ البداية',
            'تاريخ النهاية',
            'عدد الأيام',
            'رقم الضمان الإجتماعي',
            'وحدة الإرتباط',
            'نوع الحادث',
            'الملاحظات',
            'السنة',
            'الحالة'
        ];
        fputcsv($output, $headers);
        
        // Données
        $statusLabels = [
            'status-expired' => 'منتهية الصلاحية',
            'status-expiring' => 'قريبة الإنتهاء',
            'status-valid' => 'صالحة',
            'status-invalid' => 'غير صالحة'
        ];
        
        foreach ($accidents as $accident) {
            $row = [
                $accident['mecano'],
                $accident['nom'],
                $accident['libellet'],
                $accident['datedebut'],
                $accident['datefin'],
                $accident['daydiff'],
                $accident['ncnss'],
                $accident['depar'],
                $accident['type_nom'],
                $accident['commentaire'],
                $accident['anne'],
                $statusLabels[$accident['statusClass']] ?? ''
            ];
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}
?>