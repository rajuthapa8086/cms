<?php

class Session {

	private function __construct() {}

	public static function Start() {
		if (!isset($_SESSION)) {
			session_start();
		}
	}

	public static function Exists($key) {
		self::Start();
		return array_key_exists($key, $_SESSION);
	}

	public static function Set($key, $value) {
		self::Start();
		self::Destroy($key);
		$_SESSION[$key] = $value;
	}

	public static function Get($key) {
		self::Start();
		if (self::Exists($key)) {
			return $_SESSION[$key];
		}
		return NULL;
	}

	public static function GetAll() {
		self::Start();
		return $_SESSION;
	}

	public static function Destroy($key) {
		self::Start();
		if (self::Exists($key)) {
			$_SESSION[$key] = NULL;
			unset($_SESSION[$key]);
		}
	}

	public static function DestroyAll() {
		self::Start();
		session_destroy();
	}
}