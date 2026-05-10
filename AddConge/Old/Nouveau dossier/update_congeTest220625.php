<?php
// update_conge.php
session_start();

// Security headers
/*header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");*/

require_once(__DIR__ . "/DbConnexion.php");

// Initialize variables
$error = '';
$success = '';
$mecano = isset($_GET['id']) ? $conn->real_escape_string($_GET['id']) : '';
$dateDebut = isset($_GET['DateDebut']) ? $_GET['DateDebut'] : '';
$dateFin = isset($_GET['DateFin']) ? $_GET['DateFin'] : '';
$nbJours = isset($_GET['NbJour']) ? (int)$_GET['NbJour'] : 0;

// Get employee information
$employeeInfo = [];
$sqlEmployee = "SELECT stuf.nom, nbconge.rest FROM stuf 
                LEFT JOIN nbconge ON stuf.mecano = nbconge.mecano 
                WHERE stuf.mecano = ?";
$stmtEmployee = $conn->prepare($sqlEmployee);
$stmtEmployee->bind_param("s", $mecano);
$stmtEmployee->execute();
$resultEmployee = $stmtEmployee->get_result();

if ($resultEmployee->num_rows > 0) {
    $employeeInfo = $resultEmployee->fetch_assoc();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newDateDebut = $conn->real_escape_string($_POST['newDateDebut']);
    $newDateFin = $conn->real_escape_string($_POST['newDateFin']);
    $newNbJours = (int)$_POST['newNbJours'];
    
    // Validate dates
    if (empty($newDateDebut) || empty($newDateFin)) {
        $error = "يرجى إدخال تاريخي البدء والانتهاء";
    } elseif ($newDateDebut > $newDateFin) {
        $error = "تاريخ البداية يجب أن يكون قبل تاريخ النهاية";
    } else {
        // Verify the new dates don't conflict with existing leaves
        $sqlCheck = "SELECT id FROM conge 
                     WHERE mecano = ? 
                     AND ((datedebut <= ? AND datefin >= ?) 
                     OR (datedebut <= ? AND datefin >= ?) 
                     OR (datedebut >= ? AND datefin <= ?))
                     AND id != ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        $id = (int)$_POST['id'];
        $stmtCheck->bind_param("sssssssi", $mecano, $newDateDebut, $newDateDebut, 
                              $newDateFin, $newDateFin, $newDateDebut, $newDateFin, $id);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();
        
        if ($resultCheck->num_rows > 0) {
            $error = "التواريخ الجديدة تتعارض مع إجازة موجودة بالفعل";
        } else {
            // Calculate the difference in vacation days
            $oldNbJours = (int)$_POST['oldNbJours'];
            $difference = $newNbJours - $oldNbJours;
            
            // Check if there's enough vacation balance
            $currentBalance = $employeeInfo['rest'] ?? 0;
            if ($difference > 0 && $difference > $currentBalance) {
                $error = "الرصيد الحالي للإجازات غير كافي لهذا التعديل";
            } else {
                // Update the leave record
                $sqlUpdate = "UPDATE conge 
                             SET datedebut = ?, datefin = ?, nbjours = ? 
                             WHERE id = ?";
                $stmtUpdate = $conn->prepare($sqlUpdate);
                $stmtUpdate->bind_param("ssii", $newDateDebut, $newDateFin, $newNbJours, $id);
                
                if ($stmtUpdate->execute()) {
                    // Update the vacation balance if needed
                    if ($difference != 0) {
                        $sqlUpdateBalance = "UPDATE nbconge 
                                            SET rest = rest - ? 
                                            WHERE mecano = ?";
                        $stmtUpdateBalance = $conn->prepare($sqlUpdateBalance);
                        $stmtUpdateBalance->bind_param("is", $difference, $mecano);
                        $stmtUpdateBalance->execute();
                    }
                    
                    $success = "تم تحديث الإجازة بنجاح";
                    // Close the modal after successful update
                    echo '<script>
                        setTimeout(function() {
                            window.parent.$("#externalModal").modal("hide");
                            window.parent.location.reload();
                        }, 1500);
                    </script>';
                } else {
                    $error = "حدث خطأ أثناء تحديث الإجازة";
                }
            }
        }
    }
}

// Get the current leave information
$leaveInfo = [];
if (!empty($_GET['id'])) {
    $sqlLeave = "SELECT * FROM conge WHERE id = ?";
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

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تحيين الإجازة</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>
<body>
    <div class="container">
        <h2>تحيين الإجازة</h2>
        <p>الموظف: <?php echo htmlspecialchars($employeeInfo['nom'] ?? ''); ?></p>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($leaveInfo['id'] ?? ''); ?>">
            <input type="hidden" name="oldNbJours" value="<?php echo htmlspecialchars($nbJours); ?>">
            
            <div class="form-group">
                <label for="newDateDebut">تاريخ بداية الإجازة:</label>
                <input type="date" id="newDateDebut" name="newDateDebut" 
                       value="<?php echo htmlspecialchars($dateDebut); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="newDateFin">تاريخ نهاية الإجازة:</label>
                <input type="date" id="newDateFin" name="newDateFin" 
                       value="<?php echo htmlspecialchars($dateFin); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="newNbJours">عدد أيام الإجازة:</label>
                <input type="number" id="newNbJours" name="newNbJours" 
                       value="<?php echo htmlspecialchars($nbJours); ?>" min="1" required>
            </div>
            
            <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
            <button type="button" class="btn btn-secondary" onclick="window.parent.$('#externalModal').modal('hide')">إلغاء</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Calculate days when dates change
        document.getElementById('newDateDebut').addEventListener('change', calculateDays);
        document.getElementById('newDateFin').addEventListener('change', calculateDays);
        
        function calculateDays() {
            const startDate = new Date(document.getElementById('newDateDebut').value);
            const endDate = new Date(document.getElementById('newDateFin').value);
            
            if (startDate && endDate && startDate <= endDate) {
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                document.getElementById('newNbJours').value = diffDays;
            }
        }
    </script>
</body>
</html>