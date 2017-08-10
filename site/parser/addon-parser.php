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
jimport('joomla.filesystem.folder');

require_once __DIR__ . '/addons.php';

class AddonParser {
    public static $loaded_addon = array();
    public static $css_content = '';
    public static $js_content = '';
    private static $sppagebuilderAddonTags = array();

    public static function addAddon($tag, $func)
    {
        if ( is_callable($func) )
                self::$sppagebuilderAddonTags[$tag] = $func;
    }

    public static function spDoAddon($content) {

        if ( false === strpos( $content, '[' ) ) {
            return $content;
        }
        if (empty(self::$sppagebuilderAddonTags) || !is_array(self::$sppagebuilderAddonTags))
            return $content;
        $pattern = self::getAddonRegex();
        return preg_replace_callback( "/$pattern/s", array('AddonParser','doAddonTag'), $content );
    }

    /**
     * Import/Include addon file
     *
     * @param string  $file_name  The addon name. Optional
     *
     * @since 1.0.8
     */
    public static function getAddonPath( $addon_name = '') {
        $template = self::getTemplateName();
        $template_path = JPATH_ROOT . '/templates/' . $template;
        $plugins = self::getPluginsAddons();

        if ( file_exists( $template_path . '/sppagebuilder/addons/' . $addon_name . '/site.php' ) ) {
            return $template_path . '/sppagebuilder/addons/' . $addon_name;
        } elseif ( file_exists( JPATH_ROOT . '/components/com_sppagebuilder/addons/'. $addon_name . '/site.php' ) ) {
            return JPATH_ROOT . '/components/com_sppagebuilder/addons/'. $addon_name;
        } else {
            // Load from plugin
            if(isset($plugins[$addon_name]) && $plugins[$addon_name]) {
                return $plugins[$addon_name];
            }
        }
    }


    private static function getAddonRegex()
    {
        $tagnames = array_keys(self::$sppagebuilderAddonTags);
        $tagregexp = join( '|', array_map('preg_quote', $tagnames) );
        // WARNING! Do not change this regex without changing do_addon_tag() and strip_addon_tag()
        // Also, see addon_unautop() and shortcode.js.
        return
                  '\\['                              // Opening bracket
                . '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
                . "($tagregexp)"                     // 2: Shortcode name
                . '(?![\\w-])'                       // Not followed by word character or hyphen
                . '('                                // 3: Unroll the loop: Inside the opening shortcode tag
                .     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
                .     '(?:'
                .         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
                .         '[^\\]\\/]*'               // Not a closing bracket or forward slash
                .     ')*?'
                . ')'
                . '(?:'
                .     '(\\/)'                        // 4: Self closing tag ...
                .     '\\]'                          // ... and closing bracket
                . '|'
                .     '\\]'                          // Closing bracket
                .     '(?:'
                .         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
                .             '[^\\[]*+'             // Not an opening bracket
                .             '(?:'
                .                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
                .                 '[^\\[]*+'         // Not an opening bracket
                .             ')*+'
                .         ')'
                .         '\\[\\/\\2\\]'             // Closing shortcode tag
                .     ')?'
                . ')'
                . '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
    }


    private static function doAddonTag ( $m )
    {
        // allow [[foo]] syntax for escaping a tag
        if ( $m[1] == '[' && $m[6] == ']' ) {
                return substr($m[0], 1, -1);
        }
        $tag = $m[2];
        $attr = self::addonParseAtts( $m[3] );
        if ( isset( $m[5] ) ) {
                // enclosing tag - extra parameter
                return $m[1] . call_user_func( self::$sppagebuilderAddonTags[$tag], $attr, $m[5], $tag ) . $m[6];
        } else {
                // self-closing tag
                return $m[1] . call_user_func( self::$sppagebuilderAddonTags[$tag], $attr, null,  $tag ) . $m[6];
        }
    }


