<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

// all settings loading
if ( $action === 'setting' ) {
	require_once JPATH_COMPONENT_ADMINISTRATOR .'/builder/classes/base.php';
	require_once JPATH_COMPONENT_ADMINISTRATOR .'/builder/classes/config.php';

	/* Template language load */

	$db = JFactory::getDbo();
	$query = "SELECT template FROM #__template_styles WHERE client_id = 0 AND home = 1";
	$db->setQuery($query);
	$defaultemplate = $db->loadResult();

	$lang = JFactory::getLanguage();
	$lang->load('tpl_' . $defaultemplate, JPATH_SITE, $lang->getName(), true);

	/* Template language load */

	$type = $_POST['type'];

	if ( $type == 'list' ) // Load addons list
	{
		SpPgaeBuilderBase::loadAddons();
		$form_fields = SpAddonsConfig::$addons;

		foreach ($form_fields as &$form_field) {
			$form_field['visibility'] = true;
		}

		usort($form_fields, function($a){
			if (isset($a['pro']) && $a['pro']) {
				return 1;
			}
		});
	}
	else
	{
		SpPgaeBuilderBase::loadInputTypes();

		if ( $type === 'row' || $type === 'inner_row' ) // Load row settings
		{
			require_once JPATH_COMPONENT_ADMINISTRATOR .'/builder/settings/row.php';
			$form_fields = $row_settings;
		}
		else if ( $type === 'column' || $type === 'inner_column' ) // Load column settings
		{
			require_once JPATH_COMPONENT_ADMINISTRATOR .'/builder/settings/column.php';
			$form_fields = $column_settings;
		}
		else if ( $type === 'addon' || $type === 'inner_addon' ) // Load single addon settings
		{
			$addon_name = $_POST['addonName'];
			SpPgaeBuilderBase::loadSingleAddon( $addon_name );
			$form_fields = SpAddonsConfig::$addons;

			$first_attr = current($form_fields[$addon_name]['attr']);
			$options = SpPgaeBuilderBase::addonOptions();

			if(isset($first_attr['type']) && !is_array($first_attr['type'])){
				$newArry['general'] = $form_fields[$addon_name]['attr'];
				$form_fields[$addon_name]['attr'] = $newArry;
			}

			// Merge style
			if(isset($form_fields[$addon_name]['attr']['style'])) {
				$options['style'] = array_merge($form_fields[$addon_name]['attr']['style'], $options['style']);
			}

			foreach ($options as $key => $option) {
				$form_fields[$addon_name]['attr'][$key] = $option;
			}

		}
	}

	$response = new SppbSettingsAjax($_POST, $form_fields);
	echo $response->get_ajax_request(); die();
}

// Pre-defined page library
if ($action === 'page-library') {
	$page_folder_path = JPATH_COMPONENT_ADMINISTRATOR.'/builder/templates';
	if (!file_exists($page_folder_path)) {
		echo json_encode(array('status' => 'false')); die;
	}

	$files = JFolder::files($page_folder_path,'.json');
	$pages = array();

	if (count($files)) {
		foreach ($files as $key => $file) {
			$pages[$key]['name'] = $file;
			$pages[$key]['source'] = 'component';
		}
	}

	echo json_encode(array('status' => 'true', 'data' => $pages)); die;

	// add to library from active template

}

// Load Page Template List
if ($action === 'pre-page-list') {
	$output = array('status' => 'false', 'data' => 'Templates not found.');
	$templates = array(); // All pre-defined templates list

	// SPPB Pro Version Templates
	$sppb_pages_dir_path = JPATH_COMPONENT_ADMINISTRATOR.'/builder/templates';
	if ( file_exists( $sppb_pages_dir_path ) ) {
		$folders = JFolder::folders( $sppb_pages_dir_path );
		if ( count( $folders ) ) {
			foreach ( $folders as $key => $folder ) {
				$page = array();
				$page['name'] 	= $folder;
				$page['img'] = false;
				if(file_exists(JPATH_COMPONENT_ADMINISTRATOR . '/builder/templates/' . $folder . '/preview.png')) {
					$page['img'] 	= JURI::root( true ) . '/administrator/components/com_sppagebuilder/builder/templates/' . $folder . '/preview.png';
				} else {
					$page['img'] 	= JURI::root( true ) . '/administrator/components/com_sppagebuilder/assets/img/template-preview.png';
				}

				array_push($templates, $page);
			}
		}
	}

	// template support
	// Plugins support

	if (count($templates)) {
		$output['status'] = 'true';
		$output['data'] = $templates;
		echo json_encode($output); die();
	}

	echo json_encode($output); die();
}

// Load page from uploaded page
if ($action === 'upload-page') {
	if ( isset($_FILES['page']) && $_FILES['page']['error'] === 0 && $_FILES['page']['type'] === 'application/json') {
		$content = file_get_contents($_FILES['page']['tmp_name']);
		if (is_array(json_decode($content))) {

			// Check frontend editing
			if ($input->get('editarea', '', 'STRING') == 'frontend') {
				require_once JPATH_COMPONENT_ADMINISTRATOR . '/builder/classes/addon.php';
				$content = SpPageBuilderAddonHelper::__($content);
				$content = SpPageBuilderAddonHelper::getFontendEditingPage($content);
			}

			echo json_encode( array('status' => true, 'data' => $content) ); die;
		}
	}

	echo json_encode(array('status'=> false, 'data'=>'Something worng there.')); die;
}
