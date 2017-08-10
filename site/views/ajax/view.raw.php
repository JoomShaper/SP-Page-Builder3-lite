<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

jimport( 'joomla.filesystem.file' );
jimport( 'joomla.filesystem.folder' );
$input = JFactory::getApplication()->input;
$action = $input->get('callback', '', 'STRING');

// all settings loading
if ( $action === 'addon' ) {
	require_once JPATH_COMPONENT . '/parser/addon-parser.php';

	$output = '';

	$addon = (object) $_POST['addon'];
	$addon_name = $addon->name;

	$addon->settings = (object)$addon->settings;
	$class_name = 'SppagebuilderAddon' . ucfirst($addon_name);
	$addon_path = AddonParser::includeAddon( $addon_name );
	$output .= JLayoutHelper::render('addon.start', array('addon' => $addon)); // start addon

	require_once $addon_path.'/site.php';

	AddonParser::setStyleScript( $addon_name, $addon, $addon_path);
	if ( class_exists( $class_name ) ) {
			$addon_obj  = new $class_name( $addon, 0, 0 );  // initialize addon class
			$output     .= $addon_obj->render();
	} else {
		$output .= AddonParser::spDoAddon( AddonParser::generateShortcode($addon, 0, 0));
	}

	$output .= JLayoutHelper::render('addon.end'); // end addon


		echo json_encode(array('html' => $output, 'status' => 'true' )); die;
}
if ($action === 'row') {
	$row 						= $_POST['row'];
	$inner_section 	= $_POST['inner_section'];
	$boxlayout 			= $_POST['boxlayout'];

	if (isset($row['settings']) && count($row['settings'])) {
			$settings = (object)$row['settings'];
	} else {
		$settings = new stdClass;
	}

	$settings->boxlayout = $boxlayout;
	$settings->dynamicId = $row['id'];

	$start 	= JLayoutHelper::render( 'row.start', array( 'options' => $settings, 'inner'=> $inner_section ) );
	$end 		= JLayoutHelper::render( 'row.end', array( 'options' => $settings, 'inner'=> $inner_section ) );

	echo json_encode(array('start' => $start, 'end' => $end, 'status' => 'true')); die;
}
