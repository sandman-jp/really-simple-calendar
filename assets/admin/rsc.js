
jQuery(function($){
	
	$('.rsc-reload-button').click(function(e){
		e.preventDefault();
	});
	
	if($('.rsc-ajax-update').length){
		
		var form_val = $('.rsc-ajax-update').serialize();
		var $form = $('.rsc-ajax-update');
		
		$form.bind('update',function(){
			if($(this).serialize() != form_val){
				//if changed
				form_val = $(this).serialize();
				
				let old_form = $('.rsc-calendar-form').serializeArray();
				let new_form = [];
				
				let value_arr = {};
				
				for(let i=0; i<=old_form.length; i++){
					let input = old_form[i];
					
					if(typeof input != 'undefined'){
						if(input['name'].match(/\[\]$/)){
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
				
				for(let o in value_arr){
					new_form.push({name: o, value: value_arr[o]});
				}
				
				$('#rsc-calendar-wrap').css('opacity', .5);
				$('#rsc-calendar-message').html('');
				setTimeout(function(){
						$.ajax({
							url: ajaxurl,
							data: {
								action: 'rsc_get_calendar',
								rsc_data: new_form,
							},
							dataType: 'json',
							type: 'post',
						}).then(function(res){
							$('#rsc-calendar-message').html('<div class="updated"><p>'+RSC.CALENDAR_LOAD_SUCCESS+'</p></div>');
							$('#rsc-calendar-wrap').html(res.data);
							$('#rsc-calendar-wrap').css('opacity', 1);
						}, function(error){
							$('#rsc-calendar-message').html('<div class="error"><p>'+RSC.CALENDAR_LOAD_FAILED+'</p></div>');
							// console.log(error);
						});
				}, 100);
			}
		});
		
		$form.click(function(){
			$(this).trigger('update')
		});
		
		$('select', $form).change(function(){
			$(this).trigger('update')
		});
		$('input', $form).blur(function(){
			$(this).trigger('update')
		});
	}
	
	function toggleReadonly(e){
		
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
		
		var $target = $('input, button, select, textarea', $td);
		
		if($(this).prop('checked')){
			$target.attr('readonly', true);
		}else{
			$target.attr('readonly', false);
		}
	}
	
	//common
	$('.rsc-lock-inputs input').change(toggleReadonly);
	
	//bulk settings
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
	
	//event settings
	function removeEventColumn(e){
		e.preventDefault();
		
		if($(this).attr('readonly')){
			return;
		}
		
		let $list = $(this).parents('.rsc-table-list');
		
		let $tr = 	$(this).parents('tr:first');
		if($tr.index()){
			$tr.remove();
		}else{
			$tr.find('input').each(function(i){
				$(this).val('');
			});
		}
		
		//番号振り直し
		$list.find('tr').each(function(i){
			$(this).find('.rsc-col-index').text(i);
		});
	}
	
	$('.rsc-col-delete').click(removeEventColumn);
	
	
	$('.rsc-add-button').click(function(e){
		e.preventDefault();
		
		let $list = $(this).parents('table').find('.rsc-table-list');
		
		let $copy = $($('#event-column-template').html());
		
		$list.append($copy);
		let index = Date.now();
		
		$copy.find('.rsc-col-index').text($copy.index());
		$copy.find('input').each(function(i){
			let name = $(this).attr('name');
			name = name.replace('[x]', '['+index+']');
			$(this).attr('name', name);
		});
		
		$('.rsc-lock-inputs input', $copy).change(toggleReadonly);
		$('.rsc-col-delete', $copy).click(removeEventColumn);
		
	});
	
	/* Short code */
	$('#rsc-shortcode').bind('update', function(e){
		//if changed
		$('#rsc-calendar-wrap').css('opacity', .5);
		$('#rsc-calendar-message').html('');
		
		$.ajax({
			url: ajaxurl,
			data: {
				action: 'rsc_get_calendar_by_shortcode',
				rsc_data: $('#rsc-shortcode').val(),
			},
			dataType: 'json',
			type: 'post',
		}).then(function(res){
			$('#rsc-calendar-message').html('<div class="updated"><p>'+RSC.CALENDAR_LOAD_SUCCESS+'</p></div>');
			$('#rsc-calendar-wrap').html(res.data);
			$('#rsc-calendar-wrap').css('opacity', 1);
		}, function(error){
			$('#rsc-calendar-message').html('<div class="error"><p>'+RSC.CALENDAR_LOAD_FAILED+'</p></div>');
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
		let $inputs = $('[type="number"], [type="date"]', $('#rsd-shortcode-form')).filter(':visible');
		
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
		code = code.replace(reg, '')
		
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
	
	$('.rsc-event-class').change(function(e){
		let val = $(this).val();
		if(val != ''){
			$(this).parents('tr').find('[type="color"]').prop('disabled', true);
		}else{
			$(this).parents('tr').find('[type="color"]').prop('disabled', false);
		}
	});
	$('.rsc-event-class').change();
});