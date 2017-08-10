<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SppagebuilderAddonDivider extends SppagebuilderAddons {

	public function render() {

		$class 	 		= (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$divider_type	= (isset($this->addon->settings->divider_type) && $this->addon->settings->divider_type) ? $this->addon->settings->divider_type : '';

		return '<div class="sppb-divider sppb-divider-' . $divider_type . ' ' . $class . '"></div>';
	}

	public function css() {
		$addon_id = '#sppb-addon-' . $this->addon->id;

		$divider_type		= (isset($this->addon->settings->divider_type) && $this->addon->settings->divider_type) ? $this->addon->settings->divider_type : '';
		$margin_top 	 	= (isset($this->addon->settings->margin_top) && $this->addon->settings->margin_top) ? $this->addon->settings->margin_top : 30;
		$margin_bottom 	 	= (isset($this->addon->settings->margin_bottom) && $this->addon->settings->margin_bottom) ? $this->addon->settings->margin_bottom : 30;
		$border_color 	 	= (isset($this->addon->settings->border_color) && $this->addon->settings->border_color) ? $this->addon->settings->border_color : '#eeeeee';
		$border_style 	 	= (isset($this->addon->settings->border_style) && $this->addon->settings->border_style) ? $this->addon->settings->border_style : 'solid';
		$border_width 	 	= (isset($this->addon->settings->border_width) && $this->addon->settings->border_width) ? $this->addon->settings->border_width : 1;
		$divider_height 	= (isset($this->addon->settings->divider_height) && $this->addon->settings->divider_height) ? $this->addon->settings->divider_height : 10;
		$divider_image 		= (isset($this->addon->settings->divider_image) && $this->addon->settings->divider_image) ? $this->addon->settings->divider_image : '';
		$background_repeat 	= (isset($this->addon->settings->background_repeat) && $this->addon->settings->background_repeat) ? $this->addon->settings->background_repeat : 'no-repeat';

		$css = '';

		$style = '';
		$style .= ($margin_top != '') ? 'margin-top:' . (int) $margin_top  . 'px;' : '';
		$style .= ($margin_bottom != '') ? 'margin-bottom:' . (int) $margin_bottom  . 'px;' : '';

		if($style) {
			$css .= $addon_id . ' .sppb-divider {';
			$css .= $style;
			$css .= '}';
		}

		$inner_style = '';
		if($divider_type == 'border') {
			$inner_style .= $border_width ? 'border-bottom-width:' . (int) $border_width  . 'px;' : '';
			$inner_style .= ($border_style) ? 'border-bottom-style:' . $border_style  . ';' : '';
			$inner_style .= ($border_color) ? 'border-bottom-color:' . $border_color  . ';' : '';
		} else {
			$inner_style .= ($divider_height) ? 'height:' . (int) $divider_height  . 'px;' : '';
			$inner_style .= ($divider_image) ? 'background-image: url(' . JURI::base(true) . '/' . $divider_image  . ');background-repeat:' . $background_repeat . ';background-position:50% 50%;' : '';
		}

		if($inner_style) {
			$css .= $addon_id . ' .sppb-divider {';
			$css .= $inner_style;
			$css .= '}';
		}

		return $css;
	}

}
