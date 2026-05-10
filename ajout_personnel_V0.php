<html>
<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script type='text/JavaScript'>

function getXhr(){
var xhr = null;
if(window.XMLHttpRequest) xhr = new XMLHttpRequest();
else if(window.ActiveXObject){ 
try {
xhr = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
xhr = new ActiveXObject("Microsoft.XMLHTTP");
}
}
else { 
alert("Votre navigateur ne supporte pas les objetsXMLHTTPRequest...");
xhr = false;
}
return xhr
}

function go(){

var xhr = getXhr()

xhr.onreadystatechange = function(){

if(xhr.readyState == 4 && xhr.status == 200){
document.getElementById("lo").src=xhr.responseText;
if(+document.getElementById('recipientname').value=='') document.getElementById("lo").src='images/err.jpg';
}
}

xhr.open("GET","verifdispmecano.php?mecano="+document.getElementById('recipientname').value,true);
xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
xhr.send(null);
}
</script>
</head>

<?php
session_start();
require('connection.php');
?>




<body>
<?php

      $type=$_GET['type'];
	 	 
?>
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header" align=center>
	  
      <div class="modal-body">
	  
	 
	  
        <form method='POST' action='ajout_personnel.php?type=insert'>
		
		
		<div class="form-group row" align=right dir=rtl>
            <label for="recipient-name" class="col-sm-7 col-form-label" dir=rtl>الرقم الآلي :</label>
			    <div class="col-sm-5">
				<input type="text" class="form-control" name="recipientname" id="recipientname" value="" align=right dir=rtl onchange="go()">
                </div>
				
				<label for="message-text" class="col-sm-6 col-form-label" dir=rtl>الإسم و اللقب :</label>
			    <div class="col-sm-6">
            <input type="text" class="form-control" id="nomprenom" value="" align=right dir=rtl>
                </div>
		</div>
		
		 
			 
		    <div class="form-group row" align=right dir=rtl data-spy="scroll">
            <label for="message-text" class="col-sm-7 col-form-label" dir=rtl>رقم بطاقة التعريف :</label>
			    <div class="col-sm-5">
            <input type="text" class="form-control"  name ="cin" value=""  align=right dir=rtl>
          </div>
            <label for="message-text" class="col-sm-7 col-form-label" dir=rtl align=right >تاريخ الولادة :</label>
			<div class="col-sm-5">
			<input type="date" class="form-control" name="Dnaissance" value=""  align=right dir=rtl>
           </div>
          
            <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">تاريخ الانتداب :</label>
			<div class="col-sm-5">
			 <input type="date" class="form-control"  name ="Drecrutement" value=""  align=right dir=rtl>
          </div>
		  
		  
		  
            <label for="message-text" class="col-sm-4 col-form-label" dir=rtl>الرتبة :</label>
			<div class="col-sm-8">
			<select name="grade" class="form-control">
             <?php $reqsn="select * from titres group by libellet";$ssn=mysql_query($reqsn);while($rsn=mysql_fetch_row($ssn)) {echo "<option value=".$rsn[0].">".$rsn[1]."</option>";} ?>
            </select>
			</div>


          
            <label for="message-text" class="col-sm-5 col-form-label" dir=rtl align=right >المصلحة :</label>
			<div class="col-sm-7">
			<select name="service" id="select6" class="form-control">
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
            </select>
          </div>
		  
         
            <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">القسم :</label>
			<div class="col-sm-5">
			<select name="dep" id="select7" class="form-control">
                <?php $reqsn="select * from dep";$ssn=mysql_query($reqsn);while($rsn=mysql_fetch_row($ssn)) {echo "<option value=".$rsn[0];if(@$r[3]==$rsn[0]) echo ' selected="selected" ';echo ">".$rsn[2]."</option>";} ?>
            </select>
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
                <option value="0" <?php if(@$r[12]==0) echo ' selected="selected" ';  ?>>مترسم</option>
                <option value="1" <?php if(@$r[12]==1) echo ' selected="selected" ';  ?>>متربص</option>
                <option value="2" <?php if(@$r[12]==2) echo ' selected="selected" ';  ?>>متعاقد</option>
				<option value="3" <?php if(@$r[12]==3) echo ' selected="selected" ';  ?>>ملحق</option>
				<option value="4" <?php if(@$r[12]==4) echo ' selected="selected" ';  ?>>متقاعد</option>
            </select>
         </div>
		 
		 
		 
            <label for="message-text" class="col-sm-7 col-form-label" dir=rtl>السلك :</label>
			<div class="col-sm-5">
            <select name="fil" id="fil" class="form-control">
              <option value="A" <?php if(@$r[16]=="A") echo ' selected="selected" ';  ?> >إداري</option>
			  <option value="T"  <?php if(@$r[16]=="T") echo ' selected="selected" ';  ?>>تقني</option>
			  <option value="E"  <?php if(@$r[16]=="E") echo ' selected="selected" ';  ?>>إستغلال</option>
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
              <option value="10" <?php if(@$r[18]==10) echo ' selected="selected" ';  ?>>اداري سبت و احد</option>
              <option value="0" <?php if(@$r[18]==0) echo ' selected="selected" ';  ?>>احد</option>
              <option value="1" <?php if(@$r[18]==1) echo ' selected="selected" ';  ?>>اثنين</option>
              <option value="2" <?php if(@$r[18]==2) echo ' selected="selected" ';  ?>>ثلاثاء</option>
              <option value="3" <?php if(@$r[18]==3) echo ' selected="selected" ';  ?>>اربعاء</option>
              <option value="4" <?php if(@$r[18]==4) echo ' selected="selected" ';  ?>>خميس</option>
              <option value="5" <?php if(@$r[18]==5) echo ' selected="selected" ';  ?>>جمعة</option>
              <option value="6" <?php if(@$r[18]==6) echo ' selected="selected" ';  ?>>سبت</option>
            </select>
           </div>
        
            <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">تسجيل الحضور  :</label>
			<div class="col-sm-5">
			<select name="pointage" id="pointage" dir="rtl" class="form-control">
              <option value="0" >معنيّ</option>
              <option value="1">غير معنيّ</option>
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
         </div>
        </form>
      </div>
     </div> 
    </div>


</body>
</html>