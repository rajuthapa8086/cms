<?php

class Response {

	public function __construct() {}

	public static function Redirect($url) {
		header("Location: " . $url);
		exit;
	}

	public static function SetHeader($key, $value) {
		header(sprintf("%s: %s", $key, $value));
	}
}