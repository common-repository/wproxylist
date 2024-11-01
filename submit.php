<?php



	require_once('../../../wp-blog-header.php');
	require_once('functions.php');

	global $wpdb;

	

	// Get the requested URL

	$url = $_REQUEST['url'];

    $url = str_replace(array("http://", "https://", "www."), "", $url);

	$url = strip_tags(mysql_escape_string($url));
	
	// Check that the URL is not currently available
	$table_name = $wpdb -> prefix . "proxies";
	$proxies    = $wpdb -> get_results("select * from $table_name where url = '$url'");
	
	if(empty($proxies) || count($proxies) == 0) {
		add_proxy($url, "pending");
		print 'Proxy submitted successfully. Submit another?<br /><input type="text" name="proxy_url" id="proxy_url" size="60">';
	}
	else {
		print 'This URL is already in our database.<br /><input type="text" name="proxy_url" id="proxy_url" size="60">';
	}
?>
