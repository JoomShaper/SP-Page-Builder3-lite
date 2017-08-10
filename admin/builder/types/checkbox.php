<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SpTypeCheckbox{

	static function getInput($key, $attr)
	{

		if (isset($attr['value'])) {
			$attr['std'] = $attr['value'];
		}else{
			if (!isset($attr['std'])) {
				$attr['std'] = '0';
			}
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
		$output .= '<label>'.$attr['title'].'</label>';

		$output  .= '<div class="sp-pagebuilder-sp-checkbox">';
		$output  .= '<input id="field_'.$key.'" class="sp-pagebuilder-addon-input" name="'.$key.'" type="checkbox" '.(($attr['std'] == 1)?'checked':'').' value="'.$attr['std'].'">';
		$output  .= '<label for="field_'.$key.'">';
		$output  .= '<span><span></span><strong class="sp-pagebuilder-sp-checkbox-1">YES</strong><strong class="sp-pagebuilder-sp-checkbox-2">NO</strong></span>';
		$output  .= '</label>';
		$output  .= '</div>';

		if( ( isset($attr['desc']) ) && ( isset($attr['desc']) != '' ) )
		{
			$output .= '<p class="sp-pagebuilder-help-block">' . $attr['desc'] . '</p>';
		}

		$output .= '</div>';

		return $output;
	}

}
