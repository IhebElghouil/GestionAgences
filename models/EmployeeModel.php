<?php
// models/EmployeeModel.php
require_once __DIR__ . '/../config/Database.php';

class EmployeeModel {
    private $db;
    
    // Statuts des employés
    const STATUS_PERMANENT = 0;
    const STATUS_TRAINEE = 1;
    const STATUS_CONTRACT = 2;
    const STATUS_ATTACHED = 3;
    const STATUS_RETIRED = 4;
    const STATUS_ATTACHED_EXTERNAL = 5;
    const STATUS_UNAVAILABLE = 6;
    
    const STATUS_MAPPING = [
        self::STATUS_PERMANENT => [
            'name' => 'مترسم',
            'badge_class' => 'badge-success',
            'icon' => 'fas fa-user-check',
            'color' => '#27ae60'
        ],
        self::STATUS_TRAINEE => [
            'name' => 'متربص',
            'badge_class' => 'badge-warning',
            'icon' => 'fas fa-user-graduate',
            'color' => '#f39c12'
        ],
        self::STATUS_CONTRACT => [
            'name' => 'متعاقد',
            'badge_class' => 'badge-info',
            'icon' => 'fas fa-file-signature',
            'color' => '#3498db'
        ],
        self::STATUS_ATTACHED => [
            'name' => 'ملحق لدى الشركة',
            'badge_class' => 'badge-secondary',
            'icon' => 'fas fa-building',
            'color' => '#7f8c8d'
        ],
        self::STATUS_RETIRED => [
            'name' => 'متقاعد',
            'badge_class' => 'badge-danger',
            'icon' => 'fas fa-home',
            'color' => '#e74c3c'
        ],
        self::STATUS_ATTACHED_EXTERNAL => [
            'name' => 'ملحق خارج الشركة',
            'badge_class' => 'badge-purple',
            'icon' => 'fas fa-external-link-alt',
            'color' => '#9b59b6'
        ],
        self::STATUS_UNAVAILABLE => [
            'name' => 'إحالة على عدم المباشرة',
            'badge_class' => 'badge-danger',
            'icon' => 'fas fa-user-clock',
            'color' => '#c0392b'
        ]
    ];
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Récupère la liste des employés
     */
    public function getEmployeesList($departements, $isAdmin = false) {
        $query = "
            SELECT DISTINCT 
                stuf.mecano, 
                stuf.nom, 
                stuf.daten, 
                stuf.daterec, 
                stuf.cin, 
                titres.libellet AS titre_libelle,
                stuf.echelle,
                stuf.degree,
                stuf.sexe,
                stuf.fil,
                stuf.contrastage,
                dep.depar,
                stuf.jrepos,
                social.ncnss, 
                social.nassurance
            FROM stuf
            LEFT JOIN dep ON stuf.dep = dep.id
            LEFT JOIN social ON social.mecano = stuf.mecano
            LEFT JOIN titres ON titres.id = stuf.titre
            WHERE stuf.contrastage IN (0,1,2,3,4,5,6)
        ";
        
        if (!$isAdmin && !empty($departements)) {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query .= " AND stuf.dep IN ($placeholders)";
        }
        
        $query .= " GROUP BY stuf.mecano ORDER BY stuf.mecano ASC";
        
        $stmt = $this->db->prepare($query);
        
        if (!$isAdmin && !empty($departements)) {
            $types = str_repeat('i', count($departements));
            $stmt->bind_param($types, ...$departements);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $employees = [];
        $stats = [
            'total' => 0,
            'permanent' => 0,
            'trainee' => 0,
            'attached_internal' => 0,
            'attached_external' => 0,
            'unavailable' => 0,
            'active' => 0
        ];
        
        $today = date_create('today');
        
        while ($row = $result->fetch_assoc()) {
            $stats['total']++;
            
            // Calcul de l'âge
            $dateNaissance = date_create($row['daten']);
            $age = $dateNaissance ? $dateNaissance->diff($today)->y : 0;
            
            // Calcul de l'ancienneté
            $dateEmbauche = date_create($row['daterec']);
            $anciennete = $dateEmbauche ? $dateEmbauche->diff($today)->y : 0;
            
            // Détermination du code du statut
            $statusCode = $row['contrastage'];
            
            // Mise à jour des statistiques
            switch ($statusCode) {
                case self::STATUS_PERMANENT:
                    $stats['permanent']++;
                    $stats['active']++;
                    break;
                case self::STATUS_TRAINEE:
                    $stats['trainee']++;
                    $stats['active']++;
                    break;
                case self::STATUS_ATTACHED:
                    $stats['attached_internal']++;
                    $stats['active']++;
                    break;
                case self::STATUS_ATTACHED_EXTERNAL:
                    $stats['attached_external']++;
                    break;
                case self::STATUS_UNAVAILABLE:
                    $stats['unavailable']++;
                    break;
            }
            
            // Détermination du type de poste
            $echelle = $row['echelle'];
            $silk = '';
            if ($echelle !== null && $echelle !== '') {
                $echelleVal = (int)$echelle;
                if ($echelleVal >= 500) $silk = 'C';
                elseif ($echelleVal < 300) $silk = 'E';
                else $silk = 'M';
            } else {
                $silk = '-';
            }
            
            // Badge pour l'échelle
            $echelleBadge = $silk == 'C' ? 'badge-success' : ($silk == 'M' ? 'badge-warning' : 'badge-info');
            
            // Statut textuel
            $statusInfo = self::STATUS_MAPPING[$statusCode] ?? [
                'name' => 'غير محدد',
                'badge_class' => 'badge-secondary'
            ];
            
            $gender = ($row['sexe'] == 'M' || $row['sexe'] == 'm') ? 'ذكر' : (($row['sexe'] == 'F' || $row['sexe'] == 'f') ? 'أنثى' : '-');
            
            $employees[] = [
                'mecano' => $row['mecano'],
                'nom' => $row['nom'],
                'daten' => $row['daten'],
                'daterec' => $row['daterec'],
                'cin' => $row['cin'],
                'titre_libelle' => $row['titre_libelle'],
                'echelle' => $row['echelle'],
                'echelle_badge' => $echelleBadge,
                'degree' => $row['degree'],
                'silk' => $silk,
                'fil' => $row['fil'],
                'depar' => $row['depar'],
                'age' => $age,
                'anciennete' => $anciennete,
                'ncnss' => $row['ncnss'] ?? '',
                'nassurance' => $row['nassurance'] ?? '',
                'sexe' => $gender,
                'status_code' => $statusCode,
                'status_name' => $statusInfo['name'],
                'status_badge' => $statusInfo['badge_class']
            ];
        }
        
        $stmt->close();
        
        return [
            'employees' => $employees,
            'stats' => $stats
        ];
    }
    
    /**
     * Récupère les informations d'un employé pour modification
     */
    public function getEmployeeInfo($mecano) {
        $query = "
            SELECT stuf.*, social.ncnss, social.nassurance, dep.depar
            FROM stuf
            LEFT JOIN social ON social.mecano = stuf.mecano
            LEFT JOIN dep ON stuf.dep = dep.id
            WHERE stuf.mecano = ?
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $mecano);
        $stmt->execute();
        $result = $stmt->get_result();
        $employee = $result->fetch_assoc();
        $stmt->close();
        return $employee;
    }
    
