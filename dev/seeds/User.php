<?php

function CreateUser() {
	global $db_config;
	$db = new Db($db_config);
	$sql = <<<SQL
INSERT INTO `users`
(`id`, `username`, `password`, `active`, `created_at`, `modified_at`)
VALUES
(%d, '%s', '%s', %d, '%s', '%s');
SQL;
	$sql = sprintf(
		$sql,
		1,
		$db->escString("adminone"),
		$db->escString(md5("password" . SALT)),
		1,
		date('Y-m-d h:i:s'),
		date('Y-m-d h:i:s')
	);

	$db->execute($sql);
	echo "User created successfully";
}