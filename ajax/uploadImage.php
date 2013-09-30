<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	if (isset($_FILES['image'])) {
		$image = "@{$_FILES['image']['tmp_name']};type={$_FILES['image']['type']};filename={$_FILES['image']['name']}";
		switch($_GET['do']) {
			case 'image':
			$result = imageUpload($image);
			if (isset($result->url)) {
				echo '{"result": "success" , "url" : "' . $result->url . '"}';
			} else {
				echo '{"result": "'. $result .'"}';
			}
			break;
			case 'profile':
			$t = getTwitter();
			$skip_status = $_POST['skip_status'];
			$result = $t->updateProfileImage($image,$skip_status);
			if ($t->http_code == 200) {
				echo '{"result": "success"}';
			} else {
				echo '{"result": "'. $t->http_code .'"}';
			}
			break;
		}
	}
?>
