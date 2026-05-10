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
div.TextArea {
      margin-bottom: 15px;
	  height:60px;
	      }
		  #TextRepos {
			  display: none;
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
		var e = document.getElementById("TypeRepos");
        var value = e.value;
		
        //var joursFeries = ['2025-01-01', '2025-03-20', '2025-04-09', '2025-05-01', '2025-07-25', '2025-08-13', '2025-10-15', '2025-12-17'];
var joursFeries = [];

        fetch('recupdate.php')
            .then(response => response.json())
            .then(data => {
                joursFeries = data;
                console.log(joursFeries);
                // Vous pouvez maintenant utiliser le tableau joursFeries
            })
            .catch(error => console.error('Erreur lors de la récupération des jours fériés:', error));            

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
			
if (document.getElementById("TypeRepos").value ==0 || 
   document.getElementById("TypeRepos").value ==1 || 
   document.getElementById("TypeRepos").value ==2 || 
   document.getElementById("TypeRepos").value ==3 || 
   document.getElementById("TypeRepos").value ==4 || 
   document.getElementById("TypeRepos").value ==7 || 
   document.getElementById("TypeRepos").value ==10 || 
   document.getElementById("TypeRepos").value ==13 || 
   document.getElementById("TypeRepos").value ==14 )
{
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
}else  {
		var startDate = new Date(DateDebut);
            var endDate = new Date(DateFin);

            var totalDays = 0;
	 for (var d = startDate; d <= endDate; d.setDate(d.getDate() + 1)) {
		  totalDays++;
	 }
	 return totalDays;
	}
        }

        // Événement de soumission du formulaire
        $('#DateDebut, #DateFin,#JourDeReposFixe,#TypeRepos').on('change', function(event) {
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
				
				
				 $('#DateFin').val(null);
            $('#NbJoursConge').val(0);
            $('#NbJoursResteSolde').val($('#Solde').val());
            $("#buttonStyle").hide();
                return;
            }
			
			if (DateDebut > DateFin) {
                //alert('يرحى التثبّت في تاريخ نهاية الإجازة');
				$("#success-alert").fadeTo(2000, 500).slideUp(800, function(){
   $("#success-alert").slideUp(500);
    }); 
				$('#success-alert').html('يرحى التثبّت في تاريخ نهاية الإجازة');
				
			$('#DateFin').val(null);
            $('#NbJoursConge').val(0);
            $('#NbJoursResteSolde').val($('#Solde').val());
            $("#buttonStyle").hide();
                return;
            }
			
			const difference = calculerDifferenceOuvree(DateDebut, DateFin);
			if (difference ==0) {
               
				$("#buttonStyle").hide();
			                return;
            }

            // Calcul de la différence en jours ouvrés
           // const difference = calculerDifferenceOuvree(DateDebut, DateFin);

            // Affichage du résultat
			if (document.getElementById("TypeRepos").value ==0){
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
               if(document.getElementById("TypeRepos").value==0){
				$("#success-alert").fadeTo(2000, 500).slideUp(800, function(){
                $("#success-alert").slideUp(500);
                }); 
				$('#success-alert').html('رصيد الإجازات غير كافي');
				
				 $('#DateFin').val(null);
            $('#NbJoursConge').val(0);
            $('#NbJoursResteSolde').val($('#Solde').val());
            $("#buttonStyle").hide();
                return;
			   }
            }}
			
			else
			{
			var input = document.getElementById('NbJoursConge');
            input.value = difference;	
			document.getElementById('NbJoursResteSolde').value = document.getElementById('Solde').value
			}
            // Optionnel : envoyer les données au serveur via AJAX
           $.ajax({
    url: 'verifdate.php',
    type: 'GET',
    data: {
        DateDebut: DateDebut,
        DateFin: DateFin,
        difference: difference,
        mecano: mecano
    },
    timeout: 5000,  // 5 secondes pour le timeout
    success: function(response) {
        // Si la réponse du serveur est 0, afficher un message d'alerte
        if (response == 0) {
            $("#success-alert").fadeTo(2000, 500).slideUp(800, function() {
                $("#success-alert").slideUp(500);
            }); 
            $('#success-alert').html('الراحة المطلوبة قد تكون مسجلة سابقا او جزء تابع لراحة مسجلة');

            // Réinitialiser les champs de date et de congé
            $('#DateFin').val(null);
            $('#NbJoursConge').val(0);
            $('#NbJoursResteSolde').val($('#Solde').val());
            $("#buttonStyle").hide();
        } else {
            $("#buttonStyle").show();
        }
    },
    error: function(xhr, status, error) {
        if (status === 'timeout') {
            alert('La requête a expiré. Veuillez réessayer.');
        } else {
            console.log('Erreur AJAX:', error);
        }
    },
    beforeSend: function() {
        // Optionnel : Afficher un indicateur de chargement avant la requête
        $('#loadingIndicator').show();
    },
    complete: function() {
        // Optionnel : Masquer l'indicateur de chargement après la réponse
        $('#loadingIndicator').hide();
    }
});
        });
    </script>
