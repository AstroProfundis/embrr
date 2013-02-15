<?php
include ('lib/twitese.php');
$title = "@{$_GET['id']} - Following";
include ('inc/header.php');
?>
<script src="js/list_members.js"></script>
<?php
$type = 'list_members';
include ('inc/userlist.php');
include ('inc/sidebar.php');
include ('inc/footer.php');
?>
