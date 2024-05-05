<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Authorization, Content-Type');

    // Includes
    include_once '../../models/Category.php';
    include_once '../../helpers/auth_check.php';
    include_once '../../helpers/database_connect.php';

    // Check user authentication first
    checkAuthentication();

    // Instantiate DB & connect
    $db = connectToDatabase();

    $category = new Category($db);

    $data = json_decode(file_get_contents("php://input"));

    $category->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Delete item
    $category->delete();

    // Return success message as JSON
    echo json_encode(
        array('message' => 'Category Deleted')
    );