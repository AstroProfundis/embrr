<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	switch($_GET['do']) {
		case 'image':
		if (!isset($_FILES['image'])) break;
		$image = "@{$_FILES['image']['tmp_name']};type={$_FILES['image']['type']};filename={$_FILES['image']['name']}";
		$result = imageUpload($image);
		if (isset($result->url)) {
			echo '{"result": "success" , "url" : "' . $result->url . '"}';
		} else {
			echo '{"result": "error"}';
		}
		break;
		case 'profile':
		if (!isset($_FILES['image'])) break;
		$image = file_get_contents($_FILES['image']['tmp_name']);
		$image = base64_encode($image);
		$t = getTwitter();
		$skip_status = $_POST['skip_status'];
		$result = $t->updateProfileImage($image,$skip_status);
		if ($t->http_code == 200) {
			echo '{"result": "success"}';
		} else {
			echo '{"result": "error"}';
		}
		break;
		case 'background':
		$t = getTwitter();
		if (isset($_POST['tile'])) {
			$tile = $_POST['tile'];
			$result = $t->updateProfileBackground(false, $tile);
		}
		else {
			$image = file_get_contents($_FILES['image']['tmp_name']);
			$image = base64_encode($image);
			$result = $t->updateProfileBackground($image);
		}
		if ($t->http_code == 200) {
			echo '{"result": "success", "url": "'. getAvatar($result->profile_background_image_url) .'", "tile": "'. ($result->profile_background_tile ? "true" : "false") .'"}';
		} else {
			echo '{"result": "error"}';
		}
		break;
	}
?>
