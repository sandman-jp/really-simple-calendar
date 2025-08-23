jQuery(function($){
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
		
		$item.append('<input type="hidden" name="smjp_rs_calendar_event_exclude['+time+'][]" value="'+date+'" '+attr+'>');
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
		
		//番号振り直し
		$('tr', $list).each(function(i){
			$(this).find('.rsc-col-index').text(i);
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
		});
		
		$new_tr.attr('data-time', now);
		$tr.after($new_tr);
		
		
		let $list = $(this).closest('.rsc-table-body');
		//番号振り直し
		$('tr', $list).each(function(i){
			$(this).find('.rsc-col-index').text(i);
		});
	});
});