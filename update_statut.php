<?php
// update_statut_simple.php
require_once('Dbconnexion.php');

if(isset($_GET['mecano'])) {
    $mecano = intval($_GET['mecano']);
    
    $sql = "UPDATE stuf SET contrastage = 0 WHERE mecano = ? AND contrastage in (4,5,6)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $mecano);
    
    if($stmt->execute()) {
        echo '<script>
            alert("✅ تم تحديث الحالة بنجاح");
            window.location.href = "c_search_agents.php";
        </script>';
    } else {
        echo '<script>
            alert("❌ حدث خطأ أثناء التحديث");
            window.location.href = "c_search_agents.php";
        </script>';
    }
    
    $stmt->close();
}

$conn->close();
?>