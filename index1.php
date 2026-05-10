<?php
// Début du script PHP - aucun contenu HTML avant
require("DbConnexion.php"); 

$error_message = "";

// Démarrer la session au tout début
session_start();

// Vérifier si l'utilisateur est déjà connecté
if(isset($_SESSION['congidGA'])) {
    header('location:c_agences.php');
    exit();
}

// Traitement du formulaire
if(isset($_POST['form']) && $_POST['form'] == '1') {
    if(!empty($_POST['login']) && !empty($_POST['password'])) {
        
        $login = $_POST['login'];
        $pass = md5($_POST['password']);
        
        $req = "SELECT * FROM login WHERE login = ? AND password = ?";
        $stmt = mysqli_prepare($connection, $req);
        mysqli_stmt_bind_param($stmt, "ss", $login, $pass);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        
        if($r = mysqli_fetch_row($res)) {
            $_SESSION['congidGA'] = $r[0];
            $map = [
                12 => 8,
                15 => 'admin',
                16 => 'admin',
                17 => 'admin',
				21 => 'admin',
				22 => 'admin',
                4 => [2, 13],
				18 => [2,6,3,13],
				19 => [8,7,5],
				20 => [8,3,12],
				23 => [1,2,3,4,5,6,7,8,9,10,11,12,13],
				24 => [1,2,3,4,5,6,7,8,9,10,11,12,13],
                5 => 5,
                6 => 6,
                7 => 7,
                8 => 3,
                9 => 13,
                10 => 11,
                11 => 12,
            ];
            $departement = $map[$r[0]] ?? null;
            $add = $_SERVER['REMOTE_ADDR'];
            
            $reqs = "INSERT INTO LoginHistory (loggedas, ip) VALUES (?, ?)";
            $stmt2 = mysqli_prepare($connection, $reqs);
            mysqli_stmt_bind_param($stmt2, "ss", $login, $add);
            mysqli_stmt_execute($stmt2);
            
            $_SESSION['departement'] = $departement;
            
            header('location:c_agences.php');
            exit();
            
        } else {
            $error_message = "اسم المستخدم او كلمة السر غير صحيحة";
        }
        
    } else {
        $error_message = "يجب ادخال المعلومات التالية";
    }
}

// Si on arrive ici, c'est qu'on doit afficher le formulaire de login
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التطبيقة الداخلية للتصرف في الأعوان - الشركة الجهوية للنقل بقابس</title>
    <link rel="stylesheet" href="https://192.168.1.20:8081/GestionAgences/CSS/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1e5799 0%, #207cca 51%, #2989d8 100%);
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .header h2 {
            font-size: 1.8rem;
            font-weight: 400;
        }
        
        .login-container {
            display: flex;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            max-width: 1000px;
            width: 100%;
        }
        
        .login-image {
            flex: 1;
            background: linear-gradient(rgba(30, 87, 153, 0.7), rgba(32, 124, 202, 0.7)), url('http://192.168.1.20:8081/GestionAgences/images/bgindex.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px;
            color: white;
        }
        
        .login-image h3 {
            font-size: 1.8rem;
            margin-bottom: 20px;
        }
        
        .login-image p {
            font-size: 1.1rem;
            line-height: 1.6;
        }
        
        .login-form {
            flex: 1;
            padding: 40px;
        }
        
        .login-form h2 {
            color: #1e5799;
            margin-bottom: 30px;
            font-size: 1.8rem;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 15px 15px 15px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #1e5799;
            box-shadow: 0 0 0 2px rgba(30, 87, 153, 0.2);
            outline: none;
        }
        
        .btn {
            background: linear-gradient(to right, #1e5799, #2989d8);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .btn:hover {
            background: linear-gradient(to right, #18467e, #2070b8);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .error {
            background-color: #ffeaea;
            color: #d32f2f;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-right: 4px solid #d32f2f;
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            color: white;
            font-size: 0.9rem;
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-left: 8px;
        }
        
        .forgot-password {
            color: #1e5799;
            text-decoration: none;
        }
        
        .forgot-password:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
            }
            
            .login-image {
                display: none;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .header h2 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>الشركة الجهوية للنقل بقابس</h1>
            <h2>التطبيقة الداخلية للتصرف في الأعوان</h2>
        </div>
        
        <div class="login-container">
            <div class="login-image">
                <h3>مرحباً بك في نظام إدارة الأعوان</h3>
                <p>منصة متكاملة لإدارة أعوان الشركة الجهوية للنقل بقابس. يمكنك من خلال هذه المنصة الوصول إلى جميع البيانات والمعلومات المتعلقة بالأعوان وإدارة حساباتهم بسهولة وأمان.</p>
            </div>
            
            <div class="login-form">
                <h2>تسجيل الدخول</h2>
                
                <?php if(!empty($error_message)): ?>
                    <div class="error"><?php echo $error_message; ?></div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <input type="hidden" name="form" value="1">
                    
                    <div class="form-group">
                        <label for="login">اسم المستخدم</label>
                        <div class="input-with-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" name="login" id="login" required autocomplete="username" placeholder="أدخل اسم المستخدم" value="<?php echo isset($_POST['login']) ? htmlspecialchars($_POST['login']) : ''; ?>">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">كلمة السر</label>
                        <div class="input-with-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="password" id="password" required autocomplete="current-password" placeholder="أدخل كلمة السر">
                        </div>
                    </div>
                    
                    <div class="remember-forgot">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">تذكرني</label>
                        </div>
                        <a href="#" class="forgot-password">نسيت كلمة السر؟</a>
                    </div>
                    
                    <button type="submit" class="btn">تسجيل الدخول</button>
                </form>
            </div>
        </div>
        
        <div class="footer">
            <p>جميع الحقوق محفوظة © <?php echo date('Y'); ?> الشركة الجهوية للنقل بقابس</p>
        </div>
    </div>

    <script>
        // Simple animation for the login form
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.login-form');
            form.style.opacity = '0';
            form.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                form.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                form.style.opacity = '1';
                form.style.transform = 'translateY(0)';
            }, 300);
            
            // Add focus effects to form inputs
            const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>