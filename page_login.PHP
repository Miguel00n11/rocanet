<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Quantum | Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	
	<!-- ================== BEGIN core-css ================== -->
	<link href="assets/css/vendor.min.css" rel="stylesheet" />
	<link href="assets/css/app.min.css" rel="stylesheet" />
	<!-- ================== END core-css ================== -->
	
</head>
<body >
	<!-- BEGIN #loader -->
	<div id="loader" class="app-loader">
		<div class="d-flex align-items-center">
			<div class="app-loader-circle"></div>
			<div class="app-loader-text">LOADING...</div>
		</div>
	</div>
	<!-- END #loader -->

	<!-- BEGIN #app -->
	<div id="app" class="app app-full-height app-without-header">
		<!-- BEGIN login -->
		<div class="login">
			<!-- BEGIN login-content -->
			<div class="login-content">
				<form action="index.html" method="GET" name="login_form">
					<h1 class="text-center">Sign In</h1>
					<div class="text-body text-opacity-50 text-center mb-5">
						For your protection, please verify your identity.
					</div>
					<div class="mb-4">
						<label class="form-label">Email Address</label>
						<input type="text" class="form-control form-control-lg fs-14px" value="" placeholder="username@address.com" />
					</div>
					<div class="mb-4">
						<div class="d-flex">
							<label class="form-label">Password</label>
							<a href="#" class="ms-auto text-body text-opacity-50">Forgot password?</a>
						</div>
						<input type="password" class="form-control form-control-lg fs-14px" value="" placeholder="Enter your password" />
					</div>
					<div class="mb-4">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="" id="customCheck1" />
							<label class="form-check-label fw-500" for="customCheck1">Remember me</label>
						</div>
					</div>
					<button type="submit" class="btn btn-theme btn-lg d-block w-100 mb-3">SIGN IN</button>
					<div class="text-center text-body text-opacity-50">
						Don't have an account yet? <a href="page_register.html">Sign up</a>.
					</div>
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
	
</body>
</html>
