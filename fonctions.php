<?php
// fonctions.php

class CongesFunctions {
    private $connection;
    
    public function __construct($connection) {
        $this->connection = $connection;
    }
    
    /**
     * Récupère les données des congés annuels
     */
    public function getCongesAnnuelData($departement, $isAdmin = false) {
        $PremierJourCtrl = date('Y-01-01', strtotime('next year'));
        $DernierJourCtrl = date('Y-12-31', strtotime('last year'));
        $DateFinRequete = date('Y-12-31');
        $DateDebutRequete = date('Y-01-01');
        
        if (!$isAdmin) {
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
                        SELECT SUM(DATEDIFF(LEAST(?, ac.datefin), GREATEST(?, ac.datedebut)))
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
                WHERE contrastage IN (0, 1, 3) AND stuf.dep = ?
                ORDER BY nbconge.rest DESC
            ";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ssssi", $PremierJourCtrl, $DernierJourCtrl, $DateFinRequete, $DateDebutRequete, $departement);
        } else {
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
                        SELECT SUM(DATEDIFF(LEAST(?, ac.datefin), GREATEST(?, ac.datedebut)))
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
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("ssss", $PremierJourCtrl, $DernierJourCtrl, $DateFinRequete, $DateDebutRequete);
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
     * Récupère les données des repos compensateurs
     */
    public function getReposCompensateurData($departement, $isAdmin = false) {
        if (!$isAdmin) {
            $query = "
                SELECT autreconge.mecano, nom, datedebut, datefin, dep.depar, commentaire, anne
                FROM autreconge
                LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
                LEFT JOIN dep ON stuf.dep = dep.id
                WHERE type = 7 AND contrastage IN (0, 1, 3) AND stuf.dep = ?
                ORDER BY datefin DESC
            ";
            $stmt = $this->connection->prepare($query);
            $stmt->bind_param("i", $departement);
        } else {
            $query = "
                SELECT autreconge.mecano, nom, datedebut, datefin, dep.depar, commentaire, anne
                FROM autreconge
                LEFT JOIN stuf ON autreconge.mecano = stuf.mecano
                LEFT JOIN dep ON stuf.dep = dep.id
                WHERE type = 7 AND contrastage IN (0, 1, 3)
                ORDER BY datefin DESC
            ";
            $stmt = $this->connection->prepare($query);
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
     * Calcule les jours de maladie à soustraire
     */
    public function calculateJoursMaladie($totalMaladie) {
        $maladieBrackets = [
            [7, 19, 1], [20, 32, 2], [33, 45, 3], [46, 58, 4], [59, 71, 5],
            [72, 84, 6], [85, 97, 7], [98, 110, 8], [111, 123, 9], [124, 136, 10],
            [137, 149, 11], [150, 162, 12], [163, 175, 13], [176, 188, 14], [189, 201, 15],
            [202, 214, 16], [215, 227, 17], [228, 240, 18], [241, 253, 19], [254, 266, 20],
            [267, 279, 21], [280, 292, 22], [293, 305, 23], [306, 318, 24], [319, 331, 25],
            [332, 344, 26], [345, 357, 27], [358, 370, 28]
        ];
        
        $NbJourMaladieSoustract = 0;
        foreach ($maladieBrackets as $bracket) {
            if ($totalMaladie >= $bracket[0] && $totalMaladie <= $bracket[1]) {
                $NbJourMaladieSoustract = $bracket[2];
                break;
            }
        }
        
        return $NbJourMaladieSoustract;
    }
    
    /**
     * Calcule les statistiques pour les congés annuels
     */
    public function calculateCongesStats($data) {
        $stats = [
            'totalEmployees' => 0,
            'totalSolde' => 0,
            'maladieCases' => 0
        ];
        
        foreach ($data as $row) {
            $stats['totalEmployees']++;
            $stats['totalSolde'] += $row['rest'];
            
            $joursMaladie = $this->calculateJoursMaladie($row['TotalJoursMaladie']);
            if ($joursMaladie > 0) {
                $stats['maladieCases']++;
            }
        }
        
        return $stats;
    }
    
    /**
     * Calcule les statistiques pour les repos compensateurs
     */
    public function calculateCompensationStats($data) {
        $stats = [
            'totalCompensations' => 0,
            'currentCompensations' => 0,
            'upcomingCompensations' => 0,
            'uniqueDepartments' => []
        ];
        
        $currentDate = date('Y-m-d');
        
        foreach ($data as $row) {
            $stats['totalCompensations']++;
            
            // Track unique departments
            if (!in_array($row['depar'], $stats['uniqueDepartments'])) {
                $stats['uniqueDepartments'][] = $row['depar'];
            }
            
            // Determine status
            if ($currentDate >= $row['datedebut'] && $currentDate <= $row['datefin']) {
                $stats['currentCompensations']++;
            } else if ($row['datedebut'] > $currentDate) {
                $stats['upcomingCompensations']++;
            }
        }
        
        return $stats;
    }
}
?>