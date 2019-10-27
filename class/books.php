<?php

class Books {

	private $books = [];
	private $db;

	public function getBooks(){
		global $db;

		$q = "SELECT * FROM books AS boo 
			LEFT JOIN author AS aut ON boo.author = aut.author_id
			LEFT JOIN category AS cat ON boo.category = cat.category_id";
		$db->query($q);
		$this->books = $db->results();
		return $this->books;
	}

	public function getNames (){
		$names = [];
		if ( !empty($this->books) ) foreach ( $this->books as $book ){
			$names[$book['book_id']] = !empty($book['name']) ? $book['name'] : '';
		}
		return $names;
	}

	public function getAuthors (){
		$authors = [];
		if ( !empty($this->books) ) foreach ( $this->books as $book ){
			if ( !empty($book['author_name']) && !in_array( $book['author_name'], $authors )){
				$authors[] = $book['author_name'];
			}

		}
		return $authors;
	}

	public function addAuthor($name){
		global $db;
		$q = "INSERT INTO author SET author_name = :name;";
		$db->query($q, [':name' => $name]);
		return $db->lastId();
	}

	public function getAuthorId($name, $params = []){
		global $db;
		$q = "SELECT author_id FROM author WHERE author_name = :name;";
		$db->query($q, [':name' => $name]);
		$author = $db->results();

		if( empty($author) && isset($params['add_author']) ){
			return $this->addAuthor($name);
		}
		return $author[0]['author_id'];
	}

	public function addCategory($name){
		global $db;
		$q = "INSERT INTO category SET category_name = :name;";
		$db->query($q, [':name' => $name]);
		return $db->lastId();
	}

	public function getCategoryId($name, $params = []){
		global $db;
		$q = "SELECT category_id FROM category WHERE category_name = :name;;";
		$db->query($q, [':name' => $name]);
		$category = $db->results();

		if( empty($category) && isset($params['add_category']) ){
			return $this->addCategory($name);
		}
		return $category[0]['category_id'];
	}

	public function getCategories(){
		global $db;
		$q = "SELECT category_id, category_name FROM category;";
		$db->query($q);
		$category = $db->results();
		return $category;
	}

	public function getISBN(){
		$isbns = [];
		if ( !empty($this->books) ) foreach ( $this->books as $book ){
			$isbns[$book['book_id']] = $book['isbn'];
		}
		return $isbns;
	}

	public function getPrice(){
		$prices = [];
		if ( !empty($this->books) ) foreach ( $this->books as $book ){
			$prices[$book['book_id']] = $book['price'];
		}
		return $prices;
	}

	public function isBookInDb($name){
		global $db;
		$ret = false;
		$q = "SELECT book_id FROM books WHERE name = :name; ";
		$db->query($q, [':name' => $name]);
		$book = $db->results();
		if ( !empty($book) ){
			$ret = true;
		}
		return $ret;
	}

	public function addBook( $data ){
		global $db;
		$params = ['add_author' => 1, 'add_category' => 1 ];
		$author = $this->getAuthorId( $data['author'], $params );
		$category = $this->getCategoryId( $data['category'], $params );
		$price = number_format((float)$data['price'], 2, '.', '');
		$name = htmlspecialchars(strip_tags($data['name']));
		$isbn = htmlspecialchars(strip_tags($data['isbn']));

		if( is_numeric($author) && is_numeric($category) && is_numeric($price) && !empty($data['name']) && !empty($data['isbn']) ){
			$created = date('Y-m-d H:i:s');

			$q = "INSERT INTO books 
			SET name=:name, price=:price, isbn=:isbn, category=:category, author=:author, created=:created";

			$values = [ ':name' => $name, ':price' => $price, ':isbn' => $isbn, ':category' => $category,
						':author' => $author, ':created' => $created ];
			$db->query($q, $values);
			$book_id = $db->lastId();

			return $book_id;
		}
		return 0;
	}
}