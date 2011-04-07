<?php

define('MYSQLI_HOST', 'internal-db.s85776.gridserver.com');
define('MYSQLI_USER', 'db85776_urlshort');
define('MYSQLI_PASSWORD', 'urlshortvic2!');
define('MYSQLI_DATABASE', 'db85776_urlshortener');
define('TWITTER_USERNAME', 'suhajdab');
define('SHORT_URL', 'http://1r.nu/'); // include the trailing slash!
define('DEFAULT_URL', 'http://onereason.eu'); // omit the trailing slash!

function get_my_headers() { 
	$myheaders = array('HTTP_USER_AGENT','HTTP_ACCEPT_LANGUAGE','REMOTE_ADDR','HTTP_CLIENT_IP');
	foreach($_SERVER as $key=>$value) { 
		if (in_array($key, $myheaders)) { 
  		//$key=str_replace(" ","-",ucwords(strtolower(str_replace("_"," ",substr($key,5))))); 
  		$out[$key]=$value; 
		}
	} 
	return $out; 
}
?>