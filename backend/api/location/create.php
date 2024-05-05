<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Authorization, Content-Type');

    // Includes
    include_once '../../config/Database.php';
    include_once '../../models/Location.php';
    include_once '../../helpers/auth_check.php';

    // Prevent CORS error by returning status code 200 for OPTIONS preflight requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    // Check user authentication first
    checkAuthentication();

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

    // Create item and get ID
    $createdLocationID = $location->create();

    // Return data of created item
    $createdLocation = new Location($db);
    $createdLocation->id = $createdLocationID;
    $createdLocation->read_single();

    $location_array = array(
        'id' => $createdLocation->id,
        'name' => $createdLocation->name,
        'description' => $createdLocation->description,
        'streetAddress' => $createdLocation->streetAddress,
        'postalCode' => $createdLocation->postalCode,
        'city' => $createdLocation->city,
    );

    // Return item as JSON
    print_r(json_encode($location_array));