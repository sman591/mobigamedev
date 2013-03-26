<?php

class owcms_password {

	function getPasswordSalt()
	{
	    return substr( str_pad( dechex( mt_rand() ), 8, '0', STR_PAD_LEFT ), -8 );
	}
	
	// calculate the hash from a salt and a password
	function getPasswordHash( $salt, $password )
	{
	    return $salt . ( hash( 'sha512', $salt . $password ) );
	}
	
	// compare a password to a hash
	function comparePassword( $password, $hash )
	{
	    $salt = substr( $hash, 0, 8 );
	    return $hash == $this->getPasswordHash( $salt, $password );
	}
	
	// get a new hash for a password
	//$hash = getPasswordHash( getPasswordSalt(), NEWPASSWORD );
	
	public function generate_password($length=9, $strength=0) {
	
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		
		if ($strength & 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%';
		}
	 
		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}

} /* class password */

?>