<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// get database connection
include_once 'config/database.php';
  
// instantiate programme object
include_once 'programme.php';
  
$database = new Database();
$db = $database->getConnection();
  
$programme = new Programme($db);
  
// get posted data
$data = json_decode(file_get_contents("php://input"));
  
// make sure data is not empty
if(
    !empty($data->start_time) &&
    !empty($data->end_time) &&
    !empty($data->room_id) &&
    !empty($data->programme_type_id)
){
  
    // set programme property values
    $programme->start_time = $data->start_time;
    $programme->end_time = $data->end_time;
    $programme->room_id = $data->room_id;
    $programme->programme_type_id = $data->programme_type_id;
    
  
    // create the programme
    if($programme->create()){
  
        // set response code - 201 created
        http_response_code(201);
  
        // tell the user
        echo json_encode(array("message" => "programme was created."));
    }
  
    // if unable to create the programme, tell the user
    else{
  
        // set response code - 503 service unavailable
        http_response_code(503);
  
        // tell the user
        echo json_encode(array("message" => "Unable to create programme."));
    }
}
  
// tell the user data is incomplete
else{
  
    // set response code - 400 bad request
    http_response_code(400);
  
    // tell the user
    echo json_encode(array("message" => "Unable to create programme. Data is incomplete."));
}
?>
