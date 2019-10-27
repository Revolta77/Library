<?php
include ($_SERVER['DOCUMENT_ROOT'] . '/subdom/knihy/configuration.php');
include (SITEPATH . 'global.php');

// show error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

$home_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/api/';
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$records_per_page = 5;
$from_record_num = ($records_per_page * $page) - $records_per_page;

$error_msg = [
	'404' => 'No books found.',
	'400' => 'Unable to create book. Data is incomplete.',
	'201' => 'Book was created.',
	'503' => 'Unable to create book.',

];