<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SppagebuilderAddonIcon extends SppagebuilderAddons {

	public function render() {

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$class .= (isset($this->addon->settings->alignment) && $this->addon->settings->alignment) ? ' ' . $this->addon->settings->alignment : '';
		$class .= (isset($this->addon->settings->hover_effect) && $this->addon->settings->hover_effect) ? ' sppb-icon-hover-effect-' . $this->addon->settings->hover_effect : '';
		$name = (isset($this->addon->settings->name) && $this->addon->settings->name) ? $this->addon->settings->name : '';

		if($name) {
			$output   = '<div class="sppb-icon' . $class . '">';
			$output  .= '<span class="sppb-icon-inner">';
			$output  .= '<i class="fa ' . $name . '"></i>';
			$output  .= '</span>';
			$output  .= '</div>';
			return $output;
		}
	}

	public function css() {
		$addon_id = '#sppb-addon-' . $this->addon->id;

		// Normal
		$icon_style  = (isset($this->addon->settings->margin) && $this->addon->settings->margin) ? 'margin: ' . $this->addon->settings->margin . ';' : '';
		$icon_style .= (isset($this->addon->settings->height) && $this->addon->settings->height) ? 'height: ' . (int) $this->addon->settings->height . 'px;' : '';
		$icon_style .= (isset($this->addon->settings->width) && $this->addon->settings->width) ? 'width: ' . (int) $this->addon->settings->width . 'px;' : '';
		$icon_style .= (isset($this->addon->settings->color) && $this->addon->settings->color) ? 'color: ' . $this->addon->settings->color . ';' : '';
		$icon_style .= (isset($this->addon->settings->background) && $this->addon->settings->background) ? 'background-color: ' . $this->addon->settings->background . ';' : '';
		$icon_style .= (isset($this->addon->settings->border_color) && $this->addon->settings->border_color) ? 'border-style: solid; border-color: ' . $this->addon->settings->border_color . ';' : '';
		$icon_style .= (isset($this->addon->settings->border_width) && $this->addon->settings->border_width) ? 'border-width: ' . (int) $this->addon->settings->border_width . 'px;' : '';
		$icon_style .= (isset($this->addon->settings->border_radius) && $this->addon->settings->border_radius) ? 'border-radius: ' . (int) $this->addon->settings->border_radius . 'px;' : '';
		$font_size = (isset($this->addon->settings->size) && $this->addon->settings->size) ? 'font-size: ' . (int) $this->addon->settings->size . 'px;' : '';
		$font_size .= (isset($this->addon->settings->height) && $this->addon->settings->height) ? 'line-height: ' . (int) $this->addon->settings->height . 'px;' : '';

		// Mouse Hover
		$icon_style_hover  = (isset($this->addon->settings->hover_color) && $this->addon->settings->hover_color) ? 'color: ' . $this->addon->settings->hover_color . ';' : '';
		$icon_style_hover .= (isset($this->addon->settings->hover_background) && $this->addon->settings->hover_background) ? 'background-color: ' . $this->addon->settings->hover_background . ';' : '';
		$icon_style_hover .= (isset($this->addon->settings->hover_border_color) && $this->addon->settings->hover_border_color) ? 'border-color: ' . $this->addon->settings->hover_border_color . ';' : '';
		$icon_style_hover .= (isset($this->addon->settings->hover_border_width) && $this->addon->settings->hover_border_width) ? 'border-width: ' . (int) $this->addon->settings->hover_border_width . 'px;' : '';
		$icon_style_hover .= (isset($this->addon->settings->hover_border_radius) && $this->addon->settings->hover_border_radius) ? 'border-radius: ' . (int) $this->addon->settings->hover_border_radius . 'px;' : '';

		$css = '';
		if($icon_style) {
			$css .= $addon_id . ' .sppb-icon-inner {';
			$css .= $icon_style;
			$css .= "\n" . '}' . "\n"	;
		}

		if($font_size) {
			$css .= $addon_id . ' .sppb-icon-inner i {';
			$css .= $font_size;
			$css .= "\n" . '}' . "\n"	;
		}

		// Hover
		if($icon_style_hover) {
			$css .= $addon_id . ' .sppb-icon-inner:hover {';
			$css .= $icon_style_hover;
			$css .= "\n" . '}' . "\n"	;
		}

		return $css;
	}
}
