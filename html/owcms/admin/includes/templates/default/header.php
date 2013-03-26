<?php

if (in_array($site->current_env(), array('LIVE', 'STAGE'))) {
	$testingMin = '.min';
} else {$testingMin = '';}

if (!$this->is_dynamic()) {

echo '<!DOCTYPE html>
<html lang="en">
    <head>
		<title>'.stripslashes($title).'</title>
		<!--[if lt IE 8]>
			<script type="text/javascript">
			window.location = "/siteadmin/ie.html"
			</script>
		<![endif]-->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="shortcut icon" href="'.ADMIN_URL_PATH.'resources/images/favicon.ico"/>
		<link rel="stylesheet" href="'.ADMIN_URL_PATH.'resources/bootstrap-2.2.2/css/bootstrap.min.css" type="text/css" />
		<link rel="stylesheet" href="'.ADMIN_URL_PATH.'resources/bootstrap-2.2.2/css/bootstrap-responsive.min.css" type="text/css" />
		<link rel="stylesheet" href="'.ADMIN_URL_PATH.'resources/css/style.min.css?v=1.0" type="text/css" />';
		
		echo stripcslashes($option->details('ga'));

	/* If IE 8 */
	echo '<!--[if lt IE 9]>
				<script type="text/javascript">
				$(document).ready(function(){
					modal(\'Unsupported Browser\', \'unsupportedIE\', \'unsupportedIE\');
				});
				</script>
			<![endif]-->';

	echo '</head>';
	
} /* if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') */

/* END HEAD */

/* START BODY */

echo '<body id="page-'.$this->details('slug').'" '.$bodyclass.'>'; ?>

<?php echo stripcslashes($this->details('header')); ?>

<? /* Normal Navigation */	

if (!$this->is_bare()) {

	echo '<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
	<div class="container-fluid">
	<a class="brand" href="/">owCMS</a>';
	
	if ($user->details('id')) {
	
		echo '<div class="btn-group pull-right">
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="icon-user"></i> '.$user->details('email').'
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li><a href="'.ADMIN_LOCATION.'#'.ADMIN_LOCATION.'account/">Account</a></li>
				<li class="divider"></li>
				<li><a href="'.ADMIN_URL_PATH.'user/auth.php?logout=1">Sign Out</a></li>
			</ul>
		</div>';
		
	}
	
	echo '<ul class="nav">';
		$pagesdb = mysql_query("SELECT * FROM `owcms_pages` WHERE enabled = '1' AND position = '0' ORDER BY `id` ASC");
		while ( $page = mysql_fetch_array( $pagesdb ) ) {
			
			if ($page['hasdropdown']=='1') {
				if ($page['slug']=='admin'&&!$user->is_admin()) {}
				else {
					echo '<li class="dropdown '.$this->active($page['id'], $page['position']).'"><a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$this->details('name', $page['id']).' <b class="caret"></b></a>
						<ul class="dropdown-menu">
						<li class="'.$this->active($page['id'], $page['position']).'"><a href="'.ADMIN_LOCATION.$page["slug"].'/">'.$this->details('name', $page['id']).'</a></li>';
							$dropid = $page['id'];
							$dropdb = mysql_query("SELECT * FROM `pages` WHERE position='$dropid' AND enabled = '1' ORDER BY `id` ASC ");
							while ($drop = mysql_fetch_array($dropdb) ) {
								
								if ($drop['hasdropdown']=='1') {
									if ($drop['slug']=='admin'&&!$user->is_admin()) {}
									else {
										echo '
											<li class="divider"></li>
											<li class="nav-header">'.$this->details('name', $page['id']).' '.$this->details('name', $drop['id']).'</li>
											<li class="'.$this->active($drop['id'], $drop['position']).'"><a href="'.ADMIN_LOCATION.$page["slug"].'/'.$drop["slug"].'/">'.$this->details('name', $drop['id']).'</a></li>';
												$drop2id = $drop['id'];
												$drop2db = mysql_query("SELECT * FROM `pages` WHERE position='$drop2id' AND enabled = '1' ORDER BY `id` ASC");
												while ($drop2 = mysql_fetch_array($drop2db) ) {
													echo '<li class="'.$this->active($drop2['id'], $page['position']).'"><a href="'.ADMIN_LOCATION.$page["slug"].'/'.$drop["slug"].'/'.$drop2["slug"].'/">'.$this->details('name', $drop2['id']).'</a></li>';
												}
									}
								}
								else {
									echo '<li class="'.$this->active($drop['id'], $page['position']).'"><a href="'.ADMIN_LOCATION.$page["slug"].'/'.$drop["slug"].'/">'.$this->details('name', $drop['id']).'</a></li>';
								}
							}
						echo '</ul>
					</li>';
				}
			}
			else {
			
				if ($page['slug']) {
					echo '<li class="'.$this->active($page['id'], $page['position']).'"><a href="'.ADMIN_LOCATION.stripslashes($page["slug"]).'/">'.$this->details("name", $page['id']).'</a></li>';
				}
				else {
					echo '<li class="'.$this->active($page['id'], $page['position']).'"><a href="'.ADMIN_LOCATION.'">'.$this->details("name", $page['id']).'</a></li>';
				}
				
			} /* else (if page hasdropdown) */
		}
	echo '
	</ul>
	</div>
	</div></div>'; 

}
	
?>

<div id="page-wrap" class="container-fluid">

<div class="row-fluid">
	<div class="span12">
	
	<!-- REQUIRE JAVASCRIPT -->
	<noscript>
		<style type="text/css">
			.container, .subnav, #main-content {display: none;}
		</style>
		<div style="padding: 20px 0;text-align: center;">
		<h1>Sorry, this website requires JavaScript.</h1>
		<h2>Please enable JavaScript or <a href="http://browsehappy.com/">switch browsers</a> (we recommend <a href="http://google.com/chrome/">Google Chrome</a>).</h2>
		</div>
	</noscript>

	</div>
</div>

<? if (!$this->is_bare()) { ?>

<div class="row-fluid">
	<div class="span12">
	
	<? /* Handle Errors */
	if ($_REQUEST['action']) {
		switch ($_REQUEST['action']) {
			case 1:
				$alert_type		= 'block';
				$alert_heading	= 'Incorrect Email/Password';
				$alert_content	= '';
			break;
			case 2:
				$alert_type		= 'success';
				$alert_heading	= 'You have been logged out.';
				$alert_content	= '';
			break;
			case 3:
				$alert_type		= 'error';
				$alert_heading	= 'Error signing up!';
				$alert_content	= '';
			break;
			case 4:
				$alert_type		= 'block';
				$alert_heading	= 'Passwords do not match!';
				$alert_content	= '';
			break;
			case 5:
				$alert_type		= 'block';
				$alert_heading	= 'Required fields left blank!';
				$alert_content	= '';
			break;
			break;
			case 6:
				$alert_type		= 'success';
				$alert_heading	= 'new owcms_user(s) created!';
				$alert_content	= 'Initial password is <strong>password01</strong>';
			break;
			case 7:
				$alert_type		= 'block';
				$alert_heading	= 'Song '.base64_decode($_REQUEST['desc']).' not found!';
				$alert_content	= 'Choose "this is a new song" if intended.';
			break;
			case 'saved':
				$alert_type		= 'success';
				$alert_heading	= 'Saved '.$_REQUEST['desc'].'';
				$alert_content	= '';
			break;
			case 'admin':
				$alert_type		= 'block';
				$alert_heading	= 'You must be logged in as an administrator to view that content.';
				$alert_content	= '';
			break;
			case 'login':
				$alert_type		= 'block';
				$alert_heading	= 'Please login!';
			break;
			default:
				$alert_type		= 'block';
				$alert_heading	= 'Unknown Errror';
			break;
		}
		
		$alert = new bootstrap_alert($alert_heading, $alert_content, $alert_type);
		echo $alert->display();
	} /* end error handleing */ 
	
	/* Global Alert (hidden by css) #alert-gloabl */
	$alert = new bootstrap_alert('Alert', '[alert content]', 'warning', 'global', array('href' => 'javascript:ow_ajax_handler(\'error\', \'reset\', \'#alert-global\')'));
	echo $alert->display();
	
	?>
	
	</div>
</div>

<? } ?>

<div id="main-content">