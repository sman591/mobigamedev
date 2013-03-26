<?php

/* sendMail() function by Stuart Olivera

$mail_type		:	Type of email your sending out
$mail_users		:	Can be SITE_weekly, SITE_everyone, or a custom user shortname (see "LOAD USERS" switch array)
$mail_id		:	Sets id for schedule, date, music, etc. (customized to fit each $mail_type)
$mail_comments	:	Comments to be put in email, most of the time below the headline (h2).
$receipt_opt	:	Whether or not to [yes] echo the receipt or [no] enter the receipt into the mail logs database.
$mail_extras	:	Extras; Used by specific $mail_type's (ex. music table for song changes)

*/

function sendMail($mail_type, $mail_users, $mail_id, $mail_comments, $receipt_opt, $mail_extras) {
$site_class = new owcms_site();
$MASTER = file_get_contents($site_class->filepath().'includes/mail/template1.html');

preg_match('/^[0-9]+$/', $mail_id, $mail_id_val);
$mail_id = $mail_id_val[0];


/* 
	Mail-specific Options 
**********************/
$john_user_id = '3';
$ceci_user_id = '7';
$stuart_user_id = '1';
$mark_user_id = '63';
$receipt = '';


/* 
	Site-Specific Changes 
**********************/
if (strpos($mail_type, 'sdband') !== false) {

	$headerSite = "St. David's 9am Band";
	$baseUrl = "http://sdband.oliveraweb.com/";
	$navigation = '<a href="http://sdband.oliveraweb.com/" style="color: #444; padding: 0 2px;">Home</a>
		<a href="http://sdband.oliveraweb.com/music/" style="color: #444; padding: 0 2px;">Music</a>
		<a href="http://sdband.oliveraweb.com/calendar/" style="color: #444; padding: 0 2px;">Calendar</a>
		<a href="http://sdband.oliveraweb.com/recordings/" style="color: #444; padding: 0 2px;">Recordings</a>';
	$mail_from = 'John Ware <jware@jwguitar.com>';
	$mail_subject_base = '9am Music - ';
	
}
elseif (strpos($mail_type, 'youthchoirs') !== false) {
	
	$headerSite = "St. David's Youth Choir";
	$baseUrl = "http://sdband.oliveraweb.com/youthchoirs/";
	$navigation = '<a href="http://sdband.oliveraweb.com/youthchoirs/" style="color: #444; padding: 0 2px;">Home</a>
		<a href="http://sdband.oliveraweb.com/youthchoirs/music/" style="color: #444; padding: 0 2px;">Music</a>
		<a href="http://sdband.oliveraweb.com/youthchoirs/schedule/" style="color: #444; padding: 0 2px;">Schedule</a>
		<a href="http://sdband.oliveraweb.com/youthchoirs/weekly/" style="color: #444; padding: 0 2px;">Weekly Recordings</a>';
	$mail_from = 'CeCi Stephens <iggitha@aol.com>';
	$mail_subject_base = 'Youth Choirs - ';

}
$MASTER = str_replace('<$headerSite>', $headerSite, $MASTER);
$MASTER = str_replace('<$baseUrl>', $baseUrl, $MASTER);
$MASTER = str_replace('<$navigation>', $navigation, $MASTER);


/* 
	If Schedule 
**********************/
if (strpos($mail_type, 'schedule') !== false) {
	
	$thisS = mysql_fetch_array(mysql_query("SELECT * FROM schedule WHERE id='$mail_id' LIMIT 1"));
	$thisServiceId = $thisS['id'];
	
	if (strpos($mail_type, 'sdband') !== false) {$comments = stripslashes($thisS['comments']);}
	elseif (strpos($mail_type, 'youthchoirs') !== false) {$comments = stripslashes($thisS['ycComments']);}
	$comments .= '<p>Please check vocal assignments on website.</p>';
	$MASTER = str_replace('<$content>', $comments, $MASTER);
	
	$emailTitle = date("l, F jS @ ga", $thisS['date']);
	if (strpos($mail_type, 'confirm') !== false) { 
		$emailTitle = '<p style="color: red;">Edited by: '.$mail_extras['edited_by'].'</p>'.$emailTitle; 
		$mail_subject = 'Edited schedule for '.date("F j, Y @ ga", $thisS['date']);
	}
	elseif (strpos($mail_type, 'update') !== false) { 
		$mail_subject = 'Updated schedule for '.date("F j, Y @ ga", $thisS['date']);
	}
	elseif (strpos($mail_type, 'weekly') !== false) { 
		$mail_subject = 'Schedule this week';
	}
	else {$mail_subject = 'Schedule';}
	$MASTER = str_replace('<$emailTitle>', $emailTitle, $MASTER);
	
	if (strpos($mail_type, 'confirm') === false) {
		
		$MASTER = str_replace('<$headerTitle>', "Schedule Update", $MASTER);
		
		$attendTemplate = '<tr>
			<td valign="top" align="center" style="font-weight: 100; font-size: 18px; font-family: Arial, Helevtica, Verdana, san-serif; color: #202020; text-align: center; margin: 0; padding: 0 0 10px; line-height: 50px;"><$attendButtons>
			<a href="http://sdband.oliveraweb.com/" class="grey" style="-webkit-border-radius: 8px; border: 1px solid #d0d0d0; padding: 10px 25px; background-color: #DDDDDD; background: url(http://sdband.oliveraweb.com/resources/images/button-grey.png) repeat-x; text-decoration: none; color: #222; text-align: center; -moz-border-radius: 8px; margin: 7px 5px;">View Service</a>
		
			</td>
		</tr>';
		
		$MASTER = str_replace('<$attend>', $attendTemplate, $MASTER);
		
	} /* if (strpos($mail_type, 'confirm') !== true) */
	else {
		$MASTER = str_replace('<$headerTitle>', "Schedule Edit Confirmation", $MASTER);
		$MASTER = str_replace('<$attend>', '', $MASTER);
	}
	
	if (strpos($mail_type, 'sdband') !== false) {
		$songarray = array($thisS['song1desc']=>$thisS['song1'], $thisS['song2desc']=>$thisS['song2'], $thisS['song3desc']=>$thisS['song3'],$thisS['song4desc']=>$thisS['song4'],$thisS['song5desc']=>$thisS['song5'],$thisS['song6desc']=>$thisS['song6'],$thisS['song7desc']=>$thisS['song7'],$thisS['song8desc']=>$thisS['song8']);
	}
	elseif (strpos($mail_type, 'youthchoirs') !== false) {
		$songarray = array($thisS['ycdesc']=>$thisS['song5']);
	}
	foreach( $songarray as $key => $value){
		if ($key=='Kid\'s Choir'||strpos($mail_type, 'youthchoirs') !== false) {
			$songquery = mysql_query("SELECT * FROM ycsongs WHERE id='$value' LIMIT 1");
		}
		else {
			$songquery = mysql_query("SELECT * FROM songs WHERE id='$value' LIMIT 1");
		}
		$song = mysql_fetch_array($songquery);
		$i++;
		$songstext .= '
		<tr>
			<td class="song" style="text-align: left; width: auto; padding-right: 8px; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">'.stripslashes($song['name']).'</td>
			<td class="desc" style="text-align: right; width: auto; padding-left: 8px; font-family: JazzText; font-size: 16px;">'.stripslashes($key).'</td>
		</tr>';
	}
	$MASTER = str_replace('<$songs>', $songstext, $MASTER);
	
	if (strpos($mail_type, 'sdband') !== false) {
		$aquery = mysql_query("SELECT * FROM attend WHERE serviceid = '$thisServiceId'");
		$attenda = mysql_fetch_array( $aquery );
		for ($alertuid=1; $alertuid<=100; $alertuid++) {
			if ($attenda['userid'.$alertuid]=='no') {
				$alertsuser = mysql_fetch_array(mysql_query("SELECT name_first, site FROM users WHERE id = '$alertuid' LIMIT 1"));
				if ($alertsuser['site']=='both'||$alertsuser['site']=='sdband') {
					$a++;
					if ($a != '1') {
					$alerts .= ', ';
					}
					elseif ($a == '1') {
					$alerts = 'No ';
					}
					$alerts .= $alertsuser['name_first'];
				}
			}
		}
		$MASTER = str_replace('<$alerts>', $alerts, $MASTER);
	} /* if (strpos($mail_type, 'sdband') !== false) */
	elseif (strpos($mail_type, 'youthchoirs') !== false) {
	
		if ($thisS['ycband']=='yes') {$MASTER = str_replace('<$alerts>', 'With Band', $MASTER);}
		else {$MASTER = str_replace('<$alerts>', '', $MASTER);}
	
	} /* elseif (strpos($mail_type, 'youthchoirs') !== false) */
	else {
		$MASTER = str_replace('<$alerts>', '', $MASTER);
	}

} /* (strpos($mail_type, 'schedule') !== false) */



/* 
	If Attendance Change 
**********************/
elseif (strpos($mail_type, 'attend') !== false) {
	
	$a_id = mysql_real_escape_string($mail_extras);
	$a_u = mysql_fetch_array(mysql_query("SELECT * FROM `users` WHERE `id`='$a_id' LIMIT 1"));
	
	$thisS = mysql_fetch_array(mysql_query("SELECT * FROM schedule WHERE id='$mail_id' LIMIT 1"));
	$thisServiceId = $thisS['id'];
	
	$mail_subject = $a_u['name_first'].' '.$a_u['name_last'].' is <$attendStatus> '.date("F jS @ ga", $thisS['date']);
	
	$MASTER = str_replace('<$headerTitle>', "Attendance Change", $MASTER);
	
	$emailTitle = $a_u['name_first'].' '.$a_u['name_last'].' is';
	$MASTER = str_replace('<$emailTitle>', $emailTitle, $MASTER);
	
	$content = '<div style="text-align: center;">
	<br />
	<$attendSingle>
	<br />
	<h2 style="color:#333333; font-weight: bold; margin: 0; padding: 0px; line-height: 26px; font-size: 18px; font-family: Helvetica, Arial, sans-serif; ">'.date("l, F jS @ ga", $thisS['date']).'</h2>
	</div><br />'.$mail_comments;
	
	$MASTER = str_replace('<$content>', $content, $MASTER);
	
	/* Not Used Values Set As Blank */
	$MASTER = str_replace('<$attend>', '', $MASTER);
	$MASTER = str_replace('<$songs>', '', $MASTER);
	$MASTER = str_replace('<$alerts>', '', $MASTER);

} /* (strpos($mail_type, 'attend') !== false) */



/* 
	If Music 
**********************/
if (strpos($mail_type, 'music') !== false) {
	
	$MASTER = str_replace('<$headerTitle>', 'Music Updates', $MASTER);
	$MASTER = str_replace('<$content>', $mail_comments, $MASTER);
	
	if (strpos($mail_type, 'sdband') !== false) {
		$songquery = mysql_query("SELECT * FROM songs WHERE id='$mail_id' LIMIT 1");
	}
	elseif (strpos($mail_type, 'youthchoirs') !== false) {
		$songquery = mysql_query("SELECT * FROM ycsongs WHERE id='$mail_id' LIMIT 1");
	}
	$song = mysql_fetch_array( $songquery );
	
	$mail_subject = stripslashes($song['name']).' Updates';
	
	$MASTER = str_replace('<$emailTitle>', stripslashes($song['name']).' Updates', $MASTER);
	
	/* ****** TEST ONLY- REMOVE ******
	$mail_extras = array('songstext'=>'<tr>
		<td class="song" style="text-align: left; width: auto; padding-right: 8px; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">All PDF</td>
		<td class="desc" style="text-align: right; width: auto; padding-left: 8px; font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #999999;"><a href="http://sdband.oliveraweb.com/view?file=">view</a>&nbsp;&nbsp;<a href="http://sdband.oliveraweb.com/downloadpdf?file=">download pdf</a></td>
		</tr>'); */
		
	$songstext = $mail_extras['songstext'];
	$MASTER = str_replace('<$songs>', $songstext, $MASTER);
	
	if (strpos($mail_type, 'sdband') !== false) {
		$aquery = mysql_query("SELECT * FROM attend WHERE serviceid = '$thisServiceId'");
		$attenda = mysql_fetch_array( $aquery );
		for ($alertuid=1; $alertuid<=20; $alertuid++) {
			if ($attenda['userid'.$alertuid]=='no') {
				$alertsuser = mysql_fetch_array(mysql_query("SELECT name_first, site FROM users WHERE id = '$alertuid' LIMIT 1"));
				if ($alertsuser['site']=='both'||$alertsuser['site']==$site) {
					$a++;
					if ($a != '1') {
					$alerts .= ', ';
					}
					elseif ($a == '1') {
					$alerts = 'No ';
					}
					$alerts .= $alertsuser['name_first'];
				}
			}
		}
		$MASTER = str_replace('<$alerts>', $alerts, $MASTER);
	} /* if (strpos($mail_type, 'sdband') !== false) */
	else {
		$MASTER = str_replace('<$alerts>', '', $MASTER);
	}
	
	/* Not Used Values Set As Blank */
	$MASTER = str_replace('<$attend>', '', $MASTER);
	$MASTER = str_replace('<$alerts>', '', $MASTER);

} /* (strpos($mail_type, 'music') !== false) */

/* Sets & restores timezone for timestamp in email */
$est_orig = date_default_timezone_get();
date_default_timezone_set('EDT');
$MASTER = str_replace('<$asof>', '<p style="text-align: center;padding-bottom: 0;">Up-to-date as of '.date('l, M jS Y g:i:s A T', time()), $MASTER);
date_default_timezone_set($est_orig);

/* LOAD USERS, MASTER TEMPLATE */
switch ($mail_users) {
	case 'john':
		$userdb = mysql_query("SELECT * FROM users WHERE id='$john_user_id' LIMIT 1") or die(mysql_error());
	break;
	case 'ceci':
		$userdb = mysql_query("SELECT * FROM users WHERE id='$ceci_user_id' LIMIT 1") or die(mysql_error());
	break;
	case 'mark':
		$userdb = mysql_query("SELECT * FROM users WHERE id='$mark_user_id' LIMIT 1") or die(mysql_error());
	break;
	case 'stuart':
		$userdb = mysql_query("SELECT * FROM users WHERE id='$stuart_user_id' LIMIT 1") or die(mysql_error());
	break;
	case 'admin':
		$userdb = mysql_query("SELECT * FROM users WHERE role='admin'") or die(mysql_error());
	break;
	case 'sdband_weekly':
		$userdb = mysql_query("SELECT * FROM users WHERE send='yes' AND site='sdband' OR send='yes' AND site='both'") or die(mysql_error());
	break;
	case 'youthchoirs_weekly':
		$userdb = mysql_query("SELECT * FROM users WHERE send='yes' AND site='youthchoirs' OR send='yes' AND site='both'") or die(mysql_error());
	break;
	case 'sdband_everyone':
		$userdb = mysql_query("SELECT * FROM users WHERE site='sdband' OR site='both'") or die(mysql_error());
	break;
	case 'youthchoirs_everyone':
		$userdb = mysql_query("SELECT * FROM users WHERE site='youthchoirs' OR site='both'") or die(mysql_error());
	break;
	default:
		/* allow the use of userid:ID */
		if (strpos($mail_users, 'userid') !== false) {
			$useridchunks = explode(":", $mail_users);
			$useridchunk2 = mysql_real_escape_string($useridchunks[1]);
			$userdb = mysql_query("SELECT * FROM users WHERE `id`='$useridchunk2' LIMIT 1") or die(mysql_error());
		}
		/* allow for use of multiple user id's in the form of an array */
		elseif (is_array($mail_users)) {
		
			foreach ($mail_users as $key => $value) {
				$where_select_i++;
				if ($where_select_i!=1) {
					$where_select .= ' OR ';
				}
				$where_select .= "`id`='".mysql_real_escape_string($value)."'";
			}
			
			if (trim($where_select)!='') {
				$userdb = mysql_query("SELECT * FROM `users` WHERE $where_select") or die(mysql_error());
			}
			else {
				die('Error: Invalid/empty user array!');
			}
			
		}
		else {
			echo 'Error: User type not defined or not valid! Script exited.';
			exit;
		}
	break;
}

while ($user = mysql_fetch_array( $userdb )) {

	/* $MASTER MUST NOT BE CHANGED DURING THE "WHILE"*/
	
	/* Reset $message for each user's properties */
	$message = $MASTER;
	/*	*	*	*	*	*	*	*	*	*	*	 */
	
	
	if (strpos($mail_type, 'schedule') !== false) {
	
		$attendusr = mysql_fetch_array( mysql_query("SELECT * FROM attend WHERE serviceid = '$thisServiceId' LIMIT 1") );
		switch ($attendusr['userid'.$user['id']]) {
			case 'yes':
				$attend = '<p style="font-size: 18px; font-weight: bold; padding-top: 8px; padding-bottom: 1px;">You Are:</p>
				<a href="#" class="green" style="-webkit-border-radius: 7px; cursor: default; border: 1px solid #C0DAC0;padding: 10px 25px; background-color: #CCf5CC; background: url(http://sdband.oliveraweb.com/resources/images/button-green.png) repeat-x #CCf5CC; text-decoration: none; color: #222; text-align: center; -moz-border-radius: 8px; margin: 7px 5px 7px 7px;">Attending</a><br />
				<a href="http://sdband.oliveraweb.com/user/attend.php?form=1&name=<$uid>&id='.$thisS['id'].'&resp=no&email=yes" class="red" style="-webkit-border-radius: 7px; border: 1px solid #DAC0C0; padding: 10px 25px; background-color: #f5CCCC; background: url(http://sdband.oliveraweb.com/resources/images/button-red.png) repeat-x #f5CCCC; text-decoration: none; color: #222; text-align: center; -moz-border-radius: 7px; margin: 7px 5px;">I\'m Not Attending</a>';
			break;
			
			case 'no':
				$attend = '<p style="font-size: 18px; font-weight: bold; padding-top: 8px; padding-bottom: 1px;">You Are:</p>
				<a href="#" class="red" style="-webkit-border-radius: 7px; cursor: default; border: 1px solid #DAC0C0; padding: 10px 25px; background-color: #f5CCCC; background: url(http://sdband.oliveraweb.com/resources/images/button-red.png) repeat-x #f5CCCC; text-decoration: none; color: #222; text-align: center; -moz-border-radius: 7px; margin: 7px 5px;">Not Attending</a><br />
				<a href="http://sdband.oliveraweb.com/user/attend.php?form=1&name=<$uid>&id='.$thisS['id'].'&resp=yes&email=yes" class="green" style="-webkit-border-radius: 7px; border: 1px solid #C0DAC0;padding: 10px 25px; background-color: #CCf5CC; background: url(http://sdband.oliveraweb.com/resources/images/button-green.png) repeat-x #CCf5CC; text-decoration: none; color: #222; text-align: center; -moz-border-radius: 8px; margin: 7px 5px 7px 7px;">I\'m Attending</a>';
			break;
	
			default:
				$attend = '<a href="http://sdband.oliveraweb.com/user/attend.php?form=1&name=<$uid>&id='.$thisS['id'].'&resp=yes&email=yes" class="green" style="-webkit-border-radius: 7px; border: 1px solid #C0DAC0;padding: 10px 25px; background-color: #CCf5CC; background: url(http://sdband.oliveraweb.com/resources/images/button-green.png) repeat-x #CCf5CC; text-decoration: none; color: #222; text-align: center; -moz-border-radius: 8px; margin: 7px 5px 7px 7px;">Attending</a>
		<a href="http://sdband.oliveraweb.com/user/attend.php?form=1&name=<$uid>&id='.$thisS['id'].'&resp=no&email=yes" class="red" style="-webkit-border-radius: 7px; border: 1px solid #DAC0C0; padding: 10px 25px; background-color: #f5CCCC; background: url(http://sdband.oliveraweb.com/resources/images/button-red.png) repeat-x #f5CCCC; text-decoration: none; color: #222; text-align: center; -moz-border-radius: 7px; margin: 7px 5px;">Not Attending</a><br />';
			break;
		} /* switch ($attendusr['userid'.$alertuid]) */
		$message = str_replace('<$attendButtons>', $attend, $message);
		
	} /* if (strpos($mail_type, 'schedule') !== false) */
	
	
	if (strpos($mail_type, 'attend') !== false) {
		
		$attendusr = mysql_fetch_array( mysql_query("SELECT * FROM attend WHERE serviceid = '$thisServiceId'") );
		switch ($attendusr['userid'.$a_u['id']]) {
			case 'yes':
				$attend = '<a href="#" class="green" style="-webkit-border-radius: 7px; cursor: default; border: 1px solid #C0DAC0;padding: 10px 25px; background-color: #CCf5CC; background: url(http://sdband.oliveraweb.com/resources/images/button-green.png) repeat-x #CCf5CC; text-decoration: none; color: #222; text-align: center; -moz-border-radius: 8px; margin: 7px 5px 7px 7px;">Attending</a><br /><br />';
				$attendStatus = 'Attending';
			break;
			
			case 'no':
				$attend = '<a href="#" class="red" style="-webkit-border-radius: 7px; cursor: default; border: 1px solid #DAC0C0; padding: 10px 25px; background-color: #f5CCCC; background: url(http://sdband.oliveraweb.com/resources/images/button-red.png) repeat-x #f5CCCC; text-decoration: none; color: #222; text-align: center; -moz-border-radius: 7px; margin: 7px 5px;">Not Attending</a><br /><br />';
				$attendStatus = 'Not Attending';
			break;
	
			default:
				$attend = '<p style="color: red;">No Response Available!<br />
					Service ID: '.$thisS['id'].'</p>';
				$attendStatus = '<unknown>';
			break;
		} /* switch ($attendusr['userid'.$alertuid]) */
		$mail_subject = str_replace('<$attendStatus>', $attendStatus, $mail_subject);
		$message = str_replace('<$attendSingle>', $attend, $message);
		
	} /* if (strpos($mail_type, 'schedule') !== false) */
	
	
	$message = str_replace('<$name>', $user['name_first'].' '.$user['name_last'], $message);
	$message = str_replace('<$email>', $user['email'], $message);
	$message = str_replace('<$uid>', $user['id'], $message);
	
/*
	echo ( $message );
	exit;
*/
	
	
	// Set headers
	$headers = 'From: '.$mail_from . "\r\n" .
    'Reply-To: '.$mail_from . "\r\n" .
    'Content-type: text/html; charset=utf-8' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
	$receipt .= '<p>'.$user['email'].' - ';
	
	if ($site->current_env() == 'LIVE') {
		$receipt .= '**Testing Mode Enabled** (no email sent)';
	}
	else {

		// Attempt to send, Return a success/failiure notice to receipt
		if ( mail($user['email'], $mail_subject_base.$mail_subject, $message, $headers) ) {
		
			$receipt .= 'Sent</p>';
		}
		else {
			$receipt .= '***FAILED***</p>';
		}
	
	}
	
/* 	echo 'Attempted to send to: "'.$user['email'].'" with subject "'.$mail_subject_base.$mail_subject.'"<br />'; */

}
	$receipt .= '<br /><p>-------------START MESSAGE-------------</p>';
	$receipt .= $message;
	$receipt .= '<p>-------------END MESSAGE-------------</p>';

if ($receipt_opt=='yes') {
	return $receipt;
}
else {
	$date = date('Y-m-d H:i:s');
	$description = mysql_real_escape_string($mail_type);
	$users = mysql_real_escape_string($mail_users);
	$content = mysql_real_escape_string($receipt);
	mysql_query("INSERT INTO `email_log` (`date`, `description`, `users`, `content`) VALUES ('$date', '$description', '$users', '$content')") or die(mysql_error());
}

} /* ----------- function sendMail(); ----------- */
?>