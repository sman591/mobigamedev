<?php

class owcms_cookie {
    
    private $session, $cookie_name;
    
    public function __construct($cookie_name = '', $session = false) {
	    
	    /* Construct cookie with $cookie_name (required) */
	    
	    if ($cookie_name) {
		    
		    $this->cookie_name = 'owcms-'.SHORT_NAME.'_'.$cookie_name;
		    
	    }
	    
	    $this->session = $session;
	    
	    $this->check_cookie_name();
	    
    }
    
    private function check_cookie_name() {
	  	
	  	/* Checks if cookie_name is set */
	  	
	  	if (trim($this->cookie_name)=='') {
	  	
			trigger_error('Must have a cookie name', E_USER_ERROR);
		    return false;
		    
	    }
	    else {
	    
		    return true;
	    
	    }
		
    }
    
    private function get_data() {
	    
	    /* Returns either cookie or session data, dependent on $this->session */

	    if ($this->session) {
		    
		    return $_SESSION[$this->cookie_name];
		    
	    }
	    else {
		    
		    return $_COOKIE[$this->cookie_name];
		    
	    }
	    
    }
    
   	private function enc($text) {
   		
   		/* Open the cipher */
	    $td = mcrypt_module_open('rijndael-256', '', 'cbc', '');
	    $ks = mcrypt_enc_get_key_size($td);
	
	    /* Create key */
	    $key = substr(md5(COOKIE_KEY), 0, $ks);
	
	    /* Intialize encryption */
	    mcrypt_generic_init($td, $key, COOKIE_IV);
   		
   		return urlencode(base64_encode(mcrypt_generic($td, $text)));
   		
   		/* Terminate encryption handler */
	    mcrypt_generic_deinit($td);
   	
   	}
   	
   	private function denc() {
   	
   		if (!$this->cookie_is_set()) {
	   		
	   		return false;
	   		
   		}
   	
   		/* Open the cipher */
	    $td = mcrypt_module_open('rijndael-256', '', 'cbc', '');

	    /* Create the IV and determine the keysize length, use MCRYPT_RAND on Windows instead */
	    $ks = mcrypt_enc_get_key_size($td);
	
	    /* Create key */
	    $key = substr(md5(COOKIE_KEY), 0, $ks);
   	
   		/* Initialize encryption module for decryption */
	    mcrypt_generic_init($td, $key, COOKIE_IV);
	
	    /* Decrypt encrypted string */
	    return mdecrypt_generic($td, base64_decode(urldecode($this->get_data())));
   	
   	}
   	
   	public function set_cookie($new_cookie_value = '', $time = false) {
	   	
	   	/* Sets the cookie. Returns if successful */
	   	
	   	if (!$time) {
		   	
		   	$time = strtotime("+1 week");
		   	
	   	}
	   	
	   	setcookie($this->cookie_name, $this->enc($new_cookie_value), $time, '/', DOMAIN);
	   	
   	}
   	
   	public function cookie_is_set() {
	   	
	   	/* Checks if cookie (or session) is set */
	   	
	   	return (isset($_COOKIE[$this->cookie_name]) || isset($_SESSION[$this->cookie_name]));
	   	
   	}
	
	public function get_cookie() {
		
		/* Returns the content of $_COOKIE[$this->cookie_name]. If it isn't set, will return false */
		
		if (!$this->cookie_is_set()) {
			
			return false;
			
		}
		
		return $this->denc();
	}
	
	public function destroy() {
		
		/* Erases the cookie/session data */
			
		unset($_SESSION[$this->cookie_name]);
		setcookie($this->cookie_name, '', 1, '/', DOMAIN);
		
	}
	
}

?>