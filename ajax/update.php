<?php 
	include ('../lib/twitese.php');
	include_once('../lib/timeline_format.php');
	if(!isset($_SESSION)){
		session_start();
	}
	$t = getTwitter();
	if ( isset($_POST['status']) && isset($_POST['in_reply_to']) ) {
		if (trim($_POST['status']) == '' &&
		    (!isset($_POST['media_ids']) || trim($_POST['media_ids']) == '')) {
			echo 'empty';
			exit();
		}

		$status = get_magic_quotes_gpc() ? stripslashes($_POST['status']) : $_POST['status'];
		if(substr($status, 0, 2) !== 'D '){
			$mediaIds = false;
			if (isset($_POST['media_ids']) && trim($_POST['media_ids']) != '') {
				$mediaIds = $_POST['media_ids'];
			}
			$result = $t->update($status, $_POST['in_reply_to'], true, $mediaIds);
		}
		else{
			$pieces = explode(" ", $status);
			$targetId = $pieces[1];
			$message = substr($status, 3 + strlen($targetId));
			$result = $t->newDirectMessage($targetId, $message);
		}

		if((isset($result->error) && strpos($result->error, 'duplicate') > 0) ||
		   isset($result->recipient)){
			$tmp = $t->userTimeline();
			$result = $tmp[0];
			if(!isset($result->recipient)){
				echo 'error';
			}
		}

		if(isset($result->user)){
			$user = $result->user;
			$time = $_SERVER['REQUEST_TIME']+3600*24*365;
			if($user){
				setcookie('friends_count', $user->friends_count, $time, '/');
				setcookie('statuses_count', $user->statuses_count, $time, '/');
				setcookie('followers_count', $user->followers_count, $time, '/');
				setcookie('imgurl', getAvatar($user->profile_image_url), $time, '/');
				setcookie('name', $user->screen_name, $time, '/');
				setcookie('listed_count', $user->listed_count, $time, '/');
			}
			echo format_timeline($result, $t->username, true);
		}else{
			echo 'error';
		}
	}
?>
