<?php

function CreateSettingsTable() {
	global $db_config;
	$db = new Db($db_config);
	$sql = <<<SQL
CREATE TABLE IF NOT EXISTS `settings` (
	`site_name` VARCHAR(255) NOT NULL
);
SQL;
	if ($db->execute($sql)) {
		$db->execute(sprintf("INSERT INTO `settings` (`site_name`) VALUES ('%s');", $db->escString('My Website')));
		echo "Settings table created successfully";
	} else {
		echo "Error: Could not create settings table. Please try again";
	}
}

function DropSettingsTable() {
	global $db_config;
	$db = new Db($db_config);
	$sql = <<<SQL
DROP TABLE IF EXISTS `settings`;
SQL;
	if ($db->execute($sql)) {
		echo "Settings table dropped successfully";
	} else {
		echo "Error: Could not drop settings table. Please try again";
	}
}