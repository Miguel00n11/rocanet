/*
Template Name: QUANTUM - Responsive Bootstrap 5 Admin Template
Version: 4.0.0
Author: Sean Ngu
Website: http://www.seantheme.com/quantum/
*/

var handleTableHeight = function() {
  var targetHeight = window.innerHeight - document.getElementById('table').getBoundingClientRect().top;

  document.getElementById('table').style.height = targetHeight + 'px';
};

var handleCheckbox = function() {
	document.querySelectorAll('[data-toggle="check-all"]').forEach(function (checkbox) {
		checkbox.addEventListener('change', function () {
			var isChecked = this.checked;
			var table = this.closest('#table');
			var checkboxes = table.querySelectorAll('tbody input[type="checkbox"]');

			checkboxes.forEach(function (checkbox) {
				checkbox.checked = isChecked;
			});
		});
	});
};

/* Controller
------------------------------------------------ */
$(document).ready(function() {
  handleTableHeight();
  handleCheckbox();

  window.addEventListener('resize', function () {
    handleTableHeight();
  });
});