<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
<!-- jQuery Modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
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
    width:280px; line-height:16px;
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
    width:460px; line-height:16px;
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

#myInputnom {
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

#myInputsocial {
  background-image: url('/css/searchicon.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  width: 100%;
  font-size: 16px;
  padding: 12px 20px 12px 40px;
  border: 1px solid #ddd;
  margin-bottom: 12px;
}

#myInputobservation{
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
<title>Accidents de Travail</title>
</head>
<body>
<div align="right"><a href="logout.php"><img src="images/exit.png" width="48" height="48" border="0" /><br/><b>خروج</b></a></div>

<?php include('menu.php'); ?>


<center><h2>متابعة حوادث الشغل</h2></center>


<?php
 if (isset($_SESSION['congidGA']))
  {
  


if ($_SESSION['departement']<>"admin")

{

$req="select depar from dep where id='".$_SESSION['departement']."'";
$res=mysql_query($req);
if($r=mysql_fetch_row($res)) {
echo '<center><h2>'.$r[0].'</h2></center><br/>';
}
}
else
{
echo '<center><h2>بجميع الوكالات و الورشات</h2></center><br/>';	
echo '<a href="loginhistory.php">متابعة الولوج إلى التطبيقة</a>';  	
}

  }
   else
	  
	  {
		echo '<script language="Javascript">

document.location.replace("index.php");

</script>';
		  
	  }
?>
<table>
<tr colspan="3"><td dir="rtl"><button onclick="exportToExcel()"><img src="images/excel.png" height ="80" width="100" /></td></tr>
<tr colspan="3">

<tr>

</tr></table>


<table dir="rtl">
  <tr class="header">
    <td style="width:7%;"><input type="text" id="myInput" onkeyup="myFunction()" placeholder="البحث بالرقم الآلي ..." title="Type in a Mecano" dir="rtl"></td>
    <td style="width:13%;"><input type="text" id="myInputnom" onkeyup="myFunctionnom()" placeholder="البحث بالإسم أو اللقب ..." title="Type in a name" dir="rtl"></td>
	<td style="width:15%;"></td>
	<td style="width:9%;"><input type="text" id="myInputDatedebut" onkeyup="myFunctionDateDebut()" placeholder="البحث بالتاريخ من ..." title="Type in a name" dir="rtl"></td>
	<td style="width:9%;"><input type="text" id="myInputDateFin" onkeyup="myFunctionDateFin()" placeholder="البحث بالتاريخ إلى ...." title="Type in a name" dir="rtl"></td>
	<td style="width:7%;"></td>
	<td style="width:7%;"><input type="text" id="myInputsocial" onkeyup="myFunctionsocial()" placeholder="البحث رقم الضمان الإجتماعي ..." title="Type in a name" dir="rtl"></td>
	<td style="width:15%;"><input type="text" id="myInputaffect" onkeyup="myFunctionaffectation()" placeholder="البحث بوحدة الإرتباط ..." title="Type in a name" dir="rtl"></td>
	<td style="width:20%;"><input type="text" id="myInputobservation" onkeyup="myFunctionobservation()" placeholder="البحث بالملاحظات ..." title="Type in an observation" dir="rtl"></td>
	<td style="width:5%;"><input type="text" id="myInputannee" onkeyup="myFunctionannee()" placeholder="البحث بالسنة ..." title="Type in a year" dir="rtl"></td>
  </tr>
  </table>


