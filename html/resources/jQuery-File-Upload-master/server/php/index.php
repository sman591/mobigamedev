<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

error_reporting(E_ALL | E_STRICT);
require('UploadHandler.php');

require('../../../../owcms/includes/header.php');

class CustomUploadHandler extends UploadHandler {
    protected function get_user_id() {
        
        $user = new owcms_user();
        
        if (!$user->is_logged_in(true)) {
	        
	    	exit;
	        
        }
        else {
	        
	        return 'public';
	        
        }
        
    }
}

$upload_handler = new CustomUploadHandler(array(
    'user_dirs' => true,
    'download_via_php' => true,
    'upload_dir' => FILES_FILEPATH.'/'
));