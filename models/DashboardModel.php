<?php
// models/DashboardModel.php
require_once __DIR__ . '/../config/Database.php';

class DashboardModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Récupère le nom du département
     */
    public function getDepartmentName($departements, $isAdmin) {
        if ($isAdmin) {
            return 'بجميع الوكالات و الورشات';
        }
        
        if (empty($departements)) {
            return 'جميع الأقسام';
        }
        
        $firstDept = $departements[0];
        $stmt = $this->db->prepare("SELECT depar FROM dep WHERE id = ?");
        $stmt->bind_param("i", $firstDept);
        $stmt->execute();
        $stmt->bind_result($deptName);
        $stmt->fetch();
        $stmt->close();
        
        return count($departements) > 1 ? 
            $deptName . ' و ' . (count($departements) - 1) . ' أقسام أخرى' : 
            $deptName;
    }
    
    /**
     * Récupère le nombre d'accidents expirés
     */
    public function getExpiredAccidentsCount($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT COUNT(autreconge.mecano) FROM autreconge
                     LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
                     WHERE type = 6 AND contrastage IN (0, 1, 3) 
                     AND datefin < CURDATE() AND valide = 1";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT COUNT(autreconge.mecano) FROM autreconge
                     LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
                     WHERE type = 6 AND contrastage IN (0, 1, 3) 
                     AND stuf.dep IN ($placeholders) 
                     AND datefin < CURDATE() AND valide = 1";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count ?? 0;
    }
    
    /**
     * Récupère le nombre de cartes professionnelles expirées
     */
    public function getExpiredProfessionalCardsCount($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT COUNT(DISTINCT cartes.mecano) 
                     FROM cartes
                     LEFT JOIN stuf ON cartes.mecano = stuf.mecano
                     WHERE contrastage IN (0, 1, 3) 
                     AND stuf.titre IN (6, 7, 8) 
                     AND cartes.type = 0 
                     AND (finvalidite < CURDATE() OR finvalidite = '0000-00-00')";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT COUNT(DISTINCT cartes.mecano) 
                     FROM cartes
                     LEFT JOIN stuf ON cartes.mecano = stuf.mecano
                     WHERE stuf.dep IN ($placeholders)
                     AND contrastage IN (0, 1, 3) 
                     AND stuf.titre IN (6, 7, 8) 
                     AND cartes.type = 0 
                     AND (finvalidite < CURDATE() OR finvalidite = '0000-00-00')";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count ?? 0;
    }
    
    /**
     * Récupère le nombre de permis de conduire expirés
     */
    public function getExpiredDrivingLicensesCount($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT COUNT(DISTINCT cartes.mecano) 
                     FROM cartes
                     LEFT JOIN stuf ON cartes.mecano = stuf.mecano
                     WHERE contrastage IN (0, 1, 3) 
                     AND stuf.titre IN (6, 7, 8) 
                     AND cartes.type = 1 
                     AND (finvalidite < CURDATE() OR finvalidite = '0000-00-00')";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT COUNT(DISTINCT cartes.mecano) 
                     FROM cartes
                     LEFT JOIN stuf ON cartes.mecano = stuf.mecano
                     WHERE stuf.dep IN ($placeholders) 
                     AND contrastage IN (0, 1, 3) 
                     AND stuf.titre IN (6, 7, 8) 
                     AND cartes.type = 1 
                     AND (finvalidite < CURDATE() OR finvalidite = '0000-00-00')";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count ?? 0;
    }
    
    /**
     * Récupère le nombre de certificats médicaux expirés
     */
    public function getExpiredMedicalCertsCount($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT COUNT(DISTINCT certificats.mecano) 
                     FROM certificats
                     LEFT JOIN stuf ON certificats.mecano = stuf.mecano
                     WHERE certificats.Etat = 1
                     AND contrastage IN (0, 1, 3)
                     AND (DateFin < CURDATE() OR DateFin = '0000-00-00')";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT COUNT(DISTINCT certificats.mecano) 
                     FROM certificats
                     LEFT JOIN stuf ON certificats.mecano = stuf.mecano
                     WHERE stuf.dep IN ($placeholders) 
                     AND certificats.Etat = 1
                     AND contrastage IN (0, 1, 3)
                     AND (DateFin < CURDATE() OR DateFin = '0000-00-00')";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count ?? 0;
    }
    
    /**
     * Récupère le nombre de départs à la retraite imminents
     */
    public function getUpcomingRetirementsCount($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT COUNT(*) FROM stuf 
                     WHERE contrastage IN (0, 1, 3) 
                     AND DATE_ADD(daten, INTERVAL 60 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT COUNT(*) FROM stuf 
                     WHERE dep IN ($placeholders) 
                     AND contrastage IN (0, 1, 3) 
                     AND DATE_ADD(daten, INTERVAL 60 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count ?? 0;
    }
    
    /**
     * Récupère le nombre de promotions imminentes
     */
    public function getUpcomingPromotionsCount($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT COUNT(*) FROM stuf 
                     WHERE contrastage IN (0, 1, 3) 
                     AND (DATE_ADD(daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                     OR DATE_ADD(daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                     OR DATE_ADD(daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH))";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT COUNT(*) FROM stuf 
                     WHERE dep IN ($placeholders) 
                     AND contrastage IN (0, 1, 3) 
                     AND (DATE_ADD(daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                     OR DATE_ADD(daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                     OR DATE_ADD(daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH))";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count ?? 0;
    }
    
    /**
     * Récupère le nombre de changements de carrière récents
     */
    public function getCareerChangesCount($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT COUNT(*) FROM carriere c
                     WHERE c.dateeffet >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT COUNT(*) FROM carriere c
                     LEFT JOIN stuf s ON c.mecano = s.mecano
                     WHERE s.dep IN ($placeholders) 
                     AND c.dateeffet >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count ?? 0;
    }
    
    /**
     * Récupère le nombre de nouveaux recrutements
     */
    public function getNewRecruitsCount($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT COUNT(*) FROM stuf 
                     WHERE contrastage IN (0, 1, 3) 
                     AND daterec >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT COUNT(*) FROM stuf 
                     WHERE dep IN ($placeholders) 
                     AND contrastage IN (0, 1, 3) 
                     AND daterec >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count ?? 0;
    }
    
    /**
     * Récupère le nombre de sanctions non clôturées
     */
    public function getOpenSanctionsCount($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT COUNT(*) FROM sanctions 
                     WHERE statut != 'closed'";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT COUNT(*) FROM sanctions s
                     LEFT JOIN stuf ON s.mecano = stuf.mecano
                     WHERE stuf.dep IN ($placeholders) 
                     AND s.statut != 'closed'";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count ?? 0;
    }
    
    /**
     * Récupère le nombre de dossiers de détachement non clôturés
     */
    public function getDetachmentFilesCount($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT COUNT(*) FROM detachement 
                     WHERE statut != 0";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT COUNT(*) FROM detachement d
                     LEFT JOIN stuf s ON d.mecano = s.mecano
                     WHERE s.dep IN ($placeholders) 
                     AND d.statut != 0";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        
        return $count ?? 0;
    }
    
    /**
     * Récupère les détails des départs à la retraite
     */
    public function getRetirementDetails($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT s.mecano, s.nom, DATE_ADD(s.daten, INTERVAL 60 YEAR) as retirement_date, 
                            COALESCE(nc.rest, 0) as leave_balance
                     FROM stuf s
                     LEFT JOIN nbconge nc ON s.mecano = nc.mecano
                     WHERE s.contrastage IN (0, 1, 3) 
                     AND DATE_ADD(s.daten, INTERVAL 60 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                     ORDER BY retirement_date ASC
                     LIMIT 10";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT s.mecano, s.nom, DATE_ADD(s.daten, INTERVAL 60 YEAR) as retirement_date,
                            COALESCE(nc.rest, 0) as leave_balance
                     FROM stuf s
                     LEFT JOIN nbconge nc ON s.mecano = nc.mecano
                     WHERE s.dep IN ($placeholders) 
                     AND s.contrastage IN (0, 1, 3) 
                     AND DATE_ADD(s.daten, INTERVAL 60 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                     ORDER BY retirement_date ASC
                     LIMIT 10";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        
        return $data;
    }
    
    /**
     * Récupère les détails des promotions
     */
    public function getPromotionDetails($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT s.mecano, s.nom, 
                            CASE 
                                WHEN DATE_ADD(s.daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN '20 سنة (ترقية ثانية)'
                                WHEN DATE_ADD(s.daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN '10 سنوات (ترقية أولى)'
                                WHEN DATE_ADD(s.daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN '57 سنة (ترقية استثنائية)'
                            END as promotion_type,
                            CASE 
                                WHEN DATE_ADD(s.daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN DATE_ADD(s.daterec, INTERVAL 20 YEAR)
                                WHEN DATE_ADD(s.daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN DATE_ADD(s.daterec, INTERVAL 10 YEAR)
                                WHEN DATE_ADD(s.daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN DATE_ADD(s.daten, INTERVAL 57 YEAR)
                            END as promotion_date
                     FROM stuf s
                     WHERE s.contrastage IN (0, 1, 3) 
                     AND (DATE_ADD(s.daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                     OR DATE_ADD(s.daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                     OR DATE_ADD(s.daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH))
                     ORDER BY promotion_date ASC
                     LIMIT 20";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT s.mecano, s.nom, 
                            CASE 
                                WHEN DATE_ADD(s.daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN '20 سنة (ترقية ثانية)'
                                WHEN DATE_ADD(s.daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN '10 سنوات (ترقية أولى)'
                                WHEN DATE_ADD(s.daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN '57 سنة (ترقية استثنائية)'
                            END as promotion_type,
                            CASE 
                                WHEN DATE_ADD(s.daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN DATE_ADD(s.daterec, INTERVAL 20 YEAR)
                                WHEN DATE_ADD(s.daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN DATE_ADD(s.daterec, INTERVAL 10 YEAR)
                                WHEN DATE_ADD(s.daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH) THEN DATE_ADD(s.daten, INTERVAL 57 YEAR)
                            END as promotion_date
                     FROM stuf s
                     WHERE s.dep IN ($placeholders) 
                     AND s.contrastage IN (0, 1, 3) 
                     AND (DATE_ADD(s.daterec, INTERVAL 20 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                     OR DATE_ADD(s.daterec, INTERVAL 10 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                     OR DATE_ADD(s.daten, INTERVAL 57 YEAR) BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 3 MONTH))
                     ORDER BY promotion_date ASC
                     LIMIT 20";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        
        return $data;
    }
    
    /**
     * Récupère les détails des changements de carrière
     */
    public function getCareerDetails($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT c.mecano, s.nom, c.ancienrang, c.nouveaurang, c.dateeffet, c.commission
                     FROM carriere c
                     LEFT JOIN stuf s ON c.mecano = s.mecano
                     WHERE c.dateeffet >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                     ORDER BY c.dateeffet DESC
                     LIMIT 10";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT c.mecano, s.nom, c.ancienrang, c.nouveaurang, c.dateeffet, c.commission
                     FROM carriere c
                     LEFT JOIN stuf s ON c.mecano = s.mecano
                     WHERE s.dep IN ($placeholders) 
                     AND c.dateeffet >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                     ORDER BY c.dateeffet DESC
                     LIMIT 10";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        
        return $data;
    }
    
    /**
     * Récupère les détails des nouveaux recrus
     */
    public function getNewRecruitsDetails($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT s.mecano, s.nom, s.daterec, titres.libellet as fonction
                     FROM stuf s
                     LEFT JOIN titres ON s.titre = titres.id
                     WHERE s.contrastage IN (0, 1, 3) 
                     AND s.daterec >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
                     ORDER BY s.daterec DESC
                     LIMIT 10";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT s.mecano, s.nom, s.daterec, titres.libellet as fonction
                     FROM stuf s
                     LEFT JOIN titres ON s.titre = titres.id
                     WHERE s.dep IN ($placeholders) 
                     AND s.contrastage IN (0, 1, 3) 
                     AND s.daterec >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)
                     ORDER BY s.daterec DESC
                     LIMIT 10";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        
        return $data;
    }
    
    /**
     * Récupère les détails des sanctions non clôturées
     */
    public function getOpenSanctionsDetails($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT s.idsanction, s.mecano, s.nom, s.grade, s.faute, s.sanction, 
                            s.datefaute, s.datesanction, s.statut, s.conseil_discipline,
                            DATEDIFF(CURDATE(), s.datefaute) as jours_ecoules
                     FROM sanctions s
                     WHERE s.statut != 'closed'
                     ORDER BY 
                        CASE s.statut 
                            WHEN 'open' THEN 1 
                            WHEN 'pending' THEN 2 
                            WHEN 'review' THEN 3 
                            ELSE 4 
                        END,
                        s.datefaute ASC
                     LIMIT 15";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT s.idsanction, s.mecano, s.nom, s.grade, s.faute, s.sanction, 
                            s.datefaute, s.datesanction, s.statut, s.conseil_discipline,
                            DATEDIFF(CURDATE(), s.datefaute) as jours_ecoules
                     FROM sanctions s
                     LEFT JOIN stuf st ON s.mecano = st.mecano
                     WHERE st.dep IN ($placeholders) 
                     AND s.statut != 'closed'
                     ORDER BY 
                        CASE s.statut 
                            WHEN 'open' THEN 1 
                            WHEN 'pending' THEN 2 
                            WHEN 'review' THEN 3 
                            ELSE 4 
                        END,
                        s.datefaute ASC
                     LIMIT 15";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        
        return $data;
    }
    
    /**
     * Récupère les détails des dossiers de détachement non clôturés
     */
    public function getDetachmentFilesDetails($departements, $isAdmin) {
        if ($isAdmin) {
            $query = "SELECT d.id, d.mecano, d.nomprenom, d.affectation, d.source, d.situation, 
                            d.datedetachement, d.periode, d.renouvellemnt1, d.renouvellemnt2, 
                            d.renouvellemnt3, d.dossier, d.observations, d.statut,
                            DATE_ADD(
                                d.datedetachement, 
                                INTERVAL (
                                    COALESCE(NULLIF(TRIM(d.periode), ''), 0) +
                                    COALESCE(NULLIF(TRIM(d.renouvellemnt1), ''), 0) +
                                    COALESCE(NULLIF(TRIM(d.renouvellemnt2), ''), 0) +
                                    COALESCE(NULLIF(TRIM(d.renouvellemnt3), ''), 0)
                                ) YEAR
                            ) AS date_fin,
                            DATEDIFF(
                                DATE_ADD(
                                    d.datedetachement, 
                                    INTERVAL (
                                        COALESCE(NULLIF(TRIM(d.periode), ''), 0) +
                                        COALESCE(NULLIF(TRIM(d.renouvellemnt1), ''), 0) +
                                        COALESCE(NULLIF(TRIM(d.renouvellemnt2), ''), 0) +
                                        COALESCE(NULLIF(TRIM(d.renouvellemnt3), ''), 0)
                                    ) YEAR
                                ),
                                CURDATE()
                            ) as jours_restants
                     FROM detachement d
                     WHERE d.statut != 0
                     ORDER BY 
                        CASE WHEN d.statut = 1 THEN 0 ELSE 1 END,
                        jours_restants ASC
                     LIMIT 15";
            $stmt = $this->db->prepare($query);
        } else {
            $placeholders = implode(',', array_fill(0, count($departements), '?'));
            $query = "SELECT d.id, d.mecano, d.nomprenom, d.affectation, d.source, d.situation, 
                            d.datedetachement, d.periode, d.renouvellemnt1, d.renouvellemnt2, 
                            d.renouvellemnt3, d.dossier, d.observations, d.statut,
                            DATE_ADD(
                                d.datedetachement, 
                                INTERVAL (
                                    COALESCE(NULLIF(TRIM(d.periode), ''), 0) +
                                    COALESCE(NULLIF(TRIM(d.renouvellemnt1), ''), 0) +
                                    COALESCE(NULLIF(TRIM(d.renouvellemnt2), ''), 0) +
                                    COALESCE(NULLIF(TRIM(d.renouvellemnt3), ''), 0)
                                ) YEAR
                            ) AS date_fin,
                            DATEDIFF(
                                DATE_ADD(
                                    d.datedetachement, 
                                    INTERVAL (
                                        COALESCE(NULLIF(TRIM(d.periode), ''), 0) +
                                        COALESCE(NULLIF(TRIM(d.renouvellemnt1), ''), 0) +
                                        COALESCE(NULLIF(TRIM(d.renouvellemnt2), ''), 0) +
                                        COALESCE(NULLIF(TRIM(d.renouvellemnt3), ''), 0)
                                    ) YEAR
                                ),
                                CURDATE()
                            ) as jours_restants
                     FROM detachement d
                     LEFT JOIN stuf s ON d.mecano = s.mecano
                     WHERE s.dep IN ($placeholders) 
                     AND d.statut != 0
                     ORDER BY 
                        CASE WHEN d.statut = 1 THEN 0 ELSE 1 END,
                        jours_restants ASC
                     LIMIT 15";
            $stmt = $this->db->prepare($query);
            $stmt->bind_param(str_repeat('i', count($departements)), ...$departements);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        
        return $data;
    }
}
?>