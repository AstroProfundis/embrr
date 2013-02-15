<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	$t = getTwitter();
	$result;
	if ( isset($_POST['action']) && isset($_POST['id']) ) {
		switch($_POST['action']){
			case 'create':
				$result = $t->followUser($_POST['id']);
				break;
			case 'destory':
				$result = $t->destroyUser($_POST['id']);
				break;
			case 'block':
				$result = $t->blockUser($_POST['id']);
				break;
			case 'unblock':
				$result = $t->unblockUser($_POST['id']);
				break;
			case 'report':
				$result = $t->reportSpam($_POST['id']);
				break;
			case 'show':
				$result = getRelationship($_POST['id']);
				if ($result) {
					echo $result;
					return;
				}
				break;
		}
		if ($result){
			refreshProfile($t);
			echo 'success';
		}else{
			echo 'error';
		}
	}

?>

