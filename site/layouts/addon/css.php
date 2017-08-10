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

$inlineCSS = '';
$addon_css = '';
$addon_link_css = '';
$addon_link_hover_css = '';
$addon_id = "#sppb-addon-". $addon->id;

// Color
if(isset($addon->settings->global_text_color) && $addon->settings->global_text_color) {
    $addon_css .= "\tcolor: " . $addon->settings->global_text_color . ";\n";
}

if(isset($addon->settings->global_link_color) && $addon->settings->global_link_color) {
    $addon_link_css .= "\tcolor: " . $addon->settings->global_link_color . ";\n";
}

if(isset($addon->settings->global_link_hover_color) && $addon->settings->global_link_hover_color) {
    $addon_link_hover_css .= "\tcolor: " . $addon->settings->global_link_hover_color . ";\n";
}

// Background
if(isset($addon->settings->global_use_background) && $addon->settings->global_use_background) {
    if(isset($addon->settings->global_background_color) && $addon->settings->global_background_color) {
        $addon_css .= "\tbackground-color: " . $addon->settings->global_background_color . ";\n";
    }

    if(isset($addon->settings->global_background_image) && $addon->settings->global_background_image) {
        $addon_css .= "\tbackground-image: url(" . JURI::base(true) . '/' . $addon->settings->global_background_image . ");\n";

        if(isset($addon->settings->global_background_repeat) && $addon->settings->global_background_repeat) {
            $addon_css .= "\tbackground-repeat: " . $addon->settings->global_background_repeat . ";\n";
        }

        if(isset($addon->settings->global_background_size) && $addon->settings->global_background_size) {
            $addon_css .= "\tbackground-size: " . $addon->settings->global_background_size . ";\n";
        }

        if(isset($addon->settings->global_background_attachment) && $addon->settings->global_background_attachment) {
            $addon_css .= "\tbackground-attachment: " . $addon->settings->global_background_attachment . ";\n";
        }
    }
}

// Border
if(isset($addon->settings->global_user_border) && $addon->settings->global_user_border) {
    if(isset($addon->settings->global_border_width) && $addon->settings->global_border_width) {
        $addon_css .= "border-width: " . $addon->settings->global_border_width . "px;\n";
    }

    if(isset($addon->settings->global_border_color) && $addon->settings->global_border_color) {
        $addon_css .= "border-color: " . $addon->settings->global_border_color . ";\n";
    }

    if(isset($addon->settings->global_boder_style) && $addon->settings->global_boder_style) {
        $addon_css .= "border-style: " . $addon->settings->global_boder_style . ";\n";
    }
}

// Border radius
if(isset($addon->settings->global_border_radius) && $addon->settings->global_border_radius) {
    $addon_css .= "border-radius: " . $addon->settings->global_border_radius . "px;\n";
}

// Padding & Margin
if(isset($addon->settings->global_margin) && $addon->settings->global_margin) {
    $addon_css .= "margin: " . $addon->settings->global_margin . ";\n";
}

if(isset($addon->settings->global_padding) && $addon->settings->global_padding) {
    $addon_css .= "padding: " . $addon->settings->global_padding . ";\n";
}

if($addon_css) {
    $inlineCSS .= $addon_id ." {\n" . $addon_css . "}\n";
}

if($addon_link_css) {
    $inlineCSS .= $addon_id ." a {\n" . $addon_link_css . "}\n";
}

if($addon_link_hover_css) {
    $inlineCSS .= $addon_id ." a:hover,\n#sppb-addon-". $addon->id ." a:focus,\n#sppb-addon-". $addon->id ." a:active {\n" . $addon_link_hover_css . "}\n";
}

//Addon Title
if(isset($addon->settings->title) && $addon->settings->title) {
    $title_style  = (isset($addon->settings->title_margin_top) && $addon->settings->title_margin_top != '') ? 'margin-top:' . (int) $addon->settings->title_margin_top . 'px;' : '';
    $title_style .= (isset($addon->settings->title_margin_bottom) && $addon->settings->title_margin_bottom != '') ? 'margin-bottom:' . (int) $addon->settings->title_margin_bottom . 'px;' : '';
    $title_style .= (isset($addon->settings->title_text_color) && $addon->settings->title_text_color) ? 'color:' . $addon->settings->title_text_color . ';' : '';
    $title_style .= (isset($addon->settings->title_fontsize) && $addon->settings->title_fontsize) ? 'font-size:' . $addon->settings->title_fontsize . 'px;line-height:' . $addon->settings->title_fontsize . 'px;' : '';
    $title_style .= (isset($addon->settings->title_lineheight) && $addon->settings->title_lineheight) ? 'line-height:' . $addon->settings->title_lineheight . 'px;' : '';
    $title_style .= (isset($addon->settings->title_fontweight) && $addon->settings->title_fontweight) ? 'font-weight:' . $addon->settings->title_fontweight . ';' : '';
    $title_style .= (isset($addon->settings->title_letterspace) && $addon->settings->title_letterspace) ? 'letter-spacing:' . $addon->settings->title_letterspace . ';' : '';

    $title_font_style = (isset($addon->settings->title_fontstyle) && $addon->settings->title_fontstyle) ? $addon->settings->title_fontstyle : '';

    if(is_array($title_font_style) && count($title_font_style)) {
      if(in_array('underline', $title_font_style)) {
        $title_style .= 'text-decoration: underline;';
      }

      if(in_array('uppercase', $title_font_style)) {
        $title_style .= 'text-transform: uppercase;';
      }

      if(in_array('italic', $title_font_style)) {
        $title_style .= 'font-style: italic;';
      }

      if(in_array('lighter', $title_font_style)) {
        $title_style .= 'font-weight: lighter;';
      } else if(in_array('normal', $title_font_style)) {
        $title_style .= 'font-weight: normal;';
      } else if(in_array('bold', $title_font_style)) {
        $title_style .= 'font-weight: bold;';
      } else if(in_array('bolder', $title_font_style)) {
        $title_style .= 'font-weight: bolder;';
      }
    }

    if($title_style) {
        $inlineCSS .= $addon_id ." .sppb-addon-title {\n" . $title_style . "}\n";
    }
}

echo $inlineCSS;
