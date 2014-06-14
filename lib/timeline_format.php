<?php
	function format_retweet($status, $retweetByMe = false){
		$retweeter = $status->user;
		$rt_status = $status->retweeted_status;
		$status_owner = $rt_status->user;
		$date = format_time($status->created_at);
		$text = formatEntities(
			$rt_status->entities,
			isset($rt_status->extended_entities) ? $rt_status->extended_entities : null,
			$rt_status->text);
		$html = '<li>
			<span class="status_author">
			<a href="user.php?id='.$status_owner->screen_name.'" target="_blank"><img id="avatar" src="'.getAvatar($status_owner->profile_image_url).'" title="Hello, I am  '.$status_owner->screen_name.'. Click for more..." /></a>
			</span>
			<span class="status_body">
			<span title="Retweets from people you follow appear in your timeline." class="big-retweet-icon"></span>
			<span class="status_id">'.$status->id_str.'</span>
			<span class="status_word"><a class="user_name" href="user.php?id='.$status_owner->screen_name.'" id="'.$status_owner->screen_name.'">'.($_COOKIE['shownick']=='true' ? $status_owner->name : $status_owner->screen_name).'</a> 
			<span class="tweet">&nbsp;'.$text['text'].'</span>
			</span>'.
			'<span class="extended_entities">'.$text['extended'].'</span>'
			.'<span class="actions">
			<a class="replie_btn fa fa-reply" title="Reply" href="#"></a>
			<a class="rt_btn fa fa-share" title="Quote" href="#"></a>';
		if($retweetByMe != true){
			$html .= '<a class="retw_btn fa fa-retweet" title="New Retweet" href="#"></a>';
		} else {
			$html .= '<a class="unrt_btn fa fa-retweet" title="Undo Retweet" href="#"></a>';
		}
		$html .= $status->favorited ? '<a class="unfav_btn fa fa-star-o" title="UnFav" href="#"></a>' : '<a class="favor_btn fa fa-star-o" title="Fav" href="#"></a>';
		if($retweetByMe == true){
			$html .= '<span class="rt_id" style="display:none">'.$status->id_str.'</span>';
		}
		$html .='</span><span class="status_info"><span class="source">by <a href="user.php?id='.$retweeter->screen_name.'">'.$retweeter->screen_name.'</a> via '.$status->source.'</span>
			<span class="date"><a href="status.php?id='.$rt_status->id_str.'" id="'.$date.'" target="_blank">'.date('Y-m-d H:i:s', $date).'</a></span>
			</span>
			</span>';
		$html .= $status->favorited ? '<i class="faved"></i>' : '';
		$html .= '</li>';
		return $html;
	}

	function format_retweet_of_me($status){
		$status_owner = $status->user;
		$date = format_time($status->created_at);
		$text = formatEntities(
			$status->entities,
			isset($status->extended_entities) ? $status->extended_entities : null,
			$status->text);
		$html = '<li>
			<span class="status_author">
			<a href="user.php?id='.$status_owner->screen_name.'" target="_blank"><img id="avatar" src="'.getAvatar($status_owner->profile_image_url).'" title="click for more..." /></a>
			</span>
			<span class="status_body">
			<span title="Retweets from people you follow appear in your timeline." class="big-retweet-icon"></span><span class="status_id">'.$status->id_str.'</span>
			<span class="status_word">
			<a class="user_name" href="user.php?id='.$status_owner->screen_name.'" id="'.$status_owner->screen_name.'">'.($_COOKIE['shownick']=='true' ? $status_owner->name : $status_owner->screen_name).'</a><span class="tweet">&nbsp;'.$text['text'].'</span></span>'.
			'<span class="extended_entities">'.$text['extended'].'</span>'
			.'<span class="actions">
			<a class="replie_btn fa fa-reply" title="Reply" href="#"></a>
			<a class="rt_btn" title="Retweet" href="#">Retweet</a>';
		$html .= $status->favorited ? '<a class="unfav_btn fa fa-star-o" title="UnFav" href="#"></a>' : '<a class="favor_btn fa fa-star-o" title="Fav" href="#"></a>';
		$html .= '</span>
			<span class="status_info">via '.$status->source.'
			<span class="date"><a href="status.php?id='.$status->id_str.'" id="'.$date.'" target="_blank">'.date('Y-m-d H:i:s', $date).'</a></span>
			retweeted by '.$status->retweet_count.
			(($status->retweet_count>1)?' persons':' person').'
			</span>
			</span>';
		$html .= $status->favorited ? '<i class="faved"></i>' : '';
		$html .= '</li>';
		return $html;
	}

	function getRetweeters($id, $count = 20){
		$t = getTwitter();
		$retweets = $t->retweets($id, $count);
		$html = '<span class="vcard">';
		foreach($retweets as $retweet){
			$user = $retweet->user;
			$html .= '<a class="url" title="'.$user->name.'" rel="contact" href="../user.php?id='.$user->screen_name.'">
				<img class="photo fn" width="24" height="24" src="'.getAvatar($user->profile_image_url).'" alt="'.$user->name.'" />
				</a>';
		}
		$html .= "</span>";
		return $html;
	}

	function format_timeline($status, $screen_name, $updateStatus = false){
		$user = $status->user;
		$date = format_time($status->created_at);
		$text = formatEntities(
			$status->entities,
			isset($status->extended_entities) ? $status->extended_entities : null,
			$status->text);
		
		if(preg_match('/^\@'.getTwitter()->username.'/i', $text['text']) == 1){
			$output = "<li class=\"reply\">";
		}elseif($updateStatus == true){
			$output = "<li class=\"mine\">";
		}else{
			$output = "<li>";
		}
		$output .= '<span class="status_author">
		<a href="user.php?id='.$user->screen_name.'" target="_blank"><img id="avatar" src="'.getAvatar($user->profile_image_url).'" title="Hello, I am  '.$user->screen_name.'. Click for more..." /></a>
		</span>
		<span class="status_body">
		<span class="status_id">'.$status->id_str.'</span>
		<span class="status_word"><a class="user_name" href="user.php?id='.$user->screen_name.'" id="'.$user->screen_name.'">'.($_COOKIE['shownick']=='true' ? $user->name : $user->screen_name).'</a> <span class="tweet">&nbsp;'.$text['text'].'</span></span>';
		$output .= '<span class="extended_entities">'.$text['extended'].'</span>';
		$output .= "<span class=\"actions\">
			<a class=\"replie_btn fa fa-reply\" title=\"Reply\" href=\"#\"></a>
			<a class=\"rt_btn fa fa-share\" title=\"Quote\" href=\"#\"></a>
			";
		if($user->screen_name != $screen_name){
			$output .= "<a class=\"retw_btn fa fa-retweet\" title=\"New Retweet\" href=\"#\"></a>";
		}
		$output .= $status->favorited == true ? "<a class=\"unfav_btn fa fa-star-o\" title=\"UnFav\" href=\"#\"></a>" : "<a class=\"favor_btn fa fa-star-o\" title=\"Fav\" href=\"#\"></a>";
		if ($user->screen_name == $screen_name) $output .= "<a class=\"delete_btn fa fa-trash-o\" title=\"Delete\" href=\"#\"></a>";
		$output .= "</span><span class=\"status_info\">";
		if ($status->in_reply_to_status_id) $output .= "<span class=\"in_reply_to\"> <a class=\"ajax_reply\" href=\"ajax/status.php?id=$status->in_reply_to_status_id_str&uid=$user->id \">to $status->in_reply_to_screen_name</a> </span>";
		$output .= "<span class=\"source\">via $status->source</span>
			<span class=\"date\"><a href=\"status.php?id=$status->id_str\" id=\"$date\" target=\"_blank\">".date('Y-m-d H:i:s', $date)."</a></span>
			</span>
			</span>";
		$output .= $status->favorited == true ? '<i class="faved"></i>' : '';
		$output .= "</li>";
		return $output;
	}
	
	function format_message($message,$isSentPage=false) {
		if ($isSentPage) {
			$name = $message->recipient_screen_name;
			$nick = $message->recipient->name;
			$imgurl = getAvatar($message->recipient->profile_image_url);
			$messenger = $message->recipient;
		} else {
			$name = $message->sender_screen_name;
			$nick = $message->sender->name;
			$imgurl = getAvatar($message->sender->profile_image_url);
			$messenger = $message->sender;
		}
		$date = format_time($message->created_at);
		$text = formatEntities(
			$message->entities,
			isset($message->extended_entities) ? $message->extended_entities : null,
			$message->text);
		
		$output = "
			<li>
				<span class=\"status_author\">
					<a href=\"user.php?id=$name\" target=\"_blank\"><img id=\"avatar\" src=\"$imgurl\" title=\"Hello, I am $name. Click for more...\" /></a>
				</span>
				<span class=\"status_body\">
					<span class=\"status_id\">$message->id </span>
					<span class=\"status_word\"><a class=\"user_name\" href=\"user.php?id=$name\" id=\"$name\">".($_COOKIE["shownick"]=='true' ? $nick : $name)."</a> ".$text['text']." </span>".
			'<span class="extended_entities">'.$text['extended'].'</span>'
					."<span class=\"actions\">
		";
		
		if ($isSentPage) {
			$output .= "<a class=\"msg_delete_btn fa fa-trash-o\" href=\"#\" title=\"Delete\"></a>";
		} else {
			$output .= "<a class=\"msg_replie_btn fa fa-reply\" href=\"#\" title=\"Reply\"></a><a class=\"msg_delete_btn fa fa-trash-o\" href=\"#\" title=\"Delete\"></a>";
		}
		$output .="</span><span class=\"status_info\"><span class=\"date\" id=\"$date\">".date('Y-m-d H:i:s', $date)."</span></span></span></li>";
		return $output;
	}
?>
