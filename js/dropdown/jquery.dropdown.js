/*
 * jQuery dropdown: A simple dropdown plugin
 *
 * Inspired by Bootstrap: http://twitter.github.com/bootstrap/javascript.html#dropdowns
 *
 * Copyright 2011 Cory LaViska for A Beautiful Site, LLC. (http://abeautifulsite.net/)
 *
 * Dual licensed under the MIT or GPL Version 2 licenses
 *
*/
if(jQuery) (function($) {
	
	$.extend($.fn, {
		dropdown: function(method, data) {
			switch (method) {
				case 'hide':
					hideDropdowns();
					return $(this);
				case 'attach':
					return $(this).attr('data-dropdown', data);
				case 'detach':
					hideDropdowns();
					return $(this).removeAttr('data-dropdown');
				case 'disable':
					return $(this).addClass('dropdown-disabled');
				case 'enable':
					hideDropdowns();
					return $(this).removeClass('dropdown-disabled');
			}
		}
	});
	
	function showDropdowns(event) {
		var trigger = $(this),
			dropdown = $( $(this).attr('data-dropdown') ),
			isOpen = trigger.hasClass('dropdown-open');
		
		if (event != undefined) {
			event.preventDefault();
			event.stopPropagation();
		}
		
		hideDropdowns();
		
		if (isOpen || trigger.hasClass('dropdown-disabled')) return;
		
		dropdown
			.css({
				left: dropdown.hasClass('anchor-right') ? 
					trigger.position().left - (dropdown.outerWidth() - trigger.outerWidth()) : trigger.offset().left,
				top: trigger.position().top + trigger.height()
			})
			.slideDown(100);
		
		trigger.addClass('dropdown-open');
	};
	
	function hideDropdowns(event) {
		
		var targetGroup = event ? $(event.target).parents().addBack() : null;
		if( targetGroup && targetGroup.is('.dropdown-menu') && !targetGroup.is('A') ) return;
		
		$('body')
			.find('.dropdown-menu').slideUp(100).end()
			.find('[data-dropdown]').removeClass('dropdown-open');
	};
	
	$(function () {
		$('body').on('click.dropdown', '[data-dropdown]', showDropdowns);
		$('html').on('click.dropdown', hideDropdowns);

		$(window).on('resize.dropdown', hideDropdowns);
	});
	
})(jQuery);