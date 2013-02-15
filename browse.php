<?php 
	include ('lib/twitese.php');
	$title = "Public";
	include ('inc/header.php');
	if (!loginStatus()) header('location: login.php');
?>

<script src="js/browse.js"></script>

<div id="statuses" class="column round-left">
	<h2 id="browse_title">See what people are saying about…</h2>
	<div class="clear"></div>
	
	<?php
	// selected from top 50 freqently used Han Chracter in http://www.cslog.cn/Content/word-frequency-list-of-chinese/
		$seed = array(
		'的', '一', '是', '不', '了', '我', '人', '在', '有', '这', '他', '来', '个', '上', '说', '中', '大', '为', '到', '道', '你', '们', '出', '就', '时', '以', '之', '那', '和', '子', '地', '得', '自', '要', '下', '可', '而', '学', '过', '对', '么', '然', '她', '国', '去', '里'
	);
		if(!isset($_COOKIE['browse_seed'])) {
			$browse_seed = $seed[mt_rand(0,48)];
			setcookie('browse_seed', $browse_seed, $_SERVER['REQUEST_TIME']+300, '/');
		} else {
			$browse_seed = $_COOKIE['browse_seed'];
		}
		$t = getTwitter();
		$p = 1;
		if (isset($_GET['p'])) {
			$p = (int) $_GET['p'];
			if ($p <= 0) $p = 1;
		}
	
		$statuses = $t->search($browse_seed, $p, 50);
		$resultCount = count($statuses->results);
		if ($resultCount <= 0) {
			echo "<div id=\"empty\">No tweet to display.</div>";
		} else {
			include_once('lib/timeline_format.php');
			$output = '<ol class="timeline" id="allTimeline">';
			foreach ($statuses->results as $status) {
				if(!preg_match('/[\p{Hiragana}\p{Katakana}\p{Hangul}]+/u', $status->text) ) { // filter the Japanese and Korean tweets since some of Han Character included.
					$output .= format_search($status);
				}
			}
			$output .= "</ol><div id=\"pagination\">";

			if ($p >1) $output .= "<a id=\"more\" class=\"round more\" style=\"float: left;\" href=\"browse.php?p=" . ($p-1) . "\">Back</a>";
			if (!$empty) $output .= "<a id=\"more\" class=\"round more\" style=\"float: right;\" href=\"browse.php?p=" . ($p+1) . "\">Next</a>";
			
			$output .= "</div>";
			echo $output;
		}
	?>
</div>

<?php 
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
