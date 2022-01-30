<?php
//test purpose, check connection
include_once 'config/database.php';
// instantiate database 
$database = new Database();
$db = $database->getConnection();
