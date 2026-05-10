<!DOCTYPE html>
<meta charset="UTF-8">
<head>
<style>
table, th, td {
    align: right;
	direction: rtl;
	line-height: 2.5;
	font-size:16px;
	 
}
.left {
  direction: rtl;
  font-weight:bold;
}
table{
width: 100%;
}
td{
text-align: right;
}

</style>
  <style>
@media print {
      header, footer {
        display: none; /* Masquer l'en-tête et le pied de page */
      }
    }
  </style>
</head>
<body>
<?php

function numToWordsRec($number) {
    $words = array(
        0 => 'صفر', 1 => 'يوم واحد', 2 => 'يومان',
        3 => 'ثلاثة', 4 => 'أربعة', 5 => 'خمسة',
        6 => 'ستّة', 7 => 'سبعة', 8 => 'ثمانية',
        9 => 'تسعة', 10 => 'عشرة', 11 => 'أحدى عشر',
        12 => 'إثنى عشر', 13 => 'ثلاثة عشر', 
        14 => 'أربعة عشر', 15 => 'خمسة عشر',
        16 => 'ستّة عشر', 17 => 'سبعة عشر', 18 => 'ثمانية عشر',
        19 => 'تسعة عشر', 20 => 'عشرون', 30 => 'ثلاثون',
        40 => 'أربعون', 50 => 'خمسون', 60 => 'ستّون',
        70 => 'سبعون', 80 => 'ثمانون',
        90 => 'تسعون'
    );

if ($number < 3) {
        return $words[$number].' '.'('.$number.')';
    }

    if ($number < 11) {
        return $words[$number].' '.'('.$number.')'.' أيّام';
    }
	 if ($number < 20) {
        return $words[$number].' '.'('.$number.')'.' يوما';
    }

    if ($number < 100) {
        return $words[$number % 10] .
               ' ' . 'و'.' '.$words[10 * floor($number / 10)].' ('.$number.')'.' يوما';
    }


    return numToWordsRec(floor($number / 1000000)) .
           ' million ' . numToWordsRec($number % 1000000);
}

// Example usage:


require('DbConnexion.php');

$JourDeReposFixe=mysql_real_escape_string(stripslashes($_GET['JourDeReposFixe']));

switch($JourDeReposFixe){
	case 0:
	$JourFixe="الأحد";
	break;
	case 1:
	$JourFixe="الإثنين";
	break;
	case 2:
	$JourFixe="الثلاثاء";
	break;
	case 3:
	$JourFixe="الأربعاء";
	break;
	case 4:
	$JourFixe="الخميس";
	break;
	case 5:
	$JourFixe="الجمعة";
	break;
	case 6:
	$JourFixe="السّبت";
	break;
	case 10:
	$JourFixe="السّبت و الأحد";
	break;
	
	
	
}

//$conn = mysqli_connect("127.0.0.1","root","","pointage");


$mecano=mysql_real_escape_string(stripslashes($_GET['mecano']));
$nom=mysql_real_escape_string(stripslashes($_GET['NomPrenom']));
$DateDebut=mysql_real_escape_string(stripslashes($_GET['DateDebut']));
$DateFin=mysql_real_escape_string(stripslashes($_GET['DateFin']));
$NbJoursConge=mysql_real_escape_string(stripslashes($_GET['NbJoursConge']));
$NbJoursResteSolde=mysql_real_escape_string(stripslashes($_GET['NbJoursResteSolde']));
$SoldeActuel=mysql_real_escape_string(stripslashes($_GET['Solde']));

$number = $NbJoursConge;


$date = DateTime::createFromFormat("Y-m-d", $DateDebut);

$Annee=$date->format("Y");

//$ress1=mysql_query("SELECT * FROM conge WHERE datedebut < '".$DateFin."' AND datefin >'".$DateDebut."' AND mecano='".$mecano."'");
//$ress2=mysql_query("SELECT * FROM autreconge WHERE datedebut < '".$DateFin."' AND datefin >'".$DateDebut."' AND mecano='".$mecano."'");

/*$sqlConge = "SELECT * FROM conge WHERE datedebut < '".$DateFin."' AND datefin >'".$DateDebut."' AND mecano='".$mecano."'";
$resultConge = $conn->query($sqlConge);

$sqlAutreConge = "SELECT * FROM autreconge WHERE datedebut < '".$DateFin."' AND datefin >'".$DateDebut."' AND mecano='".$mecano."'";
$resultAutreConge = $conn->query($sqlAutreConge);

if (($resultConge->num_rows > 0) || ($resultAutreConge->num_rows > 0)) {

echo "<script language=javascript>alert('الراحة المطلوبة قد تكون مسجلة سابقا او جزء تابع لراحة مسجلة');history.go(-1);</script>";

}
else
{


$sqlInsert = "INSERT INTO conge (mecano,datedebut,datefin,nbjours,annee) VALUES (".$mecano.", '".$DateDebut."', '".$DateFin."',".$NbJoursConge.",".$Annee.")";

if ($conn->query($sqlInsert) === TRUE) {
	$last_id = $conn->insert_id;
	
	$sqlUpdate="UPDATE nbconge SET rest= ".$NbJoursResteSolde." WHERE mecano=".$mecano."";
	 if ($conn->query($sqlUpdate) === TRUE) {*/
	 	 
    echo "<table >
<tr>
<td class='left' colspan=2>التّاريخ :".date('Y/m/d')."</td>
<td class='left' colspan=2>الرصيد الحالي :".$SoldeActuel."</td>
<td class='left' colspan=2>الرصيد المتبقّي :".$NbJoursResteSolde."</td>
</tr>
<tr>
<td class='left' colspan=2>رقم الإجازة :".$last_id."</td>
<td class='left' colspan=2>يوم الرّاحة الأسبوعيّة :".$JourFixe."</td>
</tr>
<tr>
<td class='left' colspan=2>الرّقم الآلي :".$mecano."</td>
<td class='left' colspan=2>الإسم و اللّقب :".$nom."</td>
</tr>
<tr>
<td class='left' colspan=2>تاريخ بداية الإجازة :".$DateDebut."</td><td class='left' colspan=2>تاريخ نهاية الإجازة :".$DateFin."</td>
</tr>
<tr>
<td class='left' colspan=2>عدد أيّام الإجازة :".numToWordsRec($number)." </td>
</tr>
<tr>
<td class='left' colspan=2>العنوان أثناء الإجازة :</td>
</tr>
<tr>
<td class='left' colspan='2'>إمضاء الرئيس المباشر </td>
<td class='left' colspan='2'></td>
<td class='left'>إمضاء العون </td>
</tr>";
	 /*}
	 else
	 {
	 echo "Error: " . $sqlUpdate . "<br>" . $conn->error;	 
	 }
} else {
    echo "Error: " . $sqlInsert . "<br>" . $conn->error;
}
}*/
?>
</body>
</html>