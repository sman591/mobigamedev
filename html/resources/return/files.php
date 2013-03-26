<?php require_once '../../owcms/includes/header.php';

$user->is_logged_in();

$current_site	= $_POST['site'];
$lookup_id 		= mysql_real_escape_string($_POST['id']);

$js_handler_type	= $_POST['type'];

$ow = new owcms_functions;

/* Allow for Recordings file handler */
switch(strtolower($_POST['id'])) {
	
	case 'user':
	
		$song_id	= 'user';
		$song_name	= 'User Files';
		
		$song_path	= '/user/';
	
	break;

	case 'public':
	default:
	
		$song_id	= 'public';
		$song_name	= 'Public Files';
		
		$song_path	= '/public/';
	
	break;

}


if ($_POST['fileType']=='file') {
	
	$difference = str_replace($song_path, '', $_POST['filePath']);
	
	$output .= '<p>'.$difference.'</p>
	<div class="file_options">
		<div class="file_option">
			<a href="/dl.php?file='.base64_encode($_POST['filePath']).'" class="btn btn-primary" target="_blank"><i class="icon-download-alt icon-white"></i> Download</a>
		</div>';
		
		if (in_array(strtolower($_POST['fileExt']), array('mp3'))) {
			$output .= '<div class="file_option">';
			$output .= $ow->mejs('/'.$_POST['filePath'], '', 'audio');
			$output .= $ow->mejs('config');
			$output .= '</div>';
		}
		if (in_array(strtolower($_POST['fileExt']), array('mp4', 'flv', 'wmv'))) {
			$output .= '<div class="file_option">';
			$output .= $ow->mejs('/'.$_POST['filePath'], '', 'video');
			$output .= $ow->mejs('config');
			$output .= '</div>';
		}
		elseif (in_array(strtolower($_POST['fileExt']), array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'pages', 'ai', 'psd', 'tiff', 'dxf', 'svg', 'eps', 'ps', 'ttf', 'xps', 'rar', 'jpg'))) {
			$output .= '<div class="file_option">
				<a href="/view.php?file='.base64_encode($_POST['filePath']).'" class="btn btn-info" target="_blank"><i class="icon-share icon-white"></i> View</a>
			</div>';
		}
	
	$output .= '</div>';
	
} /* if ($_POST['fileType']=='file') */
else {
	
	if ($_POST['fileType']=='dir') {
		
		$difference = str_replace($song_path, '', stripslashes($_POST['filePath']).'/');
		
		$song_path .= $difference;
		
		$path_folders = explode('/', $difference);
		
		$num_folders = count($path_folders)-1;
		
/* 		echo $num_folders; */
		
		if ($num_folders==0) { $num_folders=1; }
		
		$folder_offset .= 16+($num_folders*16);
		$folder_offset .= 'px';
		
		$tr_class = $_POST['existingClasses'].' dir_files_'.$_POST['trId'];
		
		$tr_class = str_replace('focus', '', $tr_class);
		
	}
	else {
		$folder_offset = '';
		$tr_class  = '';
	}
	
	if (!is_dir(FILES_FILEPATH.$song_path)) {
		jsonDataReturn('error', "Directory doesn't exist!");
		exit;
	}
	
	require_once(FILEPATH.'/includes/classes/file/File.php');
	$File = new Explorer();
	$File->SetPath(FILES_FILEPATH.$song_path);
	$array = $File->Listing(); 
	
	foreach ($array as $value) {
	
		$tr_id					= 'file_'.md5($song_name.'_'.$value['filename'].'_'.mt_rand(0, 100));
	
		$js_onClick				= 'songFiles_handler(\'click\', \''.$tr_id.'\', \''.$js_handler_type.'\')';
		$js_details_onclick		= 'songFiles_handler(\'details\', \''.$tr_id.'\', \''.$js_handler_type.'\')';
	
		$tr_attr['filePath'] 	= $song_path.$value['filename'];
		$tr_attr['fileType'] 	= $value['type'];
		$tr_attr['songId']		= $song_id;
		$tr_attr['fileExt'] 	= $value['extension'];
	
		if ($value['type']=='dir') {
		
			$icon_class 	= 'icon-folder-close';
			$details_icon_class = 'icon-chevron-down';
			$file_extension = '';
			$actions	= '<a onclick="'.$js_details_onclick.'" class="btn btn-info btn-small" ow_type="click"><i class="'.$details_icon_class.' icon-white"></i></a>
							<div class="btn-spacer"></div>';
			
		}
		else {
		
			$icon_class 		= 'icon-file';
			$details_icon_class = 'icon-zoom-in';
			$file_extension 	= $value['extension'];
			$actions		= '<div class="btn-spacer"></div><a href="/dl.php?file='.base64_encode($tr_attr['filePath']).'" class="btn btn-primary btn-small" target="_blank" ow_type="download"><i class="icon-download-alt icon-white"></i></a>';
			
		}
	
		$heading	= $value['filename'];
	
		$fileList  .= '<tr id="'.$tr_id.'" filePath="'.$tr_attr['filePath'].'" fileType="'.$tr_attr['fileType'].'" fileExt="'.$tr_attr['fileExt'].'" songId="'.$tr_attr['songId'].'" class="'.$tr_class.'">
		<td class="file_type span1" onclick="'.$js_onClick.'"><i class="'.$icon_class.'"></i><span class="file_extension">'.$file_extension.'</span></td>
		<td class="file_name" style="padding-left: '.$folder_offset.'" onclick="'.$js_onClick.'">'.$heading.'</td>
		<td class="file_actions span2">
			'.$actions.'
			<a onclick="javascript:songFiles_delete(\'confirm\', \''.$heading.'\', \''.base64_encode($tr_attr['filePath']).'\')" class="btn btn-danger btn-small" ow_type="delete"><i class="icon-trash icon-white"></i></a></td>
		</tr>';
		
		$has_files = true;
		
	} /* foreach */
	
	if (!$has_files) {
		
		$fileList = '<tr class="'.$tr_class.'"><td></td><td style="padding-left: '.$folder_offset.'">(empty)</td><td></td>';
		
	}
		
	if ($_POST['fileType']=='dir') {
		$output .= $fileList;
	}
	else {
		$output .= '<div class="ow_fileList">
			<div class="list_holder">
				<table class="table">
				<thead>
					<th class="file_details" colspan="2"><div class="file_type"></div><div class="file_name">&nbsp;</div><div class="file_actions"></div></th>
					<th class="file_close"><div class="close_holder"></div></th>
				</thead>
				<tbody>';
				
				$output .= $fileList;
				
				$output .= '</tbody></table>
			</div>
		</div>';
	}
	
} /* else */

jsonDataReturn('success', $output);

?>