<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SppagebuilderAddonAccordion extends SppagebuilderAddons {

	public function render() {

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$style = (isset($this->addon->settings->style) && $this->addon->settings->style) ? $this->addon->settings->style : 'panel-default';
		$title = (isset($this->addon->settings->title) && $this->addon->settings->title) ? $this->addon->settings->title : '';
		$heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : 'h3';

		$output   = '';
		$output  = '<div class="sppb-addon sppb-addon-accordion ' . $class . '">';

		if($title) {
			$output .= '<'.$heading_selector.' class="sppb-addon-title">' . $title . '</'.$heading_selector.'>';
		}

		$output .= '<div class="sppb-addon-content">';
		$output	.= '<div class="sppb-panel-group">';

		foreach ($this->addon->settings->sp_accordion_item as $key => $item) {
			$output  .= '<div class="sppb-panel sppb-'. $style .'">';
			$output  .= '<div class="sppb-panel-heading'. (($key == 0) ? ' active' : '') .'">';
			$output  .= '<span class="sppb-panel-title">';

			if($item->icon != '') {
				$output  .= '<i class="fa ' . $item->icon . '"></i> ';
			}

			$output  .= $item->title;
			$output  .= '</span>';
			$output  .= '<span class="sppb-toggle-direction"><i class="fa fa-chevron-right"></i></span>';
			$output  .= '</div>';
			$output  .= '<div class="sppb-panel-collapse"' . (($key != 0) ? ' style="display: none;"' : '') . '>';
			$output  .= '<div class="sppb-panel-body">';
			$output  .= $item->content;
			$output  .= '</div>';
			$output  .= '</div>';
			$output  .= '</div>';
		}

		$output  .= '</div>';
		$output  .= '</div>';
		$output  .= '</div>';

		return $output;
	}
}
