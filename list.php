<?php 
	include ('lib/twitese.php');
	$title = "@{$_GET['id']}";
	include ('inc/header.php');
	if (!loginStatus()) header('location: login.php');
?>

<script src="js/list.js"></script>

<div id="statuses">
	<?php 
		$id = isset($_GET['id'])? $_GET['id'] : false;
		$since_id = isset($_GET['since_id'])? $_GET['since_id'] : false;
		$max_id = isset($_GET['max_id'])? $_GET['max_id'] : false;
		$t = getTwitter();
		$statuses = $t->listStatus($id, $since_id, $max_id);
		$listInfo = $t->listInfo($id);
		if ($statuses === false) {
			header('location: error.php');exit();
		} 
		
		$isFollower = false;
		//$isFollower = $t->isFollowedList($id);
		$empty = count($statuses) == 0? true: false;
		if ($empty) {
			echo "<div id=\"empty\">No Tweet To Display</div>";
		} else {
	?>
	
		
	<div id="info_head">
		<a href="https://twitter.com/<?php echo $userid ?>"><img id="info_headimg" src="<?php echo getAvatar($listInfo->user->profile_image_url); ?>" /></a>
		<div id="info_name"><?php echo $id?></div>
		<div id="info_relation">
		<?php if ($isFollower) {?>
			<a id="list_block_btn" class="info_btn_hover" href="#">Unfollow</a>
		<?php } else { ?>
			<a id="list_follow_btn" class="info_btn" href="#">Follow</a>
		<?php } ?>
			<a id="list_send_btn" class="info_btn" href="#">Tweet</a>
			<a class="info_btn" href="list_followers.php?id=<?php echo $id?>">Followers (<?php echo $listInfo->subscriber_count?>)</a>
			<a class="info_btn" href="list_members.php?id=<?php echo $id?>">Members (<?php echo $listInfo->member_count?>)</a>
		</div>
	</div>
	<div class="clear"></div>
	
	<?php 
		
			$output = '<ol class="timeline" id="allTimeline">';
			include('lib/timeline_format.php');
			$firstid = false;
			$lastid = false;
			foreach ($statuses as $status) {
				if (!$firstid) $firstid = $status->id_str;
				$lastid = $status->id_str;
				if (isset($status->retweeted_status)) {
					$output .= format_retweet($status);
				} else { 
					$output .= format_timeline($status,$t->username);
				}
			}
			$lastid = bcsub($lastid, "1");
			
			$output .= "</ol><div id=\"pagination\">";
			
			$output .= "<a id=\"less\" class=\"round more\" style=\"float: left;\" href=\"list.php?id={$id}&since_id={$firstid}\">Back</a>";
			$output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"list.php?id={$id}&max_id={$lastid}\">Next</a>";
			
			$output .= "</div>";
			
			echo $output;
		}

	?>
</div>

<?php 
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
