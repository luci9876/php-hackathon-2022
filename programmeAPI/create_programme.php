<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


// get database connection
include_once 'config/database.php';

include_once 'programme.php';
include_once 'room_type.php';

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
$database = new Database();
$db = $database->getConnection();

$programme = new Programme($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->start_time) &&
    !empty($data->end_time) &&
    !empty($data->room_id) &&
    !empty($data->programme_type_id)
) {

    // set programme property values
    $programme->start_time = $data->start_time;
    $programme->end_time = $data->end_time;
    $programme->room_id = $data->room_id;
    $programme->programme_type_id = $data->programme_type_id;
    $room_type = new RoomType($db);
    $room_type->room_id = $data->room_id;
    $room_type->programme_type_id = $data->programme_type_id;

    if ($room_type->checkRoom() < 1) {
        http_response_code(400);

        // tell the user
        echo json_encode(array("message" => "Unable to create programme. Room don't support this typeof programme!"));
        exit(0);
    }
    $programmes = $programme->read();
    if (!empty($programmes)) {
        $arr = ($programmes);

        foreach ($arr as $item) {
            $start = $item['start'];
            $end = $item['end'];
            if ($programme->start_time <= $end && $start <= $programme->end_time) {
                http_response_code(400);

                // tell the user
                echo json_encode(array("message" => "Unable to create programme. Room is not avaiable!"));
                exit(0);
            }
        }
    }
    // create the programme
    if ($programme->create()) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "programme was created."));
    }

    // if unable to create the programme, tell the user
    else {


        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to create programme."));
    }
}

// tell the user data is incomplete
else {


    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create programme. Data is incomplete."));
}
