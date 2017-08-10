<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

abstract class SppagebuilderHelper {

	public static $extension = 'com_sppagebuilder';

	public static function addSubmenu($vName) {

		JHtmlSidebar::addEntry(
			JText::_('COM_SPPAGEBUILDER_PAGES'),
			'index.php?option=com_sppagebuilder&view=pages',
			$vName == 'pages'
		);

		JHtmlSidebar::addEntry(
			JText::_('COM_SPPAGEBUILDER_CATEGORIES'),
			'index.php?option=com_categories&extension=com_sppagebuilder',
			$vName == 'categories');

		JHtmlSidebar::addEntry(
				'<i class="fa fa-plug"></i> ' . JText::_('COM_SPPAGEBUILDER_INTEGRATIONS'),
				'index.php?option=com_sppagebuilder&view=integrations',
				$vName == 'integrations'
			);

		JHtmlSidebar::addEntry(
			JText::_('COM_SPPAGEBUILDER_MEDIA'),
			'index.php?option=com_sppagebuilder&view=media',
			$vName == 'media'
		);
	}

	public static function getVersion() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
		->select('e.manifest_cache')
		->select($db->quoteName('e.manifest_cache'))
		->from($db->quoteName('#__extensions', 'e'))
		->where($db->quoteName('e.element') . ' = ' . $db->quote('com_sppagebuilder'));

		$db->setQuery($query);
		$manifest_cache = json_decode($db->loadResult());

		if(isset($manifest_cache->version) && $manifest_cache->version) {
			return $manifest_cache->version;
		}

		return '1.0';
	}

	// 3rd party

	public static function onAfterIntegrationSave($attribs) {

		if(!self::getIntegration($attribs['option'])) return;

		$db = JFactory::getDbo();

		if(self::checkPage($attribs['option'], $attribs['view'], $attribs['id'])) {

			$fields = array(
				$db->quoteName('title') . ' = ' . $db->quote($attribs['title']),
				$db->quoteName('text') . ' = ' . $db->quote($attribs['text']),
				$db->quoteName('modified') . ' = ' . $db->quote($attribs['modified']),
				$db->quoteName('modified_by') . ' = ' . $db->quote($attribs['modified_by']),
				$db->quoteName('active') . ' = ' . $db->quote($attribs['active'])
			);

			self::updatePage($attribs['id'], $fields);

		} else {
			$values = array(
				$db->quote($attribs['title']),
				$db->quote($attribs['text']),
				$db->quote($attribs['option']),
				$db->quote($attribs['view']),
				$db->quote($attribs['id']),
				$db->quote($attribs['active']),
				$db->quote(1),
				$db->quote($attribs['created_on']),
				$db->quote($attribs['created_by']),
				$db->quote($attribs['modified']),
				$db->quote($attribs['modified_by']),
				$db->quote($attribs['language'])
			);

			self::insertPage($values);
		}

		return true;
	}

	public static function onIntegrationPrepareContent($text, $option, $view, $id) {

		if(!self::getIntegration($option)) return $text;

		$page_content = self::getPageContent($option, $view, $id);
		if($page_content) {
			require_once JPATH_ROOT .'/components/com_sppagebuilder/parser/addon-parser.php';
			$doc = JFactory::getDocument();
			$doc->addStyleSheet(JUri::base(true).'/components/com_sppagebuilder/assets/css/sppagebuilder.css');
			$doc->addScript(JUri::base(true).'/components/com_sppagebuilder/assets/js/sppagebuilder.js');
			return AddonParser::viewAddons(json_decode($page_content->text));
		}
	}

	public static function getPageContent($extension, $extension_view, $view_id = 0) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('text')));
		$query->from($db->quoteName('#__sppagebuilder'));
		$query->where($db->quoteName('extension') . ' = '. $db->quote($extension));
		$query->where($db->quoteName('extension_view') . ' = '. $db->quote($extension_view));
		$query->where($db->quoteName('view_id') . ' = '. $view_id);
		$query->where($db->quoteName('active') . ' = 1');
		$db->setQuery($query);
		$result = $db->loadObject();

		if(count($result)) {
			return $result;
		}

		return false;
	}

	private static function checkPage($extension, $extension_view, $view_id) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id')));
		$query->from($db->quoteName('#__sppagebuilder'));
		$query->where($db->quoteName('extension') . ' = '. $db->quote($extension));
		$query->where($db->quoteName('extension_view') . ' = '. $db->quote($extension_view));
		$query->where($db->quoteName('view_id') . ' = '. $view_id);
		$db->setQuery($query);

		return $db->loadResult();
	}

	private static function insertPage($content = array()) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$columns = array(
			'title',
			'text',
			'extension',
			'extension_view',
			'view_id',
			'active',
			'published',
			'created_on',
			'created_by',
			'modified',
			'modified_by',
			'language'
		);

		$query
		->insert($db->quoteName('#__sppagebuilder'))
		->columns($db->quoteName($columns))
		->values(implode(',', $content));

		$db->setQuery($query);
		$db->execute();
	}

	private static function updatePage($view_id, $content) {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$condition = array($db->quoteName('view_id') . ' = ' . $view_id);
		$query->update($db->quoteName('#__sppagebuilder'))->set($content)->where($condition);
		$db->setQuery($query);
		$db->execute();
	}

	private static function getIntegration($option) {

		$db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $user = JFactory::getUser();
    $query->select('a.id');
    $query->from('#__sppagebuilder_integrations as a');
    $query->where($db->quoteName('component') . ' = ' . $db->quote($option));
    $query->where($db->quoteName('state') . ' = 1');
    $db->setQuery($query);
    $result = $db->loadResult();

		return $result;
  }
}
