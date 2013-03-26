<?php

class Project {

	private $id, $allowed_params;

	public function __construct($id = null) {
		
		$this->id = $id;
		
		global $db;
		
		foreach ($db->query("SELECT `COLUMN_NAME` 
					FROM INFORMATION_SCHEMA.COLUMNS
					WHERE table_name = 'projects' AND table_schema = 'datamine'") as $row => $column) {
		
			$all_params[$column['COLUMN_NAME']] = $column['COLUMN_NAME'];
					
		}
		
		/* Return Params */
		
		$return_params = $all_params;
		
		unset($return_params['id'], $return_params['modified'], $return_params['last_modified_by'], $return_params['created']);
		
		$this->allowed_return_params = $return_params;
		
		/* Edit Params */
		
		$edit_params = $all_params;
		
		unset($edit_params['id'], $edit_params['modified'], $edit_params['last_modified_by'], $edit_params['created']);
		
		$this->allowed_edit_params = $edit_params;
		
	}
	
	public function get_id() {
		return $this->id;
	}

	function get() {
		
		if (!$this->get_id())
			die(json_encode(array('error' => 'No project selected')));
		else
			$project = new dm_project('id:'.$this->get_id());
	
		if (!$project->exists()) {
			die(json_encode(array('error' => 'No project exists')));
		}
		
		foreach ($this->allowed_return_params as $param_name) {
			
			$output[$param_name] = $project->details($param_name);
			
		}
		
		echo json_encode($output);
		
	}
	
	function insert() {
		
		echo json_encode(array('error' => 'Insert function not yet configured'));
		
	}
	
	function save() {
		
		$app = new \Slim\Slim(array(
			'debug' => true
		));
		
		$user_check = new owcms_user();
		
		$id = $this->get_id();
		
		if (!$user_check->is_admin(false))
			die(json_encode(array('error' => 'Not authorized')));
	
		if (!$this->get_id())
			die(json_encode(array('error' => 'No project selected')));
		else
			$project = new dm_project('id:'.$this->get_id());
	
		if (!$project->exists()) {
			die(json_encode(array('error' => 'No project exists')));
		}
		
		global $db;
		
		$req = $app->request();
		$json = json_decode($req->getBody(), true);
		
		$params = array();
		
		foreach ($this->allowed_edit_params as $key) {
			
			if (isset($json[$key])) {
				
				switch ($key) {
					
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
		
		$sql = "UPDATE `projects` 
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
	
	function delete($id = null) {
		
		echo json_encode(array('error' => 'Delete function not yet configured'));
		exit;
		
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
	
}