<?php
$serverName = "iron\iron";
$database = 'project_test';
$uid = 'project';
$pwd = 'qtt4sas,';
// $port = 1433;
// $dns = "sqlsrv:server=" . $serverName . ";Database=$database;ConnectionPooling=0";
// echo $dns;

// try {
// 	$conn = new PDO(
// 		$dns,
// 		$uid,
// 		$pwd,
// 		array(
// 			//PDO::ATTR_PERSISTENT => true,
// 			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
// 		)
// 	);
// } catch (PDOException $e) {
// 	die("Error connecting to SQL Server: " . $e->getMessage());
// }

// $sql = "SELECT name FROM proj_users";
// foreach ($conn->query($sql) as $row) {
// 	echo $row['name'] . "<br>";
// }

// echo explode(".", explode("/", $_SERVER['PHP_SELF'])[1])[0];

class ConnectDB {
	private $server;
	private $database;
	private $user;
	private $pass;
	private $db;

	function __construct($server, $database, $user, $pass) {
		$this->server = $server;
		$this->database = $database;
		$this->user = $user;
		$this->pass = $pass;
	}

	function getConnection() {
		$dns = "sqlsrv:server=$this->server;Database=$this->database;ConnectionPooling=0";
		$this->db = new PDO(
			$dns,
			$this->user,
			$this->pass,
			array(
				//PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			)
		);
	}

	function queryMysql($query) {
		$result = $this->db->query($query);
		if (!$result) {
			die($this->db->error);
		}

		return $result;
	}
}

$conn = new ConnectDB($serverName, $database, $uid, $pwd);
$conn->getConnection();

$sql = "SELECT name FROM proj_users";
$c = $conn->queryMysql($sql);

foreach ($c as $row) {
	echo $row['name'] . "<br>";
}
