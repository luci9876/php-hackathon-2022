<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object file
include_once 'config/database.php';
include_once 'programme.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// authorization
$programme = new Programme($db);
if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="My API"');
    header('HTTP/1.0 401 Unauthorized');
    echo '<p>Access denied. You did not enter a password.</p>';
    exit;
}
if ($_SERVER['PHP_AUTH_PW'] == 'AdminsOnly') {
    echo '<p>Access granted</p>';
} else {
    header('WWW-Authenticate: Basic realm="My API"');
    header('HTTP/1.0 401 Unauthorized');
    echo '<p>Access denied! You do not know the password.</p>';
    exit;
}
// get programme id
$data = json_decode(file_get_contents("php://input"));

// set programme id to be deleted
$programme->id = $data->id;

// delete the programme
if ($programme->delete()) {

    // set response code - 200 ok
    http_response_code(200);

    // tell the user
    echo json_encode(array("message" => "Succesfull!"));
}

// if unable to delete the programme
else {

    // set response code - 503 service unavailable
    http_response_code(503);

    // tell the user
    echo json_encode(array("message" => "Unable to delete programme."));
}
