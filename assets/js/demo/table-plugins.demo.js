/*
Template Name: QUANTUM - Responsive Bootstrap 5 Admin Template
Version: 4.0.0
Author: Sean Ngu
Website: http://www.seantheme.com/quantum/
*/

var handleRenderTableData = function() {
	var table = $('#datatableDefault').DataTable({
		dom: "<'row mb-3'<'col-sm-4'l><'col-sm-8 text-end'<'d-lg-flex justify-content-end'<'mb-3 mb-lg-0'f>B>>>t<'d-lg-flex align-items-center mt-3'<'me-auto mb-lg-0 mb-3'i><'mb-0'p>>",
    lengthMenu: [ 10, 20, 30, 40, 50 ],
		responsive: true,
		buttons: [
			{ extend: 'print', className: 'btn btn-sm ms-2' },
			{ extend: 'csv', className: 'btn btn-sm' }
		]
	});
};


/* Controller
------------------------------------------------ */
$(document).ready(function() {
	handleRenderTableData();
});