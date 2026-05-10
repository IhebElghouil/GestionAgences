<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Document sans titre</title>

<?php
if(@$_POST['mecano']) $mecano=$_POST['mecano']; 
if(@$_GET['mecano']) $mecano=$_GET['mecano']; 

 $code=array();
  
  $code[11]="Mi.F.";
  $code[12]="Mi.R.";
  $code[13]="Mi.M.";
 $code[2]="Ex."; $code[3]="Cu."; $code[4]="Sy."; $code[5]="Ma.";$code[6]="At.";$code[7]="Rr.";$code[8]="RMat.";$code[9]="RSS.";$code[10]="San.";
  
 ?>
<script language="javascript">
$('document').ready(function(){
	updatestatus();
	scrollalert();
});
function updatestatus(){
	//Show number of loaded items
	var totalItems=$('#content p').length;
	$('#status').text('Loaded '+totalItems+' Items');
}
function scrollalert(){
	var scrolltop=$('#scrollbox').attr('scrollTop');
	var scrollheight=$('#scrollbox').attr('scrollHeight');
	var windowheight=$('#scrollbox').attr('clientHeight');
	var scrolloffset=20;
	if(scrolltop>=(scrollheight-(windowheight+scrolloffset)))
	{
		//fetch new items
		$('#status').text('Loading more items...');
		$.get('new-items.html', '', function(newitems){
			$('#content').append(newitems);
			updatestatus();
		});
	}
	setTimeout('scrollalert();', 1500);
}
</script>
<style>
#container{ 
	width:700px; 
	margin:0px auto; 
	padding:40px 0; 
}
#scrollbox{ 
	width:700px; 
	height:700px;  
	overflow:auto; overflow-x:hidden; 
}
#container > p{ 
	background:#eee; 
	color:#666; 
	font-family:Arial, sans-serif; font-size:0.75em; 
	padding:5px; margin:0; 
	text-align:right;
}
</style>
<script language="javascript">

function menu(){
//$("#tab2").hide();$("#tab3").hide();$("#tab4").hide();
if(document.getElementById('typcon').value==0) 
{
if ($("#tab2").is(":hidden")) {

if (!$("#tab3").is(":hidden")) $("#tab3").hide();
if (!$("#tab4").is(":hidden")) $("#tab4").hide();
if (!$("#tab12").is(":hidden")) $("#tab12").hide();

$("#tab2").slideDown("slow");


}

}
else
{
if(document.getElementById('typcon').value==1)  $("#tab4").slideDown("slow"); else $("#tab4").hide();
if(document.getElementById('typcon').value!=6 && document.getElementById('typcon').value!=5)  $("#tab12").slideDown("slow"); else $("#tab12").hide();
if ($("#tab3").is(":hidden")) {
if (!$("#tab2").is(":hidden")) $("#tab2").hide();
$("#tab3").slideDown("slow");


}

}



}










function  difdate(oo){ 
if(document.getElementById('typcon').value!=5) 
{

var dd1=document.getElementById("date1").value;
var dd2=document.getElementById("date2").value;
dd1=dd1.replace('-','/');
dd1=dd1.replace('-','/');
dd2=dd2.replace('-','/');
dd2=dd2.replace('-','/');


var d1 = new Date(dd1);
var d2 = new Date(dd2);


var da1=dd1.split('/');
var da2=dd2.split('/');
var dm=0;
var dmm=0;
var cc=0;
var cc2=0;

var mdeb;
var mfin;
for (var y=parseInt(da1[0],10);y<=parseInt(da2[0],10);y++){
if (y==parseInt(da1[0],10)){
if (parseInt(da1[0],10)==parseInt(da2[0],10)){
mdeb=parseInt(da1[1],10);
mfin=parseInt(da2[1],10);

}else {
mdeb=parseInt(da1[1],10);
mfin=12;
}




}else {
if (y!=parseInt(da2[0],10)){
mdeb=1;
mfin=12;
}else{
mdeb=1;
mfin=parseInt(da2[1],10);

}







}
for (var m=mdeb;m<=mfin;m++){
if(m!=parseInt(da2[1],10)){
if (m == 1 || m == 3 || m == 5 || m == 7 || m == 8 || m == 10 || m == 12) {
		dm = 31;
	} else if (m == 4 || m == 6 || m == 9 || m == 11) {
		dm = 30;
	} else {
		dm = (y % 4 == 0) ? 29 : 28;
	}

}else dm=parseInt(da2[2],10);

if(m==parseInt(da1[1],10)) dmm=parseInt(da1[2],10);else dmm=1;

for (var d=dmm;d<=dm;d++){

var dat = new Date(y+"/"+m+"/"+d);



if(document.getElementById('repos').value==10){
if(parseInt(dat.getDay(),10)==0 || parseInt(dat.getDay(),10)==6) {cc+=1;
}else {

$.ajax({
type: "GET",
url: "verifdate.php",
async: false,
data: { dat:y+"/"+m+"/"+d },
success: function(msg)
        {
           if(msg!='') {cc+=1;}
        }
});




}
}else{

if(parseInt(dat.getDay(),10)==parseInt(document.getElementById('repos').value,10)) cc+=1;else {


$.ajax({
type: "GET",
url: "verifdate.php",
async: false,
data: { dat:y+"/"+m+"/"+d },
success: function(msg)
        {
           if(msg!='') {cc+=1;}
        }
});



}


}




}

}}


if((parseInt(d2.getTime()-d1.getTime(),10)/(24*3600*1000))>=0) {



if(document.getElementById('typcon').value==0){


if(((parseInt(d2.getTime()-d1.getTime(),10)/(24*3600*1000))+1)-cc <= document.getElementById("nbrest").value){
document.getElementById("nbj").value=((parseInt(d2.getTime()-d1.getTime(),10)/(24*3600*1000))+1)-cc;
document.getElementById("nbj2").value=(document.getElementById("nbrest").value-document.getElementById("nbj").value);




}else {
alert('عدد ايام الراحة المرغوب فيها اكبر من عدد ايام الراحة المسموح بها  بـــ '+ ((((parseInt(d2.getTime()-d1.getTime(),10)/(24*3600*1000))+1)-cc)-(document.getElementById("nbrest").value)) +' يوم');
document.getElementById("nbj").value=((parseInt(d2.getTime()-d1.getTime(),10)/(24*3600*1000))+1)-cc;
document.getElementById("nbj2").value=(document.getElementById("nbrest").value-document.getElementById("nbj").value);

}

}else{

document.getElementById("nbj").value=((parseInt(d2.getTime()-d1.getTime(),10)/(24*3600*1000))+1)-cc;




} 



}
if((parseInt(d2.getTime()-d1.getTime(),10)/(24*3600*1000))<0) {
alert('انتباه يوم الخروج يجب ان يكون اصغر من يوم الرجوع');
oo.value='';

}











}





if(document.getElementById('typcon').value==5) 
{









var dd1=document.getElementById("date12").value;
var dd2=document.getElementById("date22").value;
dd1=dd1.replace('-','/');
dd1=dd1.replace('-','/');
dd2=dd2.replace('-','/');
dd2=dd2.replace('-','/');


var d1 = new Date(dd1);
var d2 = new Date(dd2);


var da1=dd1.split('/');
var da2=dd2.split('/');
var dm=0;
var dmm=0;
var cc=0;
for (var y=parseInt(da1[0],10);y<=parseInt(da2[0],10);y++){

for (var m=parseInt(da1[1],10);m<=parseInt(da2[1],10);m++){
if(m!=parseInt(da2[1],10)){
if (m == 1 || m == 3 || m == 5 || m == 7 || m == 8 || m == 10 || m == 12) {
		dm = 31;
	} else if (m == 4 || m == 6 || m == 9 || m == 11) {
		dm = 30;
	} else {
		dm = (y % 4 == 0) ? 29 : 28;
	}

}else dm=parseInt(da2[2],10);

if(m==parseInt(da1[1],10)) dmm=parseInt(da1[2],10);else dmm=1;



















for (var d=dmm;d<=dm;d++){

var dat = new Date(y+"/"+m+"/"+d);





$.ajax({
type: "GET",
url: "verifdate.php",
async: false,
data: { dat:y+"/"+m+"/"+d },
success: function(msg)
        {
           if(msg!='') {cc+=1;}
        }
});


}












}}


if((parseInt(d2.getTime()-d1.getTime(),10)/(24*3600*1000))>=0) {





document.getElementById("nbj4").value=((parseInt(d2.getTime()-d1.getTime(),10)/(24*3600*1000))+1)-cc;






}
if((parseInt(d2.getTime()-d1.getTime(),10)/(24*3600*1000))<0) {
alert('انتباه يوم الخروج يجب ان يكون اصغر من يوم الرجوع');
oo.value='';

}













}


}











