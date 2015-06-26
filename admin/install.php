<?php
define('DS', DIRECTORY_SEPARATOR);
define('BASE_DIR', dirname(__FILE__) . DS  .'..' . DS);
require_once BASE_DIR . 'Libs' . DS . 'autoload.php';
require_once BASE_DIR . 'configs' .  DS . 'incs.php';
require_once BASE_DIR . 'dev' . DS . 'schemas' . DS . 'incs.php';
require_once BASE_DIR . 'dev' . DS . 'seeds' . DS . 'incs.php';

CreateSettingsTable();
