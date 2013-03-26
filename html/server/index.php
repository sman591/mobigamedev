<?php

ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_NOTICE ^ E_USER_NOTICE);

require '../owcms/includes/header.php';

require 'lib/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array(
	'debug' => true
));

/*

	PAGE
****************/

$app->get('/page(/:slug)', function ($slug = false) {
	
	$prepend_with_page = true;
	
	if (!$slug)
		$page = new owcms_page('id:1', false, false);
	else
		$page = new owcms_page('slug:'.($prepend_with_page ? 'page/' : '').$slug, false, false);

	if (!$page->page_exists) {
		echo json_encode(array('error' => 'No page exists'));
		exit;
	}
	
	$output = array(
		'id'		=> $page->details('id'),
		'title'		=> $page->details('name'),
		'slug'		=> $page->details('slug'),
		'content'	=> $page->initialize(true)
	);
	
	echo json_encode($output);
	
});


/*

	USER
****************/

function return_user($id = null) {
	
	$user_check = new owcms_user();
	
	if (!$id && $user_check->is_logged_in(true))
		$id = $user_check->details('id');
	
	if (!$user_check->is_admin(false) && $user_check->details('id') != $id) {
		
		echo json_encode(array('error' => 'Not authorized'));
		exit;
		
	}
	
	if (!$id)
		echo json_encode(array('error' => 'No user selected'));
	else
		$user = new owcms_user('id:'.$id);

	if (!$user->user_exists()) {
		echo json_encode(array('error' => 'No user exists'));
		exit;
	}
	
	$output = array(
		'id'			=> $user->details('id'),
		'name_first'	=> $user->details('name_first'),
		'name_last'		=> $user->details('name_last'),
		'email'			=> $user->details('email'),
		'role'			=> $user->details('role'),
		'last_login'	=> $user->details('last_login'),
		'locked'		=> $user->details('locked')
	);
	
	echo json_encode($output);
	
}

function insert_user() {
	
	$user_check = new owcms_user();
	
	if (!$user_check->is_admin(false)) {
		
		echo json_encode(array('error' => 'Not authorized'));
		exit;
		
	}
	
	global $db;
	
	$insert = $db->prepare("INSERT INTO `users` SET `created`=CURRENT_TIMESTAMP");
	$insert->execute();
	$id = $db->lastInsertId();
	
	save_user($id);
	
}

function save_user($id = null) {
	
	$app = new \Slim\Slim(array(
		'debug' => true
	));
	
	$user_check = new owcms_user();
	
	if (!$id && $user_check->is_logged_in(true))
		$id = $user_check->details('id');
	
	if (!$user_check->is_admin(false) && $user_check->details('id') != $id) {
		
		echo json_encode(array('error' => 'Not authorized'));
		exit;
		
	}
	
	if (!$id)
		echo 'no';
	else
		$user = new owcms_user('id:'.$id);

	if (!$user->user_exists()) {
		echo json_encode(array('error' => 'No user exists'));
		exit;
	}
	
	global $db;
	
	$req = $app->request();
	$json = json_decode($req->getBody(), true);
	
	$allowed_params = array('name_first', 'name_last', 'email', 'role');
	
	$params = array();
	
	foreach ($allowed_params as $key) {
		
		if (isset($json[$key])) {
			
			switch ($key) {
				
				case 'role':
				
					if ($user->is_admin(false))
						$params[':'.$key] = $json[$key];						
				
				break;
				default: 
				
					$params[':'.$key] = $json[$key];
				
				break;
				
			}
			
		}
		
	}
	
	$params[':id'] = $id;
	
	$set_params = "";
	
	foreach ($params as $key => $value) {
		$set_params .= "`".ltrim($key, ":")."`=".$key.", ";
	}
	
	$set_params = rtrim($set_params, ", "); /* Remove last , from $set_params */
	
	$sql = "UPDATE `users` 
			SET ".$set_params."
			WHERE `id`=:id";
	$q = $db->prepare($sql);

	$q->execute($params);
	
	if ($db->errorCode() !== '00000') {
		echo 'Execute fail: ';
		die(print_r($q->errorInfo(), true));
		exit;
	}
	
}

function delete_user($id = null) {
	
	$user_check = new owcms_user();
	
	if (!$user_check->is_admin(false) && $user_check->details('id') != $id) {
		
		echo json_encode(array('error' => 'Not authorized'));
		exit;
		
	}
	
	if (!$id)
		echo 'no';
	else
		$user = new owcms_user('id:'.$id);

	if (!$user->user_exists()) {
		echo json_encode(array('error' => 'No user exists'));
		exit;
	}
	
	global $db;
	
	$params = array(
			':id'			=> $id
		);
	
	$insert = $db->prepare("DELETE FROM `users` WHERE `id`=:id AND `locked`='0'");
	$insert->execute($params);
	
}

$app->get('/user(/:id)', function ($id = false) {
	
	return_user($id);
	
});


$app->post('/user', function () {
	
	insert_user();
	
});


$app->put('/user/:id', function ($id = false) {
	
	save_user($id);
	
	return_user($id);
	
});


$app->delete('/user/:id', function ($id = false) {
	
	delete_user($id);
	
});



/*

	PROJECT
****************/

include 'classes/project.php';

$app->get('/project(/:id)', function ($id = false) {
	
	$project = new Project($id);
	$project->get();
	
});


$app->post('/project', function () {
	
	$project = new Project();
	$project->insert();
	
});


$app->put('/project/:id', function ($id = false) {
	
	$project = new Project($id);
	$project->save();
	
	$project->get();
	
});


$app->delete('/project/:id', function ($id = false) {
	
	$project = new Project($id);
	$project->delete();
	
});


$app->run();

?>