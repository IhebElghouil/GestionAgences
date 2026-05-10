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
<style>
#myTable {
  border-collapse: collapse;
  width: 40%;
  border: 1px solid #ddd;
  font-size: 15px;
}

#myTable th, #myTable td {
  text-align: right;
  padding: 12px;
}

#myTable tr {
  border-bottom: 1px solid #ddd;
}

#myTable tr.header, #myTable tr:hover {
  background-color: #f1f1f1;
}
#myTable tr:nth-child(even) {
            background-color: #eeeeee;
        }



#myTable1 {
  border-collapse: collapse;
  width: 50%;
  border: 1px solid #ddd;
  font-size: 15px;
}

#myTable1 th, #myTable1 td {
  text-align: right;
  padding: 12px;
}

#myTable1 tr {
  border-bottom: 1px solid #ddd;
}

#myTable1 tr.header, #myTable1 tr:hover {
  background-color: #f1f1f1;
}
#myTable1 tr:nth-child(even) {
            background-color: #eeeeee;
        }
</style>
</head>

<body>
<center>
<?php

require("connection.php");
if(@$_GET['annee'] && @$_GET['mois']){
$d1=$_GET['annee']."-".$_GET['mois']."-01";
$d2=$_GET['annee']."-".$_GET['mois']."-31";

echo "<h2>".$_GET['annee']."/".$_GET['mois']." : الراحات لشهر </h2>";
$ech="";
$reqq="";
if($_GET['dep']!='') {
$re=mysql_query("select * from dep where id='".$_GET['dep']."'");
$r=mysql_fetch_row($re);
$ech.=" قسم : &nbsp&nbsp".$r[2]."<br>";
$reqq.=" and stuf.dep='".$_GET['dep']."'";
}
if($_GET['service']!='') {
$re=mysql_query("select * from service where id='".$_GET['service']."'");
$rser=mysql_fetch_row($re);
$ech.=" المصلحة : &nbsp&nbsp".$rser[1]."<br>";
$reqq.=" and stuf.idservice='".$_GET['service']."'";
}
echo "<h2>".$ech." </h2>";

if (@$_GET['r']=='1'){
?>
<table width="639" border="1" id="myTable">
  <tr>
    <th width="90" bgcolor="#333333"><div align="center"><strong><span class="Style1">: عدد ايام الراحة</span></strong></div></th>
    <th width="90" bgcolor="#333333"><div align="center"><strong><span class="Style1">: يوم الرجوع </span></strong></div></th>
    <th width="102" bgcolor="#333333"><div align="center"><strong><span class="Style1">: يوم الخروج </span></strong></div></th>
   
    <th width="170" bgcolor="#333333"><div align="center"><strong><span class="Style1"> : الاسم واللقب</span></strong></div></th>
    <th width="71" bgcolor="#333333"><div align="center"><strong><span class="Style1">: رقم الالي</span></strong></div></th>
    <th width="76" bgcolor="#333333"><div align="center"><strong><span class="Style1">: رقم الراحة</span></strong></div></th>
    </tr>
  <?php 



  $req="select conge.*,stuf.nom from conge,stuf where conge.mecano=stuf.mecano and ((datedebut between '".$d1."' and '".$d2."') or (datefin between '".$d1."' and '".$d2."')) ".$reqq." order by stuf.mecano,datedebut asc";

$resul=mysql_query($req);
while($r=mysql_fetch_row($resul)){
  ?>
  <tr>
    <td><div align="center"><?php echo $r[4]; ?></div></td>
	<td><div align="center"><?php echo $r[3]; ?></div></td>
	<td><div align="center"><?php echo $r[2]; ?></div></td>
    <td><div align="center"><?php echo $r[6]; ?></div></td>
    <td><div align="center"><?php echo $r[1]; ?></div></td>
    <td><div align="center">C<?php echo $r[0]; ?></div></td>
    </tr>
<?php } ?>
</table>
<?php }
if (@$_GET['ra']=='1'){

?>
  <br />
  <br />
  <table width="778" border="1" id="myTable1">
    <tr>
      <th width="169" bgcolor="#333333"><div align="center"><strong><span class="Style1"> : الملاحظات</span></strong></div></th>
      <th width="95" bgcolor="#333333"><div align="center"><strong><span class="Style1">: يوم الرجوع </span></strong></div></th>
      <th width="82" bgcolor="#333333"><div align="center"><strong><span class="Style1">: يوم الخروج </span></strong></div></th>
      <th width="147" bgcolor="#333333"><div align="center"><strong><span class="Style1"> : الاسم واللقب</span></strong></div></th>
      <th width="109" bgcolor="#333333"><div align="center"><strong><span class="Style1">: نوع الراحة</span></strong></div></th>
      <th width="63" bgcolor="#333333"><div align="center"><strong><span class="Style1">: رقم الالي</span></strong></div></th>
      <th width="67" bgcolor="#333333"><div align="center"><strong><span class="Style1">: رقم الراحة</span></strong></div></th>
    </tr>
    <?php 



  $req="select autreconge.*,stuf.nom from autreconge,stuf where autreconge.mecano=stuf.mecano and ((datedebut between '".$d1."' and '".$d2."') or (datefin between '".$d1."' and '".$d2."')) ".$reqq." order by datedebut desc";

$resul=mysql_query($req);
while($r=mysql_fetch_row($resul)){
  ?>
    <tr>
      <td><div align="center"><?php echo $r[7]; ?></div></td>
      <td><div align="center"><?php echo $r[4]; ?></div></td>
      <td><div align="center"><?php echo $r[3]; ?></div></td>
      <td><div align="center"><?php echo $r[9]; ?></div></td>
      <td><div align="center"><?php if ($r[2]==1) echo "القيام بمهمة"; if ($r[2]==2) echo "راحة استثنائية"; if ($r[2]==3) echo "رخصة ثقافية"; if ($r[2]==4) echo "رخصة نقابية";if ($r[2]==5) echo "رخصة مرضية";if ($r[2]==7) echo "الراحة التعوضية"; if ($r[2]==1) {if ($r[6]==1) echo "<br>تكوين  ";if ($r[6]==2) echo "<br>إجتماع  ";}?></div></td>
      <td><div align="center"><?php echo $r[1]; ?></div></td>
      <td><div align="center">AC<?php echo $r[0]; ?></div></td>
    </tr>
    <?php } ?>
  </table>
  <?php } } ?>
</center>
</body>
</html>
