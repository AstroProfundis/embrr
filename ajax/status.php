<?php
	// ver 0.2 by @Chris_Ys
	if(!isset($_SESSION)){
		session_start();
	}
	include_once("../lib/twitese.php");

	if(isset($_GET['id']) && isset($_GET['uid'])){
		$t = getTwitter();
		$user_id = $_GET['uid'];
		$reply_to_id = "";
		$html = '<div class="ajax_form round">';
		$html .= "<a class=\"close\" title=\"Close\" onclick=\"$(this).parent().slideToggle(300);\" href=\"#\"></a>";
		$html .= '<ol>';
		$html .= formatConversation($_GET['id']);
		$html .= '</ol></div>';
		echo $html;
	}else{
		echo 'error';
	}

	function formatConversation($status_id){
		GLOBAL $t;
		GLOBAL $user_id;
		GLOBAL $reply_to_id;
		$status = $t->showStatus($status_id);
		if(!isset($status->user)){
			return "";
		}
		$user = $status->user;
		if($reply_to_id == ""){
			$reply_to_id = $user->id;
		}
		$date = format_time($status->created_at);
		$text = formatEntities(&$status->entities,$status->text);
		$end = (!isset($status->in_reply_to_user_id) || ($user_id != $status->in_reply_to_user_id && $reply_to_id != $status->in_reply_to_user_id));
		$html = '<li class="round">
			<span class="status_author">
			<a href="user.php?id='.$user->screen_name.'" target="_blank"><img src="'.getAvatar($user->profile_image_url).'" style="height: 30px; width: 30px;"></a>
			</span>
			<span class="status_body">
			<span class="status_id">'.$status_id.'</span>
			<span class="status_word" style="font-size: 12px;"><a class="user_name" href="user.php?id='.$user->screen_name.'">'.$user->screen_name.'</a> <span class="tweet">'.$text.'</span></span>
			<span class="status_info" style="font-size: 11px; margin: 0px;">';
		if($end && isset($status->in_reply_to_user_id)){
			$html .= '<span class="in_reply_to"> <a class="ajax_reply" href="ajax/status.php?id='.$status->in_reply_to_status_id_str.'&uid='.$user->id.'">in reply to '.$status->in_reply_to_screen_name.'</a></span>';
		}
		$html .= '<span class="source">via '.$status->source.'</span>
			<span class="date"><a href="status.php?id='.$status->id_str.'" id="'.$date.'" target="_blank">'.date('Y-m-d H:i:s', $date).'</a></span>
			</span>
			</span>
			</li>';
		if(!$end){
			$html .= formatConversation($status->in_reply_to_status_id_str);
		}
		return $html;
	}
?>
