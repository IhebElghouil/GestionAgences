<!DOCTYPE html>
<meta charset="UTF-8">
<HTML lang="ar">
<HEAD>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link href="css/bootstrap.min.css" rel="stylesheet"  crossorigin="anonymous">
<script src="JS/bootstrap.bundle.min.js"  crossorigin="anonymous"></script>
<script src="JS/jquery-3.6.0.min.js"></script>
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

table.result {
-moz-border-radius:80px 0;
-webkit-border-radius:80px 0;
border-radius:80px 0;
}




</style>

<script>
    $("#success-alert").hide();
	$("#buttonStyle").hide();
        // Liste des jours fériés au format 'YYYY-MM-DD'
        var joursFeries = ['2025-01-01', '2025-03-20', '2025-04-09', '2025-05-01', '2025-07-25', '2025-08-13', '2025-10-15', '2025-12-17'];
             // Fonction pour vérifier si une date est un jour de repos (weekend)
        function estJourDeRepos(date) {
            const day = new Date(date).getDay(); // 0 = dimanche, 6 = samedi
            //return day === 0 || day === 6; // Retourne true si c'est un samedi ou un dimanche
			if ($('select#JourDeReposFixe').val()==10)
			{
			return day === 0 || day === 6;
			}
			else
			{
            return day == $('select#JourDeReposFixe').val();       
			}
	   }

        // Fonction pour vérifier si une date est un jour férié
        function estJourFerie(date) {
            const dateStr = date.toISOString().split('T')[0]; // Format 'YYYY-MM-DD'
            return joursFeries.includes(dateStr);
        }

        // Fonction pour calculer la différence entre deux dates en jours ouvrés (en excluant weekends et jours fériés)
        function calculerDifferenceOuvree(DateDebut, DateFin) {
            let startDate = new Date(DateDebut);
            let endDate = new Date(DateFin);

            let totalDays = 0;

            // Assurez-vous que startDate soit toujours avant endDate
            if (startDate > endDate) {
                const temp = startDate;
                startDate = endDate;
                endDate = temp;
            }

            // Calcul de la différence en jours ouvrés
            for (let d = startDate; d <= endDate; d.setDate(d.getDate() + 1)) {
                if (!estJourDeRepos(d) && !estJourFerie(d)) {
                    totalDays++;
                }
            }

            return totalDays;
        }

        // Événement de soumission du formulaire
        $('#DateDebut, #DateFin,#JourDeReposFixe').on('change', function(event) {
            event.preventDefault(); // Empêche la soumission normale du formulaire

            const DateDebut = $('#DateDebut').val();
            const DateFin = $('#DateFin').val();
			const mecano= str1;

            // Vérification si les dates sont valides
            if (!DateDebut || !DateFin) {
               // alert('يرجى إختيار تاريخ بداية الإجازة و نهايتها');
				 //$("#success-alert").fadeTo(2000, 500).slideUp(800, function(){
                 //$("#success-alert").slideUp(500);
                 //}); 
				
               return;
            }
			
			var Date1 = new Date($('#DateDebut').val());
			var Date2 = new Date($('#DateFin').val());
      
      AnneeDebut = Date1.getFullYear();
	  AnneeFin = Date2.getFullYear();
	  
			if (AnneeDebut != AnneeFin) {
                //alert('الإجازة لا يمكن أن تمتدّ على سنتين');
				
				$("#success-alert").fadeTo(2000, 500).slideUp(800, function(){
                $("#success-alert").slideUp(500);
                }); 
				$('#success-alert').html('الإجازة لا يمكن أن تمتدّ على سنتين');
				
				
				var inputDateFin = document.getElementById('DateFin');
                inputDateFin.value = null;
				var input = document.getElementById('NbJoursConge');
                input.value = 0;
				
				var input = document.getElementById('NbJoursResteSolde');
                input.value = $('#Solde').val();
				
				
				 $("#buttonStyle").hide();
                return;
            }
			
			if (DateDebut > DateFin) {
                //alert('يرحى التثبّت في تاريخ نهاية الإجازة');
				$("#success-alert").fadeTo(2000, 500).slideUp(800, function(){
   $("#success-alert").slideUp(500);
    }); 
				$('#success-alert').html('يرحى التثبّت في تاريخ نهاية الإجازة');
				
				var inputDateFin = document.getElementById('DateFin');
                inputDateFin.value = null;
				var input = document.getElementById('NbJoursConge');
                input.value = 0;
				
				var input = document.getElementById('NbJoursResteSolde');
                input.value = $('#Solde').val();
				
				
				 $("#buttonStyle").hide();
                return;
            }
			
			const difference = calculerDifferenceOuvree(DateDebut, DateFin);
			if (difference ==0) {
                //alert('يرحى التثبّت في تاريخ نهاية الإجازة');
				//customAlert.alert('يرجى إختيار تاريخ بداية الإجازة و نهايتها ');
				//var inputDateFin = document.getElementById('DateFin');
                //inputDateFin.value = null;
				$("#buttonStyle").hide();
			                return;
            }

            // Calcul de la différence en jours ouvrés
           // const difference = calculerDifferenceOuvree(DateDebut, DateFin);

            // Affichage du résultat
			if (difference <=  $('#Solde').val())
			{
            //$('#difference').text(difference);
			
			var input = document.getElementById('NbJoursConge');
    
    // Affecter une valeur à cet input
             input.value = difference;
			 
			 document.getElementById('NbJoursResteSolde').value = document.getElementById('Solde').value - difference;
			//$('NbJoursConge').val()==difference;
			
			 //$("#buttonStyle").show();
}
else{
               
				$("#success-alert").fadeTo(2000, 500).slideUp(800, function(){
                $("#success-alert").slideUp(500);
                }); 
				$('#success-alert').html('رصيد الإجازات غير كافي');
				
				var inputDateFin = document.getElementById('DateFin');
                inputDateFin.value = null;
				
				var input = document.getElementById('NbJoursConge');
                input.value = 0;
				
				var input = document.getElementById('NbJoursResteSolde');
                input.value = $('#Solde').val();
				
				
				 $("#buttonStyle").hide();
                return;
            }
            // Optionnel : envoyer les données au serveur via AJAX
            $.ajax({
                url:'verifdate.php',  // Remplacez par l'URL de votre script serveur
                type: 'GET',
                data: {
                    DateDebut: DateDebut,
                    DateFin: DateFin,
                    difference: difference,
					mecano: mecano
                },
				timeout: 5000, // 5 secondes
                success: function(response) {
                    if (response==0){
						
						$("#success-alert").fadeTo(2000, 500).slideUp(800, function(){
                $("#success-alert").slideUp(500);
                }); 
				$('#success-alert').html('الراحة المطلوبة قد تكون مسجلة سابقا او جزء تابع لراحة مسجلة');
				
				var inputDateFin = document.getElementById('DateFin');
                inputDateFin.value = null;
				
				var input = document.getElementById('NbJoursConge');
                input.value = 0;
				
				var input = document.getElementById('NbJoursResteSolde');
                input.value = $('#Solde').val();
				 $("#buttonStyle").hide();
                return;
						
					}
					else
					{
						$("#buttonStyle").show();
					}
                },
                error: function(xhr, status, error) {
                   if (status === 'timeout') {
            alert('La requête a expiré. Veuillez réessayer.');
        } else {
            console.log('Erreur AJAX:', error);
        }
					
					
                }
            });
        });
    </script>


