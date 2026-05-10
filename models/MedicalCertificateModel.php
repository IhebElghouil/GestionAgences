<?php
// models/MedicalCertificateModel.php
require_once __DIR__ . '/../config/Database.php';

class MedicalCertificateModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Récupère la liste des certificats médicaux
     */
    public function getCertificatesList($departements, $isAdmin = false) {
        $currentDate = date('Y-m-d');
        
        if ($isAdmin) {
            $query = "
                SELECT 
                    certificats.id,
                    certificats.mecano, 
                    stuf.nom, 
                    datecertificat, 
                    numcertifcat, 
                    DateFin, 
                    Observation, 
                    certificats.Etat, 
                    dep.depar, 
                    titres.libellet, 
                    DATEDIFF(DateFin, CURRENT_DATE()) AS days_left
                FROM certificats
                LEFT JOIN stuf ON stuf.mecano = certificats.mecano
                LEFT JOIN titres ON titres.id = stuf.titre
                LEFT JOIN dep ON stuf.dep = dep.id
                WHERE contrastage IN (0,1,3)
                ORDER BY certificats.Etat DESC, days_left ASC
            ";
            $stmt = $this->db->prepare($query);
        } else {
            if (empty($departements)) {
                return ['certificates' => [], 'stats' => $this->getEmptyStats()];
            }
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $types = str_repeat('i', count($departements));
            
            $query = "
                SELECT 
                    certificats.id,
                    certificats.mecano, 
                    stuf.nom, 
                    datecertificat, 
                    numcertifcat, 
                    DateFin, 
                    Observation, 
                    certificats.Etat, 
                    dep.depar, 
                    titres.libellet, 
                    DATEDIFF(DateFin, CURRENT_DATE()) AS days_left
                FROM certificats
                LEFT JOIN stuf ON stuf.mecano = certificats.mecano
                LEFT JOIN titres ON titres.id = stuf.titre
                LEFT JOIN dep ON stuf.dep = dep.id
                WHERE contrastage IN (0,1,3) AND stuf.dep IN ($placeholders)
                ORDER BY certificats.Etat DESC, days_left ASC
            ";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param($types, ...$departements);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        $certificates = [];
        $totalCertificates = 0;
        $expiredCertificates = 0;
        $urgentCertificates = 0;
        $todayCertificates = 0;
        $validCertificates = 0;
        
        while ($row = $result->fetch_assoc()) {
            $totalCertificates++;
            $daysLeft = intval($row['days_left']);
            $etat = $row['Etat'];
            $dateFin = $row['DateFin'];
            
            // Determine status
            $statusClass = '';
            $urgencyIndicator = '';
            $statusType = '';
            
            if ($dateFin < $currentDate && $etat == 1) {
                $statusClass = 'status-expired';
                $urgencyIndicator = 'indicator-expired';
                $statusType = 'expired';
                $expiredCertificates++;
            } elseif ($daysLeft >= 1 && $daysLeft <= 10 && $etat == 1) {
                $statusClass = 'status-urgent';
                $urgencyIndicator = 'indicator-urgent';
                $statusType = 'urgent';
                $urgentCertificates++;
            } elseif ($daysLeft == 0 && $etat == 1) {
                $statusClass = 'status-today';
                $urgencyIndicator = 'indicator-today';
                $statusType = 'today';
                $todayCertificates++;
            } else {
                $statusType = 'valid';
                $validCertificates++;
            }
            
            $certificates[] = [
                'id' => $row['id'],
                'mecano' => $row['mecano'],
                'nom' => $row['nom'],
                'libellet' => $row['libellet'],
                'datecertificat' => $row['datecertificat'],
                'numcertifcat' => $row['numcertifcat'],
                'datefin' => $row['DateFin'],
                'observation' => $row['Observation'],
                'depar' => $row['depar'],
                'days_left' => $daysLeft,
                'statusClass' => $statusClass,
                'urgencyIndicator' => $urgencyIndicator,
                'statusType' => $statusType
            ];
        }
        
        $stmt->close();
        
        return [
            'certificates' => $certificates,
            'stats' => [
                'total' => $totalCertificates,
                'valid' => $validCertificates,
                'expired' => $expiredCertificates,
                'urgent' => $urgentCertificates,
                'today' => $todayCertificates
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
     * Récupère les employés pour le select
     */
    public function getEmployees() {
        $query = "SELECT mecano FROM stuf WHERE contrastage IN (0,1,3) ORDER BY mecano";
        $result = $this->db->query($query);
        
        $employees = [];
        while ($row = $result->fetch_assoc()) {
            $employees[] = $row['mecano'];
        }
        
        return $employees;
    }
    
    /**
     * Récupère le nom d'un employé par son matricule
     */
    public function getEmployeeName($mecano) {
        $query = "SELECT nom FROM stuf WHERE mecano = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $mecano);
        $stmt->execute();
        $stmt->bind_result($nom);
        $stmt->fetch();
        $stmt->close();
        
        return $nom ?: 'غير موجود';
    }
    
    /**
     * Ajoute un nouveau certificat médical
     */
    public function addCertificate($data) {
        $query = "INSERT INTO certificats (mecano, datecertificat, numcertifcat, DateFin, Observation, Etat) 
                  VALUES (?, ?, ?, ?, ?, 1)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("issss", 
            $data['mecano'], 
            $data['datecertificat'], 
            $data['numcertifcat'], 
            $data['datefin'], 
            $data['observation']
        );
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    /**
     * Met à jour un certificat médical
     */
    public function updateCertificate($id, $datecertificat, $numcertifcat, $datefin, $observation, $etat) {
        $query = "UPDATE certificats SET datecertificat = ?, numcertifcat = ?, DateFin = ?, Observation = ?, Etat = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ssssii", $datecertificat, $numcertifcat, $datefin, $observation, $etat, $id);
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    
    /**
     * Récupère les informations d'un certificat pour modification
     */
    public function getCertificateInfo($id) {
        $query = "SELECT * FROM certificats WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $certificate = $result->fetch_assoc();
        $stmt->close();
        
		if ($certificate) {
        $certificate['nom'] = $certificate['employee_name'] ?? '';
    }
		
        return $certificate;
    }
    
    private function getEmptyStats() {
        return [
            'total' => 0,
            'valid' => 0,
            'expired' => 0,
            'urgent' => 0,
            'today' => 0
        ];
    }
}
?>