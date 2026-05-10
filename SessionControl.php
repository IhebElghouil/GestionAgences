<?php
if (isset($_SESSION['congidGA'])) {
    if ($_SESSION['departement'] !== "admin") {
        // Create a database connection using mysqli
        include('DbConnexion.php');
        
        if (is_array($_SESSION['departement'])) {
            $departmentNames = array();
            
            foreach ($_SESSION['departement'] as $depId) {
                $stmt = $conn->prepare("SELECT depar FROM dep WHERE id = ?");
                $stmt->bind_param("s", $depId);
                $stmt->execute();
                $stmt->bind_result($departmentName);

                if ($stmt->fetch()) {
                    $departmentNames[] = htmlspecialchars($departmentName);
                }

                $stmt->close();
            }
            
            // Afficher les départments avec séparateur
            if (count($departmentNames) > 1) {
                echo '<center><h2>' . implode(' | ', $departmentNames) . '</h2></center><br/>';
            } else {
                echo '<center><h2>' . $departmentNames[0] . '</h2></center><br/>';
            }
            
            $conn->close();
        } else {
            $stmt = $conn->prepare("SELECT depar FROM dep WHERE id = ?");
            $stmt->bind_param("s", $_SESSION['departement']);
            $stmt->execute();
            $stmt->bind_result($departmentName);

            if ($stmt->fetch()) {
                echo '<center><h2>' . htmlspecialchars($departmentName) . '</h2></center><br/>';
            }

            $stmt->close();
            $conn->close();
        }
    } else {
        echo '<center><h2>بجميع الوكالات و الورشات</h2></center><br/>';
    }
} else {
    echo '<script language="Javascript">
        document.location.replace("index.php");
    </script>';
}
?>
