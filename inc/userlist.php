<div id="statuses" class="column round-left">
<script src="js/userlist.js"></script>
<?php
	include_once('lib/timeline_format.php');
	
	if(!isset($_SESSION)){
		session_start();
	}
	$test_var = false;

	$t = getTwitter();
	$p = -1;
	if (isset($_GET['p'])) {
		$p = $_GET['p'] = '' ? -1 : $_GET['p'];
	}
	$c = -1;
	if (isset($_GET['c'])) {
		$c = $_GET['c'];
	}

	$id = isset($_GET['id']) ? $_GET['id'] : null;
	$userid = $id;
	{
		switch ($type) {
			case 'blocks':
				echo $userid ? "You can't view others' blocking!" : "
					<h2 style='margin: 10px 0pt 20px 15px'>
					<span>People I'm blocking</span>
					</h2>
					<div id='subnav'>
					<span class='subnavLink'><a href='friends.php'>People I'm following</a></span>
					<span class='subnavLink'><a href='followers.php'>People who follow me</a></span>		
					<span class='subnavNormal'>People I'm blocking</span>
					</div>";
				break;
			case 'friends':
				echo $userid ? "
					<h2 style='margin: 10px 0pt 20px 15px'>
					<span>People <a href='user.php?id=$userid'>" . $userid . "</a> is following</span>
					</h2>
					<div id='subnav'>
					<span class='subnavNormal'>People <b>" . $userid . "</b> is following</span>
					<span class='subnavLink'><a href='followers.php?id=$userid'>People who follow <b>" . $userid . "</b></a></span>
					</div>" : "
					<h2 style='margin: 10px 0pt 20px 15px'>
					<span>People I'm following</span>
					</h2>
					<div id='subnav'>
					<span class='subnavNormal'>People I'm following</span>
					<span class='subnavLink'><a href='followers.php'>People who follow me</a></span>
					<span class='subnavLink'><a href='block.php'>People I'm blocking</a></span>
					</div>";
				break;
			case 'followers':
				echo $userid ? "
					<h2 style='margin: 10px 0pt 20px 15px'>
					<span>People who follow <a href='user.php?id=$userid'>" . $userid . "</a></span>
					</h2>
					<div id='subnav'>
					<span class='subnavLink'><a href='friends.php?id=$userid'>People <b>" . $userid . "</b> is following</a></span>
					<span class='subnavNormal'>People who follow <b>" . $userid . "</b></span>
					</div>" : "
					<h2 style='margin: 10px 0pt 20px 15px'>
					<span>People who follow me</span>
					</h2>
					<div id='subnav'><span class='subnavLink'><a href='friends.php'>People I'm following</a></span>
					<span class='subnavNormal'>People who follow me</span>
					<span class='subnavLink'><a href='block.php'>People I'm blocking</a></span>
					</div>";
				break;
			case 'list_members':
				echo "
					<h2 style='margin: 10px 0pt 20px 15px'>
					<span>Members of list <span class=\"list_id\">$id</span></span>
					</h2>
					<div id='subnav'><span class='subnavNormal'>Members of list <b>$id</b></span>
					<span class='subnavLink'><a href='list.php?id=$id'>Go back to the list</a></span>
					</div>";
				break;
			case 'list_followers':
				echo "
					<h2 style='margin: 10px 0pt 20px 15px'>
					<span>Followers of list $id</span>
					</h2>
					<div id='subnav'><span class='subnavNormal'>Followers of list <b>$id</b></span>
					<span class='subnavLink'><a href='list.php?id=$id'>Go back to the list</a></span>
					</div>";
				break;
			case 'browse':
				echo "<div id='subnav'><span class='subnavNormal'>See what people are saying about…</span></div>";
				break;
		}
	}

	echo '<div class="clear"></div>';
	switch ($type) {
		case 'blocks':
			$p = $p < 1 ? 1 : $p;
			$t->type = 'xml';
			$userlist = $t->blockingList($p)->user;
			$nextlist = count($userlist) == 20 ? $p + 1 : 0;
			$prelist = $p <= 1 ? 0 : $p - 1;
			break;
		case 'friends':
			$t->type = 'xml';
			$userlist = $t->friends($id, $p);
			$next_page = $userlist->next_cursor;
			$previous_page = $userlist->previous_cursor;
			$userlist = $userlist->users->user;
			break;
		case 'followers':
			$t->type = 'xml';
			$userlist = $t->followers($id, $p);
			$next_page = $userlist->next_cursor;
			$previous_page = $userlist->previous_cursor;
			$userlist = $userlist->users->user;
			break;
		case 'list_members':
			$t->type = 'xml';
			$userlist = $t->listMembers($id, $c);
			$nextlist = (string) $userlist->next_cursor;
			$prelist = (string) $userlist->previous_cursor;
			$userlist = $userlist->users->user;
			break;
		case 'list_followers':
			$t->type = 'xml';
			$userlist = $t->listFollowers($id, $c);
			$nextlist = (string) $userlist->next_cursor;
			$prelist = (string) $userlist->previous_cursor;
			$userlist = $userlist->users->user;
			break;
		case 'browse':
			$userlist = $t->followers($id, $p);
			break;
	}

	$empty = count($userlist) == 0? true: false;
	if ($empty) {
		if($type == 'blocks'){
			$empty_msg = 'No blocked user to display.';
		}else{
			$empty_msg = 'No tweet to display.';
		}
		echo "<div id=\"empty\">$empty_msg</div>";
	} else {
		$output = '<ol class="rank_list">';
		foreach ($userlist as $user) {
			$output .= "
				<li>
				<span class=\"rank_img\">
				<img id= \"avatar\"title=\"Click for more...\" src=\"".getAvatar($user->profile_image_url)."\" />
				</span>
				<div class=\"rank_content\">
				<span class=\"rank_num\"><span class=\"rank_name\"><a href=\"user.php?id=$user->screen_name\">$user->name</a></span>&nbsp;<span class=\"rank_screenname\">$user->screen_name</span><span id=\"rank_id\" style=\"display: none;\">$user->id</span></span>
				<span class=\"rank_count\"><b>Followers:</b> $user->followers_count  <b>Following:</b> $user->friends_count  <b>Tweets:</b> $user->statuses_count</span>
				";
			if ($user->description) $output .= "<span class=\"rank_description\"><b>Bio:</b> $user->description</span>";
			$list_id = explode("/",$id);
			if ($type == 'list_members' &&  $list_id[0] == $t->username) $output .= "<span class=\"status_info\"><a class=\"delete_btn list_delete_btn\" href=\"#\">delete</a></span>";
			$output .= "
				</div>
				</li>
				";
		}
		$output .= "</ol><div id=\"pagination\">";
		if ($type == 'list_members' || $type == 'list_followers' || $type == 'blocks') {
			if ($prelist != 0) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"list_members.php?id=$id&c=$prelist\">Back</a>";
			if ($nextlist != 0) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"list_members.php?id=$id&c=$nextlist\">Next</a>";
		} else {
			if ($id) {
				if ($p >0)
					$output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"$type.php?id=$id&p=" . $previous_page . "\">Back</a>";
				if ($next_page != 0)
					$output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"$type.php?id=$id&p=" . $next_page . "\">Next</a>";
			} else {
				if ($p >0)
					$output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"$type.php?p=" . $previous_page . "\">Back</a>";
				if ($next_page != 0)
					$output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"$type.php?p=" . $next_page . "\">Next</a>";
			}
		}
		$output .= "</div>";

		echo $output;
	}
?>
</div>
