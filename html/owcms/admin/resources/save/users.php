<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/owcms/includes/header.php');

$user->admin_check();
if ($_POST['userstable']) {

	$userid = mysql_real_escape_string($_POST['userid']);
	
	$user_table = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `id`='$userid' LIMIT 1"));
	
	$name_first = mysql_real_escape_string($_POST['name_first']);
	$name_last = mysql_real_escape_string($_POST['name_last']);
	$email = mysql_real_escape_string($_POST['email']);
/*
	if (!checkEmail($_POST['email'])) {
		jsonDataReturn('error', 'The email "'.$email.'" is not a valid email address.');
		exit;
	}
*/
	if ($user_table['locked']=='1') { $role = $user_table['role']; }
	else { $role = mysql_real_escape_string($_POST['role']); }
	
	mysql_query("UPDATE `users` SET `name_first`='$name_first', `name_last`='$name_last', `email`='$email', `role`='$role' WHERE `id`='$userid' ") or die(mysql_error());
	
	if (($_POST['newUser']=='yes')) {
		
		$email_test = mysql_query("SELECT `id`,`name_first`,`name_last` FROM `users` WHERE `email`='$email'") or die(mysql_error());
		
		while ($array = mysql_fetch_array($email_test)) {
			jsonDataReturn('error', 'The email "'.$email.'" already belongs to "'.$array['name_first'].' '.$array['name_last'].'"!');
			exit;
		}
		
		if (trim($_POST['newUserPassword'])=='') {
			jsonDataReturn('error', 'You must specify a password for the new user');
			exit;
		}
	
		$password = new owcms_password;
		$hash = $password->getPasswordHash( $password->getPasswordSalt(), $_POST['newUserPassword']);
		
		mysql_query("INSERT INTO users (`name_first`, `name_last`, `email`, `password`, `role`) VALUES('$name_first', '$name_last', '$email', '$hash', '$role' ) ") or die(mysql_error());
		$hash = null;
		
	}
	
	jsonDataReturn('success', '');
	exit;
}
// end if post userstable

if ($_POST['userDelete']=='true') {
	$id = mysql_real_escape_string( $_POST['id'] );
	$name = mysql_real_escape_string( $_POST['name'] );
	$array = mysql_fetch_array(mysql_query("SELECT * FROM users WHERE id='$id' AND locked='0' LIMIT 1"));
	$uid = $array['id'];
	if (mysql_query("DELETE FROM users WHERE id='$uid' AND locked='0'") or die(mysql_error())) {
		jsonDataReturn('success', '');
	}
	else { jsonDataReturn('error', 'Could not delete "'.$name.'"!'); }
} /*  if ($_POST['userDelete']=='true') */

if ($_POST['userPassword']=='true') {
	
	$id = mysql_real_escape_string($_POST['userid']);
	
	$password = new owcms_password;
		
	$array = mysql_fetch_array(mysql_query("SELECT `id`, `password` FROM `users` WHERE `id` = '$id' LIMIT 1"));
	
	if (trim($_POST['pass1'])==''||trim($_POST['pass2'])=='') {
		jsonDataReturn('error', 'New password can not be blank!');
		exit;
	}
	if ($_POST['pass1']==$_POST['pass2']) {
		$hash = $password->getPasswordHash( $password->getPasswordSalt(), $_POST['pass1']);
		mysql_query("UPDATE `users` SET `password`='$hash' WHERE `id` = '$id'") or die(mysql_query());
		$hash = null;
		jsonDataReturn('success', '');
	}
	else {
		jsonDataReturn('error', 'Passwords do not match!');
		exit;
	}
	
}

?>