<table id="myTable" dir="rtl">
  <tr class="header">
    <th style="width:7%;">الرقم الآلي</th>
    <th style="width:13%;">الإسم و اللقب</th>
	<th style="width:15%;">الرتبة</th>
	<th style="width:9%;">التاريخ من</th>
	<th style="width:9%;">التاريخ إلى</th>
	<th style="width:7%;">عدد الأيام</th>
	<th style="width:7%;">رقم الضمان الإجتماعي</th>
	<th style="width:15%;">وحدة الإرتباط</th>
	<th style="width:20%;">ملاحظات</th>
	<th style="width:5%;">السنة</th>
	<?php
	if ($_SESSION['departement']=="admin")
  {
	 echo '<th style="width:5%;"></th>';
  }
	
	?>
  </tr>
  
  <?php 
    if (isset($_SESSION['congidGA']))
  {
  
  $departement=$_SESSION['departement'];
  if ($_SESSION['departement']<>"admin")
  {
  $re=mysql_query("select autreconge.mecano,nom,datedebut,datefin,dep.depar,commentaire,anne,titres.libellet,autreconge.id,valide,DATEDIFF (datefin,CURRENT_DATE()),ncnss from autreconge left join stuf on autreconge.mecano=stuf.mecano LEFT JOIN social ON stuf.mecano = social.mecano LEFT JOIN titres on titres.id=stuf.titre left join dep on stuf.dep=dep.id where type=6 AND contrastage in (0,1,3) AND stuf.dep=".$departement." order by valide desc");
		
  }
  else{
	  
	 $re=mysql_query("select autreconge.mecano,nom,datedebut,datefin,dep.depar,commentaire,anne,titres.libellet,autreconge.id,valide,DATEDIFF (datefin,CURRENT_DATE()),ncnss from autreconge left join stuf on autreconge.mecano=stuf.mecano LEFT JOIN social ON stuf.mecano = social.mecano LEFT JOIN titres on titres.id=stuf.titre left join dep on stuf.dep=dep.id where type=6 AND contrastage in (0,1,3) order by valide desc");  
  }
  
  while ($r=mysql_fetch_row($re)) {
	  
	  
	$DebutDate = $r[2];
	$FinDate = $r[3];
    $daydiff=floor((abs(strtotime($FinDate) - strtotime($DebutDate))/(60*60*24)))+1;


            $date1=date_format(date_create($r[3]), 'Y-m-d');
			$date2 = date('Y-m-d');
			$dayecart=floor((abs(strtotime($date1) - strtotime($date2))/(60*60*24)))+1;
			
			//echo "<script>alert('".$r[10]."');</script>";
			$datefinAT=$r[10];
					if (($date1 < $date2) && ($r[9]==1)) {	
		 echo ' <tr bgcolor="#e54342">';
		 }
		 else if (($r[10]>=1) && ($r[10]<=10) && ($r[9]==1)) {	
		 echo ' <tr bgcolor="orange">';
		 }
		 else if (($r[10]==0) && ($r[9]==1)) {	
		 echo ' <tr bgcolor="yellow">';
		 }
		 else
		 {
		echo ' <tr>';	 
		 }
		  echo'
				
    <td><span class="spnDetails">'.$r[0].'</span>';
	
	if ($r[10]<0 && $r[9]==1) { 
	echo '
	<span class="spnTooltip">
        <strong>إنتهت صلوحية الشهادة الطبية لحادث الشغل</strong><br />
    </span>';}
	if ($r[10]==0 && $r[9]==1) { 
	echo '
	<span class="spnTooltip">
        <strong>تنتهي صلوحية الشهادة الطبية لحادث الشغل اليوم</strong><br />
    </span>';}
	
	else if (($r[10]>=1) && ($r[10]<=10) && ($r[9]==1)) { 
	if ($r[10]<11)
	{
$jlettre="أيّام";
	}	
	
	else
		
	{
$jlettre="يوما";
	}	
	
	echo '
	<span class="spnTooltip">
        <strong>تنتهي صلوحية الشهادة الطبية لحادث الشغل خلال '.$r[10].' '.$jlettre.'</strong><br />
    </span>';}
	
		 echo'
	</td>	 
    <td dir="rtl">'.$r[1].'</td>
	<td>'.$r[7].'</td>
	<td>'.$r[2].'</td>
    <td>'.$r[3].'</td>
	<td>'.$daydiff.'</td>
	<td>'.$r[11].'</td>
	<td>'.$r[4].'</td>
	<td>'.$r[5].'</td>
	<td>'.$r[6].'</td>
	';
  ?>
  <?php
	if ($_SESSION['departement']=="admin")
  {
	 echo '<td><a href="page.php?mecano='.$r[8].'&type=3" rel="modal:open"><img src="images\Edit.png"></a></td>';
	 
  }
	?>
	<?php
		echo '</tr>';
		}
	

  }	
		 
		?>
  
</table>

<script>
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function myFunctionaffectation() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInputaffect");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[7];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function myFunctionDateDebut() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInputDatedebut");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[3];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function myFunctionDateFin() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInputDateFin");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[4];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function myFunctionnom() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInputnom");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function myFunctionsocial() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInputsocial");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[6];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function myFunctionannee() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInputannee");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[9];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function myFunctionobservation() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInputobservation");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[8];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}

function exportToExcel() {
    const uri = 'data:application/vnd.ms-excel;base64,';
    const template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';

    const base64 = (s) => window.btoa(unescape(encodeURIComponent(s)));

    const format = function (template, context) {
        return template.replace(/{(\w+)}/g, (m, p) => context[p])
    };

    const html = document.getElementById('myTable').innerHTML;
    const ctx = {
        worksheet: 'Worksheet',
        table: html,
    };

    const link = document.createElement("a");
    link.download = "AccidentsDeTravail.xls";
    link.href = uri + base64(format(template, ctx));
    link.click();

}

</script>

</body>
</html>
