<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SpTypeMargin{

	static function getInput($key, $attr)
	{

		if (!isset($attr['std'])) {
			$attr['std'] = '';
		} else {
			$attr['std'] = trim($attr['std']);
		}

		$attr['std'] = strip_tags($attr['std']);

		$margin = array('', '', '', '');

		if($attr['std'] != '') {
			$std = explode(' ', $attr['std']);

			switch (count($std)) {
				case 1:
					$margin = array($std[0], $std[0], $std[0], $std[0]);
					break;

				case 2:
					$margin = array($std[0], $std[1], $std[0], $std[1]);
					break;

				case 3:
					$margin = array($std[0], $std[1], $std[2], $std[1]);
					break;

				case 4:
					$margin = array($std[0], $std[1], $std[2], $std[3]);
					break;

				default:
					$margin = array($std[0], $std[0], $std[0], $std[0]);
					break;
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

		$output  = '<div class="sp-pagebuilder-form-group sp-pagebuilder-group-margin"' . $depend_data . '>';
		$output .= '<label>'.$attr['title'].'</label>';
		$output	.= '<input class="sp-pagebuilder-form-control sp-pagebuilder-addon-input" type="hidden" name="'. $key .'" value="' . $attr['std'] . '">';

		$output	.= '<div class="sppb-row sp-pagebuilder-margins-list">';
		$output	.= '<div class="sppb-col-xs-3">';
		$output	.= '<input type="text" class="sp-pagebuilder-form-control sp-pagebuilder-control-margin" data-position="0" placeholder="Top" value="' . $margin[0] . '">';
		$output	.= '</div>';
		$output	.= '<div class="sppb-col-xs-3">';
		$output	.= '<input type="text" class="sp-pagebuilder-form-control sp-pagebuilder-control-margin" data-position="1" placeholder="Right" value="' . $margin[1] . '">';
		$output	.= '</div>';
		$output	.= '<div class="sppb-col-xs-3">';
		$output	.= '<input type="text" class="sp-pagebuilder-form-control sp-pagebuilder-control-margin" data-position="2" placeholder="Bottom" value="' . $margin[2] . '">';
		$output	.= '</div>';
		$output	.= '<div class="sppb-col-xs-3">';
		$output	.= '<input type="text" class="sp-pagebuilder-form-control sp-pagebuilder-control-margin" data-position="3" placeholder="Left" value="' . $margin[3] . '">';
		$output	.= '</div>';
		$output	.= '</div>';

		if( ( isset($attr['desc']) ) && ( isset($attr['desc']) != '' ) ) {
			$output .= '<p class="sp-pagebuilder-help-block">' . $attr['desc'] . '</p>';
		}

		$output .= '</div>';

		return $output;
	}

}
