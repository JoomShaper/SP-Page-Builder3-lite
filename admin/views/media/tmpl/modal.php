<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined('_JEXEC') or die ('restricted aceess');

$app = JFactory::getApplication();
$input = $app->input;
$target = $input->get('target', '', 'STRING');
$support = $input->get('support', 'image', 'STRING');
ob_start();
$layout_path = JPATH_ROOT . '/administrator/components/com_sppagebuilder/layouts';
?>
<div class="sp-pagebuilder-media-modal-overlay">
  <div id="sp-pagebuilder-media-modal" class="<?php echo (count($this->items)) ? '': 'sp-pagebuilder-media-manager-empty '; ?>clearfix" data-target="<?php echo $target; ?>" data-support="<?php echo $support; ?>">
    <div class="sp-pagebuilder-media-modal-inner">

      <div class="sp-pagebuilder-media-modal-sidebar">
        <div class="sp-pagebuilder-media-modal-sidebar-inner">
          <?php
            $brand_layout = new JLayoutFile('brand', $layout_path);
            echo $brand_layout->render();
          ?>
          <ul id="sp-pagebuilder-media-types">
            <?php
            $categories_layout = new JLayoutFile('media.categories', $layout_path);
            echo $categories_layout->render( array( 'categories'=>$this->categories ) );
            ?>
          </ul>
        </div>
      </div>

      <div class="sp-pagebuilder-media-toolbar clearfix">
				<div id="sp-pagebuilder-media-tools" class="pull-left clearfix">
					<div>
						<input type="file" id="sp-pagebuilder-media-input-file" multiple="multiple" style="display:none">
						<a href="#" id="sp-pagebuilder-upload-media" class="sp-pagebuilder-btn sp-pagebuilder-btn-success sp-pagebuilder-btn-lg"><i class="fa fa-upload"></i><span class="hidden-phone hidden-xs"> <?php echo JText::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_UPLOAD_FILES'); ?></span></a>
					</div>

					<div style="display: none;">
						<a href="#" id="sp-pagebuilder-insert-media" class="sp-pagebuilder-btn sp-pagebuilder-btn-success sp-pagebuilder-btn-lg"><i class="fa fa-check"></i> <?php echo JText::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_INSERT'); ?></a>
						<a href="#" id="sp-pagebuilder-cancel-media" class="sp-pagebuilder-btn sp-pagebuilder-btn-default sp-pagebuilder-btn-lg"><i class="fa fa-times"></i> <?php echo JText::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_CANCEL'); ?></a>
					</div>

					<div style="display: none;">
						<a href="#" id="sp-pagebuilder-media-create-folder" class="sp-pagebuilder-btn sp-pagebuilder-btn-primary sp-pagebuilder-btn-lg"><i class="fa fa-plus"></i> <?php echo JText::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_CREATE_FOLDER'); ?></a>
					</div>

					<div>
						<div class="sp-pagebuilder-media-search">
							<i class="fa fa-search"></i>
							<input type="text" class="sp-pagebuilder-form-control" id="sp-pagebuilder-media-search-input" placeholder="<?php echo JText::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_SEARCH'); ?>">
							<a href="#" class="sp-pagebuilder-clear-search" style="display: none;"><i class="fa fa-times-circle"></i></a>
						</div>
					</div>
				</div>

				<div class="pull-right hidden-phone">
					<div>
						<select id="sp-pagebuilder-media-filter" data-type="browse">
							<option value=""><?php echo JText::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_MEDIA_ALL'); ?></option>
							<?php foreach ($this->filters as $key => $this->filter) { ?>
								<option value="<?php echo $this->filter->year . '-' . $this->filter->month; ?>"><?php echo JHtml::_('date', $this->filter->year . '-' . $this->filter->month, 'F Y'); ?></option>
							<?php } ?>
						</select>
					</div>

					<div style="display: none;">
						<a href="#" id="sp-pagebuilder-delete-media" class="sp-pagebuilder-btn sp-pagebuilder-btn-danger sp-pagebuilder-btn-lg hidden-phone hidden-xs"><i class="fa fa-minus-circle"></i> <?php echo JText::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_DELETE'); ?></a>
					</div>
				</div>

        <a href="#" class="sp-pagebuilder-btn-close-modal"><i class="fa fa-times"></i></a>
			</div><!--/.page-builder-pages-toolbar-->

      <div class="sp-pagebuilder-media-modal-body">
        <div class="sp-pagebuilder-media-modal-body-inner">

          <div class="sp-pagebuilder-media-list clearfix">
    				<div class="sp-pagebuilder-media-empty">
    					<div>
    					<i class="fa fa-upload"></i>
    					<h3 class="sp-pagebuilder-media-empty-title">
    						<?php echo JText::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_DRAG_DROP_UPLOAD'); ?>
    					</h3>
    					<div>
    							<a href="#" id="sp-pagebuilder-upload-media-empty" class="sp-pagebuilder-btn sp-pagebuilder-btn-primary sp-pagebuilder-btn-lg"><?php echo JText::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_OR_SELECT'); ?></a>
    					</div>
    					</div>
    				</div>

    				<div class="sp-pagebuilder-media-wrapper">
    					<ul class="sp-pagebuilder-media clearfix">
    						<?php
    						foreach ($this->items as $key => $this->item) {
                  $format_layout = new JLayoutFile('media.format', $layout_path);
              		echo $format_layout->render( array( 'media'=>$this->item ));
    						}
    						?>
    					</ul>
    					<?php if($this->total > ($this->limit + $this->start)) { ?>
    						<div class="sp-pagebuilder-media-loadmore clearfix">
    							<a id="sp-pagebuilder-media-loadmore" class="sp-pagebuilder-btn sp-pagebuilder-btn-primary sp-pagebuilder-btn-lg" href="#"><i class="fa fa-refresh"></i> <?php echo JText::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_LOAD_MORE'); ?></a>
    						</div>
    					<?php } ?>
    				</div>
    			</div>

        </div>
      </div>

    </div>
  </div>
  <?php

  echo ob_get_clean();

  die();
