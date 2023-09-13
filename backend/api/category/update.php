<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, 
    Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);

    $data = json_decode(file_get_contents("php://input"));

    $category->id = isset($_GET['id']) ? $_GET['id'] : die();
    $category->name = $data->name;
    $category->description = $data->description;
    $category->color = $data->color;

    $category->update();

    // Return updated item
    $category->read_single();

    $category_array = array(
        'id' => $category->id,
        'name' => $category->name,
        'description' => $category->description,
        'color' => $category->color
    );

    print_r(json_encode($category_array));