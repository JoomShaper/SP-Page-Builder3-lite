<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

$addon = $displayData['addon'];

// Visibility
$custom_class = (isset($addon->settings->hidden_md) && $addon->settings->hidden_md) ? 'sppb-hidden-md sppb-hidden-lg ' : '';
$custom_class .= (isset($addon->settings->hidden_sm) && $addon->settings->hidden_sm) ? 'sppb-hidden-sm ' : '';
$custom_class .= (isset($addon->settings->hidden_xs) && $addon->settings->hidden_xs) ? 'sppb-hidden-xs ' : '';

// Animation
$addon_attr = '';
if(isset($addon->settings->global_use_animation) && $addon->settings->global_use_animation) {
    if(isset($addon->settings->global_animation) && $addon->settings->global_animation) {
        $custom_class = 'sppb-wow ' . $addon->settings->global_animation . ' ';
        if(isset($addon->settings->global_animationduration) && $addon->settings->global_animationduration) {
            $addon_attr .= ' data-sppb-wow-duration="' . $addon->settings->global_animationduration . 'ms" ';
        }
        if(isset($addon->settings->global_animationdelay) && $addon->settings->global_animationdelay) {
            $addon_attr .= 'data-sppb-wow-delay="' . $addon->settings->global_animationdelay . 'ms" ';
        }
    }
}

$html = '<div id="sppb-addon-'. $addon->id .'" class="'. $custom_class .'clearfix" '.  $addon_attr .'>';

// Load common css
$app = JFactory::getApplication();
$layout_path = JPATH_ROOT . '/components/com_sppagebuilder/layouts';
$addon_css_layout = new JLayoutFile('addon.css', $layout_path);
$addon_css = $addon_css_layout->render(array('addon'=>$addon));

if($addon_css) {
  if($app->input->get('layout', '', 'STRING') != 'edit') {
    JFactory::getDocument()->addStyleDeclaration($addon_css);
  }
}

echo $html;
