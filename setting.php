<?php
	include ('lib/twitese.php');
	$title = "Settings";
	include ('inc/header.php');	
	if (!loginStatus()) header('location: login.php');	
?>
<script src="js/colorpicker.js"></script>
<script src="js/setting.js"></script>
<link rel="stylesheet" href="css/colorpicker.css" />
<div id="statuses" class="column round-left">
	<div id="setting">
<div id="setting_nav">
<?php
	$settingType = isset($_GET['t'])? $_GET['t'] : 1;
	switch($settingType){
		case 'profile':
?>
			<span class="subnavLink"><a href="setting.php">Customize</a></span><span class="subnavNormal">Profile</span>
<?php			
			break;
		default:
?>
			<span class="subnavNormal">Customize</span><span class="subnavLink"><a href="setting.php?t=profile">Profile</a></span>
<?php	
	}
?>
</div>
<?php
	switch($settingType){
		case 'profile':
			$user = getTwitter()->veverify(true);
?>
			<form id="setting_form" action="ajax/uploadImage.php?do=profile" method="post" enctype="multipart/form-data">
				<fieldset class="settings">
				<legend>Avatar</legend>
				<ol>
				<li style="display:inline-block"><img src="<?php echo isset($_COOKIE['imgurl']) ? $_COOKIE['imgurl'] : getAvatar($user->profile_image_url)?>" id="avatarimg"></img></li>
				<ol style="margin-left:29px">
					<li><input type="file" name="image" id="profile_image"/></li>
					<li><input type="submit" id="AvatarUpload" class="btn" value="Upload"/><small style="margin-left:10px;vertical-align: middle;">BMP,JPG or PNG accepted, less than 800K.</small></li>
				</ol></ol>
				</fieldset>
			</form>
			<form id="setting_form" action="ajax/uploadImage.php?do=background" method="post" enctype="multipart/form-data">
				<fieldset class="settings">
				<legend>Background</legend>
				<ol>
				<li style="display:inline-block"><img src="<?php echo getAvatar($user->profile_background_image_url)?>" id="backgroundimg" style="max-width: 460px;"></img></li>
				<li><input type="file" name="image" id="profile_background"/></li>
				<li><input type="submit" id="BackgroundUpload" class="btn" value="Upload"/><small style="margin-left:10px;vertical-align: middle;">BMP,JPG or PNG accepted, less than 800K.</small></li>
				<li>
				<input id="tile" type="checkbox" <?php echo $user->profile_background_tile ? 'checked="checked"' : '' ?> />
				<label>Tile the profile background</label>
				</li>
				</ol>
				</fieldset>
			</form>
			<form id="setting_form" action="ajax/updateProfile.php" method="post">
				<fieldset class="settings">
				<legend>Literature</legend>
				<table id="setting_table">
				<tr>
				<td class="setting_title">Name: </td>
				<td><input class="setting_input" type="text" name="name" value="<?php echo isset($user->name) ? $user->name : ''?>" /></td>
				</tr>
				<tr>
				<td class="setting_title">URL: </td>
				<td><input class="setting_input" type="text" name="url" value="<?php
			if (!isset($user->url))
				echo '';
			else {
				$hops = array();
				$newurl = expandRedirect($user->url, $hops);
				echo $newurl;
			}
		?>" /></td>
				</tr>
				<tr>
				<td class="setting_title">Location: </td>
				<td><input class="setting_input" type="text" name="location" value="<?php echo isset($user->location) ? $user->location : '' ?>" /></td>
				</tr>
				<tr>
				<td class="setting_title">Bio: </td><td><small style="margin-left:5px;vertical-align: top;">*Max 160 chars</small></td>
				</tr><tr>
				<td></td>
				<td><textarea id="setting_text" name="description"><?php echo isset($user->description) ? $user->description : '' ?></textarea></td>
				</tr>
				</table>
				<input type="submit" id="saveProfile" class="btn" value="Save" />
				</fieldset>
<?php
			break;
		default:
?>
		<form id="style_form" action="setting.php" method="post">
			
			<fieldset class="settings">

			<legend>Utility</legend>

			<input id="proxifyAvatar" type="checkbox" />
			<label>Proxify the Avatar</label>
			
			<br /><br />			
			<input id="autoscroll" type="checkbox" />
			<label>Timeline Autopaging</label>
			
			<br /><br />			
			<input id="sidebarscroll" type="checkbox" />
			<label>Fixed Sidebar</label>

			<br /><br />
			Share to Twitter: <a class="share" title="Drag me to share!" href="javascript:var%20d=document,w=window,f='<?php echo $base_url."/share.php" ?>',l=d.location,e=encodeURIComponent,p='?u='+e(l.href)+'&t='+e(d.title)+'&d='+e(w.getSelection?w.getSelection().toString():d.getSelection?d.getSelection():d.selection.createRange().text)+'&s=bm';a=function(){if(!w.open(f+p,'sharer','toolbar=0,status=0,resizable=0,width=600,height=300,left=175,top=150'))l.href=f+'.new'+p};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else{a()}void(0);">Share</a>
			<small>(Bookmark this link for future use)</small>

			</fieldset>
			
			<fieldset class="settings">

			<legend>Media Preview</legend>

			<input id="showpic" type="checkbox" checked="checked" />
			<label>Enable Images Preview</label>
			<small>(Supports common image hostings)</small>

			<br /><br />

			<input id="mediaPreSelect" type="checkbox" checked="checked" />
			<label>Enable Videos Preview</label>
			<small>(Supports Xiami and Tudou)</small><br />

			</fieldset>

			<fieldset class="settings">

			<legend>Auto Refresh Interval</legend>

			<label>Home Page</label>
			<select id="homeInterval" name="homeInterval" value="<?php echo getCookie('homeInterval')?>">
				<option value="1">1 min</option>
				<option value="2" selected="selected">2 min (Default)</option>
				<option value="3">3 min</option>
				<option value="5">5 min</option>
				<option value="10">10 min</option>
				<option value="0">Never</option>
			</select>
			<label>Updates Page</label>
			<select id="updatesInterval" name="updatesInterval" value="<?php echo getCookie('updatesInterval')?>">
				<option value="1">1 min</option>
				<option value="2">2 min</option>
				<option value="3" selected="selected">3 min (Default)</option>
				<option value="5">5 min</option>
				<option value="10">10 min</option>
				<option value="0">Never</option>
			</select>

			</fieldset>

			<fieldset class="settings">

			<legend>UI Preferences</legend>
			<input id="twitterbg" type="checkbox" />
			<label>Use twitter account background</label>
			
			<br /><br />

			<input id="shownick" type="checkbox" />
			<label>Use nickname instead of username</label>

			<br /><br />

			<label>Background Color</label>
			<input class="bg_input" type="text" id="bodyBg" name="bodyBg" value="<?php echo getDefCookie("Bgcolor","") ?>" />
			<small>(Choose your favorite color here)</small>

			<br /><br />

			<label>Font Size</label>
			<select id="fontsize" name="fontsize" value="<?php echo getCookie('fontsize')?>">
				<option value="12px">Small</option>
				<option value="13px" selected="selected">Middle(Default)</option>
				<option value="14px">Large</option>
				<option value="15px">Extra Large</option>
			</select>
			<small>(Set the font size)</small>

			<br /><br />		

			<label>Customize CSS</label>
			<small>(You can put your own CSS hack here, or your Twitter style code)</small>
			<br />
			<label>Tips:</label>
			<small>You must use <a href="http://i.zou.lu/csstidy/" target="_blank" title="Powered by Showfom">CSSTidy</a> to compress your stylesheet.</small>
			<br />
			<textarea type="text" id="myCSS" name="myCSS" value="" /><?php echo getDefCookie("myCSS","") ?></textarea>
			</fieldset>

<?php
	}
?>
	<a class="share" title="Drag me to share!" href="javascript:var%20d=document,w=window,f='<?php echo $base_url."/share.php" ?>',l=d.location,e=encodeURIComponent,p='?u='+e(l.href)+'&t='+e(d.title)+'&d='+e(w.getSelection?w.getSelection().toString():d.getSelection?d.getSelection():d.selection.createRange().text)+'&s=bm';a=function(){if(!w.open(f+p,'sharer','toolbar=0,status=0,resizable=0,width=600,height=300,left=175,top=150'))l.href=f+'.new'+p};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else{a()}void(0);">Share</a>
    <a id="reset_link" href="#" title="You will lose all customized settings!">Reset to default</a>

</form>
	</div>
</div>

<?php 
	include ('inc/sidebar.php');
	include ('inc/footer.php');
?>
