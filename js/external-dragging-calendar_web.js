var Script = function () {


    /* initialize the external events
     -----------------------------------------------------------------

    $('#external-events div.external-event').each(function() {

        // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
        // it doesn't need to have a start or end
        var eventObject = {
            title: $.trim($(this).text()) // use the element's text as the event title
        };

        // store the Event Object in the DOM element so we can get to it later
        $(this).data('eventObject', eventObject);

        // make the event draggable using jQuery UI
        $(this).draggable({
            zIndex: 999,
            revert: true,      // will cause the event to go back to its
            revertDuration: 0  //  original position after the drag
        });

    });


    /* initialize the calendar
     -----------------------------------------------------------------*/

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    //$('#calendar').fullCalendar({ //original
	var calendar = $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,basicWeek,basicDay'
        },

//        droppable: true, // this allows things to be dropped onto the calendar !!!
        
//		drop: function(date, allDay) { // this function is called when something is dropped

            // retrieve the dropped element's stored Event Object
//            var originalEventObject = $(this).data('eventObject');

            // we need to copy it, so that multiple events don't have a reference to the same object
//            var copiedEventObject = $.extend({}, originalEventObject);

            // assign it the date that was reported
//            copiedEventObject.start = date;
//            copiedEventObject.allDay = allDay;

            // render the event on the calendar
            // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
            //$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

            // is the "remove after drop" checkbox checked?
//            if ($('#drop-remove').is(':checked')) {
                // if so, remove the element from the "Draggable Events" list
//                $(this).remove();
//            }

//        },
		events:"http://portal.krausnaimer.com.br/events.php",
		
		//////////////////////////////////////////////////////////////// add_events
		selectable: false,
		selectHelper: true,
		select: function(start, end, allDay){
			var title = prompt('Titulo da Visita:');
			if(title){
				start = $.fullCalendar.formatDate(start, "yyyy-MM-dd HH:mm:ss");
				end = $.fullCalendar.formatDate(end, "yyyy-MM-dd HH:mm:ss");
				$.ajax({
					url: 'http://localhost/portal.krausnaimer.com.br/add_events.php',
					data: 'title='+ title+'&start='+ start +'&end='+ end,
					type: "POST",
					sucess: function(json){
						alert('OK');
					}
				});
				calendar.fullCalendar('renderEvent',
				{
					title: title,
					start: start,
					end: end,
					allDay: allDay
				},
				true //make the envent "stick"
				);
			}
			calendar.fullCalendar('unselect');
		},
		
        editable: true,
		/////////////////////////////////////////////////////////////update_events
		eventDrop: function(event, delta){
			var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
			var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
			$.ajax({
				url: 'http://localhost/portal.krausnaimer.com.br/update_events.php',
				data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,
				type: "POST",
				sucess: function(json){
					alert('OK');
				}
			});
		},
		eventResize: function(event){
			var start = $.fullCalendar.formatDate(event.start, "yyyy-MM-dd HH:mm:ss");
			var end = $.fullCalendar.formatDate(event.end, "yyyy-MM-dd HH:mm:ss");
			$.ajax({
				url: 'http://localhost/portal.krausnaimer.com.br/update_events.php',
				data: 'title='+ event.title+'&start='+ start +'&end='+ end +'&id='+ event.id ,
				type: "POST",
				sucess: function(json){
					alert('OK');
				}
			});
		},
		
		////////////////////////////////////////////////////////////////delete_events
		eventClick: function(event){
			var decision = confirm("Tem certeza?");
			if(decision){
				$.ajax({
					type: "POST",
					url: 'http://localhost/portal.krausnaimer.com.br/delete_events.php',
					data: "&id=" + event.id
				});
				$('#calendar').fullCalendar('removeEvents', event.id);
			}
		}
		
    });


}();