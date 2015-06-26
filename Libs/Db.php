<?php

class Db {

	private $dbObj;

	public function __construct($config) {
		$this->dbObj = new mysqli(
			$config['host'],
			$config['user'],
			$config['pass'],
			$config['name']
		);

		if (mysqli_connect_error()) {
			die("Database Error: " . mysqli_connect_error());
		}
	}

	public function execute($sql) {
		$query = $this->dbObj->query($sql);
		if ($this->dbObj->error) {
			die("Database Error: " . $this->dbObj->error);
		}
		return $query;
	}

	public function row($sql) {
		return (FALSE !== $this->execute($sql)) ? $this->execute($sql)->fetch_assoc() : NULL;		
	}

	public function rows($sql) {
		$rows = array();
		$result = $this->execute($sql);
		while($row = $result->fetch_assoc()) {
			$rows[] = $row;
		}
		return $rows;
	}

	public function numRows($sql) {
		return (FALSE !== $this->execute($sql)) ? $this->execute($sql)->num_rows : -1;		
	}

	public function affectedRows() {
		return $this->dbObj->affected_rows;		
	}

	public function escString($value) {
		return $this->dbObj->real_escape_string($value);
	}

}