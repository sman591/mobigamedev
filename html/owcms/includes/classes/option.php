<?php

class owcms_option {
    
	public function details($detail = '', $return = '') {

		$detail = mysql_real_escape_string($detail);

		$array = mysql_fetch_array(mysql_query("SELECT * FROM options WHERE name='$detail' LIMIT 1"));

		return $array['content'];
		
	} /* function details() */
    
} /* class options */

?>