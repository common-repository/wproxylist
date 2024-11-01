<?php



	/////////////////////////////////////////////////////////////////////

	///				          IMPORTANT NOTE						  ///

	/////////////////////////////////////////////////////////////////////

	/*

		 It is not necessary to run this file alone. Simply activate this

		 plugin from the administration panel of your Wordpress installation

		 and the installation will be handled for you.

	 */

	

	// Sets up a new table for this plugin once it is activated

	function wproxy_activate() {

		global $wpdb;

		

		$table_name = $wpdb -> prefix . "proxies";

		

		// Ensure that the table doesn't already exist in the database

		if($wpdb -> get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {

			// Build or update the table as necessary

			$sql = "CREATE TABLE IF NOT EXISTS `" . $table_name . "` (
				  `id` mediumint(9) NOT NULL auto_increment,
				  `url` varchar(55) NOT NULL,
				  `hits` int(11) NOT NULL,
				  `time` timestamp NOT NULL default '0000-00-00 00:00:00',
				  `country` tinytext NOT NULL,
                  `feature` varchar(55) NOT NULL,
				  `accepted` tinyint(1) NOT NULL,
				  UNIQUE KEY `id` (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;";

				

				require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

				dbDelta($sql);
		}

	}

	

	// Removes the table from the database when the plugin is deactivated

	function wproxy_deactivate() {

		global $wpdb;		

		$table_name = $wpdb -> prefix . "proxies";

		$wpdb -> get_var("DROP TABLE IF EXISTS $table_name;");

	}



?>
