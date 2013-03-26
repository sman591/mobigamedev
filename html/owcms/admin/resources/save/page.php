<?php 

require_once $_SERVER['DOCUMENT_ROOT'].'/owcms/includes/header.php';

$user->admin_check('page_head');

if ($_POST['id']) {
	
	global $db;
	
	if ($_POST['id']=='new') {
		
		$insert = $db->prepare("INSERT INTO `pages` SET `created`=CURRENT_TIMESTAMP");
		$insert->execute();
		$id = $db->lastInsertId();
		
	}
	elseif (is_numeric($_POST['id'])) {
		$id = $_POST['id'];
	}
	else {
		die('Invalid page ID');
	}
	
	if ($_POST['action']=='delete') {
	
		$params = array(
			':id'			=> $id
		);
		
		$sql = "DELETE FROM `pages`
				WHERE `id`=:id";
		$q = $db->prepare($sql);
		
		$q->execute($params);
	
		if ($db->errorCode() !== '00000') {
			echo 'Execute fail: ';
			die(print_r($q->errorInfo(), true));
			exit;
		}
		
		header('Location: '.ADMIN_LOCATION.'#'.ADMIN_LOCATION.'pages/');
	
	}
	else {
	
		$params = array(
			':position'		=> $_POST['position'],
			':order'		=> $_POST['order'],
			':slug'			=> $_POST['slug'],
			':name'			=> $_POST['name'],
			':subtitle'		=> $_POST['subtitle'],
			':header'		=> $_POST['header'],
			':content'		=> $_POST['content'],
			':template'		=> $_POST['template'],
			':enabled'		=> $_POST['enabled'],
			':hasdropdown'	=> $_POST['hasdropdown'],
			':id'			=> $id
		);
		
		$sql = "UPDATE `pages` 
				SET `position`=:position,
				`order`=:order,
				`slug`=:slug,
				`name`=:name,
				`subtitle`=:subtitle,
				`header`=:header,
				`content`=:content,
				`template`=:template,
				`enabled`=:enabled,
				`hasdropdown`=:hasdropdown
				WHERE `id`=:id";
		$q = $db->prepare($sql);
	
		$q->execute($params);
		
		if ($db->errorCode() !== '00000') {
			echo 'Execute fail: ';
			die(print_r($q->errorInfo(), true));
			exit;
		}
		
		header('Location: '.ADMIN_LOCATION.'#'.ADMIN_LOCATION.'pages/');
	
	}
	
}
else {
	die('No page ID');
}

?>