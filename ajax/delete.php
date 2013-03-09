<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	$t = getTwitter();
	if ( isset($_POST['status_id']) ) {
		$result = $t->deleteStatus($_POST['status_id']);
		if ($result) echo 'success';
		else echo 'error';
	}
	elseif ( isset($_POST['message_id']) ) {
		$result = $t->deleteDirectMessage($_POST['message_id']);
		if ($result) echo 'success';
		else echo 'error';
	}
	elseif ( isset($_POST['favor_id']) ) {
		$result = $t->removeFavorite($_POST['favor_id']);
		if ($result) echo 'success';
		else echo 'error';
	}
	elseif ( isset($_POST['list_slug']) ) {
		$result = $t->deleteList($_POST['list_slug']);
		if ($result) echo 'success';
		else echo 'error';
	}
	elseif ( isset($_POST['list_member']) ) {
		$result = $t->deleteListMember($_POST['slug'], $_POST['owner'], $_POST['list_member']);
		if ($result) echo 'success';
		else echo 'error';
	}
?>

