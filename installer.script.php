<?php
/**
* @package SP Page Builder
* @author JoomShaper http://www.joomshaper.com
* @copyright Copyright (c) 2010 - 2016 JoomShaper
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class com_sppagebuilderInstallerScript {

  /**
  * method to uninstall the component
  *
  * @return void
  */

  public function uninstall($parent) {
    $db = JFactory::getDBO();
    $status = new stdClass;
    $status->modules = array();
    $manifest = $parent->getParent()->manifest;

    // Uninstal Modules
    $modules = $manifest->xpath('modules/module');
    foreach ($modules as $module)
    {
      $name = (string)$module->attributes()->module;
      $client = (string)$module->attributes()->client;
      $db = JFactory::getDBO();
      $query = "SELECT `extension_id` FROM `#__extensions` WHERE `type`='module' AND element = ".$db->Quote($name)."";
      $db->setQuery($query);
      $extensions = $db->loadColumn();
      if (count($extensions))
      {
        foreach ($extensions as $id)
        {
          $installer = new JInstaller;
          $result = $installer->uninstall('module', $id);
        }
        $status->modules[] = array('name' => $name, 'client' => $client, 'result' => $result);
      }
    }
  }


  /**
  * method to run before an install/update/uninstall method
  *
  * @return void
  */

  public function preflight($type, $parent) {
    // Remove Free Updater
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $conditions = array($db->quoteName('location') . ' = ' . $db->quote('http://www.joomshaper.com/updates/com-sp-page-builder-free.xml'));
    $query->delete($db->quoteName('#__update_sites'));
    $query->where($conditions);
    $db->setQuery($query);
    $db->execute();
  }


  /**
  * method to run after an install/update/uninstall method
  *
  * @return void
  */

  public function postflight($type, $parent) {
    $db = JFactory::getDBO();
    $status = new stdClass;
    $status->modules = array();
    $src = $parent->getParent()->getPath('source');
    $manifest = $parent->getParent()->manifest;

    // Install Modules
    $modules = $manifest->xpath('modules/module');
    foreach ($modules as $module) {
      $name = (string)$module->attributes()->module;
      $client = (string)$module->attributes()->client;
      $path = $src . '/modules/' . $client . '/' . $name;
      $position = (isset($module->attributes()->position) && $module->attributes()->position) ? (string)$module->attributes()->position : '';
      $ordering = (isset($module->attributes()->ordering) && $module->attributes()->ordering) ? (string)$module->attributes()->ordering : 0;

      $installer = new JInstaller;
      $result = $installer->install($path);

      if($client == 'administrator') {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array();

        $fields[] = $db->quoteName('published') . ' = 1';

        if($position) {
          $fields[] = $db->quoteName('position') . ' = ' . $db->quote($position);
        }

        if($ordering) {
          $fields[] = $db->quoteName('ordering') . ' = ' . $db->quote($ordering);
        }

        $conditions = array(
          $db->quoteName('module') . ' = ' . $db->quote($name)
        );

        $query->update($db->quoteName('#__modules'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $db->execute();

        // Retrive ID
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select($db->quoteName(array('id')));
        $query->from($db->quoteName('#__modules'));
        $query->where($db->quoteName('module') . ' = ' . $db->quote($name));
        $db->setQuery($query);
        $id = (int) $db->loadResult();

        // New
        if($id) {
          $db = JFactory::getDbo();
          $db->setQuery("INSERT IGNORE INTO #__modules_menu (`moduleid`,`menuid`) VALUES (".$id.", 0)");
          $db->query();
        }
      }
    }

    if ($type == 'uninstall') {
			return true;
		} ?>

    <style type="text/css">
    .sppb-installation-wrap{
      padding: 20px 0 40px;
      overflow: hidden;
    }
    .sppb-installation-wrap .sppb-installation-left {
    	float: left;
    	width: 250px;
    	margin-right: 15px;
    }
    .sppb-installation-wrap .sppb-installation-footer{
      margin-top: 30px;
    }

    .sppb-installation-wrap .sppb-installation-footer a{
      margin-right: 10px;
    }
    </style>
    <div class="sppb-installation-wrap row-fluid">
    	<div class="span4 sppb-installation-left span2">
    		<img src="../media/com_sppagebuilder/images/logo-pagebuilder.jpg" alt="SP Page Builder" />
    	</div> <!-- /.sppb-installation-left -->
    	<div class="sppb-installation-right span8">
        <div class="sppb-installation-texts">
          <h2>SP Page Builder Lite</h2>
          <p>Trusted by 250,000+ people worldwide, SP Page Builder is an extremely powerful drag &amp; drop design system.<br/>
          Whether you're a beginner or a professional, you must love taking control over your website design.</p>
          <p>With SP Page Builder, you can build a unique, stunning and functional site without coding a single line.<br/>
          Using the tool, anyone can build a professional quality site in minutes.</p>
        </div>
        <div class="sppb-installation-footer">
          <div class="pagebuilder-links">
            <a class="btn btn-success" href="index.php?option=com_sppagebuilder">Get Started</a>
        		<a class="btn btn-info" href="index.php?option=com_sppagebuilder&task=page.add" target="_blank">Create a New Page</a>
            <a class="btn btn-warning" href="https://www.joomshaper.com/documentation/joomla-extensions/sp-page-builder-2-x" target="_blank">Documentation</a>
    			</div>
    	 </div>
     </div> <!-- /.sppb-installation-right -->
    </div> <!-- /.sppb-installation-wrap -->
  <?php
  }
}
