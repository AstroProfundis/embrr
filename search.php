<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('lib/twitese.php');
	$title = "Search";
	include ('inc/header.php');

	function getSearch($query, $page){
		GLOBAL $output;
		$t = getTwitter();
		$MAX_TWEETS = 20;
		$statuses = $t->search($query,$page,$MAX_TWEETS);

		//if ($statuses === false) {
		//	header('location: error.php');exit();
		//}
		$resultCount = count($statuses->results);
		if ($resultCount <= 0) {
			echo "<div id=\"empty\">No tweet to display.</div>";
		} else {
			include_once('lib/timeline_format.php');
			$output = '<ol class="timeline" id="allTimeline">';
			foreach ($statuses->results as $status) {
				$output .= format_search($status);
			}
			$output .= "</ol><div id=\"pagination\">";

			if ($page > 1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"search.php?q=".urlencode($query)."&p=" . ($page - 1) . "\">Back</a>";
			if ($resultCount == $MAX_TWEETS) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"search.php?q=".urlencode($query)."&p=" . ($page + 1) . "\">Next</a>";
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
	$p = 1;
	if (isset($_GET['p'])) {
		$p = (int) $_GET['p'];
		if ($p <= 0) $p = 1;
	}
	$output = '';
	if (isset($_GET['q'])) {
		$q = $_GET['q'];
		getSearch($q, $p);
	}
	echo $output;
?>
</div>

<?php 
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
