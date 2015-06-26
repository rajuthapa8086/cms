<?php

class Request {

	private function __construct() {}

	public static function Get($key) {
		if (isset($_GET[$key])) {
			return $_GET[$key];
		}
		return NULL;
	}

	public static function POST($key) {
		if (isset($_POST[$key])) {
			return $_POST[$key];
		}
		return NULL;
	}
}