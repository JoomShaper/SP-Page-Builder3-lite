<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SpTypeMedia
{

	static function getInput($key, $attr)
	{

		if(!isset($attr['std'])){
			$attr['std'] = '';
		}

		if($attr['std']!='') {
			$src = 'src="' . JURI::root() .  $attr['std'] . '"';
		} else {
			$src = '';
		}

		// Media Format
		if(isset($attr['format']) && $attr['format']){
			$media_format = $attr['format'];
		} else {
			$media_format = 'image';
		}

		if (!isset($attr['placeholder'])) {
			$attr['placeholder'] = '';
		}

		// Depends
		$depend_data = '';
		if(isset($attr['depends'])) {
			$array = array();
			foreach ($attr['depends'] as $operand => $value) {
			  if(!is_array($value)) {
			    $array[] = array(
			      $operand,
			      '=',
			      $value
			    );
			  } else {
			    $array = $attr['depends'];
			  }
			}

			$depend_data = " data-depends='". json_encode($array) ."'";
		}

		$output  = '<div class="sp-pagebuilder-form-group"' . $depend_data . '>';
		$output .= '<label>' . $attr['title'] . '</label>';

		if($attr['std']) {
			if($media_format == 'image') {
				$img_src = JURI::root(true) . '/' . $attr['std'];
				if(strpos($attr['std'], "http://") !== false || strpos($attr['std'], "https://") !== false){
					 $img_src = $attr['std'];
				}
				$output .= '<img class="sp-pagebuilder-media-preview" src="' . $img_src . '" alt="" />';
			}
		} else {
			if($media_format == 'image') {
				$output .= '<img class="sp-pagebuilder-media-preview sp-pagebuilder-media-no-image" alt="" />';
			}
		}

		if($media_format == 'image') {
			if (isset($attr['show_input']) && $attr['show_input'] == true) {
				$output	.= '<input class="sp-pagebuilder-form-control sp-pagebuilder-input-media sp-pagebuilder-media-input sp-pagebuilder-addon-input" type="text" name="'. $key .'" value="'.$attr['std'].'" placeholder="'.$attr['placeholder'].'" autocomplete="off">';
			} else {
				$output	.= '<input class="sp-pagebuilder-form-control sp-pagebuilder-input-media sp-pagebuilder-media-input sp-pagebuilder-addon-input" type="hidden" name="'. $key .'" value="'.$attr['std'].'">';
			}
		} else {
			$output	.= '<input class="sp-pagebuilder-form-control sp-pagebuilder-input-media sp-pagebuilder-media-input sp-pagebuilder-addon-input" type="text" name="'. $key .'" value="'.$attr['std'].'" placeholder="'.$attr['placeholder'].'" autocomplete="off">';
		}

		$output .= '<a href="#" class="sp-pagebuilder-btn sp-pagebuilder-btn-primary sp-pagebuilder-btn-media-manager" data-support="' . $media_format . '"><i class="fa fa-spinner fa-spin" style="display: none; margin-right: 5px;"></i> '. JText::_('COM_SPPAGEBUILDER_MEDIA_MANAGER_UPLOAD_' . strtoupper($media_format)) .'</a>';
		$output .= ' <a class="sp-pagebuilder-btn sp-pagebuilder-btn-danger sp-pagebuilder-btn-clear-media" href="#"><i class="icon-remove"></i></a>';

		if( ( isset($attr['desc']) ) && ( isset($attr['desc']) != '' ) )
		{
			$output .= '<p class="sp-pagebuilder-help-block">' . $attr['desc'] . '</p>';
		}

		$output .= '</div>';

		return $output;

	}
}
