<?php

class owcms_auth {
	
	private function get_auth_data() {
		
		/* Return auth data, wheter in a cookie or session */

		$cookie = new owcms_cookie(AUTH_COOKIE_NAME);
		$cookie_data = $cookie->get_cookie();

		return unserialize($cookie_data);
		
	}
	
	private function get_current_login_hash() {
			
		if ($this->is_logged_in()) {
			
			$data = $this->get_auth_data();
			
			return $data['hash'];
			
		}
		
		return false;
		
	}
	
	private function verify_auth_hash() {
		
		$data = $this->get_auth_data();
		
		$current_user_id = $data['user_id'];
		$current_login_hash = $data['hash'];
		
		$user = new owcms_user('id:'.$current_user_id);
		
		if (!is_array($user->get_current_logins()))
			return false;
		
		foreach ($user->get_current_logins() as $index => $login) {
			
			if (in_array($current_login_hash, $login))
				return true;
			
		}
		
		return false;
		
	}
	
	public function get_current_user_id() {
		
		/* Returns the current user ID. If no user, returns false */
		
		if ($this->is_logged_in()) {
			
			$data = $this->get_auth_data();
			
			return $data['user_id'];
			
		}
		
		return false;
		
	}
	
	
	public function is_logged_in($no_redirect = false) {

		/* Returns whether the current visitor is logged in or not */
		
		$data = $this->get_auth_data();
		
		$current_user_id = $data['user_id'];
		
		if (trim($current_user_id)!='') {
			
			
			if (!$this->verify_auth_hash()) {
				
				if (!$no_redirect) {
					redirect(LOGIN_URL.'?login_error=verify_fail', 'Authentication Failed', 'Your session has either expired, or another account activity has reset your login (such as changing your password). Please <a href="'.LOGIN_URL.'">sign in</a> again.');
				}
				else {
					trigger_error('Hash Verification Failed', E_USER_NOTICE);
					return false;
				}
				
			}
			
			
			return true; /* Return true if nothing throws false */
			
		}
		
		if (!$no_redirect) {
			redirect(LOGIN_URL.'?login_error=login', 'Please Sign In', 'You must be <a href="'.LOGIN_URL.'">signed in &raquo;</a> to view this content.');
		}
	
		return false;
			
		
	}
	
	
	public function login($user_selector = false, $user_password = false, $redirect = true) {
		
		/* Attemps to log the current user in. If cannot, will redirect to login page (or output message if headers already sent) */
		
		if (!$user_selector) {
				
			redirect(LOGIN_URL.'?login_error=no_username', 'Login Failed', 'You must supply a user to login to');
			
			return false;
			
		}
		
		$user = new owcms_user($user_selector);
		
		if (!$user->user_exists()) {
			
			redirect(LOGIN_URL.'?login_error=user_unknown', 'Login Failed', 'The user you attempted to login to doesn\'t exist.');
			
			return false;
			
		}
		else {
			
			$password = new owcms_password;
			
			if ($password->comparePassword($user_password, $user->details('password'))) {
				
				/* Update current logins database */
				
				$current_logins = $user->get_current_logins();
				
				$new_hash = hash('sha512', mt_rand());
				
				$new_current_login = array(
										'login_date' 	=> strtotime('now'),
										'user_id'		=> $user->details('id'),
										'hash'			=> $new_hash
									);

				/* Add current login to databsae */
				if (!$user->add_current_login($new_current_login)) {
				
					die('Failed to add login to database');
				
				}
				
				/* Set Cookie */
				$cookie = new owcms_cookie(AUTH_COOKIE_NAME);
				$cookie->set_cookie(serialize($new_current_login), strtotime("+1 months"));
				
				/* Redirect */
				redirect(LOGIN_REDIRECT, 'You are logged in!', '<a href="'.ltrim('/', URL_PATH).'">Click here &raquo;</a> to continue');
				
				return true;
				
				
			}
			else {
			
				redirect(LOGIN_URL.'?login_error=incorrect', 'Login Failed', 'The username and password you supplied do not match.');
			
				return false;
			
			}
			
		}
		
	}
	
	
	public function logout() {
		
		/* Logs out the curernt user */
		
		
		/* Remove current login from databse */
		$user = new owcms_user;
		$user->remove_current_login($this->get_current_login_hash());
		
		/* Destroy Cookie */
		$cookie = new owcms_cookie(AUTH_COOKIE_NAME);
		$cookie->destroy();
		
	}
	
}

?>