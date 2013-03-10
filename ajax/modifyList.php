<?php
        if(!isset($_SESSION)){
                session_start();
        }
        include ('../lib/twitese.php');
        $t = getTwitter();
	$name = $_POST['name'];
	$description = $_POST['description'];
	$mode = $_POST['mode'];
	if (isset($_POST['slug'])) {
		$result = $t->editList($_POST['slug'], $name, $description, $mode);
	}
	else {
		$result = $t->createList($name, $description, $mode);
	}
	if ($result) {
		$ret = '{"result": "success"';
		$ret .= ',"username":"'.$t->username.'"';
		$ret .= ',"imgurl":"'.getAvatar($result->user->profile_image_url).'"';
		$ret .= ',"contentid":"list'.$result->id_str.'"';
		$ret .= ',"listuri":"'.substr($result->uri,1).'"';
		$ret .= '}';
		echo $ret;
	}
	else echo '{"result": "error"}';
?>
