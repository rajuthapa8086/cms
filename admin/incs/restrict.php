<?php
if (!defined('LOGIN_PAGE')) {
	if (!(Session::Exists('username') && Session::Exists('id'))) {
		Response::Redirect(ADMIN_ROOT . 'login.php');
	}
}