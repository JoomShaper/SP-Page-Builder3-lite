<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SpTypeText{

	static function getInput($key, $attr)
	{

		if (!isset($attr['std'])) {
			$attr['std'] = '';
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

		// Clean html
		$attr['std'] = strip_tags($attr['std'], '<strong><b><i><br><span>');

		$output  = '<div class="sp-pagebuilder-form-group"' . $depend_data . '>';
		$output .= '<label>'.$attr['title'].'</label>';
		$output	.= '<input class="sp-pagebuilder-form-control sp-pagebuilder-addon-input" type="text" name="'. $key .'" value="'.$attr['std'].'" placeholder="'.$attr['placeholder'].'" autocomplete="off" />';

		if( ( isset($attr['desc']) ) && ( isset($attr['desc']) != '' ) )
		{
			$output .= '<p class="sp-pagebuilder-help-block">' . $attr['desc'] . '</p>';
		}

		$output .= '</div>';

		return $output;
	}

}
