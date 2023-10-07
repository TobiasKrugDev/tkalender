<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Location.php';
    include_once '../../helpers/auth_check.php';

    // Check user authentication first
    checkAuthentication();

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Get Items
    $location = new Location($db);

    $location->limit = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    $location->offset = isset($_GET['page']) ? $location->limit * ($_GET['page'] - 1) : 0;
    $location->sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'id';
    $location->orderDirection = isset($_GET['orderDirection']) ? $_GET['orderDirection'] : 'ASC';
    $location->searchQuery = isset($_GET['filter']) ? $_GET['filter'] : '';

    $result = $location->read();
    $num = $result->rowCount();

    // Get Total Items Number
    $totalItems = $location->count();

    $location_array = array();
    $location_array['items'] = array();
    $location_array['totalItems'] = $totalItems;

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $location_item = array(
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'streetAddress' => $street_address,
            'postalCode' => $postal_code,
            'city' => $city
        );

        array_push($location_array['items'], $location_item);
    }

    echo json_encode($location_array);