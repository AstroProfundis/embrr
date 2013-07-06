<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	if (isset($_FILES['image'])) {
		switch($_GET['do']) {
			case 'image':
			$image = "@{$_FILES['image']['tmp_name']};type={$_FILES['image']['type']};filename={$_FILES['image']['name']}";
			$result = imageUpload($image);
			if (isset($result->url)) {
				echo '{"result": "success" , "url" : "' . $result->url . '"}';
			} else {
				echo '{"result": "error"}';
			}
			break;
			case 'profile':
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
			$image = file_get_contents($_FILES['image']['tmp_name']);
			$image = base64_encode($image);
			$t = getTwitter();
			$skip_status = $_POST['skip_status'];
			$result = $t->updateProfileBackground($image,$skip_status);
			if ($t->http_code == 200) {
				echo '{"result": "success", "url": "'. getAvatar($result->profile_background_image_url) .'"}';
			} else {
				echo '{"result": "error"}';
			}
			break;
		}
	}
?>