    private static function addonParseAtts($text)
    {
        $atts = array();
        $pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
        $text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
        if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
                foreach ($match as $m) {
                        if (!empty($m[1]))
                                $atts[strtolower($m[1])] = stripcslashes($m[2]);
                        elseif (!empty($m[3]))
                                $atts[strtolower($m[3])] = stripcslashes($m[4]);
                        elseif (!empty($m[5]))
                                $atts[strtolower($m[5])] = stripcslashes($m[6]);
                        elseif (isset($m[7]) and strlen($m[7]))
                                $atts[] = stripcslashes($m[7]);
                        elseif (isset($m[8]))
                                $atts[] = stripcslashes($m[8]);
                }
        } else {
                $atts = ltrim($text);
        }
        return $atts;
    }


    public static function getAddons() {
        $template = self::getTemplateName();

        require_once JPATH_ROOT . '/components/com_sppagebuilder/addons/module/site.php';//include module manually

        $template_path = JPATH_ROOT . '/templates/' . $template;
        $tmpl_folders = array();
        if (file_exists($template_path . '/sppagebuilder/addons')) {
            $tmpl_folders = JFolder::folders( $template_path . '/sppagebuilder/addons');
        }


        $folders = JFolder::folders( JPATH_ROOT . '/components/com_sppagebuilder/addons');
        if($tmpl_folders){
            $merge_folders = array_merge( $folders, $tmpl_folders );
            $folders = array_unique( $merge_folders );
        }

        if (count($folders)) {
            foreach ($folders as $folder) {
                $tmpl_file_path = $template_path . '/sppagebuilder/addons/'.$folder.'/site.php';
                $com_file_path = JPATH_ROOT . '/components/com_sppagebuilder/addons/'.$folder.'/site.php';
                if($folder!='module') {
                    if(file_exists( $tmpl_file_path ))
                    {
                        require_once $tmpl_file_path;
                    }
                    else if(file_exists( $com_file_path ))
                    {
                        require_once $com_file_path;
                    }
                }
            }
        }
    }


    public static function viewAddons( $content, $fluid = 0 ){

        $layout_path = JPATH_ROOT . '/components/com_sppagebuilder/layouts';

        $doc = JFactory::getDocument();

        if (is_array($content)) {
            $output = '';

            foreach ($content as $row) {
                $row->settings->dynamicId = $row->id;

                // Row Visibility and ACL
                if ( isset($row->visibility) && !$row->visibility ) {
                   continue;
                }

                if($fluid == 1) {
                  $row->settings->fullscreen = 1;
                }

                $row_layout = new JLayoutFile('row.start', $layout_path);
                $output .= $row_layout->render(array('options' => $row->settings));

                foreach ($row->columns as $column) {

                    $column->settings->cssClassName = $column->class_name;
                    $column->settings->cssClassName = str_replace('column-parent ', '', $column->settings->cssClassName);
                    $column->settings->cssClassName = str_replace('active-column-parent', '', $column->settings->cssClassName);
                    $column->settings->dynamicId    = $column->id;

                    // Column Visibility and ACL
                    if ( isset($column->visibility) && !$column->visibility ) {
                       continue;
                    }

                    $column_layout = new JLayoutFile('column.start', $layout_path);
                    $output .= $column_layout->render(array('options' => $column->settings));

                    foreach ($column->addons as $key => $addon) {
                        // Addon Visibility and ACL
                        if ( isset($addon->visibility) && !$addon->visibility ) {
                          continue;
                        }

                        // ACL
                        $access = true;
                        $authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
                        if(isset($addon->settings->acl)) {
                            $access_list = $addon->settings->acl;
                            $access = false;
                            foreach ($access_list as $acl) {
                               if(in_array($acl, $authorised)) {
                                    $access = true;
                                }
                            }

                            unset($addon->settings->acl);
                        }

                        if(!$access) {
                            continue;
                        }

                        // End ACL

                        if ( isset($addon->type) && $addon->type === 'inner_row' ) {
                            $output .= self::viewAddons(array($addon), 1);
                        } else {

                            $addon_name = $addon->name;
                            $class_name = 'SppagebuilderAddon' . ucfirst($addon_name);
                            $addon_path = AddonParser::getAddonPath( $addon_name );

                            if(file_exists($addon_path . '/site.php')) {

                                $addon_layout = new JLayoutFile('addon.start', $layout_path);
                                $output .= $addon_layout->render(array('addon'=>$addon)); // start addon

                                require_once $addon_path . '/site.php';

                                if ( class_exists( $class_name ) ) {
                                    $addon_obj  = new $class_name($addon);  // initialize addon class
                                    $output .= $addon_obj->render();

                                    // Scripts
                                    if ( method_exists( $class_name, 'scripts' ) ) {
                                      $scripts = $addon_obj->scripts();
                                      if(count($scripts)) {
                                        foreach ($scripts as $key => $script) {
                                          $doc->addScript($script);
                                        }
                                      }
                                    }

                                    // JS
                                    if (method_exists($class_name, 'js')) {
                                        $doc->addScriptDeclaration($addon_obj->js());
                                    }

                                    // Stylesheets
                                    if ( method_exists( $class_name, 'stylesheets' ) ) {
                                      $stylesheets = $addon_obj->stylesheets();
                                      if(count($stylesheets)) {
                                        foreach ($stylesheets as $key => $stylesheet) {
                                          $doc->addStyleSheet($stylesheet);
                                        }
                                      }
                                    }

                                    // css
                                    if (method_exists($class_name, 'css')) {
                                      $doc->addStyleDeclaration($addon_obj->css());
                                    }

                                } else {
                                  $output .= AddonParser::spDoAddon(AddonParser::generateShortcode($addon));
                                }

                                $addon_layout = new JLayoutFile('addon.end', $layout_path);
                                $output .= $addon_layout->render(); // end addon
                            }
                        }
                    }

                    $column_layout = new JLayoutFile('column.end', $layout_path);
                    $output .= $column_layout->render(array('options' => $column->settings));
                }

                $row_layout = new JLayoutFile('row.end', $layout_path);
                $output .=  $row_layout->render(array('options' => $row->settings));
            }

            return htmlspecialchars_decode(AddonParser::spDoAddon($output));
        }else{
           return '<p>'.$content.'</p>';
        }

    }

    public static function generateShortcode($addon){

      if (!empty($addon->settings)) {
          $addon->settings->dynamicId = $addon->id;
          $ops = AddonParser::generateShortcodeOps($addon->settings);
      }

      $output = '[sp_'.$addon->name;
      if (isset($ops['default'])) {
        $output .= $ops['default'];
      }
      $output .= ']';
      if (isset($ops['repeat'])) {
        $output .= $ops['repeat'];
      }
      $output .= '[/sp_'.$addon->name.']';

      return $output;
    }

    public static function generateShortcodeOps( $ops ) {
      $default = '';
      $repeat  = '';

      foreach ( $ops as $key => $val ) {
        if ( !is_array($val) ) {
          $default .= ' '.$key.'="'.htmlspecialchars($val).'"';
        }
        else
        {
          $temp = '';
          foreach ( $val as $innerKey => $innerVal ) {
            $temp .= '['.$key;
            foreach ( $innerVal as $inner_key => $inner_val) {
              $temp .= ' '. $inner_key .'="'.htmlspecialchars( $inner_val ).'"';
            }
            $temp .= '][/' . $key . ']';
          }
          $repeat .= $temp;
        }
      }

      if ( $default ) $result['default'] = $default;
      if ( $repeat ) $result['repeat'] = $repeat;

      return $result;
    }


    // Get list of plugin addons
    private static function getPluginsAddons() {
        $path = JPATH_PLUGINS . '/sppagebuilder';
        if(!JFolder::exists($path)) return;

        $plugins = JFolder::folders($path);
        if(!count($plugins)) return;

        $elements = array();
        foreach ($plugins as $plugin) {
            if(JPluginHelper::isEnabled('sppagebuilder', $plugin)) {
                $addons_path = $path . '/' . $plugin . '/addons';
                if(JFolder::exists($addons_path)) {
                    $addons = JFolder::folders($addons_path);
                    foreach ($addons as $addon) {
                        $path = $addons_path . '/' . $addon;
                        if(JFile::exists($path . '/site.php')) {
                            $elements[$addon] = $path;
                        }
                    }
                }
            }
        }

        return $elements;
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
}


function spAddonAtts( $pairs, $atts, $shortcode = '' ) {
    $atts = (array)$atts;
    $out = array();
    foreach($pairs as $name => $default) {
        if ( array_key_exists($name, $atts) )
            $out[$name] = $atts[$name];
        else
            $out[$name] = $default;
    }

    return $out;
}

AddonParser::getAddons();
