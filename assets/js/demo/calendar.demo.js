/*
Template Name: QUANTUM - Responsive Bootstrap 5 Admin Template
Version: 4.0.0
Author: Sean Ngu
Website: http://www.seantheme.com/quantum/
*/

var handleRenderFullcalendar = function() {
	// external events
	var containerEl = document.getElementById('external-events');
  var Draggable = FullCalendar.Interaction.Draggable;
	new Draggable(containerEl, {
    itemSelector: '.fc-event-link',
    eventData: function(eventEl) {
      return {
        title: eventEl.innerText,
        color: eventEl.getAttribute('data-color')
      };
    }
  });
  
  // fullcalendar
  var d = new Date();
	var month = d.getMonth() + 1;
	    month = (month < 10) ? '0' + month : month;
	var year = d.getFullYear();
	var day = d.getDate();
	var today = moment().startOf('day');
	var calendarElm = document.getElementById('calendar');
	var calendar = new FullCalendar.Calendar(calendarElm, {
    headerToolbar: {
      left: 'today prev,next title',
      center: '',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    buttonText: {
    	today:    'Today',
			month:    'Month',
			week:     'Week',
			day:      'Day'
    },
    initialView: 'dayGridMonth',
    editable: true,
    droppable: true,
  	themeSystem: 'bootstrap',
		views: {
			timeGrid: {
				eventLimit: 6 // adjust to 6 only for timeGridWeek/timeGridDay
			}
		},
  	events: [{
			title: 'Trip to London',
			start: year + '-'+ month +'-01',
			end: year + '-'+ month +'-05',
			color: 'rgba('+ app.color.themeRgb + ', .35)'
		},{
			title: 'Meet with Sean Ngu',
			start: year + '-'+ month +'-02T06:00:00',
			color: 'rgba('+ app.color.primaryRgb + ', 1)'
		},{
			title: 'Mobile Apps Brainstorming',
			start: year + '-'+ month +'-10',
			end: year + '-'+ month +'-12',
			color: 'rgba('+ app.color.pinkRgb + ', .35)'
		},{
			title: 'Stonehenge, Windsor Castle, Oxford',
			start: year + '-'+ month +'-05T08:45:00',
			end: year + '-'+ month +'-06T18:00',
			color: 'rgba('+ app.color.indigoRgb + ', .35)'
		},{
			title: 'Paris Trip',
			start: year + '-'+ month +'-12',
			end: year + '-'+ month +'-16'
		},{
			title: 'Domain name due',
			start: year + '-'+ month +'-15',
			end: year + '-'+ month +'-15',
			color: 'rgba('+ app.color.primaryRgb + ', .35)'
		},{
			title: 'Cambridge Trip',
			start: year + '-'+ month +'-19',
			end: year + '-'+ month +'-19'
		},{
			title: 'Visit Apple Company',
			start: year + '-'+ month +'-21T05:00:00',
			color: 'rgba('+ app.color.greenRgb + ', 1)'
		},{
			title: 'Exercise Class',
			start: year + '-'+ month +'-21T07:30:00',
			color: 'rgba('+ app.color.warningRgb + ', 1)'
		},{
			title: 'Live Recording',
			start: year + '-'+ month +'-22T03:00:00',
			color: 'rgba('+ app.color.primaryRgb + ', 1)'
		},{
			title: 'Announcement',
			start: year + '-'+ month +'-22T15:00:00',
			color: 'rgba('+ app.color.dangerRgb + ', 1)'
		},{
			title: 'Dinner',
			start: year + '-'+ month +'-23T18:00:00'
		},{
			title: 'New Android App Discussion',
			start: year + '-'+ month +'-25T08:00:00',
			end: year + '-'+ month +'-25T10:00:00',
			color: 'rgba('+ app.color.dangerRgb + ', 1)'
		},{
			title: 'Marketing Plan Presentation',
			start: year + '-'+ month +'-25T12:00:00',
			end: year + '-'+ month +'-25T14:00:00',
			color: 'rgba('+ app.color.primaryRgb + ', 1)'
		},{
			title: 'Chase due',
			start: year + '-'+ month +'-26T12:00:00',
			color: 'rgba('+ app.color.warningRgb + ', 1)'
		},{
			title: 'Heartguard',
			start: year + '-'+ month +'-26T08:00:00',
			color: 'rgba('+ app.color.warningRgb + ', 1)'
		},{
			title: 'Lunch with Richard',
			start: year + '-'+ month +'-28T14:00:00',
			color: 'rgba('+ app.color.primaryRgb + ', 1)'
		},{
			title: 'Web Hosting due',
			start: year + '-'+ month +'-30',
			color: 'rgba('+ app.color.primaryRgb + ', .35)'
		}]
	});
	
	calendar.render();
};


var handleInlineDatepicker = function() {
	$('[data-render="inline-datepicker"]').datepicker();
};


/* Controller
------------------------------------------------ */
$(document).ready(function() {
	handleRenderFullcalendar();
	handleInlineDatepicker();
});