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

    $location->id = isset($_GET['id']) ? $_GET['id'] : die();
    $location->name = $data->name;
    $location->description = $data->description;
    $location->streetAddress = $data->streetAddress;
    $location->postalCode = $data->postalCode;
    $location->city = $data->city;

    $location->update();

    // Return updated item
    $location->read_single();

    $location_array = array(
        'id' => $location->id,
        'name' => $location->name,
        'description' => $location->description,
        'streetAddress' => $location->streetAddress,
        'postalCode' => $location->postalCode,
        'city' => $location->city,
    );

    print_r(json_encode($location_array));