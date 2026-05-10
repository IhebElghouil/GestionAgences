<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>تحيين الإجازة</title>
    <!-- Load jQuery first -->
    <script src="JS/jquery-3.6.0.min.js"></script>
    <!-- Then Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Other libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            direction: rtl;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="date"], input[type="number"] {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .btn {
            margin-top: 10px;
        }
        #loadingIndicator {
            display: none;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <?php
    // Start session at the very beginning
    session_start();

    require_once(__DIR__ . "/DbConnexion.php");

    // Initialize variables
    $error = '';
    $success = '';
    $mecano = isset($_GET['mecano']) ? $conn->real_escape_string($_GET['mecano']) : '';
    $dateDebut = isset($_GET['DateDebut']) ? $_GET['DateDebut'] : '';
    $dateFin = isset($_GET['DateFin']) ? $_GET['DateFin'] : '';
    $nbJours = isset($_GET['NbJour']) ? (int)$_GET['NbJour'] : 0;
    $SoldeConge = isset($_GET['RestSolde']) ? $_GET['RestSolde'] : 0;
    $jrepos = 0;

    // Get employee information using prepared statement
    $employeeInfo = [];
    $sqlEmployee = "SELECT stuf.nom, nbconge.rest, jrepos FROM stuf 
                    LEFT JOIN nbconge ON stuf.mecano = nbconge.mecano 
                    WHERE stuf.mecano = ?";
    $stmtEmployee = $conn->prepare($sqlEmployee);
    $stmtEmployee->bind_param("s", $mecano);
    $stmtEmployee->execute();
    $resultEmployee = $stmtEmployee->get_result();

    if ($resultEmployee->num_rows > 0) {
        $employeeInfo = $resultEmployee->fetch_assoc();
        $jrepos = $employeeInfo['jrepos'];
    }

    // Get the current leave information
    $leaveInfo = [];
    if (!empty($_GET['id'])) {
        $sqlLeave = "SELECT datedebut, datefin, nbjours FROM conge WHERE id = ?";
        $stmtLeave = $conn->prepare($sqlLeave);
        $stmtLeave->bind_param("i", $_GET['id']);
        $stmtLeave->execute();
        $resultLeave = $stmtLeave->get_result();
        
        if ($resultLeave->num_rows > 0) {
            $leaveInfo = $resultLeave->fetch_assoc();
            $dateDebut = $leaveInfo['datedebut'];
            $dateFin = $leaveInfo['datefin'];
            $nbJours = $leaveInfo['nbjours'];
        } else {
            $error = "لم يتم العثور على الإجازة المحددة";
        }
    }
    ?>

    <div class="container">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form id="leaveForm" method="post" action="update_leave_handler.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">
            <input type="hidden" id="oldNbJours" name="oldNbJours" value="<?php echo htmlspecialchars($nbJours); ?>">
            <input type="hidden" name="mecano" value="<?php echo htmlspecialchars($mecano); ?>">
            
            <div class="input-group mb-3" dir="rtl">
                <span class="input-group-text" id="basic-addon2" dir="rtl" style="color:red;font-weight: bold;width:225px">الرصيد الحالي للإجازات : </span>
                <input type="number" id="Solde" name="Solde" style="font-size: 18px;color:red;font-weight: bold;" class="form-control"
                       value="<?php echo htmlspecialchars($SoldeConge); ?>" readonly>
            </div>
            
            <div class="form-group">
                <label for="DateDebut">تاريخ بداية الإجازة:</label>
                <input type="date" id="DateDebut" name="DateDebut" 
                       value="<?php echo htmlspecialchars($dateDebut); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="DateFin">تاريخ نهاية الإجازة:</label>
                <input type="date" id="DateFin" name="DateFin" 
                       value="<?php echo htmlspecialchars($dateFin); ?>" required>
            </div>
            
            <div class="alert alert-danger" id="success-alert" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                <strong id="error-message"></strong>
            </div>
            
            <div class="input-group mb-3" dir="rtl">
                <span class="input-group-text" id="basic-addon2" dir="rtl">يوم الرّاحة الأسبوعيّة : </span>
                <select name="JourDeReposFixe" id="JourDeReposFixe" class="form-select" aria-label=".form-select-lg example" title="يوم الراحة الأسبوعيّة" style="font-size: 18px;">
                    <option value="0" <?php if($jrepos==0) echo 'selected'; ?>>الأحد</option>
                    <option value="1" <?php if($jrepos==1) echo 'selected'; ?>>الإثنين</option>
                    <option value="2" <?php if($jrepos==2) echo 'selected'; ?>>الثلاثاء</option>
                    <option value="3" <?php if($jrepos==3) echo 'selected'; ?>>الأربعاء</option>
                    <option value="4" <?php if($jrepos==4) echo 'selected'; ?>>الخميس</option>
                    <option value="5" <?php if($jrepos==5) echo 'selected'; ?>>الجمعة</option>
                    <option value="6" <?php if($jrepos==6) echo 'selected'; ?>>السبت</option>
                    <option value="10" <?php if($jrepos==10) echo 'selected'; ?>>السبت و الأحد</option>
                </select>
            </div>
            
            <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon2">عدد أيام الإجازة:</span>
                <input type="number" id="NbJoursConge" name="NbJoursConge" class="form-control"
                       value="<?php echo htmlspecialchars($nbJours); ?>" min="1" required readonly>
            </div>
            
            <div class="input-group mb-3" dir="rtl">
                <span class="input-group-text" id="basic-addon2" dir="rtl" style="font-size: 18px;color:green;font-weight: bold;">الرّصيد المتبقّي : </span>
                <input type="text" name="NbJoursResteSolde" style="font-size: 18px;color:green;font-weight: bold;" id="NbJoursResteSolde" class="form-control" placeholder="الرّصيد المتبقّي......" aria-label="SoldeRestant" aria-describedby="basic-addon2" dir="rtl" readonly value="<?php echo htmlspecialchars($SoldeConge - $nbJours); ?>">
            </div>
            
            <div id="loadingIndicator">جاري المعالجة...</div>
            
            <button type="submit" class="btn btn-primary" id="SaveData">حفظ التعديلات</button>
          <!-- <button class="btn btn-danger" onclick="
  window.parent.document.getElementById('externalModal').classList.remove('show');
  window.parent.document.getElementById('externalModal').style.display = 'none';
  window.parent.document.body.classList.remove('modal-open');
  window.parent.document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
">إلغاء</button> -->


        </form>
    </div>

    <script>
    $(document).ready(function() {
        var joursFeries = [];
        var str1 = "<?php echo $mecano; ?>";
        
        // Fetch holidays
        fetch('recupdate.php')
            .then(response => response.json())
            .then(data => {
                joursFeries = data;
                console.log('Fetched holidays:', joursFeries);
            })
            .catch(error => console.error('Error fetching holidays:', error));
        
        // Function to check if a date is a weekend
        function estJourDeRepos(date) {
            const day = new Date(date).getDay(); // 0 = Sunday, 6 = Saturday
            const jourRepos = $('#JourDeReposFixe').val();
            
            if (jourRepos == 10) {
                return day === 0 || day === 6;
            } else {
                return day == jourRepos;
            }
        }
        
        // Function to check if a date is a holiday
        function estJourFerie(date) {
            const dateStr = date.toISOString().split('T')[0];
            return joursFeries.includes(dateStr);
        }
        
        // Function to calculate working days difference
        function calculerDifferenceOuvree(DateDebut, DateFin, TypeRepos) {
            let startDate = new Date(DateDebut);
            let endDate = new Date(DateFin);
            let totalDays = 0;
            
            if (startDate > endDate) {
                [startDate, endDate] = [endDate, startDate];
            }
            
            if (TypeRepos == 0) { // Working days calculation
                for (let d = startDate; d <= endDate; d.setDate(d.getDate() + 1)) {
                    if (!estJourDeRepos(d) && !estJourFerie(d)) {
                        totalDays++;
                    }
                }
            } else { // All days calculation
                for (let d = startDate; d <= endDate; d.setDate(d.getDate() + 1)) {
                    totalDays++;
                }
            }
            
            return totalDays;
        }
        
        // Handle date changes
        $('#DateDebut, #DateFin, #JourDeReposFixe').on('change', function() {
            const DateDebut = $('#DateDebut').val();
            const DateFin = $('#DateFin').val();
            const TypeRepos = 0; // Assuming regular leave
            
            if (!DateDebut || !DateFin) {
                showError('يرجى إختيار تاريخ بداية الإجازة و نهايتها');
                return;
            }
            
            const Date1 = new Date(DateDebut);
            const Date2 = new Date(DateFin);
            
            if (Date1.getFullYear() !== Date2.getFullYear()) {
                showError('الإجازة لا يمكن أن تمتدّ على سنتين');
                resetFields();
                return;
            }
            
            if (Date1 > Date2) {
                showError('يرحى التثبّت في تاريخ نهاية الإجازة');
                resetFields();
                return;
            }
            
            const difference = calculerDifferenceOuvree(DateDebut, DateFin, TypeRepos);
            
            if (difference === 0) {
                showError('لا توجد أيام عمل في الفترة المحددة');
                resetFields();
                return;
            }
            
            const solde = parseInt($('#Solde').val());
			const Oldnbdays = parseInt($('#oldNbJours').val());
            
            if (difference > solde) {
                showError('رصيد الإجازات غير كافي');
                resetFields();
                return;
            }
            
            $('#NbJoursConge').val(difference);
            $('#NbJoursResteSolde').val(solde + Oldnbdays - difference);
            
            // Verify dates with server
            verifyDatesWithServer(DateDebut, DateFin, difference);
        });
        
        function verifyDatesWithServer(DateDebut, DateFin, difference) {
            $.ajax({
                url: 'verifdate.php',
                type: 'GET',
                data: {
                    DateDebut: DateDebut,
                    DateFin: DateFin,
                    difference: difference,
                    mecano: str1
                },
                timeout: 5000,
                beforeSend: function() {
                    $('#loadingIndicator').show();
                },
                success: function(response) {
                    if (response == 0) {
                        showError('الراحة المطلوبة قد تكون مسجلة سابقا او جزء تابع لراحة مسجلة');
                        //resetFields();
                    }
                },
                error: function(xhr, status, error) {
                    if (status === 'timeout') {
                        showError('La requête a expiré. Veuillez réessayer.');
                    } else {
                        console.log('AJAX Error:', error);
                        showError('حدث خطأ أثناء التحقق من التواريخ');
                    }
                },
                complete: function() {
                    $('#loadingIndicator').hide();
                }
            });
        }
        
        function showError(message) {
            $('#error-message').text(message);
            $('#success-alert').show();
            setTimeout(function() {
                $('#success-alert').fadeOut();
            }, 5000);
        }
        
        function resetFields() {
            $('#DateFin').val('');
            $('#NbJoursConge').val(0);
            $('#NbJoursResteSolde').val($('#Solde').val());
        }
        
        // Form submission
        $('#leaveForm').on('submit', function(e) {
            e.preventDefault();
            
            if (parseInt($('#NbJoursConge').val()) <= 0) {
                showError('يرجى تحديد فترة إجازة صالحة');
                return false;
            }
            
            // Submit form via AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        alert('تم تحديث الإجازة بنجاح');
                        window.parent.$('#externalModal').modal('hide');
                        window.parent.location.reload();
                    } else {
                        showError(response.message || 'حدث خطأ أثناء حفظ البيانات');
                    }
                },
                error: function() {
                    showError('حدث خطأ أثناء الاتصال بالخادم');
                }
            });
        });
    });
    </script>
</body>
</html>