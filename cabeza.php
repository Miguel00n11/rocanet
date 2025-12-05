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
				<div class="menu-item dropdown d-lg-flex d-none">
					<a href="#" class="menu-link">
						<span>$1,859,050.12</span>
					</a>
				</div>
				
				
				
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
							<span class="d-block"><span>USERNAME@GMAIL.COM</span></span>
						</div>
					</a>
					<div class="dropdown-menu dropdown-menu-end me-lg-3 fs-10px fade">
						<h6 class="dropdown-header">USER OPTIONS</h6>
						<a class="dropdown-item" href="profile.html">VIEW PROFILE</a>
						<a class="dropdown-item" href="settings.html">ACCOUNT SETTINGS</a>
						<a class="dropdown-item" href="calendar.html">CALENDER SETTINGS</a>
						<div class="dropdown-divider"></div>
						<a class="dropdown-item" href="helper.html">HELP & SUPPORT</a>
						<a class="dropdown-item" href="page_login.html">LOG OUT</a>
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
							<span class="menu-icon">
								<iconify-icon icon="ph:rocket-duotone"></iconify-icon>
							</span>
							<span class="menu-text">CLIENTES</span>
						</a>
					</div>
					<div class="menu-item ">
						<a href="obras.php" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:chart-bar-duotone"></iconify-icon>
							</span>
							<span class="menu-text">OBRAS</span>
						</a>
					</div>
					<div class="menu-item ">
						<a href="cotizaciones.php" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:chart-bar-duotone"></iconify-icon>
							</span>
							<span class="menu-text">COTIZACIONES</span>
						</a>
					</div>
					<div class="menu-item ">
						<a href="cilindros.php" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:chart-bar-duotone"></iconify-icon>
							</span>
							<span class="menu-text">CILINDROS</span>
						</a>
					</div>
					
					<div class="menu-header">SISTEMAS</div>
					<div class="menu-item ">
						<a href="widgets.html" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:stack-duotone"></iconify-icon>
							</span>
							<span class="menu-text">WIDGETS</span>
						</a>
					</div>
					<div class="menu-item has-sub ">
						<a href="#" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:sparkle-duotone"></iconify-icon>
							</span>
							<span class="menu-text">Captura</span>
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item ">
								<a href="cilindros.php" class="menu-link">
									<span class="menu-text">Cilindros</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="ai_image_generator.html" class="menu-link">
									<span class="menu-text">Vigas</span>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item has-sub ">
						<a href="javascript:;" class="menu-link">
							<div class="menu-icon">
								<iconify-icon icon="ph:handbag-simple-duotone"></iconify-icon>
							</div>
							<div class="menu-text d-flex align-items-center">POS SYSTEM</div> 
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item">
								<a href="pos_customer_order.html" target="_blank" class="menu-link">
									<div class="menu-text">CUSTOMER ORDER</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="pos_kitchen_order.html" target="_blank" class="menu-link">
									<div class="menu-text">KITCHEN ORDER</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="pos_counter_checkout.html" target="_blank" class="menu-link">
									<div class="menu-text">COUNTER CHECKOUT</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="pos_table_booking.html" target="_blank" class="menu-link">
									<div class="menu-text">TABLE BOOKING</div>
								</a>
							</div>
							<div class="menu-item">
								<a href="pos_menu_stock.html" target="_blank" class="menu-link">
									<div class="menu-text">MENU STOCK</div>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item has-sub ">
						<a href="#" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:game-controller-duotone"></iconify-icon>
							</span>
							<span class="menu-text">UI KITS</span> 
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item ">
								<a href="ui_bootstrap.html" class="menu-link">
									<span class="menu-text">BOOTSTRAP</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="ui_buttons.html" class="menu-link">
									<span class="menu-text">BUTTONS</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="ui_card.html" class="menu-link">
									<span class="menu-text">CARD</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="ui_icons.html" class="menu-link">
									<span class="menu-text">ICONS</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="ui_modal_notification.html" class="menu-link">
									<span class="menu-text">MODAL & NOTIFICATION</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="ui_typography.html" class="menu-link">
									<span class="menu-text">TYPOGRAPHY</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="ui_tabs_accordions.html" class="menu-link">
									<span class="menu-text">TABS & ACCORDIONS</span>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item has-sub ">
						<a href="#" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:pencil-simple-duotone"></iconify-icon>
							</span>
							<span class="menu-text">FORMS</span> 
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item ">
								<a href="form_elements.html" class="menu-link">
									<span class="menu-text">FORM ELEMENTS</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="form_plugins.html" class="menu-link">
									<span class="menu-text">FORM PLUGINS</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="form_wizards.html" class="menu-link">
									<span class="menu-text">WIZARDS</span>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item has-sub ">
						<a href="#" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:grid-nine-duotone"></iconify-icon>
							</span>
							<span class="menu-text">TABLES</span>
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item ">
								<a href="table_elements.html" class="menu-link">
									<span class="menu-text">TABLE ELEMENTS</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="table_plugins.html" class="menu-link">
									<span class="menu-text">TABLE PLUGINS</span>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item has-sub ">
						<a href="#" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:chart-donut-duotone"></iconify-icon>
							</span>
							<span class="menu-text">CHARTS</span>
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item ">
								<a href="chart_js.html" class="menu-link">
									<span class="menu-text">CHART.JS</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="chart_apex.html" class="menu-link">
									<span class="menu-text">APEXCHARTS.JS</span>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item ">
						<a href="map.html" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:map-pin-area-duotone"></iconify-icon>
							</span>
							<span class="menu-text">MAP</span>
						</a>
					</div>
					<div class="menu-item has-sub active">
						<a href="#" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:terminal-window-duotone"></iconify-icon>
							</span>
							<span class="menu-text">LAYOUT</span>
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item active">
								<a href="layout_starter.html" class="menu-link">
									<span class="menu-text">STARTER PAGE</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="layout_fixed_footer.html" class="menu-link">
									<span class="menu-text">FIXED FOOTER</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="layout_full_height.html" class="menu-link">
									<span class="menu-text">FULL HEIGHT</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="layout_full_width.html" class="menu-link">
									<span class="menu-text">FULL WIDTH</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="layout_boxed_layout.html" class="menu-link">
									<span class="menu-text">BOXED LAYOUT</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="layout_collapsed_sidebar.html" class="menu-link">
									<span class="menu-text">COLLAPSED SIDEBAR</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="layout_top_nav.html" class="menu-link">
									<span class="menu-text">TOP NAV</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="layout_mixed_nav.html" class="menu-link">
									<span class="menu-text">MIXED NAV</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="layout_mixed_nav_boxed_layout.html" class="menu-link">
									<span class="menu-text">MIXED NAV BOXED LAYOUT</span>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-item has-sub ">
						<a href="#" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:open-ai-logo-duotone"></iconify-icon>
							</span>
							<span class="menu-text">PAGES</span>
							<span class="menu-caret"><b class="caret"></b></span>
						</a>
						<div class="menu-submenu">
							<div class="menu-item ">
								<a href="page_scrum_board.html" class="menu-link">
									<span class="menu-text">SCRUM BOARD</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="page_products.html" class="menu-link">
									<span class="menu-text">PRODUCTS</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="page_product_details.html" class="menu-link">
									<span class="menu-text">PRODUCT DETAILS</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="page_orders.html" class="menu-link">
									<span class="menu-text">ORDERS</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="page_order_details.html" class="menu-link">
									<span class="menu-text">ORDER DETAILS</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="page_gallery.html" class="menu-link">
									<span class="menu-text">GALLERY</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="page_search_results.html" class="menu-link">
									<span class="menu-text">SEARCH RESULTS</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="page_coming_soon.html" class="menu-link">
									<span class="menu-text">COMING SOON PAGE</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="page_404_error.html" class="menu-link">
									<span class="menu-text">404 ERROR PAGE</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="page_login.html" class="menu-link">
									<span class="menu-text">LOGIN</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="page_register.html" class="menu-link">
									<span class="menu-text">REGISTER</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="page_data_management.html" class="menu-link">
									<span class="menu-text">DATA MANAGEMENT</span>
								</a>
							</div>
							<div class="menu-item ">
								<a href="page_pricing.html" class="menu-link">
									<span class="menu-text">PRICING PAGE</span>
								</a>
							</div>
						</div>
					</div>
					<div class="menu-header">USER PORTAL</div>
					<div class="menu-item ">
						<a href="file_manager.html" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:folder-duotone"></iconify-icon>
							</span>
							<span class="menu-text">FILE MANAGER</span>
						</a>
					</div>
					<div class="menu-item ">
						<a href="messenger.html" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:messenger-logo-duotone"></iconify-icon>
							</span>
							<span class="menu-text">MESSENGER</span>
						</a>
					</div>
					<div class="menu-item ">
						<a href="profile.html" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:user-focus-duotone"></iconify-icon>
							</span>
							<span class="menu-text">PROFILE</span>
						</a>
					</div>
					<div class="menu-item ">
						<a href="calendar.html" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:calendar-duotone"></iconify-icon>
							</span>
							<span class="menu-text">CALENDAR</span>
						</a>
					</div>
					<div class="menu-item ">
						<a href="settings.html" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:gear-duotone"></iconify-icon>
							</span>
							<span class="menu-text">SETTINGS</span>
						</a>
					</div>
					<div class="menu-item ">
						<a href="helper.html" class="menu-link">
							<span class="menu-icon">
								<iconify-icon icon="ph:first-aid-kit-duotone"></iconify-icon>
							</span>
							<span class="menu-text">HELPER</span>
						</a>
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
		