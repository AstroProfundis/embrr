<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	$t = getTwitter();
	if (isset($_COOKIE['woeid'])) {
		$woeid = getEncryptCookie('woeid');
	} else {
		$tr = $t->trends_closest($_GET['lat'], $_GET['long']);
		if (isset($tr->woeid)) {
			$woeid = $tr[0]->woeid;
		} else {
			$woeid = 1;
		}
		setEncryptCookie('woeid', $woeid, $_SERVER['REQUEST_TIME'] + 3600*24);
	}
	$tr = $t->trends_place($woeid);
	$trends = $tr[0]->trends;
	
	if (count($trends) == 0) {
		echo "empty";
	}else{
		$html = '';
		foreach ($trends as $trend) {
			$li = '
				<li>
				<a href="search.php?q='.$trend->query.'">'.$trend->name.'</a>
				</li>
				';
			$html .= $li;
		}
		echo $html;
	}
?>
