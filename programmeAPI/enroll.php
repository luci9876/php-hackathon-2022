<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once 'config/database.php';


include_once 'attendees.php';
include_once 'programme.php';
include_once 'room.php';

$database = new Database();
$db = $database->getConnection();

$attendee = new Attendees($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if (
    !empty($data->programme_id) &&
    !empty($data->cnp)
) {
    // set  values
    $attendee->cnp = $data->cnp;
    $attendee->programme_id = $data->programme_id;
    if (!$attendee->validCNP($attendee->cnp)) {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to enroll! Invalid CNP!"));
        exit(0);
    }
    if ($attendee->countCnp() > 0) {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to enroll! Already enrolled!"));
        exit(0);
    }
    $programme = new Programme($db);
    $room = new Room($db);
    $r_id = $programme->getRoom($attendee->programme_id);
    $capacity = $room->getCapacity($r_id);
    if ($capacity - $attendee->countAttendees() - 1 < 0) {
        http_response_code(400);
        echo json_encode(array("message" => "Unable to enroll! No more seats avaiable!"));
        exit(0);
    }
    // enroll attendee
    if ($attendee->create()) {

        // set response code - 201 created
        http_response_code(201);

        // tell the user
        echo json_encode(array("message" => "Attendee was enrolled."));
    } else {

        // set response code - 503 service unavailable
        http_response_code(503);

        // tell the user
        echo json_encode(array("message" => "Unable to enroll."));
    }
}

// tell the user data is incomplete
else {

    // set response code - 400 bad request
    http_response_code(400);

    // tell the user
    echo json_encode(array("message" => "Unable to create product. Data is incomplete."));
}
