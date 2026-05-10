<html>
<head>
<style type = "text/css">
         p {page-break-after: always;}
      </style>
</head>
<body>
<table width="100%" border="3" cellspacing="0" dir=rtl>
  <tr>
    <td width="15%" rowspan=2><div align="center" class="title">السنة</div></td>
    <td width="15%" rowspan=2><div align="center" class="title">رصيد الإجازات المتبقيّة</div></td>
    <td width="40%" colspan=4><div align="center" class="title">الإجازة المطلوبة</div></td>
    <td width="35%" rowspan=2><div align="center" class="title">ملاحظات</div></td>
  </tr>
  <tr>
    <td><div align="center" class=title>الرقم</div></td>
    <td><div align="center" class=title>من</div></td>
	<td><div align="center" class=title>إلى</div></td>
	<td><div align="center" class=title>عدد الأيام</div></td>
	    
  </tr>

  <?php 
  require('DbConnexion.php');
  
   $mecano=$_POST['myInput'];
   
   
  /* $reqnom=mysql_query("select nom from stuf where mecano='".$mecano."'");
   $resultnom=mysql_fetch_row($reqnom);
   $nom=$resultnom[0];
	
	
  
    echo '<div align="right" dir=rtl><h3>الإسم و اللقب : '.$nom.'</h3></div>';
  
    $reqanne=mysql_query("select annee from annee");
	$result=mysql_fetch_row($reqanne);
	$annee=$result[0];*/
	
	
	
	$stmt = mysqli_prepare($connection, "SELECT nom FROM stuf WHERE mecano = ?");
    mysqli_stmt_bind_param($stmt, "s", $mecano);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $nom);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

// Display the name
echo '<div align="right" dir=rtl><h3>الإسم و اللقب : ' . htmlspecialchars($nom) . '</h3></div>';

$stmt = mysqli_prepare($connection, "SELECT annee FROM annee");
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $annee);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

	
	
	
	
	
		
   /* $reqsoldeconge=mysql_query("select (nbj1+nbj2),nbj1,nbj2 from nbconge WHERE mecano='".$mecano."'");
	$resultsolde=mysql_fetch_row($reqsoldeconge);
	$Anciensolde=$resultsolde[0];
    $Restsolde=$resultsolde[1];
    $Actuelsolde=$resultsolde[2];*/
	
	
	
	
	$stmt = mysqli_prepare($connection, "SELECT (nbj1 + nbj2) AS Anciensolde, nbj1 AS Restsolde, nbj2 AS Actuelsolde FROM nbconge WHERE mecano = ?");
    mysqli_stmt_bind_param($stmt, "s", $mecano);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $Anciensolde, $Restsolde, $Actuelsolde);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
  
  /* $renbligne=mysql_query("select count(mecano) from conge where annee='".$annee."' AND mecano='".$mecano."'");
   $rnbligne=mysql_fetch_row($renbligne);
   $numrows=$rnbligne[0];*/
   
   
/*  $PremierJourCtrl = date('Y-01-01', strtotime('next year'));
$DernierJourCtrl = date('Y-12-31', strtotime('last year'));

$DateFinRequete=date('Y-12-31');
$DateDebutRequete=date('Y-01-01');

// Requête pour obtenir le nombre de jours de maladie
$sqlMaladie = "SELECT SUM(DATEDIFF(LEAST(?, datefin), GREATEST(?, datedebut))) AS TotalJoursMaladie 
               FROM autreconge 
               WHERE datedebut <= ? AND datefin >= ? AND TYPE = 5 AND mecano = ?";
$stmtMaladie = $conn->prepare($sqlMaladie);
$stmtMaladie->bind_param("sssss", $PremierJourCtrl, $DernierJourCtrl, $DateFinRequete, $DateDebutRequete, $mecano);
$stmtMaladie->execute();
$resultMaladie = $stmtMaladie->get_result();*/

//$annee = 2025; // Année spécifique
$stmtAnnee = $connection->prepare("SELECT annee FROM annee LIMIT 1");
$stmtAnnee->execute();
$resultAnnee = $stmtAnnee->get_result();

if ($row = $resultAnnee->fetch_assoc()) {
    $annee = $row['annee'];
} else {
    die("Aucune année active trouvée");
}

$DateDebutRequete = "$annee-01-01";
$DateFinRequete = "$annee-12-31";

// Version CORRIGÉE sans commentaires SQL incorrects
$sqlMaladie = "SELECT 
    SUM(
        DATEDIFF(
            LEAST(?, datefin),
            GREATEST(?, datedebut)
        )+1
    ) AS TotalJoursMaladie 
    FROM autreconge 
    WHERE datedebut <= ? 
    AND datefin >= ? 
    AND TYPE = 5 
    AND mecano = ?";

$stmtMaladie = $connection->prepare($sqlMaladie);
$stmtMaladie->bind_param("sssss", $DateFinRequete, $DateDebutRequete, $DateFinRequete, $DateDebutRequete, $mecano);
$stmtMaladie->execute();
$resultMaladie = $stmtMaladie->get_result();
  $NbJourMaladie = 0;
