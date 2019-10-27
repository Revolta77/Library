<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/book.php';

$database = new Db();
$db = $database->getConnection();

$book = new Book($db);

$data = json_decode(file_get_contents("php://input"));
$errors = [];

if( !empty($data->name) && !empty($data->price) && !empty($data->isbn) &&
	( !empty($data->category_id) || !empty($data->category_name) ) &&
	( !empty($data->author_id) || !empty($data->author_name) )
){
	$book->name = $data->name;
	$book->price = $data->price;
	$book->isbn = $data->isbn;
	$book->category_id = isset($data->category_id) ? $data->category_id : '';
	$book->author_id = isset($data->author_id) ? $data->author_id : "";
	$book->category_name = isset($data->category_name) ? $data->category_name : '';
	$book->author_name = isset($data->author_name) ? $data->author_name : "";
	$book->created = date('Y-m-d H:i:s');

	if( isset($data->category_id) && !is_numeric($data->category_id)){
		$errors[] = "Category_id must by integer";
	}
	if( isset($data->author_id) && !is_numeric($data->author_id)){
		$errors[] = "Author_id must by integer";
	}

	if ( !empty($errors) ){
		http_response_code(401);
		echo json_encode( array( "message" => $errors ) );
	} elseif( $book->create() ){
		http_response_code(201);
		echo json_encode(array("message" => $error_msg['201']));
	} else{
		http_response_code(503);
		echo json_encode(array("message" => $error_msg['503']));
	}
} else {
	http_response_code(400);
	echo json_encode(array("message" => $error_msg['400']));
}
?>