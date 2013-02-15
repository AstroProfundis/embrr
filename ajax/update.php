<?php 
	include ('../lib/twitese.php');
	include_once('../lib/timeline_format.php');
	if(!isset($_SESSION)){
		session_start();
	}
	$t = getTwitter();
	if ( isset($_POST['status']) && isset($_POST['in_reply_to']) ) {
		if (trim($_POST['status']) == '') {
			echo 'empty';
			exit();
		}
		if (get_magic_quotes_gpc()) {
			$result = $t->update(stripslashes($_POST['status']), $_POST['in_reply_to']);
		} else {
			$result = $t->update($_POST['status'], $_POST['in_reply_to']);
		}
		if(isset($result->error)){
			if(strpos($result->error, 'duplicate') > 0){
				$tmp = $t->userTimeline();
				$result = $tmp[0];
				echo 'error';
			}
		}
		if(isset($result->user)){
			$user = $result->user;
			$time = $_SERVER['REQUEST_TIME']+3600*24*365;
			if ($user) {
				setcookie('friends_count', $user->friends_count, $time, '/');
				setcookie('statuses_count', $user->statuses_count, $time, '/');
				setcookie('followers_count', $user->followers_count, $time, '/');
				setcookie('imgurl', getAvatar($user->profile_image_url), $time, '/');
				setcookie('name', $user->screen_name, $time, '/');
			}
			echo format_timeline($result, $t->username, true);
		}else{
			echo 'error';
		}
	}
?>
