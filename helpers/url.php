<?php

function site_url($url) {
	$explode = explode('/', $_SERVER['PHP_SELF']);
	unset($explode[count($explode) -1]);
	return implode('/', $explode) . '/' . $url;
}

function base_url() {
	$explode = explode('/', $_SERVER['PHP_SELF']);
	unset($explode[count($explode) -1]);
	return implode('/', $explode) . '/';
}

function get_slug() {
	return str_replace(base_url(), '', $_SERVER['REQUEST_URI']);
}