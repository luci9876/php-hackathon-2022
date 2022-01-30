<?php
//test purpose
include_once 'config/database.php';
include_once 'admins.php';
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
$admin = new Admin($db);
// query products
$stmt = $admin->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num > 0) {

    // products array
    $admins_arr = array();
    $admins_arr["records"] = array();


    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // extract row
        // this will make $row['name'] to
        // just $name only
        extract($row);

        $admin_item = array(
            "id" => $id,
            "username" => $username,
        );
        array_push($admins_arr["records"], $admin_item);
    }

    // set response code - 200 OK
    http_response_code(200);

    // show  in json format
    echo json_encode($admins_arr);
} else {

    // set response code - 404 Not found
    http_response_code(404);


    echo json_encode(
        array("message" => "No admins found.")
    );
}
