<?php require_once('../owcms/includes/header.php');

if ($_POST['saveSettings']) {

	$id = $user->details('id');

	if (!$id) {
		jsonDataReturn('error', 'You must be logged in!');
		exit;
	}
	else {
		
		$name_first = mysql_real_escape_string($_POST['name_first']);
		$name_last = mysql_real_escape_string($_POST['name_last']);
		$email = mysql_real_escape_string($_POST['email']);
		
		mysql_query("UPDATE `users` SET `name_first`='$name_first', `name_last`='$name_last', `email`='$email' WHERE `id` = '$id'") or die(mysql_error());
		
		if ($_POST['changePass']) {
		
			$password = new owcms_password;
		
			$array = mysql_fetch_array(mysql_query("SELECT `id`, `password` FROM `users` WHERE `id` = '$id' LIMIT 1"));
			
			if($password->comparePassword($_POST['cpass'], $array['password'])) {
				if (trim($_POST['pass1'])==''||trim($_POST['pass2'])=='') {
					jsonDataReturn('error', 'New password must not be blank!');
					exit;
				}
				if ($_POST['pass1']==$_POST['pass2']) {
					$hash = $password->getPasswordHash( $password->getPasswordSalt(), $_POST['pass1']);
					mysql_query("UPDATE `users` SET `password`='$hash' WHERE `id` = '$id'") or die(mysql_query());
					$hash = null;
				}
				else {
					jsonDataReturn('error', 'Passwords do not match!');
					exit;
				}
			}
			else {
				jsonDataReturn('error', 'Current passwords do not match!');
				exit;
			}
		}
		
		jsonDataReturn('success', '');
		
	}
} ?>