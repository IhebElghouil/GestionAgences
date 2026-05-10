<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>POINTAGE</title>
<style type="text/css">
<!--
.Style1 {color: #FFFFFF}
-->
</style>
</head>

<body>
<center>
<?php

require("connection.php");

$ech="";

if($_GET['dep']!='') {
$re=mysql_query("select * from dep where id='".$_GET['dep']."'");
$r=mysql_fetch_row($re);
$ech.=" قسم : &nbsp&nbsp".$r[2]."<br>";
}
if($_GET['service']!='') {
$re=mysql_query("select * from service where id='".$_GET['service']."'");
$rser=mysql_fetch_row($re);
$ech.=" المصلحة : &nbsp&nbsp".$rser[1]."<br>";
}

echo "<h2>".$ech." </h2><h3>".date("Y/m")." : لشهر</h3>";



?>
<table width="400" border="1" bordercolor="#000000">
  <tr>
    <td width="157" bgcolor="#333333"><div align="center"><strong><span class="Style1">: عدد أيام الراحة المتبقية</span></strong></div></td>
   
    <td width="158" bgcolor="#333333"><div align="center"><strong><span class="Style1"> : الاسم واللقب</span></strong></div></td>
    <td width="63" bgcolor="#333333"><div align="center"><strong><span class="Style1">: رقم الالي</span></strong></div></td>
    </tr>
  <?php 

$reqq="";

if($_GET['dep']!='') {
$reqq="stuf.dep='".$_GET['dep']."'";
}
if($_GET['service']!='') {
if($reqq!="") $reqq.=" and ";
$reqq.="stuf.idservice='".$_GET['service']."'";
}
if($reqq!="") $reqq=" and ".$reqq;
  $req="select nbconge.*,stuf.nom,stuf.contrastage from nbconge,stuf where nbconge.mecano=stuf.mecano and contrastage<3".$reqq;

$resul=mysql_query($req);
while($r=mysql_fetch_row($resul)){
  ?>
  <tr>
    <td bgcolor="#CCCCCC"><div align="center"><?php if($r[5]!='2') echo $r[3];else echo $r[3]-(12-date("n")); ?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo $r[4]; ?></div></td>
    <td bgcolor="#CCCCCC"><div align="center"><?php echo $r[0]; ?></div></td>
    </tr>
<?php } ?>
</table>
  <br />
  <br />

</center>
</body>
</html>
