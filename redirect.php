<?php

	ob_start();

	require_once('../../../wp-blog-header.php');
	global $wpdb;
	
	$url = mysql_escape_string(base64_decode($_GET['url']));
	$table_name = $wpdb -> prefix . "proxies";
	$wpdb->query($wpdb->prepare("update $table_name set hits = hits + 1, time = NOW() where url = '$url'"));
	header("Location: http://" . $url);
?>