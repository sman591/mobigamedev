<?php

class dm_project {
	
	public $exists = false;
	
	public function __construct($selector = '') {
		
		if ($selector===false)
			return false;
		
		if (trim($selector)=='')
			return false;
		
		if (trim($selector)!='') {
		
			$selects = explode(':', $selector);
			
			$selects[0] = trim($selects[0]);
			$selects[1] = mysql_real_escape_string(trim($selects[1]));
			
			switch ($selects[0]) {
				
				case 'id':
					$selector_query = "`id`='$selects[1]'";
				break;
				
				case 'slug':
					$selector_query = "`slug`='$selects[1]'";
				break;
				
				default:
					die('Could not select with identifier "'.$selects[0].'"!');
				break;
				
			} /* switch ($selects[1]) */
		
			$query = mysql_query("SELECT * FROM `projects` WHERE $selector_query");
			
			if (mysql_num_rows($query)==1) {
			
				$this->array = mysql_fetch_array($query);
				$this->exists = true;
			
			}
			else {
				trigger_error("Query returned no or too many projects!", E_USER_NOTICE);
			}
		
		}
         
    }
    
    public function exists() {
	    
	    return $this->exists;
    }
    
    
    public function details($detail) {
        
        if (!$this->exists) {
	        
	        return false;
	        
        }
		
		$array = $this->array;
		
		switch ($detail) {
		
			case 'id':
				$output .= $array['id'];
			break;
			default:
				$output .= $array[$detail];
			break;
			
		} /* switch ($detail) */
		
		return stripslashes($output);
				
	} /* function details() */
	
	
}