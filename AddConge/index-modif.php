<!DOCTYPE html>
<meta charset="UTF-8">
<?php
session_start();
require('DbConnexion.php');
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<script src="JS/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

<script>
function hided()
{
	str1=$('#myInput').val();
	if (str1.length > 1) {
		$("#Content").load("load-text.php", {'myInput':str1});
	}


}
</script>
<link href="css/bootstrap.min.css" rel="stylesheet"  crossorigin="anonymous">
<style>      
 		select,input {
             font-size: 20px;
             width:180px;
             height:50px;
			 direction: rtl;
			 text-align: right;
}
          div {
			  padding-top: 10px;
			  padding-bottom: 10px;
			 text-align: center;
			 position: relative;
			 margin: auto;
			 direction: rtl;
                         
}
      p {
		  font-size: 1.5em;
		  direction: rtl;
		  text-align: center;
		  background-color : #eafaf1;
		  font-weight: bold;
	  }
    </style>

</head>
<?php
/*// Fonction permettant de compter le nombre de jours ouvrés entre deux dates
    function get_nb_open_days($date_start, $date_stop) {
    $arr_bank_holidays = array(); // Tableau des jours feriés
 
    // On boucle dans le cas où l'année de départ serait différente de l'année d'arrivée
    $diff_year = date('Y', $date_stop) - date('Y', $date_start);
    for ($i = 0; $i <= $diff_year; $i++) {
    $year = (int)date('Y', $date_start) + $i;
    // Liste des jours feriés
    $arr_bank_holidays[] = '1_1_'.$year; // رأس السنة الادارية
    $arr_bank_holidays[] = '1_5_'.$year; // عيد الشغل
    $arr_bank_holidays[] = '9_4_'.$year; // عيد الشهداء
    $arr_bank_holidays[] = '25_7_'.$year; // عيد الجمهورية
    $arr_bank_holidays[] = '13_8_'.$year; // عيد المراة
    $arr_bank_holidays[] = '20_3_'.$year; // عيد الاستقلال
    $arr_bank_holidays[] = '15_10_'.$year; // عيد الجلاء
    $arr_bank_holidays[] = '17_12_'.$year; // عيد الثورة
 
    // Récupération de paques. Permet ensuite d'obtenir le jour de l'ascension et celui de la pentecote
    /*$easter = easter_date($year);
    $arr_bank_holidays[] = date('j_n_'.$year, $easter + 86400); // Paques
    $arr_bank_holidays[] = date('j_n_'.$year, $easter + (86400*39)); // Ascension
    $arr_bank_holidays[] = date('j_n_'.$year, $easter + (86400*50)); // Pentecote
    }
    //print_r($arr_bank_holidays);
    $nb_days_open = 0;
    while ($date_start <= $date_stop) {
    // Si le jour suivant n'est ni un dimanche (0) ou un samedi (6), ni un jour férié, on incrémente les jours ouvrés
    if (!in_array(date('w', $date_start), array($_GET['JourDeRepos']))
    && !in_array(date('j_n_'.date('Y', $date_start), $date_start), $arr_bank_holidays)) {
    $nb_days_open++;
     }
     $date_start += 86400;
     }
	 
	     return $nb_days_open;
	    }
 
    // Exemple : Du 11 au 15 juillet il n'y a qu'un jour ouvré (week-end + 1 jours férié)
	if(ISSET($_GET['DateDebut'])){
     $date_depart = strtotime($_GET['DateDebut']);
     $date_fin = strtotime($_GET['DateFin']);
     $nb_jours_ouvres = get_nb_open_days($date_depart, $date_fin);
    echo 'Il y a '.$nb_jours_ouvres.' jours ouvr&eacute;s entre le '.date('d/m/Y', $date_depart).' et le '.date('d/m/Y', $date_fin);
	
	}*/
?>


<body data-spy="scroll" data-target="#navbar-example">



<div><a href="congenational.php"><img src="../images/calendrier1.png" height ="80" width="100" /></a><br/>
<span>إضافة عطلة رسميّة</span></div>
<div class="card" style="max-width: 50rem;text-align: center;">

<button type=submit class="btn btn-primary" disabled>مطلب إجازة سنويّة</button>


<form action="Insert.php" method="GET" dir=rtl> 



 <div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>الرقم الآلي : </span>
 <input type="text" id="myInput"  onkeyup="hided()"  placeholder="الرقم الآلي" title="أدخل الرقم الآلي" dir="rtl" value="<?php if(ISSET($_GET['mecano'])){echo $_GET['mecano'];}?>"> 
</div>
<div class="ex1" id="Content" style=display: none;>
</div>

</form>
</body>
</html>