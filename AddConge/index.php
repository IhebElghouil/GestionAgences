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
$(document).ready(function() {
    // Initialisation
    var mecano = "<?php echo isset($_GET['mecano']) ? htmlspecialchars($_GET['mecano'], ENT_QUOTES) : ''; ?>";
    if(mecano) {
        $('#myInput').val(mecano);
    }
    
    // Sélection et focus
    $('#myInput').focus().select();
    
    // Appel initial
    hided();
    
    // Gestion des événements
    $('#myInput').on('input', hided); // Meilleure que onkeyup
});
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
	   .card {
        width: 90%;
        max-width: 900px; /* Ajustement de la largeur maximale */
        margin: 20px auto;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    </style>
<style>
.holiday-button-container {
    display: inline-block;
    margin: 10px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.holiday-button {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-decoration: none;
    width: 120px;
    transition: all 0.3s ease;
    padding: 15px 10px;
    border-radius: 12px;
    background: linear-gradient(145deg, #ffffff, #f0f0f0);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    color: #333;
    border: 1px solid #e0e0e0;
}

.holiday-button:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    background: linear-gradient(145deg, #f8f8f8, #e8e8e8);
}

.holiday-button:active {
    transform: translateY(1px);
}

.button-icon {
    margin-bottom: 10px;
}

.icon {
    height: 60px;
    width: 60px;
    object-fit: contain;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    transition: transform 0.3s ease;
}

.holiday-button:hover .icon {
    transform: scale(1.05);
}

.button-text {
    font-size: 14px;
    font-weight: 500;
    text-align: center;
    color: #2c3e50;
    line-height: 1.3;
}
.return-link {
    display: flex;
    justify-content: center;
    margin-top: 15px;
}

.return-link a {
    text-decoration: none;
    color: #007bff;
    font-weight: bold;
    font-size: 15px;
    transition: color 0.3s;
}

.return-link a:hover {
    color: #0056b3;
}
</style>
</head>



<body data-spy="scroll" data-target="#navbar-example">



<div class="holiday-button-container">
    <a href="congenational.php" class="holiday-button">
        <div class="button-icon">
            <img src="../images/calendrier1.png" alt="Icône calendrier" class="icon"/>
        </div>
        <span class="button-text">إضافة عطلة رسميّة</span>
    </a>
</div>

<div class="return-link">
    <a href="http://192.168.1.20:8081/GestionAgences/c_agences.php">
        <i class="fas fa-arrow-right me-1"></i> العودة إلى القائمة الرئيسية
    </a>
</div>
<div class="card">

<button type=submit class="btn btn-primary" disabled>مطلب إجازة سنويّة</button>


<form action="Insert.php" method="GET" dir=rtl> 



 <div class="input-group mb-3" dir=rtl>
<span class="input-group-text" id="basic-addon2" dir=rtl>الرقم الآلي : </span>
 <input type="text" id="myInput"  onkeyup="hided()"  placeholder="الرقم الآلي" title="أدخل الرقم الآلي" dir="rtl" value="<?php if(ISSET($_GET['mecano'])){echo $_GET['mecano'];}?>"> 
</div>
<div class="ex1" id="Content" style=display: none; class="card">
</div>

</form>
</body>
</html>