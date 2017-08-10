/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2016 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

jQuery(function($) {

	// Save Page
	$('#adminForm').on('submit', function(event) {
		if($('#form_task').val() != 'page.cancel') {
			event.preventDefault();
		}
	});

	$('#btn-save-page, #btn-save-copy, #btn-save-new, #btn-save-close').on('click', function(event) {
		event.preventDefault();

		var $this = $(this);
		var form = $('#adminForm');
		var task = 'page.apply';

		if(event.target.id == 'btn-save-copy') {
			task = 'page.save2copy';
		}

		$('#form_task').val(task);

		$.ajax({
			type : 'POST',
			url: 'index.php?option=com_sppagebuilder&task=page.' + task,
			data: form.serialize(),
			beforeSend: function() {
				$this.find('.fa-save').removeClass('fa-save').addClass('fa-spinner fa-spin');
			},
			success: function (response) {

				try {
					var data = $.parseJSON(response);

					$this.find('.fa').removeClass('fa-spinner fa-spin').addClass('fa-save');

					if($('.sp-pagebuilder-notifications').length === 0) {
						$('<div class="sp-pagebuilder-notifications"></div>').appendTo('body')
					}

					var msg_class = 'success';

					if(!data.status) {
						var msg_class = 'error';
					}

					if(data.title) {
						$('#jform_title').val(data.title);
					}

					if(data.id) {
						$('#jform_id').val(data.id)
					}

					$('<div class="notify-'+ msg_class +'">' + data.message + '</div>').css({
						opacity: 0,
						'margin-top': -15,
						'margin-bottom': 0
					}).animate({
						opacity: 1,
						'margin-top': 0,
						'margin-bottom': 15
					},200).prependTo('.sp-pagebuilder-notifications');

					$('.sp-pagebuilder-notifications').find('>div').each(function() {
						var $this = $(this);

						setTimeout(function(){
							$this.animate({
								opacity: 0,
								'margin-top': -15,
								'margin-bottom': 0
							}, 200, function() {
								$this.remove();
							});
						}, 3000);
					});

					if(!data.status) {
						return;
					}

					window.history.replaceState("", "", data.redirect);

					if(data.frontend_editor_url) {
						if($('#btn-page-frontend-editor').length === 0) {
							$('#btn-page-options').parent().before('<div class="sp-pagebuilder-btn-group"><a id="btn-page-frontend-editor" target="_blank" href="javascript:;" class="sp-pagebuilder-btn sp-pagebuilder-btn-info"><i class="fa fa-edit"></i> '+ Joomla.JText._('COM_SPPAGEBUILDER_FRONTEND_EDITOR') +' <small>(PRO)</small></a></div>' + "\n");
						}
					}

					if(data.preview_url) {
						if($('#btn-page-preview').length === 0) {
							$('#btn-page-options').parent().before('<div class="sp-pagebuilder-btn-group"><a id="btn-page-preview" target="_blank" href="'+ data.preview_url +'" class="sp-pagebuilder-btn sp-pagebuilder-btn-inverse"><i class="fa fa-eye"></i> ' + Joomla.JText._('COM_SPPAGEBUILDER_PREVIEW') + '</a></div>' + "\n");
						}
					}

					if(event.target.id == 'btn-save-new') {
						window.location.href= "index.php?option=com_sppagebuilder&view=page&layout=edit";
					}

					if(event.target.id == 'btn-save-close') {
						window.location.href= "index.php?option=com_sppagebuilder&view=pages";
					}

				} catch (e) {
					window.location.href= "index.php?option=com_sppagebuilder&view=pages";
				}
			}
		})
	});
});
