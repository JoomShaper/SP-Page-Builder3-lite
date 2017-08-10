<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2015 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SppagebuilderHelperSite {

	public static function loadLanguage() {
        $lang = JFactory::getLanguage();
        // Load component language
        $lang->load('com_sppagebuilder', JPATH_ADMINISTRATOR, $lang->getName(), true);

        // Load template language file
        $lang->load('tpl_' . self::getTemplate(), JPATH_SITE, $lang->getName(), true);

        require_once JPATH_ROOT .'/administrator/components/com_sppagebuilder/helpers/language.php';
    }

		private static function getTemplate() {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('template')));
        $query->from($db->quoteName('#__template_styles'));
        $query->where($db->quoteName('client_id') . ' = '. $db->quote(0));
        $query->where($db->quoteName('home') . ' = '. $db->quote(1));
        $db->setQuery($query);
        return $db->loadResult();
    }
}
