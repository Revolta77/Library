<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/book.php';

$database = new Db();
$db = $database->getConnection();

// initialize object
$book = new Book($db);

// query products
$stmt = $book->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	// products array
	$books_arr = array();
	$books_arr["records"] = array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);

		$book_item = array(
			"book_id" => $book_id,
			"name" => $name,
			"isbn" => $isbn,
			"price" => $price,
			"category_id" => $category_id,
			"category_name" => $category_name,
			"author_id" => $author_id,
			"author_name" => $author_name,
		);

		array_push($books_arr["records"], $book_item);
	}

	// set response code - 200 OK
	http_response_code(200);

	// show products data in json format
	echo json_encode($books_arr);
} else{

	// set response code - 404 Not found
	http_response_code(404);

	// tell the user no products found
	echo json_encode(
		array("message" => $error_msg['404'])
	);
}

