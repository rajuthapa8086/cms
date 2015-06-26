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
$sql = "SELECT * FROM `pages` ORDER BY `id` DESC";

$done = Request::Get('done');

$smsg = "";

switch (strtolower($done)) {
	case 'add':
		$smsg = "Page added successfully";
		break;
	case 'edit':
		$smsg = "Page edited successfully";
		break;
	case 'upload_image':
		$smsg = "Page image uploaded successfully";
		break;
	case 'delete':
		$smsg = "Page deleted successfully";
		break;	
}

//-------------------------------------------------------

echo Util::Render('master.phtml', array(
	'page_title'	=>	'Pages Listing Page',
	'content'		=>	Util::Render('pages/index.phtml', array(
		'total'	=>	$db->numRows($sql),
		'pages'	=>	$db->rows($sql),
		'smsg' => $smsg,
	))
));