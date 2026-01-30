<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<title>Login - RocaNet</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="assets/css/vendor.min.css" rel="stylesheet" />
	<link href="assets/css/app.min.css" rel="stylesheet" />
	<!-- ================== END core-css ================== -->
	
	<!-- Firebase -->
	<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
	<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-auth-compat.js"></script>
	
	<style>
		.logo-container {
			text-align: center;
			margin-bottom: 2rem;
		}
		
		.logo-container img {
			max-width: 250px;
			width: 100%;
			height: auto;
		}
		
		.alert {
			display: none;
			padding: 12px 16px;
			border-radius: 6px;
			margin-bottom: 1rem;
		}
		
		.alert.show {
			display: block;
		}
		
		.alert-danger {
			background-color: #f8d7da;
			color: #721c24;
			border: 1px solid #f5c6cb;
		}
		
		.alert-success {
			background-color: #d4edda;
			color: #155724;
			border: 1px solid #c3e6cb;
		}
		
		/* Asegurar que inputs y botones mantengan el estilo del template */
		.form-control {
			font-size: 14px !important;
		}
		
		.btn-theme {
			font-weight: 400;
			letter-spacing: 0.5px;
		}
		
		/* Prevenir el cambio de color cuando el navegador aplica autofill */
		input:-webkit-autofill,
		input:-webkit-autofill:hover,
		input:-webkit-autofill:focus,
		input:-webkit-autofill:active {
			-webkit-box-shadow: 0 0 0 30px var(--bs-body-bg) inset !important;
			-webkit-text-fill-color: var(--bs-body-color) !important;
			transition: background-color 5000s ease-in-out 0s;
		}
		
		/* Para navegadores Firefox */
		input:-moz-autofill,
		input:-moz-autofill-preview {
			filter: none;
		}
		
		/* Estilos para el toggle de contraseña */
		.password-toggle {
			position: absolute;
			right: 12px;
			top: 50%;
			transform: translateY(-50%);
			cursor: pointer;
			color: var(--bs-body-color);
			opacity: 0.6;
			transition: opacity 0.2s;
			z-index: 10;
			font-size: 18px;
		}
		
		.password-toggle:hover {
			opacity: 1;
		}
		
		.password-wrapper {
			position: relative;
		}
		
		.password-wrapper input {
			padding-right: 45px;
		}
	</style>
