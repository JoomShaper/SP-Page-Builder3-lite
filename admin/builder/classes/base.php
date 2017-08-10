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

class SpPgaeBuilderBase {

	private static function str_replace_first($from, $to, $subject) {
		$from = '/'.preg_quote($from, '/').'/';
		return preg_replace($from, $to, $subject, 1);
	}

	public static function loadInputTypes() {
		$types = JFolder::files( JPATH_ROOT .'/administrator/components/com_sppagebuilder/builder/types', '\.php$', false, true);
		foreach ($types as $type) {
			include_once $type;
		}
	}

	private static function getTemplateName() {
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('template')));
		$query->from($db->quoteName('#__template_styles'));
		$query->where($db->quoteName('client_id') . ' = 0');
		$query->where($db->quoteName('home') . ' = 1');
		$db->setQuery($query);

		return $db->loadObject()->template;
	}

	// Load addons list
	public static function loadAddons() {

		require_once JPATH_ROOT . '/components/com_sppagebuilder/addons/module/admin.php';

		$template_path = JPATH_ROOT . '/templates/' . self::getTemplateName(); // current template path
		$tmpl_folders = array();

		if ( file_exists($template_path . '/sppagebuilder/addons') ) {
			$tmpl_folders = JFolder::folders($template_path . '/sppagebuilder/addons');
		}

		$folders = JFolder::folders(JPATH_ROOT .'/components/com_sppagebuilder/addons');

		if($tmpl_folders) {
			$merge_folders = array_merge( $folders, $tmpl_folders );
			$folders = array_unique( $merge_folders );
		}

		if ( count( $folders ) ) {
			foreach ( $folders as $folder ) {
				$tmpl_file_path = $template_path . '/sppagebuilder/addons/'.$folder.'/admin.php';
				$com_file_path = JPATH_ROOT . '/components/com_sppagebuilder/addons/'.$folder.'/admin.php';

				if($folder!='module') {
					if(file_exists( $tmpl_file_path )) {
						require_once $tmpl_file_path;
					} else if( file_exists( $com_file_path )) {
						require_once $com_file_path;
					}
				}
			}
		}
	}

	public static function loadSingleAddon( $name = '' ) {
		if (!$name) return;

		$name = self::str_replace_first('sp_', '', $name);
		$template_path = JPATH_ROOT . '/templates/' . self::getTemplateName(); // current template path
		$tmpl_addon_path = $template_path . '/sppagebuilder/addons/'. $name .'/admin.php';
		$com_addon_path = JPATH_ROOT . '/components/com_sppagebuilder/addons/'. $name .'/admin.php';

		if(file_exists( $tmpl_addon_path )) {
			require_once $tmpl_addon_path;
		} else if( file_exists( $com_addon_path )) {
			require_once $com_addon_path;
		}
	}

	public static function addonOptions(){
		$options = array(

			'style' => array(
				'global_options'=>array(
					'type'=>'separator',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_OPTIONS'),
				),
				'global_text_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_TEXT_COLOR')
				),
				'global_link_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_LINK_COLOR'),
				),
				'global_link_hover_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_LINK_COLOR_HOVER'),
				),
				'global_use_background'=>array(
					'type'=>'checkbox',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_ENABLE_BACKGROUND_OPTIONS'),
					'std'=>0
				),
				'global_background_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_COLOR'),
					'depends'=>array('global_use_background'=>1)
				),
				'global_background_image'=>array(
					'type'=>'media',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_IMAGE'),
					'placeholder'=>'10',
					'depends'=>array('global_use_background'=>1)
				),
				'global_background_repeat'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT'),
					'values'=>array(
						'no-repeat'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_NO_REPEAT'),
						'repeat-all'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_ALL'),
						'repeat-horizontally'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_HORIZONTALLY'),
						'repeat-vertically'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_REPEAT_VERTICALLY'),
						'inherit'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_INHERIT'),
					),
					'std'=>'no-repeat',
					'depends'=>array('global_use_background'=>1)
				),
				'global_background_size'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE_DESC'),
					'values'=>array(
						'cover'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE_COVER'),
						'contain'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_SIZE_CONTAIN'),
						'inherit'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_INHERIT'),
					),
					'std'=>'cover',
					'depends'=>array('global_use_background'=>1)
				),
				'global_background_attachment'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT'),
					'values'=>array(
						'fixed'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT_FIXED'),
						'scroll'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BACKGROUND_ATTACHMENT_SCROLL'),
						'inherit'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_INHERIT'),
					),
					'std'=>'inherit',
					'depends'=>array('global_use_background'=>1)
				),
				'global_user_border'=>array(
					'type'=>'checkbox',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_USE_BORDER'),
					'std'=>0
				),
				'global_border_width'=>array(
					'type'=>'number',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_WIDTH'),
					'std'=>'',
					'depends'=>array('global_user_border'=>1)
				),
				'global_border_color'=>array(
					'type'=>'color',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_COLOR'),
					'depends'=>array('global_user_border'=>1)
				),
				'global_boder_style'=>array(
					'type'=>'select',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE'),
					'values'=>array(
						'none'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_NONE'),
						'solid'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_SOLID'),
						'double'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DOUBLE'),
						'dotted'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DOTTED'),
						'dashed'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_STYLE_DASHED'),
					),
					'depends'=>array('global_user_border'=>1)
				),
				'global_border_radius'=>array(
					'type'=>'number',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_BORDER_RADIUS'),
					'std'=>''
				),
				'global_margin'=>array(
					'type'=>'margin',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_MARGIN'),
					'std'=>''
				),
				'global_padding'=>array(
					'type'=>'padding',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_PADDING'),
					'std'=>''
				),
				'global_use_animation'=>array(
					'type'=>'checkbox',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_USE_ANIMATION'),
					'std'=>0
				),
				'global_animation'=>array(
					'type'=>'animation',
					'title'=>JText::_('COM_SPPAGEBUILDER_ANIMATION'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ANIMATION_DESC'),
					'depends'=>array('global_use_animation'=>1)
				),

				'global_animationduration'=>array(
					'type'=>'number',
					'title'=>JText::_('COM_SPPAGEBUILDER_ANIMATION_DURATION'),
					'desc'=> JText::_('COM_SPPAGEBUILDER_ANIMATION_DURATION_DESC'),
					'std'=>'300',
					'placeholder'=>'300',
					'depends'=>array('global_use_animation'=>1)
				),

				'global_animationdelay'=>array(
					'type'=>'number',
					'title'=>JText::_('COM_SPPAGEBUILDER_ANIMATION_DELAY'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_ANIMATION_DELAY_DESC'),
					'std'=>'0',
					'placeholder'=>'300',
					'depends'=>array('global_use_animation'=>1)
				),
			),

			'responsive' => array(
				'hidden_md'=>array(
					'type'=>'checkbox',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_MD'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_MD_DESC'),
					'std'=>'0',
				),

				'hidden_sm'=>array(
					'type'=>'checkbox',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_SM'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_SM_DESC'),
					'std'=>'0',
				),

				'hidden_xs'=>array(
					'type'=>'checkbox',
					'title'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_XS'),
					'desc'=>JText::_('COM_SPPAGEBUILDER_GLOBAL_HIDDEN_XS_DESC'),
					'std'=>'0',
					)
				)
			);

			return $options;
		}

		public static function getAddonCategories($addons){
			$categories = array();
			foreach ($addons as $addon) {
				if (isset($addon['category'])) {
					$categories[] = $addon['category'];
				}
			}

			$new_array = array_count_values($categories);

			$result[0]['name'] = 'All';
			$result[0]['count'] = count($addons);
			if (count($new_array)) {
				$i = 1;
				foreach ($new_array as $key => $row) {
					$result[$i]['name'] = $key;
					$result[$i]['count'] = $row;
					$i = $i + 1;
				}
			}

			return $result;
		}

		// Load CSS and JS files for all addons
		public static function loadAssets($addons){
			foreach ($addons as $key => $addon) {
				$class_name = 'SppagebuilderAddon' . ucfirst($key);
				$addon_path = AddonParser::getAddonPath( $key );

				if(class_exists($class_name)) {
					$obj = new $class_name($addon);

					// Scripts
					if ( method_exists( $class_name, 'scripts' ) ) {
						$scripts = $obj->scripts();
						if(count($scripts)) {
							$doc = JFactory::getDocument();
							foreach ($scripts as $key => $script) {
								$doc->addScript($script);
							}
						}
					}

					// Stylesheets
					if ( method_exists( $class_name, 'stylesheets' ) ) {
						$stylesheets = $obj->stylesheets();
						if(count($stylesheets)) {
							$doc = JFactory::getDocument();
							foreach ($stylesheets as $key => $stylesheet) {
								$doc->addStyleSheet($stylesheet);
							}
						}
					}

				}
			}
		}


		public static function getAddonPath( $addon_name = '') {
        $app = JFactory::getApplication();
        $template = $app->getTemplate();
        $template_path = JPATH_ROOT . '/templates/' . $template;

        if ( file_exists( $template_path . '/sppagebuilder/addons/' . $addon_name . '/site.php' ) ) {
            return $template_path . '/sppagebuilder/addons/' . $addon_name;
        } elseif ( file_exists( JPATH_ROOT . '/components/com_sppagebuilder/addons/'. $addon_name . '/site.php' ) ) {
            return JPATH_ROOT . '/components/com_sppagebuilder/addons/'. $addon_name;
        }
    }

	}
