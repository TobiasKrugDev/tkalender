<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Appointment.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Get Items
    $appointment = new Appointment($db);

    $appointment->contactFilter = isset($_GET['filter_contact']) ? $_GET['filter_contact'] : null;
    $appointment->locationFilter = isset($_GET['filter_location']) ? $_GET['filter_location'] : null;
    $appointment->limit = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    $appointment->offset = isset($_GET['page']) ? $appointment->limit * ($_GET['page'] - 1) : 0;

    $result = $appointment->read();
    $num = $result->rowCount();

    // Get Total Items Number
    $totalItems = $appointment->count();

    $posts_arr = array();
    $posts_arr['items'] = array();
    $posts_arr['totalItems'] = $totalItems;

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $post_item = array(
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'startAt' => $startAt,
            'endAt' => $endAt,
            'location' => $location,
            'category' => $category,
            'icon' => $icon
        );

        array_push($posts_arr['items'], $post_item);
    }

    echo json_encode($posts_arr);