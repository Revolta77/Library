<?php


class Database {

	private static $instance = null;
	private $pdo;
	private $query;
	private $results;
	private $count = 0;
	private $error = false;

	private $query_string = "";
	private $bindValues = array();
	private $lastId;



	private function __construct() {

		try {
			// Put your database information
			$this->pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=". DB_NAME,DB_USER,DB_PASSWORD);
		} catch (PDOException $e) {
			die($e->getMessage());
		}

	}

	// specify your own database credentials
	private $host = DB_HOST;
	private $db_name = DB_NAME;
	private $username = DB_USER;
	private $password = DB_PASSWORD;
	public $conn;

	// get the database connection
	public function getConnection(){

		$this->conn = null;

		try{
			$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
			$this->conn->exec("set names utf8");
		}catch(PDOException $exception){
			echo "Connection error: " . $exception->getMessage();
		}

		return $this->conn;
	}

	public static function getInstance() {
		if (is_null(self::$instance)) {
			self::$instance = new Database();
		}
		return self::$instance;
	}

	public function query($sql, $parameters = array()) {
		$this->error = false;

		if ($this->query = $this->pdo->prepare($sql)) {
			foreach ($parameters as $key => $param) {
				$this->query->bindValue($key, $param);
			}
			if ($this->query->execute()) {
				// You can PDO::FETCH_OBJ instad of assoc, or whatever you like
				$this->results = $this->query->fetchAll(PDO::FETCH_ASSOC);
				$this->count = $this->query->rowCount();
				if($this->count > 0){
					$this->lastId = $this->pdo->lastInsertId();
				}

			} else {
				$this->error = true;
			}
		}
		return $this;
	}


	public function select($fields = "*") {
		$action = "";
		$this->query_string = "";
		if (is_array($fields)) {
			$action = "SELECT ";
			for ($i = 0; $i < count($fields); $i++) {
				$action .= $fields[$i];
				if ($i != count($fields) - 1)
					$action .= ', ';
			}
		} else {
			$action = "SELECT * ";
		}
		$this->query_string .= $action;
		return $this;
	}

	public function from($table) {
		$this->query_string .= " FROM {$table} ";
		return $this;
	}

	public function where($where = array()) {
		$keys = array_keys($where);
		$action = " WHERE ";
		for ($i = 0; $i < count($keys); $i++) {
			$action .= $keys[$i] . ' = ?';
			if ($i < count($keys) - 1)
				$action .= ' AND ';
			$this->bindValues[] = $where[$keys[$i]];
		}
		$this->query_string .= $action;
		return $this;
	}

	public function execute() {
		if (!empty($this->query_string))
			$this->query($this->query_string, $this->bindValues);
		$this->bindValues = array();
	}

	public function getQueryString() {
		return $this->query_string;
	}

	public function results() {
		return $this->results;
	}
	public function first() {
		return $this->results[0];
	}
	public function last() {
		return $this->results[$this->count-1];
	}
	public function row($id) {
		return $this->results[$id];
	}
	public function error() {
		return $this->error();
	}
	public function count() {
		return $this->count;
	}
	public function lastId() {
		return $this->lastId;
	}
}