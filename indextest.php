<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Importation de Pointage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: #333;
            line-height: 1.6;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 400px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .auth-header {
            background: #2c3e50;
            color: white;
            padding: 25px;
            text-align: center;
        }

        .auth-header h1 {
            font-weight: 600;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .auth-header p {
            font-size: 14px;
            opacity: 0.8;
        }

        .auth-form {
            padding: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
        }

        .input-with-icon input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        .input-with-icon input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }

        .btn-login {
            width: 100%;
            background: #2ecc71;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #27ae60;
        }

        .btn-login:disabled {
            background-color: #95a5a6;
            cursor: not-allowed;
        }

        .auth-footer {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }

        .auth-footer p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .auth-footer a {
            color: #3498db;
            text-decoration: none;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 5px;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            max-width: 400px;
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.3s, transform 0.3s;
        }

        .notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        .notification.success {
            background-color: #2ecc71;
        }

        .notification.error {
            background-color: #e74c3c;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
            cursor: pointer;
        }

        @media (max-width: 480px) {
            .auth-container {
                max-width: 100%;
            }
            
            body {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-header">
            <h1>Importation de Pointage</h1>
            <p>Veuillez vous connecter pour accéder à l'application</p>
        </div>
        
        <div class="auth-form">
            <form id="loginForm">
                <div class="form-group">
                    <label for="username">Nom d'utilisateur</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" required autocomplete="username">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                        <span class="password-toggle" id="passwordToggle">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                </div>
                
                <button type="submit" class="btn-login" id="loginBtn">Se connecter</button>
            </form>
        </div>
        
        <div class="auth-footer">
            <p>Contactez l'administrateur en cas de problème de connexion</p>
        </div>
    </div>
    
    <div class="notification" id="notification"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('passwordToggle');
            const notification = document.getElementById('notification');
            const loginBtn = document.getElementById('loginBtn');
            
            // Fonction pour basculer la visibilité du mot de passe
            passwordToggle.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Changer l'icône
                const eyeIcon = this.querySelector('i');
                eyeIcon.classList.toggle('fa-eye');
                eyeIcon.classList.toggle('fa-eye-slash');
            });
            
            // Fonction pour afficher les notifications
            function showNotification(type, message) {
                notification.className = `notification ${type}`;
                notification.innerHTML = `
                    <i class="fas ${type === 'success' ? 'fa-check' : 'fa-exclamation-triangle'}"></i>
                    <p>${message}</p>
                `;
                notification.classList.add('show');
                
                setTimeout(() => {
                    notification.classList.remove('show');
                }, 5000);
            }
            
            // Fonction pour valider le formulaire
            loginForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const username = usernameInput.value.trim();
                const password = passwordInput.value.trim();
                
                if (!username) {
                    showNotification('error', 'Veuillez saisir votre nom d\'utilisateur');
                    usernameInput.focus();
                    return;
                }
                
                if (!password) {
                    showNotification('error', 'Veuillez saisir votre mot de passe');
                    passwordInput.focus();
                    return;
                }
                
                // Authentification avec le serveur
                await authenticateUser(username, password);
            });
            
            // Fonction pour l'authentification avec le serveur
            async function authenticateUser(username, password) {
                // Afficher un indicateur de chargement
                const originalText = loginBtn.innerHTML;
                loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion...';
                loginBtn.disabled = true;
                
                try {
                    // Envoyer les identifiants au serveur pour vérification
                    const response = await fetch('auth-login.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            username: username,
                            password: password
                        })
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        showNotification('success', 'Connexion réussie! Redirection...');
                        
                        // Stocker l'état de connexion
                        localStorage.setItem('isAuthenticated', 'true');
                        localStorage.setItem('username', username);
                        
                        // Rediriger vers l'application principale après un délai
                        setTimeout(() => {
                            window.location.href = 'Interface.php';
                        }, 1500);
                    } else {
                        showNotification('error', result.message || 'Identifiants incorrects');
                        loginBtn.innerHTML = originalText;
                        loginBtn.disabled = false;
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    showNotification('error', 'Erreur de connexion au serveur');
                    loginBtn.innerHTML = originalText;
                    loginBtn.disabled = false;
                }
            }
            
            // Vérifier si l'utilisateur est déjà connecté
            if (localStorage.getItem('isAuthenticated') === 'true') {
                window.location.href = 'Interface.php';
            }
            
            // Focus sur le champ username au chargement
            usernameInput.focus();
        });
    </script>
</body>
</html>