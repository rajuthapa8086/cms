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

if (Request::Post('add_page_key') == "1") {

	$db = new Db($db_config);

	$page_title = trim(Request::Post('page_title'));
	$content = trim(Request::Post('content'));
	$slug = trim(Request::Post('slug'));
	$menu_title = trim(Request::Post('menu_title'));
	$is_home = is_null(Request::Post('is_home')) ? 0 : 1;
	$is_menu = is_null(Request::Post('is_menu')) ? 0 : 1;
	$active = is_null(Request::Post('active')) ? 0 : 1;

	if ($page_title == "") {
		$errors['page_title'][] = "Page title field cannot be empty";
	}

	if ($content == "") {
		$errors['content'][] = "Content field cannot be empty";
	}

	if ($slug == "") {
		$errors['slug'][] = "Slug field cannot be empty";
	}

	if ($menu_title == "") {
		$errors['menu_title'][] = "Menu title field cannot be empty";
	}

	if ($db->numRows(sprintf("SELECT `id` FROM `pages` WHERE `slug` = '%s'", $slug)) > 0) {
		$errors['slug'][] = "Slug " . $slug . " already exists. Please try another";
	}

	if ($db->numRows(sprintf("SELECT `id` FROM `pages` WHERE `menu_title` = '%s'", $menu_title)) > 0) {
		$errors['menu_title'][] = "Menu title " . $menu_title . " already exists. Please try another";
	}

	if (empty($errors)) {

		if ($is_home == 1) {
			$db->execute("UPDATE `pages` SET `is_home` = 0");
		}

		$sql = <<<SQL
INSERT INTO `pages`
(`page_title`, `content`, `slug`, `menu_title`, `is_home`, `is_menu`, `active`, `created_at`, `modified_at`)
VALUES
('%s', '%s', '%s', '%s', %d, %d, %d, '%s', '%s');
SQL;
		$sql = sprintf(
			$sql,
			$db->escString($page_title),
			$db->escString($content),
			$db->escString($slug),
			$db->escString($menu_title),
			(int)$db->escString($is_home),
			(int)$db->escString($is_menu),
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
	'page_title'	=>	'Add New Page',
	'content'		=>	Util::Render('pages/add.phtml', array(
		'errors'	=>	$errors,
		'emsg'		=>	$emsg,
	))
));