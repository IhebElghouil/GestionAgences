<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="StyleSheet.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="JS/xlsx.full.min.js"></script>
<script src="JS/MyScript.js"></script>
<link rel="stylesheet" href="CSS/TableStyle.css" />

<?php
session_start();

?>
<script src="/GestionAgences/JS/jquery.min.js"></script>

<script>
    $(function(){
      // bind change event to select
      $('#dynamic_select').on('change', function () {
          var url = "/GestionAgences/BonsLait.php"; // get selected value
		  var choixdate = $(this).val();
		  var choixmois= document.getElementById("dynamic_select_Month").value;
		  if (url) { // require a URL
              window.location = url+"?"+"choixdate="+choixdate+"&choixmois="+choixmois; // redirect
          }
          return false;
      });
	  
	  $('#dynamic_select_Month').on('change', function () {
          var url = "/GestionAgences/BonsLait.php"; // get selected value
		  var choixmois = $(this).val();
		  var choixdate= document.getElementById("dynamic_select").value;
		  if (url) { // require a URL
              window.location = url+"?"+"choixdate="+choixdate+"&choixmois="+choixmois; // redirect
          }
          return false;
      });
    });
</script>

<title>Bons Lait</title>
</head>
<body >
<?php include('menu.php'); ?>


<?php
include('DbConnexion.php');

$choixdate = date("Y");

// Get the current month (for display)
$choixmois = date("m");

// Handle GET request to override default values
$choixdate = isset($_GET['choixdate']) ? $_GET['choixdate'] : $choixdate;
$choixmois = isset($_GET['choixmois']) ? $_GET['choixmois'] : $choixmois;

// Closing the connection
$conn->close();
?>
<?php  
// Session validation
if (isset($_SESSION['congidGA'])) {
    // Fetch department based on session
    $departement = $_SESSION['departement'];
    if ($departement != "admin") {
        // Non-admin view: Fetch department name
        $stmt = $conn->prepare("SELECT depar FROM dep WHERE id = ?");
        $stmt->bind_param("i", $departement);
        $stmt->execute();
        $result = $stmt->get_result();
        $r = $result->fetch_row();
        echo '<center><h2>' . htmlspecialchars($r[0]) . '</h2></center><br/>';
    } else {
        // Admin view
        echo '<center><h2></h2></center><br/>';
        echo '<a href="loginhistory.php">متابعة الولوج إلى التطبيقة</a>';
    }
} else {
    echo '<script language="Javascript">
    document.location.replace("index.php");
    </script>';
}
?>
<!-- Dropdowns for Year and Month -->
<table align="center">
    <tr>
	<td dir="rtl">
            <h2>متابعة وصولات الحليب :</h2>
        </td>
        <td>
            <form>
			<select id="dynamic_select_Month" name="choixmois">
                    <?php
                    // Dynamically generate month options, preselect current month
                    for ($month = 1; $month <= 12; $month++) {
                        $monthFormatted = str_pad($month, 2, '0', STR_PAD_LEFT);
                        echo "<option value='$monthFormatted' " . ($choixmois == $month ? 'selected' : '') . ">$monthFormatted</option>";
                    }
                    ?>
                </select>
			
               
            </form>
        </td>
        <td>
            <form>
                 <select id="dynamic_select" name="choixdate">
                    <?php
                    // Dynamically generate year options, preselect current year
                    for ($year = 2011; $year <= 2035; $year++) {
                        echo "<option value='$year' " . ($choixdate == $year ? 'selected' : '') . ">$year</option>";
                    }
                    ?>
                </select>
            </form>
        </td>
        
    </tr>
</table>

<div class="mobile-message">
    لرؤية كافة الأعمدة، يرجى التمرير أفقيًا ← →