</HEAD>
<body onload="myFunction()">

<?php
/*session_start();*/
echo '<table id="result" class="result" align=center>';
require('connection.php');
//$requete=mysql_query("select * from stuf where mecano='".mysql_real_escape_string($_POST['myInput'])."'");
 #region affichage congés Annuels
  if ($_POST['myInput'] <> "") {

 $requete=mysql_query("select conge.id,datedebut,datefin,nbjours,nom from conge left join stuf on conge.mecano=stuf.mecano where conge.mecano='".mysql_real_escape_string($_POST['myInput'])."' order by conge.mecano,datefin desc");	
 //$rec=mysql_query("select autreconge.mecano,nom,datedebut,datefin,nbjours,type from autreconge left join stuf on autreconge.mecano=stuf.mecano where autreconge.mecano='1178' and contrastage in (0,1,3) order by autreconge.mecano,datefin desc");	
 $requeteFind=mysql_query("select stuf.mecano,stuf.nom,stuf.daten,stuf.daterec,titres.libellet,nbconge.nbj1,nbconge.nbj2,nbconge.rest,((nbj1+nbj2)-rest),stuf.jrepos from stuf LEFT JOIN titres on stuf.titre=titres.id LEFT JOIN nbconge on nbconge.mecano=stuf.mecano where stuf.mecano='".mysql_real_escape_string($_POST['myInput'])."'");	
  
$rec=mysql_query("select autreconge.id,datedebut,datefin,commentaire,type,type2 from autreconge left join stuf on autreconge.mecano=stuf.mecano where autreconge.mecano='".mysql_real_escape_string($_POST['myInput'])."' order by datefin desc");	


 $requetecar=mysql_query("select * from carriere where mecano='".mysql_real_escape_string($_POST['myInput'])."' order by dateeffet desc");	

$PremierJourCtrl = date('Y-01-01', strtotime('next year'));
$DernierJourCtrl = date('Y-12-31', strtotime('last year'));

$DateFinRequete=date('Y-12-31');
$DateDebutRequete=date('Y-01-01');

$requeteMaladie=mysql_query("SELECT SUM( DATEDIFF( LEAST( '".$PremierJourCtrl."', `datefin` ) , -- Limiter la date de fin à 2023
GREATEST( '".$DernierJourCtrl."', datedebut )) ) AS TotalJoursMaladie FROM autreconge
WHERE datedebut <= '".$DateFinRequete."' AND datefin >= '".$DateDebutRequete."' AND TYPE =5 AND mecano='".mysql_real_escape_string($_POST['myInput'])."'");

