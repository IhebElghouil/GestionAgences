<?php
session_start();
require('DbConnexion.php');



// Function to sanitize input
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate CSRF token
   
    
    // Sanitize inputs
    $numcertif = sanitizeInput($_POST['numcertif']);
    $datecertif = sanitizeInput($_POST['datecertif']);
    $recipientname = sanitizeInput($_POST['statut']);
    $finvaliditecertif = sanitizeInput($_POST['finvaliditecertif']);
    $observations = sanitizeInput($_POST['observations']);
    
    // Validate required fields
    $errors = [];
    if (empty($numcertif)) $errors[] = "Certificate number is required";
    if (empty($datecertif)) $errors[] = "Certificate date is required";
    if (empty($recipientname)) $errors[] = "Recipient is required";
    if (empty($finvaliditecertif)) $errors[] = "Expiry date is required";
    
    // Check if certificate number and date already exist
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT datecertificat, numcertifcat FROM certificats WHERE datecertificat = ? AND numcertifcat = ?");
        $stmt->bind_param("ss", $datecertif, $numcertif);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $errors[] = "هذه الشهادة مسجّلة سابق بقاعدة البيانات";
        }
        $stmt->close();
    }
    
    // If no errors, proceed with insertion
    if (empty($errors)) {
        // Find the maximum expiry date for this recipient
        $stmt = $conn->prepare("SELECT MAX(DateFin) FROM certificats WHERE mecano = ?");
        $stmt->bind_param("s", $recipientname);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_row();
        $maxDate = $row[0];
        $stmt->close();
        
        // Determine if this is the latest certificate
        $isLatest = (!$maxDate || $finvaliditecertif > $maxDate);
        $etat = $isLatest ? 1 : 0;
        
        // Insert the new certificate
        $stmt = $conn->prepare("INSERT INTO certificats (mecano, datecertificat, numcertifcat, DateFin, Observation, Etat) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $recipientname, $datecertif, $numcertif, $finvaliditecertif, $observations, $etat);
        
        if ($stmt->execute()) {
            $id = $stmt->insert_id;
            
            // If this is the latest certificate, update previous ones
            if ($isLatest) {
                $updateStmt = $conn->prepare("UPDATE certificats SET Etat = 0 WHERE mecano = ? AND id <> ?");
                $updateStmt->bind_param("si", $recipientname, $id);
                $updateStmt->execute();
                $updateStmt->close();
            }
            
            $success = "تمّت إضافة اشهادة الطبيّة بنجاح تحت عدد : " . $id;
        } else {
            $errors[] = "خطأ في قاعدة البيانات: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>نظام الشهادات الطبية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 800px;
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 10px;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }
        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }
        .alert {
            border-radius: 8px;
        }
        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header text-center">
                <h2 class="mb-0">نظام إدارة الشهادات الطبية</h2>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <h5>يوجد الأخطاء التالية:</h5>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($success)): ?>
                    <div class="alert alert-success">
                        <h5>تمت العملية بنجاح</h5>
                        <p class="mb-0"><?php echo $success; ?></p>
                        <div class="mt-3">
                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-primary">إضافة شهادة جديدة</a>
                        </div>
						<button 
  type="button" 
  class="btn btn-secondary px-4" 
  onclick="window.location.href='http://192.168.1.20:8081/GestionAgences/c_certifmedtravail.php'">
  رجوع إلى القائمة
</button>
                    </div>
                <?php endif; ?>
                
				<?php if (!isset($success)): ?>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" novalidate>
                    <input type="hidden" name="token" value="<?php echo $token; ?>">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="numcertif" class="form-label">رقم الشهادة الطبية</label>
                            <input type="text" class="form-control" id="numcertif" name="numcertif" 
                                   value="<?php echo isset($_POST['numcertif']) ? htmlspecialchars($_POST['numcertif']) : ''; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="datecertif" class="form-label">تاريخ الشهادة</label>
                            <input type="date" class="form-control" id="datecertif" name="datecertif" 
                                   value="<?php echo isset($_POST['datecertif']) ? htmlspecialchars($_POST['datecertif']) : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="statut" class="form-label">إسم العون</label>
                            <input type="text" class="form-control" id="statut" name="statut" 
                                   value="<?php echo isset($_POST['statut']) ? htmlspecialchars($_POST['statut']) : ''; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="finvaliditecertif" class="form-label">تاريخ انتهاء الصلاحية</label>
                            <input type="date" class="form-control" id="finvaliditecertif" name="finvaliditecertif" 
                                   value="<?php echo isset($_POST['finvaliditecertif']) ? htmlspecialchars($_POST['finvaliditecertif']) : ''; ?>" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="observations" class="form-label">ملاحظات</label>
                        <textarea class="form-control" id="observations" name="observations" rows="3"><?php echo isset($_POST['observations']) ? htmlspecialchars($_POST['observations']) : ''; ?></textarea>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary px-4">إرسال البيانات</button>
                        <button 
  type="button" 
  class="btn btn-secondary px-4" 
  onclick="window.location.href='http://192.168.1.20:8081/GestionAgences/c_certifmedtravail.php'">
  رجوع إلى القائمة
</button>
                    </div>
                </form>
				<?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>