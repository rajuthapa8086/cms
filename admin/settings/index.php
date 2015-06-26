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
$sql = "SELECT * FROM `settings`";

$done = Request::Get('done');

$emsg = "";
$smsg = "";

switch (strtolower($done)) {
	case 'edit':
		$smsg = "Settings edited successfully";
		break;
}

if (Request::Post('edit_settings_key') == "1") {
	$site_name = trim(Request::Post('site_name'));
	if ($site_name == "") {
		$emsg = "Site name cannot be empty";
	}

	if ($emsg == "") {
		if ($db->execute(sprintf("UPDATE `settings` SET `site_name` = '%s'", $site_name))) {
			Response::Redirect('index.php?done=edit');
		} else {
			$emsg = "Could not edit settings. Something went wrong. Please try again.";
		}
	}
	
}

//-------------------------------------------------------

echo Util::Render('master.phtml', array(
	'page_title'	=>	'Settings',
	'content'		=>	Util::Render('settings/index.phtml', array(
		'settings'	=>	$db->row($sql),
		'smsg' => $smsg,
		'emsg' => $emsg,
	))
));