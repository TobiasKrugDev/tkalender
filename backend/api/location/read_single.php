<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Includes
    include_once '../../config/Database.php';
    include_once '../../models/Location.php';
    include_once '../../helpers/auth_check.php';

    // Check user authentication first
    checkAuthentication();

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    $location = new Location($db);

    $location->id = isset($_GET['id']) ? $_GET['id'] : die();
    $location->read_single();

    $location_array = array(
        'id' => $location->id,
        'name' => $location->name,
        'description' => $location->description,
        'streetAddress' => $location->streetAddress,
        'postalCode' => $location->postalCode,
        'city' => $location->city,
    );

    // Return item as JSON
    print_r(json_encode($location_array));