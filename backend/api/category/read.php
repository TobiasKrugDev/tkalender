<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Includes
    include_once '../../config/Database.php';
    include_once '../../models/Category.php';
    include_once '../../helpers/auth_check.php';

    // Check user authentication first
    checkAuthentication();

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

    // Get Total Items Number
    $totalItems = $category->count();

    $category_array = array();
    $category_array['items'] = array();
    $category_array['totalItems'] = $totalItems;

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $category_item = array(
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'color' => $color,
        );

        array_push($category_array['items'], $category_item);
    }

    // Return items as JSON
    echo json_encode($category_array);