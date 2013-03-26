<?php

if (in_array($site->current_env(), array('LIVE', 'STAGE'))) {
	$testingMin = '.min';
} else {$testingMin = '';}

if ($_SERVER['HTTP_X_REQUESTED_WITH'] != 'XMLHttpRequest') {

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
		<link rel="shortcut icon" href="/resources/images/favicon.ico"/>
		<link rel="stylesheet" href="/resources/bootstrap-2.3.1/css/bootstrap'.$testingMin.'.css" type="text/css" />
		<link href="/resources/cosmo-2.3.1/css/bootstrap.min.css" rel="stylesheet">
		<style type="text/css">
	      body {
	        padding-bottom: 15px;
	      }
	      .sidebar-nav {
	        padding: 9px 0;
	      }
	    </style>
		<link rel="stylesheet" href="/resources/bootstrap-2.3.1/css/bootstrap-responsive'.$testingMin.'.css" type="text/css" />
		<link rel="stylesheet" href="/resources/css/style.css?v=1.1" type="text/css" />
		<!-- <link type="text/css" href="/resources/js/jqueryui/css/sdband/jquery-ui-1.8.16.custom.css" rel="stylesheet" /> -->';
		
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

<!-- UP UP DOWN DOWN LEFT RIGHT LEFT RIGHT B A -->

<? /* Normal Navigation */	

if (!$this->is_bare()) {

	echo '<div class="navbar-wrapper">
      <!-- Wrap the .navbar in .container to center it within the absolutely positioned parent. -->

        <div class="navbar navbar-inverse">
          <div class="navbar-inner">
          <div class="container-fluid">
          	<a class="brand" href="/"><i style="background: url(\'../resources/images/dMineLogoWhite-20.png\') no-repeat; width: 26px; height: 20px; text-indent: -9999px; display: inline-block;">dm</i>Data<sup>MINE</sup></a>';
	
	if ($user->is_logged_in(true)) {
	
		echo '<div class="btn-group pull-right">
			<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="icon-user"></i> '.$user->details('email').'
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li><a href="/#/account">Account</a></li>
				'.($user->is_admin() ? '<li><a href="/siteadmin/#/siteadmin/">Site Admin</a></li>' : '<li>hi</li>').'
				<li class="divider"></li>
				<li><a href="/user/auth.php?logout=1">Sign Out</a></li>
			</ul>
		</div>';
		
	}
	else { ?>
		
		<form method="post" action="/user/auth.php" class="navbar-form pull-right">
			<input type="text" name="email" class="span2" placeholder="Email">
			<input type="password" name="password" class="span2" placeholder="Password">
			<button type="submit" class="btn btn-warning">Sign In</button>
		</form>
		
	<? }
	
	echo '<ul class="nav">';
		$pagesdb = mysql_query("SELECT * FROM pages WHERE enabled = '1' AND position = '0' ORDER BY `order` ASC");
		while ( $page = mysql_fetch_array( $pagesdb ) ) {
			
			if ($page['hasdropdown']=='1') {
				if ($page['slug']=='admin'&&!$user->is_admin()) {}
				else {
					echo '<li class="dropdown '.$this->active($page['id'], $page['position']).'"><a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$this->details('name', $page['id']).' <b class="caret"></b></a>
						<ul class="dropdown-menu">
						<li class="'.$this->active($page['id'], $page['position']).'"><a href="/'.$page["slug"].'">'.$this->details('name', $page['id']).'</a></li>';
							$dropid = $page['id'];
							$dropdb = mysql_query("SELECT * FROM `pages` WHERE position='$dropid' AND enabled = '1' ORDER BY `order` ASC ");
							while ($drop = mysql_fetch_array($dropdb) ) {
								
								if ($drop['hasdropdown']=='1') {
									if ($drop['slug']=='admin'&&!$user->is_admin()) {}
									else {
										echo '
											<li class="divider"></li>
											<li class="nav-header">'.$this->details('name', $page['id']).' '.$this->details('name', $drop['id']).'</li>
											<li class="'.$this->active($drop['id'], $drop['position']).'"><a href="/'.$page["slug"].'/'.$drop["slug"].'">'.$this->details('name', $drop['id']).'</a></li>';
												$drop2id = $drop['id'];
												$drop2db = mysql_query("SELECT * FROM `pages` WHERE position='$drop2id' AND enabled = '1' ORDER BY `id` ASC");
												while ($drop2 = mysql_fetch_array($drop2db) ) {
													echo '<li class="'.$this->active($drop2['id'], $page['position']).'"><a href="/'.$page["slug"].'/'.$drop["slug"].'/'.$drop2["slug"].'">'.$this->details('name', $drop2['id']).'</a></li>';
												}
									}
								}
								else {
									echo '<li class="'.$this->active($drop['id'], $page['position']).'"><a href="/'.$page["slug"].'/'.$drop["slug"].'">'.$this->details('name', $drop['id']).'</a></li>';
								}
							}
						echo '</ul>
					</li>';
				}
			}
			else {
			
				if ($page['slug']) {
					echo '<li class="'.$this->active($page['id'], $page['position']).'"><a href="#/'.stripslashes($page["slug"]).'">'.$this->details("name", $page['id']).'</a></li>';
				}
				else {
					echo '<li class="'.$this->active($page['id'], $page['position']).'"><a href="#/">'.$this->details("name", $page['id']).'</a></li>';
				}
				
			} /* else (if page hasdropdown) */
		}
	echo '
	</ul>
	</div>
	</div></div></div>'; 

}
	
?>

<div id="page-wrap" class="container-fluid">

<div class="row-fluid">
	<div class="span12">
	
	<!-- REQUIRE JAVASCRIPT -->
	<noscript>
		<div style="padding: 20px 0;text-align: center;" class="alert alert-error">
		<h1>Sorry, this website requires JavaScript.</h1>
		<h2>Please enable JavaScript or <a href="http://browsehappy.com/" style="background: #FCCFD2; padding: 2px 10px;">switch browsers</a> (we recommend <a href="http://google.com/chrome/" style="background: #FCCFD2; padding: 2px 10px;">Google Chrome</a>).</h2>
		</div>
	</noscript>

	</div>
</div>

<? if (!$this->is_bare()) { ?>

<div class="row-fluid">
	<div class="span12">
	
	<? /* Handle Alerts */
	
	if ($this->get_url_query('signup') == 'success') {
		$alert_type		= 'success';
		$alert_heading	= 'Sign up has been successful! Please sign in with your newly created account.';
	}
	
	if (isset($alert_heading) || isset($alert_content)) {
		
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