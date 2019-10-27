<?php

include_once ( CLASSPATH . 'books.php');

class Book extends Books {

	private $conn;
	private $table_name = "books";

	public $book_id;
	public $name;
	public $isbn;
	public $price;
	public $category_id;
	public $category_name;
	public $author_id;
	public $author_name;
	public $created;

	public function __construct($db){
		$this->conn = $db;
	}

	function read(){

		$query = "SELECT b.book_id, b.name, b.isbn, b.price, b.created, 
       		c.category_name, b.category AS category_id,
       		a.author_name, b.author AS author_id  
            FROM " . $this->table_name . " AS b
            LEFT JOIN category AS c ON b.category = c.category_id
            LEFT JOIN author AS a ON b.author = a.author_id
            ORDER BY b.created DESC";

		$stmt = $this->conn->prepare($query);

		$stmt->execute();

		return $stmt;
	}

	function create(){

		$query = "INSERT INTO " . $this->table_name . "
            SET name=:name, price=:price, isbn=:isbn, category=:category_id, author=:author_id, created=:created";

		$stmt = $this->conn->prepare($query);

		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->price=htmlspecialchars(strip_tags($this->price));
		$this->isbn=htmlspecialchars(strip_tags($this->isbn));
		if( !empty($this->category_name) ){
			$this->category_id = $this->getCategoryId( htmlspecialchars(strip_tags($this->category_name)), ['add_category'] );
		} else {
			$this->category_id=htmlspecialchars(strip_tags($this->category_id));
		}
		if( !empty($this->author_name) ){
			$this->author_id = $this->getAuthorId( htmlspecialchars(strip_tags($this->author_name)), ['add_author'] );
		} else {
			$this->author_id=htmlspecialchars(strip_tags($this->author_id));
		}

		$this->author_id=htmlspecialchars(strip_tags($this->author_id));
		$this->created=htmlspecialchars(strip_tags($this->created));

		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":price", $this->price);
		$stmt->bindParam(":isbn", $this->isbn);
		$stmt->bindParam(":category_id", $this->category_id);
		$stmt->bindParam(":author_id", $this->author_id);
		$stmt->bindParam(":created", $this->created);

		if($stmt->execute()){
			return true;
		}
		return false;
	}

	function readOne(){
		$query = "SELECT b.book_id, b.name, b.isbn, b.price, b.created, 
       		c.category_name, b.category AS category_id,
       		a.author_name, b.author AS author_id  
            FROM " . $this->table_name . " AS b
            LEFT JOIN category AS c ON b.category = c.category_id
            LEFT JOIN author AS a ON b.author = a.author_id
            WHERE b.book_id = ?
            LIMIT 0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind id of product to be updated
		$stmt->bindParam(1, $this->book_id);

		// execute query
		$stmt->execute();

		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// set values to object properties
		$this->name = $row['name'];
		$this->price = $row['price'];
		$this->isbn = $row['isbn'];
		$this->category_id = $row['category_id'];
		$this->category_name = $row['category_name'];
		$this->author_id = $row['author_id'];
		$this->author_name = $row['author_name'];
	}

	function search($keywords){
		$query = "SELECT b.book_id, b.name, b.isbn, b.price, b.created, 
       		c.category_name, b.category AS category_id,
       		a.author_name, b.author AS author_id  
            FROM " . $this->table_name . " AS b
            LEFT JOIN category AS c ON b.category = c.category_id
            LEFT JOIN author AS a ON b.author = a.author_id
            WHERE b.name LIKE ? OR b.isbn LIKE ? OR c.category_name LIKE ? OR a.author_name LIKE ?
            ORDER BY b.created DESC";

		$stmt = $this->conn->prepare($query);
		$keywords=htmlspecialchars(strip_tags($keywords));
		$keywords = "%{$keywords}%";

		$stmt->bindParam(1, $keywords);
		$stmt->bindParam(2, $keywords);
		$stmt->bindParam(3, $keywords);
		$stmt->bindParam(4, $keywords);

		$stmt->execute();

		return $stmt;
	}

	public function readPaging($from_record_num, $records_per_page){

		$query = "SELECT b.book_id, b.name, b.isbn, b.price, b.created, 
       		c.category_name, b.category AS category_id,
       		a.author_name, b.author AS author_id  
            FROM " . $this->table_name . " AS b
            LEFT JOIN category AS c ON b.category = c.category_id
            LEFT JOIN author AS a ON b.author = a.author_id
            ORDER BY b.created DESC
            LIMIT ?, ?";

		$stmt = $this->conn->prepare( $query );

		$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

		$stmt->execute();

		return $stmt;
	}

	public function count(){
		$query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}
}
?>