<?php
// models/DepartureModel.php
require_once __DIR__ . '/../config/Database.php';

class DepartureModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Récupère la liste des départs
     */
    public function getDepartList($departements, $isAdmin = false) {
        if ($isAdmin) {
            $query = "SELECT d.*, s.nom, s.daten, dep.depar 
                      FROM depart d
                      LEFT JOIN stuf s ON d.mecano = s.mecano
                      LEFT JOIN dep ON s.dep = dep.id
                      ORDER BY d.datedepart DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            if (empty($departements)) {
                return [];
            }
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $types = str_repeat('i', count($departements));
            $query = "SELECT d.*, s.nom, s.daten, dep.depar 
                      FROM depart d
                      LEFT JOIN stuf s ON d.mecano = s.mecano
                      LEFT JOIN dep ON s.dep = dep.id
                      WHERE s.dep IN ($placeholders)
                      ORDER BY d.datedepart DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param($types, ...$departements);
            $stmt->execute();
            $result = $stmt->get_result();
        }
        
        $departs = [];
        while ($row = $result->fetch_assoc()) {
            $retirementDate = '';
            if (!empty($row['daten']) && $row['daten'] != '0000-00-00') {
                $retirementDate = date('Y-m-d', strtotime($row['daten'] . ' +60 years'));
            }
            
            $departs[] = [
                'id' => $row['id'],
                'mecano' => $row['mecano'],
                'nom' => $row['nom'] ?? '',
                'depar' => $row['depar'] ?? '',
                'daten' => $row['daten'] ?? '',
                'dateretraite' => $retirementDate,
                'datedepart' => $row['datedepart'] ?? '',
                'rest' => $row['rest'] ?? 0,
                'cministere' => $row['cministere'] ?? 0,
                'annee' => $row['annee'] ?? '',
                'observation' => $row['observation'] ?? ''
            ];
        }
        $stmt->close();
        
        return $departs;
    }
    
    /**
     * Récupère la liste des détachements
     */
    public function getDetachementList($departements, $isAdmin = false) {
        $currentDate = date('Y-m-d');
        
        if ($isAdmin) {
            $query = "SELECT d.* FROM detachement d ORDER BY d.datedetachement DESC";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            if (empty($departements)) {
                return [];
            }
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $types = str_repeat('i', count($departements));
            $query = "SELECT d.* FROM detachement d 
                      LEFT JOIN stuf s ON d.mecano = s.mecano
                      WHERE s.dep IN ($placeholders)
                      ORDER BY d.datedetachement DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param($types, ...$departements);
            $stmt->execute();
            $result = $stmt->get_result();
        }
        
        $detachements = [];
        while ($row = $result->fetch_assoc()) {
            $periodeTotal = intval($row['periode'] ?? 0) + intval($row['renouvellemnt1'] ?? 0) + 
                            intval($row['renouvellemnt2'] ?? 0) + intval($row['renouvellemnt3'] ?? 0);
            
            $dateFin = null;
            $monthsLeft = 0;
            $monthsCategory = 'all';
            $monthsClass = '';
            $monthsText = '';
            
            if (!empty($row['datedetachement']) && $row['datedetachement'] != '0000-00-00' && $periodeTotal > 0) {
                $dateFin = date('Y-m-d', strtotime($row['datedetachement'] . " +$periodeTotal years"));
                $diff = (strtotime($dateFin) - strtotime($currentDate)) / (60 * 60 * 24 * 30);
                $monthsLeft = max(0, round($diff));
                
                if ($dateFin < $currentDate) {
                    $monthsCategory = 'expired';
                    $monthsClass = 'months-red';
                    $monthsText = 'منتهي';
                } elseif ($monthsLeft <= 3) {
                    $monthsCategory = 'less3';
                    $monthsClass = 'months-red';
                    $monthsText = $monthsLeft . ' أشهر';
                } elseif ($monthsLeft <= 6) {
                    $monthsCategory = '3to6';
                    $monthsClass = 'months-yellow';
                    $monthsText = $monthsLeft . ' أشهر';
                } else {
                    $monthsCategory = 'more6';
                    $monthsClass = 'months-green';
                    $monthsText = $monthsLeft . ' أشهر';
                }
            } else {
                $monthsText = 'غير محدد';
                $monthsClass = 'months-yellow';
            }
            
            $docCount = $this->getDocumentsCount($row['id']);
            
            $detachements[] = [
                'id' => $row['id'],
                'mecano' => $row['mecano'],
                'nomprenom' => $row['nomprenom'] ?? '',
                'affectation' => $row['affectation'] ?? '',
                'source' => $row['source'] ?? '',
                'situation' => $row['situation'] ?? '',
                'datedetachement' => $row['datedetachement'] ?? '',
                'periode' => $row['periode'] ?? 0,
                'renouvellemnt1' => $row['renouvellemnt1'] ?? 0,
                'renouvellemnt2' => $row['renouvellemnt2'] ?? 0,
                'renouvellemnt3' => $row['renouvellemnt3'] ?? 0,
                'dossier' => $row['dossier'] ?? '',
                'observations' => $row['observations'] ?? '',
                'statut' => $row['statut'] ?? 0,
                'date_fin' => $dateFin,
                'months_left' => $monthsLeft,
                'months_category' => $monthsCategory,
                'months_class' => $monthsClass,
                'months_text' => $monthsText,
                'docCount' => $docCount
            ];
        }
        $stmt->close();
        
        return $detachements;
    }
    
    /**
     * Récupère le nombre de documents pour un détachement
     */
    public function getDocumentsCount($detachementId) {
        $query = "SELECT COUNT(*) as count FROM detachement_documents WHERE detachement_id = ?";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("i", $detachementId);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count;
    }
    
    /**
     * Récupère les statistiques
     */
    public function getStats($departements, $isAdmin = false) {
        $departs = $this->getDepartList($departements, $isAdmin);
        $detachements = $this->getDetachementList($departements, $isAdmin);
        
        $totalDeparts = count($departs);
        $retired = 0;
        $currentYearDeparts = 0;
        $currentYear = date('Y');
        
        foreach ($departs as $depart) {
            if ($depart['observation'] == 'تقاعد') $retired++;
            if ($depart['annee'] == $currentYear) $currentYearDeparts++;
        }
        
        $totalDetOut = 0;
        $totalDetIn = 0;
        $totalNonService = 0;
        
        foreach ($detachements as $det) {
            if ($det['situation'] == 'ملحق خارج الشركة') $totalDetOut++;
            elseif ($det['situation'] == 'ملحق لدى الشركة') $totalDetIn++;
            elseif ($det['situation'] == 'إحالة على عدم المباشرة') $totalNonService++;
        }
        
        return [
            'total_departs' => $totalDeparts,
            'retired' => $retired,
            'current_year' => $currentYearDeparts,
            'total_detachements_out' => $totalDetOut,
            'total_detachements_in' => $totalDetIn,
            'total_nonservice' => $totalNonService
        ];
    }
    
    /**
     * Ajoute un départ
     */
    public function addDepart($data) {
        $query = "INSERT INTO depart (mecano, datedepart, rest, cministere, annee, observation) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("isiiis", 
            $data['mecano'], 
            $data['datedepart'], 
            $data['restconge'], 
            $data['ministere'], 
            $data['anneedepart'], 
            $data['cause']
        );
        $result = $stmt->execute();
        $stmt->close();
        
        if ($result) {
            $updateStuf = "UPDATE stuf SET contrastage = 4 WHERE mecano = ?";
            $stmt2 = $this->db->prepare($updateStuf);
            $stmt2->bind_param("i", $data['mecano']);
            $stmt2->execute();
            $stmt2->close();
        }
        
        return $result;
    }
    
    /**
     * Ajoute ou met à jour un détachement
     */
    public function saveDetachement($data) {
        if (isset($data['id']) && $data['id'] > 0) {
            $query = "UPDATE detachement SET 
                      mecano = ?, nomprenom = ?, affectation = ?, source = ?, situation = ?,
                      datedetachement = ?, periode = ?, renouvellemnt1 = ?, renouvellemnt2 = ?,
                      renouvellemnt3 = ?, dossier = ?, observations = ?, statut = ?
                      WHERE id = ?";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("isssssiiisssi", 
                $data['mecano'], $data['nomprenom'], $data['affectation'], $data['source'], 
                $data['situation'], $data['datedetachement'], $data['periode'], 
                $data['renouvellemnt1'], $data['renouvellemnt2'], $data['renouvellemnt3'],
                $data['dossier'], $data['observations'], $data['statut'], $data['id']
            );
        } else {
            $query = "INSERT INTO detachement (mecano, nomprenom, affectation, source, situation,
                      datedetachement, periode, renouvellemnt1, renouvellemnt2, renouvellemnt3,
                      dossier, observations, statut) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param("isssssiiisss", 
                $data['mecano'], $data['nomprenom'], $data['affectation'], $data['source'],
                $data['situation'], $data['datedetachement'], $data['periode'],
                $data['renouvellemnt1'], $data['renouvellemnt2'], $data['renouvellemnt3'],
                $data['dossier'], $data['observations']
            );
        }
        
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Supprime un détachement
     */
    public function deleteDetachement($id) {
        $query = "DELETE FROM detachement WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Ferme un détachement (statut = 1)
     */
    public function closeDetachement($id) {
        $query = "UPDATE detachement SET statut = 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Récupère les informations d'un employé pour le formulaire de départ
     */
    public function getUserInfo($mecano) {
        $query = "SELECT s.mecano, s.nom, s.daten, s.daterec, s.dep, 
                         dep.depar as departement, COALESCE(nc.rest, 0) as restconge,
                         DATE_ADD(s.daten, INTERVAL 60 YEAR) as dateretraite
                  FROM stuf s
                  LEFT JOIN dep ON s.dep = dep.id
                  LEFT JOIN nbconge nc ON s.mecano = nc.mecano
                  WHERE s.mecano = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $mecano);
        $stmt->execute();
        $result = $stmt->get_result();
        $userInfo = $result->fetch_assoc();
        $stmt->close();
        return $userInfo;
    }
    
    /**
     * Récupère les employés pour le select
     */
    public function getEmployees() {
        $query = "SELECT mecano FROM stuf WHERE contrastage IN (0,1,3) ORDER BY mecano ASC";
        $result = $this->db->query($query);
        $employees = [];
        while ($row = $result->fetch_assoc()) {
            $employees[] = $row['mecano'];
        }
        return $employees;
    }
    
    /**
     * Récupère les années pour le select
     */
    public function getYears() {
        $years = [];
        for ($year = 2011; $year <= 2035; $year++) {
            $years[] = $year;
        }
        return $years;
    }
    
    /**
     * Récupère les causes de départ
     */
    public function getDepartCauses() {
        return ['تقاعد', 'عزل', 'وفاة', 'إنهاء تربّص', 'إلحاق', 'تقاعد مبكّر', 'إنهاء إلحاق', 'إحالة على عدم المباشرة'];
    }
    
    /**
     * Récupère les options de dossier
     */
    public function getDossierOptions() {
        return [
            'مؤشّر من رئاسة الحكومة',
            'في طور التأشير (رئاسة الحكومة)',
            'في طور التأشير (جهة الإلحاق)',
            'في طور التجديد (رئاسة الحكومة)',
            'في طور التجديد (جهة الإلحاق)',
            'في طور الإدماج (رئاسة الحكومة)',
            'في طور الإدماج (جهة الإلحاق)'
        ];
    }
    
    /**
     * Récupère les situations
     */
    public function getSituations() {
        return ['ملحق خارج الشركة', 'ملحق لدى الشركة', 'إنهاء إلحاق', 'إحالة على عدم المباشرة'];
    }
    
    /**
     * Récupère la liste des documents d'un détachement
     */
    public function getDocuments($detachementId) {
        $query = "SELECT * FROM detachement_documents WHERE detachement_id = ? ORDER BY upload_date DESC";
        $stmt = $this->db->prepare($query);
        if (!$stmt) {
            return [];
        }
        $stmt->bind_param("i", $detachementId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $documents = [];
        while ($row = $result->fetch_assoc()) {
            $documents[] = $row;
        }
        $stmt->close();
        return $documents;
    }
    
    /**
     * Ajoute un document
     */
    public function addDocument($detachementId, $fileName, $filePath, $fileSize, $fileType, $description, $userId) {
        $query = "INSERT INTO detachement_documents (detachement_id, document_name, file_path, file_size, document_type, description, uploaded_by) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("issdssi", $detachementId, $fileName, $filePath, $fileSize, $fileType, $description, $userId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    /**
     * Supprime un document
     */
    public function deleteDocument($documentId) {
        $query = "SELECT file_path FROM detachement_documents WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $documentId);
        $stmt->execute();
        $stmt->bind_result($filePath);
        $stmt->fetch();
        $stmt->close();
        
        if ($filePath && file_exists($filePath)) {
            unlink($filePath);
        }
        
        $query = "DELETE FROM detachement_documents WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $documentId);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
}
?>