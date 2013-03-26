<?php

class owcms_user {
    
    private $user_exists = false;
    
	public function __construct($selector = '') {
		
		if (trim($selector)=='') {
			
			$auth = new owcms_auth;
			
			if ($auth->is_logged_in(true)) {
				
				$selector = 'id:'.$auth->get_current_user_id();
				
			}
		
		}
		
		if (trim($selector)!='') {
		
			$selects = explode(':', $selector);
			
			$selects[0] = trim($selects[0]);
			$selects[1] = mysql_real_escape_string(trim($selects[1]));
			
			switch ($selects[0]) {
				
				case 'id':
					$selector_query = "`id`='$selects[1]'";
				break;
				
				case 'email':
					$selector_query = "`email`='$selects[1]'";
				break;
				
				default:
					die('Could not select with identifier "'.$selects[0].'"!');
				break;
				
			} /* switch ($selects[1]) */
		
			$query = mysql_query("SELECT * FROM `users` WHERE $selector_query");
		
			if (mysql_num_rows($query)==1) {
			
				$this->array = mysql_fetch_array($query);
				$this->user_exists = true;
			
			}
			elseif (mysql_num_rows($query) > 1) {
				trigger_error("User query returned too many users!", E_USER_NOTICE);
				$this->user_exists = true;
			}
			else {
				trigger_error("User query returned no users", E_USER_NOTICE);
			}
		
		}
         
    }
    
    public function details($detail) {
        
        if (!$this->user_exists) {
	        
	        return false;
	        
        }
		
		$array = $this->array;
		
		switch ($detail) {
		
			case 'id':
				$output .= $array['id'];
			break;
			case 'name_first':
				$output .= stripslashes($array['name_first']);
			break;
			case 'name_last':
				$output .= stripslashes($array['name_last']);
			break;
			case 'name_full':
				$output .= stripslashes($array['name_first']).' '.stripslashes($array['name_last']);
			break;
			case 'email':
				$output .= stripslashes($array['email']);
			break;
			case 'role':
				$output .= $array['role'];
			break;
			case 'last_login':
				$output .= strtotime($array['last_login']);
			break;
			case 'current_login':
				$output .= $array['current_login'];
			break;
			default:
				$output .= $array[$detail];
			break;
			
		} /* switch ($detail) */
		
		return stripslashes($output);
				
	} /* function details() */
	
	
	public function set_detail($detail_name, $new_value = '') {
		
		if (!$this->user_exists) {
			
			return false;
			
		}
		
		/* Sets new value for a user detail */
		
		if (!$detail_name) {
			
			trigger_error("No detail provided to set", E_USER_ERROR);
			return false;
			
		}
		
		$new_value = mysql_real_escape_string($new_value);
		
		$user_id = $this->details('id');
		
		mysql_query("UPDATE `users` SET `$detail_name`='$new_value' WHERE `id`='$user_id'") or die(mysql_error());
		
		return true;
		
	}
	
	
	public function is_logged_in($param1 = false) {
		
		/* Returns true if logged in, false if not. */
		
		$auth = new owcms_auth;
		
		return $auth->is_logged_in($param1);
		
	}
	
	
	public function user_exists() {
		
		return $this->user_exists;
		
	}
	
	
	public function get_current_logins() {
		
		/* Returns array of current logins */
		
		return json_decode($this->details('current_logins'), true);
		
	}
	
	
	public function add_current_login($new_current_login) {
		
		if (!$new_current_login) {
			
			trigger_error("No new current login data to add", E_USER_ERROR);
			return false;
			
		}
		
		if (ALLOW_CONCURRENT_LOGINS == true) {
			
			$current_logins = $this->get_current_logins();
			
			$current_logins[$new_hash] = $new_current_login;

		}
		else {
			
			$current_logins = array($new_hash => $new_current_login);
			
		}

		return $this->set_detail('current_logins', json_encode($current_logins));
		
	}
	
	
	public function remove_current_login($current_login_hash) {
		
		/* Remove a current login from the array of current logins. Returns if successful or not */
		
		if (!$current_login_hash) {
			
			trigger_error('No login hash to remove!', E_USER_ERROR);
			
			return false;
			
		}
		
		$current_logins = $this->get_current_logins();
		
		unset($current_logins[$current_login_hash]);
		
		return $this->set_detail('current_logins', json_encode($current_logins));
		
	}
	
	
	public function is_admin() {
		
		if (!$this->is_logged_in(true))
			return false;
		
		return ($this->details('role') == 'admin');
		
	}
	
	
	public function admin_check() {
		
		/* Redirects to login if not admin. */
		
		$this->is_logged_in();
		
		if (!$this->is_admin()) {
			
			redirect(LOGIN_URL.'?login_error=admin', 'Insufficient Privileges', 'You don\'t have sufficient privileges to access that content.');
			
		}
		
		return true;
		
	}
	