</div>

 <div class="d-flex justify-content-between mb-3">
            <button id="clearFilters" class="btn btn-outline-secondary">
                مسح كل الفلاتر <span class="ms-2">🗑️</span>
            </button>
            
            <button onclick="exportTableToExcel('myTable', 'BonLait.xlsx')" class="btn btn-outline-success">
                <img src="images/excel.png" alt="تصدير إكسل" width="20" height="20">
                <span class="ms-2">تصدير إلى Excel</span>
            </button>
        </div>
		 <div class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" id="filterMecano" class="form-control column-filter" data-column="0" placeholder="البحث بالرقم الآلي..." aria-label="فلترة بالرقم الآلي">
            </div>
            <div class="col-md-3">
                <input type="text" id="filterNom" class="form-control column-filter" data-column="1" placeholder="البحث بالإسم أو اللقب..." aria-label="فلترة بالإسم">
            </div>
            
            <div class="col-md-3">
                <input type="text" id="filterAffectation" class="form-control column-filter" data-column="8" placeholder="البحث بوحدة الإرتباط..." aria-label="فلترة بوحدة الإرتباط">
            </div>
            
        </div>

<!-- Data Table -->
<table id="myTable" dir="rtl">
    <tr class="header">
        <th>الرقم الآلي</th>
        <th>الإسم و اللقب</th>
        <th>الشهر</th>
        <th>السنة</th>
        <th>عدد أيّام العمل</th>
        <th>عدد أيام الغياب</th>
		 <th>قيمة الوصولات</th>
        <th>عدد أيام الراحة في الأسبوع</th>
        <th>وحدة الإرتباط</th>
    </tr>
    <!-- Rows will be generated by PHP code above -->
	<?php
if (isset($_SESSION['congidGA'])) {
    $departement = $_SESSION['departement'];
include('DbConnexion.php');
   
        // Query for admin
    $query = "SELECT DISTINCT 
    S.mecano,
    S.nom,
    MONTH(A.date) AS month,
    YEAR(A.date) AS year,
    COUNT(DISTINCT A.date) AS distinct_dates_count,
    COUNT(DISTINCT C.dateabsence) AS distinct_absence_count,
    SV.libellet,
    S.jrepos
FROM stuf S
LEFT JOIN service SV ON SV.id = S.idservice
LEFT JOIN verifabsence C 
    ON S.mecano = C.mecano 
    AND moisabsence = ? 
    AND anneeabsence = ?
LEFT JOIN pointageall A 
    ON S.mecano = A.mecano 
    AND MONTH(A.date) = ? 
    AND YEAR(A.date) = ?
WHERE S.contrastage IN (0, 1, 3)
  AND S.lait = 1
GROUP BY 
    S.mecano, S.nom, SV.libellet, S.jrepos, MONTH(A.date), YEAR(A.date)
ORDER BY 
    S.mecano, SV.libellet ASC;";
            
$stmt = $conn->prepare($query);
$stmt->bind_param("iiii", $choixmois, $choixdate, $choixmois, $choixdate);
$stmt->execute();
$result = $stmt->get_result();

    

    // Display the results in table rows
    while ($r = $result->fetch_row()) {
        $nbjoursrepos = ($r[7] == 10) ? 2 : 1; // Determine rest days based on the value of jrepos
        echo '<tr>
                <td>' . $r[0] . '</td>
                <td>' . $r[1] . '</td>
                <td>' . $r[2] . '</td>
                <td>' . $r[3] . '</td>
                <td>' . $r[4] . '</td>
                <td>' . $r[5] . '</td>
                <td>' . $r[4]*1.3*1.5 . '</td>
				<td>' . $nbjoursrepos . '</td>
                <td>' . $r[6] . '</td>
              </tr>';
    }

    // Closing the connection after the query
    $conn->close();
}
?>
</table>

<script>
function filterTable() {
            var filters = {};
            $('.column-filter').each(function() {
                if ($(this).val()) {
                    filters[$(this).data('column')] = $(this).val().toLowerCase();
                }
            });
            
            $('#myTable tbody tr').each(function() {
                var showRow = true;
                var cells = $(this).find('td');
                
                for (var col in filters) {
                    if (filters.hasOwnProperty(col)) {
                        var cellText = cells.eq(col).text().toLowerCase();
                        if (cellText.indexOf(filters[col]) === -1) {
                            showRow = false;
                            break;
                        }
                    }
                }
                
                $(this).toggle(showRow);
            });
        }
        
        $('.column-filter').on('keyup change', filterTable);
        
        $('#clearFilters').on('click', function() {
            $('.column-filter').val('');
            filterTable();
        });
</script>

</body>
</html>
