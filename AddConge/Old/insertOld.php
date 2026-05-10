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
if (isset($_GET['JourDeReposFixe']) && isset($_GET['mecano']) ) {
	


$code=array();
  
  /*$code[1]="Mi.M.";
  $code[2]="Ex."; 
  $code[3]="Cu."; 
  $code[4]="Sy."; 
  $code[5]="Ma.";
  $code[6]="At.";
  $code[7]="RC.";
  $code[8]="C.Mat.";
  $code[9]="RSS.";
  $code[10]="San.";
  $code[11]="C.Naiss.";
  $code[12]="C.Pat.";
  $code[13]="C.Pat.";
  $code[14]="Mi.R.";*/
  
			
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
require_once(__DIR__ . "/DbConnexion.php");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Escaping user input with prepared statements
$JourDeReposFixe = $_GET['JourDeReposFixe'];
$mecano = $_GET['mecano'];
$nom = $_GET['NomPrenom'];
$DateDebut = $_GET['DateDebut'];
$DateFin = $_GET['DateFin'];
$NbJoursConge = $_GET['NbJoursConge'];
$NbJoursResteSolde = $_GET['NbJoursResteSolde'];
$SoldeActuel = $_GET['Solde'];
$TypeRepos = $_GET['TypeRepos'];
if($TypeRepos==13){
	$TypeRepos=1;
	$Type2=1;
}else if ($TypeRepos==14){
	$TypeRepos=1;
	$Type2=2;
}else if ($TypeRepos==1){
	$TypeRepos=1;
	$Type2=3;
}else {
		$Type2=0;
}
$TextArea=$_GET['TextArea'];

// Sanitize the input using prepared statements to avoid SQL injection
$JourDeReposFixe = $conn->real_escape_string(stripslashes($JourDeReposFixe));
$mecano = $conn->real_escape_string(stripslashes($mecano));
$nom = $conn->real_escape_string(stripslashes($nom));
$DateDebut = $conn->real_escape_string(stripslashes($DateDebut));
$DateFin = $conn->real_escape_string(stripslashes($DateFin));
$NbJoursConge = $conn->real_escape_string(stripslashes($NbJoursConge));
$NbJoursResteSolde = $conn->real_escape_string(stripslashes($NbJoursResteSolde));
$SoldeActuel = $conn->real_escape_string(stripslashes($SoldeActuel));
$TypeRepos = $conn->real_escape_string(stripslashes($TypeRepos));
$TextArea = $conn->real_escape_string(stripslashes($TextArea));
$Type2 = $conn->real_escape_string(stripslashes($Type2));

$daysOfWeek = array(
    0 => "الأحد",  // Sunday
    1 => "الإثنين", // Monday
    2 => "الثلاثاء", // Tuesday
    3 => "الأربعاء", // Wednesday
    4 => "الخميس", // Thursday
    5 => "الجمعة", // Friday
    6 => "السّبت", // Saturday
    10 => "السّبت و الأحد" // Saturday and Sunday
);

$JourFixe = isset($daysOfWeek[$JourDeReposFixe]) ? $daysOfWeek[$JourDeReposFixe] : "Unknown day"; // Default to "Unknown day" if not found


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

*/
$add = $_SERVER['REMOTE_ADDR'];

if($TypeRepos==0) {
	
	
$sqlInsert = "INSERT INTO conge (mecano,datedebut,datefin,nbjours,annee,LoggedAs,Solde,JRepos) VALUES (".$mecano.", '".$DateDebut."', '".$DateFin."',".$NbJoursConge.",".$Annee.",'".$add."',".$NbJoursResteSolde.",".$JourDeReposFixe.")";

}
else
{
$sqlInsert = "INSERT INTO autreconge (mecano,type,datedebut,datefin,nbj,type2,commentaire,anne,LoggedAs) VALUES (".$mecano.",".$TypeRepos.", '".$DateDebut."', '".$DateFin."',".$NbJoursConge.",".$Type2.",'".$TextArea."',".$Annee.",'".$add."')";	

/*$resmaxdate=mysql_query("select MAX(datedebut) from autreconge where mecano='".$mecano."' and type=6");
$rmaxdate=mysql_fetch_row($resmaxdate);

 $d1 = $rmaxdate[0];
//on compare la date maximale dans la BD avec la date de début de l'AT saisi
 if (($d1)<($_POST['date12']))
			{
				//si la date bd est inférieur à la date début saisi alors c'est le dernier AT (valide=1)
				$req=mysql_query("insert into autreconge values(Null,'".$mecano."','".$_POST['typcon']."','".$_POST['date12']."','".$_POST['date22']."','".$_POST['nbj']."','".$typ."','".$_POST['com']."','".$r[0]."',1)");
                $id=mysql_insert_id();
				
				//on doit modifier les insertions antérieures (valide=0)
				$requpdatevalide="update autreconge set valide=0 where mecano='".$mecano."' and id <> '".$id."' and type=6";
                $reupdatevalide=mysql_query($requpdatevalide);
			} 
			
			else {

            //si la date bd est inférieur à la date début saisi alors ce n'est pas le dernier AT (valide=0)
            $req=mysql_query("insert into autreconge values(Null,'".$mecano."','".$_POST['typcon']."','".$_POST['date12']."','".$_POST['date22']."','".$_POST['nbj']."','".$typ."','".$_POST['com']."','".$r[0]."',0)");
            $id=mysql_insert_id();
			}			*/
}
if ($conn->query($sqlInsert) === TRUE) {
	$last_id = $conn->insert_id;
	
	if ($TypeRepos<>0){
		$sqlUpdate="UPDATE nbconge SET rest= ".$NbJoursResteSolde." WHERE mecano=".$mecano."";
	 if ($conn->query($sqlUpdate) === TRUE) {
	 }
		
		
		echo "<script language=javascript>alert('تم اضافة الراحة بنجاح تحت رقم ".$last_id."'); open('index.php?mecano=".$mecano."','_top');</script>";
	}
	if($TypeRepos==0) {
	$sqlUpdate="UPDATE nbconge SET rest= ".$NbJoursResteSolde." WHERE mecano=".$mecano."";
	 if ($conn->query($sqlUpdate) === TRUE) {
	 	
//echo '<script>alert(C.'.$last_id.');</script>';
echo "<script language=javascript>alert('تم اضافة الراحة بنجاح تحت رقم C".$last_id."'); open('index.php?mecano=".$mecano."','_top');</script>";
    /*echo "<table >
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
</tr>";*/
	 }
	 else
	 {
	 echo "Error: " . $sqlUpdate . "<br>" . $conn->error;	 
	 }
}} else {
    echo "Error: " . $sqlInsert . "<br>" . $conn->error;
}
}else {
	header("Location: index.php");
}
?>
</body>
</html>