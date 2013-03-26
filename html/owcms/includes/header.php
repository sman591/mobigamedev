<?php 
/*	Olivera Web CMS - owCMS 2.0
	Origianl Build Site: http://media.oweb.co
	
	Lead Dev: Stuart Olivera
	Contact: stuart@oliveraweb.com

	********* READ ME *********

	THIS PRODUCT IS NOT TO BE SOLD, SHARED, OR IN
	ANY WAY DISTRIBUTED UNLESS GIVEN EXPLICET
	PERMISSION FROM OLIVERA WEB.


	****** DO NOT MODIFY ******			  
	Everything in /owcms/ is modified during normal upgrades

*/

/* SITE SETUP */

ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE);

require_once 'classes/site.php';
$site = new owcms_site();
$site->constants();

/* Local, Testing, Staging, and Live Switches */
switch ($site->current_env()) {
	
	case 'LOCAL':
		
		error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE);
	
	break;
	case 'TEST':
	
		error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE); 
	
	break;
	case 'STAGE':
	
		error_reporting(E_ERROR); 
	
	break;
	case 'LIVE':
	default:
	
		error_reporting(E_ERROR);
	
	break;
	
}

if (DEBUG === true)
	error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE);

/* Connect to MySQL Database */
$mysql_connection = mysql_connect(MYSQL_DATABASE, MYSQL_USER, MYSQL_PASSWORD);

if (!$mysql_connection) {
	if ($site->current_env() != 'LIVE')
		die(MYSQL_CONNECT_FAIL.' '. mysql_error());
	else
		die(MYSQL_CONNECT_FAIL);
}

mysql_select_db(MYSQL_TABLE, $mysql_connection);

global $db;

try {
	$db = new PDO('mysql:host='.MYSQL_DATABASE.';dbname='.MYSQL_TABLE, MYSQL_USER, MYSQL_PASSWORD);
}
catch(PDOException $e) {

	error_log($e->getMessage());	    
	
	if ($site->current_env() != 'LIVE')
		echo $e->getMessage().'<br />';
	
    die("A database error was encountered");
    
}

/* Start session for auth & other uses */
if (ENABLE_SESSIONS)
	session_start();


/* Function to output messages instead of redirecting if headers have already been sent */
function redirect($url = '', $message = false, $submessage = false) {

	$page = new owcms_page(false);
	
	if ($page->is_dynamic()) {
		
		echo 'REDIRECT:'.$url;
		exit;
		
	}
	elseif (headers_sent()) {
		
		if ($message)
			echo '<h2>'.$message.'</h2>';
			
		if ($submessage)
			echo '<p>'.$submessage.'</p>';
			
		exit;
		
	}
	
	header('Location: http://'.DOMAIN.$url, true, 307);
	exit;
	
}


/* CLASSES */

require('classes/cookie.php');

require('classes/auth.php');

require('classes/user.php');
$user = new owcms_user();

require('classes/password.php');

require('classes/option.php');

require('classes/page.php');

include(FILEPATH.'/includes/includes.php');

?>