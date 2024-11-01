<?php



	// Builds the form for the proxy list

	function build_form($proxies, $title) {

		$plugin_directory = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 

		

		include("proxy_list.php");

					 

		return $form_html;

	}


	// List proxies

	function proxy_list($atts) {

		global $wpdb;

		

		// Defaults for the shortcode

		extract(shortcode_atts(

				array('num' => 100,
					  'title' => 'WProxy List Plugin'), $atts

		));
		
		if(empty($atts['title']))
			$atts['title'] = 'WProxy List Plugin';

		// Get the information from the database

		$table_name = $wpdb -> prefix . "proxies";
		
		$proxies_feature = $wpdb -> get_results("select * from $table_name where accepted = '1' and feature = 'featured' order by RAND()");
		
		$proxies_second   = $wpdb -> get_results("select * from $table_name where accepted = '1' and feature = 'second' order by RAND()");

		$proxies_basic    = $wpdb -> get_results("select * from $table_name where accepted = '1' and feature != 'featured' and feature != 'second' order by RAND() desc limit {$num}");
		
		$proxies = array_merge($proxies_feature, $proxies_second, $proxies_basic);
		return build_form($proxies, $atts['title']);

	}
	

	// Add the previous functions to the Shortcode API calls
	add_shortcode('list_proxies', 'proxy_list');

	

	// Adds a proxy to the database

	function add_proxy($url, $accepted) {

		global $wpdb;

		

		

		$replacements = array("http://", "https://", "www.");

		$url = str_replace($replacements, "", $url);

		$url = mysql_escape_string($url);

		

		// Figure out the country that this proxy is hosted in

		$IP = gethostbyname($url);

		$country_file = file_get_contents('http://ipinfodb.com/ip_query.php?ip='.$IP);

		

		$country = preg_match("/<CountryCode>.*<\/CountryCode>/", $country_file, $matches);

		$country = $matches[0];

		

		$replacements = array("<CountryCode>", "</CountryCode>");

		$country = str_replace($replacements, "", $country);

		

		if(empty($country) || $country == "XX")

			$country = "US";

		

		$table_name = $wpdb -> prefix . "proxies";

		if($accepted == "accepted")

			$wpdb -> insert($table_name, array( 'url' => $url, 'country' => $country, 'accepted' => 1, 'feature' => 'none'), array( '%s', '%s', '%d', '%s'));

		

		else

			$wpdb -> insert($table_name, array( 'url' => $url, 'country' => $country, 'feature' => 'none'), array( '%s', '%s', '%s'));

	}

	

	// Removes a proxy from the database

	function remove_proxy($id) {

		global $wpdb;

		$id = mysql_escape_string($id);

		$table_name = $wpdb -> prefix . "proxies";

		$wpdb -> query("DELETE FROM $table_name where id = '$id' limit 1");

	}

	

	// Approves a proxy in the database

	function approve_proxy($id) {

		global $wpdb;

		$id = mysql_escape_string($id);

		$table_name = $wpdb -> prefix . "proxies";

		$wpdb->query("UPDATE $table_name set accepted = '1' where id ='$id'");

	}



?>
