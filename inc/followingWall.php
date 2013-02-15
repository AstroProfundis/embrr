<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include('../lib/twitese.php');

	$t = getTwitter();
	$friends = $t->friends()->users;
	$html = '<div id="following_list">';
	$count = 0;
	$MAX_COUNT = 30;
	foreach($friends as $friend){
		$html .= '<span class="vcard">
			<a class="url" title="'.$friend->name.'" rel="contact" href="../user.php?id='.$friend->screen_name.'">
			<img class="photo fn" width="24" height="24" src="'.getAvatar($friend->profile_image_url).'" alt="'.$friend->name.'" />
			</a>
			</span>';
		if(++$count == $MAX_COUNT){
			break;
		}
	}
	echo $html.'</div>';
?>
