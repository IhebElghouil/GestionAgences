<!DOCTYPE html>
<html>
<head>
<title>Questionnaires</title>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="StyleSheet.css">
<link rel="stylesheet" href="CSS/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="JS/jquery.min.js"></script>
<script>
$(document).ready(function() {
$("#b2").click(function(){
str1=$('#myInput').val();
$("#Content").load("affiche_questionnaire.php", {'myInput':str1});
})
})
</script>
<script>
function hided()
{
str1=$('#myInput').val();
$("#Content").load("affiche_questionnaire.php", {'myInput':str1});
}
</script>
<?php
session_start();
require('connection.php');
?>


</head>
<body data-spy="scroll" data-target="#navbar-example">


<?php include('menu.php'); ?>

 <div class="container d-flex mt-4 p-4" dir=rtl align=center>
       		
        <div class="card mb-3">
		 <p dir="rtl"><b>الرقم الآلي</b></p>
        <input type="text" id="myInput" onkeyup="hided()" placeholder="الرقم الآلي" title="أدخل الرقم الآلي" dir="rtl">   
        </div>

</div>








	<div class="ex1" id="Content" style=display: none;>
 
</div>

</body>
</html> 