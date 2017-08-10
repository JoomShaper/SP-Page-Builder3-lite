/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2016 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

jQuery(function($) {

	// Padding
	$(document).on('change', '.sp-pagebuilder-control-padding', function(event) {
		var padding = '';
		var $self = $(this);
		$(this).closest('.sp-pagebuilder-paddings-list').find('>div').each(function(event){
			padding += $(this).find('.sp-pagebuilder-control-padding').val() + ' ';
		});
		$self.closest('.sp-pagebuilder-paddings-list').prev().val($.trim(padding));
	});

	// Margin
	$(document).on('change', '.sp-pagebuilder-control-margin', function(event) {
		var margin = '';
		var $self = $(this);
		$(this).closest('.sp-pagebuilder-margins-list').find('>div').each(function(event){
			margin += $(this).find('.sp-pagebuilder-control-margin').val() + ' ';
		});
		$self.closest('.sp-pagebuilder-margins-list').prev().val($.trim(margin));
	});

	// Page Options
	var arrval = {};
	$.fn.openPopupAlt = function() {
		$('#page-options').addClass('sp-pagebuilder-modal-overlay-after-open');
		$('#page-options').find('.sp-pagebuilder-modal-content').addClass('sp-pagebuilder-modal-content-after-open');
		$('body').addClass('sp-pagebuilder-modal-open');

		//Store
		$('.sp-pagebuilder-modal-alt .form-group').find('>input,>textarea,>select').each(function() {
			arrval[$(this).attr('id')] = $(this).val();
		});
	};

	$.fn.closePopupAlt = function(options) {
		var settings = $.extend({
			reset: false
		}, options );

		$('#page-options').addClass('sp-pagebuilder-modal-overlay-before-close');
		$('#page-options').find('.sp-pagebuilder-modal-content').addClass('sp-pagebuilder-modal-content-before-close');
		$('#page-options').removeClass('sp-pagebuilder-modal-overlay-before-close sp-pagebuilder-modal-overlay-after-open');
		$('#page-options').find('.sp-pagebuilder-modal-content').removeClass('sp-pagebuilder-modal-content-before-close sp-pagebuilder-modal-content-after-open');
		$('body').removeClass('sp-pagebuilder-modal-open');

		if(settings.reset) {
			$('.sp-pagebuilder-modal-alt .form-group').find('>input,>textarea,>select').each(function() {
				$(this).val(arrval[$(this).attr('id')]);

				if( ($(this).attr('id') == 'jform_og_image') && (arrval[$(this).attr('id')] !='' ) )	{
					$(this).prev('.sppb-media-preview').removeClass('no-image').attr('src', pagebuilder_base + arrval[$(this).attr('id')]);
				}
			});
		}

		return this;
	};

	$('#btn-page-options').on('click', function(event) {
		event.preventDefault();
		$().openPopupAlt();
	});

	$(document).on('click', '.sp-pagebuilder-modal-alt .sp-pagebuilder-modal-content-after-open', function(event) {
		if (event.target !== this)
		return;

		$().closePopupAlt({
			reset: true
		});
	});

	$(document).on('click', '#btn-cancel-page-options', function(event) {
		event.preventDefault();
		$().closePopupAlt({
			reset: true
		});
	});

	$(document).on('click', '#btn-apply-page-options', function(event) {
		$().closePopupAlt();
	});

	// Fontawesome
	// Init dropdown
	$(document).on('click', '.sp-pagebuilder-fontawesome-icon-input', function(event) {
		event.preventDefault();
		$(this).closest('.sp-pagebuilder-fontawesome-icon-chooser').toggleClass('open');

		if($(this).closest('.sp-pagebuilder-fontawesome-icon-chooser').hasClass('open')) {
			$(this).closest('.sp-pagebuilder-fontawesome-icon-chooser').find('input[type="text"]').focus();
		}
	});

	// Select Icon
	$(document).on('click', '.sp-pagebuilder-fa-list-icon', function(event) {
		event.preventDefault();
		var $this = $(this);
		var parent = $this.closest('.sp-pagebuilder-fontawesome-icon-chooser')
		var fa_icons = $(this).closest('ul').find('>li');

		fa_icons.removeClass('active');
		$this.addClass('active');

		parent.find('.sp-pagebuilder-fontawesome-icon-input>span').html('<i class="fa '+ $this.data('fontawesome_icon') +'"></i> ' + $this.data('fontawesome_icon_name'));
		parent.find('.sp-pagebuilder-addon-input-fa').val($this.data('fontawesome_icon_name'));
		parent.addClass('sp-pagebuilder-has-fa-icon').removeClass('open');
	});

	// Search Icon
	$(document).on('keyup', '.sp-pagebuilder-fontawesome-dropdown input[type="text"]', function(){
		var value = $(this).val();
		var exp = new RegExp('.*?' + value + '.*?', 'i');

		$(this).next('.sp-pagebuilder-fontawesome-icons').children().each(function() {
			var isMatch = exp.test($('span', this).text());
			$(this).toggle(isMatch);
		});
	});

	// Remove Icon
	$(document).on('click', '.sp-pagebuilder-remove-fa-icon', function(event) {
		event.stopPropagation();
		event.preventDefault();
		var $this = $(this);
		var parent = $this.closest('.sp-pagebuilder-fontawesome-icon-chooser');

		parent.removeClass('sp-pagebuilder-has-fa-icon');
		parent.find('.sp-pagebuilder-fontawesome-icon-input>span').html('--' + Joomla.JText._('COM_SPPAGEBUILDER_ADDON_ICON_SELECT') + '--');
		parent.find('.sp-pagebuilder-fontawesome-icons>li').removeClass('active');
		parent.find('.sp-pagebuilder-addon-input-fa').val('');
	});

	/* End fontawesome */

	// Sticky Header
	if ($(".com_sppagebuilder #sp-pagebuilder-page-tools").length > 0) {
		$(window).on('scroll', function () {
			if ($(window).scrollTop() > 220) {
				$(".com_sppagebuilder #sp-pagebuilder-page-tools").addClass('fixed-sp-page-tools');
			} else {
				$(".com_sppagebuilder #sp-pagebuilder-page-tools").removeClass('fixed-sp-page-tools');
			}
		});
	}
});
