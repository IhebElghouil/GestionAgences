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
$re=mysql_query($req);
if($r=@mysql_fetch_row($re))
{	
	echo 'الرقم الآلي موجود بقاعدة البيانات';	 
}else{
	
	
	
	echo ' <div class="form-group row" align=right dir=rtl data-spy="scroll">
			<label for="message-text" class="col-sm-6 col-form-label" dir=rtl>الإسم و اللقب :</label>
			    <div class="col-sm-6">
            <input type="text" class="form-control" id="nomprenom" name="nomprenom" value="" align=right dir=rtl required>
                </div>
            <label for="message-text" class="col-sm-7 col-form-label" dir=rtl>رقم بطاقة التعريف :</label>
			    <div class="col-sm-5">
            <input type="text" class="form-control"  name ="cin" value=""  align=right dir=rtl required>
          </div>
            <label for="message-text" class="col-sm-7 col-form-label" dir=rtl align=right >تاريخ الولادة :</label>
			<div class="col-sm-5">
			<input type="date" class="form-control" name="Dnaissance" value=""  align=right dir=rtl required>
           </div>
          
            <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">تاريخ الانتداب :</label>
			<div class="col-sm-5">
			 <input type="date" class="form-control"  name ="Drecrutement" value=""  align=right dir=rtl required>
          </div>
		  
		  
		  
            <label for="message-text" class="col-sm-4 col-form-label" dir=rtl>الرتبة :</label>
			<div class="col-sm-8">
			<select name="grade" class="form-control">'; ?>
             <?php $reqsn="select * from titres group by libellet";$ssn=mysql_query($reqsn);while($rsn=mysql_fetch_row($ssn)) {echo "<option value=".$rsn[0].">".$rsn[1]."</option>";} ?>
            <?php echo '</select>
			</div>


          
            <label for="message-text" class="col-sm-5 col-form-label" dir=rtl align=right >المصلحة :</label>
			<div class="col-sm-7">
			<select name="service" id="select6" class="form-control">'; ?>
                <?php $reqsn="select * from service where sb='5'";
				$ssn=mysql_query($reqsn);
				while($rsn=mysql_fetch_row($ssn)) {
				echo "<option value=".$rsn[0];
				if(@$r[11]==$rsn[0]) echo ' selected="selected" ';echo ">".$rsn[1]."</option>";
				$reqsn2="select * from service where iddirection='".$rsn[0]."'";
				$ssn2=mysql_query($reqsn2);while($rsn2=mysql_fetch_row($ssn2)) {
				echo "<option value=".$rsn2[0];
				if(@$r[11]==$rsn2[0]) echo ' selected="selected" ';echo ">---> ".$rsn2[1]."</option>";
				
				
								$reqsn3="select * from service where iddirection='".$rsn2[0]."'";
				$ssn3=mysql_query($reqsn3);while($rsn3=mysql_fetch_row($ssn3)) {
				echo "<option value=".$rsn3[0];
				if(@$r[11]==$rsn3[0]) echo ' selected="selected" ';echo "> &nbsp;&nbsp;&nbsp;&nbsp;------> ".$rsn3[1]."</option>";
				}
				
				
				
				
				
				
				}
				
				
				
				
				
				
				
				 } ?><option value="0">بدون مصلحة</option> 
            <?php echo '</select>
          </div>
		  
         
            <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">القسم :</label>
			<div class="col-sm-5">
			<select name="dep" id="select7" class="form-control">'; ?>
                <?php $reqsn="select * from dep";$ssn=mysql_query($reqsn);while($rsn=mysql_fetch_row($ssn)) {echo "<option value=".$rsn[0];if(@$r[3]==$rsn[0]) echo ' selected="selected" ';echo ">".$rsn[2]."</option>";} ?>
            <?php echo '</select>
          </div>
		  
		  
		  
            <label for="message-text" class="col-sm-7 col-form-label" dir=rtl>السلم :</label>
			<div class="col-sm-5">
            <input type="text" class="form-control"  name ="echelle" value=""  align=right dir=rtl>
          </div>
		  
		  
		  
            <label for="message-text" class="col-sm-7 col-form-label" dir=rtl align=right >الدرجة :</label>
			<div class="col-sm-5">
			<input type="text" class="form-control" name="echelon" value=""  align=right dir=rtl>
           </div>
		   
		   
          
            <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">الصفة :</label>
			<div class="col-sm-5">
			<select name="etat" id="select8"  class="form-control">
                <option value="0" >مترسم</option>
                <option value="1" >متربص</option>
                <option value="2" >متعاقد</option>
				<option value="3" >ملحق</option>
				<option value="4" >متقاعد</option>
            </select>
         </div>
		 
		 
		 
            <label for="message-text" class="col-sm-7 col-form-label" dir=rtl>السلك :</label>
			<div class="col-sm-5">
            <select name="fil" id="fil" class="form-control">
              <option value="A" >إداري</option>
			  <option value="T"  >تقني</option>
			  <option value="E"  >إستغلال</option>
            </select>
          </div>
		  
		  
            <label for="message-text" class="col-sm-7 col-form-label" dir=rtl align=right >باقي رصيد إجازات :</label>
			<div class="col-sm-5">
			<input type="text" class="form-control" name="restconge" value=""  align=right dir=rtl>
          </div> 

            <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">رصيد إجازات النية الحاليّة :</label>
			<div class="col-sm-5">
			<input type="text" class="form-control" name="soldeconge" value=""  align=right dir=rtl>
          </div>
		  
		  
            <label for="message-text" class="col-sm-7 col-form-label" dir=rtl align=right >يوم الراحة الأسبوعية :</label>
			<div class="col-sm-5">
			<select name="Jourrepos" dir="rtl" class="form-control">
              <option value="10">اداري سبت و احد</option>
              <option value="0" >احد</option>
              <option value="1">اثنين</option>
              <option value="2" >ثلاثاء</option>
              <option value="3" >اربعاء</option>
              <option value="4" >خميس</option>
              <option value="5" >جمعة</option>
              <option value="6" >سبت</option>
            </select>
           </div>
        
            <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">تسجيل الحضور  :</label>
			<div class="col-sm-5">
			<select name="pointage" id="pointage" dir="rtl" class="form-control">
              <option value="0" >غير معنيّ</option>
              <option value="1">معنيّ</option>
            </select>
         </div>
		 
            <label for="message-text" class="col-sm-7 col-form-label" dir=rtl align=right >رقم الضمان الإجتماعي :</label>
			<div class="col-sm-5">
			<input type="text" class="form-control" name="codesocial" value=""  align=right dir=rtl>
           </div>
          
            <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">رقم التأمين الجماعي :</label>
			<div class="col-sm-5">
		<input type="text" class="form-control" name="codeassurance" value=""  align=right dir=rtl>
			</div>
          </div>
		  <div class="modal-footer">
           <button type="submit" class="btn btn-primary">حفظ التحيينات</button>
         </div>'; 
}
}
else
{
	echo 'ERRROR';
}
?>
</body>
</html>
