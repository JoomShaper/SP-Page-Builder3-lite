<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SpTypeSelect{

	static function getInput($key, $attr)
	{
		if(!isset($attr['std'])){
			$attr['std'] = '';
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

		// multiple
		$multiple = false;
		if(isset($attr['multiple'])) {
			$multiple = 'multiple';
		}

		$output  = '<div class="sp-pagebuilder-form-group"' . $depend_data . '>';
		$output .= '<label>'.$attr['title'].'</label>';

		$output .= '<select class="sp-pagebuilder-form-control sp-pagebuilder-addon-input" name="'.$key.'" id="field_'.$key.'" '. $multiple .'>';

		foreach( $attr['values'] as $key=>$value )
		{

			if($multiple) {
				$selected = '';
				if(is_array($attr['std']) && (in_array($key, $attr['std']))) {
					$selected = ' selected';
				} else if ($key == $attr['std']) {
					$selected = ' selected';
				}

				$output .= '<option value="'. $key .'"'. $selected .'>'. $value .'</option>';
			} else {
				$output .= '<option value="'.$key.'" '.(($attr['std'] == $key )?'selected':'').'>'.$value.'</option>';
			}

		}

		$output .= '</select>';

		if( ( isset($attr['desc']) ) && ( isset($attr['desc']) != '' ) )
		{
			$output .= '<p class="sp-pagebuilder-help-block">' . $attr['desc'] . '</p>';
		}

		$output .= '</div>';

		return $output;
	}

}
