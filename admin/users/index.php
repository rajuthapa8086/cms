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

$db = new Db($db_config);
$sql = "SELECT * FROM `users` ORDER BY `id` DESC";

$done = Request::Get('done');

$smsg = "";

switch (strtolower($done)) {
	case 'add':
		$smsg = "User added successfully";
		break;
	case 'edit':
		$smsg = "User edited successfully";
		break;
	case 'delete':
		$smsg = "User deleted successfully";
		break;	
	case 'edit_password':
		$smsg = "User password edited successfully";
}

//-------------------------------------------------------

echo Util::Render('master.phtml', array(
	'page_title'	=>	'Users Listing Page',
	'content'		=>	Util::Render('users/index.phtml', array(
		'total'	=>	$db->numRows($sql),
		'users'	=>	$db->rows($sql),
		'smsg' => $smsg,
	))
));