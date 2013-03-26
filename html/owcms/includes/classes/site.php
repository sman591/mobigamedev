<?php

class owcms_site {
	
	public $url = URL;
	
	public function current_env() {
	
		/* Returns current enviornment (LIVE, STAGE, TEST, LOCAL). Default is LIVE. */
		
		require_once($_SERVER['DOCUMENT_ROOT'].'/includes/config.php');
		
		global $config;
		
		if (strstr(ini_get("include_path"), "MAMP")!==false) {
		
			return 'LOCAL';
		
		}
		else {

			switch ($_SERVER['HTTP_HOST']) {
				
				case $config['TEST']['DOMAIN']:

					return 'TEST';
				
				break;
				case $config['STAGE']['DOMAIN']:

					return 'STAGE';
				
				break;
				case $config['LIVE']['DOMAIN']:
				default:

					return 'LIVE';
					
				break;
				
			}
		
		}
	
	}
	
	public function constants() {
		
		/* Only set constants if they aren't already */
		
		if (OWEB_CONSTANTS_SET !== true) {
			
			$this->set_constants();
			
		}
		
	}
	
	private function set_constants() {

		/* Sets constants based on default of $config['LIVE']. If the current enviornment is different, any $config['ENV'] variables will override the LIVE ones */
		
		require_once($_SERVER['DOCUMENT_ROOT'].'/includes/config.php');
		
		foreach ($config['LIVE'] as $key => $value) {
			
			if ($this->current_env() != 'LIVE') {

				if (isset($config[$this->current_env()][$key])) {
					$value = $config[$this->current_env()][$key];
				}
				
			}

			define($key, $value);
			
		}
		
		define('OWEB_CONSTANTS_SET', true);
		
	}
	
}
/* end class site */

?>