<?php
/**
 * @package SP Page Builder
 * @author JoomShaper http://www.joomshaper.com
 * @copyright Copyright (c) 2010 - 2016 JoomShaper
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/
//no direct accees
defined ('_JEXEC') or die ('restricted aceess');

class SppagebuilderAddonTestimonial extends SppagebuilderAddons {

	public function render() {

		$class = (isset($this->addon->settings->class) && $this->addon->settings->class) ? $this->addon->settings->class : '';
		$style = (isset($this->addon->settings->style) && $this->addon->settings->style) ? $this->addon->settings->style : '';
		$title = (isset($this->addon->settings->title) && $this->addon->settings->title) ? $this->addon->settings->title : '';
		$heading_selector = (isset($this->addon->settings->heading_selector) && $this->addon->settings->heading_selector) ? $this->addon->settings->heading_selector : 'h3';

		//Options
		$review = (isset($this->addon->settings->review) && $this->addon->settings->review) ? $this->addon->settings->review : '';
		$name = (isset($this->addon->settings->name) && $this->addon->settings->name) ? $this->addon->settings->name : '';
		$company = (isset($this->addon->settings->company) && $this->addon->settings->company) ? $this->addon->settings->company : '';
		$avatar = (isset($this->addon->settings->avatar) && $this->addon->settings->avatar) ? $this->addon->settings->avatar : '';
		$avatar_width = (isset($this->addon->settings->avatar_width) && $this->addon->settings->avatar_width) ? $this->addon->settings->avatar_width : '';
		$avatar_position = (isset($this->addon->settings->avatar_position) && $this->addon->settings->avatar_position) ? $this->addon->settings->avatar_position : 'left';
		$link = (isset($this->addon->settings->link) && $this->addon->settings->link) ? $this->addon->settings->link : '';
		$link_target = (isset($this->addon->settings->link_target) && $this->addon->settings->link_target) ? ' target="' . $this->addon->settings->link_target . '"' : '';

		//Output
		$output  = '<div class="sppb-addon sppb-addon-testimonial ' . $class . '">';
		$output .= ($title) ? '<'.$heading_selector.' class="sppb-addon-title">' . $title . '</'.$heading_selector.'>' : '';
		$output .= '<div class="sppb-addon-content">';
		$output .= '<div class="sppb-media">';

		if ($avatar) {
			$output .= '<a' . $link_target . ' class="pull-'.$avatar_position.'" href="'.$link.'">';
			$output .= '<img class="sppb-media-object" src="'.$avatar.'" width="' . $avatar_width . '" alt="'.$name.'">';
			$output .= '</a>';
		}

		$output .= '<div class="sppb-media-body">';
		$output .= '<blockquote>';
		$output .= $review;
		$output .= '<footer><strong>'.$name.'</strong> <cite>'.$company.'</cite></footer>';
		$output .= '</blockquote>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;

	}
}
