<?php
define('DS', DIRECTORY_SEPARATOR);
define('BASE_DIR', dirname(__FILE__) . DS);
define('SITE_ROOT', '');
require_once BASE_DIR . 'Libs' . DS . 'autoload.php';
require_once BASE_DIR . 'configs' .  DS . 'incs.php';
require_once BASE_DIR . 'helpers' .  DS . 'incs.php';

//-------------------------------------------------------

Util::$template_path = BASE_DIR . 'templates' . DS;

//-------------------------------------------------------

$notFound = false;

$db = new Db($db_config);
$sql_menus = "SELECT `id`, `menu_title`, `page_title`, `slug` FROM `pages` WHERE `active` = 1 AND `is_menu` = 1 AND `is_home` = 0";
$sql_home = "SELECT `id`, `menu_title`, `page_title`, `slug` FROM `pages` WHERE `active` = 1 AND `is_menu` = 1 AND `is_home` = 1";

$slug = get_slug();

$home = $db->row($sql_home);

if ($slug == "") {
	if (!is_null($home)) {
		$slug = $home['slug'];
	} else {
		$notFound = true;
	}
}


$sql_page = sprintf("SELECT * FROM `pages` WHERE `slug` = '%s'", $db->escString($slug));
$page = $db->row($sql_page);

if (is_null($page)) {
	$notFound = true;
}

//-------------------------------------------------------

if ($notFound) {
	Response::SetHeader($_SERVER['SERVER_PROTOCOL'], '404 Page Not Found');
	die(Util::Render('notfound.html'));
}

echo Util::Render('index.phtml', array(
  'menus' => $db->rows($sql_menus),
  'home' => $home,
  'page' => $page,
));