<script>
function showOnChange() {
  
 var e = document.getElementById("TypeRepos");
 var value = e.value;

if (value ==0)
{
	TextRepos.style.display = "none";
}
else
{
	TextRepos.style.display = "block";
}

}
</script>
 <script>
    function updateDate() {
      // Set the value using the correct format
      const newDate = '2025-12-01';
      document.getElementById('DateFin').value = newDate;
    }
  </script>
</HEAD>
<body onload="myFunction()">

<?php
/*session_start();*/
echo '<table id="result" class="result" align=center>';

// Connexion à la base de données avec mysqli
//$conn = new mysqli("127.0.0.1", "root", "", "pointage");
require_once(__DIR__ . "/DbConnexion.php");

// Vérifier la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Défini l'encodage de la connexion en UTF-8
$conn->set_charset("utf8");


//require('connection.php');
//$requete=mysql_query("select * from stuf where mecano='".mysql_real_escape_string($_POST['myInput'])."'");
 #region affichage congés Annuels
  if ($_GET['id'] <> "") {

 echo $_GET['id'];


// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}

// Sécurisation des données d'entrée
$mecano = $conn->real_escape_string($_POST['myInput']);

// Requête pour obtenir les informations de congé
$sqlConge = "SELECT conge.id, datedebut, datefin, nbjours 
             FROM conge 
             LEFT JOIN stuf ON conge.mecano = stuf.mecano 
             WHERE conge.mecano = ? AND stuf.contrastage in (0,1,3)
             ORDER BY conge.mecano, datefin DESC";
$stmtConge = $conn->prepare($sqlConge);
$stmtConge->bind_param("s", $mecano);
$stmtConge->execute();
$resultConge = $stmtConge->get_result();

// Requête pour obtenir les informations du salarié
$sqlFind = "SELECT stuf.mecano, stuf.nom, stuf.daten, stuf.daterec, titres.libellet, nbconge.nbj1, nbconge.nbj2, nbconge.rest, stuf.jrepos 
            FROM stuf 
            LEFT JOIN titres ON stuf.titre = titres.id 
            LEFT JOIN nbconge ON nbconge.mecano = stuf.mecano 
            WHERE stuf.mecano = ? AND stuf.contrastage in (0,1,3)";
$stmtFind = $conn->prepare($sqlFind);
$stmtFind->bind_param("s", $mecano);
$stmtFind->execute();
$resultFind = $stmtFind->get_result();

// Requête pour obtenir les autres congés
$sqlAutreConge = "SELECT autreconge.id, datedebut, datefin, commentaire, type, type2 
                  FROM autreconge 
                  LEFT JOIN stuf ON autreconge.mecano = stuf.mecano 
                  WHERE autreconge.mecano = ? AND stuf.contrastage in (0,1,3)
                  ORDER BY datefin DESC";
$stmtAutreConge = $conn->prepare($sqlAutreConge);
$stmtAutreConge->bind_param("s", $mecano);
$stmtAutreConge->execute();
$resultAutreConge = $stmtAutreConge->get_result();

// Requête pour obtenir les carrières
$sqlCarriere = "SELECT * FROM carriere WHERE mecano = ? ORDER BY dateeffet DESC";
$stmtCarriere = $conn->prepare($sqlCarriere);
$stmtCarriere->bind_param("s", $mecano);
$stmtCarriere->execute();
$resultCarriere = $stmtCarriere->get_result();

// Dates de contrôle
$PremierJourCtrl = date('Y-01-01', strtotime('next year'));
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
$resultMaladie = $stmtMaladie->get_result();


// Requête pour obtenir le nombre de jours de maladie
$sqlExceptionnel = "SELECT SUM(nbj) AS TotalJoursExceptionnel
               FROM autreconge 
               WHERE datedebut <= ? AND datefin >= ? AND TYPE = 2 AND mecano = ?";
$stmtExceptionnel = $conn->prepare($sqlExceptionnel);
$stmtExceptionnel->bind_param("sss",  $DateFinRequete, $DateDebutRequete, $mecano);
$stmtExceptionnel->execute();
$stmtExceptionnel->bind_result($NbJourExceptionnel);
$stmtExceptionnel->fetch();
 if ($NbJourExceptionnel === null) {
      $NbJourExceptionnel = 0; // or handle as needed
  }
$stmtExceptionnel->close();

// Récupérer le nombre total de jours de maladie
$NbJourMaladie = 0;
while ($rMaladie = $resultMaladie->fetch_row()) {
    $NbJourMaladie = $rMaladie[0];
}

// Affichage des résultats
$num_rows = $resultFind->num_rows;
if ($num_rows > 0) {
    while ($rNom = $resultFind->fetch_row()) {
        $mecano = $rNom[0];
        $nom = $rNom[1];
        $datenaissance = $rNom[2];
        $daterecrutement = $rNom[3];
        $grade = $rNom[4];
        $nbj1 = $rNom[5];
        $nbj2 = $rNom[6];
        $rest = $rNom[7];
        $jconsomme = $nbj1+$nbj2-$rest;
        $jrepos = $rNom[8];
    }
// calculs de nombre de jours à soustraire suite maladie

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
// Fermer les déclarations et la connexion
$stmtConge->close();
$stmtFind->close();
$stmtAutreConge->close();
$stmtCarriere->close();
$stmtMaladie->close();
$conn->close();
?>

<div class="input-group mb-3" dir=rtl>
<input type="hidden" name="mecano" value=<?php echo $mecano; ?>>
<span class="input-group-text" id="basic-addon2" dir=rtl>الإسم و اللّقب : </span>
<input type="text" class="form-control"  style="font-size: 18px;"  aria-label="Recipient's username" aria-describedby="basic-addon2" dir=rtl name="NomPrenom" id="NomPrenom" readonly value="<?php echo $nom; ?>">
</div>
<!--
<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>تاريخ الولادة : </span>
<input type="text" class="form-control"  style="font-size: 18px;" placeholder="الرصيد الحالي للإجازات......" aria-label="Recipient's username" aria-describedby="basic-addon2" dir=rtl name="NbJours" id="NbJours" disabled value="<?php // $datenaissance; ?>">
</div>

 <div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>تاريخ الإنتداب : </span>
<input type="text" class="form-control"  style="font-size: 18px;" placeholder="الرصيد الحالي للإجازات......" aria-label="Recipient's username" aria-describedby="basic-addon2" dir=rtl name="NbJours" id="NbJours" disabled value="<?php // $daterecrutement; ?>">
</div> 

<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>الرّتبة : </span>
<input type="text" class="form-control"  style="font-size: 18px;" placeholder="الرصيد الحالي للإجازات......" aria-label="Recipient's username" aria-describedby="basic-addon2" dir=rtl name="NbJours" id="NbJours" disabled value="<?php // $grade; ?>">
</div>
	-->
<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>رصيد الإجازات : <?php echo "(".$nbj1."+".$nbj2." )";  ?></span>
<input type="text" class="form-control"  style="font-size: 18px;" aria-label="SoldeN-2" aria-describedby="basic-addon2" dir=rtl name="NbJoursN-2" id="NbJoursN-2" readonly value="<?php echo $nbj1+$nbj2; ?>">
<span class="input-group-text" id="basic-addon2" dir=rtl style="color:red;font-weight: bold;">خصم بعنوان الرخص المرضيّة <?php echo "(".$NbJourMaladie." )"; ?> :</span>
<input type="text" class="form-control"  style="font-size: 18px;color:red;font-weight: bold;"  aria-label="SoldeActuel" aria-describedby="basic-addon2" dir=rtl name="JourMaladie" id="JourMaladie" readonly value="<?php echo $NbJourMaladieSoustract; ?>">
</div>

<!--<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl style="color:red;font-weight: bold;">خصم بعنوان الرخص المرضيّة : </span>
<input type="text" class="form-control"  style="font-size: 18px;color:red;font-weight: bold;"  aria-label="SoldeActuel" aria-describedby="basic-addon2" dir=rtl name="JourMaladie" id="JourMaladie" readonly value="<?php // $NbJourMaladie; ?>">
</div>

<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>رصيد إجازات السّنة الحاليّة : </span>
<input type="text" class="form-control"  style="font-size: 18px;" aria-label="SoldeN" aria-describedby="basic-addon2" dir=rtl name="NbJoursN" id="NbJoursN" disabled value="<?php // $nbj2; ?>">
</div>
-->
<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>أيّام الإجازة المتمتّع بها : </span>
<input type="text" class="form-control"  style="font-size: 18px;" placeholder="الرصيد الحالي للإجازات......" aria-label="JoursConsom" aria-describedby="basic-addon2" dir=rtl name="NbJours" id="NbJours" disabled value="<?php echo ($jconsomme-$NbJourMaladieSoustract); ?>">
<span class="input-group-text" id="basic-addon2" dir=rtl style="color:red;font-weight: bold;width:225px">الرصيد الحالي للإجازات : </span>
<input type="text" class="form-control"  style="font-size: 18px;color:red;font-weight: bold;" placeholder="الرصيد الحالي للإجازات......" aria-label="SoldeActuel" aria-describedby="basic-addon2" dir=rtl name="Solde" id="Solde" readonly value="<?php echo ($rest); ?>">

</div>

<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl style="color:red;font-weight: bold;">أيّام الرّاحة الإستثنائيّة المستهلكة :</span>
<input type="text" class="form-control"  style="font-size: 18px;color:red;font-weight: bold;"  aria-label="SoldeActuel" aria-describedby="basic-addon2" dir=rtl name="Solde" id="Solde" readonly value="<?php echo $NbJourExceptionnel; ?>">
</div>

<!--<select name="JourDeRepos" class="form-select form-select-lg mb-3" aria-label=".form-select-lg example">
<option value=0>إجازة سنويّة</option>
</select>

-->
<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>نوع الراحة :</span>
<select name="TypeRepos"  id="TypeRepos" class="form-select" aria-label=".form-select-lg example"  title="نوع الراحة"  style="font-size: 18px;" onchange='showOnChange()'>
<option value=0 selected="selected">إجازة سنويّة</option>
              <option value=1>مهمّة إداريّة</option>
              <option value=2>راحة استثنائية</option>
              <option value=3>رخصة ثقافية</option>
              <option value=4>رخصة نقابية</option>
			  <option value=5>رخصة مرضية</option>
			  <option value=6>حادث شغل</option>
              <option value=7>راحة تعويضيّة</option>
			  <option value=9>عطلة بدون أجر</option>
			  <option value=10>إيقاف عن العمل</option>
			  <option value=8>عطلة أمومة</option>
			  <option value=12>عطلة أبوّة</option>
			  <option value=11>عطلة ولادة</option>
			  <option value=13>دورة تكوينيّة</option>
			  <option value=14>إجتماع</option>
</select>
</div>
<div  dir=rtl id="TextRepos">
<span class="input-group-text" id="basic-addon2" dir=rtl>الملاحظات :</span>
<textarea id="TextArea" name="TextArea" class="form-control" id="exampleFormControlTextarea1" rows="5"></textarea>
</div>
<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>تاريخ بداية الإجازة : </span>
<input type=date name="DateDebut" id="DateDebut" style="font-size: 18px;" title="تاريخ بداية الإجازة" value="<?php if(ISSET($_GET['DateDebut'])){echo $_GET['DateDebut'];}else { echo null;} ?>" dir=rtl min= "<?php  //$Date = date('Y-m-d');

// Add days to date and display it
//echo date('Y-m-d', strtotime($Date. ' - 3 days'));  ?>"  class="form-select" aria-label=".form-select-lg example" autofocus>

<span class="input-group-text" id="basic-addon2" dir=rtl>تاريخ نهاية الإجازة : </span>
<input type=date name="DateFin" id="DateFin" style="font-size: 18px;"   title="تاريخ نهاية الإجازة" value="<?php if(ISSET($_GET['DateFin'])){echo $_GET['DateFin'];}else { echo null;} ?>"  class="form-select" aria-label=".form-select-lg example">
</div>

<div class="alert alert-danger" id="success-alert">
    <a href="#" class="close" data-dismiss="alert">&times;</a>
    <strong>يرجى إختيار تاريخ بداية الإجازة و نهايتها</strong>
</div>

<!--<div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>تاريخ نهاية الإجازة : </span>
<input type=date name="DateFin" id="DateFin" style="font-size: 18px;"   title="تاريخ نهاية الإجازة" value="<?php if(ISSET($_GET['DateFin'])){echo $_GET['DateFin'];}else { echo null;} ?>"  class="form-select" aria-label=".form-select-lg example">
</div>-->

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
<input type="text" name="NbJoursResteSolde"  style="font-size: 18px;color:green;font-weight: bold;" id="NbJoursResteSolde"  class="form-control" placeholder="الرّصيد المتبقّي......" aria-label="SoldeRestant" aria-describedby="basic-addon2" dir=rtl readonly value="<?php echo ($rest); ?>">
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
      <th scope="col" width="20%">رقم الإجازة</th>
      <th scope="col" width="20%">بداية الإجازة</th>
      <th scope="col" width="20%">إنتهاء الإجازة</th>
	  <th scope="col" width="20%">عدد الأيام</th>
	  <th scope="col"  width="20%" colspan="2">#</th>
	  
	</tr>
  </thead>
  </table>
  <div class="card-body overflow-scroll" style="width: 100%; height: 500px; align: center;">
  <table class="table" id="result" dir=rtl>
  <tbody class="table-group-divider">
  
';
//$CurrentDate = date_create(date('Y-m-d')); // Get the current date
while($r= $resultConge->fetch_row()){
  
echo '
 
    <tr>
      <td>'.$r[0].'</td>
      <td>'.$r[1].'</td>
      <td>'.$r[2].'</td>
	  <td>'.$r[3].'</td>';
	  
 $CtrlDateDebut = date_create($r[1]);; // Assuming $r[1] is a date string
$CurrentDate = new DateTime();

// Calculate the difference between the two dates
$diff = date_diff($CurrentDate,$CtrlDateDebut);

// Get the absolute difference in days
$Ecart = $diff->invert;

//if($Ecart==0){
echo '<td><a href="delete.php?id='.$r[0].'&Nbjours='.$r[3].'&mecano='.$mecano.'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
  <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
</svg></a></td>
<td><a href="update_conge.php?id=' . urlencode($r[0]) . '" rel="modal:open"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
</svg></a></td>
	 <td><a href="print.php?id='.$r[0].'&nom='.$nom.'"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
  <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
  <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1"/>
</svg></a></td>
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
	  <th scope="col" width="40%">الملاحظات</th>
	</tr>
  </thead>
  </table>
  <div class="card-body overflow-scroll" style="width: 100%; height: 500px; align: center;">
  <table class="table" id="myTable" dir=rtl width="100%">
  <tbody class="table-group-divider">
';
while ($rc=$resultAutreConge->fetch_row()) {
	  
	  if($rc[4]==1)
	  {
		  if($rc[5]==1){
			  $code='CMi.F';
             $typec='تكوين';	
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
	<td width="40%">'.$rc[3].'</td>
	
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
  }}
echo '</table>';

?>




</body>
</HTML>