<?php
	function setEncryptCookie($key, $value, $time = 0, $path = '/') {
		if (trim(SECURE_KEY) == '') {
			setcookie($key, $value, $time, $path);
		} else {
			setcookie($key, encrypt($value), $time, $path);
		}
	}

	function getEncryptCookie($key) {
		if ( isset($_COOKIE[$key]) ) {
			if (trim(SECURE_KEY) == '') {
				return $_COOKIE[$key];
			} else {
				return decrypt($_COOKIE[$key]);
			}
		} else { 
			return null;
		}
	}

	function getCookie($key) {
		if ( isset($_COOKIE[$key]) ) 
			return $_COOKIE[$key];
		else 
			return null;
	}

	function delCookie($key) {
		setcookie($key, '', $_SERVER['REQUEST_TIME']-300, '/');
	}

	function encrypt($plain_text) {
		if ( !function_exists('mcrypt_module_open') ) {
			return EDencrypt($plain_text, SECURE_KEY);
		}
		$td = mcrypt_module_open('blowfish', '', 'cfb', '');
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		mcrypt_generic_init($td, SECURE_KEY, $iv);
		$crypt_text = mcrypt_generic($td, $plain_text);
		mcrypt_generic_deinit($td);
		return base64_encode($iv.$crypt_text);
	}

	function decrypt($crypt_text) {
		if ( !function_exists('mcrypt_module_open') ) {
			return EDdecrypt($crypt_text, SECURE_KEY);
		}
		$crypt_text = base64_decode($crypt_text);
		$td = mcrypt_module_open('blowfish', '', 'cfb', '');
		$ivsize = mcrypt_enc_get_iv_size($td);
		$iv = substr($crypt_text, 0, $ivsize);
		$crypt_text = substr($crypt_text, $ivsize);
		mcrypt_generic_init($td, SECURE_KEY, $iv);
		$plain_text = mdecrypt_generic($td, $crypt_text);
		mcrypt_generic_deinit($td);

		return $plain_text;
	}

	if ( !function_exists('mb_strlen') ) {
		function mb_strlen($text, $encode) {
			if (strtolower($encode) == 'utf-8') {
				return preg_match_all('%(?:
					[\x09\x0A\x0D\x20-\x7E]     # ASCII
					| [\xC2-\xDF][\x80-\xBF]# non-overlong 2-byte
					|  \xE0[\xA0-\xBF][\x80-\xBF] # excluding overlongs
					| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2} # straight 3-byte
					|  \xED[\x80-\x9F][\x80-\xBF] # excluding surrogates
					|  \xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
					| [\xF1-\xF3][\x80-\xBF]{3}   # planes 4-15
					|  \xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
				)%xs',$text,$out);
			}else{
				return strlen($text);
			}
		}
	}
	
	function keyED($txt,$encrypt_key) {

		$encrypt_key = md5($encrypt_key);
		$ctr=0;
		$tmp = "";

		for ($i=0;$i<strlen($txt);$i++) {
			if ($ctr==strlen($encrypt_key)) $ctr=0;
			$tmp.= substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1);
			$ctr++;
		}

		return $tmp;
	}

	function EDencrypt($txt,$key) {

		srand((double)microtime()*1000000);
		$encrypt_key = md5(rand(0,32000));
		$ctr=0;
		$tmp = "";

		for ($i=0;$i<strlen($txt);$i++) {
			if ($ctr==strlen($encrypt_key)) $ctr=0;
			$tmp.= substr($encrypt_key,$ctr,1) . (substr($txt,$i,1) ^ substr($encrypt_key,$ctr,1));
			$ctr++;
		}

		return keyED($tmp,$key);
	}

	function EDdecrypt($txt,$key) {

		$txt = keyED($txt,$key);
		$tmp = "";

		for ($i=0;$i<strlen($txt);$i++) {
			$md5 = substr($txt,$i,1);
			$i++;
			$tmp.= (substr($txt,$i,1) ^ $md5);
		}

		return $tmp;

	}
?>
