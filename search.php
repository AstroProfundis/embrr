<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('lib/twitese.php');
	$title = "Search";
	include ('inc/header.php');

	function getSearch($query, $sinceid, $maxid){
		GLOBAL $output;
		$t = getTwitter();
		$answer = $t->search($query,$sinceid,$maxid);

		//if ($statuses === false) {
		//	header('location: error.php');exit();
		//}
		$resultCount = count($answer->statuses);
		if ($resultCount <= 0) {
			echo "<div id=\"empty\">No tweet to display.</div>";
		} else {
			include_once('lib/timeline_format.php');
			$output = '<ol class="timeline" id="allTimeline">';
			foreach ($answer->statuses as $status) {
				if (isset($status->retweeted_status)) {
                                        $output .= format_retweet($status);
                                } else {
                                        $output .= format_timeline($status,$t->username);
                                }
			}
			$output .= "</ol><div id=\"pagination\">";

			$next_results = isset($answer->search_metadata->next_results) ? $answer->search_metadata->next_results : false;
			if ($next_results) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"search.php". $next_results ."\">Next</a>";
			$output .= "</div>";
		}
	}

	if (!loginStatus()) header('location: login.php');
?>
<style>#trend_entries{display:block}</style>
<script src="js/search.js"></script>
<div id="statuses" class="column round-left">

	<form action="search.php" method="get" id="search_form">
		<input type="text" name="q" id="query" value="<?php echo $_GET['q'] ?>" />
		<input type="submit" class="more round" style="width: 103px; margin-left: 10px; display: block; float: left; height: 34px; font-family: tahoma; color: rgb(51, 51, 51);" value="Search">
	</form>
<?php
	$sinceid = false;
	$maxid = false;
	if (isset($_GET['since_id'])) {
		$sinceid = $_GET['p'];
	}
	if (isset($_GET['max_id'])) {
		$maxid = $_GET['max_id'];
	}
	$output = '';
	if (isset($_GET['q'])) {
		$q = $_GET['q'];
		getSearch($q, $sinceid, $maxid);
	}
	echo $output;
?>
</div>

<?php 
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
