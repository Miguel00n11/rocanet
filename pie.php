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
					<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-info" data-theme-class="theme-info" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Cyan">&nbsp;</a></div>
					<div class="app-theme-list-item"><a href="#" class="app-theme-list-link bg-primary" data-theme-class="theme-primary" data-toggle="theme-selector" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-container="body" data-bs-title="Blue">&nbsp;</a></div>
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
	<!-- ================== BEGIN core-js ================== -->
	<script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
	<script src="assets/js/vendor.min.js"></script>
	<script src="https://cdn.datatables.net/2.3.6/js/dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/2.3.6/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.datatables.net/columncontrol/1.2.0/js/dataTables.columnControl.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/3.0.7/js/dataTables.responsive.min.js"></script>
	<script src="assets/js/app.min.js"></script>

	<!-- DataTables -->
	<!-- <script src="assets/plugins/datatables.net/js/dataTables.min.js"></script>
	<script src="assets/plugins/datatables.net-bs5/js/dataTables.bootstrap5.min.js"></script>
	<script src="assets/plugins/datatables.net-responsive/js/dataTables.responsive.min.js"></script> -->
	<script src="assets/plugins/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
	<!-- <script src="assets/plugins/datatables.net-buttons/js/dataTables.buttons.min.js"></script> -->
	<script src="assets/plugins/datatables.net-buttons/js/buttons.html5.min.js"></script>
	<script src="assets/plugins/datatables.net-buttons/js/buttons.print.min.js"></script>
	<script src="assets/plugins/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js"></script>

	<!-- ================== END core-js ================== -->
	<script>
		document.addEventListener('DOMContentLoaded', function() {

			const tableEl = document.querySelector('#datatableDefault');
			if (!tableEl) return;

			const dt = new DataTable(tableEl, {
				autoWidth: false,
				responsive: true,

				order: [
					[1, 'desc']
				],
				columnControl: [
					'order',
					['search', 'spacer', 'orderAsc', 'orderDesc', 'orderClear']
				],

				columnDefs: [{
						targets: [0, 1, 2, 3, 4, 5, 6, 12],
						columnControl: [
							'order',
							['search', 'spacer', 'orderAsc', 'orderDesc', 'orderClear']
						]
					},
					{
						targets: [13, 14],
						orderable: false,
						searchable: false,
						responsivePriority: 1,
						className: 'dt-body-center'
					}
				],

				ordering: {
					indicators: false
				},

				responsive: true,

				language: {
					search: "Buscar:",
					lengthMenu: "Mostrar _MENU_ registros",
					info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
					paginate: {
						next: "Siguiente",
						previous: "Anterior"
					}
				}
			});

			// 🔒 Evita ResizeObserver loop
			setTimeout(() => {
				dt.columns.adjust();
			}, 100);

		});
	</script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {

			const tableEl = document.querySelector('#datatableObras');

			// 🔒 Si no existe la tabla, salir
			if (!tableEl) {
				return;
			}

			// tableEl.classList.add('nowrap');

			new DataTable(tableEl, {
				// ✅ PRIMERA COLUMNA EN ORDEN DESCENDENTE
				order: [
					[0, 'desc']
				],
				columnControl: [
					'order',
					['search', 'spacer', 'orderAsc', 'orderDesc', 'orderClear']
				],

				columnDefs: [
					// 🔹 Columnas con búsqueda / orden
					{
						targets: [0, 1, 2, 3],
						columnControl: [
							'order',
							['search', 'spacer', 'orderAsc', 'orderDesc']
						]
					},

					// 🔴 Dropdowns: Editar / Nuevo
					{
						targets: [4, 5],
						orderable: false,
						searchable: false,
						responsivePriority: 1,
						className: 'dt-body-center'
					}
				],

				ordering: {
					indicators: false
				},

				responsive: true,

				language: {
					search: "Buscar:",
					lengthMenu: "Mostrar _MENU_ registros",
					info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
					paginate: {
						next: "Siguiente",
						previous: "Anterior"
					}
				}
			});

		});
	</script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {

			const tableEl = document.querySelector('#datatableClientes');

			// 🔒 Si no existe la tabla, no hacer nada
			if (!tableEl) {
				return;
			}

			// tableEl.classList.add('nowrap');

			new DataTable(tableEl, {
				// ✅ PRIMERA COLUMNA EN ORDEN DESCENDENTE
				order: [
					[0, 'desc']
				],
				columnControl: [
					'order',
					['search', 'spacer', 'orderAsc', 'orderDesc', 'orderClear']
				],

				columnDefs: [
					// 🔹 Columnas normales (datos)
					{
						targets: [0, 1, 2, 3, 4, 5, 6, 7, 8],
						columnControl: [
							'order',
							['search', 'spacer', 'orderAsc', 'orderDesc']
						]
					},

					// 🔴 Botón Seleccionar
					{
						targets: [9],
						orderable: false,
						searchable: false,
						responsivePriority: 1,
						className: 'dt-body-center'
					}
				],

				ordering: {
					indicators: false
				},

				responsive: true,

				language: {
					search: "Buscar:",
					lengthMenu: "Mostrar _MENU_ registros",
					info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
					paginate: {
						next: "Siguiente",
						previous: "Anterior"
					}
				}
			});

		});
	</script>
	<script>
		document.addEventListener('DOMContentLoaded', function() {

			const tableEl = document.querySelector('#datatableVigasGeneral');

			// 🔒 Si la tabla NO existe, salir sin error
			if (!tableEl) {
				return;
			}

			// tableEl.classList.add('nowrap');

			new DataTable(tableEl, {
				// ✅ PRIMERA COLUMNA EN ORDEN DESCENDENTE
				order: [
					[0, 'desc']
				],
				columnControl: [
					'order',
					['search', 'spacer', 'orderAsc', 'orderDesc', 'orderClear']
				],

				columnDefs: [{
						targets: [0, 1, 2, 4, 5, 12],
						columnControl: [
							'order',
							['search', 'spacer', 'orderAsc', 'orderDesc']
						]
					},
					{
						targets: [3, 17, 18],
						orderable: false,
						searchable: false,
						responsivePriority: 1,
						className: 'dt-body-center'
					}
				],

				ordering: {
					indicators: false
				},

				responsive: true,

				language: {
					search: "Buscar:",
					lengthMenu: "Mostrar _MENU_ registros",
					info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
					paginate: {
						next: "Siguiente",
						previous: "Anterior"
					}
				}
			});

		});
	</script>





	</body>

	</html>