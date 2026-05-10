<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">

<?php
session_start();
require('connection.php');
?>
<style>
a.tooltip {outline:none; }
a.tooltip strong {line-height:30px;}
a.tooltip:hover {text-decoration:none;} 
a.tooltip span {
    z-index:10;display:none; padding:14px 20px;
    margin-top:-30px; margin-left:28px;
    width:300px; line-height:16px;
}
a.tooltip:hover span{
    display:inline; position:absolute; color:#111;
    border:1px solid #DCA; background:#fffAF0;}
.callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;}
    
/*CSS3 extras*/
a.tooltip span
{
    border-radius:4px;
    box-shadow: 5px 5px 8px #CCC;
}


tr .spnTooltip {
    z-index:10;display:none; padding:14px 20px;
    margin-top:-50px; margin-left:28px;
    width:450px; line-height:16px;
}
tr:hover .spnTooltip{
    display:inline; position:absolute; color:#111;
    border:1px solid #DCA; background:#fffAF0;}
.callout {z-index:20;position:absolute;top:30px;border:0;left:-12px;}
</style>
<style>
* {
  box-sizing: border-box;
}

#myInput {
  background-image: url('/css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}

#myInputDateFin {
  background-image: url('/css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}


#myInputDatedebut{
  background-image: url('/css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}


#myInputaffect {
  background-image: url('/css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}

#myInputannee {
  background-image: url('/css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}

#myTable {
  border-collapse: collapse;
  width: 100%;
  border: 1px solid #ddd;
  font-size: 18px;
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
</style>
<title>Tableau d'activité journalière</title>
</head>
<body>
<div align="right"><a href="logout.php"><img src="images/exit.png" width="48" height="48" border="0" /><br/><b>خروج</b></a></div>

<?php include('menu.php'); ?>

<center><h2>جدول النشاط اليومي</h2></center>
<?php
include('SessionControl.php');
?>
<table>
<tr colspan="3"><td dir="rtl"><button onclick="exportTableToExcel('myTable', 'ListeRetraite')"><img src="images/excel.png" height ="80" width="100" /></button></td></tr>
<tr colspan="3">

<tr>

</tr></table>

<table id="myTable" dir="rtl">

<tr>
           <td><input name='myInputPOS'  class='form-control' type=text   id=myInputPOS   onkeypress='enterKeyPressedPOS(event)'   required ></td>
	       <td><input name='myInputITE'  class='form-control' type=text   id=myInputITE   onkeypress='enterKeyPressedITE(event)'   required ></td>
           <td><input name='myInputDEB1' class='form-control' type=date   id=myInputDEB1  onkeypress='enterKeyPressedDEB1(event)'  required ></td>
           <td><input name='myInputFIN1' class='form-control' type=text   id=myInputFIN1  onkeypress='enterKeyPressedFIN1(event)'  required ></td>
           <td><input name='myInputDEB2' class='form-control' type=number id=myInputDEB2  onkeypress='enterKeyPressedDEB2(event)'  required min=0 oninput="validity.valid||(value='');"></td>
           <td><input name='myInputFIN2' class='form-control' type=number id=myInputFIN2  onkeypress='enterKeyPressedFIN2(event)'  required min=0 oninput="validity.valid||(value='');"></td>
           <td><input name='myInputDEB3' class='form-control' type=number   id=myInputDEB3  onkeypress='enterKeyPressedDEB3(event)'  required min=0 oninput="validity.valid||(value='');"></td>
           <td><input name='myInputDIST' class='form-control' type=number id=myInputDIST  onkeypress='enterKeyPressedDIST(event)'  required min=0 oninput="validity.valid||(value='');"></td>
		   <td><input name='myInputDEB1' class='form-control' type=date   id=myInputDEB1  onkeypress='enterKeyPressedDEB1(event)'  required ></td>
</tr>


  <tr class="header">
    <th style="width:10%;">رقم الحافلة المعطّبة</th>
    <th style="width:15%;">نوعيّة العطب</th>
	<th style="width:10%;">تاريخ العطب</th>
	<th style="width:10%;">رقم الحافلة المعوّضة</th>
	<th style="width:15%;">الأسطول</th>
	<th style="width:10%;">عدد الحافلات المعطّبة</th>
	<th style="width:10%;">نسبة العطب</th>
	<th style="width:10%;">المحاصيل</th>
	<th style="width:10%;">التاريخ</th>
  </tr>
  
  <?php 
  
   if (isset($_SESSION['congidGA']))
  {
  
  
  
  
    $departement=$_SESSION['departement'];

//$rs=mysql_query("select  annee from  annee");
//$rss=mysql_fetch_row($rs);
//$annee=$rss[0];
if ($_SESSION['departement']<>"admin")
  {
  //$re=mysql_query("select distinct depart.mecano,nom,datenaissance,datedepart,dateretraite,annee,dep.depar,cministere,rest,DATEDIFF (dateretraite,CURRENT_DATE()) from depart left join stuf on stuf.mecano=depart.mecano left join dep on stuf.dep=dep.id  left join nbconge on stuf.mecano=nbconge.mecano where stuf.dep=".$departement." and depart.annee>=".$annee." order by datedepart asc");
  }
else
{
	//$re=mysql_query("select distinct depart.mecano,nom,datenaissance,datedepart,dateretraite,annee,dep.depar,cministere,rest,DATEDIFF (dateretraite,CURRENT_DATE()) from depart left join stuf on stuf.mecano=depart.mecano left join dep on stuf.dep=dep.id  left join nbconge on stuf.mecano=nbconge.mecano where depart.annee>=".$annee." order by datedepart asc");
}	
	/*	while ($r=mysql_fetch_row($re)) {
			
			$date1=date_format(date_create($r[4]), 'Y-m-d');
			 $date2 = date('Y-m-d');
  


  
		 if ($date1 < $date2) {	
		 echo ' <tr bgcolor="orange">';
		 }
		 else
		 {
		echo ' <tr>';	 
		 }
		  echo'
				
    <td><span class="spnDetails">'.$r[0].'</span>';
	
	if (0<$r[9]) { 
	if ($r[9]<11)
	{
$jlettre="أيّام";
	}	
	
	else
		
	{
$jlettre="يوما";
	}	
	
	echo '
	<span class="spnTooltip">
         <strong>يحال '.$r[1].' على التقاعد خلال '.$r[9].' '.$jlettre.'</strong><br />
    </span>';}
	
	
	echo '</td>
    <td dir="rtl" align="right">'.$r[1].'</td>
	 <td>'.$r[6].'</td>
	  <td>'.$r[2].'</td>
    <td>'.$r[3].'</td>
	  <td>'.$r[4].'</td>
	  <td>'.$r[8].'</td>
	  <td>'.$r[7].'</td>
	  <td>'.$r[5].'</td>
	  </tr>';
		}
		*/
  }	 
		?>
  
</table>
<br/>


</body>
</html>
