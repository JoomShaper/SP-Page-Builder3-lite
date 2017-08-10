<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SpTypeAnimation{

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

		$output  = '<div class="sp-pagebuilder-form-group"' . $depend_data . '>';
		$output .= '<label>'.$attr['title'].'</label>';

		$output .= '<select class="sp-pagebuilder-form-control sp-pagebuilder-addon-input chosen-select-deselect data-animation-select" name="'.$key.'" id="field_'.$key.'">';

		$animations = array(
				"fadeIn",
				"fadeInDown",
				"fadeInDownBig",
				"fadeInLeft",
				"fadeInLeftBig",
				"fadeInRight",
				"fadeInRightBig",
				"fadeInUp",
				"fadeInUpBig",

				"flip",
				"flipInX",
				"flipInY",

				"rotateIn",
				"rotateInDownLeft",
				"rotateInDownRight",
				"rotateInUpLeft",
				"rotateInUpRight",

				"zoomIn",
				"zoomInDown",
				"zoomInLeft",
				"zoomInRight",
				"zoomInUp",

				"bounceIn",
				"bounceInDown",
				"bounceInLeft",
				"bounceInRight",
				"bounceInUp"

			);

		$output .= '<option value=""></option>';

		foreach( $animations as $animation )
		{
			$output .= '<option value="'.$animation.'" '.(($attr['std'] == $animation )?'selected':'').'>'. $animation .'</option>';
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
