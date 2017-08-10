<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

// import Joomla table library
jimport('joomla.database.table');

class SppagebuilderTablePage extends JTable {

	function __construct(&$db) {
		parent::__construct('#__sppagebuilder', 'id', $db);
	}

	public function store($updateNulls = true) {
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if ($this->id) {
			$this->modified		= $date->toSql();
			$this->modified_by		= $user->get('id');
		} else {
			if (!(int) $this->created_on) {
				$this->created_on = $date->toSql();
			}
			if (empty($this->created_by)) {
				$this->created_by = $user->get('id');
			}
		}

		return parent::store($updateNulls);
	}
}
