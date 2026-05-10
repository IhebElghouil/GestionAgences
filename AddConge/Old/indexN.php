<?php
session_start();
require('connection.php');
?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<script src="JS/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<style>      
 		select,input {
             font-size: 20px;
             width:120px;
             height:50px;
			 direction: rtl;
			 text-align: right;
}
          div {
			 padding-top: 5px;
			 padding-bottom: 5px;
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
	       span   {
		  font-size: 3em;
		  direction: rtl;
		  text-align: center;
		 
	  }
	  
    </style>


<script src="http://127.0.0.1/Addconge/JS/jquery.min.js"></script>
</head>
<?php
// Fonction permettant de compter le nombre de jours ouvrés entre deux dates
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
	
	}
?>


<body>


<div class="card" style="max-width: 50rem;text-align: center;">

<button type=submit class="btn btn-primary" disabled>مطلب إجازة سنويّة</button>


<form action="indexN.php" method="GET" dir=rtl> 



 <div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>الإسم و اللّقب :</span>
<select name="Mecano"   id='Employe' class="form-select" aria-label=".form-select-lg example"  style="font-size: 18px;" >
	  <option value=0>-----------------------------</option>
<?php	  
$requeteFindEmploye=mysql_query("SELECT stuf.mecano,nom,jrepos,rest FROM stuf LEFT JOIN nbconge on stuf.mecano=nbconge.mecano where contrastage in (0,1,3) order by stuf.mecano asc");	
$num_rows = mysql_num_rows($requeteFindEmploye);
if ($num_rows>0){
while($rNom=mysql_fetch_row($requeteFindEmploye)) {
echo '<option value='.$rNom[0].'>'.$rNom[0].'-'.$rNom[1].'</option>';
}
}
?>
</select>  </div>
 
      
               


<select name="TypeJourDeRepos" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
<option value=0>إجازة سنويّة</option>
</select>


<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>تاريخ بداية الإجازة : </span>
<input type=date name="DateDebut" id="datepicker" style="font-size: 18px;" title="تاريخ بداية الإجازة" value="<?php if(ISSET($_GET['DateDebut'])){echo $_GET['DateDebut'];}else { echo date('Y-m-d');} ?>" dir=rtl min= "<?php  $Date = date('Y-m-d');

// Add days to date and display it
echo date('Y-m-d', strtotime($Date. ' - 3 days'));  ?>"  class="form-select" aria-label=".form-select-lg example">
</div>



<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>تاريخ نهاية الإجازة : </span>
<input type=date name="DateFin" id="datepicker" style="font-size: 18px;"   title="تاريخ نهاية الإجازة" value="<?php if(ISSET($_GET['DateFin'])){echo $_GET['DateFin'];}else { echo date('Y-m-d');} ?>"  class="form-select" aria-label=".form-select-lg example">
</div>

<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>يوم الرّاحة الأسبوعيّة : </span>
<select name="JourDeRepos"  class="form-select " aria-label=".form-select-lg example"  title="يوم الراحة الأسبوعيّة"  style="font-size: 18px;" >
<option value=0 <?php if(ISSET($_GET['JourDeRepos'])) { if($_GET['JourDeRepos']==0) {echo 'selected';}}?>>الأحد</option>
<option value=1 <?php if(ISSET($_GET['JourDeRepos'])) { if($_GET['JourDeRepos']==1) {echo 'selected';}}?>>الإثنين</option>
<option value=2 <?php if(ISSET($_GET['JourDeRepos'])) { if($_GET['JourDeRepos']==2) {echo 'selected';}}?>>الثلاثاء</option>
<option value=3 <?php if(ISSET($_GET['JourDeRepos'])) { if($_GET['JourDeRepos']==3) {echo 'selected';}}?>>الأربعاء</option>
<option value=4 <?php if(ISSET($_GET['JourDeRepos'])) { if($_GET['JourDeRepos']==4) {echo 'selected';}}?>>الخميس</option>
<option value=5 <?php if(ISSET($_GET['JourDeRepos'])) { if($_GET['JourDeRepos']==5) {echo 'selected';}}?>>الجمعة</option>
<option value=6 <?php if(ISSET($_GET['JourDeRepos'])) { if($_GET['JourDeRepos']==6) {echo 'selected';}}?>>السبت</option>

</select>
</div>
<div class="input-group mb-3" dir=rtl>
 <span class="input-group-text" id="basic-addon2" dir=rtl>الرصيد الحالي للإجازات : </span> 
<!--<input type="text" class="form-control"  style="font-size: 18px;" placeholder="الرصيد الحالي للإجازات......" aria-label="Recipient's username" aria-describedby="basic-addon2" dir=rtl name="NbJours" id="NbJours" readonly value="<?php echo $rNom[3]; ?>">-->
 <select id="SoldeRestant" name="SoldeRestant" disabled>
        <option value="<?php if(ISSET($_GET['SoldeRestant'])){echo $_GET['SoldeRestant'];}else { echo 0;} ?>"><?php if(ISSET($_GET['SoldeRestant'])){echo $_GET['SoldeRestant'];}else { echo 0;} ?></option>
 </select>
</div>

<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>عدد أيّام الرّاحة : </span>
<input type="text" name="NbJours"  style="font-size: 18px;" id="NbJours" readonly value="<?php if(ISSET($_GET['DateFin'])){echo $nb_jours_ouvres;}else { echo 0;} ?>" class="form-control" placeholder="عدد أيّام الرّاحة......" aria-label="Recipient's username" aria-describedby="basic-addon2" dir=rtl readonly >
</div>

<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>الرّصيد المتبقّي : </span>
<input type="text" name="NbJoursRest"  style="font-size: 18px;" id="NbJoursRest" readonly value="" class="form-control" placeholder="الرّصيد المتبقّي......" aria-label="Recipient's username" aria-describedby="basic-addon2" dir=rtl readonly>
</div>

<div class="d-grid gap-2" >
<button type=submit class="btn btn-primary">حفظ المعطيات</button>
</div>
<div>

<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script>
      $(document).ready(function(){
        $("#Employe").change(function(){
          var mecano=$(this).val();
          $.ajax({
            url:'load_products.php',
            type:'POST',
            data:{id:mecano},
            success:function(res){
              $("#SoldeRestant").html(res);
            }
          });
        });
      });
	  </script>
</div>
</form>
</div>
<div class="ex1" id="Content" style=display: none;>
</div>
</body>
</html>