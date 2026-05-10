<?php session_start(); 
?>
<html>
<head>
<title>pointage</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style type="text/css">
<!--
body {
	background-color: #317300;
}
.Style1 {
	color: #FFFFFF;
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size:22px;
}
.sof {
	color: #FFFFFF;
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size:16px;
}
.d{
height:302px; width:668px; overflow:auto; 
}
-->
</style></head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<!-- Save for Web Slices (pointage.psd) -->
<center>
  <br>
  <br>
  <table id="Table_01" width="901" height="600" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td colspan="8">
			<img src="images/pointage_01.gif" width="900" height="170" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="170" alt=""></td>
	</tr>
	<tr>
		<td rowspan="7">
			<img src="images/pointage_02.gif" width="122" height="430" alt=""></td>
		<td width="668" height="302" colspan="6" background="images/pointage_03.gif" class="Style1" align="center" valign="middle">
		<div class="d Style1" align="center" valign="middle">
      <?php
	  if(!@$_SESSION['idloginn']) { require("login.php"); }else{
	  if(!@$_GET['page']) require("affichage1.php");
	  if(@$_GET['page']=="enr") require("tobase2.php");
	  if(@$_GET['page']=="tobase") require("tobase.php");
	  if(@$_GET['page']=="frm") {session_destroy(); header("location:index.php");}
	  if(@$_GET['page']=="aff") require("affichage1.php");
	
	  }
	  ?>	 </div> </td>
		<td rowspan="7">
			<img src="images/pointage_04.gif" width="110" height="430" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="302" alt=""></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">
			<img src="images/pointage_05.gif" width="289" height="70" alt=""></td>
		<td rowspan="3"><a href="index.php?page=frm"><img src="images/pointage_06.gif" alt="" width="88" height="78" border="0"></a></td>
		<td colspan="3">
			<img src="images/pointage_07.gif" width="291" height="64" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="64" alt=""></td>
	</tr>
	<tr>
		<td rowspan="5">
			<img src="images/pointage_08.gif" width="23" height="64" alt=""></td>
		<td rowspan="3">
			<a href="index.php?page=enr"><img src="images/pointage_09.gif" alt="" width="220" height="45" border="0"></a></td>
		<td rowspan="5">
			<img src="images/pointage_10.gif" width="48" height="64" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="6" alt=""></td>
	</tr>
	<tr>
		<td rowspan="3">
			<a href="index.php?page=aff"><img src="images/pointage_11.gif" alt="" width="213" height="45" border="0"></a></td>
		<td rowspan="4">
			<img src="images/pointage_12.gif" width="76" height="58" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="8" alt=""></td>
	</tr>
	<tr>
		<td rowspan="3">
			<img src="images/pointage_13.gif" width="88" height="50" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="31" alt=""></td>
	</tr>
	<tr>
		<td rowspan="2">
			<img src="images/pointage_14.gif" width="220" height="19" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="6" alt=""></td>
	</tr>
	<tr>
		<td>
			<img src="images/pointage_15.gif" width="213" height="13" alt=""></td>
		<td>
			<img src="images/spacer.gif" width="1" height="13" alt=""></td>
	</tr>
</table>
</center>
<!-- End Save for Web Slices -->
</body>
</html>