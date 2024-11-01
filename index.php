<?php



	/// UPDATED FRIDAY 26th, 2010 - 11:40 AM
	// LOUIS LANG
//Redirect added 7/17/2010



	##########################################################################################


	/*



		Plugin Name: WProxy List Free

		Description: This plugin allows you to easily setup a proxy list using Wordpress.

		Version: 1.0.1

		Author: S Dawkins (UPGRADE)

		Author URI: http://www.wproxylist.com



	*/



	##########################################################################################



	

	wp_enqueue_script('jquery');





	// Activates the installation by creating a custom table in the WP installation







	require_once("activate.php");


	// Initiate the install when the plugin is activated





	register_activation_hook(__FILE__,'wproxy_activate');






	// Remove the plugin table on deactivation



	register_deactivation_hook( __FILE__, 'wproxy_deactivate' );



	// All functions for this plugin can be found in the following file




	require_once("functions.php");


	// Setup the administration panel


	add_action('admin_menu', 'wproxy_menu');


	// Require the admin functions



	require_once("admin_functions.php");



?>
