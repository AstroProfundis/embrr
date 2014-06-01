<?php
	ob_start();
	if(!isset($_SESSION)){
		session_start();
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
<meta name="keywords" content="embr, open source, php, twitter, oauth" />
<meta name="description" content="Vivid Interface for Twitter" />
<meta name="author" content="Contributors" />
<link rel="icon" href="img/favicon.ico" />
<link id="css" href="css/main.css" rel="stylesheet" />
<link href="//cdn.jsdelivr.net/fontawesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" />
<title>Embr / <?php echo $title ?></title>
<?php 
	$myCSS = getDefCookie("myCSS");
	$old_css = "ul.sidebar-menu li.active a";
	$new_css = "ul.sidebar-menu a.active";
	$myCSS = str_replace($old_css,$new_css,$myCSS);
	$fontsize = getDefCookie("fontsize","13px");
	$Bgcolor = getDefCookie("Bgcolor");
	$Bgimage = getAvatar(getDefCookie("Bgimage"));
	$Bgrepeat = getDefCookie("Bgrepeat","no-repeat");
	
	if ($title != 'Error' ){
		setcookie('loginPage',$_SERVER['PHP_SELF'],$_SERVER['REQUEST_TIME']+3600*24);
	}
?>
<style type="text/css">
<?php echo $myCSS ?>
a:active,a:focus {outline:none}
body {font-size:<?php echo $fontsize ?> !important;<?php 
	if ($Bgcolor != "") echo 'background-color:'.$Bgcolor.';';
 	if ($Bgimage != "") echo 'background-image: url("'.$Bgimage.'");';
 ?>background-repeat:<?php echo $Bgrepeat ?>}
</style>
<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-2.1.1.min.js"></script>
<script src="js/jquery.embrr.utils.js"></script>
<script src="js/mediaPreview.js"></script>
<script src="js/public.js"></script>
</head>
<body>
<div id="shortcutTip" style="display:none"></div>
	<header>
		<div class="wrapper">
		<div id="sentTip"></div>
			<a href="index.php"><img id="logo" style="float:left" width="155" height="49" src="img/logo.png" /></a>
			<nav class="round">
			<ul>
				<?php $scheme=(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != "on") ? 'http://' : 'https://';
				$base_url=str_replace('http://',$scheme,BASE_URL);
				?> 
				<li><a href="index.php">Home</a></li>
				<li><a href="profile.php">Profile</a></li>
				<li><a href="setting.php">Settings</a></li>
				<li><a href="logout.php">Logout</a></li>			
			</ul>
			</nav>
		</div>
	</header>
	<div id="content">
		<div class="wrapper">
			<div class="content-bubble-arrow"></div>
				<table cellspacing="0" class="columns">
					<tbody>
						<tr>
							<td id="left" class="column round-left">
