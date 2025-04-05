function reloadCalendar(old_form, action='rsc_get_calendar'){
			
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
	let $ = jQuery;
	$('#rsc-calendar-wrap').css('opacity', .5);
	$('#rsc-calendar-message').html('');
	setTimeout(function(){
			$.ajax({
				url: ajaxurl,
				data: {
					action: action,
					rsc_data: new_form,
				},
				dataType: 'json',
				type: 'post',
			}).then(function(res){
				if(res.success){
					$('#rsc-calendar-message').html('<div class="updated"><p>'+RSC.CALENDAR_LOAD_SUCCESS+'</p></div>');
					$('#rsc-calendar-wrap').html(res.data);
				}else{
					$('#rsc-calendar-message').html('<div class="error"><p>'+res.data+'</p></div>');
				}
				$('#rsc-calendar-wrap').css('opacity', 1);
				$('.rsc-calendar-event').click(expandEvent);
			}, function(error){
				$('#rsc-calendar-message').html('<div class="error"><p>'+RSC.CALENDAR_LOAD_FAILED+'</p></div>');
			});
	}, 100);

	
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
		
		var $target = $('input, button, select, textarea, .rsc-event-exclude-list', $td);
		
		if($(this).prop('checked')){
			$target.attr('readonly', true);
		}else{
			$target.attr('readonly', false);
		}
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
	
	//event settings
	$('.rsc-event-class input').change(function(e){
		let val = $(this).val();
		if(val != ''){
			$(this).parents('tr').find('[type="color"]').prop('disabled', true);
		}else{
			$(this).parents('tr').find('[type="color"]').prop('disabled', false);
		}
	});
	
	
	
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
	
	function addExcludeDate(time, date){
		// let time = $elm.data('time');
		let $item = $('<span class="rsc-event-exclude-value"></span>');
		let $list = $('tr[data-time="'+time+'"] .rsc-event-exclude-list');
		let attr = $list.attr('disabled') ? 'disabled="disabled"' : '';
		
		$item.append('<input type="hidden" name="rs_calendar_event_exclude['+time+'][]" value="'+date+'" '+attr+'>');
		$item.append('<span class="rsc-event-exclude-date-item">'+date+'<button class="rsc-event-exclude-close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M12 13.06l3.712 3.713 1.061-1.06L13.061 12l3.712-3.712-1.06-1.06L12 10.938 8.288 7.227l-1.061 1.06L10.939 12l-3.712 3.712 1.06 1.061L12 13.061z"></path></svg></button></span>');
		$list.append($item);
		
		$('.rsc-event-exclude-close', $item).click(function(e){
			e.preventDefault();
			$(this).parents('.rsc-event-exclude-value').remove();
		});
	}
	$('.rsc-event-exclude-date').change(function(e){
		e.preventDefault();
		addExcludeDate($(this).data('time'), $(this).val());
	});
	
	if(typeof rsc_event_exclude_list != 'undefined'){
		//init
		for(let o in rsc_event_exclude_list){
			let list = rsc_event_exclude_list[o];
			
			for(let i=0; i<list.length; i++){
				addExcludeDate(o, list[i])
			}
			
		}
	}
	
	$('.rsc-col-delete').click(removeEventColumn);
	
	$('.rsc-add-button').click(function(e){
		e.preventDefault();
		
		let $list = $(this).parents('table').find('.rsc-table-body');
		
		let $copy = $($('#event-column-template').html());
		
		$list.append($copy);
		let index = Date.now();
		
		let data = $copy.attr('data-time');
		if(typeof data != 'undefined'){
			data = data.replace('x', index);
			$copy.attr('data-time', data);
		}
		
		$copy.find('.rsc-col-index').text($copy.index());
		$copy.find('input').each(function(i){
			let name = $(this).attr('name');
			if(typeof name != 'undefined'){
				name = name.replace('[x]', '['+index+']');
				$(this).attr('name', name);
			}else{
				data = $(this).attr('data-time');
				if(typeof data != 'undefined'){
					data = data.replace('x', index);
					$(this).attr('data-time', data);
				}
			}
		});
		
		$('.rsc-lock-inputs input', $copy).change(toggleReadonly);
		$('.rsc-col-delete', $copy).click(removeEventColumn);
		$('.rsc-event-exclude-date', $copy).change(function(e){
			e.preventDefault();
			addExcludeDate($(this).data('time'), $(this).val());
		});
		
		$('.rsc-table-list-wrapper').animate({scrollTop: $('.rsc-table-list').height()});
	});
	
	$('.rsc-col-copy').click(function(e){
		e.preventDefault();
		let $tr = $(this).parents('tr');
		let time = $tr.attr('data-time');
		
		let $new_tr = $tr.clone(true);
		let now = Date.now();
		
		$new_tr.find('[name]').each(function(i){
			let new_name = $(this).attr('name').replace(time, now);
			$(this).attr('name', new_name);
		})
		$new_tr.attr('data-time', now);
		$tr.after($new_tr);
		
		
		let $list = $(this).parents('.rsc-table-list');
		//番号振り直し
		$list.find('tr').each(function(i){
			$(this).find('.rsc-col-index').text(i);
		});
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
			$('#rsc-calendar-message').html('<div class="updated"><p>'+RSC.CALENDAR_LOAD_SUCCESS_SHORTCODE+'</p></div>');
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
		$('body').append('<form id="rsd-shortcode-form"></form>');
	}
});