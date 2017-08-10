<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

jimport('joomla.application.component.modellist');

class SppagebuilderModelIntegrations extends JModelList {

	public function __construct($config = array()) {
		parent::__construct($config);
	}

	protected function getListQuery() {

		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$user = JFactory::getUser();

		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.title, a.description, a.component, a.state'
			)
		);

		$query->from('#__sppagebuilder_integrations as a');

		return $query;
	}

}
