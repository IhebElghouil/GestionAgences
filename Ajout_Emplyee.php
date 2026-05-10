<html>
<head>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div id=alertContainer></div>
<?php
session_start();
require('connection.php');
?>
<div class="modal-header">
  <h5 class="modal-title">إضافة عون</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
</div>
<div class="modal-body">
  <form id="updateForm" action="save.php" method="POST">
   <div class="form-group row" align=right dir=rtl>
        <label for="message-text" class="col-sm-6 col-form-label" dir=rtl>الرقم الآلي :</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" id="mecano" name="mecano" value="" align=right dir=rtl required>
        </div>
   </div>
    <div class="form-group row" align=right dir=rtl>
        <label for="message-text" class="col-sm-6 col-form-label" dir=rtl>الإسم و اللقب :</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" id="nomprenom" name="nomprenom" value="" align=right dir=rtl required>
        </div>
   </div>
   <div class="form-group row" dir="rtl" style="text-align: right;">
        <label for="statut" class="col-sm-6 col-form-label">الحالة العائليّة و المهنيّة : </label>
        <div class="col-sm-6">
            <?php
            $options = [
                "" => "غير محدّد",
                "MariéExecution" => "متزوّج/تنفيذ",
                "MariéMaitrise"  => "متزوّج/تسيير",
                "CélibExecution" => "أعزب/تنفيذ",
                "CélibMaitrise"  => "أعزب/تسيير"
            ];
            ?>
            <select name="statut" id="statut" class="form-control">
                <?php
                foreach ($options as $value => $label) {
                    echo "<option value=\"$value\">$label</option>";
                }
                ?>
            </select>
        </div>
   </div>
     
    <div class="form-group row" align=right dir=rtl data-spy="scroll">
        <label for="message-text" class="col-sm-7 col-form-label" dir=rtl>رقم بطاقة التعريف :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="cin" value="" align=right dir=rtl required>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir=rtl align=right>تاريخ الولادة :</label>
        <div class="col-sm-5">
            <input type="date" class="form-control" name="Dnaissance" value="" align=right dir=rtl required>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">تاريخ الانتداب :</label>
        <div class="col-sm-5">
            <input type="date" class="form-control" name="Drecrutement" value="" align=right dir=rtl required>
        </div>
        
        <label for="message-text" class="col-sm-4 col-form-label" dir=rtl>الرتبة :</label>
        <div class="col-sm-8">
            <select name="grade" class="form-control">
                <?php 
                $reqsn="select * from titres group by libellet";
                $ssn=mysqli_query($connection,$reqsn);
                while($rsn=mysqli_fetch_row($ssn)) {
                    echo "<option value=".$rsn[0];
                    if(@$r[2]==$rsn[0]) echo ' selected="selected" ';
                    echo ">".$rsn[1]."</option>";
                } 
                ?>
            </select>
        </div>
        
        <label for="message-text" class="col-sm-5 col-form-label" dir=rtl align=right>المصلحة :</label>
        <div class="col-sm-7">
            <select name="service" id="select6" class="form-control">
                <?php 
                $reqsn="select * from service where sb='5'";
                $ssn=mysqli_query($connection,$reqsn);
                while($rsn=mysqli_fetch_row($ssn)) {
                    echo "<option value=".$rsn[0];
                    if(@$r[11]==$rsn[0]) echo ' selected="selected" ';
                    echo ">".$rsn[1]."</option>";
                    
                    $reqsn2="select * from service where iddirection='".$rsn[0]."'";
                    $ssn2=mysqli_query($connection,$reqsn2);
                    while($rsn2=mysqli_fetch_row($ssn2)) {
                        echo "<option value=".$rsn2[0];
                        if(@$r[11]==$rsn2[0]) echo ' selected="selected" ';
                        echo ">---> ".$rsn2[1]."</option>";
                        
                        $reqsn3="select * from service where iddirection='".$rsn2[0]."'";
                        $ssn3=mysqli_query($connection,$reqsn3);
                        while($rsn3=mysqli_fetch_row($ssn3)) {
                            echo "<option value=".$rsn3[0];
                            if(@$r[11]==$rsn3[0]) echo ' selected="selected" ';
                            echo "> &nbsp;&nbsp;&nbsp;&nbsp;------> ".$rsn3[1]."</option>";
                        }
                    }
                } 
                ?><option value="0">بدون مصلحة</option> 
            </select>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">القسم :</label>
        <div class="col-sm-5">
            <select name="dep" id="select7" class="form-control">
                <?php 
                $reqsn="select * from dep";
                $ssn=mysqli_query($connection,$reqsn);
                while($rsn=mysqli_fetch_row($ssn)) {
                    echo "<option value=".$rsn[0];
                    if(@$r[3]==$rsn[0]) echo ' selected="selected" ';
                    echo ">".$rsn[2]."</option>";
                } 
                ?>
            </select>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir=rtl>السلم :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="echelle" value="" align=right dir=rtl>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir=rtl align=right>الدرجة :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="echelon" value="" align=right dir=rtl>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">الصفة :</label>
        <div class="col-sm-5">
            <select name="etat" id="select8" class="form-control">
                <option value=""> </option>
                <option value="0">مترسم</option>
                <option value="1">متربص</option>
                <option value="2">متعاقد</option>
                <option value="3">ملحق</option>
                <option value="4">متقاعد</option>
            </select>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir=rtl>السلك :</label>
        <div class="col-sm-5">
            <select name="fil" id="fil" class="form-control">
                <option value="A">إداري</option>
                <option value="T">تقني</option>
                <option value="E">إستغلال</option>
                <option value="EC">سائق</option>
                <option value="ER">قابض</option>
            </select>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir=rtl>التصنيف :</label>
        <div class="col-sm-5">
            <select name="numpers" id="numpers" class="form-control">
                <option value="A">إداري</option>
                <option value="T">تقني</option>
                <option value="EC">سائق</option>
                <option value="ER">قابض</option>
                <option value="TN">تنظيف</option>
                <option value="َُEA">إداري إستغلال</option>
                <option value="َُECT">مراقبة</option>
                <option value="َُAG">حراسة</option>
            </select>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir=rtl align=right>باقي رصيد إجازات :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="restconge" value="" align=right dir=rtl>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">رصيد إجازات السّنة الحاليّة :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="soldeconge" value="" align=right dir=rtl>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir=rtl align=right>يوم الراحة الأسبوعية :</label>
        <div class="col-sm-5">
            <select name="Jourrepos" dir="rtl" class="form-control">
                <option value="10">اداري سبت و احد</option>
                <option value="0">احد</option>
                <option value="1">اثنين</option>
                <option value="2">ثلاثاء</option>
                <option value="3">اربعاء</option>
                <option value="4">خميس</option>
                <option value="5">جمعة</option>
                <option value="6">سبت</option>
            </select>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">تسجيل الحضور :</label>
        <div class="col-sm-5">
            <select name="pointage" id="pointage" dir="rtl" class="form-control">
                <option value="0">غير معنيّ</option>
                <option value="1">معنيّ</option>
            </select>
        </div>
        
        <label class="col-sm-7 col-form-label">بداية تسجيل الحضور :</label>
        <div class="col-sm-5">
            <input type="date" 
                   class="form-control" 
                   id="Dpointage"
                   name="Dpointage" 
                   value="" 
                   align="right" 
                   dir="rtl"
                   disabled>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir=rtl align=right>رقم الضمان الإجتماعي :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="codesocial" value="" align=right dir=rtl>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir="rtl" align="right">رقم التأمين الجماعي :</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" name="codeassurance" value="" align=right dir=rtl>
        </div>
        
        <label for="message-text" class="col-sm-7 col-form-label" dir=rtl align=right>يتمتّع بالحليب :</label>
        <div class="col-sm-5">
            <select name="lait" dir="rtl" class="form-control">
                <option value="1">نعم</option>
                <option value="0">لا</option>
            </select>
        </div>
        
        <label class="col-sm-7 col-form-label">الجنس :</label>
        <div class="col-sm-5">
            <select name="sexe" dir="rtl" class="form-control">
                <option value="M">ذكر</option>
                <option value="F">أنثى</option>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">حفظ التحيينات</button>
    </div>
  </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const pointageSelect = document.getElementById("pointage");
    const dpointageInput = document.getElementById("Dpointage");
    
    function toggleDpointage() {
        if (pointageSelect.value === "1") {
            dpointageInput.disabled = false;
            dpointageInput.required = true;
        } else {
            dpointageInput.disabled = true;
            dpointageInput.required = false;
            dpointageInput.value = "";
        }
    }
    
    pointageSelect.addEventListener("change", toggleDpointage);
    toggleDpointage(); // Appel initial
});
</script>
</body>
</html>