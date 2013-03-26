<?php

class bootstrap_grid {
	
	function __construct($size = '', $id = '', $vars = '') {
	
		/*	$size		Span size of column
			$id			Unique id of the column (used for HTML/JS identification)
			$vars		Custom variables, such as class
				class		Adds classes to .span-#
		*/
	
		if (is_numeric($size)) {
			$this->size = round($size);
		}
		else {
			$this->size = null;
		}
		
		$this->container_id = $id;
		
		if (is_array($vars)) {
			
			foreach ($vars as $key => $value) {
				
				if (!$this->$key) { /* prevent overwritten variables */
				
					$this->$key = $value;
				
				}
				
			}
		
		}
		
	} /* __construct() */
	
	public function header($title = '', $subtitle = '', $return = false) {
		
		if (trim($title)!=''||trim($subtitle)!='') { /* ensure there is either a title or subtitle to begin with */
			
			$output = '<div class="span-header">
				<h3><span class="title">'.$title.'</span>';
				
			if (trim($subtitle)!='') {
				$output .= '<span class="subtitle">'.$subtitle.'</span>';
			}
			
			$output .= '</h3></div>';
			
			if ($return) {
				return $output;
			}
			else {
				$this->header = $output;
			}
		}
		
	} /* header() */
	
	public function content($content, $return = false) {
		
		if ($return) {
			return $content;
		}
		else {
			$this->content = $content;
		}
		
	} /* content() */
	
	public function display() {
		
		if (isset($this->size)) {
			$span_class = 'span'.$this->size;
		}
		else {
			$span_class = '';
		}
		
		if (trim($this->class)!='') {
			$span_class .= ' '.$this->class;
		}
		
		$span_id	= 'span-'.$this->container_id;
		
		$output = '<div class="'.$span_class.'" id="'.$span_id.'">';
		
		$output .= $this->header;
		
		$output .= $this->content;
		
		$output .= '</div>';
		
		return $output;
		
	}
	
}