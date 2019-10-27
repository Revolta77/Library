<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/book.php';

// instantiate database and product object
$database = new Db();
$db = $database->getConnection();

// initialize object
$book = new Book($db);

// get keywords
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

// query products
$stmt = $book->search($keywords);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	// products array
	$books_arr=array();
	$books_arr["records"]=array();

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		extract($row);

		$books_item=array(
			"id" => $book_id,
			"name" => $name,
			"isbn" => html_entity_decode($isbn),
			"price" => $price,
			"category_id" => $category_id,
			"category_name" => $category_name,
			"author_id" => $author_id,
			"author_name" => $author_name
		);

		array_push($books_arr["records"], $books_item);
	}
	http_response_code(200);
	echo json_encode($books_arr);
}

else{
	http_response_code(404);
	echo json_encode(
		array("message" => $error_msg['404'])
	);
}
?>