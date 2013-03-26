<?php

class bootstrap_collapse {
	
	function __construct($accordion_id = '', $vars = '') {
		
		/*	$accordion_id*	Appended to "accordion-" as an ID
			$vars			Custom variables, such as class
				class			Adds classes to .accordion
		*/
			
		if (trim($accordion_id)=='') {
			die('ID of accordion cannot be blank!');
		}
		else {
			$this->id = 'accordion-'.$accordion_id;
		}
		
		if (is_array($vars)) {
			
			foreach ($vars as $key => $value) {
				
				if (!$this->$key) { /* prevent overwritten variables */
					$this->$key = $value;
				}
				
			} /* foreach */
		
		} /* if (islet($vars)) */
		
	} /* __construct() */
	
	public function addGroup($header, $content, $existing_groups = '', $return = false) {
		
		if (trim($existing_groups)!=''&&is_array($existing_groups)) {
			$groups = $existing_groups;
		}
		else {
			$groups = $this->groups;
		}
		
		if (!$groups[1]) {
			$i = 1;
		}
		else {
			end($groups);
			$i = key($groups)+1;
		}
		
		$header = str_replace('<groupLink>', '<a class="accordion-toggle '.$this->link_class.'" data-toggle="collapse" data-parent="#'.$this->id.'" href="#'.$this->id.'-body'.$i.'">', $header);
		$header = str_replace('</groupLink>', '</a>', $header);
		
		$group = '<div class="accordion-group">
	      <div class="accordion-heading">
	        '.$header.'
	      </div>
	      <div id="'.$this->id.'-body'.$i.'" class="accordion-body collapse" style="height: 0px;">
	        <div class="accordion-inner">
		        '.$content.'
			</div>
			</div>
	    </div>';
		
		$groups[$i] = $group;
		
		if ($return) {
			return $groups;
		}
		else {
			$this->groups[$i] = $group;
		}
	
	}
	
	private function html($existing_groups) {
		
		$html = '<div id="'.$this->id.'" class="accordion '.$this->class.'">';
			
			if (is_array($existing_groups)) {
				$groups = $existing_groups;
			}
			elseif (is_array($this->groups)) {
				$groups = $this->groups;
			}
			
			foreach ($groups as $key => $value) {
				$html .= $value;
			}
		
		$html .= '</div>';
		
		return $html;
		
	} /* html() */
	
	public function display($existing_groups = '') {
		
		return $this->html($existing_groups);
		
	} /* display() */
	
}

?>