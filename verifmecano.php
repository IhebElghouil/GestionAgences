<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<script>
function verifdisponibilite(Ncertif,Datecertif) {
  if (Ncertif=="" && Datecertif=="") {
    document.getElementById("txtHint1").innerHTML="";
    return;
  }
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("txtHint1").innerHTML=this.responseText;
    }
  }
  
  xmlhttp.open("GET","verifmecano.php?num="+Ncertif+"&date="+Datecertif,true);
  xmlhttp.send();
  
}
</script>
</head>
<body>
<?php
require("connection.php"); 
//on vérifie si le mécano existe déjà dans la table stuf
// si vrai on affiche les textbox pour compléter l'insertion de données concernant le certificat médical
// si faux on affiche un message indiquant que le mecano n'existe pas dans la table stuf

if(isset($_GET['q']))
{
$req="select nom from stuf where mecano='".@$_GET['q']."'";
$re=mysqli_query($connection,$req);
if($r=@mysqli_fetch_row($re))
{	echo ' <div class="mb-2" align=right>
            <label for="message-text" class="col-form-label" dir=rtl>الإسم و اللقب :</label>
            <input type="text" class="form-control" id="nomprenom" value="'.$r[0].'" align=right dir=rtl readonly>
          </div>
		  <div class="mb-2" align=right>
            <label for="message-text" class="col-form-label" dir=rtl>تاريخ الشهادة الطبيّة :</label>
            <input type="date" class="form-control" id="datecertif" name="datecertif" value=""  align=right dir=rtl   placeholder="yyyy-dd-mm" required>
          </div>
		  <div class="mb-2" align=right>
            <label for="message-text" class="col-form-label" dir=rtl>رقم الشهادة الطبيّة :</label>
            <input type="text" class="form-control" id="numcertif" name="numcertif" value=""  align=right dir=rtl required>
          </div>
		  <div class="mb-2" align=right>
            <label for="message-text" class="col-form-label" dir=rtl>تاريخ نهاية الصلوحيّة :</label>
            <input type="date" class="form-control" id="finvaliditecertif" name="finvaliditecertif" value=""  align=right dir=rtl placeholder="yyyy-dd-mm" required>
          </div>
		  <div class="mb-3" align=right>
            <label for="message-text" class="col-form-label" dir=rtl>الملاحظات :</label>
            <textarea class="form-control" id="observations" name="observations" value=""  align=right dir=rtl  /></textarea>
          </div>
		  <div class="modal-footer">
           <button type="submit" class="btn btn-primary">حفظ التحيينات</button>
         </div>'; 
		 
}else{
	echo 'الرقم الآلي غير موجود بقاعدة البيانات';
}
}
else
{
	echo 'ERRROR';
}
?>
</body>
</html>
