<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	include('../lib/timeline_format.php');
	$t = getTwitter();
	if ( isset($_GET['since_id']) ) {
		$statuses = $t->homeTimeline(false, $_GET['since_id']);
		$count = count($statuses);
		$html = "";
		if ($count <= 0) {
			echo "empty";
		}
		else
		{
			foreach ($statuses as $status)
			{
				if($status->id_str < $_GET['since_id'])
				{
					break;
				}
				
				if(($status->user->screen_name == $t->username ) && (strpos($status->source, "api") !== false || strpos($status->source, "embr") !== false)){
					$count -= 1;
					continue;
				}
				elseif ( isset($status->retweeted_status) )
				{
					if ( ($t->username == $status->retweeted_status->user->screen_name) && (strpos($status->source, "api") != false || strpos($status->source, "embr") !== false) )
					{
						$count -= 1;
						continue;
					}
				}
				if(isset($status->retweeted_status)){
					$html .= format_retweet($status);
				}else{
					$html .= format_timeline($status, $t->username);
				}
			}
			$tweetCounter = "<span class=\"tweetcount\">$count</span> unread tweet(s)";
			$html .= '<div class="new">'.$tweetCounter.'</div>';
			echo $html;
		}
	} else {
		echo 'error';
	}
?>
