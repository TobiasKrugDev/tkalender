<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Location.php';

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

    print_r(json_encode($location_array));