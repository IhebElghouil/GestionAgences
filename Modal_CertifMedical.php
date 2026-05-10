<html>
<head>
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
  
  xmlhttp.open("GET","verifmecano.php?q="+str,true);
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

     // $mecano=$_GET['mecano'];
	 // $type=$_GET['type'];
	 
?>
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header" align=center>
	  <h2 class="modal-title fs-5" id="exampleModalLabel" align=center dir=rtl>إضافة شهادة طبيّة</h2>
	  
      </div>
      <div class="modal-body">
	  
	  	  
        <form action="addmedical.php" Method="POST">
		  <div class="mb-2" align=right>
		    <label for="recipient-name" class="col-form-label" dir=rtl id="mecano">الرقم الآلي :</label>
            <input type="text" class="form-control" name="recipientname" id="recipientname" value="" align=right dir=rtl required onchange="showname(document.getElementById('recipientname').value)">
          
		  </div>
		  <div id="txtHint">..........</div>
		  
        </form>
	  
      </div>
      
    </div>
  </div>

</body>
</html>