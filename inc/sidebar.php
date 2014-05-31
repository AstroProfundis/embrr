<td class="column round-right" id="side_base">
<table>
<tr>
<td>
<div id="side" class="round-right">
	<div id="sideinfo">
		<a href="profile.php"><img id="sideimg" src="<?php echo getCookie("imgurl")?>" /></a>
		<span id="sideid"><span id="side_name"><?php echo getEncryptCookie('twitese_name')?></span><a href="#" id="profileRefresh" title="refresh your profile" class="fa fa-repeat"></a></span>
		<a href="profile.php"><span id="me_tweets"><span id="update_count"><?php echo getCookie('statuses_count')?></span> tweets</span></a>
	</div>
	<?php if (strrpos($_SERVER['PHP_SELF'], 'profile')) {
		$t = getTwitter();
	$user = $t->showUser();
	$expanded_url = $user->entities->url->urls[0]->expanded_url ? $user->entities->url->urls[0]->expanded_url : $user->url;
?>
	<ul id="user_info_profile">
		<li><span>Name</span> <?php echo $user->name ?></li>
		<?php if ($user->location) echo "<li><span>Location</span> $user->location</li>"; ?>
		<?php if (($expanded_url) and (strlen($expanded_url)>20)) echo '<li><span>Web</span> <a href="' .$expanded_url. '" target="_blank">' .substr($expanded_url, 0, 20). '...</a></li>'; else if (($expanded_url) and (strlen($expanded_url)<=20)) echo '<li><span>Web</span> <a href="' .$expanded_url. '" target="_blank">' .$expanded_url. '</a></li>';?>
		<?php if ($user->description) echo "<li><span>Bio</span> ".formatText($user->description)."</li>"; ?>
		</ul>
	<?php }?>
	<ul id="user_stats">
		<li>
			<a href="friends.php">
				<span class="count"><?php echo getCookie('friends_count')?></span>
				<span class="label">following</span>
			</a>
		</li>
		<li>
			<a href="followers.php">
				<span class="count"><?php echo getCookie('followers_count')?></span>
				<span class="label">followers</span>
			</a>
		</li>
		<li>
			<a href="lists.php?t=2">
				<span class="count"><?php $listed_count = getCookie('listed_count'); echo ($listed_count > 0 ? $listed_count : 0);?></span>
				<span class="label">listed</span>
			</a>
		</li>
	</ul>
	
		<li>
		<DIV id='profile' class='section'> 
		<p id="sidebarTip" class='promotion round'> 
	<?php
	$preset = array(
		array(
			'term' => 'Short&middot;cuts',
			'def' => '<em>n.</em> Use shortcuts in Embrr.',
			'more' => '<strong>Shortcuts available now:</strong><br>
			C / U - Update<br>
			T - Go to top<br>
			B - Go to bottom<br>
			R - Refresh<br>
			S - Search'
		),
		array(
			'term' => 'User Di&middot;rect View',
			'def' => '<em>n.</em> Now you can view the user page of your interested more incentively.',
			'more' => 'Take @'.SITE_OWNER.' for example, you can visit his/her page via '.BASE_URL.'/'.SITE_OWNER,
		),
		array(
			'term' => 'Realtime Refresh',
			'def' => '<em>v.</em> Now you can refresh your profile whenever you like!',
			'more' => 'See the circle behind your username? Try to click it!'
		),
	);
		if(isset($_COOKIE['Tip_Title']) || isset($_COOKIE['Tip_Content']) || isset($_COOKIE['Tip_More'])){
			$raw = array(
				'term' => getEncryptCookie('Tip_Title'),
				'def' => getEncryptCookie('Tip_Content'),
				'more' => getEncryptCookie('Tip_More'),
			);
			initSidebarTip($raw);
		} else {
			srand((double)microtime()*1000000);
			initSidebarTip($preset[rand(0,2)]);
		}
	?>
		</p>
		</DIV>
		</li>
		
	<div class="clear"></div>
	<ul id="primary_nav" class="sidebar-menu">
	<li id="updates_tab"><a class="in-page-link" href="all.php"><span>Updates</span></a></li>
	<li id="replies_tab"><a class="in-page-link" href="replies.php"><span>@<?php echo is_null(getEncryptCookie('twitese_name')) ? $t->screen_name : getEncryptCookie('twitese_name'); ?></span></a></li>
	<li id="msgs_tab"><a class="in-page-link" href="message.php"><span>Direct Messages</span></a></li>
	<li id="lists_tab"><a class="in-page-link" href="lists.php"><span>Lists</span></a></li>
	<li id="favs_tab"><a class="in-page-link" href="favor.php"><span>Favorites</span></a></li>
	<li id="retweets_tab"><a class="in-page-link" href="retweets.php"><span>Retweets</span></a></li>
	</ul>
	<?php include ('sidepost.php') ?>
</div>
</td>
</tr>
</table>
<?php
	function initSidebarTip($entity){
		echo '<a class="definition"><strong contenteditable="true" class="sidebarTipText">'.$entity['term'].'</strong><span contenteditable="true" class="sidebarTipText">'.$entity['def'].'</span></a><br><br><span>Click for more details.<span id="indicator">[+]</span></span><span id="sidebarTip_more"><span contenteditable="true" class="sidebarTipText"><br>'.$entity['more'].'</span><br><br><a href="#" id="tip_reset" title="You will lose all customized Tips!">Reset to default</a></span>';
	}
?>
