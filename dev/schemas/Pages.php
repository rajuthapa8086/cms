<?php

function CreatePagesTable() {
	global $db_config;
	$db = new Db($db_config);
	$sql = <<<SQL
CREATE TABLE IF NOT EXISTS `pages` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`page_title` VARCHAR(255) NOT NULL,
	`content` LONGTEXT,
	`slug` VARCHAR(255) NOT NULL,
	`menu_title` VARCHAR(50) NOT NULL,
	`image` VARCHAR(50) NOT NULL DEFAULT '',
	`page_url` TEXT,
	`is_home` TINYINT(1) NOT NULL DEFAULT 0,
	`is_menu` TINYINT(1) NOT NULL DEFAULT 0,
	`active` TINYINT(1) NOT NULL DEFAULT 0,
	`created_at` DATETIME NOT NULL default '0000-00-00 00:00:00',
	`modified_at` DATETIME NOT NULL default '0000-00-00 00:00:00',
	PRIMARY KEY (`id`),
	UNIQUE KEY (`slug`),
	UNIQUE KEY (`menu_title`)
);
SQL;
	if ($db->execute($sql)) {
		echo "Pages table created successfully";
	} else {
		echo "Error: Could not create pages table. Please try again";
	}
}

function DropPagesTable() {
	global $db_config;
	$db = new Db($db_config);
	$sql = <<<SQL
DROP TABLE IF EXISTS `pages`;
SQL;
	if ($db->execute($sql)) {
		echo "Pages table dropped successfully";
	} else {
		echo "Error: Could not drop pages table. Please try again";
	}
}