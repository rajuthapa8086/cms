<?php

function autoloadLibs($class) {
	$classPath = BASE_DIR . 'Libs' . DS . $class . '.php';
	if (file_exists($classPath)) {
		require_once $classPath;
	}
}

spl_autoload_register('autoloadLibs');