	/* DEPRECATED */
	
	public function admin() {
		
		trigger_error("DEPRECATED", E_USER_ERROR);
		
		if ($this->details('role')!='admin') {
		
			$ip = mysql_real_escape_string( $_SERVER['REMOTE_ADDR'] );
			$page = mysql_real_escape_string( $_SERVER['PHP_SELF'] );
			
			mysql_query("INSERT INTO log (ip, name, value) VALUES('$ip', 'not_admin', 'Page Attempt: $page') ");
			if(headers_sent()) {
				echo '<center><br /><h2>You must be logged in to view this page!</h2></center>';
			}
			else{header('Location: /?action=admin');}
			exit(0);
		}
		
	} /* admin() */
	
	public function logged_in($return = false) {
		
		trigger_error("DEPRECATED", E_USER_ERROR);
		
		if (trim($_COOKIE['oweb_auth-'.SHORT_NAME])!='') {
			$cookie = new owcms_cookie;
			$auth = unserialize($cookie->denc($_COOKIE['oweb_auth-'.SHORT_NAME]));
		}
		elseif (trim($_SESSION['oweb_auth-'.SHORT_NAME])!='') {
			$cookie = new owcms_cookie;
			$auth = unserialize($cookie->denc($_SESSION['oweb_auth-'.SHORT_NAME]));
		}
		else {
			if ($return) {
				return false;
				exit;
			}
			else {
				$ip = mysql_real_escape_string( $_SERVER['REMOTE_ADDR'] );
				$page = mysql_real_escape_string( $_SERVER['PHP_SELF'] );
				
				mysql_query("INSERT INTO log (ip, name, value) VALUES('$ip', 'not_logged_in', 'Page Attempt: $page') ");
				if(headers_sent()) {
					echo '<center><br /><h2>You must be logged in to view this page!</h2></center>';
				}
				else{
					$this->logout("Please login to view this content", "error");
				}
				exit;
			}
		}
		
		$cookie_id = mysql_real_escape_string($auth['id']);
		$cookie_current_login = mysql_real_escape_string($auth['current_login']);
		
		$query = mysql_query("SELECT * FROM `users` WHERE `id`='$cookie_id'");
		
		print_r($auth);
		echo 'id: '.$cookie_id;
		exit;
		
		if (mysql_num_rows($query)!=1) {
			$this->logout("Your account couldn't be found!", "error");
			exit;
		}
		else {
			$array = mysql_fetch_array($query);
			if ($array['current_login']!=$cookie_current_login) {
				$this->logout("Your session data has been corrupted, or you logged in at a different location.", "notice");
				exit;
			}
		}
		
	}
	
