<?php

$GLOBALS['site_path'] = $_SERVER['DOCUMENT_ROOT'] . '{document_root}/';

global $db;
include (SITEPATH . 'class/database.php');
$db = Database::getInstance();