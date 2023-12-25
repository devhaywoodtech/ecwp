jQuery(document).ready(function ($) {

	let start_date = $('#ecwp_event_date_start').val() || "";
	let end_date = $('#ecwp_event_date_end').val() || "";

	if(start_date != ""){
		start_date = moment.unix($('#ecwp_event_date_start').val()).format(ECWP.JSdate);
	}
	if(end_date != ""){
		end_date = moment.unix($('#ecwp_event_date_end').val()).format(ECWP.JSdate);
	}
	
	$('input[name="ecwp_event_date_range"]').daterangepicker({
		showDropdowns 	: true,
		timePicker		: true,
		startDate		: start_date || moment().startOf('hour'),
		endDate			: end_date ||  moment().startOf('hour').add(24, 'hour'),
		locale			: { format: ECWP.JSdate },
	});

	$('input[name="ecwp_event_date_range"]').on('apply.daterangepicker', function(ev, picker) {
		$('#ecwp_event_date_start').val(picker.startDate.format(ECWP.JSdate));
		$('#ecwp_event_date_end').val(picker.endDate.format(ECWP.JSdate));

		var start = moment(picker.startDate.format(ECWP.JSdate)).format("X");
		var end = moment(picker.endDate.format(ECWP.JSdate)).format("X");
		$('#ecwp_event_date_start').val(start);
		$('#ecwp_event_date_end').val(end);

	});
	
	$('.ecwp_colors').wpColorPicker();

	//Add New Organizer
	$(document).on('click', '.ecwp_organizer_new a', function (){		
		jQuery.ajax({
			url: ECWP.ajaxurl ,
			data: {
				action: 'org_fields',
				post_id : $('#post_ID').val(),
				posttype : ECWP.posttype,
				security: ECWP.security,
				count : new Date().getTime(),
			},
			type: 'POST',
			success: function(data){
				$('.ecwp_organizer_inside').append(data);
		 	}
		});		
		$('.ecwp_organizer_inside').css("display", "flex");
	});

	//Remove Already existing from Event 
	$(document).on('click', '.ecwp_remove_org', function (){
		var id = $(this).attr("data-id");		
		$('#ecwp_org_select_'+id).slideUp("slow", function() { $('#ecwp_org_select_'+id).remove();});
	});

	//When Organizer is selected remove other fields
	$(document).on('change', '.ecwp_organizer_select_inputs .wpdropdown .ecwp_select', function (){		
		var remove_id = $(this).parent().parent().attr("data-id");
		$('#ecwp_org_other_'+remove_id).slideUp("slow", function() { $('#ecwp_org_other_'+remove_id).remove();});

	})

});
