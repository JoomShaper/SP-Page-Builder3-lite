<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SppagebuilderAddonAlert extends SppagebuilderAddons{

	public function render() {

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$type = (isset($this->addon->settings->alrt_type) && $this->addon->settings->alrt_type) ? ' sppb-alert-' . $this->addon->settings->alrt_type : '';
		$title = (isset($this->addon->settings->title) && $this->addon->settings->title) ? $this->addon->settings->title : '';
		$heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : '';
		$close = (isset($this->addon->settings->close) && $this->addon->settings->close) ? $this->addon->settings->close : 0;
		$text = (isset($this->addon->settings->text) && $this->addon->settings->text) ? $this->addon->settings->text : '';

		if($text) {

			$output  = '<div class="sppb-addon sppb-addon-alert ' . $class .'">';
			$output  .= '<' . $heading_selector . ' class="sppb-addon-title">' . $title .'</' . $heading_selector . '>';
			$output .= '<div class="sppb-addon-content">';
			$output .= '<div class="sppb-alert' . $type . ' sppb-fade in">';
			$output .= ($close) ? '<button type="button" class="close" data-dismiss="sppb-alert"><span aria-hidden="true">&times;</span></button>' : '';
			$output .= $text;
			$output .= '</div>';
			$output .= '</div>';
			$output .= '</div>';

			return $output;
		}

		return;
	}
}
