<?php
class Author{

	private $conn;
	private $table_name = "author";

	public $author_id;
	public $author_name;

	public function __construct($db){
		$this->conn = $db;
	}
	public function readAll(){
		$query = "SELECT author_id, author_name
                FROM " . $this->table_name . "
                ORDER BY author_name";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	public function read(){
		$query = "SELECT author_id, author_name
                FROM " . $this->table_name . "
                ORDER BY author_name";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}
}
?>