while ($rMaladie = $resultMaladie->fetch_row()) {
    $NbJourMaladie = $rMaladie[0];
} 
   $maladieBrackets = array(
    array(7, 19, 1),
    array(20, 32, 2),
    array(33, 45, 3),
    array(46, 58, 4),
    array(59, 71, 5),
    array(72, 84, 6),
    array(85, 97, 7),
    array(98, 110, 8),
    array(111, 123, 9),
    array(124, 136, 10),
    array(137, 149, 11),
    array(150, 162, 12),
    array(163, 175, 13),
    array(176, 188, 14),
    array(189, 201, 15),
    array(202, 214, 16),
    array(215, 227, 17),
    array(228, 240, 18),
    array(241, 253, 19),
    array(254, 266, 20),
    array(267, 279, 21),
    array(280, 292, 22),
    array(293, 305, 23),
    array(306, 318, 24),
    array(319, 331, 25),
    array(332, 344, 26),
    array(345, 357, 27),
    array(358, 370, 28)
);

            $NbJourMaladieSoustract = 0;
            foreach ($maladieBrackets as $bracket) {
                if ($NbJourMaladie >= $bracket[0] && $NbJourMaladie <= $bracket[1]) {
                    $NbJourMaladieSoustract = $bracket[2];
                    break;
                }
            }
mysqli_stmt_close($stmtMaladie);			
   
   $stmt = mysqli_prepare($connection, "SELECT COUNT(mecano) FROM conge WHERE annee= ? AND mecano = ?");
    mysqli_stmt_bind_param($stmt, "ss",$annee, $mecano);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $numrows);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
  
   // $re=mysql_query("select * from conge where annee='".$annee."' AND mecano='".$mecano."' order by datedebut asc");
	// $i=0;
	// if(mysql_num_rows($re) > 0){
	// while ($r=mysql_fetch_row($re)) 
		 
	 $re = mysqli_query($connection, "SELECT * FROM conge WHERE annee='".$annee."' AND mecano='".$mecano."' ORDER BY datedebut ASC");
	 $i=0;
	 $num_rows = mysqli_num_rows($re);
	 if($num_rows >0){
		 while($r = mysqli_fetch_row($re))
	 
	 {
if($i==0)
{
echo '<tr>
	 
	 <td align=center>'.$r[5].'</td>';
	 if($NbJourMaladieSoustract>0){
	 echo '<td align=center dir=rtl>('.$Actuelsolde.'+'.$Restsolde.')='.$NbJourMaladieSoustract.'-'.$Anciensolde.'='.$Anciensolde-$NbJourMaladieSoustract.'</td>';
	 }else{
		  echo '<td align=center dir=rtl>('.$Actuelsolde.'+'.$Restsolde.')='.$Anciensolde.'</td>';
	 }
	 echo '<td align=center>'.$r[0].'</td>
	 <td align=center>'.$r[2].'</td>
	 <td align=center>'.$r[3].'</td>
	 <td align=center>'.$r[4].'</td>';
	  if($NbJourMaladieSoustract>0){
	echo ' <td>  خصم بعنوان المرض<span> '. $NbJourMaladieSoustract.'</span> أيّام</td></tr>';
	  }else{
		  echo '<td></td></tr>';
	  }
	 $Anciensolde=$Anciensolde-$NbJourMaladieSoustract;
}	
	else{
	 echo '<tr>
	 
	 <td align=center>'.$r[5].'</td>
	 <td align=center dir=rtl>'.$Anciensolde.'</td>
	 <td align=center>'.$r[0].'</td>
	 <td align=center>'.$r[2].'</td>
	 <td align=center>'.$r[3].'</td>
	 <td align=center>'.$r[4].'</td>
	 <td></td></tr>';
	}
	$Anciensolde=$Anciensolde-$r[4];
	$i=$i+1;
      if ($i==$numrows){
echo '<tr>
     
	 <td align=center>'.$r[5].'</td>
	 <td align=center>'.$Anciensolde.'</td>
	 <td align=center></td>
	 <td align=center></td>
	 <td align=center></td>
	 <td align=center></td>
	 <td></td></tr>';
}	


 if (($i % 11)==0 and $numrows>11){
echo '</table><p></p>
<table width="100%" border="3" cellspacing="0" dir=rtl>
  <tr>
   <td width="15%" rowspan=2><div align="center" class="title">السنة</div></td>
    <td width="15%" rowspan=2><div align="center" class="title">رصيد الإجازات المتبقيّة</div></td>
    <td width="40%" colspan=4><div align="center" class="title">الإجازة المطلوبة</div></td>
    <td width="35%" rowspan=2><div align="center" class="title">ملاحظات</div></td>
  </tr>
  <tr>
    <td><div align="center" class=title>الرقم</div></td>
    <td><div align="center" class=title>من</div></td>
	<td><div align="center" class=title>إلى</div></td>
	<td><div align="center" class=title>عدد الأيام</div></td>
	    
  </tr>';
}	
	 }
	 }
	 else
	 {
		echo '<tr>
	 
	 <td align=center>'.$annee.'</td>
	 <td align=center dir=rtl>('.$Actuelsolde.'+'.$Restsolde.')='.$Anciensolde.'</td>
	 <td align=center> </td>
	 <td align=center> </td>
	 <td align=center> </td>
	 <td align=center> </td>
	 <td></td></tr>'; 
	 }
	 	
		?>
</table>
</body>
</html>