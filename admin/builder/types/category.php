<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SpTypeCategory{

	static function getInput($key, $attr)
	{

		if(!isset($attr['std'])){
			$attr['std'] = '';
		}

		if(!isset($attr['extension'])){
			$attr['extension'] = 'com_content';
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
			->select('DISTINCT a.id, a.title, a.level, a.published, a.lft');
		$subQuery = $db->getQuery(true)
			->select('id,title,level,published,parent_id,extension,lft,rgt')
			->from('#__categories')
			->where($db->quoteName('published') . ' = ' . $db->quote(1));

		// Extension
		$subQuery->where('(extension = ' . $db->quote( $attr['extension'] ) . ')');

		$query->from('(' . $subQuery->__toString() . ') AS a')
			->join('LEFT', $db->quoteName('#__categories') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');
		$query->order('a.lft ASC');

		$db->setQuery($query);

		$categories = $db->loadObjectList();

		$options = array();
		if(count($categories)) {
			foreach ($categories as $category) {
				$options[$category->id] = str_repeat('- ', ($category->level -1) ) . $category->title;
			}
		}

		$output  = '<div class="sp-pagebuilder-form-group"' . $depend_data . '>';
		$output .= '<label>'.$attr['title'].'</label>';

		$output .= '<select class="sp-pagebuilder-form-control sp-pagebuilder-addon-input" name="'.$key.'" id="field_'.$key.'">';

		$output .= '<option value=""> - All Categories - </option>';

		foreach( $options as $key=>$value )
		{
			$output .= '<option value="'.$key.'" '.(($attr['std'] == $key )?'selected':'').'>'.$value.'</option>';
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
