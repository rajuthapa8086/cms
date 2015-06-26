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
$sql_page = "SELECT * FROM `pages` WHERE `id` = %d";
$sql_page = sprintf(
	$sql_page,
	(int)$db->escString($id)
);

$filepath = BASE_DIR . 'assets' . DS . 'images' . DS;

if (Request::Post('delete_page_key') == "1") {
	$sql = "DELETE FROM `pages` WHERE `id` = %d";
	$page = $db->row($sql_page);
	$imagename = $page['image'];
	if (file_exists($filepath . $imagename)) {
		unlink($filepath . $imagename);
	}
	$sql = sprintf($sql, (int) $db->escString($id));
	if ($db->execute($sql)) {
		Response::Redirect("index.php?done=delete");
	} else {
		$emsg = "Could not delete page. Something went wrong. Please try again.";
	}
}

if (Request::Post('upload_key') == "1") {
	if (isset($_FILES['image']) && $_FILES['image']['name'] != "") {
		$image = $_FILES['image'];
		$extension = strtolower(substr($image['name'], -4));
		if ($extension != ".jpg" && $extension != ".png" && $extension != ".gif") {
			$errors['image'][] = "Image must be .jpg | .png | .gif format";
		}
		if ($image['size'] > 500000) {
			$errors['image'][] = "Image must be less than 500kb";
		}
		if (empty($errors)) {
			$imagename = 'page_' . $id . $extension;
			if (file_exists($filepath . $imagename)) {
				unlink($filepath . $imagename);
			}
			move_uploaded_file(
				$image['tmp_name'],
				$filepath . $imagename
			);
			$sql = <<<SQL
UPDATE `pages`
SET `image` = '%s'
WHERE `id` = %d
SQL;
			$sql = sprintf(
				$sql,
				$db->escString($imagename),
				(int) $db->escString($id)
			);

			if ($db->execute($sql)) {
				Response::Redirect('index.php?done=upload_image');
			} else {
				$emsg = "Could not upload image. Please try again.";
			}
		}
	}
}

if (Request::Post('edit_page_key') == "1") {
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

	if ($db->numRows(sprintf("SELECT `id` FROM `pages` WHERE `slug` = '%s' AND `id` <> %d", $db->escString($slug), (int)$db->escString($id))) > 0) {
		$errors['slug'][] = "Slug " . $slug . " already exists. Please try another";
	}

	if ($db->numRows(sprintf("SELECT `id` FROM `pages` WHERE `menu_title` = '%s' AND `id` <> %d", $db->escString($menu_title), (int)$db->escString($id))) > 0) {
		$errors['menu_title'][] = "Menu title " . $menu_title . " already exists. Please try another";
	}

	if (empty($errors)) {

		if ($is_home == 1) {
			$db->execute("UPDATE `pages` SET `is_home` = 0");
		}

		$sql = <<<SQL
UPDATE `pages`
SET
`page_title` = '%s',
`content` = '%s',
`slug` = '%s',
`menu_title` = '%s',
`is_home` = %d,
`is_menu` = %d,
`active` = %d,
`modified_at` = '%s'
WHERE
`id` = %d
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
			(int)$db->escString($id)
		);
		if ($db->execute($sql)) {
			Response::Redirect('index.php?done=edit');
		} else {
			$emsg = "Could not insert data. Something went wrong. Please try again";
		}
	}
}

//-------------------------------------------------------

echo Util::Render('master.phtml', array(
	'page_title'	=>	'Edit Page',
	'content'		=>	Util::Render('pages/edit.phtml', array(
		'errors'		=>	$errors,
		'emsg'			=>	$emsg,
		'page'			=>	$db->row($sql_page),
		'page_count'	=>	$db->numRows($sql_page),
		'requested_id'	=>	$id,
	))
));