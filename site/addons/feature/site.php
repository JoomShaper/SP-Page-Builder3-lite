<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SppagebuilderAddonFeature extends SppagebuilderAddons {

	public function render() {

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$title = (isset($this->addon->settings->title) && $this->addon->settings->title) ? $this->addon->settings->title : '';
		$heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : 'h3';

		//Options
		$title_url = (isset($this->addon->settings->title_url) && $this->addon->settings->title_url) ? $this->addon->settings->title_url : '';
		$url_appear = (isset($this->addon->settings->url_appear) && $this->addon->settings->url_appear) ? $this->addon->settings->url_appear : 'title';
		$title_position = (isset($this->addon->settings->title_position) && $this->addon->settings->title_position) ? $this->addon->settings->title_position : 'before';
		$feature_type = (isset($this->addon->settings->feature_type) && $this->addon->settings->feature_type) ? $this->addon->settings->feature_type : 'icon';
		$feature_image = (isset($this->addon->settings->feature_image) && $this->addon->settings->feature_image) ? $this->addon->settings->feature_image : '';
		$icon_name = (isset($this->addon->settings->icon_name) && $this->addon->settings->icon_name) ? $this->addon->settings->icon_name : '';
		$text = (isset($this->addon->settings->text) && $this->addon->settings->text) ? $this->addon->settings->text : '';
		$alignment = (isset($this->addon->settings->alignment) && $this->addon->settings->alignment) ? $this->addon->settings->alignment : '';

		//Image or icon position
		if($title_position == 'before') {
			$icon_image_position = 'after';
		} else if($title_position == 'after') {
			$icon_image_position = 'before';
		} else {
			$icon_image_position = $title_position;
		}

		//Reset Alignment for left and right style
		if( ($icon_image_position=='left') || ($icon_image_position=='right') ) {
			$alignment = 'sppb-text-' . $icon_image_position;
		}

		//Icon or Image
		$media = '';
		if($feature_type == 'icon') {
			if($icon_name) {
				$media  .= '<div class="sppb-icon">';
					if( ($title_url && $url_appear == 'icon') || ($title_url && $url_appear == 'both' ) ) $media .= '<a href="'. $title_url .'">';
						$media  .= '<span class="sppb-icon-container">';
						$media  .= '<i class="fa ' . $icon_name . '"></i>';
						$media  .= '</span>';
					if(($title_url && $url_appear == 'icon') || ($title_url && $url_appear == 'both' )) $media .= '</a>';
				$media  .= '</div>';
			}
		} else {
			if($feature_image) {
				$media  .= '<span class="sppb-img-container">';
				$media  .= '<img class="sppb-img-responsive" src="' . $feature_image . '" alt="'.$title.'">';
				$media  .= '</span>';
			}
		}

		//Title
		$feature_title = '';
		if($title) {
			$heading_class = '';
			if( ($icon_image_position=='left') || ($icon_image_position=='right') ) {
				$heading_class = ' sppb-media-heading';
			}

			if( ($title_url && $url_appear == 'title') || ($title_url && $url_appear == 'both' ) ) $feature_title .= '<a href="'. $title_url .'">';
			$feature_title .= '<'.$heading_selector.' class="sppb-addon-title sppb-feature-box-title'. $heading_class .'">' . $title . '</'.$heading_selector.'>';
			if(($title_url && $url_appear == 'title') || ($title_url && $url_appear == 'both' )) $feature_title .= '</a>';
		}

		//Feature Text
		$feature_text  = '<div class="sppb-addon-text">';
		$feature_text .= $text;
		$feature_text .= '</div>';

		//Output
		$output  = '<div class="sppb-addon sppb-addon-feature ' . $alignment . ' ' . $class . '">';
		$output .= '<div class="sppb-addon-content">';

		if ($icon_image_position == 'before') {
			$output .= ($media) ? $media : '';
			$output .= ($title) ? $feature_title : '';
			$output .= $feature_text;
		} else if ($icon_image_position == 'after') {
			$output .= ($title) ? $feature_title : '';
			$output .= ($media) ? $media : '';
			$output .= $feature_text;
		} else {
			if($media) {
				$output .= '<div class="sppb-media">';
				$output .= '<div class="pull-'. $icon_image_position .'">';
				$output .= $media;
				$output .= '</div>';
				$output .= '<div class="sppb-media-body">';
				$output .= ($title) ? $feature_title : '';
				$output .= $feature_text;
				$output .= '</div>';
				$output .= '</div>';
			}
		}

		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	public function css() {
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$icon_color	= (isset($this->addon->settings->icon_color) && $this->addon->settings->icon_color) ? $this->addon->settings->icon_color : '';
		$icon_size = (isset($this->addon->settings->icon_size) && $this->addon->settings->icon_size) ? $this->addon->settings->icon_size : '';
		$icon_border_color = (isset($this->addon->settings->icon_border_color) && $this->addon->settings->icon_border_color) ? $this->addon->settings->icon_border_color : '';
		$icon_border_width = (isset($this->addon->settings->icon_border_width) && $this->addon->settings->icon_border_width) ? $this->addon->settings->icon_border_width : '';
		$icon_border_radius	= (isset($this->addon->settings->icon_border_radius) && $this->addon->settings->icon_border_radius) ? $this->addon->settings->icon_border_radius : '';
		$icon_style	= (isset($this->addon->settings->icon_style) && $this->addon->settings->icon_style) ? $this->addon->settings->icon_style : '';
		$icon_background = (isset($this->addon->settings->icon_background) && $this->addon->settings->icon_background) ? $this->addon->settings->icon_background : '';
		$icon_margin_top = (isset($this->addon->settings->icon_margin_top) && $this->addon->settings->icon_margin_top) ? $this->addon->settings->icon_margin_top : '';
		$icon_margin_bottom	= (isset($this->addon->settings->icon_margin_bottom) && $this->addon->settings->icon_margin_bottom) ? $this->addon->settings->icon_margin_bottom : '';
		$icon_padding = (isset($this->addon->settings->icon_padding) && $this->addon->settings->icon_padding) ? $this->addon->settings->icon_padding : '';
		$feature_type = (isset($this->addon->settings->feature_type) && $this->addon->settings->feature_type) ? $this->addon->settings->feature_type : 'icon';
		$feature_image = (isset($this->addon->settings->feature_image) && $this->addon->settings->feature_image) ? $this->addon->settings->feature_image : '';
		$icon_name = (isset($this->addon->settings->icon_name) && $this->addon->settings->icon_name) ? $this->addon->settings->icon_name : '';

		$css = '';
		if($feature_type == 'icon') {
			if($icon_name) {
				$style = 'display:inline-block;text-align:center;';
				$style .= ($icon_margin_top) ? 'margin-top:' . (int) $icon_margin_top . 'px;' : '';
				$style .= ($icon_margin_bottom) ? 'margin-bottom:' . (int) $icon_margin_bottom . 'px;' : '';
				$style .= ($icon_padding) ? 'padding:' . $icon_padding . ';' : '';
				$style .= ($icon_color) ? 'color:' . $icon_color  . ';' : '';
				$style .= ($icon_background) ? 'background-color:' . $icon_background  . ';' : '';
				$style .= ($icon_border_color) ? 'border-style:solid;border-color:' . $icon_border_color  . ';' : '';
				$style .= ($icon_border_width) ? 'border-width:' . (int) $icon_border_width  . 'px;' : '';
				$style .= ($icon_border_radius) ? 'border-radius:' . (int) $icon_border_radius  . 'px;' : '';

				$font_size 	= ($icon_size) ? 'font-size:' . (int) $icon_size . 'px;width:' . (int) $icon_size . 'px;height:' . (int) $icon_size . 'px;line-height:' . (int) $icon_size . 'px;' : '';

				if($style) {
					$css .= $addon_id . ' .sppb-icon .sppb-icon-container {';
					$css .= $style;
					$css .= '}';
				}

				if($font_size) {
					$css .= $addon_id . ' .sppb-icon .sppb-icon-container > i {';
					$css .= $font_size;
					$css .= '}';
				}
			}
		} else {
			if($feature_image) {
				$img_style = 'display:inline-block;';
				$img_style .= ($icon_margin_top) ? 'margin-top:' . (int) $icon_margin_top . 'px;' : '';
				$img_style .= ($icon_margin_bottom) ? 'margin-bottom:' . (int) $icon_margin_bottom . 'px;' : '';

				if($img_style) {
					$css .= $addon_id . ' .sppb-img-container {';
					$css .= $img_style;
					$css .= '}';
				}
			}
		}

		return $css;
	}

}
