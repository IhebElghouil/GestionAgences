<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="StyleSheet.css">
<link rel="stylesheet" href="CSS/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="JS/jquery.min.js"></script>
<script>
$(document).ready(function() {
$("#b2").click(function(){
str1=$('#myInput').val();
$("#Content").load("load-text.php", {'myInput':str1});
})
})
</script>


<?php
session_start();
require('connection.php');
?>
<script src="http://192.168.1.20:8080/GestionAgences/JS/jquery.min.js"></script>


<title>Congés Annuels</title>
</head>
<body data-spy="scroll" data-target="#navbar-example">

<div align="right"><a href="logout.php"><img src="images/exit.png" width="48" height="48" border="0" /><br/><b>خروج</b></a></div>


<?php include('menu.php'); ?>

 <div class="container d-flex mt-4 p-4" dir=rtl align=center>
       		
        <div class="card mb-3">
		 <p dir="rtl"><b>البحث بالرقم الآلي</b></p>
        <input type="text" id="myInput" onkeyup="myFunction()" placeholder="الرقم الآلي" title="أدخل الرقم الآلي" dir="rtl">   
        </div>
        <div class="card mb-3">
		<p></p>
		<p></p>
        <input type="button" value="عرض النتيجة" class="btn btn-info" id=b2>      
		</div>
</div>


<?php
 if (isset($_SESSION['congid']))
  {
  

if ($_SESSION['departement']="admin")
  {

	//echo '<a href="loginhistory.php">متابعة الولوج إلى التطبيقة</a>';    
  }
     else
	  
	  {
		 echo '<script language="Javascript">

document.location.replace("index.php");

</script>';
		  
  }}
?>






<div class="ex1" id="Content" style=display: none;>
 
</div>
</body>
</html>
