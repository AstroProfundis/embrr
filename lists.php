<?php 
	include ('lib/twitese.php');
	$title = "Lists";
	include ('inc/header.php');
	
	if (!loginStatus()) header('location: login.php');
?>

<script src="js/lists.js"></script>

<div id="statuses">
	<?php 
		$t = getTwitter();

		$isSelf = true;
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			$isSelf = false;
		} else {
			$id = $t->username;
		}
		$type = isset($_GET['t'])? $_GET['t'] : 1;
		$c = isset($_GET['c'])? $_GET['c'] : -1;   // cursor
		switch ($type) {
			case 0:
				$lists = $t->followedLists($id, $c);
				break;
			case 1:
				$lists = $t->myLists($id);
				break;
			case 2:
				$lists = $t->beAddedLists($id, $c);
				break;
			default:
				$lists = false;
		}
		$nextlist = $lists->next_cursor;
		$prelist = $lists->previous_cursor;
		$lists = $lists->lists; 
		if ($lists === false) {
			header('location: error.php');exit();
		} 
		
		
	?>
	<div id="subnav">
	<?php if ($isSelf) {
		if ($type == 0) {?>
	       	<span class="subnavNormal">Lists you follow</span><span class="subnavLink"><a href="lists.php?t=1">Lists you created</a></span><span class="subnavLink"><a href="lists.php?t=2">Lists following you</a></span>
		<?php } else if ($type == 1) {?>
	       	<span class="subnavLink"><a href="lists.php?t=0">Lists you follow</a></span><span class="subnavNormal">Lists you created</span><span class="subnavLink"><a href="lists.php?t=2">Lists following you</a></span>
		<?php } else {?>
			<span class="subnavLink"><a href="lists.php?t=0">Lists you follow</a></span><span class="subnavLink"><a href="lists.php?t=1">All your lists</a></span><span class="subnavNormal">Lists following you</span>
		<?php }
	} else {
		if ($type == 0) {?>
	       	<span class="subnavNormal">Following Lists</span><span class="subnavLink"><a href="lists.php?id=<?php echo $id?>&t=1">All Lists</a></span><span class="subnavLink"><a href="lists.php?id=<?php echo $id?>&t=2">Lists Following @<?php echo $id?></a></span>
		<?php } else if ($type == 1) {?>
	       	<span class="subnavLink"><a href="lists.php?t=0&id=<?php echo $id?>">Following Lists</a></span><span class="subnavNormal">All Lists</span><span class="subnavLink"><a href="lists.php?id=<?php echo $id?>&t=2">Lists Following @<?php echo $id?></a></span>
		<?php } else {?>
			<span class="subnavLink"><a href="lists.php?t=0&id=<?php echo $id?>">Following Lists</a></span><span class="subnavLink"><a href="lists.php?id=<?php echo $id?>&t=1">All Lists</a></span><span class="subnavNormal">Lists Following @<?php echo $id?></span>
		<?php }
	} ?>
    </div>
    
	<?php 
		
		$empty = count($lists) == 0? true: false;
		if ($empty) {
			echo "<div id=\"empty\">No Tweet To Display</div>";
		} else {
			$output = '<ol class="rank_list">';			
			foreach ($lists as $list) {
		
				$listurl = substr($list->uri,1);
				$user = $list->user;
				$listname = explode('/',$list->full_name);
				$mode = $list->mode == 'private' ? "Private" : "Public";
				
				$output .= "
				<li>
					<span class=\"rank_img\"><img src=\"".getAvatar($user->profile_image_url)."\" /></span>
					<div class=\"rank_content\" id=\"list{$list->id_str}\">
						<span class=\"rank_num\"><span class=\"rank_name\"><a href=\"list.php?id=$listurl\"><em>$listname[0]/</em>$listname[1]</a></span></span>
						<span class=\"rank_count\">Followers: {$list->subscriber_count}&nbsp;&nbsp;Members: {$list->member_count}&nbsp;&nbsp;$mode</span> 
				";
				if ($list->description != '') $output .= "<span class=\"rank_description\">Description: $list->description</span>";
				if ($type == 0) $output .= "<span id=\"list_action\"><a id=\"btn\" href=\"javascript:void()\" class=\"unfollow_list\">Unfollow</a></span>";
				if ($type == 1 && $isSelf) $output .= "<span id=\"list_action\"><a id=\"btn\" href=\"javascript:void()\" class=\"edit_list\">Edit</a> <a id=\"btn\" href=\"javascript:void()\" class=\"delete_list\">Delete</a> <a id=\"btn\" href=\"javascript:void()\" class=\"add_member\">Add Members</a></span>";
				$output .= "
					</div>
				</li>
				";
			}
			
			$output .= "</ol>";
			
			echo $output;
		}
		
	?>
	
	<?php if ($isSelf && $type == 1) {?>
	    <a href="#" class="more round" id="list_create_btn" style="margin: 20px auto;">Create a new list</a>
	    <form method="POST" action="./lists.php?t=1" id="list_form">
	    	<input type="hidden" name="list_spanid" value="" id="list_spanid" />
	    	<input type="hidden" name="pre_list_name" value="" id="pre_list_name" />
	    	<input type="hidden" name="is_edit" value="0" id="is_edit" />
	    	<span><label for="list_name">List name</label><input type="text" name="list_name" id="list_name" /></span>
	    	<span><label for="list_description">Description</label><textarea type="text" name="list_description" id="list_description"></textarea></span>
	    	<span><label for="list_protect">Private</label><input type="checkbox" name="list_protect" id="list_protect"  />
			<a style="background: transparent url(img/sprite-icons.png) no-repeat scroll -272px -16px; -moz-background-clip: border; -moz-background-origin: padding; -moz-background-inline-policy: continuous; text-decoration: none; height: 10px; float: right; position: relative; width: 9px; left: 3px; top: -160px;" title="Close" onclick="$('#list_form').slideToggle(300)" href="#"></a>
			<input type="submit" class="btn" id="list_submit" value="" />
			
			</span>
	    	<span></span>
	    </form>
	    
	    
	<?php }?>
	
	<div id="pagination">
	<?php 
	    if ($type == 0 || $type == 2) {
	    	if ($isSelf) {
				if ($prelist != 0) echo "<a id=\"less\" class=\"round more\" style=\"float: left;\" href=\"lists.php?t=$type&c=$prelist\">Back</a>";
				if ($nextlist != 0) echo "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"lists.php?t=$type&c=$nextlist\">Next</a>";
	    	} else {
				if ($prelist != 0) echo "<a id=\"less\" class=\"round more\" style=\"float: left;\" href=\"lists.php?id=$id&t=$type&c=$prelist\">Back</a>";
				if ($nextlist != 0) echo "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"lists.php?id=$id&t=$type&c=$nextlist\">Next</a>";
	    	}
		}
	?>
	</div>
</div>

<?php 
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
