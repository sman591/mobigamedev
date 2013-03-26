<?php require_once('../owcms/includes/header.php');

$user = new owcms_user;

$user_details = array(
	'name_first'	=> $_POST['name_first'],
	'name_last'		=> $_POST['name_last'],
	'password'		=> $_POST['password']
	
);

$attempt = $user->signup($_POST['email'], $user_details);

if ($attempt === true) {

	header("Location: /?signup=success");

}
else {

	$return_details = array(
		'name_first'	=> $_POST['name_first'],
		'name_last'		=> $_POST['name_last'],
		'email'			=> $_POST['email']
	);

	header("Location: ".SIGNUP_URL."?signup_error=".base64_encode($attempt)."&signup_details=".base64_encode(json_encode($return_details)));

}

?>