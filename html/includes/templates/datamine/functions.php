<?php 

/*	Olivera Web CMS

	 -- functions.php --
	 Contains all site-specific functions

*/

function jsonDataReturn($returnCode, $returnContent) {
	
	$return = array('code' => $returnCode, 'content' => $returnContent);
	echo json_encode($return);
	
}

function jsonDataDecode($encodedContent) {
	
	$return = json_decode(stripslashes($encodedContent), true);
	return $return;
	
}

function ajaxplorer_dir_id($site) {
	
	switch($site) {
		
		case 'sdband':
			$output = '7142f9a6e9ee71abe80f80e18ebb3054';
		break;
		case 'youthchoirs':
			$output = '70100c35062a26b77512039db0a1f0f6';
		break;
		default:
			$output = '7142f9a6e9ee71abe80f80e18ebb3054';
		break;
		
	}
	
	return $output;
	
}

class owcms_functions {
	
	public function mejs($file = '', $options = '', $type = 'audio') {
		
		if ($file=='config') {
			return "<script>
				$('video,audio').mediaelementplayer({
				    // width of audio player
				    audioWidth: 260,
				    // height of audio player
				    audioHeight: 30,
				    // initial volume when the player starts
				    startVolume: 0.8,
				    // useful for <audio> player loops
				    loop: false,
				    // enables Flash and Silverlight to resize to content size
				    enableAutosize: true,
				    // the order of controls you want on the control bar (and other plugins below)
				    features: ['playpause','progress','duration','volume','fullscreen'],
				    alwaysShowControls: true,
				    pluginPath: '/resources/js/mejs/',
				    // name of flash file
				    flashName: 'flashmediaelement.swf',
				});
				</script>";
		}
		else {
		
			switch ($type) {
				
				case 'audio':
				
					return '<div class="mejs">
								<audio src="/dl.php?file='.base64_encode($file).'" type="audio/mp3" controls="controls" style="width: 100px" preload="true" '.$options.'>
									<object width="100" height="240" type="application/x-shockwave-flash" data="/resources/js/mejs/flashmediaelement.swf">
									    <param name="movie" value="/resources/js/mejs/flashmediaelement.swf" />
									    <param name="flashvars" value="controls=true&file="/dl.php?file='.base64_encode($file).'" />
									    <p>Incompatible Browser.</p>
									</object>	
								</audio>
							</div>';
				
				break;
				
				case 'video':
				
					return '<div class="mejs">
								<video width="320" height="240" controls="controls" preload="true" '.$options.'>
								    <!-- MP4 for Safari, IE9, iPhone, iPad, Android, and Windows Phone 7 -->
								    <source type="video/mp4" src="/dl.php?file='.base64_encode($file).'" />
								    <!-- Flash fallback for non-HTML5 browsers without JavaScript -->
								    <object width="320" height="240" type="application/x-shockwave-flash" data="/resources/js/mejs/flashmediaelement.swf">
								        <param name="movie" value="flashmediaelement.swf" />
								        <param name="flashvars" value="controls=true&file=/dl.php?'.base64_encode($file).'" />
								        <p>Incompatible Browser.</p>
								    </object>
								</video>
							</div>';
				
				break;
			
			}
			
		}
		
	}
	
	public function admin($site = '') {
		if ($site=='page') {
			$url = explode('/', $_SERVER['REQUEST_URI']);
			if ($url[1]=='admin'||$url[2]=='admin') {
				$true = true;
			}
		}
		else {
			$user = new owcms_user();
			if ($user->details('role')=='admin') {
				return true;
			}
			else {
				$true = false;
			}
		}
		
		if ($true) {
			return true;
		}
		else {
			if ($site=='page_head') {
				if(headers_sent()) {
					echo '<center><br /><h2>You must be logged in as an admin to view this page!</h2></center>';
				}
				else{header('Location: /?action=admin');}
				exit;
			}
			else {return false;}
		}
		
	}	
	
}

$ow = new owcms_functions;

?>