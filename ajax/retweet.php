<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include('../lib/twitese.php');
	include('timeline_format.php');
	$t = getTwitter();
	if(isset($_POST['status_id'])){
		$id = trim($_POST['status_id']);
		if($id == ''){
			return 'empty';
		}
		$result = $t->retweet($id);
		if($result){
			if($result->errors){
				echo 'duplicated';
			}else{
				echo $result->id_str;
			}
		}else{
			echo "error";
		}
	}
?>
