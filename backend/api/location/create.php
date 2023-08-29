<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, 
    Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Location.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    $location = new Location($db);

    $data = json_decode(file_get_contents("php://input"));

    $location->name = $data->name;
    $location->description = $data->description;
    $location->streetAddress = $data->streetAddress;
    $location->postalCode = $data->postalCode;
    $location->city = $data->city;

    if ($location->create()) {
        echo json_encode(
            array('message' => 'Location Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Location Not Created')
        );
    }