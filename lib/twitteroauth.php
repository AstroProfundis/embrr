<?php
/*
 * Abraham Williams (abraham@abrah.am) http://abrah.am
 *
 * Basic lib to work with Twitter's OAuth beta. This is untested and should not
 * be used in production code. Twitter's beta could change at anytime.
 *
 * Code based on:
 * Fire Eagle code - http://github.com/myelin/fireeagle-php-lib
 * twitterlibphp - http://github.com/jdp/twitterlibphp
 */

//require_once('config.php');
//require_once('oauth_lib.php');

/**
 * Twitter OAuth class
 */
class TwitterOAuth {
	/* Contains the last HTTP status code returned */
	public $http_code;
	/* Contains the last API call */
	public $last_api_call;
	/* Set up the API root URL */
	//public $host = "https://api.twitter.com/1/";
	public $host = API_URL;
	/* Set timeout default */
	public $timeout = 5;
	/* Set connect timeout */
	public $connecttimeout = 30;
	/* Verify SSL Cert */
	public $ssl_verifypeer = FALSE;
	/* Response type */
	public $type = 'json';
	/* Decode return json data */
	public $decode_json = TRUE;

	public $source = 'embr';

	// user info
	public $username;
	public $screen_name;
	public $user_id;
	
	//for debug use
	public $curl_info;
	public $http_header;

	/**
	 * Set API URLS
	 */
	function accessTokenURL()  { return 'https://api.twitter.com/oauth/access_token'; }
	function authenticateURL() { return 'https://api.twitter.com/oauth/authenticate'; }
	function authorizeURL()    { return 'https://api.twitter.com/oauth/authorize'; }
	function requestTokenURL() { return 'https://api.twitter.com/oauth/request_token'; }

	/**
	 * Debug helpers
	 */
	function lastStatusCode() { return $this->http_status; }
	function lastAPICall() { return $this->last_api_call; }

	/**
	 * construct TwitterOAuth object
	 */
	function __construct($consumer_key, $consumer_secret, $oauth_token = NULL, $oauth_token_secret = NULL) {
		$this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
		$this->consumer = new OAuthConsumer($consumer_key, $consumer_secret);
		if (!empty($oauth_token) && !empty($oauth_token_secret)) {
			$this->token = new OAuthConsumer($oauth_token, $oauth_token_secret);
			$this->screen_name = $_SESSION['access_token']['screen_name'];
			$this->username = $_SESSION['access_token']['screen_name'];
			$this->user_id = $_SESSION['access_token']['user_id'];
		} else {
			$this->token = NULL;
		}
	}


	/**
	 * Get a request_token from Twitter
	 *
	 * @returns a key/value array containing oauth_token and oauth_token_secret
	 */
	function getRequestToken($oauth_callback = NULL) {
		$parameters = array();
		if (!empty($oauth_callback)) {
			$parameters['oauth_callback'] = $oauth_callback;
		} 
		$request = $this->oAuthRequest($this->requestTokenURL(), 'GET', $parameters);
		$token = OAuthUtil::parse_parameters($request);
		$this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}

	/**
	 * Get the authorize URL
	 *
	 * @returns a string
	 */
	function getAuthorizeURL($token) {
		if (is_array($token)) {
			$token = $token['oauth_token'];
		}
		return $this->authorizeURL() . "?oauth_token={$token}";
	}

	/**
	 * Exchange the request token and secret for an access token and
	 * secret, to sign API calls.
	 *
	 * @returns array("oauth_token" => the access token,
	 *                "oauth_token_secret" => the access secret)
	 */
	function getAccessToken($oauth_verifier = FALSE) {
		$parameters = array();
		if (!empty($oauth_verifier)) {
			$parameters['oauth_verifier'] = $oauth_verifier;
		}
		$request = $this->oAuthRequest($this->accessTokenURL(), 'GET', $parameters);
		$token = OAuthUtil::parse_parameters($request);
		$this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
		return $token;
	}

