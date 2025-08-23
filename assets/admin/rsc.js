function reloadCalendar(old_form, action='smjp_rsc_get_calendar'){
			
	let new_form = [];
	
	let value_arr = {};
	let _nonce_name = false;
	let _panel_name = false;
	
	for(let i=0; i<=old_form.length; i++){
		let input = old_form[i];
		
		if(typeof input != 'undefined'){
			if(input['name'] == 'smjp_rsc_preview_settings_nonce'){
				_nonce_name = input['value'];
			}else if(input['name'] == 'smjp_rsc_setting'){
				_panel_name = input['value'];
			}else if(input['name'].match(/\[\]$/)){
				let new_name = input['name'].replace(/\[\]$/, '');
				//配列のデータのするのは分けておく
				if(typeof value_arr[new_name] == 'undefined'){
					value_arr[new_name] = [];
				}
				value_arr[new_name].push(input['value']);
				
			}else{
				new_form.push(input);
			}
		}
	}
	
	if(!_nonce_name || _nonce_name == ''){
		return;
	}
	
	for(let o in value_arr){
		new_form.push({name: o, value: value_arr[o]});
	}
	let $ = jQuery;
	$('#rsc-calendar-wrap').css('opacity', .5);
	$('#rsc-calendar-message').html('');
	
	setTimeout(function(){
			$.ajax({
				url: ajaxurl,
				data: {
					action: action,
					smjp_rsc_data: new_form,
					_nonce_name: _nonce_name,
					_panel_name: _panel_name,
				},
				dataType: 'json',
				type: 'post',
			}).then(function(res){
				if(res.success){
					$('#rsc-calendar-message').html('<div class="updated"><p>'+SMJP_RSC.CALENDAR_LOAD_SUCCESS+'</p></div>');
					$('#rsc-calendar-wrap').html(res.data);
				}else{
					$('#rsc-calendar-message').html('<div class="error"><p>'+res.data+'</p></div>');
				}
				let $style = $('style', $('#rsc-calendar-wrap'));
				
				$('#smjp_rsc_css-inline-css').text($style.text());
				$style.remove();
				
				$('#rsc-calendar-wrap').css('opacity', 1);
				$('.rsc-calendar-event').click(expandEvent);
			}, function(error){
				$('#rsc-calendar-message').html('<div class="error"><p>'+SMJP_RSC.CALENDAR_LOAD_FAILED+'</p></div>');
			});
	}, 100);

	
}

function toggleReadonly(e){
	const $ = jQuery;
	
	$td = $(this).parents('tr').find('td');
	$parent = $(this).parents('td');
	if($parent){
		$td = $td.not($parent.get(0));
	}
	
	let error = 0;
	$('input, select, textarea', $td).find('[reqired]').each(function(e){
		if($(this).val() == ''){
			this.reportValidity();
			error = 1;
		}
	});
	
	if(error){
		e.preventDefault();
		$(this).prop('checked', false);
		return false;
	}
	
	var $target = $('input, button, select, textarea, .rsc-event-exclude-list', $td);
	
	if($(this).prop('checked')){
		$target.attr('readonly', true);
	}else{
		$target.attr('readonly', false);
	}
}

jQuery(function($){
	
	$('.rsc-reload-button').click(function(e){
		e.preventDefault();
	});
	
	
	if($('.rsc-ajax-update').length && $('.rsc-calendar-form').length){
		
		var form_val = $('.rsc-ajax-update').serialize();
		var $form = $('.rsc-ajax-update');
		
		$form.bind('update',function(e){
			if($(this).serialize() != form_val){
				//if changed
				form_val = $(this).serialize();
				
				let old_form = $('.rsc-calendar-form').serializeArray();
				reloadCalendar(old_form);
			}
		});
		
		$('.rsc-reload-button').click(function(){
			let old_form = $('.rsc-calendar-form').serializeArray();
			reloadCalendar(old_form);
		});
	}
	
	
	
	//common
	$('.rsc-lock-inputs input').change(toggleReadonly);
	
	//view settings
	$('.rsc-calendar-form').submit(function(e){
		$('input:hidden:not(type="hidden")').each(function(i){
			$(this).val('');
			$(this).prop('disabled', true);
		});
	});
	
	//buld settings
	$('#rsc-calendar-type').change(function(){
		$('.rsc-period-unit').hide();
		let val = $(this).val();
		$('.rsc-period-'+val).show();
	});
	$('#rsc-calendar-type').change();
	
	
});