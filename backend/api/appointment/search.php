<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Appointment.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    $appointment = new Appointment($db);

    $appointment->searchQuery = isset($_GET['search_query']) ? $_GET['search_query'] : "";
    $appointment->limit = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 5;
    $appointment->offset = isset($_GET['page']) ? $appointment->limit * ($_GET['page'] - 1) : 0;

    $result = $appointment->search();
    $num = $result->rowCount();

    if($num > 0) {
        $posts_arr = array();
        $posts_arr['items'] = array();

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
    } else {
        // No Items
        echo json_encode(
            array('message' => 'No Items Found')
        );
    }