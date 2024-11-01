<?php

wp_enqueue_script("jquery");

$form_html = '';

$form_html .= '<script type="text/javascript" src="' . $plugin_directory . 'submit.js"></script>';

$form_html .= '<b id="proxy_page_title">' . $title . '</b>';

$form_html .= '<link href="' . $plugin_directory . 'proxy_style.css" rel="stylesheet" type="text/css">';

$form_html .= '<div id="proxy_list">';



foreach($proxies as $proxy) {
	
	$class ="proxy_item";
	
	if($proxy -> feature == "highlight") {
		$class = "proxy_item_highlight";
	}
	
	if($proxy -> feature == "featured") {
		$class = "proxy_item_featured";
	}
	
	if($proxy -> feature == "second") {
		$class = "proxy_item_second";
	}

	$form_html .= '<a href="' . $plugin_directory . 'redirect.php?url=' . base64_encode($proxy -> url) . '" target="_blank" class="' . $class . '"><img src="' . $plugin_directory . '/flags/' . strtolower($proxy -> country) . '.gif" />&nbsp;&nbsp;&nbsp;&nbsp;' . $proxy -> url . '  (' . $proxy -> hits . ' hits)</a>';

}



$form_html .= '</div>';

$form_html .= '<form action="' . $plugin_directory . 'submit.php" name="submit_new_proxy" id="submit_new_proxy" method="post">';

$form_html .= '<div id="submit_proxy_form">';

$form_html .= 'Submit A New Proxy<br />';

$form_html .= '<input type="text" name="proxy_url" id="proxy_url" size="60"></div>';

$form_html .= '<input type="submit" id="submit_proxy" value="Submit Proxy">';

$form_html .= '</form><br />';

$form_html .= '<script>jQuery.noConflict();</script> ';



?>