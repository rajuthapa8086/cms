<?php

function get_site_name() {
	global $db_config;
	$db = new Db($db_config);
	$settings = $db->row("SELECT * FROM `settings`");
	return $settings['site_name'];
}