while($rMaladie=mysql_fetch_row($requeteMaladie)){
	$NbJourMaladie=$rMaladie[0];
	
}
}

$num_rows = mysql_num_rows($requete);
if ($num_rows>0){
while($rNom=mysql_fetch_row($requeteFind)) {
	$mecano=$rNom[0];
	$nom=$rNom[1];
	$datenaissance=$rNom[2];
	$daterecrutement=$rNom[3];
	$grade=$rNom[4];
	$nbj1=$rNom[5];
	$nbj2=$rNom[6];
	$rest=$rNom[7];
	$jconsomme=$rNom[8];
	$jrepos=$rNom[9];
}	?>

<div class="input-group mb-3" dir=rtl>
<input type="hidden" name="mecano" value=<?php echo $mecano; ?>>
<span class="input-group-text" id="basic-addon2" dir=rtl>الإسم و اللّقب : </span>
<input type="text" class="form-control"  style="font-size: 18px;"  aria-label="Recipient's username" aria-describedby="basic-addon2" dir=rtl name="NomPrenom" id="NomPrenom" readonly value="<?php echo $nom; ?>">
</div>
<!--
<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>تاريخ الولادة : </span>
<input type="text" class="form-control"  style="font-size: 18px;" placeholder="الرصيد الحالي للإجازات......" aria-label="Recipient's username" aria-describedby="basic-addon2" dir=rtl name="NbJours" id="NbJours" disabled value="<?php echo $datenaissance; ?>">
</div>

 <div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>تاريخ الإنتداب : </span>
<input type="text" class="form-control"  style="font-size: 18px;" placeholder="الرصيد الحالي للإجازات......" aria-label="Recipient's username" aria-describedby="basic-addon2" dir=rtl name="NbJours" id="NbJours" disabled value="<?php echo $daterecrutement; ?>">
</div> 

<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>الرّتبة : </span>
<input type="text" class="form-control"  style="font-size: 18px;" placeholder="الرصيد الحالي للإجازات......" aria-label="Recipient's username" aria-describedby="basic-addon2" dir=rtl name="NbJours" id="NbJours" disabled value="<?php echo $grade; ?>">
</div>
	-->
<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>رصيد الإجازات : <?php echo "(".$nbj1."+".$nbj2." )";  ?></span>
<input type="text" class="form-control"  style="font-size: 18px;" aria-label="SoldeN-2" aria-describedby="basic-addon2" dir=rtl name="NbJoursN-2" id="NbJoursN-2" readonly value="<?php echo $nbj1+$nbj2; ?>">
</div>

<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl style="color:red;font-weight: bold;">خصم بعنوان الرخص المرضيّة : </span>
<input type="text" class="form-control"  style="font-size: 18px;color:red;font-weight: bold;"  aria-label="SoldeActuel" aria-describedby="basic-addon2" dir=rtl name="JourMaladie" id="JourMaladie" readonly value="<?php echo $NbJourMaladie; ?>">
</div>
<!--
<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>رصيد إجازات السّنة الحاليّة : </span>
<input type="text" class="form-control"  style="font-size: 18px;" aria-label="SoldeN" aria-describedby="basic-addon2" dir=rtl name="NbJoursN" id="NbJoursN" disabled value="<?php echo $nbj2; ?>">
</div>
-->
<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>أيّام الإجازة المتمتّع بها : </span>
<input type="text" class="form-control"  style="font-size: 18px;" placeholder="الرصيد الحالي للإجازات......" aria-label="JoursConsom" aria-describedby="basic-addon2" dir=rtl name="NbJours" id="NbJours" disabled value="<?php echo $jconsomme; ?>">
</div>

<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl style="color:red;font-weight: bold;">الرصيد الحالي للإجازات : </span>
<input type="text" class="form-control"  style="font-size: 18px;color:red;font-weight: bold;" placeholder="الرصيد الحالي للإجازات......" aria-label="SoldeActuel" aria-describedby="basic-addon2" dir=rtl name="Solde" id="Solde" readonly value="<?php echo $rest; ?>">
</div>
<!--
<select name="JourDeRepos" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
<option value=0>إجازة سنويّة</option>
</select>

-->
<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>تاريخ بداية الإجازة : </span>
<input type=date name="DateDebut" id="DateDebut" style="font-size: 18px;" title="تاريخ بداية الإجازة" value="<?php if(ISSET($_GET['DateDebut'])){echo $_GET['DateDebut'];}else { echo null;} ?>" dir=rtl min= "<?php  $Date = date('Y-m-d');

// Add days to date and display it
echo date('Y-m-d', strtotime($Date. ' - 3 days'));  ?>"  class="form-select" aria-label=".form-select-lg example">
</div>

<div class="alert alert-danger" id="success-alert">
    <a href="#" class="close" data-dismiss="alert">&times;</a>
    <strong>يرجى إختيار تاريخ بداية الإجازة و نهايتها</strong>
</div>

<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>تاريخ نهاية الإجازة : </span>
<input type=date name="DateFin" id="DateFin" style="font-size: 18px;"   title="تاريخ نهاية الإجازة" value="<?php if(ISSET($_GET['DateFin'])){echo $_GET['DateFin'];}else { echo null;} ?>"  class="form-select" aria-label=".form-select-lg example">
</div>

<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>يوم الرّاحة الأسبوعيّة : </span>
<select name="JourDeReposFixe"  id="JourDeReposFixe" class="form-select" aria-label=".form-select-lg example"  title="يوم الراحة الأسبوعيّة"  style="font-size: 18px;" >
<option value=0 <?php if($jrepos==0) {echo 'selected';}?>>الأحد</option>
<option value=1 <?php if($jrepos==1) {echo 'selected';}?>>الإثنين</option>
<option value=2 <?php if($jrepos==2) {echo 'selected';}?>>الثلاثاء</option>
<option value=3 <?php if($jrepos==3) {echo 'selected';}?>>الأربعاء</option>
<option value=4 <?php if($jrepos==4) {echo 'selected';}?>>الخميس</option>
<option value=5 <?php if($jrepos==5) {echo 'selected';}?>>الجمعة</option>
<option value=6 <?php if($jrepos==6) {echo 'selected';}?>>السبت</option>
<option value=10 <?php if($jrepos==10) {echo 'selected';}?>>السبت و الأحد</option>
</select>
</div>



<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>عدد أيّام الرّاحة : </span>
<input type="number" name="NbJoursConge"  id="NbJoursConge" style="font-size: 18px;"   class="form-control"  aria-label="NbJoursConge" dir=rtl readonly value="0">
</div>

<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl style="font-size: 18px;color:green;font-weight: bold;">الرّصيد المتبقّي : </span>
<input type="text" name="NbJoursResteSolde"  style="font-size: 18px;color:green;font-weight: bold;" id="NbJoursResteSolde"  class="form-control" placeholder="الرّصيد المتبقّي......" aria-label="SoldeRestant" aria-describedby="basic-addon2" dir=rtl readonly value="<?php echo $rest; ?>">
</div>

<div  id="buttonStyle" name="buttonStyle">
<button type=submit class="btn btn-primary" >حفظ المعطيات</button>
</div>
	


	

</div>


<?php echo '
	
<table class="table" id="result" dir=rtl>
<div class="message_box" style="margin:10px 0px;width: 100%;">
</div>
 
  <thead style="background-color:#FF0000">
    <tr>
      <th scope="col" colspan=6 style="color: red"><u>متابعة الإجازات السنويّة</u></th>
    </tr>
  </thead>

  <thead>
    <tr>
      <th scope="col">رقم الإجازة</th>
      <th scope="col">بداية الإجازة</th>
      <th scope="col">إنتهاء الإجازة</th>
	  <th scope="col">عدد الأيام</th>
	</tr>
  </thead>
  </table>
  <div class="card-body overflow-scroll" style="width: 100%; height: 500px; align: center;">
  <table class="table" id="result" dir=rtl>
  <tbody class="table-group-divider">
  
';
while($r=mysql_fetch_row($requete)){
  
echo '


  
    <tr>
      <td>'.$r[0].'</td>
      <td>'.$r[1].'</td>
      <td>'.$r[2].'</td>
	  <td>'.$r[3].'</td>
</tr>';}
echo'
 
</tbody>
</table>
</div>
<div style="height: 30px;"></div>';
/////////////////////////////////////////
////////////////////////////////////////

echo '
<table class="table" id="result" dir=rtl>
<div class="message_box" style="margin:10px 0px;width: 100%;">
</div>
  <thead>
    <tr>
      <th scope="col" colspan=6 style="color: red"><u>متابعة باقي الراحات</u></th>
    </tr>
  </thead>

  <thead>
    <tr>
      <th scope="col">رقم الراحة</th>
	  <th scope="col">نوع الراحة</th>
      <th scope="col">بداية الراحة</th>
      <th scope="col">إنتهاء الراحة</th>
	  <th scope="col">الملاحظات</th>
	</tr>
  </thead>
  </table>
  <div class="card-body overflow-scroll" style="width: 100%; height: 500px; align: center;">
  <table class="table" id="myTable" dir=rtl>
  <tbody class="table-group-divider">
';
while ($rc=mysql_fetch_row($rec)) {
	  
	  if($rc[4]==1)
	  {
		  if($rc[5]==1){
			  $code='CMi.F';
             $typec='دورة تكوينيّة';	
		  }else
			  if($rc[5]==3){
			  $code='CMi.M';
             $typec='مهمّة';	
		  }else
			  if($rc[5]==2){
			  $code='CMi.R';
             $typec='إجتماع';	
		  }
		
	  }else
		  if($rc[4]==2)
	  {
		$code='CEx';
        $typec=	'راحة إستثنائية';	
	  }else
		  if($rc[4]==3)
	  {
		$code='CِCul';
        $typec=	'رخصة ثقافيّة';	
	  }else
		  if($rc[4]==4)
	  {
		$code='CSy';
        $typec=	'رخصة نقابيّة';	
	  }else
		  if($rc[4]==5)
	  {
		$code='CMa';
        $typec=	'رخصة مرضيّة';	
	  }else
		  if($rc[4]==6)
	  {
		$code='CAt';
        $typec=	'حادث شغل';	
	  }else
		  if($rc[4]==7)
	  {
		$code='CRr';
        $typec=	'راحة تعويضيّة';	
	  }else
		  if($rc[4]==8)
	  {
		$code='CRr';
        $typec=	'عطلة أمومة';	
	  }else
		  if($rc[4]==9)
	  {
		$code='CRr';
        $typec=	'عطلة بدون أجر';	
	  }else
		  if($rc[4]==10)
	  {
		$code='CRr';
        $typec=	'إيقاف عن العمل';	
	  }
		  
		 echo'
		 <tr>
    <td>'.$code.''.$rc[0].'</td>
	<td>'.$typec.'</td>
    <td>'.$rc[1].'</td>
	<td>'.$rc[2].'</td>
	<td>'.$rc[3].'</td>
	
  </tr>
		';
		}
  
 echo'
  </tbody>
</table></div>'; 
  
  
  
  
  
echo '

<tr>
<td  align="right" dir="rtl"></td></tr>'; 




  }
else {
	echo '<tr align=center>
<td dir=rtl><div class="alert alert-danger" role="alert">
 الرجاء التثبّت من المعلومات !
</div></td>
</tr>';
  }
echo '</table>';

?>




</body>
</HTML>