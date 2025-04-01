jQuery(function($){
	
	function expandEvent(e){
		e.preventDefault();
		$(this).toggleClass('is-active');
	}
	
	$('.rsc-calendar-event').click(expandEvent);
});