function verif(mo){

if(document.getElementById('typcon').value==0) {






if (document.getElementById("date1").value=='' && document.getElementById("date2").value==''){
alert("يجب اختيار تاريخ على الاقل");

}else{


if (document.getElementById("nbj").value==0) {

alert("الرجاء التثبت في يوم الراحة");
}
else{
if ((document.getElementById("date1").value=='' && document.getElementById("date2").value!='') || (document.getElementById("date1").value!='' && document.getElementById("date2").value=='')){
if(confirm('لقد تم إخيار تاريخ واحد، هل تقصد ان مدة الراحة يوم واحد ؟')) {
if(document.getElementById("date1").value!='') {
document.getElementById("date2").value=document.getElementById("date1").value;
difdate(document.getElementById("date1"));

//document.getElementById("ff").submit();
												}
if(document.getElementById("date2").value!='') {
document.getElementById("date1").value=document.getElementById("date2").value;
difdate(document.getElementById("date2"));
//document.getElementById("ff").submit();
												}


																		}

	}
document.getElementById("ff").submit();
}}




}else{








if (document.getElementById("date12").value=='' && document.getElementById("date22").value==''){
alert("يجب اختيار تاريخ على الاقل");

}else{
if ((document.getElementById("date12").value=='' && document.getElementById("date22").value!='') || (document.getElementById("date12").value!='' && document.getElementById("date22").value=='')){
if(confirm('لقد تم إخيار تاريخ واحد، هل تقصد ان مدة الراحة يوم واحد ؟')) {
if(document.getElementById("date12").value!='') {
document.getElementById("date22").value=document.getElementById("date12").value;
difdate(document.getElementById("date12"));

//document.getElementById("ff").submit();
												}
if(document.getElementById("date22").value!='') {
document.getElementById("date12").value=document.getElementById("date22").value;
difdate(document.getElementById("date22"));
//document.getElementById("ff").submit();
												}


																		}

	}
document.getElementById("ff").submit();
}








}


}



</script>
<style type="text/css">
<!--

.ds_box {
	background-color: #FFF;
	border: 1px solid #000;
	position: absolute;
	z-index: 32767;
}

.ds_tbl {
	background-color: #FFF;
}

.ds_head {
	background-color: #0D446C;
	color: #FFF;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 13px;
	font-weight: bold;
	text-align: center;
	letter-spacing: 2px;
}

.ds_subhead {
	background-color: #CCC;
	color: #000;
	font-size: 9px;
	font-weight: bold;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	width: 22px;
}

.ds_cell {
	background-color: #EEE;
	color: #000;
	font-size: 13px;
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	padding: 5px;
	cursor: pointer;
}

.ds_cell:hover {
	background-color: #F3F3F3;
} /* This hover code won't work for IE */




.s {
	color: #FFFFFF;
	font-weight: bold;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	text-decoration:none;
}
.Style6 {font-size: 18px; font-weight: bold; text-decoration:none }
.Style7 {font-size: 24px; font-weight: bold; text-decoration: none; }
.Style8 {
	color: #FFFFFF;
	font-weight: bold;
	font-size: 24px;
}

-->
</style>
</head>

<body>
<br />
<form id="form1" name="form1" method="get" action="">
  <div align="center">
    <table width="215" border="4" cellspacing="0" bordercolor="#FFFFFF" class="sof" id="tab">
      <tr>
        <td width="42"><div align="right">
          <input name="mecano" type="text" id="mecano" size="7" maxlength="7" value="<?php echo @$mecano; ?>" />
        </div></td>
        <td width="157"><div align="right"><strong>رقم  آلي</strong></div></td>
      </tr>
    </table>
  </div>
  <p align="center">
    <input name="Submit" type="submit" class="Style6" value="بحث" />
    <input name="page" type="hidden" id="page" value="conge" />
  </p>
</form>

<p align="center"><?php

