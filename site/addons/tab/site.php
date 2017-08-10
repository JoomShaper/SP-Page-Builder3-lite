<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SppagebuilderAddonTab extends SppagebuilderAddons {

	public function render() {

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$style = (isset($this->addon->settings->style) && $this->addon->settings->style) ? $this->addon->settings->style : '';
		$title = (isset($this->addon->settings->title) && $this->addon->settings->title) ? $this->addon->settings->title : '';
		$heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : 'h3';

		//Output
		$output  = '<div class="sppb-addon sppb-addon-tab ' . $class . '">';
		$output .= ($title) ? '<'.$heading_selector.' class="sppb-addon-title">' . $title . '</'.$heading_selector.'>' : '';
		$output .= '<div class="sppb-addon-content sppb-tab">';

		//Tab Title
		$output .='<ul class="sppb-nav sppb-nav-' . $style . '">';
		foreach ($this->addon->settings->sp_tab_item as $key => $tab) {
			$title = (isset($tab->icon) && $tab->icon) ? '<i class="fa ' . $tab->icon . '"></i> ' . $tab->title : $tab->title;
			$output .='<li class="'. ( ($key==0) ? "active" : "").'"><a data-toggle="sppb-tab" href="#sppb-tab-'. ($this->addon->id + $key) .'">'. $title .'</a></li>';
		}
		$output .='</ul>';

		//Tab Contnet
		$output .='<div class="sppb-tab-content sppb-nav-' . $style . '-content">';
		foreach ($this->addon->settings->sp_tab_item as $key => $tab) {
			$output .='<div id="sppb-tab-'. ($this->addon->id + $key) .'" class="sppb-tab-pane sppb-fade'. ( ($key==0) ? " active in" : "").'">' . $tab->content .'</div>';
		}
		$output .='</div>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;

	}

	public function css() {
		$addon_id = '#sppb-addon-' . $this->addon->id;
		$tab_style = (isset($this->addon->settings->style) && $this->addon->settings->style) ? $this->addon->settings->style : '';
		$style = (isset($this->addon->settings->active_tab_color) && $this->addon->settings->active_tab_color) ? 'color: ' . $this->addon->settings->active_tab_color . ';': '';

		$css = '';
		if($tab_style == 'pills') {
			$style .= (isset($this->addon->settings->active_tab_bg) && $this->addon->settings->active_tab_bg) ? 'background-color: ' . $this->addon->settings->active_tab_bg . ';': '';
			if($style) {
				$css .= $addon_id . ' .sppb-nav-pills > li.active > a,' . $addon_id . ' .sppb-nav-pills > li.active > a:hover,' . $addon_id . ' .sppb-nav-pills > li.active > a:focus {';
				$css .= $style;
				$css .= '}';
			}
		} else if ($tab_style == 'lines') {
			$style .= (isset($this->addon->settings->active_tab_bg) && $this->addon->settings->active_tab_bg) ? 'border-bottom-color: ' . $this->addon->settings->active_tab_bg . ';': '';
			if($style) {
				$css .= $addon_id . ' .sppb-nav-lines > li.active > a,' . $addon_id . ' .sppb-nav-lines > li.active > a:hover,' . $addon_id . ' .sppb-nav-lines > li.active > a:focus {';
				$css .= $style;
				$css .= '}';
			}
		}

		return $css;
	}

}
