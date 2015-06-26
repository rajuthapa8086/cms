<?php
define('DS', DIRECTORY_SEPARATOR);
define('ADMIN_DIR', dirname(__FILE__) . DS . '..' . DS);
define('BASE_DIR', dirname(__FILE__) . DS  . '..' . DS . '..' . DS);
define('ADMIN_ROOT', '../');
define('SITE_ROOT', '../../');
require_once BASE_DIR . 'Libs' . DS . 'autoload.php';
require_once BASE_DIR . 'configs' .  DS . 'incs.php';
require_once BASE_DIR . 'helpers' .  DS . 'incs.php';
require_once ADMIN_DIR . 'incs' .  DS . 'incs.php';

//-------------------------------------------------------

Util::$template_path = ADMIN_DIR . 'templates' . DS;

//-------------------------------------------------------

$id = Request::Get('id');

if (is_null($id)) {
	Response::Redirect('index.php');
}

$db = new Db($db_config);
$sql_user = "SELECT * FROM `users` WHERE `id` = %d";
$sql_user = sprintf(
	$sql_user,
	(int)$db->escString($id)
);

//-------------------------------------------------------

echo Util::Render('master.phtml', array(
	'page_title'	=>	'View User',
	'content'		=>	Util::Render('users/view.phtml', array(
		'user'			=>	$db->row($sql_user),
		'user_count'	=>	$db->numRows($sql_user),
		'requested_id'	=>	$id,
	))
));