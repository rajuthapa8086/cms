<?php
define('DS', DIRECTORY_SEPARATOR);
define('ADMIN_DIR', dirname(__FILE__) . DS);
define('BASE_DIR', dirname(__FILE__) . DS  .'..' . DS);
define('ADMIN_ROOT', '');
define('SITE_ROOT', '../');
require_once BASE_DIR . 'Libs' . DS . 'autoload.php';
require_once BASE_DIR . 'configs' .  DS . 'incs.php';
require_once BASE_DIR . 'helpers' .  DS . 'incs.php';
require_once ADMIN_DIR . 'incs' .  DS . 'incs.php';

//-------------------------------------------------------

Util::$template_path = ADMIN_DIR . 'templates' . DS; //class defining

//-------------------------------------------------------


$db = new Db($db_config);

$sql_users = "SELECT * FROM `users` ORDER BY `id` LIMIT 10";
$sql_pages = "SELECT * FROM `pages` ORDER BY `id` LIMIT 10";


//-------------------------------------------------------

echo Util::Render('master.phtml', array(
	'page_title'	=>	'Admin Home',
	'content'		=>	Util::Render('index.phtml', array(
		'users'	=>	$db->rows($sql_users),
		'pages'	=>	$db->rows($sql_pages),
	))
));