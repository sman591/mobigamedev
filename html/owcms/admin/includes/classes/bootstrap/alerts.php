<?php

class bootstrap_alert {
	
	function __construct($heading = '', $content = '', $type = '', $alert_id = '', $vars = array()) {
		
		/*	$heading	If not blank, is put in  .alert-heading
			$content	If not blank, is put in  .alert-content
			$type		If not null, is appended to  alert-  for the css class of the alert
			$alert_id	If not blank, is put in the  id=""  attribute of  <div class="alert">
			$vars		Custom variables, such as href											 */
		
		$this->heading	= stripslashes(htmlspecialchars_decode($heading));
		$this->content	= stripslashes(htmlspecialchars_decode($content));
		$this->type		= $type;
		$this->id		= $alert_id;
		
		if (is_array($vars)) {
			
			foreach ($vars as $key => $value) {
				
				if (!$this->$key) { /* prevent overwritten variables */
					$this->$key = $value;
				}
				
			} /* foreach */
		
		} /* if (islet($vars)) */
		
	} /* __construct() */
	
	private function html() {
		
		if (isset($this->type)) {
			$css_class .= 'alert-'.$this->type;
		}
		
		if (trim($this->id)) {
			$css_id = 'id="alert-'.$this->id.'"';
		}
		
		if (trim($this->href)!='') {
			$href = $this->href;
		}
		else {
			$href = '#"  data-dismiss="alert';
		}
		
		$html = '<div '.$css_id.' class="alert fade in '.$css_class.'">';
		
		if ($this->close!==false) {
			$html .= '<a class="close" href="'.$href.'">x</a>';
		}
			
		if (trim($this->heading)!='') {
			$html .= '<h4 class="alert-heading">'.$this->heading.'</h4>';
		}
		
		if (trim($this->content)!='') {
			$html .= '<div class="alert-content">
				<p>'.$this->content.'</p>
			</div>';
		}
		
		$html .= '</div>';
		
		return $html;
		
	} /* html() */
	
	public function display() {
		
		return $this->html();
		
	} /* display() */
	
}

?>