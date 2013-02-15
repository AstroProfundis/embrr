<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	$t = getTwitter();
	if ( isset($_GET['since_id']) && isset($_GET['id'])) {

		$statuses = $t->listStatus($_GET['id'], false, $_GET['since_id']);

		$empty = count($statuses) == 0? true: false;

		if ($empty) {
			echo "empty";
		} else {
			$count = 0;
			foreach ($statuses as $status) {
				
				$user = $status->user;
				$date = $status->created_at;
				$text = formatEntities($status->entities,$status->text);

				if(strpos("@$t->username", $text) > -1) {
					if (++$count == count($statuses)) 
						$output = "<li style=\"background-color:#E8FECD;border-bottom:5px solid #CCC\">";
					else 
						$output = "<li style=\"background-color:#E8FECD\">";
				} 	else {
					$output = "<li>";
				}
				$output .= "<span class=\"status_author\">
					<a href=\"user.php?id=$user->screen_name\" target=\"_blank\"><img src=\"".getAvatar($user->profile_image_url)."\" title=\"$user->screen_name\" /></a>
					</span>
					<span class=\"status_body\">
					<span class=\"status_id\">$status->id_str </span>
					<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$user->screen_name\">$user->screen_name</a><span class=\"tweet\"> $text </span></span>
					<span class=\"actions\">
					<a class=\"replie_btn\" title=\"Reply\" href=\"a_reply.php?id=$status->id_str\">Reply</a>
					<a class=\"rt_btn\" title=\"Retweet\" href=\"a_rt.php?id=$status->id_str\">RT</a>
					<a class=\"favor_btn\" title=\"Favorite\" href=\"a_favor.php?id=$status->id_str\">Favor</a>";
				if ($user->screen_name == $t->username) $output .= "<a class=\"delete_btn\" title=\"Delete\" href=\"a_del.php?id=$status->id_str&t=s\">删除</a>";
				$output .= "</span><span class=\"status_info\">";
				if ($status->in_reply_to_status_id_str) $output .= "<span class=\"in_reply_to\"> <a class=\"ajax_reply\" href=\"ajax/status.php?id=$status->in_reply_to_status_id_str&uid=$user->id \">in reply to $status->in_reply_to_screen_name</a></span>";
				$output .= "<span class=\"source\">via $status->source</span>
					<span class=\"date\"><a href=\"status.php?id=$status->id_str\" target=\"_blank\">$date</a></span>
					</span>
					</span>
					</li>";
				$html .= '<div class="new"></div>';
				echo $output;
				*/
			}
		}

	} else {
		echo 'error';
	}

?>