</head>
<body>
	<!-- BEGIN #loader -->
	<div id="loader" class="app-loader">
		<div class="d-flex align-items-center">
			<div class="app-loader-circle"></div>
			<div class="app-loader-text">CARGANDO...</div>
		</div>
	</div>
	<!-- END #loader -->

	<!-- BEGIN #app -->
	<div id="app" class="app app-full-height app-without-header">
		<!-- BEGIN login -->
		<div class="login">
			<!-- BEGIN login-content -->
			<div class="login-content">
				<div class="logo-container">
					<img src="assets/img/logo_roca.png" alt="Laboratorio ROCA - Control de Calidad">
				</div>
				
				<form id="loginForm" onsubmit="return false;">
					<h1 class="text-center">Iniciar Sesión</h1>
					<div class="text-body text-opacity-50 text-center mb-5">
						Sistema de Gestión de Calidad
					</div>
					
					<div id="alert" class="alert"></div>
					
					<div class="mb-4">
						<label class="form-label">Correo Electrónico</label>
						<input type="text" id="email" class="form-control form-control-lg fs-14px" value="" placeholder="usuario@ejemplo.com" />
					</div>
					<div class="mb-4">
						<label class="form-label">Contraseña</label>
					<div class="password-wrapper">
						<input type="password" id="password" class="form-control form-control-lg fs-14px" value="" placeholder="Ingrese su contraseña" />
						<span class="password-toggle" onclick="togglePassword()" id="toggleIcon">
							<iconify-icon icon="mdi:eye-outline"></iconify-icon>
						</span>
					</div>
					</div>
					<button type="submit" id="btnLogin" class="btn btn-theme btn-lg d-block w-100 mb-3" onclick="login()">
						<span id="btnText">INICIAR SESIÓN</span>
					</button>
				</form>
			</div>
			<!-- END login-content -->
		</div>
		<!-- END login -->
		
		<!-- BEGIN btn-scroll-top -->
		<a href="#" data-toggle="scroll-to-top" class="btn-scroll-top fade">
			<iconify-icon icon="material-symbols-light:keyboard-arrow-up"></iconify-icon>
		</a>
		<!-- END btn-scroll-top -->
		
		<!-- BEGIN theme-panel -->
		<div class="app-theme-panel">
			<div class="app-theme-panel-container">
				<a href="#" data-toggle="theme-panel-expand" class="app-theme-toggle-btn"><iconify-icon icon="ph:gear-duotone"></iconify-icon></a>
				<div class="app-theme-panel-content">
					<div class="fs-10px fw-semibold text-white">
						THEME COLOR
					</div>
					<div class="fs-9px lh-sm mb-2 text-white text-opacity-75">
						Choose your favorite theme color
					</div>
					<!-- BEGIN theme-list -->
					<div class="app-theme-list">
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-pink" data-theme-class="theme-pink" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Pink">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-red" data-theme-class="theme-red" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Red">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-warning" data-theme-class="theme-warning" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Orange">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-yellow" data-theme-class="theme-yellow" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Yellow">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-lime" data-theme-class="theme-lime" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Lime">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-green" data-theme-class="theme-green" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Green">&nbsp;</a></div>
						<div class="app-theme-list-item active"><a href="#" class="app-theme-list-link bg-teal" data-theme-class="" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Default">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-info" data-theme-class="theme-info"  data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Cyan">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-primary" data-theme-class="theme-primary"  data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Blue">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-indigo" data-theme-class="theme-indigo" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Indigo">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-purple" data-theme-class="theme-purple" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Purple">&nbsp;</a></div>
						<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-white" data-theme-class="theme-white" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="White">&nbsp;</a></div>
					</div>
					<!-- END theme-list -->
					<hr />
					<div class="d-flex">
						<div class="flex-1 pe-2">
							<div class="d-flex fs-10px fw-semibold text-white align-items-center">RTL MODE <span class="badge bg-theme text-theme-color ms-2 position-relative d-flex align-items-center">NEW</span></div>
							<div class="fs-9px lh-sm mb-2 text-white text-opacity-75">
								Flip the layout direction to support right-to-left languages.
							</div>
						</div>
						<div class="form-check form-switch m-0 me-n2">
							<input type="checkbox" class="form-check-input" name="app-theme-rtl" id="appThemeRtl" data-toggle="theme-rtl-selector" value="1" />
							<label class="form-check-label" for="appThemeRtl"></label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- END theme-panel -->
	</div>
	<!-- END #app -->
	
	<!-- ================== BEGIN core-js ================== -->
	<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
	<script src="assets/js/vendor.min.js"></script>
	<script src="assets/js/app.min.js"></script>
	<!-- ================== END core-js ================== -->
	
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
			toggleIcon.innerHTML = '<iconify-icon icon="mdi:eye-off-outline"></iconify-icon>';
		} else {
			passwordField.type = 'password';
			toggleIcon.innerHTML = '<iconify-icon icon="mdi:eye-outline"></iconify-icon>';
		}
	}
		function showAlert(message, type = 'danger') {
			const alert = document.getElementById('alert');
			alert.className = `alert alert-${type} show`;
			alert.textContent = message;

			setTimeout(() => {
				alert.classList.remove('show');
			}, 5000);
		}

		function setLoading(isLoading) {
			const btn = document.getElementById('btnLogin');
			const btnText = document.getElementById('btnText');
			const email = document.getElementById('email');
			const password = document.getElementById('password');

			btn.disabled = isLoading;
			email.disabled = isLoading;
			password.disabled = isLoading;

			if (isLoading) {
				btnText.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>INICIANDO SESIÓN...';
			} else {
				btnText.innerHTML = 'INICIAR SESIÓN';
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
