<?php

class Util {

	private function __construct() {}

	public static $template_path;

	public static function Render($template, $data = array()) {
		$template = self::ConvertPath(self::$template_path . $template);
		if (file_exists($template)) {
			ob_start();
			extract($data);
			require_once $template;
			$content = ob_get_contents();
			ob_clean();
			return $content;
		}
	}

	public static function ConvertPath($file) {
		return str_replace('/', DS, $file);
	}

}