<?php
	include_once('../lib/twitese.php');
	function getTrends($format = 'json'){
		if($format !== 'json' && $format !== 'xml'){
			return false;
		}
		$url = 'http://search.twitter.com/trends.'.$format;
		$response = objectifyJson(processCurl($url));
		return $response;
	}

	function outputTrends($format = 'json'){
		$trends = getTrends($format);
		if(!isset($trends->trends)){
			return false;
		}
		$html = '';
		foreach ($trends->trends as $trend) {
			$li = '
				<li>
				<a href="search.php?q='.rawurlencode($trend->name).'">'.$trend->name.'</a>
				</li>
				';
			$html .= $li;
		}

		return $html;
	}
?>
