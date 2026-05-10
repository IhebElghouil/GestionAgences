<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
tr {
 height: 30px;
}
.title {
 font-weight: bold;
}
</style>
<script>
$(document).ready(function() {
$("#b2").click(function(){
str1=$('#myInput').val();
$("#Content").load("load-textconge.php", {'myInput':str1});
})
})
</script>
<script>
function hided()
{
str1=$('#myInput').val();
$("#Content").load("load-textconge.php", {'myInput':str1});
}
</script>
<script src="JS/jquery.min.js"></script>
</head>

<body>
<center>
<br>
<br>
<br>
<div align="right" dir=rtl><h3>الشركة الجهويّة للنقل بقابس</h3></div>
  <div align="center" dir=rtl><h2><u>ملف العطل السنويّة</u></h2></div> 
  <div align="right" dir=rtl><h3>الرقم الآلي :<input type="text" id="myInput" onkeyup="hided()" dir="rtl" maxlength=6></h3></div>

<div class="ex1" id="Content" style=display: none;>
 
</div>
</center>
</body>
</html>
