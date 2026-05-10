<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
function showname(str) {
  if (str=="") {
    document.getElementById("txtHint").innerHTML="";
    return;
  }
  var xmlhttp=new XMLHttpRequest();
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("txtHint").innerHTML=this.responseText;
    }
  }
  
  xmlhttp.open("GET","verifdispmecano.php?q="+str,true);
  xmlhttp.send();
  
}

</script>
</head>

<?php
session_start();
require('connection.php');
?>




<body>
<?php

	 	 if (isset($_GET['method'])) {
    
  $req="insert into stuf values('".$_POST['recipientname']."','".$_POST['nomprenom']."','".$_POST['grade']."','".$_POST['dep']."','".$_POST['Dnaissance']."','".$_POST['Drecrutement']."','".$_POST['cin']."','','','".$_POST['echelle']."','".$_POST['echelon']."','".$_POST['service']."','".$_POST['etat']."','".$_POST['restconge']."','".$_POST['soldeconge']."','".$_POST['etat']."','".$_POST['fil']."',Null,'".$_POST['Jourrepos']."','".$_POST['pointage']."')";
  $re=mysql_query($req);
  
  $req2="insert into nbconge values('".$_POST['recipientname']."','".$_POST['restconge']."','".$_POST['soldeconge']."','".($_POST['restconge']+$_POST['soldeconge'])."')";
  $re2=mysql_query($req2);
  
   $req3="insert into social values('".$_POST['recipientname']."','".$_POST['codesocial']."','".$_POST['codeassurance']."')";
   $re3=mysql_query($req3);
   
   if($_POST['grade']==6 || $_POST['grade']==7 || $_POST['grade']==8)
   {
   $req4="insert into cartes values('".$_POST['recipientname']."','','','',0)";
   $re4=mysql_query($req4);
   
   $req5="insert into cartes values('".$_POST['recipientname']."','','','',1)";
   $re5=mysql_query($req5);
   }
  // ajouter employee à la table nbrc
  //$reqajoutrc="insert into nbrc values('".$_POST['mecano']."',0,0,0)";
  //$reajoutrc=mysql_query($reqajoutrc);
  
  if($re && $re2 && $re3) {
  echo "<script language=javascript>alert('تمّت إضافة العون بنجاح');</script>";

  }
  else echo "<script language=javascript>alert('الرجاء إعادة المحاولة لاحقا');</script>";
}
?>
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header" align=center>
	  
      <div class="modal-body">
	  
	 
	  
        <form method='POST' action='ajout_personnel.php?method=insert'>
		
		
		<div class="form-group row" align=right dir=rtl>
            <label for="recipient-name" class="col-sm-7 col-form-label" dir=rtl>الرقم الآلي :</label>
			    <div class="col-sm-5">
		<input type="text" class="form-control" name="recipientname" id="recipientname" value="" align=right dir=rtl onchange="showname(document.getElementById('recipientname').value)">
                </div>
		</div>		
				
		 <div id="txtHint" class="form-group row" align=right dir=rtl data-spy="scroll"><center>......................................................</center></div>
					 
		    
        </form>
      </div>
     </div> 
    </div>
  </div>


</body>
</html>