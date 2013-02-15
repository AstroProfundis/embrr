<?php 
	include ('lib/twitese.php');
	$title = "{$_GET['id']}";
	include ('inc/header.php');
	include ('lib/timeline_format.php');

?>

<script src="js/user.js"></script>

<div id="statuses" class="column round-left">
<?php 
	if (!loginStatus() || !isset($_GET['id'])) {
		header('location: error.php');exit();
	}

	$p = 1;
	if (isset($_GET['p'])) {
		$p = (int) $_GET['p'];
		if ($p <= 0) $p = 1;
	}

	$t = getTwitter();
	$userid = $_GET['id'];
	if (isset($_GET['fav'])) {
		$statuses = $t->getFavorites($p, $userid);
	} else {
		$statuses = $t->userTimeline($p, $userid);
	}
	
	if ($statuses === false) {
		header('location: error.php');exit;
	}
	$user = $t->showUser($userid);
	if (strcasecmp($userid,$t->username) == 0) {header('location: profile.php');exit();}

	$isProtected = ($statuses == 'protected') || ($statuses->error == 'Not authorized');
	$r = getRelationship($user->screen_name);
	$isFriend = $r == 2 || $r == 1;
	$isFollower = $r == 3 || $r == 1;
	$isBlocked = $r == 4;

	if (!$isProtected) {

		$userinfo = array();
		$userinfo['name'] = $user->name;
		$userinfo['screen_name'] = $user->screen_name;
		$userinfo['friends_count'] = $user->friends_count;
		$userinfo['statuses_count'] = $user->statuses_count;
		$userinfo['followers_count'] = $user->followers_count;
		$userinfo['url'] = $user->url;
		$userinfo['description'] = formatText($user->description);
		$userinfo['location'] = $user->location;
		$userinfo['date_joined'] = date('Y-m-d', format_time($user->created_at)); //from dabr
		$userinfo['protected'] = $user->url;
		$userinfo['id'] = $user->id;
		$userinfo['image_url'] = getAvatar($user->profile_image_url);

?>
	<div id="info_head" class="round">
		<a href="https://twitter.com/<?php echo $userid ?>"><img id="info_headimg" src="<?php echo $userinfo['image_url'] ?>" /></a>
		<div id="info_name" style="display:inline-block"><?php echo $userid ?></div>
		<?php if ($isFollower) {?>
		<span id="following_me" style="display:inline!important"><img style="" src="img/yes.gif" alt="" class="icon"/><span>Following me</span></span>
<?php 
		}
?>
		<div id="info_relation">
		<?php if ($isFriend) {?>
			<a id="info_block_btn" class="btn btn-red" href="#">Unfollow</a>
		<?php } else { ?>
			<a id="info_follow_btn" class="btn btn-green" href="#">Follow</a>
		<?php } ?>
		<?php if ($isFollower) {?>
			<a class="btn" id="info_send_btn" href="message.php?id=<?php echo $userid ?>">DM</a>
		<?php } ?>
<?php if($isBlocked){ ?>
		<a class='btn' id='unblock_btn' href='#'>Unblock</a>
<?php }else{ ?>
		<a class='btn' id='block_btn' href='#'>Block</a>
<?php } ?>
			<a class="btn" id="info_reply_btn" href="#">Reply</a>
			<a class="btn" id="info_hide_btn" href="#">Hide @</a>
			<a class="btn " id="report_btn" href="#" style="color:#a22">Report Spam</a>
		</div>
	</div>
	<div class="clear"></div>
<?php 
		$empty = count($statuses) == 0? true: false;
		if ($empty) {
			echo "<div id=\"empty\">No tweet to display.</div>";
		} else {
			$output = '<ol class="timeline" id="allTimeline">';
			foreach ($statuses as $status) {
				if (isset($status->retweeted_status)) {
					$output .= format_retweet($status);
				} else { 
				$output .= format_timeline($status,$t->username);
				}
			}
			$output .= "</ol><div id=\"pagination\">";
			if ($_GET['fav'] == true) {
				if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"user.php?id=$userid&fav=true&p=" . ($p-1) . "\">Back</a>";
				if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"user.php?id=$userid&fav=true&p=" . ($p+1) . "\">Next</a>";
			} else {
				if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"user.php?id=$userid&p=" . ($p-1) . "\">Back</a>";
				if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"user.php?id=$userid&p=" . ($p+1) . "\">Next</a>";
			}
			$output .= "</div>";
			echo $output;
		}
	}//end of if(!$isProtected)
	else {
?>
		<div id="info_head" class="round">
			<div id="info_name"><?php echo $userid ?></div>
			<div id="info_relation">
			<?php if ($isFriend) {?>
				<a id="info_block_btn" class="btn_hover" href="#">Unfollow</a>
			<?php } else { ?>
				<a id="info_follow_btn" class="btn" href="#">Follow</a>
			<?php } ?>
			<?php if ($isFollower) {?>
				<a class="btn" id="info_send_btn" href="message.php?id=<?php echo $userid ?>">Send DM</a>
			<?php } ?>
<?php if($isBlocked){ ?>
		<a class='btn_hover' id='unblock_btn' href='#'>Unblock</a>
<?php }else{ ?>
		<a class='btn' id='block_btn' href='#'>Block</a>
<?php } ?>
				<a class="btn" id="info_reply_btn" href="#">Reply</a>
				<a class="btn" id="info_hide_btn" href="#">Hide @</a>
			</div>
		</div>
		<div class="clear"></div>
		<div id="empty">This user has been protected. You ought to follow before viewing this page.</div>
<?php 
	}