    /**
     * Sauvegarde un nouvel employé
     */
public function saveEmployee($data) {
    // Insertion dans stuf
    $query = "INSERT INTO stuf (mecano, nom, daten, daterec, cin, titre, echelle, degree, sexe, fil, contrastage, dep, jrepos, pointagemachine, date_debut_pointage) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->db->prepare($query);
    
    $Dpointage = ($data['pointage'] == 1) ? $data['Dpointage'] : null;
    
    $stmt->bind_param("issssisssssiiss", 
        $data['mecano'], 
        $data['nomprenom'], 
        $data['Dnaissance'], 
        $data['Drecrutement'], 
        $data['cin'], 
        $data['grade'], 
        $data['echelle'], 
        $data['echelon'], 
        $data['sexe'], 
        $data['fil'], 
        $data['etat'], 
        $data['dep'], 
        $data['Jourrepos'], 
        $data['pointage'], 
        $Dpointage
    );
    
    return $stmt->execute();
}
    
    /**
     * Met à jour un employé
     */
public function updateEmployee($data) {
    $query = "UPDATE stuf SET 
              nom = ?, daten = ?, daterec = ?, cin = ?, titre = ?, 
              echelle = ?, degree = ?, sexe = ?, fil = ?, contrastage = ?, 
              dep = ?, jrepos = ?, pointagemachine = ?, date_debut_pointage = ? 
              WHERE mecano = ?";
    $stmt = $this->db->prepare($query);
    
    $Dpointage = ($data['pointage'] == 1) ? $data['Dpointage'] : null;
    
    $stmt->bind_param("sssssissssiissi", 
        $data['nomprenom'], 
        $data['Dnaissance'], 
        $data['Drecrutement'], 
        $data['cin'], 
        $data['grade'], 
        $data['echelle'], 
        $data['echelon'], 
        $data['sexe'], 
        $data['fil'], 
        $data['etat'], 
        $data['dep'], 
        $data['Jourrepos'], 
        $data['pointage'], 
        $Dpointage, 
        $data['mecano']
    );
    
    return $stmt->execute();
}
    
    /**
     * Récupère les titres (grades) pour le formulaire
     */
    public function getTitres() {
        $query = "SELECT * FROM titres GROUP BY libellet";
        $result = $this->db->query($query);
        $titres = [];
        while ($row = $result->fetch_assoc()) {
            $titres[] = $row;
        }
        return $titres;
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
     * Récupère les services pour le formulaire
     */
    public function getServices() {
        $query = "SELECT * FROM service WHERE sb='5'";
        $result = $this->db->query($query);
        $services = [];
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
        return $services;
    }
}
?>