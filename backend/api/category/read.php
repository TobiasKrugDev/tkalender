<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Get Items
    $category = new Category($db);

    $category->limit = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    $category->offset = isset($_GET['page']) ? $category->limit * ($_GET['page'] - 1) : 0;
    $category->sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'id';
    $category->orderDirection = isset($_GET['orderDirection']) ? $_GET['orderDirection'] : 'ASC';
    $category->searchQuery = isset($_GET['filter']) ? $_GET['filter'] : '';

    $result = $category->read();
    $num = $result->rowCount();

    // Get Total Items Number
    $totalItems = $category->count();

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
                'color' => $color,
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