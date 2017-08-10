<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SpTypeAccesslevel{

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

		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('a.id', 'value') . ', ' . $db->quoteName('a.title', 'text'))
			->from($db->quoteName('#__viewlevels', 'a'))
			->group($db->quoteName(array('a.id', 'a.title', 'a.ordering')))
			->order($db->quoteName('a.ordering') . ' ASC')
			->order($db->quoteName('title') . ' ASC');

		// Get the options.
		$db->setQuery($query);
		$levels = $db->loadObjectList();

		$output  = '<div class="sp-pagebuilder-form-group"' . $depend_data . '>';
		$output .= '<label>'.$attr['title'].'</label>';

		$output .= '<select class="sp-pagebuilder-form-control sp-pagebuilder-addon-input data-accesslevel-select" name="'.$key.'" id="field_'.$key.'" multiple>';

		$output .= '<option value=""></option>';
		foreach( $levels as $level )
		{
			$selected = '';
			if(is_array($attr['std']) && (in_array($level->value, $attr['std']))) {
				$selected = ' selected';
			} else if ($level->value == $attr['std']) {
				$selected = ' selected';
			}

			$output .= '<option value="'. $level->value .'"'. $selected .'>'. $level->text .'</option>';
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
