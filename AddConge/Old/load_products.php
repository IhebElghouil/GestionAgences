<?php 

session_start();
require('connection.php');

$RequeteFindRest=mysql_query("SELECT rest FROM nbconge where mecano='{$_POST["id"]}'");	
$num_rows = mysql_num_rows($RequeteFindRest);

if ($num_rows>0){
	
while($rNom=mysql_fetch_row($RequeteFindRest)) {
	 echo "<option value='".$rNom[0]."'>".$rNom[0]."</option>";
	}
}
else{
		  echo "<option value=''>BOB</option>";
	}
	
 
?>