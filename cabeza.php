<?php 
// La sesión ya se inició en auth.php
// Solo aseguramos que esté disponible
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title>ROCAnet | Control de Calidad</title>
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="" />
	<meta name="author" content="" />

	<!-- ================== BEGIN core-css ================== -->
	<link href="assets/css/vendor.min.css" rel="stylesheet" />
	<link href="assets/css/app.min.css" rel="stylesheet" />
	<!-- ================== END core-css ================== -->
	<link rel="stylesheet"
		href="https://cdn.datatables.net/2.3.6/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/2.3.6/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/columncontrol/1.2.0/css/columnControl.dataTables.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.7/css/responsive.dataTables.min.css">



</head>

<body>
	<!-- BEGIN #loader -->
	<div id="loader" class="app-loader">
		<div class="d-flex align-items-center">
			<div class="app-loader-circle"></div>
			<div class="app-loader-text">LOADING...</div>
		</div>
	</div>
	<!-- END #loader -->

	<!-- BEGIN #app -->
	<div id="app" class="app">
		<!-- BEGIN #header -->
		<div id="header" class="app-header">
			<!-- BEGIN desktop-toggler -->
			<div class="desktop-toggler">
				<button type="button" class="menu-toggler" data-toggle-class="app-sidebar-collapsed" data-dismiss-class="app-sidebar-toggled" data-toggle-target=".app">
					<span class="bar"></span>
					<span class="bar"></span>
				</button>
			</div>
			<!-- END desktop-toggler -->

			<!-- BEGIN mobile-toggler -->
			<div class="mobile-toggler">
				<button type="button" class="menu-toggler" data-toggle-class="app-sidebar-mobile-toggled" data-toggle-target=".app">
					<span class="bar"></span>
					<span class="bar"></span>
				</button>
			</div>
			<!-- END mobile-toggler -->

			<!-- BEGIN brand -->
			<div class="brand">
				<a href="index.php" class="brand-logo w-100">
					<iconify-icon icon="lets-icons:time-progress-duotone" class="fs-24px me-2 text-theme"></iconify-icon>
					<span class="brand-text fw-500 fs-14px">ROCAnet</span>
				</a>
			</div>
			<!-- END brand -->

			<!-- BEGIN menu -->
			<div class="menu">
				<div class="menu-item dropdown">
					<a href="#" data-toggle="theme-panel-expand" class="menu-link menu-link-icon">
						<iconify-icon icon="ph:gear-duotone" class="menu-icon"></iconify-icon>
					</a>
					<div class="dropdown-menu dropdown-menu-end fade">
						<h6 class="dropdown-header">Settings</h6>
						<a class="dropdown-item" href="#">General Settings</a>
						<a class="dropdown-item" href="#">System Preferences</a>
						<a class="dropdown-item" href="#">Security Settings</a>
						<a class="dropdown-item" href="#">Application Settings</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#">About</a>
						<a class="dropdown-item" href="#">Feedback</a>
					</div>
				</div>
				<div class="menu-item dropdown dropdown-mobile-full">
					<a href="#" data-bs-toggle="dropdown" data-bs-display="static" class="menu-link d-flex align-items-center">

						<div class="menu-text d-sm-block d-none">
							<span class="d-block fw-600"><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Usuario'; ?></span>
						</div>
					</a>
					<div class="dropdown-menu dropdown-menu-end me-lg-3 fs-10px fade">
						<h6 class="dropdown-header">USER OPTIONS</h6>
						<div class="dropdown-item-text" style="white-space: normal;">
							<div class="mb-1"><strong>Email:</strong> <?php echo isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : 'No disponible'; ?></div>
							<div><strong>Puesto:</strong> <?php echo isset($_SESSION['user_puesto']) ? htmlspecialchars($_SESSION['user_puesto']) : 'No asignado'; ?></div>
						</div>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>VER PERFIL</a>
						<a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>CONFIGURACIÓN</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>CERRAR SESIÓN</a>
					</div>
				</div>
			</div>
			<!-- END menu -->

			<!-- BEGIN menu-search-float -->
			<form class="menu-search-float" method="POST" name="header_search_form">
				<div class="menu-search-container">
					<div class="menu-search-icon"><i class="bi bi-search"></i></div>
					<div class="menu-search-input">
						<input type="text" class="form-control" placeholder="Search something..." />
					</div>
					<div class="menu-search-icon">
						<a href="#" data-toggle-class="app-header-menu-search-toggled" data-toggle-target=".app"><i class="bi bi-x-lg"></i></a>
					</div>
				</div>
			</form>
			<!-- END menu-search-float -->
		</div>
		<!-- END #header -->

		<!-- BEGIN #sidebar -->
		<div id="sidebar" class="app-sidebar">
			<!-- BEGIN scrollbar -->
			<div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
				<!-- BEGIN menu -->
				<div class="menu">
					<div class="menu-header">ADMINISTRACIÓN</div>
					<div class="menu-item ">
						<a href="clientes.php" class="menu-link">
							<span class="fas fa-lg fa-fw me-2 fa-address-book menu-icon">
							</span>
							<span class="menu-text">CLIENTES</span>
						</a>
					</div>
					<div class="menu-item ">
						<a href="obras.php" class="menu-link">
							<span class="fas fa-lg fa-fw me-2 fa-list menu-icon">
							</span>
							<span class="menu-text">OBRAS / EXPEDIENTES</span>
						</a>
					</div>
					<div class="menu-item ">
						<a href="cotizaciones.php" class="menu-link">
							<span class="far fa-lg fa-fw me-2 fa-file-alt menu-icon">
							</span>
							<span class="menu-text">COTIZACIONES</span>
						</a>
					</div>
					<!-- <div class="menu-item ">
						<a href="lista_reportes_cilindros_general.php" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:chart-bar-duotone"></iconify-icon>
							</span>
							<span class="menu-text">CILINDROS</span>
						</a>
					</div> -->

					<div class="menu-header">CAPTURA DIGITAL</div>

					<div class="menu-item has-sub ">
						<a href="#" class="menu-link">
							<span class="fas fa-lg fa-fw me-2 fa-tablet-alt menu-icon">
							</span>

							<span class="menu-text">CILINDROS</span>
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item ">
								<a href="lista_reportes_cilindros_validar.php" class="menu-link">
									<span class="menu-text">Validar cilindros</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="lista_reportes_cilindros_validar.php" class="menu-link">
									<span class="menu-text">Ver actulalizados</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="lista_reportes_cilindros_validar.php?tipo=Respaldo" class="menu-link">
									<span class="menu-text">Ver originales</span>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item has-sub ">
						<a href="#" class="menu-link">
							<span class="fas fa-lg fa-fw me-2 fa-tablet-alt menu-icon">
							</span>
							<span class="menu-text">VIGAS</span>
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item ">
								<a href="lista_reportes_vigas_validar.php" class="menu-link">
									<span class="menu-text">Validar vigas</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="lista_reportes_vigas_validar.php" class="menu-link">
									<span class="menu-text">Ver actulalizados</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="lista_reportes_vigas_validar.php?tipo=Respaldo" class="menu-link">
									<span class="menu-text">Ver originales</span>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item has-sub ">
						<a href="#" class="menu-link">
							<span class="fas fa-lg fa-fw me-2 fa-tablet-alt menu-icon">
							</span>
							<span class="menu-text">COMPACTACIONES</span>
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item ">
								<a href="lista_reportes_compactaciones_validar.php" class="menu-link">
									<span class="menu-text">Validar compactaciones</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="lista_reportes_compactaciones_validar.php" class="menu-link">
									<span class="menu-text">Ver actulalizados</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="lista_reportes_compactaciones_validar.php" class="menu-link">
									<span class="menu-text">Ver originales</span>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item has-sub ">
						<a href="#" class="menu-link">
							<span class="fas fa-lg fa-fw me-2 fa-tablet-alt menu-icon">
							</span>
							<span class="menu-text">MECÁNICA DE SUELOS</span>
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item ">
								<a href="lista_reportes_mecanicas_validar.php" class="menu-link">
									<span class="menu-text">Validar mecánicas</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="lista_reportes_mecanicas_actualizados.php" class="menu-link">
									<span class="menu-text">Ver actualizados</span>
								</a>
							</div>
							<div class="menu-item ">
							<a href="lista_reportes_mecanicas_respaldo.php" class="menu-link">
									<span class="menu-text">Ver originales</span>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-header">SISTEMAS</div>
					<!-- <div class="menu-item ">
						<a href="widgets.html" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:stack-duotone"></iconify-icon>
							</span>
							<span class="menu-text">WIDGETS</span>
						</a>
					</div> -->

					<div class="menu-item has-sub ">
						<a href="#" class="menu-link">
							<span class="fas fa-lg fa-fw me-2 fa-list-ol menu-icon">
							</span>
							<span class="menu-text">ITEMS</span>
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item ">
								<a href="lista_reportes_cilindros_general.php" class="menu-link">
									<span class="menu-text">Cilindros</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="lista_reportes_vigas_general.php" class="menu-link">
									<span class="menu-text">Vigas</span>
								</a>
							</div>
						</div>
					</div>

					<div class="menu-item has-sub ">
						<a href="#" class="menu-link">
							<span class="fas fa-lg fa-fw me-2 fa-road menu-icon">
							</span>
							<span class="menu-text">DISEÑO DE PAVIMENTOS</span>
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item ">
								<a href="pavimento_rigido.php" class="menu-link">
									<span class="menu-text">Diseño de pavimento rígido</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="pavimento_rigido_piedra.php" class="menu-link">
									<span class="menu-text">Diseño de pavimento rígido con piedra ahogada</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="pavimento_flexible.php" class="menu-link">
									<span class="menu-text">Pavimento flexible</span>
								</a>
							</div>
						</div>
					</div>


				</div>
				<!-- END menu -->
				<div class="mt-auto p-15px w-100">
					<a href="https://seantheme.com/quantum/documentation/" target="_blank" class="btn d-block btn-secondary btn-sm py-6px w-100">
						DOCUMENTATION
					</a>
				</div>
			</div>
			<!-- END scrollbar -->
		</div>
		<!-- END #sidebar -->

		<!-- BEGIN mobile-sidebar-backdrop -->
		<button class="app-sidebar-mobile-backdrop" data-toggle-target=".app" data-toggle-class="app-sidebar-mobile-toggled"></button>
		<!-- END mobile-sidebar-backdrop -->