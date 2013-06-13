<?php 
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	$t = getTwitter();
	if (isset($_POST)) {
		$user = $t->updateProfile($_POST);
	} else {
		$user = $t->veverify();
	}
	if ($t->http_code == 200) {
		$time = $_SERVER['REQUEST_TIME']+3600*24*365;
		setcookie('friends_count', $user->friends_count, $time, '/');
		setcookie('statuses_count', $user->statuses_count, $time, '/');
		setcookie('followers_count', $user->followers_count, $time, '/');
		setcookie('imgurl', getAvatar($user->profile_image_url), $time, '/');
		setcookie('name', $user->screen_name, $time, '/');
		setcookie('listed_count', $user->listed_count, $time, '/');
		if($_GET['extra'] == 'bg') {
			setcookie('Bgcolor', '#'.$user->profile_background_color,$time,'/');
			setcookie('Bgimage', $user->profile_background_image_url,$time,'/');
			setcookie('Bgrepeat',$user->profile_background_tile ? "repeat" : "no-repeat",$time,'/');
		}
		echo '{"result": "success"}';
	} else {
		echo '{"result": "error"}';
	}
?>