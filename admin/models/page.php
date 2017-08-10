<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

jimport('joomla.application.component.modeladmin');

class SppagebuilderModelPage extends JModelAdmin {

    public function getTable($type = 'Page', $prefix = 'SppagebuilderTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getForm($data = array(), $loadData = true) {
        $form = $this->loadForm('com_sppagebuilder.page', 'page',array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }

        $jinput = JFactory::getApplication()->input;

    		$id = $jinput->get('id', 0);

    		// Determine correct permissions to check.
    		if ($this->getState('page.id'))
    		{
    			$id = $this->getState('page.id');

    			// Existing record. Can only edit in selected categories.
    			$form->setFieldAttribute('catid', 'action', 'core.edit');

    			// Existing record. Can only edit own pages in selected categories.
    			$form->setFieldAttribute('catid', 'action', 'core.edit.own');
    		}
    		else
    		{
    			// New record. Can only create in selected categories.
    			$form->setFieldAttribute('catid', 'action', 'core.create');
    		}

    		$user = JFactory::getUser();

        // Modify the form based on Edit State access controls.
    		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_sppagebuilder.page.' . (int) $id))
    			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_sppagebuilder')))
    		{
    			// Disable fields for display.
    			$form->setFieldAttribute('published', 'disabled', 'true');

    			// Disable fields while saving.
    			// The controller has already verified this is an page you can edit.
    			$form->setFieldAttribute('published', 'filter', 'unset');
    		}

        return $form;
    }

    protected function loadFormData() {
        $data = JFactory::getApplication()->getUserState('com_sppagebuilder.edit.page.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        $this->preprocessData('com_sppagebuilder.page', $data);

        return $data;
    }

    public function save($data) {
        $app = JFactory::getApplication();
        if ($app->input->get('task') == 'save2copy') {
            $data['title'] = $this->pageGenerateNewTitle( $data['title'] );
        }

        if(isset($data['created_by']) && $data['created_by']) {
          $data['created_by'] = $this->checkExistingUser($data['created_by']);
        }

        parent::save($data);
        return true;
    }

    protected function checkExistingUser($id) {
      $currentUser = JFactory::getUser();
      $user_id = $currentUser->id;

      if($id) {
        $user = JFactory::getUser($id);
        if($user->id) {
          $user_id = $id;
        }
      }

      return $user_id;
    }

    protected function prepareTable($table) {
        $jform = JRequest::getVar('jform');
        if (!isset($jform['boxed_layout'])) {
            $table->boxed_layout = 0;
        }
    }

    public static function pageGenerateNewTitle($title ) {
        $pageTable = JTable::getInstance('Page', 'SppagebuilderTable');

        while( $pageTable->load(array('title'=>$title)) ) {
            $m = null;
            if (preg_match('#\((\d+)\)$#', $title, $m)) {
                $title = preg_replace('#\(\d+\)$#', '('.($m[1] + 1).')', $title);
            } else {
                $title .= ' (2)';
            }
        }

        return $title;
    }
}
