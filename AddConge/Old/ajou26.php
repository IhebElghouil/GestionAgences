<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Document sans titre</title>

<?php


require("connection.php");

?>
</head>

<body>

<?php
$req=mysql_query("SELECT * FROM nbconge");
$n=0;
while($r=mysql_fetch_row($req)){
	$rest=$r[3];
	$nbj2=26;
$req2=mysql_query("UPDATE nbconge SET `nbj1` = '".$rest."',nbj2='26' WHERE mecano ='".$r[0]."'");	
$req2=mysql_query("UPDATE stuf SET anneeprec = '".$rest."',
anneeactu = '26' WHERE mecano ='".$r[0]."'");	
	$n=$n+1;
	
	
}
echo $n;
?>


</body>
</html>
