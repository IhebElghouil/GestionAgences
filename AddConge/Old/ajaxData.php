<?php 
// Include the database config file 
include_once 'dbConfig.php'; 
 
if(!empty($_POST["country_id"])){ 
    // Fetch state data based on the specific country 
    $query = "SELECT jrepos FROM stuf WHERE mecano = ".$_POST['country_id'].""; 
    $result = $db->query($query); 
     
    // Generate HTML of state options list 
    if($result->num_rows > 0){ 
       // echo '<option value="">Select State</option>'; 
        while($row = $result->fetch_assoc()){  
            echo '<option value="'.$row['jrepos'].'">'.$row['jrepos'].'</option>'; 
        } 
    }else{ 
        echo '<option value="">State not available</option>'; 
    } 

    // Fetch city data based on the specific state 
    $queryRest = "SELECT rest FROM nbconge WHERE mecano = ".$_POST['country_id']." "; 
    $resultRest = $db->query($query); 
     
    // Generate HTML of city options list 
    if($resultRest->num_rows > 0){ 
        //echo '<option value="">Select city</option>'; 
       while($rowRest = $resultRest->fetch_assoc()){ 
          
            echo '<option value="'.$rowRest['rest'].'">'.$rowRest['rest'].'</option>'; 
       } 
    }else{ 
        echo '<option value="">City not available</option>'; 
    } 
} 
?>