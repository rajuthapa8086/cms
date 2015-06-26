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

$emsg = "";
$errors = array();

if (Request::Post('add_user_key') == "1") {

	$db = new Db($db_config);

	$username = Request::Post('username');
	$password = Request::Post('password');
	$cpassword = Request::Post('cpassword');
	$active = is_null(Request::Post('active')) ? 0 : 1;

	if ($username == "") {
		$errors['username'][] = "Username field cannot be empty";
	}
	if ($password == "") {
		$errors['password'][] = "Password field cannot be empty";
	}
	if ($cpassword == "") {
		$errors['cpassword'][] = "Confirm Password field cannot be empty";
	}
	if (strlen($username) < 6 || strlen($username) > 30) {
		$errors['username'][] = "Username must be (6-30) characters long.";
	}
	if (strlen($password) < 6 || strlen($password) > 30) {
		$errors['password'][] = "Password must be (6-30) characters long.";
	}
	if ($password != $cpassword) {
		$errors['password'][] = "Password didnot matched";
		$errors['cpassword'][] = "Password didnot matched";
	}
	$sql = <<<SQL
SELECT `id` FROM `users`
WHERE `username` = '%s';
SQL;
	$sql = sprintf(
		$sql,
		$db->escString($username)
	);
	if ($db->numRows($sql) > 0) {
		$errors['username'][] = "Username " . $username . " already exists. Please try another";
	}
	
	if (empty($errors)) {
		$sql = <<<SQL
INSERT INTO `users`
(`username`, `password`, `active`, `created_at`, `modified_at`)
VALUES
('%s', '%s', %d, '%s', '%s');
SQL;
		$sql = sprintf(
			$sql,
			$db->escString($username),
			$db->escString(md5($password . SALT)),
			(int)$db->escString($active),
			date('Y-m-d h:i:s'),
			date('Y-m-d h:i:s')
		);
		if ($db->execute($sql)) {
			Response::Redirect('index.php?done=add');
		} else {
			$emsg = "Could not insert data. Something went wrong. Please try again";
		}
	}
}


//-------------------------------------------------------

echo Util::Render('master.phtml', array(
	'page_title'	=>	'Add New User',
	'content'		=>	Util::Render('users/add.phtml', array(
		'errors'	=>	$errors,
		'emsg'		=>	$emsg,
	))
));