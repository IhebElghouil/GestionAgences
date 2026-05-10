<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <title>العطل الوطنية والإجازات الرسمية</title>
    
    <style>
        .calendar-container {
            position: absolute;
            z-index: 1000;
            background: white;
            border: 1px solid #ddd;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        .ds_tbl {
            border-collapse: collapse;
        }
        .ds_head {
            background-color: #f8f9fa;
            padding: 5px;
            text-align: center;
            cursor: pointer;
            border: 1px solid #dee2e6;
        }
        .ds_cell {
            padding: 5px;
            text-align: center;
            cursor: pointer;
            border: 1px solid #dee2e6;
        }
        .ds_cell:hover {
            background-color: #007bff;
            color: white;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
        }
        .error {
            color: #dc3545;
            font-size: 0.875em;
        }
        .action-buttons {
            white-space: nowrap;
        }
        .modal-header .close {
            margin: -1rem -1rem -1rem auto;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <?php
        // Database connection
        require_once(__DIR__ . "/DbConnexion.php");
        
        // Initialize variables
        $error = '';
        $success = '';
        $current_year = date('Y');
        $edit_mode = false;
        $edit_id = 0;
        $edit_data = array();
        
        // Get current year from database
        $year_result = mysqli_query($conn, "SELECT * FROM annee LIMIT 1");
        $year_data = mysqli_fetch_assoc($year_result);
        $current_year = $year_data ? $year_data['annee'] : date('Y');
        
        // Handle edit mode
        if (isset($_GET['edit'])) {
            $edit_id = intval($_GET['edit']);
            $stmt = mysqli_prepare($conn, "SELECT * FROM congenational WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $edit_id);
            mysqli_stmt_execute($stmt);
            $edit_result = mysqli_stmt_get_result($stmt);
            $edit_data = mysqli_fetch_assoc($edit_result);
            mysqli_stmt_close($stmt);
            
            if ($edit_data) {
                $edit_mode = true;
                // Format dates for input fields - إصلاح تنسيق التاريخ
                $edit_data['deb'] = trim($edit_data['deb']);
                $edit_data['fin'] = trim($edit_data['fin']);
                
                if (strlen($edit_data['deb']) >= 4) {
                    $month = substr($edit_data['deb'], 0, 2);
                    $day = substr($edit_data['deb'], 2, 2);
                 
                } else {
					$month = '0' . substr($edit_data['deb'], 0, 1);
                    $day   = substr($edit_data['deb'], 1, 2);
                   
                }
                 $edit_data['deb_display'] = $current_year . '-' . $month . '-' . $day;
				 
				 
                if (strlen($edit_data['fin']) >= 4) {
                    $month = substr($edit_data['fin'], 0, 2);
                    $day = substr($edit_data['fin'], 2, 2);
                   
                } else {
                  $month = '0' . substr($edit_data['fin'], 0, 1);
                    $day   = substr($edit_data['fin'], 1, 2);
                }
				 $edit_data['fin_display'] = $current_year . '-' . $month . '-' . $day;
            }
        }
        
        // Handle deletion
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            $delete_id = intval($_POST['delete_id']);
            $stmt = mysqli_prepare($conn, "DELETE FROM congenational WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $delete_id);
            if (mysqli_stmt_execute($stmt)) {
                $success = "تم حذف العطلة بنجاح!";
            } else {
                $error = "خطأ في حذف العطلة.";
            }
            mysqli_stmt_close($stmt);
        }
        
        // Handle form submission for adding/updating holiday
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_holiday'])) {
            $holiday_name = mysqli_real_escape_string($conn, $_POST['holiday_name']);
            $deb = mysqli_real_escape_string($conn, $_POST['deb']);
            $fin = mysqli_real_escape_string($conn, $_POST['fin']);
            $type = intval($_POST['type']);
            $edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : 0;
            
            // Validate dates
            if (empty($holiday_name)) {
                $error = "الرجاء إدخال اسم العطلة.";
            } elseif (empty($deb) && empty($fin)) {
                $error = "الرجاء اختيار تاريخ واحد على الأقل.";
            } else {
                // If only one date is provided, use it for both
                if (empty($deb)) $deb = $fin;
                if (empty($fin)) $fin = $deb;
                
                // Parse dates - إصلاح تنسيق التاريخ للتخزين
                $deb_parts = explode('-', $deb);
                $fin_parts = explode('-', $fin);
                
                if (count($deb_parts) == 3 && count($fin_parts) == 3) {
                    $start_stored = sprintf('%02d%02d', $deb_parts[1], $deb_parts[2]);
                    $end_stored = sprintf('%02d%02d', $fin_parts[1], $fin_parts[2]);
                    
                    // Validate that dates are in correct format
                    if (!checkdate($deb_parts[1], $deb_parts[2], $deb_parts[0]) || 
                        !checkdate($fin_parts[1], $fin_parts[2], $fin_parts[0])) {
                        $error = "تواريخ غير صالحة. الرجاء التحقق من صحة التواريخ.";
                    } else {
                        // Check if date already exists (excluding current edit record)
                        $check_stmt = mysqli_prepare($conn, 
                            "SELECT id FROM congenational 
                             WHERE annee = ? 
                             AND ((? BETWEEN deb AND fin) OR (? BETWEEN deb AND fin))
                             AND id != ?");
                        mysqli_stmt_bind_param($check_stmt, "sssi", 
                            $current_year, $start_stored, $end_stored, $edit_id);
                        mysqli_stmt_execute($check_stmt);
                        mysqli_stmt_store_result($check_stmt);
                        
                        if (mysqli_stmt_num_rows($check_stmt) > 0) {
                            $error = "هناك عطلة موجودة بالفعل في هذه الفترة التاريخية!";
                            mysqli_stmt_close($check_stmt);
                        } else {
                            mysqli_stmt_close($check_stmt);
                            
                            if ($edit_mode && $edit_id > 0) {
                                // Update existing record
                                $stmt = mysqli_prepare($conn, 
                                    "UPDATE congenational 
                                     SET libellet = ?, deb = ?, fin = ?, type = ? 
                                     WHERE id = ?");
                                mysqli_stmt_bind_param($stmt, "sssii", 
                                    $holiday_name, $start_stored, $end_stored, $type, $edit_id);
                                
                                if (mysqli_stmt_execute($stmt)) {
                                    $success = "تم تحديث العطلة بنجاح!";
                                    // Exit edit mode
                                    $edit_mode = false;
                                    $edit_id = 0;
                                } else {
                                    $error = "خطأ في تحديث العطلة. الرجاء المحاولة مرة أخرى.";
                                }
                            } else {
                                // Insert new record
                                $stmt = mysqli_prepare($conn, 
                                    "INSERT INTO congenational (libellet, deb, fin, annee, type) 
                                     VALUES (?, ?, ?, ?, ?)");
                                mysqli_stmt_bind_param($stmt, "ssssi", 
                                    $holiday_name, $start_stored, $end_stored, $current_year, $type);
                                
                                if (mysqli_stmt_execute($stmt)) {
                                    $success = "تمت إضافة العطلة بنجاح!";
                                } else {
                                    $error = "خطأ في إضافة العطلة. الرجاء المحاولة مرة أخرى.";
                                }
                            }
                            mysqli_stmt_close($stmt);
                            
                            if ($success) {
                                // Refresh the page to show new data
                                header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
                                exit();
                            }
                        }
                    }
                } else {
                    $error = "صيغة التاريخ غير صحيحة. استخدم الصيغة: YYYY-MM-DD";
                }
            }
        }
        
        // Function to format stored date for display
        function formatStoredDate($stored_date, $year) {
            $stored_date = trim($stored_date);
            
            // إصلاح: التعامل مع التواريخ المخزنة بشكل غير صحيح
            if (strlen($stored_date) >= 4) {
                // إذا كان التاريخ يحتوي على '/' فهو تنسيق قديم
                if (strpos($stored_date, '/') !== false) {
                    $parts = explode('/', $stored_date);
                    if (count($parts) >= 2) {
                        $month = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                        $day = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                        return $year . '/' . $month . '/' . $day;
                    }
                } 
                // إذا كان التاريخ يحتوي على '-' فهو تنسيق قديم
                else if (strpos($stored_date, '-') !== false) {
                    $parts = explode('-', $stored_date);
                    if (count($parts) >= 2) {
                        $month = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                        $day = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                        return $year . '/' . $month . '/' . $day;
                    }
                }
                // تنسيق شهريوم (MMDD)
                else if (ctype_digit($stored_date) && strlen($stored_date) >= 4) {
                    $month = substr($stored_date, 0, 2);
                    $day = substr($stored_date, 2, 2);
                    
                    // تحقق إذا كان الشهر واليوم صالحين
                    if ($month >= 1 && $month <= 12 && $day >= 1 && $day <= 31) {
                        return $year . '/' . $month . '/' . $day;
                    }
                }
            }
			else {
				if (ctype_digit($stored_date) && strlen($stored_date) < 4) {
                    $month = substr($stored_date, 0, 1);
                    $day = substr($stored_date, 1, 2);
                    
                    // تحقق إذا كان الشهر واليوم صالحين
                    if ($month >= 1 && $month <= 12 && $day >= 1 && $day <= 31) {
                        return $year . '/' . $month . '/' . $day;
                    }
                }
				
			}
            
            // إذا لم نتمكن من تحليل التاريخ، نعيده كما هو مع علامة تحذير
            return $year . '/??/?? (' . htmlspecialchars($stored_date) . ')';
        }
        ?>
        
        <!-- Success/Error Messages -->
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="إغلاق">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="إغلاق">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <!-- Add/Edit Holiday Form -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h4><?php echo $edit_mode ? 'تعديل العطلة' : 'إضافة عطلة جديدة'; ?></h4>
            </div>
            <div class="card-body">
                <form id="holidayForm" method="POST" class="form-container">
                    <?php if ($edit_mode): ?>
                        <input type="hidden" name="edit_id" value="<?php echo $edit_id; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">اسم العطلة:</label>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="holiday_name" required
                                   placeholder="أدخل اسم العطلة"
                                   value="<?php echo $edit_mode ? htmlspecialchars($edit_data['libellet']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">تاريخ البداية:</label>
                        <div class="col-md-8">
                            <input type="date" class="form-control date-picker" 
                                   name="deb" id="deb" required
                                   value="<?php echo $edit_mode ? $edit_data['deb_display'] : ''; ?>">
                            <small class="form-text text-muted">التنسيق: سنة-شهر-يوم (مثال: <?php echo $current_year; ?>-01-01)</small>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">تاريخ النهاية:</label>
                        <div class="col-md-8">
                            <input type="date" class="form-control date-picker" 
                                   name="fin" id="fin" required
                                   value="<?php echo $edit_mode ? $edit_data['fin_display'] : ''; ?>">
                            <small class="form-text text-muted">التنسيق: سنة-شهر-يوم (مثال: <?php echo $current_year; ?>-01-01)</small>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label">النوع:</label>
                        <div class="col-md-8">
                            <select class="form-control" name="type">
                                <option value="1" <?php echo ($edit_mode && $edit_data['type'] == 1) ? 'selected' : ''; ?>>ثابت (سنوي)</option>
                                <option value="2" <?php echo ($edit_mode && $edit_data['type'] == 2) ? 'selected' : ''; ?>>متغير</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" name="save_holiday" class="btn btn-primary">
                                <?php echo $edit_mode ? 'تحديث' : 'إضافة'; ?>
                            </button>
                            <?php if ($edit_mode): ?>
                                <a href="?" class="btn btn-secondary">إلغاء</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Holidays List -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4>العطل الوطنية لسنة <?php echo $current_year; ?></h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>اسم العطلة</th>
                                <th>تاريخ البداية</th>
                                <th>تاريخ النهاية</th>
                                <th>النوع</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch holidays with prepared statement
                            $stmt = mysqli_prepare($conn, 
                                "SELECT * FROM congenational WHERE annee = ? ORDER BY deb");
                            mysqli_stmt_bind_param($stmt, "s", $current_year);
                            mysqli_stmt_execute($stmt);
                            $holidays_result = mysqli_stmt_get_result($stmt);
                            
                            if (mysqli_num_rows($holidays_result) == 0): ?>
                                <tr>
                                    <td colspan="5" class="text-center">لا توجد عطل مسجلة</td>
                                </tr>
                            <?php else:
                                while ($holiday = mysqli_fetch_assoc($holidays_result)):
                                    // Format dates for display using the new function
                                    $start_display = formatStoredDate($holiday['deb'], $current_year);
                                    $end_display = formatStoredDate($holiday['fin'], $current_year);
                                    
                                    $type_text = ($holiday['type'] == 1) ? 'ثابت' : 'متغير';
                                    $type_class = ($holiday['type'] == 1) ? 'badge-success' : 'badge-warning';
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($holiday['libellet']); ?></td>
                                <td><?php echo $start_display; ?></td>
                                <td><?php echo $end_display; ?></td>
                                <td><span  <?php echo $type_class; ?>"><?php echo $type_text; ?></span></td>
                                <td class="action-buttons">
                                    <a href="?edit=<?php echo $holiday['id']; ?>" class="btn btn-sm btn-warning">
                                        تعديل
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذه العطلة؟');">
                                        <input type="hidden" name="delete_id" value="<?php echo $holiday['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            حذف
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; 
                            endif;
                            mysqli_stmt_close($stmt);
                            ?>
                        </tbody>
                    </table>
                </div>
                
                <?php
                // عرض توضيحي عن مشاكل البيانات الموجودة
                $problem_stmt = mysqli_prepare($conn, 
                    "SELECT COUNT(*) as problem_count FROM congenational 
                     WHERE annee = ? 
                     AND (LENGTH(TRIM(deb)) < 4 OR LENGTH(TRIM(fin)) < 4 
                          OR deb NOT REGEXP '^[0-9]{4}$' OR fin NOT REGEXP '^[0-9]{4}$')");
                mysqli_stmt_bind_param($problem_stmt, "s", $current_year);
                mysqli_stmt_execute($problem_stmt);
                $problem_result = mysqli_stmt_get_result($problem_stmt);
                $problem_data = mysqli_fetch_assoc($problem_result);
                mysqli_stmt_close($problem_stmt);
                
                if ($problem_data['problem_count'] > 0): ?>
                    <div class="alert alert-warning mt-3">
                        <h5>ملاحظة:</h5>
                        <p>يوجد <?php echo $problem_data['problem_count']; ?> عطلة تحتوي على تواريخ غير صحيحة في قاعدة البيانات.</p>
                        <p>قد تحتاج إلى تصحيح هذه البيانات يدوياً أو استخدام أداة لتصحيح التنسيق.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- JavaScript for Date Validation -->
    <script>
        // Date validation and conflict checking
        function validateDates() {
            const startDate = document.getElementById('deb').value;
            const endDate = document.getElementById('fin').value;
            const currentYear = <?php echo $current_year; ?>;
            
            if (!startDate && !endDate) {
                alert('الرجاء اختيار تاريخ واحد على الأقل.');
                return false;
            }
            
            if (startDate && endDate) {
                const start = new Date(startDate);
                const end = new Date(endDate);
                
                // Check if dates are in current year
              if (start.getFullYear() !== end.getFullYear()) {
        alert(`الأخطاء: التاريخين ليسوا في نفس السنة (${start.getFullYear()} - ${end.getFullYear()})`);
        return false;
    }
                
                if (end < start) {
                    alert('تاريخ النهاية يجب أن يكون بعد تاريخ البداية.');
                    return false;
                }
            }
            
            return true;
        }
        
        // Auto-fill dates if only one is provided
        document.getElementById('holidayForm')?.addEventListener('submit', function(e) {
            const startDate = document.getElementById('deb').value;
            const endDate = document.getElementById('fin').value;
            
            if (!startDate && endDate) {
                if (confirm('تم اختيار تاريخ النهاية فقط. هل تريد استخدام نفس التاريخ للبداية؟')) {
                    document.getElementById('deb').value = endDate;
                } else {
                    e.preventDefault();
                    return false;
                }
            }
            
            if (!endDate && startDate) {
                if (confirm('تم اختيار تاريخ البداية فقط. هل تريد استخدام نفس التاريخ للنهاية؟')) {
                    document.getElementById('fin').value = startDate;
                } else {
                    e.preventDefault();
                    return false;
                }
            }
            
            if (!validateDates()) {
                e.preventDefault();
            }
        });
        
        // Simple date picker (for browsers that don't support input[type="date"])
        if (!Modernizr.inputtypes.date) {
            // Fallback for older browsers
            document.querySelectorAll('.date-picker').forEach(function(input) {
                input.type = 'text';
                // Add datepicker functionality here or use jQuery UI
            });
        }
        
        // Set minimum date to current year-01-01 and maximum to current year-12-31
        document.addEventListener('DOMContentLoaded', function() {
            const currentYear = <?php echo $current_year; ?>;
            const dateInputs = document.querySelectorAll('.date-picker');
            
            dateInputs.forEach(function(input) {
                input.min = currentYear + '-01-01';
                input.max = currentYear + '-12-31';
            });
        });
    </script>
    
    <!-- Optional: Include Modernizr for feature detection -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>