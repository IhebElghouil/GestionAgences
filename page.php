<html>


<?php
session_start();
?>




<body>
<?php
// Assuming DbConnexion.php correctly sets up the $mysqli connection.
include('DbConnexion.php');

// Sanitize and assign GET parameters
$type = isset($_GET['type']) ? (int)$_GET['type'] : 0;
$mecano = isset($_GET['mecano']) ? (int)$_GET['mecano'] : 0;

// Mapping of type values to modal titles using array() function
$modalTitles = array(
    1 => 'تحيين رخص السياقة',
    0 => 'تحيين البطاقات المهنيّة',
    2 => 'تحيين الراحة الأسبوعيّة و تسجيل الحضور',
    3 => 'تحيين حوادث الشغل',
    5 => 'تحيين الإستجوابات/العقوبات'
);

?>
<div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
        <div class="modal-header" align="center">
            <?php
            // Check if 'type' exists in the query string and display the corresponding title
            if (isset($modalTitles[$type])) {
                echo '<h2 class="modal-title fs-5 text-center" id="exampleModalLabel">' . $modalTitles[$type] . '</h2>';
            }
            ?>
        </div>
        <div class="modal-body">
            <?php
            // Prepare query based on the type value
            $query = "";
            switch ($type) {
                case 1:
                case 0:
                    $query = "SELECT stuf.mecano, nom, numcarte, dateemission, finvalidite
                              FROM stuf 
                              LEFT JOIN cartes ON stuf.mecano = cartes.mecano 
                              WHERE cartes.type = ? AND stuf.mecano = ?";
                    break;
                case 2:
                    $query = "SELECT stuf.mecano, nom, jrepos, pointagemachine 
                              FROM stuf 
                              WHERE stuf.mecano = ?";
                    break;
                case 3:
                    $query = "SELECT autreconge.mecano, stuf.nom, datedebut, datefin, commentaire, valide, type2 
                              FROM autreconge 
                              LEFT JOIN stuf ON stuf.mecano = autreconge.mecano 
                              WHERE autreconge.id = ?";
                    break;
                case 4:
                    $query = "SELECT DISTINCT certificats.mecano, stuf.nom, datecertificat, numcertifcat, DateFin, Observation, certificats.Etat 
                              FROM certificats 
                              LEFT JOIN stuf ON stuf.mecano = certificats.mecano 
                              WHERE certificats.id = ?";
                    break;
                case 5:
                    $query = "SELECT mecano, nom, grade, datefaute, datequestionnaire, faute, sanction, datesanction 
                              FROM sanctions 
                              WHERE id = ?";
                    break;
                default:
                    echo "Invalid type.";
                    exit;
            }

            // Prepare and bind parameters
            $stmt = $conn->prepare($query);
            if ($type == 2 || $type == 3 || $type == 4 || $type == 5) {
                // For type 3 and 4, binding should be different (for example, it's "i" instead of "ii" if only one parameter is passed)
                $stmt->bind_param("i", $mecano);  // Only one parameter for type 3 and 4
            } else {
                $stmt->bind_param("ii", $type, $mecano);  // Default case for most types
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $r = $result->fetch_row();  // Fetch the result as an indexed array

            // Ensure result exists
            if (!$r) {
                echo "No data found.";
                exit;
            }

            // Close statement and connection
            $stmt->close();
            ?>
			<?php
			// Helper function to map numeric values to their respective labels
function getSituation($value) {
    $situations = array(
        0 => "غير معنيّ بتسجيل الحضور",
        1 => "معنيّ بتسجيل الحضور"
    );
    return isset($situations[$value]) ? $situations[$value] : "غير محدد"; // Default if not found
}

function getJourEnLettre($value) {
    $daysOfWeek = array(
        10 => "اداري سبت و احد",
        0  => "احد",
        1  => "اثنين",
        2  => "ثلاثاء",
        3  => "اربعاء",
        4  => "خميس",
        5  => "جمعة",
        6  => "سبت"
    );
    return isset($daysOfWeek[$value]) ? $daysOfWeek[$value] : "غير محدد"; // Default if not found
}

// Check if 'type' exists and validate its value
$type = isset($_GET['type']) ? (int)$_GET['type'] : 0;
$mecano = isset($_GET['mecano']) ? (int)$_GET['mecano'] : 0;

// Prepare variables for the modal
$situation = '';
$jourenlettre = '';

// Process only if 'type' is 2
if ($type == 2) {
    $situation = getSituation($r[3]);  // Get situation based on $r[3]
    $jourenlettre = getJourEnLettre($r[2]);  // Get day based on $r[2]
}
			?>

            <form method="POST" action="updatecartes.php?modif=<?php echo $type; ?>&id=<?php echo $mecano; ?>">
                <div class="mb-3" align="right">
                    <label for="recipient-name" class="col-form-label" dir="rtl">الرقم الآلي :</label>
                    <input type="text" class="form-control" name="recipientname" id="recipientname" value="<?php echo htmlspecialchars($r[0]); ?>" readonly align="right" dir="rtl">
                </div>

                <div class="mb-3" align="right">
                    <label for="message-text" class="col-form-label" dir="rtl">الإسم و اللقب :</label>
                    <input type="text" class="form-control" id="nomprenom" value="<?php echo htmlspecialchars($r[1]); ?>" readonly align="right" dir="rtl">
                </div>

                <?php
                if ($type == 1 || $type == 0) {
                    echo '
                    <div class="mb-3" align="right">
                        <label for="message-text" class="col-form-label" dir="rtl">رقم رخصة السياقة :</label>
                        <input type="text" class="form-control" id="numpermis" name="numpermis" value="' . htmlspecialchars($r[2]) . '" align="right" dir="rtl">
                    </div>
                    <div class="mb-3" align="right">
                        <label for="message-text" class="col-form-label" dir="rtl">تاريخ الإصدار :</label>
                        <input type="date" class="form-control" id="dateemission" name="dateemission" value="' . htmlspecialchars($r[3]) . '" align="right" dir="rtl">
                    </div>
                    <div class="mb-3" align="right">
                        <label for="message-text" class="col-form-label" dir="rtl">تاريخ نهاية الصلوحيّة :</label>
                        <input type="date" class="form-control" id="finvalidite" name="finvalidite" value="' . htmlspecialchars($r[4]) . '" align="right" dir="rtl">
                    </div>';
                } elseif ($type == 2) {
					
                    echo '
                    <div class="mb-3" align="right">
                        <label for="message-text" class="col-form-label" dir="rtl">الراحة الأسبوعية القديمة :</label>
                        <input type="text" class="form-control" value="' . htmlspecialchars($jourenlettre) . '" align="right" dir="rtl" readonly>
                    </div>
                    <div class="mb-3" align="right">
                        <label for="message-text" class="col-form-label" dir="rtl">الراحة الأسبوعية الجديدة :</label>
                        <select name="repos" id="repos" dir="rtl" class="form-control">
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
                    <div class="mb-3" align="right">
                        <label for="message-text" class="col-form-label" dir="rtl">الوضعيّة الحاليّة للعون :</label>
                        <input type="text" class="form-control" value="' . htmlspecialchars($situation) . '" align="right" dir="rtl" readonly>
                    </div>
                    <div class="mb-3" align="right">
                        <label for="message-text" class="col-form-label" dir="rtl">تحيين الوضعيّة تجاه تسجيل الحضور :</label>
                        <select name="pointagemachine" id="pointagemachine" dir="rtl" class="form-control">
                            <option value="1">معنيّ</option>
                            <option value="0">غير معنيّ</option>
                        </select>
                    </div>';
                } elseif ($type == 3) {
                    echo '
                    <div class="mb-3" align="right">
                        <label for="message-text" class="col-form-label" dir="rtl">التاريخ من :</label>
                        <input type="date" class="form-control" name="debut" value="' . htmlspecialchars($r[2]) . '" align="right" dir="rtl">
                    </div>
                    <div class="mb-3" align="right">
                        <label for="message-text" class="col-form-label" dir="rtl">التاريخ إلى :</label>
                        <input type="date" class="form-control" name="fin" value="' . htmlspecialchars($r[3]) . '" align="right" dir="rtl">
                    </div>
                    <div class="mb-3" align="right">
                        <label for="message-text" class="col-form-label" dir="rtl">الملاحظات :</label>
                        <textarea class="form-control" name="observ" align="right" dir="ltr">' . htmlspecialchars($r[4]) . '</textarea>
                    </div>
					<div class="mb-3" align="right">
					<select name="TypeRepos"  id="TypeRepos" class="form-select" aria-label=".form-select-lg example"  title="نوع الراحة"  style="font-size: 18px;" >
                       <option value="11" data-type="accident" ' . ($r[6] == 11 ? 'selected' : '') . '>حادث شغل (أوّلي)</option>
                       <option value="9" data-type="prolongation" ' . ($r[6] == 9 ? 'selected' : '') . '>حادث شغل (تمديد)</option>
                       <option value="10" data-type="rechute" ' . ($r[6] == 10 ? 'selected' : '') . '>حادث شغل (إنتكاسة)</option>
					</select>
                    </div>
					<div class="mb-3" align=right>
            <label for="message-text" class="col-form-label" dir="rtl" align="right">مباشرة العمل</label>';
			if (htmlspecialchars($r[5])==1) {
			 echo '<input class="form-conctrol" type="checkbox" value="1" id="flexCheckChecked" name="flexCheckChecked" align="left">';
			}
			else
			{
			echo '<input class="form-conctrol" type="checkbox" value="0" id="flexCheckChecked" name="flexCheckChecked" align="left" checked=checked>';
			}
			
			echo '</div>';
                }
elseif ($_GET['type']==4)
		   {
			 echo '
			  <div class="mb-3" align=right>
            <label for="message-text" class="col-form-label" dir=rtl>التاريخ من :</label>
            <input type="date" class="form-control"  name ="debut" value="'.$r[2].'"  align=right dir=rtl>
          </div>
		   <div class="mb-2" align=right>
            <label for="message-text" class="col-form-label" dir=rtl>رقم الشهادة الطبيّة :</label>
            <input type="text" class="form-control" id="numcertif" name="numcertif" value="'.$r[3].'"  align=right dir=rtl required>
          </div>
		   <div class="mb-3" align=right>
            <label for="message-text" class="col-form-label" dir=rtl align=right >التاريخ إلى :</label>
			<input type="date" class="form-control" name="fin" value="'.$r[4].'"  align=right dir=rtl>
           
          </div>
		  <div class="mb-3" align=right>
            <label for="message-text" class="col-form-label" dir="rtl" align="right">الملاحظات :</label>
			<textarea class="form-control" name="observ" align="right" dir="ltr" >'.$r[5].'</textarea>
           
          </div> 
          <div class="mb-3" align=right>
            <label for="message-text" class="col-form-label" dir="rtl" align="right">تم تحيين هذه الشهادة/تمت تسوية الوضعيّة</label>';
			if ($r[6]==1) {
			 echo '<input class="form-conctrol" type="checkbox" value=1 id="flexCheckCheckedcertif" name="flexCheckCheckedcertif" align="left">';
			}
			else
			{
			echo '<input class="form-conctrol" type="checkbox" value=0 id="flexCheckCheckedcertif" name="flexCheckCheckedcertif" align="left" checked=checked>';
			}
			
			echo '</div>';		  
		  
		   }
		   if ($_GET['type']==5) {
		  
			echo '
			 <div class="mb-3" align=right>
			  <label for="message-text" class="col-form-label" dir=rtl>الرتبة :</label>
			  <input type="text" class="form-control" id="grade" name="grade" value="'.$r[2].'" readonly align=right dir=rtl>
			</div>
			 <div class="mb-3" align=right>
			  <label for="message-text" class="col-form-label" dir=rtl>تاريخ المخالفة :</label>
			  <input type="date" class="form-control" id="datefaute" name="datefaute" value="'.$r[3].'" align=right dir=rtl title="الرجاء إدخال التاريخ  على النحو التالي الشهر/اليوم/السنة">
			</div>
			 <div class="mb-3" align=right>
			  <label for="message-text" class="col-form-label" dir=rtl>تاريخ الإستجواب :</label>
			  <input type="date" class="form-control" id="datequest" name="datequest" value="'.$r[4].'"  align=right dir=rtl title="الرجاء إدخال التاريخ  على النحو التالي الشهر/اليوم/السنة">
			</div>
			<div class="mb-3" align=right>
			  <label for="message-text" class="col-form-label" dir=rtl>العقوبة :</label>
			  <select id="sanction" name="sanction" class="form-control" dir=rtl>
			  <option>تذكير بالإمتثال</option>
			  <option>توبيخ</option>
			  <option>إنذار</option>
			  <option>إيقاف ليوم واحد</option>
			  <option>إيقاف ليومين</option>
			  <option>إيقاف لثلاثة أيام</option>
			  <option>مجلس تأديب</option>
			  <option>عزل نهائي</option>
			  </select>
			</div>';
			
			 }

                ?>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">حفظ التحيينات</button>
                </div>
            </form>
        </div>
    </div>
</div>


</body>
</html>