	/**
	 * GET wrappwer for oAuthRequest.
	 */
	function get($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'GET', $parameters);
		if($response == false){
			return false;
		}
		if ($this->type == 'json' && $this->decode_json) {
			return json_decode($response);
		}elseif($this->type == 'xml' && function_exists('simplexml_load_string')){
			return simplexml_load_string($response);
		}
		return $response;
	}

	/**
	 * POST wreapper for oAuthRequest.
	 */
	function post($url, $parameters = array(), $multipart = NULL) {
		$response = $this->oAuthRequest($url, 'POST', $parameters, $multipart);
		if($response === false){
			return false;
		}
		if ($this->type === 'json' && $this->decode_json) {
			return json_decode($response);
		}elseif($this->type == 'xml' && function_exists('simplexml_load_string')){
			return simplexml_load_string($response);
		}
		return $response;
	}

	/**
	 * DELTE wrapper for oAuthReqeust.
	 */
	function delete($url, $parameters = array()) {
		$response = $this->oAuthRequest($url, 'DELETE', $parameters);
		if($response === false){
			return false;
		}
		if ($this->type === 'json' && $this->decode_json) {
			return json_decode($response);
		}elseif($this->type == 'xml' && function_exists('simplexml_load_string')){
			return simplexml_load_string($response);
		}
		return $response;
	}

	/**
	 * Format and sign an OAuth / API request, then make an HTTP request
	 */
	function oAuthRequest($url, $method, $parameters, $multipart=NULL) {
		if ($url[0] == '/') { //non-twitter.com api shall offer the entire url.
			$url = "{$this->host}{$url}.{$this->type}";
		}
		$request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
		$request->sign_request($this->sha1_method, $this->consumer, $this->token);
		$request->set_http_header($multipart);
		
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);

		switch ($method) {
		case 'GET':
			curl_setopt($ci, CURLOPT_URL, $request->to_url());
			curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
			break;
		case 'POST':
			$postfields = $multipart ? $multipart : $request->to_postdata();
			curl_setopt($ci, CURLOPT_URL, $request->get_normalized_http_url());
			curl_setopt($ci, CURLOPT_HTTPHEADER, $request->http_header);
			curl_setopt($ci, CURLOPT_POST, TRUE);
			if (!empty($postfields)) {
				curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
			}
			break;
		case 'DELETE':
			$postfields = $request->to_postdata($multipart);
			$url = $request->get_normalized_http_url();
			curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
			if (!empty($postfields)) {
				$url = "{$url}?{$postfields}";
				curl_setopt($ci, CURLOPT_URL, $url);
			}
		}

		$response = curl_exec($ci);
		$this->http_header = $request->http_header;
		$this->curl_info = curl_getinfo($ci);
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->last_api_call = curl_getinfo($ci, CURLINFO_EFFECTIVE_URL);
		
		curl_close ($ci);
		
		return $response;
	}
	

	/* ---------- API METHODS ---------- */
	/*                                   */
	/* ---------- Block ---------- */
	function blockingIDs(){
		$url = '/blocks/blocking/ids';
		return $this->get($url);
	}

	function blockingList($id, $cursor=-1, $skip_status = 1){
		$url = '/blocks/list';
		$args = array();
		if($id)
			$args['user_id'] = $id;
		if($cursor)
			$args['cursor'] = $cursor;
		$args['skip_status'] = $skip_status;
		return $this->get($url, $args);
	}

	function blockUser($id){
		$url = "/blocks/create/$id";
		return $this->post($url);
	}

	function isBlocked($id){
		$url = "/blocks/exists/$id";
		return $this->get($url);
	}

	function unblockUser($id){
		$url = "/blocks/destroy/$id";
		return $this->delete($url);
	}

	/* ---------- Messages ---------- */
	function deleteDirectMessage($id){
		$url = "/direct_messages/destroy/$id";
		return $this->delete($url);
	}

	function directMessages($page = false, $since_id = false, $count = null, $include_entities = true){
		$url = '/direct_messages';
		$args = array();
		if( $since_id )
			$args['since_id'] = $since_id;
		if( $page )
			$args['page'] = $page;
		if($include_entities)
			$args['include_entities'] = $include_entities;
		return $this->get($url, $args);
	}

	function sendDirectMessage($user, $text){
		$url = '/direct_messages/new';
		$args = array();
		$args['user'] = $user;
		if($text)
			$args['text'] = $text;
		return $this->post($url, $args);
	}

	function sentDirectMessage($page = false, $since = false, $since_id = false){
		$url = '/direct_messages/sent';
		$args = array();
		if($since)
			$args['since'] = $since;
		if($since_id)
			$args['since_id'] = $since_id;
		if($page)
			$args['page'] = $page;
		return $this->get($url, $args);
	}

	/* ---------- List ---------- */
	function addListMember($listid, $memberid){
		$url = "/lists/members/create_all";
		$args = array();
		if($listid) 
			$args['slug'] = $listid;
		if($memberid)
			$args['user_id'] = $memberid;
		
		return $this->post($url, $args);
	}

	function beAddedLists($owner_screen_name = '', $cursor = false){
		$url = "/lists/memberships";
		$args = array();
		if($owner_screen_name)
			$args['owner_screen_name'] = $owner_screen_name;
		if($cursor){
			$args['cursor'] = $cursor;
		}
		return $this->get($url, $args);
	}

	function createList($name, $description, $isPortect){
		$url = "/lists/create";
		$args = array();
		if($name)
			$args['name'] = $name;
		if($description)
			$args['description'] = $description;
		if($isProtect)
			$args['mode'] = 'private';
		
		return $this->post($url, $args);
	}

	function createdLists($username = '', $cursor = false){
		$url = "/lists/ownerships";
		$args = array();
		if($cursor)
			$args['cursor'] = $cursor;
		
		return $this->get($url, $args);
	}

	function deleteList($id){
		$url = "/lists/destroy";
		$args = array();
		$args['owner_screen_name'] = $arr[0];
		$args['slug'] = $arr[1];
		return $this->post($url, $args);
	}

	function deleteListMember($id, $memberid){
		$arr = explode("/", $id);
		$url = "/lists/members/destroy_all";
		$args = array();
		$args['slug'] = $arr[1];
		if($memberid)
			$args['id'] = $memberid;
		
		return $this->post($url, $args);
	}

	function editList($prename, $name, $description, $isProtect){
		$url = "/lists/update";
		$args = array();
		if($prename)
			$args['slug'] = $prename;
		if($name)
			$args['name'] = $name;
		if($description)
			$args['description'] = $description;
		if($isProtect)
			$args['mode'] = "private";
		return $this->post($url, $args);
	}

	function followedLists($username = '', $cursor = false){
		$url = "/lists/subscriptions";
		$args = array();
		if($username) 
			$args['user_id'] = $username;
		if($cursor)
			$args['cursor'] = $cursor;
		return $this->get($url, $args);
	}

	function followList($id){
		$url = "/lists/subscribers/create";
		$arr = explode("/", $id);
		$args = array();
		$args['owner_screen_name'] = $arr[0];
		$args['slug'] = $arr[1];
		return $this->post($url, $args);
	}

	function isFollowedList($id){
		$url = "/lists/subscribers/show";
		$arr = explode('/', $id);
		$args = array();
		$args['owner_screen_name'] = $arr[0];
		$args['slug'] = $arr[1];
		$args['user_id'] = $this->username;
		return $this->get($url, $args);
	}

	function listFollowers($id, $cursor = false, $skip_status = 1){
		$url = "/lists/subscribers";
		$arr = explode('/', $id);
		$args = array();
		$args['owner_screen_name'] = $arr[0];
		$args['slug'] = $arr[1];
		if($cursor){
			$args['cursor'] = $cursor;
		}
		$args['skip_status'] = $skip_status;
		return $this->get($url, $args);
	}

	function listInfo($id){
		$arr = explode('/', $id);
		$url = "/lists/show";
		$args = array();
		$args['owner_screen_name'] = $arr[0];
		$args['slug'] = $arr[1];
		return $this->get($url, $args);
	}

	function listMembers($id, $cursor = false, $skip_status = 1){
		$arr = explode("/", $id);
		$url = "/lists/members";
		$args = array();
		$args['owner_screen_name'] = $arr[0];
		$args['slug'] = $arr[1];
		if($cursor){
			$args['cursor'] = $cursor;
		}
		$args['skip_status'] = $skip_status;
		return $this->get($url, $args);

	}

	function listStatus($id, $page = false, $since_id = false,$include_rts = true, $include_entities = true){
		$arr = explode('/', $id);
		$url = '/lists/statuses';
		$args = array();
		$args['slug'] = $arr[1];
		$args['owner_screen_name'] = $arr[0];
		if($page){
			$args['page'] = $page;
		}
		if($since_id){
			$args['since_id'] = $since_id;
		}
		if($include_rts)
			$args['include_rts'] = $include_rts;
		if($include_entities)
			$args['include_entities'] = $include_entities;
		return $this->get($url, $args);
	}

	function unfollowList($id){
		$arr = explode("/", $id);
		$url = "/lists/subscribers/destroy";
		$args = array();
		$args['owner_screen_name'] = $arr[0];
		$args['slug'] = $arr[1];
		return $this->post($url, $args);
	}

	/* ---------- Friendship ---------- */
	function destroyUser($id){
		$url = "/friendships/destroy/$id";
		return $this->delete($url);
	}

	function followers($id = false, $page = false, $skip_status = true){
		$url = 'followers/list';
		if( $id )
			$args['user_id'] = $id;
		$args['cursor'] = $page ? $page : -1;
		$args['skip_status'] = $skip_status;
		return $this->get($url, $args);
	}

	function followUser($id, $notifications = false){
		$url = "/friendships/create/$id";
		$args = array();
		if($notifications)
			$args['follow'] = true;
		return $this->post($url, $args);
	}

	function friends($id = false, $page = false, $skip_status = true){
		$url = '/friends/list';
		$args = array();
		if( $id )
			$args['user_id'] = $id;
		$args['cursor'] = $page ? $page : -1;
		$args['skip_status'] = $skip_status;
		return $this->get($url, $args);
	}

	function isFriend($user_a, $user_b){
		$url = '/friendships/exists';
		$args = array();
		$args['user_a'] = $user_a;
		$args['user_b'] = $user_b;
		return $this->get($url, $args);
	}

	function friendship($source_screen_name,$target_screen_name){
		$url = '/friendships/show';
		$args = array();
		$args['source_screen_name'] = $source_screen_name;
		$args['target_screen_name'] = $target_screen_name;
		return $this->get($url, $args);
 	}
 	
	function relationship($target, $source = false){
		$url = '/friendships/show';
		$args = array();
		$args['target_screen_name'] = $target;
		if($source){
			$args['source_screen_name'] = $source;
		}
		return $this->get($url, $args);
	}

	function showUser($id = false, $email = false, $user_id = false, $screen_name = false,$include_entities = true){
		$url = '/users/show';
		$args = array();
		if($id)
			$args['id'] = $id;
		elseif($screen_name)
			$args['id'] = $screen_name;
		else
			$args['id'] = $this->user_id;

		return $this->get($url, $args);
	}

	/* ---------- Ratelimit ---------- */
	function ratelimit(){
		$url = '/account/rate_limit_status';
		return $this->get($url,array(),false);
	}

	/* ---------- Retweet ---------- */
	function getRetweeters($id, $count = false){
		$url = "/statuses/retweets/$id";
		if($count != false) {
			$url .= "?count=$count";
		}
		return $this->get($url);
	}

	function retweet($id){
		$url = "/statuses/retweet/$id";
		return $this->post($url);
	}

	function retweets($id, $count = 20,$include_entities = true){
		if($count > 100){
			$count = 100;
		}
		$url = "/statuses/retweets/$id";
		$args = array();
		$args['count'] = unt;
		if($include_ities)
			$args['include_entities'] = $include_entities;
		return $this->get($url,$args);
	}

	function retweets_of_me($page = false, $count = false, $since_id = false, $max_id = false,$include_entities = true){
		$url = '/statuses/retweets_of_me';
		$args = array();
		if($since_id)
			$args['since_id'] = $since_id;
		if($max_id)
			$args['max_id'] = $max_id;
		if($count)
			$args['count'] = $count;
		if($page)
			$args['page'] = $page;
		if($include_entities)
			$args['include_entities'] = $include_entities;
		return $this->get($url, $args);
	}

	/* ---------- Search ---------- */
	function search($q = false, $page = false, $rpp = false, $include_entities = true){
		$url = "/search/tweets";
		if(!$q) {
			return false;
		} else {
			$args = array();
			$args['q'] = $q;
		}
		if($page)
			$args['page'] = $page;
		if($rpp)
			$args['rpp'] = $rpp;
		if($include_entities)
			$args['include_entities'] = $include_entities;
		return $this->get($url, $args);
	}

	/* ---------- Spam ---------- */
	function reportSpam($screen_name){
		$url = '/report_spam';
		$args = array();
		$args['screen_name'] = $screen_name;
		return $this->post($url, $args);
	}

	/* ---------- Timeline ---------- */
	function deleteStatus($id){
		$url = "/statuses/destroy/$id";
		return $this->delete($url);
	}

	function homeTimeline($page = false, $since_id = false, $count = false, $include_entities = true) {
		$url = '/statuses/home_timeline';
		$args = array();
		if($page)
			$args['page'] = $page;
		if($since_id)
			$args['since_id'] = $since_id;
		if($count)
			$args['count'] = $count;
		if($include_entities)
			$args['include_entities'] = $include_entities;
		return $this->get($url, $args);
	}
	
	function friendsTimeline($page = false, $since_id = false, $count = false,$include_entities = true){
		$url = '/statuses/friends_timeline';
		$args = array();
		if($page)
			$args['page'] = $page;
		if($since_id)
			$args['since_id'] = $since_id;
		if($count)
			$args['count'] = $count;
		if($include_entities)
			$args['include_entities'] = $include_entities;
		return $this->get($url, $args);
	}

	function getFavorites($page = false,$userid=false,$include_entities = true){
		$url = '/favorites/list';
		$args = array();
		if($userid)
			$args['user_id'] = $userid;
		if($page)
			$args['page'] = $page;
		if($include_entities)
			$args['include_entities'] = $include_entities;
		return $this->get($url, $args);
	}

	function makeFavorite($id){
		$url = "/favorites/create/$id";
		return $this->post($url);
	}

	function publicTimeline($sinceid = false,$include_entities = true){
		$url = '/statuses/public_timeline';
		$args = array();
		if($sinceid){
			$args['since_id'] = $sinceid;
		}
		if($include_entities)
			$args['include_entities'] = $include_entities;
		return $this->get($url, $args);
	}

	function removeFavorite($id){
		$url = "/favorites/destroy/$id";
		return $this->post($url);
	}

	function replies($page = false, $since_id = false,$include_entities = true){
		$url = '/statuses/mentions_timeline';
		$args = array();
		if($page)
			$args['page'] = (int) $page;
		if($since_id)
			$args['since_id'] = $since_id;
		if($include_entities)
			$args['include_entities'] = $include_entities;
		return $this->get($url, $args);
	}

	function showStatus($id,$include_entities = true){
		$url = "/statuses/show/$id";
		$args = array();
		if($include_entities)
			$args['include_entities'] = $include_entities;
		return $this->get($url,$args);
	}

	function update($status, $replying_to = false,$include_entities = true){
		try{
			$url = '/statuses/update';
			$args = array();
			$args['status'] = $status;
			if($replying_to)
				$args['in_reply_to_status_id'] = $replying_to;
			if($include_entities)
				$args['include_entities'] = $include_entities;
			return $this->post($url, $args);
		}catch(Exception $ex){
			echo $ex->getLine." : ".$ex->getMessage();
		}
	}

	function userTimeline($page = false, $id = false, $count = false, $since_id = false, $include_rts = true, $include_entities = true){
		$url = '/statuses/user_timeline';
		$args = array();
		if($page)
			$args['page'] = $page;
		if($id)
			$args['id'] = $id;
		if($count)
			$args['count'] = $count;
		if($since_id)
			$args['since_id'] = $since_id;
		if($include_rts)
			$args['include_rts'] = $include_rts;
		if($include_entities)
			$args['include_entities'] = $include_entities;
		$response = $this->get($url, $args);
		return $response;
	}

	function trends_closest($lat = false, $long=false) {
		$url = "/trends/closest";
		$args = array();
		if ($lat)
			$args['lat'] = $lat;
		if ($long)
			$args['long'] = $long;
		return $this->get($url, $args);
	}
	
	function trends_place($id = 1) {
		$url = "/trends/place";
		$args = array();
		if ($id)
			$args['id'] = $id;
		return $this->get($url, $args);
	}
	/* ---------- Misc. ---------- */
	function twitterAvailable(){
		$url = "/help/test";
		if($this->get($url) == 'ok'){
			return true;
		}
		return false;
	}

	function veverify($skip_status = false){
		$url = '/account/verify_credentials';
		$args = array('skip_status' => $skip_status);
		return $this->get($url,$args);
	}
	
	function updateProfile($fields = array(), $skip_status = true){
		$url = '/account/update_profile';
		$args = array();
		foreach( $fields as $pk => $pv ){
			switch( $pk ){
			case 'name' :
				$args[$pk] = (string) substr( $pv, 0, 20 );
				break;
			case 'url' :
				$args[$pk] = (string) substr( $pv, 0, 100 );
				break;
			case 'location' :
				$args[$pk] = (string) substr( $pv, 0, 30 );
				break;
			case 'description' :
				$args[$pk] = (string) substr( $pv, 0, 160 );
				break;
			default :
				break;
			}
			$args['skip_status'] = $skip_status;
		}
		return $this->post($url, $args);
	}
	
	/* media */
	function updateProfileImage($image, $skip_status=true) {
		$url = '/account/update_profile_image';
		$mul = array();
		if($image){
			$mul['image']=$image;
		}
		if($skip_status) {
			$args['skip_status']=$skip_status;
		}
		return $this->post($url, $args, $mul);
	}
	
	function updateProfileBackground($image, $skip_status=true) {
		$url = '/account/update_profile_background_image';
		$mul = array();
		if($image){
			$mul['image']=$image;
			$mul['skip_status']=$skip_status;
		}
		return $this->post($url, NULL, $mul);
	}
	
	function updateMedia($status,$image,$replying_to = false) {
		$url = 'https://upload.twitter.com/1/statuses/update_with_media'.$this->type;
		$args = array();
		if($status) $args['status'] = $status;
		if($replying_to) $args['in_reply_to_status_id'] = $replying_to;
		$mul = array();
		if($image) $mul['media'][] = $image;
		return $this->post($url,$args,$mul);
	}
}

