<?php

function CreateUsersTable() {
	global $db_config;
	$db = new Db($db_config);
	$sql = <<<SQL
CREATE TABLE IF NOT EXISTS `users` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`username` VARCHAR(31) NOT NULL,
	`password` VARCHAR(200) NOT NULL,
	`active` TINYINT(1) NOT NULL DEFAULT 0,
	`created_at` DATETIME NOT NULL default '0000-00-00 00:00:00',
	`modified_at` DATETIME NOT NULL default '0000-00-00 00:00:00',
	`logged_at` DATETIME NOT NULL default '0000-00-00 00:00:00',
	PRIMARY KEY (`id`),
	UNIQUE KEY (`username`)
);
SQL;
	if ($db->execute($sql)) {
		echo "Users table created successfully";
	} else {
		echo "Error: Could not create users table. Please try again";
	}
}

function DropUsersTable() {
	global $db_config;
	$db = new Db($db_config);
	$sql = <<<SQL
DROP TABLE IF EXISTS `users`;
SQL;
	if ($db->execute($sql)) {
		echo "Users table dropped successfully";
	} else {
		echo "Error: Could not drop users table. Please try again";
	}
}