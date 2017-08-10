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

$doc = JFactory::getDocument();
$custom_class  = (isset($options->class) && ($options->class))?' '.$options->class:'';
$custom_class .= (isset($options->columns_equal_height) && $options->columns_equal_height ) ? ' sppb-equalize-columns' : '';
$row_id     = (isset($options->id) && $options->id )? $options->id : 'section-id-'.$options->dynamicId;
$fluid_row = (isset($options->fullscreen) && $options->fullscreen) ? $options->fullscreen : 0;
$row_class = (isset($options->no_gutter) && $options->no_gutter ) ?  ' sppb-no-gutter' : '';

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

$addon_attr = '';

// Animation
if(isset($options->animation) && $options->animation) {

	$custom_class = 'sppb-wow ' . $options->animation;

	if(isset($options->animationduration) && $options->animationduration) {
		$addon_attr .= ' data-sppb-wow-duration="' . $options->animationduration . 'ms"';
	}

	if(isset($options->animationdelay) && $options->animationdelay) {
		$addon_attr .= ' data-sppb-wow-delay="' . $options->animationdelay . 'ms"';
	}
}

$style ='';
if (isset($options->margin) && $options->margin) $style .= 'margin:'.$options->margin.';';
if (isset($options->padding) && $options->padding) $style .= 'padding:'.$options->padding.';';
if (isset($options->color) && $options->color) $style .= 'color:'.$options->color.';';
if (isset($options->background_color) && $options->background_color) $style .= 'background-color:'.$options->background_color.';';

if (isset($options->background_image) && $options->background_image) {

	if(strpos($options->background_image, "http://") !== false || strpos($options->background_image, "https://") !== false){
		$style .= 'background-image:url(' . $options->background_image.');';
	} else {
		$style .= 'background-image:url('. JURI::base(true) . '/' . $options->background_image.');';
	}

	if (isset($options->background_repeat) && $options->background_repeat) $style .= 'background-repeat:'.$options->background_repeat.';';
	if (isset($options->background_size) && $options->background_size) $style .= 'background-size:'.$options->background_size.';';
	if (isset($options->background_attachment) && $options->background_attachment) $style .= 'background-attachment:'.$options->background_attachment.';';
	if (isset($options->background_position) && $options->background_position) $style .= 'background-position:'.$options->background_position.';';

}

if($style) {
	$doc->addStyledeclaration('.sp-page-builder .page-content #' . $row_id . '{'. $style .'}');
}

// Overlay
if (isset($options->overlay) && $options->overlay) {
	$doc->addStyledeclaration('.sp-page-builder .page-content #' . $row_id . ' > .sppb-row-overlay {background-color: '. $options->overlay .'}');
}

// Video
$video_params = '';
if (isset($options->background_video) && $options->background_video) {
	if (isset($options->background_image) && $options->background_image){
		$video_params .= ' data-vide-image="' . JURI::base(true) . '/' . $options->background_image . '"';
	}
	if (isset($options->background_video_mp4) && $options->background_video_mp4) {
		$mp4_parsed = parse_url($options->background_video_mp4);
		$mp4_url = (isset($mp4_parsed['host']) && $mp4_parsed['host']) ? $options->background_video_mp4 : JURI::base(true) . '/' . $options->background_video_mp4;

		$video_params .= ' data-vide-mp4="' . $mp4_url . '"';}
	if (isset($options->background_video_ogv) && $options->background_video_ogv) {
		$ogv_parsed = parse_url($options->background_video_ogv);
		$ogv_url = (isset($ogv_parsed['host']) && $ogv_parsed['host']) ? $options->background_video_ogv : JURI::base(true) . '/' . $options->background_video_ogv;

		$video_params .= ' data-vide-ogv="' . $ogv_url . '"';
	}
	$video_params .= ' data-vide-bg';
}

$html = '';

if(!$fluid_row){
	$html .= '<section id="' . $row_id . '" class="sppb-section ' . $custom_class . '" '.$addon_attr.' ' . $video_params . '>';
	if (isset($options->overlay) && $options->overlay) {
		$html .= '<div class="sppb-row-overlay"></div>';
	}
	$html .= '<div class="sppb-row-container">';
} else {
	$html .= '<div id="' . $row_id . '" class="sppb-section ' . $custom_class . '" '.$addon_attr.' ' . $video_params . '>';
	if (isset($options->overlay) && $options->overlay) {
		$html .= '<div class="sppb-row-overlay"></div>';
	}
	$html .= '<div class="sppb-container-inner">';
}

// Row Title
if ( (isset($options->title) && $options->title) || (isset($options->subtitle) && $options->subtitle) ) {
	$title_position = '';
	if (isset($options->title_position) && $options->title_position) {
		$title_position = $options->title_position;
	}

	if($fluid_row) {
		$html .= '<div class="sppb-container">';
	}

	$html .= '<div class="sppb-section-title ' . $title_position . '">';

	if(isset($options->title) && $options->title) {
		$heading_selector   = 'h2';
		if( isset($options->heading_selector) && $options->heading_selector ) {
			$heading_selector = $options->heading_selector;
		}
		$html .= '<'. $heading_selector .' class="sppb-title-heading">' . $options->title . '</'. $heading_selector .'>';


		$title_style  = '';
		if(isset($options->heading_selector)) {
			if($options->heading_selector == '') {
				$heading_selector = 'h2';
			} else {
				$heading_selector = $options->heading_selector;
			}
		}

    //Title Font Size
		if(isset($options->title_fontsize)) {
			if($options->title_fontsize != '') {
				$title_style .= 'font-size:'.$options->title_fontsize.'px;line-height: '.$options->title_fontsize.'px;';
			}
		}

    //Title Font Weight
		if(isset($options->title_fontweight)) {
			if($options->title_fontweight != '') {
				$title_style .= 'font-weight:'.$options->title_fontweight.';';
			}
		}

        //Title Text Color
		if(isset($options->title_text_color)) {
			if($options->title_text_color != '') {
				$title_style .= 'color:'.$options->title_text_color. ';';
			}
		}

        //Title Margin Top
		if(isset($options->title_margin_top)) {
			if($options->title_margin_top != '') {
				$title_style .= 'margin-top:' . $options->title_margin_top . 'px;';
			}
		}

        //Title Margin Bottom
		if(isset($options->title_margin_bottom)) {
			if($options->title_margin_bottom != '') {
				$title_style .= 'margin-bottom:' . $options->title_margin_bottom . 'px;';
			}
		}

		$doc->addStyledeclaration('.sp-page-builder .page-content #' . $row_id . ' .sppb-section-title .sppb-title-heading {'. $title_style .'}');

	}

	// Subtitle font size
	if(isset($options->subtitle) && $options->subtitle) {
		$subtitle_fontsize = '';
		if(isset($options->subtitle_fontsize)) {
			if($options->subtitle_fontsize != '') {
				$subsubtitle_fontsize = 'font-size:' . (int) $options->subtitle_fontsize . 'px;';
				$doc->addStyledeclaration('.sp-page-builder .page-content #' . $row_id . ' .sppb-section-title .sppb-title-subheading {'. $subsubtitle_fontsize .'}');
			}
		}
	}

	if( $options->subtitle ) {
		$html .= '<p class="sppb-title-subheading">' . $options->subtitle . '</p>';
	}
	$html .= '</div>';

	if( $fluid_row ) {
		$html .= '</div>';
	}
}

$html .= '<div class="sppb-row'. $row_class .'">';

echo $html;
