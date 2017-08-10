<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

$options = $displayData['options'];
$custom_class  = (isset($options->class)) ? ' ' . $options->class : '';
$data_attr = '';
$doc = JFactory::getDocument();

// Style
$style ='';
if (isset($options->padding) && $options->padding) $style .= 'padding:'.$options->padding.';';
if (isset($options->color) && $options->color) $style .= 'color:'.$options->color.';';
if (isset($options->background) && $options->background) $style .= 'background-color:'.$options->background.';';

if($style) {
	$doc->addStyledeclaration('#column-id-' . $options->dynamicId . '{'. $style .'}');
}

// Responsive
if(isset($options->sm_col) && $options->sm_col) {
	$options->cssClassName .= ' sppb-' . $options->sm_col;
}

if(isset($options->xs_col) && $options->xs_col) {
	$options->cssClassName .= ' sppb-' . $options->xs_col;
}

// Visibility
if(isset($options->hidden_md) && $options->hidden_md) {
	$custom_class .= ' sppb-hidden-md sppb-hidden-lg';
}

if(isset($options->hidden_sm) && $options->hidden_sm) {
	$custom_class .= ' sppb-hidden-sm';
}

if(isset($options->hidden_xs) && $options->hidden_xs) {
	$custom_class .= ' sppb-hidden-xs';
}

// Animation
if(isset($options->animation) && $options->animation) {

	$custom_class .= ' sppb-wow ' . $options->animation;

	if(isset($options->animationduration) && $options->animationduration) {
		$data_attr .= ' data-sppb-wow-duration="' . $options->animationduration . 'ms"';
	}

	if(isset($options->animationdelay) && $options->animationdelay) {
		$data_attr .= ' data-sppb-wow-delay="' . $options->animationdelay . 'ms"';
	}
}

$html  = '';
$html .= '<div class="sppb-' . $options->cssClassName . '">';
$html .= '<div id="column-id-'. $options->dynamicId .'" class="sppb-addon-container' . $custom_class . '" ' . $data_attr . '>';

echo $html;