	public function login($type, $pre_email, $pre_password) {

		$site = new owcms_site();
		$pass = new owcms_password;

		$attempt_email = mysql_real_escape_string(stripslashes($pre_email));
		
		$attempt_query = mysql_query("SELECT `id`, `email`, `password`, `current_login` FROM `users` WHERE `email` = '$attempt_email' LIMIT 1") or die(mysql_error());
		$attempt_user = mysql_fetch_array($attempt_query);
		
		$id = $attempt_user['id'];
		$email = $attempt_user['email'];
		$password_hash = $attempt_user['password'];
		
		if($pass->comparePassword($pre_password, $password_hash)) {
			
			$id = mysql_real_escape_string(stripslashes($id));
			$domain = DOMAIN;
			
			if (trim($attempt_user['current_login'])=='')
				$current_login = hash('sha512', mt_rand());
			else
				$current_login = $attempt_user['current_login'];
			
			mysql_query("UPDATE `users` SET `last_login` = NOW(), `current_login` = '$current_login' WHERE `id` = '$id' LIMIT 1") or die(mysql_error());
			
			$cookie = new owcms_cookie;
			$value = $cookie->enc(serialize(array('id' => $id, 'current_login' => $current_login)));

			if ($type == 'remember') {
				
				setcookie('oweb_auth-'.SHORT_NAME, $value, time()+1209600, "/", ".$domain"); //alive for 2 weeks
				
			}
			else {

				$_SESSION['oweb_auth-'.SHORT_NAME] = $value;
				
			}
			
			header("Location: ".LOGIN_REDIRECT);
			exit;
			
		}
		else {
			
			header("Location: ".LOGIN_URL.'?login_fail=true');
			exit;
			
		}
		
	} /* login() */
	
	public function logout($pre_msg = '', $pre_type = '') {
		
		/*	$pre_msg	=	Message to display on login page once logged out
			$pre_type	=	Type of message being displayed ("success", "error", etc) */		
		
		$site = new owcms_site();
		
		$domain = DOMAIN;
		
		if (trim($pre_type)!='') {
			$message = urlencode($pre_type).'/';
		}
		else {
			$message = '';
		}
		
		if (trim($pre_msg)!='') {
			$message .= rtrim(base64_encode(($pre_msg)), "=").'/';
		}
		
		if ($_SESSION['oweb_auth-'.SHORT_NAME]) {
			$_SESSION = array();
			session_destroy();
			header("Location: /?action=2".$message);
			exit;
		}
		else {
		
			$domain = str_replace('http://', '', URL);
			$domain = str_replace('wwww.', '', $domain);
		
			setcookie('oweb_auth-'.SHORT_NAME, $id, time()-1209600, "/", ".$domain"); //alive for 2 weeks
			header("Location: /?action=2".$message);
			exit;
		}
		
	} /* logout() */
	
	
	public function signup($email = '', $details = array()) {
		
		/* Registers a new user
			
			$email		Email of new user (required)
			$details	Values of user details (name_first, name_last, etc) */
		
		if (trim($email)=='') {
			return 'Please enter a valid email';
		}
		
		global $db;
		
		$details['email'] = $email;
		
		if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $details['email']))
			return 'Please enter a valid email';
		
		$user = new owcms_user('email:'.$details['email']);
		
		if ($user->user_exists === true)
			return 'User already exists with the email '.$details['email'];
		
		/* Password */
		$password = new owcms_password;
		
		if (!isset($details['password'])) {
        
	        $details['password'] = $password->generate_password(9,4);
			
		}
		elseif (trim($details['password']) == '') {
			return 'Password cannot be blank!';
		}
		
		if (strlen($details['password']) <= 6 || strlen($details['password'] >= 20))
			return 'Password must be between 6 and 20 character long';
		
		$details['password'] = $password->getPasswordHash( $password->getPasswordSalt(), $details['password'] );

		foreach ($details as $field => $v)
            $ins[] = ':' . $field;

        $ins = implode(',', $ins);
        $fields = implode(',', array_keys($details));
        $sql = "INSERT INTO `users` ($fields) VALUES ($ins)";
        
        $dbh = $db->prepare($sql);
        foreach ($details as $f => $v) {
            $dbh->bindValue(':' . $f, $v);
        }
		
		return $dbh->execute();
		
	}
    
} /* class user */

?>