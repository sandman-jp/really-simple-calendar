jQuery(function($){
	
	//tabs
	$('.rsc-general-setting .nav-tab[href^="#"]').click(function(e){
		e.preventDefault();
		$('.nav-tab-active').removeClass('nav-tab-active');
		$(this).addClass('nav-tab-active');
		$('.rsc-setting-panel').hide();
		let id = $(this).attr('href');
		$(id).show();
	});
	
	$('[name="save"], #publish').click(function(e){
		// e.preventDefault();
		
		let now = Date.now();
		let values = $('#post').serializeArray();
		let calendar_val = {update_time: now, data: []};
		
		for(let i=0; i<values.length; i++){
			if(values[i].name.match(/^rs_calendar/)){
				calendar_val.data.push(values[i]);
				$('[name="'+values[i].name+'"]').prop('disabled', true);
			}
		}
		
		calendar_val = JSON.stringify(calendar_val);
		
		let $elm = $('<input type="hidden" name="post_content">');
		$elm.val(calendar_val);
		$('#post').append($elm);
		
		let $elm2 = $('<input type="hidden" name="rsc_update_time" value="'+now+'">');
		$('#post').append($elm2);
	});
	
	$('#rsc-hide-general-events').click(function(e){
		//e.preventDefault();
		
		if($(this).prop('checked')){
			$('.rsc-table-list tr[disabled]').hide();
		}else{
			$('.rsc-table-list tr[disabled]').show();
		}
	});
	
	$('.rsc-reload-button').click(function(e){
		let old_form = $('#post').serializeArray();
		let form = [];
		
		for(let i=0; i<old_form.length; i++){
			let $input = $('[name="'+old_form[i].name+'"]');
			if($input.length && !$input.prop('disabled')){
				form.push(old_form[i]);
			}
		}
		
		reloadCalendar(form, 'rsc_mc_get_calendar');
	});

})