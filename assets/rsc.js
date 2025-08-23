jQuery(function($){
	
	function expandEvent(e){
		e.preventDefault();
		$(this).toggleClass('is-active');
		let bg = $(this).css('background-color');
		$('.rsc-triangle', this).css('border-top-color', bg);
	}
	
	$('.rsc-calendar-event').click(expandEvent);
});