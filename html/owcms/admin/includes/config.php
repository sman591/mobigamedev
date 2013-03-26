<?php

$config = array();

global $config;

/* 
	Live (default) Enviornment Settings 
*****************************************/

/*  URL  */
$config['LIVE']['DOMAIN']				= 'media.oweb.co';								/* Domain of website */
$config['LIVE']['URL_PATH']				= '';											/* Path of website after domain. No trailing slash. */
$config['LIVE']['URL']					= $config['LIVE']['DOMAIN'].$config['LIVE']['URL_PATH'];	/* URL of website, without http:// or trailing slash */


/*  File Paths  */

$config['LIVE']['ROOT_FILEPATH']		= realpath(dirname(__FILE__).'/../../');		/* Path to site root. No trailing slash. */
$config['LIVE']['FILEPATH']				= $config['LIVE']['ROOT_FILEPATH'].'/html';		/* Path to public website content. No trailing slash.*/
$config['LIVE']['FILES_FILEPATH']		= $config['LIVE']['ROOT_FILEPATH'].'/files';	/* Path to files. No trailing slash. */


/*  Site  */
$config['LIVE']['SITE_NAME']			= 'oWeb Media';									/* Default name of site. Note the <title> tag is managed by the CMS */
$config['LIVE']['SHORT_NAME']			= 'owmedia';									/* Short name of website for unique ID's, auth, etc */
$config['LIVE']['ENABLE_SESSIONS']		= true;											/* Automatically enable $_SESSION use for site; session_start() for every page */
$config['LIVE']['TEMPLATE']				= 'owmedia';									/* Template in /includes/templates/ to use */


/*  Admin  */
$config['LIVE']['ADMIN_TITLE']			= $config['LIVE']['SITE_NAME'];					/* Title of website for the admin */
$config['LIVE']['ADMIN_LOCATION']		= '/siteadmin/'; 								/* Location of the main owCMS admin. Include trailing slash. */
$config['LIVE']['LOGIN_REDIRECT']		= '/#/files/';									/* Location which users are directed to once logged in. Include trailing slash.*/
$config['LIVE']['LOGIN_URL']			= '/signin/';									/* URL of where users can login */
$config['LIVE']['ADMIN_TEMPLATE']		= 'default';									/* Template of owCMS admin in /owcms/includes/templates/ to use */


/* Auth */
$config['LIVE']['ALLOW_CONCURRENT_LOGINS']	= true;										/* Whether or not to allow multiple concurrent logins of the same user */


/*  MySQL  */
$config['LIVE']['MYSQL_DATABASE']		= 'localhost';
$config['LIVE']['MYSQL_USER']			= 'owmedia';
$config['LIVE']['MYSQL_PASSWORD']		= '2je5zCSi6P7kgQKwLq';
$config['LIVE']['MYSQL_TABLE']			= 'owmedia';
$config['LIVE']['MYSQL_CONNECT_FAIL']	= 'Sorry, there was an error connecting to our databases. Please try again later.'; /* What to show when MySQL cannot connect. */



/* 
	Staging Enviornment Settings 
*****************************************/

/*  URL  */
$config['STAGE']['DOMAIN']				= 'stage.'.$config['LIVE']['DOMAIN'];
$config['STAGE']['URL_PATH']			= '';
$config['STAGE']['URL']					= $config['STAGE']['DOMAIN'].$config['STAGE']['URL_PATH'];


/*  MySQL  */
$config['STAGE']['MYSQL_DATABASE']		= 'localhost';
$config['STAGE']['MYSQL_USER']			= 'ow_stagi_owmedi';
$config['STAGE']['MYSQL_PASSWORD']		= 'b9yUwiWv3g73PETiZU';
$config['STAGE']['MYSQL_TABLE']			= 'ow_stagi_owmedi';



/* 
	Testing Enviornment Settings 
*****************************************/

/*  URL  */
$config['TEST']['DOMAIN']				= 'test.'.$config['LIVE']['DOMAIN'];
$config['TEST']['URL_PATH']				= '';
$config['TEST']['URL']					= $config['TEST']['DOMAIN'].$config['TEST']['URL_PATH'];


/*  MySQL  */
$config['TEST']['MYSQL_DATABASE']		= 'localhost';
$config['TEST']['MYSQL_USER']			= 'ow_testi_owmedi';
$config['TEST']['MYSQL_PASSWORD']		= 'Chy63rRo74UBUpbbRb';
$config['TEST']['MYSQL_TABLE']			= 'ow_testi_owmedi';



/* 
	Local Enviornment Settings 
*****************************************/

/*  URL  */
$config['LOCAL']['DOMAIN']				= 'test.'.$config['LIVE']['DOMAIN'];


/*  MySQL  */
$config['LOCAL']['MYSQL_DATABASE']		= 'localhost';
$config['LOCAL']['MYSQL_USER']			= '';
$config['LOCAL']['MYSQL_PASSWORD']		= '';
$config['LOCAL']['MYSQL_TABLE']			= '';



/* 
	Security Keys
	
	DO NOT SHARE - These are responsible 
	for the security of your website!
	
*****************************************/

$config['LIVE']['COOKIE_KEY']			= '8be1c04f8a57e47dbc61ca62f6979b83b433748b638f0e5966ef21fe732e88e4f8e4dab448aa2faeaab152b241ad2caf39048a3d8cc94d6c723b9117d5d218d9';
$config['LIVE']['COOKIE_IV']			= '4579c6fb64ecddecea9b0fb84e2d2588';
$config['LIVE']['AUTH_COOKIE_NAME']		= 'auth';

?>