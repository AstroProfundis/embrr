<?php
	/* API Setting
	 *
	 * This is the URL embrr used for all API calls, DO NOT change unless you know what you're doing!    *
	 */
	define('API_URL', 'https://api.twitter.com/1.1');
	define('UPLOAD_URL', 'https://upload.twitter.com/1.1');
	
	/* Basic Configuration
	 * 
	 * - A SECURE_KEY is a string that being used to encrypt and decrypt cookies, you should NOT leave   *
	 * it empty due to security reasons, a long random string is usually an option.                      *
	 *
	 * - The BASE_URL is where your embrr hosted and will be used in many in-site links.                 *
	 *
	 * - The CONSUMER_KEY and CONSUMER_SECRET is two string that Twitter used to authorize applications, *
	 * you can find them on the app detail page at https://api.twitter.com. You can leave the default    *
	 * values unchanged if you don't know how to create a new app on twitter or you can't access twitter *
	 * website, but this may be unsafe for you and your account. Use at your own risk!                   *
	 */
	define('SECURE_KEY', 'YOU SHOULD CHANGE IT');
	define('BASE_URL','http://embr.in');
	define("CONSUMER_KEY", "TEItTaPASySnYxziOyIdag");
	define("CONSUMER_SECRET", "xJEoWvBumpqgiiBuviWTa7GT8KCvP7Kv3n0hixhJaZY");

	/* Private Setting
	 *
	 * If you set ID_AUTH true, only whitelisted user can login and use this embrr, you must set up the *
	 * following AUTH_ID if you do so. Authorized user ID is seperated by ','.                          *
	 */
	define('ID_AUTH',false);
	$AUTH_ID = array('username1','username2','username3','......');

	/* Optional Information
	 *
	 * Here you can set the site owner's Twitter ID and website URL, they will show at the footer.      *
	 */
	define('SITE_OWNER', 'TWITTER');
	define('BLOG_SITE','');
?>
