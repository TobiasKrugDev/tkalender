<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Includes
    include_once '../../models/Category.php';
    include_once '../../helpers/auth_check.php';
    include_once '../../helpers/database_connect.php';

    // Check user authentication first
    checkAuthentication();

    // Instantiate DB & connect
    $db = connectToDatabase();

    $category = new Category($db);

    $category->id = isset($_GET['id']) ? $_GET['id'] : die();
    $category->read_single();

    $category_array = array(
        'id' => $category->id,
        'name' => $category->name,
        'description' => $category->description,
        'color' => $category->color
    );

    // Return item as JSON
    print_r(json_encode($category_array));