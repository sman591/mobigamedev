<?php require_once '../../owcms/includes/header.php';

$user->is_logged_in();

$ow = new owcms_functions;

switch ($_POST['action']) {
	
	case 'delete':
		
		$file = FILES_FILEPATH.base64_decode($_POST['filepath']);
		
		if (!file_exists($file)) {
			
			jsonDataReturn('error', "File doesn't exist");
			exit;
			
		}
		
		if (is_dir($file)) {
			
			/* Delete Directory */
			
			if (rmdir($file)) {
			
				jsonDataReturn('success', 'success');
				exit;
				
			}
			else {
				
				jsonDataReturn('error', 'Failed to delete folder');
				exit;
				
			}
			
		}
		else {
		
			/* Delete File */
			
			if (unlink($file)) {
				
				jsonDataReturn('success', 'success');
				exit;
				
			}
			else {
				
				jsonDataReturn('error', 'Failed to delete file');
				exit;
				
			}
		
		}
	
	break;
	
	case 'addDir':
		
		$file = FILES_FILEPATH.'public/'.$_POST['name'];
		
		if (file_exists($file)) {
		
			jsonDataReturn('error', 'Folder already exists');
			exit;
			
		}
		
		if (mkdir($file)) {
			
			jsonDataReturn('success', 'success');
			exit;
			
		}
		else {
			
			jsonDataReturn('error', 'Failed to add folder');
			exit;
			
		}
	
	break;
	
	default:
	
		jsonDataReturn('error', 'Uknown file action');
		exit;
	
	break;
	
}

?>