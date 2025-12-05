/*
Template Name: QUANTUM - Responsive Bootstrap 5 Admin Template
Version: 4.0.0
Author: Sean Ngu
Website: http://www.seantheme.com/quantum/
*/

var handleChatScrollBottom = function() {
  var elm = document.querySelector('.messenger-content-body [data-scrollbar="true"]');
	elm.scrollTop = elm.scrollHeight - elm.clientHeight;
	
	var elm = document.querySelector('.messenger-content-body');
	elm.classList.remove('invisible');
};

var handleMobileMessengerToggler = function() {
	$(document).on('click', '[data-toggle="messenger-content"]', function(e) {
		e.preventDefault();
		
		$('.messenger').toggleClass('messenger-content-toggled');
	});
};


$(document).ready(function() {
  handleChatScrollBottom();
  handleMobileMessengerToggler();
});