<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include ('../lib/twitese.php');
	$tr = getTwitter()->trends();
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
