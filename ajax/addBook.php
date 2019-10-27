<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/subdom/knihy/core.php');
if( !empty($_POST['data']) ){

	$value = [];
	$data = $_POST['data'];
	if( !empty($data) ) foreach ( $data as $input ){
		$value[$input['id']] = $input['value'];
	}

	if ( !empty($value['name'])){
		include ( CLASSPATH . 'books.php');

		$books = new Books();

		if ( $books->isBookInDb($value['name']) ){
			echo 'book_is_in_db';
		} else {
			$book_id = $books->addBook( $value );
			if ( $book_id > 0 ){
				echo 'book_added';
			} else {
				echo 'db_error';
			}
		}
	} else {
		echo 'no_data';
	}
} else {
	echo 'no_data';
}