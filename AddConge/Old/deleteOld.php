<!DOCTYPE html>
<meta charset="UTF-8">
<head>
<style>
table, th, td {
    align: right;
	direction: rtl;
	line-height: 2.5;
	font-size:16px;
	 
}
.left {
  direction: rtl;
  font-weight:bold;
}
table{
width: 100%;
}
td{
text-align: right;
}

</style>
  <style>
@media print {
      header, footer {
        display: none; /* Masquer l'en-tête et le pied de page */
      }
    }
  </style>
</head>
<body>
<?php



// Example usage:




require_once(__DIR__ . "/DbConnexion.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$ID=$_GET['id'];
$Nbjours=$_GET['Nbjours'];
$mecano=$_GET['mecano'];



$resDelete = $conn->prepare("DELETE FROM conge WHERE id = ? AND mecano = ?");
$resDelete->bind_param("ss", $ID, $mecano); // "ss" means both are strings
if ($resDelete === false) {
	

    die('Error preparing DELETE statement: ' . $conn->error);
}


$resDelete->execute();

// Get the number of affected rows
$rowsDeleted = $resDelete->affected_rows;

// Prepare UPDATE query
$resUpdate = $conn->prepare("UPDATE nbconge SET rest = rest + ? WHERE mecano = ?");
if ($resUpdate === false) {
    die('Error preparing UPDATE statement: ' . $conn->error);
}
$resUpdate->bind_param("is", $Nbjours, $mecano); // "i" for integer, "s" for string

// Execute the queries
if ($rowsDeleted==1 && $resUpdate->execute()) {
    echo "<script language='javascript'>alert('تمّ حذف الراحة السنويّة بنجاح');history.go(-1);</script>";
} else {
    echo "<script language='javascript'>alert('يرجي إعادة الحذف');history.go(-1);</script>";
}

// Close the prepared statements
$resDelete->close();
$resUpdate->close();
?>
</body>
</html>