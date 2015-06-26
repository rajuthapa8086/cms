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

$emsg = "";
$errors = array();

$db = new Db($db_config);
$sql_user = "SELECT * FROM `users` WHERE `id` = %d";
$sql_user = sprintf(
	$sql_user,
	(int)$db->escString($id)
);

if (Request::Post('delete_user_key') == "1") {
	if (Session::Get('id') != $id) {
		$sql = "DELETE FROM `users` WHERE `id` = %d";
		$sql = sprintf($sql, (int) $db->escString($id));
		if ($db->execute($sql)) {
			Response::Redirect("index.php?done=delete");
		} else {
			$emsg = "Could not delete user. Something went wrong. Please try again.";
		}
	}
}

if (Request::Post('edit_user_key') == "1") {
	if (Session::Get('id') != $id) {
		$active = is_null(Request::Post('active')) ? 0 : 1;
		$sql = <<<SQL
UPDATE `users`
SET `active` = %d,
`modified_at` = '%s'
WHERE `id` = %d
SQL;
		$sql = sprintf(
			$sql,
			(int) $db->escString($active),
			date('Y-m-d h:i:s'),
			(int) $db->escString($id)
		);
		if ($db->execute($sql)) {
			Response::Redirect("index.php?done=edit");
		} else {
			$emsg = "Could not edit user. Something went wrong. Please try again.";
		}
	}
}

if (Request::Post('edit_user_password_key') == "1") {

	$password = Request::Post('password');
	$cpassword = Request::Post('cpassword');

	if ($password == "") {
		$errors['password'][] = "Password field cannot be empty";
	}
	if ($cpassword == "") {
		$errors['cpassword'][] = "Confirm Password field cannot be empty";
	}
	if (strlen($password) < 6 || strlen($password) > 30) {
		$errors['password'][] = "Password must be (6-30) characters long.";
	}
	if ($password != $cpassword) {
		$errors['password'][] = "Password didnot matched";
		$errors['cpassword'][] = "Password didnot matched";
	}
	if (empty($errors)) {
		$sql = <<<SQL
UPDATE `users`
SET `password` = '%s',
`modified_at` = '%s'
WHERE `id` = %d
SQL;
		$sql = sprintf(
			$sql,
			$db->escString(md5($password . SALT)),
			date('Y-m-d h:i:s'),
			(int) $db->escString($id)
		);
		if ($db->execute($sql)) {
			Response::Redirect("index.php?done=edit_password");
		} else {
			$emsg = "Could not edit user password. Something went wrong. Please try again.";
		}
	}
}

//-------------------------------------------------------

echo Util::Render('master.phtml', array(
	'page_title'	=>	'Edit User',
	'content'		=>	Util::Render('users/edit.phtml', array(
		'errors'		=>	$errors,
		'emsg'			=>	$emsg,
		'user'			=>	$db->row($sql_user),
		'user_count'	=>	$db->numRows($sql_user),
		'requested_id'	=>	$id,
		'logger_id'		=>	Session::Get('id'),
	))
));