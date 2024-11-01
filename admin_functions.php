<?php

function wproxy_menu() {	  

		add_menu_page('Page title', 'WProxy', 'administrator', 'wproxy-top-level', 'wproxy_main_menu');

		add_submenu_page( 'wproxy-top-level', 'Add New Proxies', 'Add Proxies', 'administrator', 'wproxy_add_proxy', 'add_proxy_menu');

		add_submenu_page( 'wproxy-top-level', 'Manage Existing Proxies', 'Manage Proxies', 'administrator', 'wproxy_manage_proxy', 'manage_proxy_menu');

		add_submenu_page( 'wproxy-top-level', 'Approve User Submitted Proxies', 'Awaiting Approval', 'administrator', 'wproxy_approve_proxy', 'approve_proxy_menu');

	}

	

	function wproxy_main_menu() {

		global $wpdb;

		

		// Get the required information from the database

		$table_name = $wpdb -> prefix . "proxies";

		$proxies    = $wpdb -> get_results("select * from $table_name where accepted = '0'");

		$awaiting_approval = count($proxies);

		$proxies    = $wpdb -> get_results("select * from $table_name where accepted = '1'");

		$current_proxies = count($proxies);

		

		// Setup the menu here

		print '<h2 style="font-size: 20px; color: #444; font-style: italic; font-family: Times New Roman;">WProxy Management</h2>';

		print 'Please make your selection below to manage your proxy list!<br /><br />';

		

		if($awaiting_approval > 0) {

			print '<b><a href="?page=wproxy_approve_proxy">Awaiting Approval</a></b> ';

		}

		else {

			print '<a href="?page=wproxy_approve_proxy">Awaiting Approval</a> ';

		}

		

		print '<b style="font-weight: normal; color: #aaa;">(' . $awaiting_approval . ')</b> | '; 

		print '<a href="?page=wproxy_manage_proxy">Manage Proxies</a> <b style="font-weight: normal; color: #aaa;">(' . $current_proxies . ')</b> | <a href="?page=wproxy_add_proxy">Add Proxies</a>';

		print '<br /><br />';
		
		
		print '<p style="color: green; font-size: 13px;">';
		
		print '<p align="center"><b>*** To purchase the full version of this plugin, and receive <u>additional</u> functionality (Newest Proxies, Most Visited, Widgets, Paid Listings) please visit 
<a href="http://wproxylist.com/">WProxyList.com</a> *** offering Single Domain, Unlimited Domains and Developer Licensing.</b></p>';
		
		print '</p>';

		

		print '<b>How to use WProxy</b><br />';

		print '<p>Using the WProxy plugin to setup a simple proxy list is very easy. Create a new post or page, switch to HTML mode and enter one of the following: ';

		print '<i>[list_proxies]</i><br /><br />';

		print 'The function of each of the shortcode tags above is as follows: <br />';

		print '<ul>';

		print '<li style="list-style: circle; margin-left: 35px;"><i>[list_proxies]</i> - List all proxies in the database</li>';

		print '</ul>';

		print 'You can even specify how many proxies you would like to show by appending: <i>num="NUMBER_TO_SHOW"</i> to the shortcode shown above. For example, to show ';

		print 'the 25 recently visted proxies, you would enter: <i>[list_proxies num="25"]</i>';

		print '</p>';

	}

	

	function add_proxy_menu() {

		if($_POST['submitting_form'] == 'Y') {

			$proxies = preg_split("/\r\n/", $_POST['proxies']);

			

			$invalid = array();

			$count   = 0;			

			

			// Loop through each URL and add it to the database

			foreach($proxies as $proxy) {

				

				if(strlen($proxy) > 3 && preg_match("#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie", $proxy)) {

					// It's a valid URL, add it to the database

					add_proxy($proxy, "accepted");

					$count++;

				}

				

				// Otherwise, add it to the list of invalid proxies

				else {

					array_push($invalid, $proxy);

				}

			}

			

			if($count > 0)

				print '<div style="text-align: center; background: #6C9; line-height: 25px; font-size: 11px; border-bottom: 1px solid #bbb;">Added <b>' . $count . '</b> proxies successfully!</div>';

				

			if(count($invalid) > 0)	{

				$error = '<div style="text-align: center; background: #EEAAA7; line-height: 25px; font-size: 11px; border-bottom: 1px solid #bbb;">The following proxies could not be added, please

						verify that the URLs are valid: ';

						

				for($i = 0; $i < count($invalid) - 1; $i++) {

					$error .= '<i>' . $invalid[$i] . "</i>, ";

				}

				

				$error .= '<i>' . $invalid[count($invalid) - 1] . '</i>';

						

				$error .= '</div>';

				

				print $error;

			}

		}

		

		print '<h2 style="font-size: 20px; color: #444; font-style: italic; font-family: Times New Roman;">Add New Proxies</h2>';

		print 'Enter your proxies in the text area below. You can add one proxy at a time, or add proxies in bulk by entering proxy URLs on multiple lines<br /><br />';

		print '<u>NOTE:</u> Enter your URLs with http:// or https:// to ensure that they are processed correctly.<br /><br />';

		print '<form name="form1" method="post" action="">';

		print '<input type="hidden" name="submitting_form" value="Y">';

		print '<textarea style="width: 500px; height: 400px;" name="proxies"></textarea><br />';

		print '<input type="submit" value="Submit Proxies" />';

		print '</form>';

	}

	

	function manage_proxy_menu() {

		global $wpdb;
		
		$plugin_directory = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
		print '<script src="' . $plugin_directory . 'feature.js"></script>';

		if(!empty($_GET['id'])) {

			remove_proxy($_GET['id']);

			print '<div style="text-align: center; background: #6C9; line-height: 25px; font-size: 11px; border-bottom: 1px solid #bbb;">Removed proxy successfully!</div>';

		}

		

		print '<h2 style="font-size: 20px; color: #444; font-style: italic; font-family: Times New Roman;">Manage Existing Proxies</h2>';

		print 'Click on the URL for a proxy below to <b>remove</b> it from your toplist<br /><br />';

		

		$table_name = $wpdb -> prefix . "proxies";

		$proxies    = $wpdb -> get_results("select * from $table_name where accepted = '1'");

		

		print '<div style="border-radius: 5px; -moz-border-radius: 5px; width: 95%; height: 500px; overflow: scroll; overflow-x: hidden; margin: 10px; padding: 5px; border: 1px solid #ccc;">';

		

		$count = 0;

		$plugin_directory = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));

		foreach($proxies as $proxy) {
			
			if($proxy -> feature == 'highlight') {
				$highlight = 'checked="checked "';
			}
			
			if($proxy -> feature == 'featured') {
				$featured = 'checked="checked "';
			}
			
			if($proxy -> feature == 'second') {
				$second = 'checked="checked "';
			}
			
			if($proxy -> feature == 'none' || empty($proxy -> feature)) {
				$none = 'checked="checked "';
			}

			$color = "#ddd";
			
			print '<div class="proxy_url">';
			print '		<a href="admin.php?id=' . $proxy -> id . '&page=wproxy_manage_proxy" style="display: block; padding: 5px; background: ' . $color . ';">' . $proxy -> url . '</a>';
			print '</div>';
			print '<br style="clear: both;" />';

		}

		

		print '</div>';

	}

	

	function approve_proxy_menu() {

		// We are attempting to approve a proxy

		if(!empty($_GET['approve'])) {

			approve_proxy($_GET['approve']);

			print '<div style="text-align: center; background: #6C9; line-height: 25px; font-size: 11px; border-bottom: 1px solid #bbb;"><u>Approved</u> proxy successfully!</div>';

		}

		

		// We are attempting to delete the proxy from the toplist

		if(!empty($_GET['delete'])) {

			remove_proxy($_GET['delete']);

			print '<div style="text-align: center; background: #6C9; line-height: 25px; font-size: 11px; border-bottom: 1px solid #bbb;"><u>Removed</u> proxy successfully!</div>';

		}

		

		global $wpdb;

		print '<h2 style="font-size: 20px; color: #444; font-style: italic; font-family: Times New Roman;">Approve User Submitted Proxies</h2>';

		print 'Click "Approve" beside the proxy name to accept it. Click "Remove" to ignore the URL, and delete it from your database.<br /><br />';

		

		$table_name = $wpdb -> prefix . "proxies";

		$proxies    = $wpdb -> get_results("select * from $table_name where accepted = '0'");

		

		print '<div style="border-radius: 5px; -moz-border-radius: 5px; width: 95%; height: 500px; overflow: scroll; overflow-x: hidden; margin: 10px; padding: 5px; border: 1px solid #ccc;">';

		

		$count = 0;

		

		foreach($proxies as $proxy) {

			$count ++;

			$color = "#fff";

			

			if($count % 2 == 0)

				$color = "#ddd";

			

			print '<div style="display: block; background: ' . $color . '; padding: 5px;">';

			print '<a href="admin.php?approve=' . $proxy -> id . '&page=wproxy_approve_proxy">[Approve]</a> ';

			print '<a href="admin.php?delete=' . $proxy -> id . '&page=wproxy_approve_proxy" style="color: #900">[Delete]</a> - ';

			print $proxy -> url . '</div>';

		}

		

		print '</div>';

	}
	
?>