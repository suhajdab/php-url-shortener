<?php

require('inc/config.php');

function redirect($url) {
	header('Location: ' . $url, null, 301);
	die();
}

if (isset($_GET['slug'])) {
	$slug = rtrim($_GET['slug'], '!"#$%&\'()*+,-./@:;<=>[\\]^_`{|}~');
	if (is_numeric($slug) && strlen($slug) > 3) {
		redirect('http://twitter.com/' . TWITTER_USERNAME . '/status' . $_SERVER['REQUEST_URI']);
	}
	$db = new mysqli(MYSQLI_HOST, MYSQLI_USER, MYSQLI_PASSWORD, MYSQLI_DATABASE);
	$db->query('SET NAMES "utf8"');
	$slug = $db->real_escape_string($slug);
	$result = $db->query('SELECT * FROM `redirect` WHERE `slug` = "' . $slug . '"');
	if ($result && $result->num_rows > 0 ) {
		$result = $result->fetch_object();
		//$db->query('UPDATE `redirect` SET `hits` = `hits` + 1 WHERE `slug` = "' . $db->real_escape_string($slug) . '"');
		$request_headers = json_encode( get_my_headers() );
		$db->query('INSERT INTO `hits` (`id`, `request_headers`) VALUES ("' . $result->id . '", "' . $db->real_escape_string( $request_headers ) . '")');
		redirect($result->url);
	} else {
		redirect(DEFAULT_URL . $_SERVER['REQUEST_URI']);
	}
} else {
	redirect(DEFAULT_URL . '/');
}

?>