if(@$_POST['mecano']!="" || @$_GET['mecano']!=""){
if(@$_POST['formmod']){
if(@$_POST['nbj']!=@$_POST['nbjold']){

$rest=($_POST['nbrest'] + $_POST['nbjold']) - $_POST['nbj'];

$reqq="update nbconge set rest='".$rest."' where mecano='".$mecano."'";

$ree=mysql_query($reqq);
}
$req="update conge set datedebut='".$_POST['date1']."', datefin='".$_POST['date2']."', nbjours='".$_POST['nbj']."' where id='".$_POST['formmod']."'";
$re=mysql_query($req);
if($re) echo "<script language=javascript> alert(' لقد تم تعديل الراحة بنجاح '); </script>";


}

if(@$_POST['mod']){

  $req="select stuf.*, titres.libellet, dep.depar,nbconge.* from stuf,titres,dep,nbconge where nbconge.mecano=stuf.mecano and stuf.mecano='".$mecano."' and titres.id=stuf.titre and dep.id=stuf.dep";
  

  $res=mysql_query($req);
  $r=mysql_fetch_row($res);
  
  

  $ann=mysql_query("select * from annee");
  $anne=mysql_fetch_row($ann);
  $req2="select * from conge where id='".$_POST['mod']."' and annee='".$anne[0]."'";

  $res2=mysql_query($req2);
  $r2=mysql_fetch_row($res2);
  

?>
  <br />
<span class="Style7">تعديل</span></p>

<form id="ff" name="ff" method="post" action="">
  <div align="center">
    <table width="521" border="4" cellspacing="0" bordercolor="#FFFFFF" id="tab">
      <tr>
        <td colspan="4"><div align="right" class="sof"><?php echo $r[0]; ?></div></td>
        <td width="186"><div align="right" class="sof"> : رقم  آلي</div></td>
      </tr>
      <tr>
        <td colspan="4"><div align="right" class="sof"><?php echo $r[8]."/".$r[7]; ?></div></td>
        <td><div align="right" class="sof">: <span dir="rtl">الرقم  المهني</span></div></td>
      </tr>
      <tr>
        <td colspan="4"><div align="right" class="sof"><?php echo $r[1]; ?></div></td>
        <td><div align="right" class="sof">: <span dir="rtl">الاسم و اللقب </span></div></td>
      </tr>
      <tr>
	  <!--     -->
	  <!--     -->
        <td colspan="4"><div align="right" class="sof"><?php echo $r[20]; ?></div></td>
        <td><div align="right" class="sof">:<span dir="rtl"> الصفة </span></div></td>
      </tr>
      <tr>
	  <!--     -->
	  <!--     -->
        <td colspan="4"><div align="right" class="sof"><?php echo $r[21]; ?></div></td>
        <td><div align="right" class="sof">: <span dir="rtl">القسم</span></div></td>
      </tr>
      <tr>
	  <!--     -->
	  <!--     -->
        <td colspan="4"><div align="right" class="sof"><?php echo $r[23]; ?></div></td>
        <td><div align="right" class="sof">: <span dir="rtl"> عدد ايام الراحة
          <?php $annee=mysql_fetch_row(mysql_query("select * from annee")); echo $annee[0]-1;   ?>
        </span></div></td>
      </tr>
      <tr>
	  <!--     -->
	  <!--     -->
        <td colspan="4"><div align="right" class="sof"><?php echo $r[24]; ?></div></td>
        <td><div align="right" class="sof">: <span dir="rtl"> عدد ايام الراحة </span> <?php echo $annee[0];   ?> </div></td>
      </tr>
      <tr>
	  <!--     -->
	  <!--     -->
        <td colspan="4"><div align="right" class="sof"><?php echo (($r[23]+$r[24])-$r[25]); ?></div></td>
        <td><div align="right" class="sof">: تمتع بها </div></td>
      </tr>
      <tr>
	  <!--     -->
	  <!--     -->
        <td colspan="4"><div align="right" class="sof">
          <?php if($r[12]!='2') echo $r[25];else echo $r[25]-(date("n",strtotime($r[5]))-date("n")); ?>
          <input type="hidden" id='nbrest' name='nbrest' value='<?php if($r[12]!='2') echo $r[25];else echo $r[25]-(date("n",strtotime($r[5]))-date("n")); ?>' />
        </div></td>
        <td><div align="right" class="sof">: <span dir="rtl">الرصيدد الحالي</span></div></td>
      </tr>
      <tr>
        <td colspan="5"><div align="right" class="sof">
          <div align="center"> C <?php echo $r2[0]; ?> : <span dir="rtl">راحة عدد</span></div>
        </div></td>
      </tr>
      <tr>
        <td width="128"><div align="right" class="sof">
          <div align="center">
            <input name="date2" type="text" id="date2" size="10" maxlength="10" onclick="ds_sh(this);" readonly="readonly" value="<?php echo $r2[3]; ?>" />
          </div>
        </div></td>
        <td width="25"><div align="right" class="sof">
          <div align="center">الى</div>
        </div></td>
        <td width="131"><div align="right" class="sof">
          <div align="center">
            <input name="date1" type="text" id="date1" size="10" maxlength="10" onclick="ds_sh(this);" readonly="readonly" value="<?php echo $r2[2]; ?>"/>
		  </div>
        </div></td>
        <td width="21"><div align="right" class="sof">
          <div align="center">من</div>
        </div></td>
        <td><div align="right" class="sof">: <span dir="rtl">اضافة راحة</span></div></td>
      </tr>
      <tr>
        <td colspan="4"><div align="right" class="sof"><label>
          <div align="center">
            <select name="repos" id="repos" onclick="difdate(document.getElementById('date2'))">
                            <option value="10" <?php if($r[18]==10) echo ' selected="selected" ';  ?>>اداري سبت و احد</option>
              <option value="0" <?php if($r[18]==0) echo ' selected="selected" ';  ?>>احد</option>
              <option value="1" <?php if($r[18]==1) echo ' selected="selected" ';  ?>>اثنين</option>
              <option value="2" <?php if($r[18]==2) echo ' selected="selected" ';  ?>>ثلاثاء</option>
              <option value="3" <?php if($r[18]==3) echo ' selected="selected" ';  ?>>اربعاء</option>
              <option value="4" <?php if($r[18]==4) echo ' selected="selected" ';  ?>>خميس</option>
              <option value="5" <?php if($r[18]==5) echo ' selected="selected" ';  ?>>جمعة</option>
              <option value="6" <?php if($r[18]==6) echo ' selected="selected" ';  ?>>سبت</option>
            </select>
			   <script language="javascript">
		 
		  //difdate(null);
		  //alert(document.getElementById('date2'));
		  </script>
               <input name="typcon" type="hidden" id="typcon" value="0" />
          </div>
          </label>
        </div></td>
        <td><div align="right" class="sof">: <span dir="rtl">الراحة الاسبوعية</span></div></td>
      </tr>
      <tr>
        <td colspan="2"><div align="right" class="sof">
          <div align="right">
            <input name="nbj2" type="text" id="nbj2" size="3" maxlength="3" readonly="readonly"/>
            عدد الايام المتبقية </div>
        </div></td>
        <td colspan="2"><div align="right">
		<?php $ree=mysql_query("select * from conge where id='".$_POST['mod']."'");
		$rnbj=mysql_fetch_row($ree);
		
		?>
          <input name="nbjold" type="hidden" id="nbjold" value="<?php echo $rnbj[4]; ?>" />
          <input name="nbj" type="text" id="nbj" size="10" maxlength="10" readonly="readonly"/>
        </div></td>
        <td><div align="right" class="sof">: <span dir="rtl">ايام الراحة</span></div></td>
      </tr>
    </table>
  </div>
  <p align="center"><span class="sof">
    <input type="hidden" id='formmod' name='formmod' value='<?php echo $r2[0];?>' />
    <input type="hidden" id='mecano' name='mecano' value='<?php echo @$mecano; ?>' />
    </span>
      <input name="ok2" type="button" class="Style6" id="ok2" value="تعديل" onclick="verif();" />
  </p>
</form>
<p>
  <?php




}





















































if(@$_POST['formmod2']){

$req="update autreconge set datedebut='".$_POST['date12']."', datefin='".$_POST['date22']."', nbj='".$_POST['nbj4']."',  commentaire ='".$_POST['com']."',  type2 ='".@$_POST['typemiss']."' where id='".$_POST['formmod2']."'";
$re=mysql_query($req);
if($re) echo "<script language=javascript> alert(' لقد تم تعديل الراحة بنجاح '); </script>";


}

if(@$_POST['mod2']){

  $req="select stuf.*, titres.libellet, dep.depar,nbconge.* from stuf,titres,dep,nbconge where nbconge.mecano=stuf.mecano and stuf.mecano='".$mecano."' and titres.id=stuf.titre and dep.id=stuf.dep";
  

  $res=mysql_query($req);
  $r=mysql_fetch_row($res);
  
  

  $ann=mysql_query("select * from annee");
  $anne=mysql_fetch_row($ann);
 // $req2="select * from autreconge where id='".$_POST['mod2']."' and anne='".$anne[0]."'";
$req2="select * from autreconge where id='".$_POST['mod2']."' ";
  $res2=mysql_query($req2);
  $r2=mysql_fetch_row($res2);
  

?>
  <br />
<span class="Style7">تعديل</span></p>

<form id="ff" name="ff" method="post" action="">
  <div align="center">
    <table width="521" border="4" cellspacing="0" bordercolor="#FFFFFF" id="tab">
      <tr>
        <td colspan="6"><div align="right" class="sof"><?php echo $r[0]; ?></div></td>
        <td width="111"><div align="right" class="sof"> : رقم  آلي</div></td>
      </tr>
      <tr>
        <td colspan="6"><div align="right" class="sof"><?php echo $r[8]."/".$r[7]; ?></div></td>
        <td><div align="right" class="sof">: <span dir="rtl">الرقم  المهني</span></div></td>
      </tr>
      <tr>
        <td colspan="6"><div align="right" class="sof"><?php echo $r[1]; ?></div></td>
        <td><div align="right" class="sof">: <span dir="rtl">الاسم و اللقب </span></div></td>
      </tr>
      <tr>
	  <!--     -->
	  <!--     -->
        <td colspan="6"><div align="right" class="sof"><?php echo $r[20]; ?></div></td>
        <td><div align="right" class="sof">:<span dir="rtl"> الصفة </span></div></td>
      </tr>
      <tr>
	  <!--     -->
	  <!--     -->
        <td colspan="6"><div align="right" class="sof"><?php echo $r[21]; ?></div></td>
        <td><div align="right" class="sof">: <span dir="rtl">القسم</span></div></td>
      </tr>
      <tr>
        <td colspan="7"><div align="right" class="sof">
          <div align="center"> <?php 
		   $cc=$r2[2];
if($r2[2]==1) $cc=$r2[2].$r2[6];
		
		  
		  echo $code[$cc].$_POST['mod2']; ?> : <span dir="rtl">راحة عدد</span>
            <input name="typcon" type="hidden" id="typcon" value="<?php echo $r2[2];?>" />
          </div>
        </div></td>
      </tr>
	  
	  
	  
	  
	  
	  <?php if($r2[2]==1){?>
	         <tr>
         <td width="138"><div align="right" class="sof">
             
              <div align="right">
             إجتماع &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="typemiss" value="2"  <?php if ($r2[6]==2) echo 'checked="checked"';?>/>
              </div>
         </div>
             <div align="right" class="sof">               </div>
          <div align="right" class="sof"></div></td>
         <td colspan="3"><div align="right" class="sof">
           
             <div align="right">
               تكوين &nbsp;&nbsp;&nbsp;&nbsp; <input name="typemiss" type="radio" value="1" <?php if ($r2[6]==1) echo 'checked="checked"';?> />
             </div>
         </div></td>
         <td colspan="2" ><div align="right" class="sof">
           <div align="right">مهمة&nbsp;&nbsp;&nbsp;&nbsp;
               <input name="typemiss" type="radio" value="3" <?php if ($r2[6]==3) echo 'checked="checked"';?> />
           </div>
         </div></td>
         <td><div align="right" class="sof"><span dir="rtl">نوع المهمة</span></div></td>
       </tr>
	  
	  <?php }?>
	  
	  
	  
	  
     
      <tr>
        <td colspan="6"><div align="right" class="sof">
            <div align="center">
              <textarea name="com" cols="45" rows="3" id="com"><?php echo $r2[7]; ?></textarea>
            </div>
        </div></td>
        <td><div align="right" class="sof">: <span dir="rtl">ملاحظة</span></div></td>
      </tr>
      <tr>
        <td colspan="6"><div align="right">
            <input name="nbj4" type="text" id="nbj4" size="10" maxlength="10" readonly="readonly"/>
        </div></td>
        <td><div align="right" class="sof">: <span dir="rtl">ايام الراحة</span></div></td>
      </tr>
    </table>
  </div>
  <p align="center"><span class="sof">
    <input type="hidden" id='formmod2' name='formmod2' value='<?php echo $r2[0];?>' />
    <input type="hidden" id='mecano' name='mecano' value='<?php echo @$mecano; ?>' />
    </span>
      <input name="ok2" type="button" class="Style6" id="ok2" value="تعديل" onclick="verif();" />
  </p>
</form>
<p>
  <?php




}



































if(@$_POST['supp']){
$req1="select rest from nbconge where mecano='".$mecano."'";
$re1=mysql_query($req1);
$r1=mysql_fetch_row($re1);
$req3="select nbjours from conge where id='".$_POST['supp']."'";
$re3=mysql_query($req3);
$r3=mysql_fetch_row($re3);
$re2=mysql_query("update nbconge set rest='".($r1[0]+$r3[0])."' where mecano='".$mecano."'");

$req=mysql_query("delete from conge where id='".$_POST['supp']."'");
if($req && $re2) echo "<script laguage=javascript>alert('تم الحذف بنجاح');</script>";


}




if(@$_POST['supp2']){


$req=mysql_query("delete from autreconge where id='".$_POST['supp2']."'");
if($req) echo "<script laguage=javascript>alert('تم الحذف بنجاح');</script>";


}





if(@$_POST['formajou']){


if($_POST['typcon']==0){
$res=mysql_query("select * from annee");
$r=mysql_fetch_row($res);

$ress1=mysql_query("select * from conge where datedebut between '".$_POST['date1']."' and '".$_POST['date2']."' and mecano='".$mecano."'");
$ress2=mysql_query("select * from conge where datefin between '".$_POST['date1']."' and '".$_POST['date2']."' and mecano='".$mecano."'");


$ressAutreConge1=mysql_query("select * from autreconge where datedebut between '".$_POST['date1']."' and '".$_POST['date2']."' and mecano='".$mecano."'");
$ressAutreConge2=mysql_query("select * from autreconge where datefin between '".$_POST['date1']."' and '".$_POST['date2']."' and mecano='".$mecano."'");

/*Vérification si date existe déjà dans la table conge ou autreconge*/
/*$ress1=mysql_query("select * from autreconge where datedebut < '".$_POST['date2']."' and datefin >'".$_POST['date1']."' and mecano='".$mecano."'");
$ress2=mysql_query("select * from conge where datedebut < '".$_POST['date2']."' and datefin >'".$_POST['date1']."' and mecano='".$mecano."'"); */

if(mysql_fetch_row($ress1) || mysql_fetch_row($ress2)|| mysql_fetch_row($ressAutreConge1)|| mysql_fetch_row($ressAutreConge2)){echo "<script language=javascript>alert('الراحة المطلوبة قد تكون مسجلة سابقا او جزء تابع لراحة مسجلة');</script>";
}else{

$req=mysql_query("insert into conge values(Null,'".$mecano."','".$_POST['date1']."','".$_POST['date2']."','".$_POST['nbj']."','".$r[0]."')");
$id=mysql_insert_id();


 $req4=mysql_query("select rest from nbconge where mecano='".$mecano."'");
 $r4=@mysql_fetch_row($req4);
 $req2="update nbconge set rest='".($r4[0]-$_POST['nbj'])."' where mecano='".$mecano."'";
 $re2=mysql_query($req2);
 
 if($req && $re2) {
echo "<script language=javascript>alert('تم اضافة الراحة بنجاح تحت رقم C".$id."'); open('index.php?page=conge&mecano=".$mecano."','_top');</script>";


}else "<script language=javascript>alert('الرجاء اعادة البعث، ان تكرر نفس المشكل الرجاء الاتصال بمصلحة الاعلامية');</script>";
}
}






if(@$_POST['typcon']!=0){








$res=mysql_query("select * from annee");
$r=mysql_fetch_row($res);

$ress1=mysql_query("select * from autreconge where datedebut between '".$_POST['date12']."' and '".$_POST['date22']."' and mecano='".$mecano."'");
$ress2=mysql_query("select * from autreconge where datefin between '".$_POST['date12']."' and '".$_POST['date22']."' and mecano='".$mecano."'");

$ress3=mysql_query("select * from conge where datedebut between '".$_POST['date12']."' and '".$_POST['date22']."' and mecano='".$mecano."'");
$ress4=mysql_query("select * from conge where datefin between '".$_POST['date12']."' and '".$_POST['date22']."' and mecano='".$mecano."'");



/*$ress1=mysql_query("select * from autreconge where datedebut < '".$_POST['date22']."' and datefin >'".$_POST['date12']."' and mecano='".$mecano."'");
$ress2=mysql_query("select * from conge where datedebut < '".$_POST['date22']."' and datefin >'".$_POST['date12']."' and mecano='".$mecano."'"); */



//echo "select * from autreconge where datedebut between '".$_POST['date12']."' and '".$_POST['date22']."' and mecano='".$mecano."'";
if(mysql_fetch_row($ress1) || mysql_fetch_row($ress2) || mysql_fetch_row($ress3) || mysql_fetch_row($ress4)){echo "<script language=javascript>alert('الراحة المطلوبة قد تكون مسجلة سابقا او جزء تابع لراحة مسجلة');</script>";
}else{
$typ=0;
if(@$_POST['typcon']==1) $typ=$_POST['typemiss'];

$typeconge=$_POST['typcon'];

//si on a fait une insertion d'un accident de travail
if ($_POST['typcon']==6) {
	//on cherche si la date debut maximale dans la liste des AT relatif à l'agent
$resmaxdate=mysql_query("select MAX(datedebut) from autreconge where mecano='".$mecano."' and type=6");
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
			}			
}
else
{
	
$req=mysql_query("insert into autreconge values(Null,'".$mecano."','".$_POST['typcon']."','".$_POST['date12']."','".$_POST['date22']."','".$_POST['nbj']."','".$typ."','".$_POST['com']."','".$r[0]."',1)");
$id=mysql_insert_id();
}


 
 if($req) {
 $cc=$_POST['typcon'];
 
if($_POST['typcon']==1) $cc=$_POST['typcon'].$_POST['typemiss'];

echo "<script language=javascript>alert('تم اضافة الراحة بنجاح تحت رقم C".$code[$cc].$id."'); open('index.php?page=conge&mecano=".$mecano."','_top');</script>";

}else "<script language=javascript>alert('الرجاء اعادة البعث، ان تكرر نفس المشكل الرجاء الاتصال بمصلحة الاعلامية');</script>";
}











}















}




  $req="select stuf.*, titres.libellet, dep.depar,nbconge.* from stuf,titres,dep,nbconge where nbconge.mecano=stuf.mecano and stuf.mecano='".$mecano."' and titres.id=stuf.titre and dep.id=stuf.dep";

  $res=mysql_query($req);
  if($r=mysql_fetch_row($res)){
  
  




?>
</p>
<center>
  
  
  <?php
  if(!@$_POST['mod'] && !@$_POST['mod2']){ 
  ?>
</center>
<form id="ff" name="ff" method="post" action="">
  <div align="center">
<table width="521" border="4" cellspacing="0" bordercolor="#FFFFFF" id="tab">
      <tr>
        <td width="305"><div align="right" class="sof"><?php echo $r[0]; ?></div></td>
        <td width="186"><div align="right" class="sof">
          : رقم  آلي</div></td>
      </tr>
      <tr>
        <td><div align="right" class="sof"><?php echo $r[8]."/".$r[7]; ?></div></td>
        <td><div align="right" class="sof">: <span dir="rtl">الرقم  المهني</span></div></td>
      </tr>
      <tr>
        <td><div align="right" class="sof"><?php echo $r[1]; ?></div></td>
        <td><div align="right" class="sof">: <span dir="rtl">الاسم و اللقب </span></div></td>
      </tr>
      <tr>
	  <!--     -->
	  <!--     -->
        <td><div align="right" class="sof"><?php echo $r[20]; ?></div></td>
        <td><div align="right" class="sof">:<span dir="rtl">  الصفة </span></div></td>
      </tr>
      <tr>
	  <!--     -->
	  <!--     -->
        <td><div align="right" class="sof"><?php echo $r[21]; ?></div></td>
        <td><div align="right" class="sof">:  <span dir="rtl">القسم</span></div></td>
      </tr>
	  <tr>
        <td><div align="right" class="sof"><?php echo $r[5]; ?></div></td>
        <td><div align="right" class="sof">: تاريخ الإنتداب  </div></td>
      </tr>
      <tr>
	  <!--     -->
	  <!--     -->
        <td><div align="right" class="sof"><?php echo $r[23]; ?></div></td>
        <td><div align="right" class="sof">: <span dir="rtl"> عدد ايام الراحة 
            <?php $annee=mysql_fetch_row(mysql_query("select * from annee")); echo $annee[0]-1;   ?> 
        </span></div></td>
      </tr>
      <tr>
	  <!--     -->
	  <!--     -->
        <td><div align="right" class="sof"><?php echo $r[24]; ?> </div></td>
        <td><div align="right" class="sof">: <span dir="rtl"> عدد ايام الراحة </span>
            <?php echo $annee[0];   ?>
        </div></td>
      </tr>
      <tr>
	  <!--     -->
	  <!--     -->
        <td><div align="right" class="sof"><?php echo (($r[23]+$r[24])-$r[25]); ?></div></td>
        <td><div align="right" class="sof">: تمتع بها  </div></td>
      </tr>
      <tr>
	  <!--     -->
	  <!--     -->
        <td><div align="right" class="sof"><?php if($r[12]!='2') echo $r[25];else echo $r[25]-(date("n",strtotime($r[5]))-date("n")); ?>
            <input type="hidden" id='nbrest' name='nbrest' value='<?php if($r[12]!='2') echo $r[25];else echo $r[25]-(date("n",strtotime($r[5]))-date("n")); ?>' />
        </div></td>
        <td><div align="right" class="sof">: <span dir="rtl">الرصيدد الحالي</span></div></td>
      </tr>
    </table>
  <table width="521" border="4" cellspacing="0" bordercolor="#FFFFFF" id="tab">
    <tr>
      <td><div align="right" class="sof">
          <div align="center">
            <select name="typcon" id="typcon" onchange="menu()">
              <option value="0" selected="selected">راحة عادية</option>
              <option value="1">القيام بمهمة</option>
              <option value="2">راحة استثنائية</option>
              <option value="3">رخصة ثقافية</option>
              <option value="4">رخصة نقابية</option>
			  <option value="5">رخصة مرضية</option>
			  <option value="6">حادث شغل</option>
              <option value="7">الراحة التعوضية</option>
			  <option value="8">عطلة أمومة</option>
			  <option value="9">عطلة بدون أجر</option>
			  <option value="10">إيقاف عن العمل</option>
			  <option value="11">عطلة ولادة</option>
            </select>
          </div>
      </div>        <div align="right" class="sof">
            <div align="center"></div>
        </div>      <div align="right" class="sof"></div></td>
      <td><div align="right" class="sof"><span dir="rtl">نوع الراحة</span></div></td>
    </tr>
  </table>
  
  <div id="tab2">
  
  
  
  
  
  <table width="521" border="4" cellspacing="0" bordercolor="#FFFFFF" id="tab">
    <tr>
      <td width="128"><div align="right" class="sof">
          <div align="center">
            <input name="date2" type="text" id="date2" size="10" maxlength="10" onclick="ds_sh(this);" readonly="readonly" />
          </div>
      </div></td>
      <td width="25"><div align="right" class="sof">
          <div align="center">الى</div>
      </div></td>
      <td width="131"><div align="right" class="sof">
          <div align="center">
            <input name="date1" type="text" id="date1" size="10" maxlength="10" onclick="ds_sh(this);" readonly="readonly"/>
          </div>
      </div></td>
      <td width="21"><div align="right" class="sof">
          <div align="center">من</div>
      </div></td>
      <td><div align="right" class="sof">: <span dir="rtl">اضافة راحة</span></div></td>
    </tr>
    <tr>
      <td colspan="4"><div align="right" class="sof">
          <label>
          <div align="center">
            <select name="repos" id="repos" onclick="difdate(document.getElementById('date2'))">
              <option value="10" <?php if($r[18]==10) echo ' selected="selected" ';  ?>>اداري سبت و احد</option>
              <option value="0" <?php if($r[18]==0) echo ' selected="selected" ';  ?>>احد</option>
              <option value="1" <?php if($r[18]==1) echo ' selected="selected" ';  ?>>اثنين</option>
              <option value="2" <?php if($r[18]==2) echo ' selected="selected" ';  ?>>ثلاثاء</option>
              <option value="3" <?php if($r[18]==3) echo ' selected="selected" ';  ?>>اربعاء</option>
              <option value="4" <?php if($r[18]==4) echo ' selected="selected" ';  ?>>خميس</option>
              <option value="5" <?php if($r[18]==5) echo ' selected="selected" ';  ?>>جمعة</option>
              <option value="6" <?php if($r[18]==6) echo ' selected="selected" ';  ?>>سبت</option>
            </select>
          </div>
        </label>
      </div></td>
      <td><div align="right" class="sof">: <span dir="rtl">الراحة الاسبوعية</span></div></td>
    </tr>
    <tr>
      <td colspan="2"><div align="right" class="sof">
          <div align="right">
            <input name="nbj2" type="text" id="nbj2" size="3" maxlength="3" readonly="readonly"/>
            عدد الايام المتبقية </div>
      </div></td>
      <td colspan="2"><div align="right">
          <input name="nbj" type="text" id="nbj" size="10" maxlength="10" readonly="readonly"/>
      </div></td>
      <td><div align="right" class="sof">: <span dir="rtl">ايام الراحة</span></div></td>
    </tr>
  </table>
  
  
  
  
  
  
  
  </div>
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  <div id="tab12">
    <table width="521" border="4" cellspacing="0" bordercolor="#FFFFFF" id="tab">
      <tr>
        <td colspan="3"><div align="right" class="sof">
            <label> </label>
          <div align="center">
              <select name="select" id="select" onclick="difdate(document.getElementById('date2'))">
                  <option value="10" <?php if($r[18]==10) echo ' selected="selected" ';  ?>>اداري سبت و احد</option>
              <option value="0" <?php if($r[18]==0) echo ' selected="selected" ';  ?>>احد</option>
              <option value="1" <?php if($r[18]==1) echo ' selected="selected" ';  ?>>اثنين</option>
              <option value="2" <?php if($r[18]==2) echo ' selected="selected" ';  ?>>ثلاثاء</option>
              <option value="3" <?php if($r[18]==3) echo ' selected="selected" ';  ?>>اربعاء</option>
              <option value="4" <?php if($r[18]==4) echo ' selected="selected" ';  ?>>خميس</option>
              <option value="5" <?php if($r[18]==5) echo ' selected="selected" ';  ?>>جمعة</option>
              <option value="6" <?php if($r[18]==6) echo ' selected="selected" ';  ?>>سبت</option>
              </select>
            </div>
        </div></td>
        <td><div align="right" class="sof">: <span dir="rtl">الراحة الاسبوعية</span></div></td>
      </tr>
  
    </table>
  </div>
  
   <div id="tab4">
     <table width="521" border="4" cellspacing="0" bordercolor="#FFFFFF" id="tab">
       <tr>
         <td width="135"><div align="right" class="sof">
             
              <div align="right">
             إجتماع &nbsp;&nbsp;&nbsp;&nbsp; <input type="radio" name="typemiss" value="2" />
              </div>
         </div>
             <div align="right" class="sof">               </div>
          <div align="right" class="sof"></div></td>
         <td width="101"><div align="right" class="sof">
           
             <div align="right">
               تكوين &nbsp;&nbsp;&nbsp;&nbsp; <input name="typemiss" type="radio" value="1" />
             </div>
         </div></td>
         <td width="78"><div align="right" class="sof">
           <div align="right">مهمة&nbsp;&nbsp;&nbsp;&nbsp;
               <input name="typemiss" type="radio" value="3" checked="CHECKED" />
           </div>
         </div></td>
         <td width="183"><div align="right" class="sof"><span dir="rtl">نوع المهمة</span></div></td>
       </tr>
     </table>
   </div>
   
   
   
   
   
   
   

   
   
   
   
  <div id="tab3">
  
  
  
  
  
  <table width="521" border="4" cellspacing="0" bordercolor="#FFFFFF" id="tab">
    <tr>
      <td width="128"><div align="right" class="sof">
          <div align="center">
            <input name="date22" type="text" id="date22" size="10" maxlength="10" onclick="ds_sh(this);" readonly="readonly" />
          </div>
      </div></td>
      <td width="25"><div align="right" class="sof">
          <div align="center">الى</div>
      </div></td>
      <td width="131"><div align="right" class="sof">
          <div align="center">
            <input name="date12" type="text" id="date12" size="10" maxlength="10" onclick="ds_sh(this);" readonly="readonly"/>
          </div>
      </div></td>
      <td width="21"><div align="right" class="sof">
          <div align="center">من</div>
      </div></td>
      <td><div align="right" class="sof">: <span dir="rtl">اضافة راحة</span></div></td>
    </tr>
    <tr>
      <td colspan="4"><div align="right" class="sof">
        <div align="center">
          <textarea name="com" cols="45" rows="3" id="com"></textarea>
        </div>
      </div></td>
      <td><div align="right" class="sof">: <span dir="rtl">ملاحظة</span></div></td>
    </tr>
    <tr>
      <td colspan="4"><div align="right">
        <input name="nbj4" type="text" id="nbj4" size="10" maxlength="10" readonly="readonly"/>
      </div></td>
      <td><div align="right" class="sof">: <span dir="rtl">ايام الراحة</span></div></td>
    </tr>
  </table>
  
  
  
  
  
  
  
  </div>
  
  
  
  
  
  
  
</div>
  <p align="center"><span class="sof">
    <input type="hidden" id='formajou' name='formajou' value='1' />
    <input type="hidden" id='mecano' name='mecano' value='<?php echo @$mecano; ?>' />
  </span>
    <input name="ok" type="button" class="Style6" id="ok" value="اضافة" onclick="verif();" />
  </p>
</form>
<?php } ?>


<table class="ds_box" cellpadding="0" cellspacing="0" id="ds_conclass" style="display: none;">
<tr><td id="ds_calclass">
</td></tr>
</table>



<script type="text/javascript">
// <!-- <![CDATA[

// Project: Dynamic Date Selector (DtTvB) - 2006-03-16
// Script featured on JavaScript Kit- http://www.javascriptkit.com
// Code begin...
// Set the initial date.
var ds_i_date = new Date();
ds_c_month = ds_i_date.getMonth() + 1;
ds_c_year = ds_i_date.getFullYear();

// Get Element By Id
function ds_getel(id) {
	return document.getElementById(id);
}

// Get the left and the top of the element.
function ds_getleft(el) {


var tmp = 0;

	if (el.offsetParent) {
do {
			tmp += el.offsetLeft;
			
} while (el = el.offsetParent);
}
	
	

		
	
	return tmp;
}
function ds_gettop(el) {
var tmp = 0;

	if (el.offsetParent) {
do {
			tmp += el.offsetTop;
			
} while (el = el.offsetParent);
}
	
	
		
		
	
	return tmp;
}

// Output Element
var ds_oe = ds_getel('ds_calclass');
// Container
var ds_ce = ds_getel('ds_conclass');

// Output Buffering
var ds_ob = ''; 
function ds_ob_clean() {
	ds_ob = '';
}
function ds_ob_flush() {
	ds_oe.innerHTML = ds_ob;
	ds_ob_clean();
}
function ds_echo(t) {
	ds_ob += t;
}

var ds_element; // Text Element...

var ds_monthnames = [
'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
]; // You can translate it for your language.

var ds_daynames = [
'Dim', 'Lun', 'Mar', 'Me', 'Jeu', 'Ven', 'Sam'
]; // You can translate it for your language.

// Calendar template
function ds_template_main_above(t) {
	return '<table cellpadding="3" cellspacing="1" class="ds_tbl">'
	     + '<tr>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_py();">&lt;&lt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_pm();">&lt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_hi();" colspan="3">[Fermer]</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_nm();">&gt;</td>'
		 + '<td class="ds_head" style="cursor: pointer" onclick="ds_ny();">&gt;&gt;</td>'
		 + '</tr>'
	     + '<tr>'
		 + '<td colspan="7" class="ds_head">' + t + '</td>'
		 + '</tr>'
		 + '<tr>';
}

function ds_template_day_row(t) {
	return '<td class="ds_subhead">' + t + '</td>';
	// Define width in CSS, XHTML 1.0 Strict doesn't have width property for it.
}

function ds_template_new_week() {
	return '</tr><tr>';
}

function ds_template_blank_cell(colspan) {
	return '<td colspan="' + colspan + '"></td>'
}

function ds_template_day(d, m, y) {
	return '<td class="ds_cell" onclick="ds_onclick(' + d + ',' + m + ',' + y + ')">' + d + '</td>';
	// Define width the day row.
}

function ds_template_main_below() {
	return '</tr>'
	     + '</table>';
}

// This one draws calendar...
function ds_draw_calendar(m, y) {
	// First clean the output buffer.
	ds_ob_clean();
	// Here we go, do the header
	ds_echo (ds_template_main_above(ds_monthnames[m - 1] + ' ' + y));
	for (i = 0; i < 7; i ++) {
		ds_echo (ds_template_day_row(ds_daynames[i]));
	}
	// Make a date object.
	var ds_dc_date = new Date();
	ds_dc_date.setMonth(m - 1);
	ds_dc_date.setFullYear(y);
	ds_dc_date.setDate(1);
	if (m == 1 || m == 3 || m == 5 || m == 7 || m == 8 || m == 10 || m == 12) {
		days = 31;
	} else if (m == 4 || m == 6 || m == 9 || m == 11) {
		days = 30;
	} else {
		days = (y % 4 == 0) ? 29 : 28;
	}
	var first_day = ds_dc_date.getDay();
	var first_loop = 1;
	// Start the first week
	ds_echo (ds_template_new_week());
	// If sunday is not the first day of the month, make a blank cell...
	if (first_day != 0) {
		ds_echo (ds_template_blank_cell(first_day));
	}
	var j = first_day;
	for (i = 0; i < days; i ++) {
		// Today is sunday, make a new week.
		// If this sunday is the first day of the month,
		// we've made a new row for you already.
		if (j == 0 && !first_loop) {
			// New week!!
			ds_echo (ds_template_new_week());
		}
		// Make a row of that day!
		ds_echo (ds_template_day(i + 1, m, y));
		// This is not first loop anymore...
		first_loop = 0;
		// What is the next day?
		j ++;
		j %= 7;
	}
	// Do the footer
	ds_echo (ds_template_main_below());
	// And let's display..
	ds_ob_flush();
	// Scroll it into view.
	ds_ce.scrollIntoView();
}

// A function to show the calendar.
// When user click on the date, it will set the content of t.
function ds_sh(t) {
	// Set the element to set...
	ds_element = t;
	// Make a new date, and set the current month and year.
	var ds_sh_date = new Date();
	ds_c_month = ds_sh_date.getMonth() + 1;
	ds_c_year = ds_sh_date.getFullYear();
	// Draw the calendar
	ds_draw_calendar(ds_c_month, ds_c_year);
	// To change the position properly, we must show it first.
	ds_ce.style.display = '';
	// Move the calendar container!
	the_left = ds_getleft(t);
	the_top = ds_gettop(t) + t.offsetHeight;
	ds_ce.style.left = the_left + 'px';
	ds_ce.style.top = the_top + 'px';
	// Scroll it into view.
	ds_ce.scrollIntoView();
}

// Hide the calendar.
function ds_hi() {
	ds_ce.style.display = 'none';
}

// Moves to the next month...
function ds_nm() {
	// Increase the current month.
	ds_c_month ++;
	// We have passed December, let's go to the next year.
	// Increase the current year, and set the current month to January.
	if (ds_c_month > 12) {
		ds_c_month = 1; 
		ds_c_year++;
	}
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the previous month...
function ds_pm() {
	ds_c_month = ds_c_month - 1; // Can't use dash-dash here, it will make the page invalid.
	// We have passed January, let's go back to the previous year.
	// Decrease the current year, and set the current month to December.
	if (ds_c_month < 1) {
		ds_c_month = 12; 
		ds_c_year = ds_c_year - 1; // Can't use dash-dash here, it will make the page invalid.
	}
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the next year...
function ds_ny() {
	// Increase the current year.
	ds_c_year++;
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Moves to the previous year...
function ds_py() {
	// Decrease the current year.
	ds_c_year = ds_c_year - 1; // Can't use dash-dash here, it will make the page invalid.
	// Redraw the calendar.
	ds_draw_calendar(ds_c_month, ds_c_year);
}

// Format the date to output.
function ds_format_date(d, m, y) {
	// 2 digits month.
	m2 = '00' + m;
	m2 = m2.substr(m2.length - 2);
	// 2 digits day.
	d2 = '00' + d;
	d2 = d2.substr(d2.length - 2);
	// YYYY-MM-DD
//	return y + '-' + m2 + '-' + d2;
	return y + '-' + m2 + '-' + d2;
}

// When the user clicks the day.
function ds_onclick(d, m, y) {
	// Hide the calendar.
	ds_hi();
	// Set the value of it, if we can.
	if (typeof(ds_element.value) != 'undefined') {
		ds_element.value = ds_format_date(d, m, y);
		
		difdate(ds_element);
	// Maybe we want to set the HTML in it.
	} else if (typeof(ds_element.innerHTML) != 'undefined') {
		ds_element.innerHTML = ds_format_date(d, m, y);
			
		difdate(ds_element);
	// I don't know how should we display it, just alert it to user.
	} else {
		alert (ds_format_date(d, m, y));
	}
}

// And here is the end.

// ]]> -->
</script>
<br />











<table width="677" border="0">
	<?php
}
$req4=mysql_query("select * from conge where mecano='".$mecano."'");


//On Récupère l'année
$reqannee=mysql_query("select annee from annee");

//On Initialise la variable 'AnneeActuelle'
$AnneeActuelle=0;

//On affecte le résultat obtenu depuis la table année à la variable 'AnneeActuelle'
while($AnneeEnCours=mysql_fetch_row($reqannee)){
	$AnneeActuelle=$AnneeEnCours[0];
}

//On limite la période d'affichage des congés à l'année en cours et le deux années précèdentes
$PeriodeValide=$AnneeActuelle-3;

if(@mysql_fetch_row($req4)){

//On ajoute un filtre d'année
$req4=mysql_query("select * from conge where mecano='".$mecano."' and annee>'".$PeriodeValide."' order by id desc" );

?>
  <tr>
    <td width="671"><div align="center" class="Style8">راحة عادية</div></td>
  </tr>
  <tr>
    <td><div align="center">
	
	


	<div id="container">
	<div id="scrollbox" >
		<div id="content" >
      <table width="90%" height="85" border="4" cellspacing="0" bordercolor="#FFFFFF" id="tab">
        <tr>
          <td width="50" height="24"><div align="right" class="sof">
            <div align="center"></div>
          </div></td>
          <td width="47"><div align="right" class="sof">
            <div align="center"></div>
          </div></td>
          <td width="82"><div align="right" class="sof">
            <div align="center">ايام الراحة</div>
          </div></td>
          <td width="64"><div align="right" class="sof">
            <div align="center">الى</div>
          </div></td>
          <td width="61"><div align="right" class="sof"> 
            <div align="center">من</div>
          </div></td>
          <td width="60"><div align="right" class="sof">
            <div align="center">الرقم</div>
          </div></td>
        </tr>
        <?php
	
	while($rr2=mysql_fetch_row($req4)){
		$DateDebutConge = strtotime(date_format(date_create($rr2[2]), 'Y-m-d'));
                $DateJour = strtotime(date('Y-m-d'));
 
// On récupère la différence entre les 2 dates précédentes en secondes

                $Diff = $DateJour - $DateDebutConge;
 
//On converti le résultat (exprimé en secondes) en jours 
//Avec 1 jour= 60 secondes * 60 minutes *24 heures

                 $nbJours = $Diff/86400; // 86 400 = 60*60*24
	?>
	
        <tr>
          <td height="53"><div align="right" class="sof"> 
            <div align="center">
              <form id="form2" name="form2" method="post" action="">
                <input name="mod" type="hidden" id="mod" value="<?php echo $rr2[0];?>" />
                <input name="mecano" type="hidden" id="mecano" value="<?php echo $mecano;?>" />
				<?php
				 //On n'affiche le bouton Supprimer que si la date del'élaboration du titre de congé n'a pas dépassé 3 jours
 
 if ($nbJours < 3)
	 {
  echo '
       <input name="Submit2" type="submit" class="Style6" value="تعديل" />';
  }
              
				?>
                </form>
            </div>
          </div></td>
          <td><div align="right" class="sof">
            <div align="center">
              <form id="form2" name="form2" method="post" action="">
                <input name="supp" type="hidden" id="supp" value="<?php echo $rr2[0];?>" />
                <input name="mecano" type="hidden" id="mecano" value="<?php echo $mecano;?>" />
				<?php	
											
				
 
 //On n'affiche le bouton Supprimer que si la date del'élaboration du titre de congé n'a pas dépassé 3 jours
 
 if ($nbJours < 3)
	 {
  echo '
  
    <input name="Submit22" type="submit" class="Style6" value="الغــاء" />';
  }
				?>
				
				
              
				
				
				
                </form> </div>
          </div></td>
          <td><div align="right" class="sof">
            <div align="center"><?php echo $rr2[4]; ?></div>
          </div></td>
          <td><div align="right" class="sof"> 
            <div align="center"><?php echo $rr2[3]; ?></div>
          </div></td>
          <td><div align="right" class="sof"> 
            <div align="center"><?php echo $rr2[2]; ?></div>
          </div></td>
          <td><div align="right" class="sof"> 
            <div align="center">C<?php echo $rr2[0]; ?></div>
          </div></td>
        </tr>
        <?php } ?>
      </table></div>
	</div>
	<p><span id="status" ></span></p>
</div>
    </div></td>
  </tr><?php }
	
	
	
	?>
</table>
<br />
<br />
<br />
<br />
<?php
}


?>
<script language="javascript">
$("#tab3").hide();$("#tab4").hide();$("#tab12").hide();

</script>
</body>
</html>
