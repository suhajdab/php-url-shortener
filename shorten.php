<?php

require('inc/config.php');

header('Content-Type: text/plain;charset=UTF-8');

$url = isset($_GET['url']) ? urldecode(trim($_GET['url'])) : '';
//	bookmarklet mode
$bm = isset($_GET['bm']);

if (in_array($url, array('', 'about:blank', 'undefined', 'http://localhost/'))) {
	die('Enter a URL.');
}

$db = new mysqli(MYSQLI_HOST, MYSQLI_USER, MYSQLI_PASSWORD, MYSQLI_DATABASE);
$db->query('SET NAMES "utf8"');

$url = $db->real_escape_string($url);

function generate_slug( $db, $l = 6 ) {
	$v = 'aeiou';
	$c = 'bcdfghjklmnprstv';
	$w = '';
  
  do {
		$max = 5;
		for($i = 0; $i < $l; $i++) {
			$set = ( rand( 1, $max ) < 3 ) ? $v : $c;
			if ( $set == $c ) { 
				$max = $max * 0.75; 
			} else { 
				$max = $max * 1.5; 
			}
			$w .= substr( $set, rand( 0, strlen($set) - 1 ), 1 );
		}
		$result = $db->query('SELECT `slug` FROM `redirect` WHERE `slug` = "' . $w . '"');
	} while ( $result && $result->num_rows > 0 );
  
	return $w;
}

function respond( $res_obj ) {
	global $bm;
	$result = array (
		"hits" => $res_obj->hits,
		"shortUrl" => SHORT_URL . $res_obj->slug,
		"added" => $res_obj->date
	);
	$json = json_encode($result);
	
	//	return bookmarklet dialog or simple json
	if ( $bm ) {
		/*
			Bookmarklet code
			javascript:function one_s(){var d=document,z=d.createElement('scr'+'ipt'),b=d.body,l=d.location;try{if(!b)throw(0);z.setAttribute('src',l.protocol+'//1rsn.com/shorten.php?url='+encodeURIComponent(l.href)+'&bm=true');b.appendChild(z);}catch(e){alert('Please wait until the page has loaded.');}}one_s();void(0);
		*/
		header('Content-type: application/javascript');
		echo "( function ( d ) {\n";
		echo "var result = " . $json . ";\n";
		//	create all elements for dialog
		echo "var e = d.createElement('div'), i = d.createElement('input'); p = d.createElement('p'); b = d.createElement('button'); l = document.createElement('link');\n";
		//	link ( stylesheet )
		echo "l.rel = 'stylesheet'; l.href='http://1rsn.com/css/bookmarklet.css';\n";
		//	close button
		echo "b.id = 'onersn_bkmrklt_button';\n";
		echo "b.innerHTML = 'x'; b.onclick = function () { this.parentNode.parentNode.removeChild(e); };\n";
		//	input with shortened url
		echo "i.value = result.shortUrl;\n";
		echo "i.id = 'onersn_bkmrklt_input';\n";
		//	p tag with stats
		echo "p.innerHTML = 'hits: ' + result.hits + ', added: ' + result.added + '<br/>shortened by <img src=\"http://onereason.eu/images/onereason-logo.png\" height=12 />';\n";
		echo "p.id ='onersn_bkmrklt_p';\n";
		//	dialog
		echo "e.id = 'onersn_bkmrklt_dialog';\n";
		echo "e.appendChild(l); e.appendChild(i); e.appendChild(p); e.appendChild(b); d.body.appendChild(e); i.select();\n";
		echo "})( document );";
	} else {
		header('Content-type: application/json');
		echo $json;
	}
}

$result = $db->query('SELECT * FROM `redirect` WHERE `url` = "' . $url . '" LIMIT 1');
if ($result && $result->num_rows > 0) { // If there’s already a short URL for this URL
	respond($result->fetch_object());
} else {
	$slug = generate_slug( $db );
	if ($db->query('INSERT INTO `redirect` (`slug`, `url`, `date`, `hits`) VALUES ("' . $slug . '", "' . $url . '", NOW(), 0)')) {
		$result = $db->query('SELECT * FROM `redirect` WHERE `slug` = "' . $slug . '" LIMIT 1');
		respond($result->fetch_object());
		$db->query('OPTIMIZE TABLE `redirect`');
	}
}

?>