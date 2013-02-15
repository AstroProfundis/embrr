<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include('../lib/twitese.php');

	$t = getTwitter();
	$MAX_COUNT = 36;
	$friends = $t->friends(false, false, $MAX_COUNT)->users;
	$html = '';
	foreach($friends as $friend){
		$html .= '<span class="vcard">
			<a class="url" title="'.$friend->name.'" rel="contact" href="../user.php?id='.$friend->screen_name.'">
			<img class="photo fn" width="24" height="24" src="'.getAvatar($friend->profile_image_url).'" alt="'.$friend->name.'" />
			</a>
			</span>';
	}
	echo $html;
?>
