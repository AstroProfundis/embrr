<?php 
	include ('lib/twitese.php');
	$title = "@{$_GET['id']}";
	include ('inc/header.php');
	if (!loginStatus()) header('location: login.php');
?>

<script src="js/list.js"></script>

<div id="statuses">
	<?php 
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
		
		$id = isset($_GET['id'])? $_GET['id'] : false;
		$t = getTwitter();
		$statuses = $t->listStatus($id, $p);
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
			foreach ($statuses as $status) {
				if (isset($status->retweeted_status)) {
					$output .= format_retweet($status);
				} else { 
					$output .= format_timeline($status,$t->username);
				}
			}
			
			$output .= "</ol><div id=\"pagination\">";
			
			if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"list.php?id=$id&p=" . ($p-1) . "\">Back</a>";
			if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"list.php?id=$id&p=" . ($p+1) . "\">Next</a>";
			
			$output .= "</div>";
			
			echo $output;
		}
		
		
		
	?>
</div>

<?php 
	include ('inc/sidebar.php');
?>

<?php 
	include ('inc/footer.php');
?>
