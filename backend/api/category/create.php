<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, 
    Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // Includes
    include_once '../../config/Database.php';
    include_once '../../models/Category.php';
    include_once '../../helpers/auth_check.php';

    // Check user authentication first
    checkAuthentication();

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    $category = new Category($db);

    $data = json_decode(file_get_contents("php://input"));

    $category->name = $data->name;
    $category->description = $data->description;
    $category->color = $data->color;

    // Create item and get ID
    $createdCategoryID = $category->create();

    // Return data of created item
    $createdCategory = new Category($db);
    $createdCategory->id = $createdCategoryID;
    $createdCategory->read_single();

    $category_array = array(
        'id' => $createdCategory->id,
        'name' => $createdCategory->name,
        'description' => $createdCategory->description,
        'color' => $createdCategory->color
    );

    // Return item as JSON
    print_r(json_encode($category_array));