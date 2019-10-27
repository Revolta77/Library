<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/book.php';

// get database connection
$database = new Db();
$db = $database->getConnection();

// prepare product object
$book = new Book($db);

// set ID property of record to read
$book->book_id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of product to be edited
$book->readOne();

if($book->name!=null){
	// create array
	$book_arr = array(
		"id" =>  $book->book_id,
		"name" => $book->name,
		"isbn" => $book->isbn,
		"price" => $book->price,
		"category_id" => $book->category_id,
		"category_name" => $book->category_name,
		"author_id" => $book->author_id,
		"author_name" => $book->author_name,
	);
	http_response_code(200);
	echo json_encode($book_arr);
} else {
	http_response_code(404);
	echo json_encode(array("message" => $error_msg['404']));
}
?>