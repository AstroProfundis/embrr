<?php 
	include ('lib/twitese.php');
	$title = "Error";
	include ('inc/header.php');
?>

<div id="login_area">
	<div id="error">
		<?php
		if ( isset($_GET['t'])){ 
			switch($_GET['t']){
				case 1:
				echo '<p>Fail to connect Twitter right now. Please <a href="index.php">go back</a> or <a href="logout.php">sign in</a> minutes later.</p>';
				if(loginStatus())
				{
				echo '<p> The API will reset in '.intval((format_time(getTwitter()->ratelimit()->reset_time) - time())/60).' min(s).';
				break;
				}
				default:
				echo '<p>Ooops, an unknown error occured. Please <a href="index.php">go back</a> or <a href="logout.php">sign in</a> again.</p>';
			}
		} else{
			echo '<p>Ooops, an unknown error occured. Please <a href="index.php">go back</a> or <a href="logout.php">sign in</a> again.</p>';
		}
		?>
	</div>
</div>	

<?php include ('inc/footer.php') ?>
