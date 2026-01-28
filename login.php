<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RocaNet</title>

    <!-- CSS -->
    <link href="assets/css/vendor.min.css" rel="stylesheet" />
    <link href="assets/css/app.min.css" rel="stylesheet" />

    <!-- Firebase -->
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-auth-compat.js"></script>

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Roboto', sans-serif;
        }

        .login-container {
            max-width: 420px;
            width: 100%;
            padding: 20px;
        }

        .login-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            padding: 40px;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-logo h1 {
            color: #667eea;
            font-size: 32px;
            font-weight: 700;
            margin: 0;
            margin-bottom: 5px;
        }

        .login-logo p {
            color: #888;
            font-size: 14px;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            cursor: pointer;
            color: #888;
            font-size: 18px;
            user-select: none;
            padding: 5px;
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: #667eea;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            box-sizing: border-box;
            color: #333;
            background-color: #fff;
        }
        
        .form-control::placeholder {
            color: #999;
        }

        .form-control:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            color: #333;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }

        .alert-success {
            background-color: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }

        .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spin 0.6s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .login-footer {
            text-align: center;
            margin-top: 20px;
            color: white;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <h1>🪨 RocaNet</h1>
                <p>Sistema de Gestión de Calidad</p>
            </div>

            <div id="alert" class="alert"></div>

            <form id="loginForm" onsubmit="return false;">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" class="form-control" placeholder="usuario@ejemplo.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" class="form-control" placeholder="••••••••" required style="padding-right: 45px;">
                        <span class="password-toggle" onclick="togglePassword()" id="toggleIcon">👁</span>
                    </div>
                </div>

                <button type="submit" id="btnLogin" class="btn-login" onclick="login()">
                    Iniciar Sesión
                </button>
            </form>
        </div>

        <div class="login-footer">
            <p>&copy; 2026 RocaNet. Todos los derechos reservados.</p>
        </div>
    </div>

    <script>
        const firebaseConfig = {
            apiKey: "AIzaSyAQ9mz40-PMbjqFIPqG0gPiXT-Lmc6pJO4",
            authDomain: "registrocompactacioneroca.firebaseapp.com",
            databaseURL: "https://registrocompactacioneroca-default-rtdb.firebaseio.com",
            projectId: "registrocompactacioneroca",
            storageBucket: "registrocompactacioneroca.appspot.com",
            messagingSenderId: "519042066380",
            appId: "1:519042066380:web:e8d21cf61fc13b563f5f69",
            measurementId: "G-QVWV7C3GCP"
        };

        firebase.initializeApp(firebaseConfig);

        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.textContent = '🙈';
            } else {
                passwordField.type = 'password';
                toggleIcon.textContent = '👁';
            }
        }

        function showAlert(message, type = 'danger') {
            const alert = document.getElementById('alert');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            alert.style.display = 'block';

            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        }

        function setLoading(isLoading) {
            const btn = document.getElementById('btnLogin');
            const email = document.getElementById('email');
            const password = document.getElementById('password');

            btn.disabled = isLoading;
            email.disabled = isLoading;
            password.disabled = isLoading;

            if (isLoading) {
                btn.innerHTML = '<span class="spinner"></span> Iniciando sesión...';
            } else {
                btn.innerHTML = 'Iniciar Sesión';
            }
        }

        function login() {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;

            if (!email || !password) {
                showAlert('Por favor, completa todos los campos');
                return;
            }

            setLoading(true);

            firebase.auth().signInWithEmailAndPassword(email, password)
                .then(user => user.user.getIdToken())
                .then(token => {
                    return fetch('validar_firebase.php', {
                        method: 'POST',
                        credentials: 'include',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ token })
                    });
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'ok') {
                        showAlert('¡Inicio de sesión exitoso!', 'success');
                        setTimeout(() => {
                            window.location.href = 'index.php';
                        }, 500);
                    } else {
                        throw new Error(data.error || 'No se pudo crear la sesión');
                    }
                })
                .catch(err => {
                    setLoading(false);
                    let errorMessage = 'Error al iniciar sesión';
                    
                    if (err.code === 'auth/user-not-found') {
                        errorMessage = 'Usuario no encontrado';
                    } else if (err.code === 'auth/wrong-password') {
                        errorMessage = 'Contraseña incorrecta';
                    } else if (err.code === 'auth/invalid-email') {
                        errorMessage = 'Correo electrónico inválido';
                    } else if (err.code === 'auth/too-many-requests') {
                        errorMessage = 'Demasiados intentos. Inténtalo más tarde';
                    } else if (err.message) {
                        errorMessage = err.message;
                    }
                    
                    showAlert(errorMessage);
                });
        }

        // Permitir login con Enter
        document.getElementById('password').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                login();
            }
        });

        document.getElementById('email').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('password').focus();
            }
        });
    </script>
</body>

</html>