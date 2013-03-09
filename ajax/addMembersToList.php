<?php
        if(!isset($_SESSION)){
                session_start();
        }
        include ('../lib/twitese.php');
        $t = getTwitter();
	$listId = $_POST['slug'];
	$id = $_POST['owner'];
	$members = $_POST['add_members'];
	$result = $t->addListMember($listId, $id, $members);
	if ($result) {
		$ret = 'Followers: '.$result->subsriber_count.'&nbsp;&nbsp;';
		$ret .= 'Members: '.$result->member_count.'&nbsp;&nbsp;';
		$ret .= $result->mode == "private" ? "Private" : "Public";
		echo $ret;
	} else
		echo 'error';
?>
