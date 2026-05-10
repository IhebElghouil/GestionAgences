<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>POINTAGE</title>
<style type="text/css">
<!--
.Style2 {font-size: 20px; }
.Style5 {font-weight: bold; font-size: 20px; }
-->
</style>
</head>

<body>
<center>
<?php

require("connection.php");
if(@$_GET['mecano']){
$re=mysql_query("select stuf.*,titres.* from stuf,titres where stuf.titre=titres.id and mecano='".$_GET['mecano']."'");
$r=mysql_fetch_row($re);




?>
<br />
  <br />
  <table width="624" border="0">
    <tr>
      <td width="618" height="574" colspan="8"><p align="right" dir="rtl"><br />
        قــابــس في: <?php echo date("Y/m/d");?> </p>
        <p align="right" dir="rtl"> عــــــــــ <?php echo @$_GET['num'];?>  ــــدد</p>
        <p dir="rtl">&nbsp;</p>
        <p dir="rtl">   </p>
        <h1 align="center" dir="rtl">شـــهـــادة عـــمــــل</h1>
        <p dir="rtl">&nbsp;</p>
        <p align="center" dir="rtl"><strong>*******</strong><br />
        </p>
        <p dir="rtl"><br />
          <br />
          <br />
        </p>
        <p align="justify" dir="rtl">  <span class="Style2">          يــشــهــد الــرئــيــس الــمــديــر الــعــام  للــشـركــة الــجــهـوية للنــقــل بــقــابـس أن<br />
          <br />
          الـسـيـد: <strong><?php echo $r[1];?></strong> رقـم<strong> <?php echo $r[8]."/".$r[7];?> </strong> يعمل بالـمـؤسّـسـة بـخطّـة:<strong> <?php echo $r[19];if ($r[12]==0) echo " مترسم";if ($r[12]==1) echo " متربص";if ($r[12]==2) echo " متعاقد";?>.</strong> <br />
          <br />
          سـلّـمـت لـه  هــذه  الــشــهـادة بـطـلـب مـنـه للإدلاء بـها لـدى من يهمه الأمر.<br />  
          </span><br />
          <br />
          <br />
        </p>
        <p align="justify" dir="rtl">&nbsp;</p>
        <?php
		if(@$_GET['sing']==1){
		
		
		?>
        <table width="359" border="0">
          <tr>
            <td width="10">&nbsp;</td>
            <td width="339"><div align="center" class="Style5"><strong>عـن/  الــر ئـيـس الـمـديـر الـعـام</strong></div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><div align="center" class="Style5"><strong>رئيس  مصلحة الموارد البشرية والتكوين</strong></div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><div align="center" class="Style5">عواطف القويسمي</div></td>
          </tr>
        </table>
		<?php } ?>
		
		
		
			<?php
		if(@$_GET['sing']==2){
		
		
		?>
        <table width="359" border="0">
          <tr>
            <td width="83">&nbsp;</td>
            <td width="266"><div align="center" class="Style5"><strong>الرئـيس المديــر العــام</strong></div></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><div align="center" class="Style5">هشام العساس</div></td>
          </tr>
        </table>
		<?php } ?>
        <p align="left" dir="rtl">          </p>
        <p dir="rtl">&nbsp;</p>
      <p dir="rtl">&nbsp;</p></td>
    </tr>
  </table>
  
  
  
  
  
  <br />
<br />
<?php } ?>
</center>
</body>
</html>