?>
</div>

<?php if (!$isProtected) {?>
<td class="column round-right" id="side_base">
<table>
<tr>
<td>
<div id="side" class="round-right">
	<ul id="user_info">
		<li><span>Name</span> <?php echo $userinfo['name']?></li>
		<?php if ($userinfo['location']) echo '<li><span>Location</span> ' . $userinfo['location'] . '</li>'; ?>
		<?php if (($userinfo['url']) and (strlen($userinfo['url'])>20)) echo '<li><span>Web</span> <a href="' .$userinfo['url']. '" target="_blank">' .substr($userinfo['url'], 0, 20). '...</a></li>'; else if (($userinfo['url']) and (strlen($userinfo['url'])<=20)) echo '<li><span>Web</span> <a href="' .$userinfo['url']. '" target="_blank">' .$userinfo['url']. '</a></li>';?>
		<?php  if ($userinfo['description']) echo "<li><span>Bio</span> " . $userinfo['description'] . "</li>"; ?>
		<?php  echo "<li><span>Joined at</span> " . $userinfo['date_joined'] . "</li>"; ?>
	</ul>
	<ul id="user_stats" style="margin:0 0 10px;">
		<li>
			<a href="friends.php?id=<?php echo $userid ?>">
				<span class="count"><?php echo $userinfo['friends_count'] ?></span>
				<span class="label">Following</span>
			</a>
		</li>
		<li>
			<a href="followers.php?id=<?php echo $userid ?>">
				<span class="count"><?php echo $userinfo['followers_count'] ?></span>
				<span class="label">Followers</span>
			</a>
		</li>
		<li>
			<a href="user.php?id=<?php echo $userid ?>">
				<span class="count"><?php echo $userinfo['statuses_count'] ?></span>
				<span class="label">Tweets</span>
			</a>
		</li>
	</ul>
	<div class="clear"></div>
	<ul id="primary_nav" class="sidebar-menu">
	<li id="tweets_tab"><a class="in-page-link" href="user.php?id=<?php echo $userid ?>"><span>Tweets</span></a></li>
	<li id="@_tab"><a class="in-page-link" href="search.php?q=@<?php echo $userid ?>"><span>@<?php echo $userid ?></span></a></li>
	<li id="favs_tab"><a class="in-page-link" href="user.php?id=<?php echo $userid ?>&fav=true"><span>Favorites</span></a></li>
	<li id="lists_tab"><a class="in-page-link" href="lists.php?id=<?php echo $userid ?>"><span>Lists</span></a></li>
	</ul>
	<div class="clear"></div>
	<?php include ('inc/sidepost.php') ?>
</div>
</td>
</tr>
</table>
<?php } else { 
		include ('inc/sidebar.php');
}

	include ('inc/footer.php');
?>
