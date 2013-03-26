<? require_once('../header.php');

$return = 	'<h1>Song Direcory Check</h1>
			<h3>Root filepath is '.FILEPATH.'</h3>';

$song_query = mysql_query("SELECT `id` FROM `songs` ORDER BY `id` ASC") or die(mysql_error());

while ($song_array = mysql_fetch_array($song_query)) {
	
	$return .= '<p><strong>Checking '.$song_array['id'].' ';
	
	$song = new sdband_music('id:'.$song_array['id']);
	
	$return .= '('.$song->name('human').')</strong></p>';
	
	$return .= '<p>Dir: '.$song->details('file_dir').'&nbsp;&nbsp;';
	
	if (is_dir(FILEPATH.$song->details('file_dir'))) {
	
		$return .= '<span style="color: green">Exists</span>';
		
	}
	else {
	
		$return .= '<span style="color: red">Does not exist!</span></p>
					<p>Attempting to create... ';
		
		if ($song->addDir($song->details('file_dir'))) {
		
			$return .= '<span style="color: green">created!</span>';
			
		}
		else {
			
			$return .= '<span style="color: red">failed!</span>';
			
		}
		
	}
	
	$return .= '</p>';
	
}

echo $return;

?>
---done---