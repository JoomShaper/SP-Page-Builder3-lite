<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2016 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');
JHtml::_('jquery.framework');
$doc = JFactory::getDocument();
$doc->addStylesheet( JURI::base(true) . '/components/com_sppagebuilder/assets/css/font-awesome.min.css' );
$doc->addStylesheet( JURI::base(true) . '/components/com_sppagebuilder/assets/css/sppagebuilder.css' );

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->get('id');

?>

<div class="sp-pagebuilder-admin-top"></div>

<div class="sp-pagebuilder-admin clearfix" style="position: relative;">
	<div id="j-sidebar-container" class="span2">
		<?php echo JLayoutHelper::render('brand'); ?>
		<?php echo $this->sidebar; ?>
	</div>

	<div id="j-main-container" class="span10">
		<div class="sp-pagebuilder-main-container-inner">

			<div class="sp-pagebuilder-pages-toolbar clearfix"></div>

			<div class="sp-pagebuilder-integrations clearfix">
				<ul class="sp-pagebuilder-integrations-list clearfix">
					<?php
					$list = json_decode(file_get_contents('https://www.joomshaper.com/updates/pagebuilder/integrations.json'));
					foreach ($list as $key => $item) {
						$class = "available";
						?>
							<li class="<?php echo $class; ?>" data-integration="<?php echo $key; ?>">
								<div>
									<div>
										<img src="<?php echo $item->thumb; ?>" alt="<?php echo $item->title; ?>">
										<span>
											<i class="fa fa-check-circle"></i><?php echo $item->title; ?>
											<div class="sp-pagebuilder-btns">
												<a href="https://www.joomshaper.com/page-builder" target="_blank" class="sp-pagebuilder-btn sp-pagebuilder-btn-success sp-pagebuilder-btn-sm sp-pagebuilder-btn-install">Buy Pro</a>
											</div>
										</span>
									</div>
								</div>
							</li>
						<?php
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>
