<!DOCTYPE html>
<html lang="en">
    <head>
		<title>MobiGameDev Demo</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- This ensures the canvas works on IE9+.  Don't remove it! -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		
		<link rel="shortcut icon" href="/resources/images/favicon.ico"/>
		<link rel="stylesheet" href="/resources/bootstrap-2.3.1/css/bootstrap.min.css" type="text/css" />
		<link href="/resources/cyborg-2.3.1/css/bootstrap.min.css" rel="stylesheet">
		<style type="text/css">
	      body {
	        padding-bottom: 15px;
	      }
	    </style>
		<link rel="stylesheet" href="/resources/bootstrap-2.3.1/css/bootstrap-responsive.min.css" type="text/css" />
		<link rel="stylesheet" href="/resources/css/style.css?v=1.0" type="text/css" />
    </head>
    <body>
    
    <div class="navbar-wrapper">

		<div class="navbar navbar-inverse">
			<div class="navbar-inner">
				<div class="container-fluid">
					<a class="brand" href="/">MobiGameDev</a>
					<ul class="nav">
						<li class="active"><a href="/">Demo Game</a></li>
						<li class="active"><a href="/star/">Star Cat Fish</a></li>
					</ul>
				</div>
			</div>
		</div>
		
    </div>
    
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
		
		<div class="row-fluid">
			<div class="span12">
			
			<div id="alert-global" class="alert fade in alert-warning"><a class="close" href="javascript:ow_ajax_handler('error', 'reset', '#alert-global')">x</a><h4 class="alert-heading">Alert</h4><div class="alert-content">
						<p>[alert content]</p>
					</div></div>	
			</div>
		</div>
		
		
		
		<div id="main-content">