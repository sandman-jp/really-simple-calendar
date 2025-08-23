jQuery(function($){
	
	/* Short code */
	$('#rsc-shortcode').bind('update', function(e){
		//if changed
		$('#rsc-calendar-wrap').css('opacity', .5);
		$('#rsc-calendar-message').html('');
		
		let preview_nonce = $('#smjp_rsc_preview_settings_nonce').val();
		let panel_name = $('#smjp_rsc_setting').val();
		
		$.ajax({
			url: ajaxurl,
			data: {
				action: SMJP_RSC.SMJP_RSC_GET_CALENDAR_BY_SHORTCODE,
				smjp_rsc_data: $('#rsc-shortcode').val(),
				_nonce_val: preview_nonce, 
				_nonce_key: panel_name,
			},
			dataType: 'json',
			type: 'post',
		}).then(function(res){
			$('#rsc-calendar-message').html('<div class="updated"><p>'+SMJP_RSC.CALENDAR_LOAD_SUCCESS_SHORTCODE+'</p></div>');
			$('#rsc-calendar-wrap').html(res.data);
			$('#rsc-calendar-wrap').css('opacity', 1);
		}, function(error){
			$('#rsc-calendar-message').html('<div class="error"><p>'+SMJP_RSC.CALENDAR_LOAD_FAILED+'</p></div>');
		});
		
		$('#rsd-shortcode-form').get(0).reset();
	});
	$('#rsc-shortcode').change(function(e){
		$('#rsc-shortcode').trigger('update');
	});
	
	$('.rsc-copy-button').click(function(e){
		e.preventDefault();
		let txt = $('#rsc-shortcode').val();
		navigator.clipboard.writeText(txt);
		
		$(this).find('.rsc-tooltip').show();
		setTimeout(function(){
			$('.rsc-tooltip').hide();
		}, 1000);
	});
	
	$('#rsc-shortcode-attr').change(function(e){
		//e.preventDefault();
	});
	
	$('#rsc-shortcode-attr, select.rsc-shortcode-attr').change(function(e){
		e.preventDefault();
		$('.rsc-shortcode-attr').not(this).hide();
		
		let key = $('#rsc-shortcode-attr').val();
		
		let $align = $('#rsc-shortcode-attr-type-align');
		if($('#rsc-shortcode-attr-type').val() == 'day'){
			$align.show();
		}else{
			$align.hide();
		}
		
		if($('#rsc-shortcode-attr-'+key).length){
			$('#rsc-shortcode-attr-'+key).show();
			val = $('#rsc-shortcode-attr-'+key).val();
			
			if($('#rsc-shortcode-attr-'+key+'-'+val).length){
				$('#rsc-shortcode-attr-'+key+'-'+val).show();
			}
		}
		
		let $date = $('#rsc-shortcode-attr-date');
		if($(this).val() == 'date'){
			$date.show();
		}else{
			$date.val('');
			$date.hide();
		}
		
	});
	
	$('#rsc-add-attr-button').click(function(e){
		e.preventDefault();
		
		//varidate
		let $inputs = $('[type="number"], [type="date"]', $('#rsc-shortcode-panel')).filter(':visible');
		
		let error = false;
		$inputs.each(function(i){
			if(!this.reportValidity()){
				error = true;
			}
		});
		
		if(error){
			return false;
		}
		
		let key = $('#rsc-shortcode-attr').val();
		if(key == ''){
			return false;
		}
		let val = $('#rsc-shortcode-attr-'+key).val();
		
		let code = $('#rsc-shortcode').val();
		
		//clean code
		let reg = new RegExp(' '+key+'=\".+?\"');
		code = code.replace(reg, '');
		
		$('#rsc-shortcode-attr option').each(function(i){
			if($(this).prop('disabled')){
				let k = $(this).val();
				reg = new RegExp(' '+k+'=\".+?\"');
				code = code.replace(reg, '')
			}
		});
		
		reg = new RegExp(' align=\".+?\"');
		code = code.replace(reg, '');
		
		//make
		if($('#rsc-shortcode-attr-'+key+'-'+val).filter(':visible').length){
			val = $('#rsc-shortcode-attr-'+key+'-'+val+' input').val();
		}else if(val == 'date'){
			val = $('#rsc-shortcode-attr-date input').val();
		}
		
		code = code.replace(']', ' '+key+'="'+val+'"]');
		//additional attr
		$strtof_week = $('#rsc-shortcode-attr [value="start_of_week"]');
		
		if(key == 'type' && val == 'day'){
			//remove start day of week
			reg = new RegExp(' start_of_week=\".+?\"');
			code = code.replace(reg, '');
			code = code.replace(']', ' align="'+$('#rsc-shortcode-attr-type-align').val()+'"]');
			
			$strtof_week.prop('disabled', true);
		}else{
			let locked = $strtof_week.attr('data-locked');
			if(locked == '0'){
				$strtof_week.prop('disabled', false);
			}
		}
		
		
		$('#rsc-shortcode').val(code);
		
		$('#rsc-shortcode').trigger('update');
		
		$('.rsc-shortcode-attr').hide();
		$('#rsc-shortcode-attr, .rsc-shortcode-attr, .rsc-shortcode-attr input').val('');
	});
	
	if(!$('#rsd-shortcode-form').length){
		let $form = '<form id="rsd-shortcode-form"></form>'
		$('body').append($form);
	}
});