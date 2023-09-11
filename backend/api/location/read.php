<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Location.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Get Items
    $location = new Location($db);

    $location->limit = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    $location->offset = isset($_GET['page']) ? $location->limit * ($_GET['page'] - 1) : 0;

    $result = $location->read();
    $num = $result->rowCount();

    // Get Total Items Number
    $totalItems = $location->count();

    if($num > 0) {
        $posts_arr = array();
        $posts_arr['items'] = array();
        $posts_arr['totalItems'] = $totalItems;

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $post_item = array(
                'id' => $id,
                'name' => $name,
                'description' => $description,
                'streetAddress' => $street_address,
                'postalCode' => $postal_code,
                'city' => $city
            );

            array_push($posts_arr['items'], $post_item);
        }

        echo json_encode($posts_arr);
    } else {
        // No Items
        echo json_encode(
            array('message' => 'No Items Found')
        );
    }