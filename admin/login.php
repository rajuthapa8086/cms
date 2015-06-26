<?php
define('LOGIN_PAGE', 'true');
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

Util::$template_path = ADMIN_DIR . 'templates' . DS;

//-------------------------------------------------------

$emsg = "";

if (Request::Post('login_key') == "1") {
	$username = Request::Post('username');
	$password = Request::Post('password');

	if ($username == "" || $password == "") {
		$emsg = "Please enter both username and password.";
	} else {
		$db = new Db($db_config);
		$sql = <<<SQL
SELECT * FROM `users`
WHERE `username` = '%s' AND `password` = '%s' AND `active` = 1
SQL;
		$sql = sprintf(
			$sql,
			$db->escString($username),
			$db->escString(md5($password . SALT))			
		);
		if ($db->numRows($sql) > 0) {
			$user = $db->row($sql);
			$db->execute(sprintf(
				"UPDATE `users` SET `logged_at` = '%s' WHERE `id` = %d",
				date('Y-m-d h:i:s'),
				(int) $db->escString($user['id'])
			));
			Session::Set('username', $user['username']);
			Session::Set('id', $user['id']);
			Response::Redirect(ADMIN_ROOT . 'index.php');
		} else {
			$emsg = "Login Failed";
		}
	}

}

if (Request::Get('logout') == 'true') {
	Session::Destroy('username');
	Session::Destroy('id');
	Response::Redirect(ADMIN_ROOT . 'login.php');
}

//-------------------------------------------------------

echo Util::Render('master.phtml', array(
	'page_title'	=>	'Login',
	'content'		=>	Util::Render('login.phtml', array(
		'emsg'	=>	$emsg,
	))
));