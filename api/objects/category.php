<?php
class Category{

	private $conn;
	private $table_name = "category";

	public $category_id;
	public $category_name;

	public function __construct($db){
		$this->conn = $db;
	}
	public function readAll(){
		$query = "SELECT category_id, category_name
                FROM " . $this->table_name . "
                ORDER BY category_name";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	public function read(){
		$query = "SELECT category_id, category_name
                FROM " . $this->table_name . "
                ORDER BY category_name";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}
}
?>