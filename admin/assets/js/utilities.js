/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2016 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
jQuery(function($) {
	// Sticky Media toolbar
	if ($(".sp-pagebuilder-media-toolbar").length > 0) {
		$(window).on('scroll', function () {
			if ($(window).scrollTop() > 220) {
				$(".sp-pagebuilder-media-toolbar").addClass('fixed');
			} else {
				$(".sp-pagebuilder-media-toolbar").removeClass('fixed');
			}
		});
	}
});
