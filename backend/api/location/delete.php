<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, 
    Access-Control-Allow-Methods, Authorization, X-Requested-With');

    // Includes
    include_once '../../config/Database.php';
    include_once '../../models/Location.php';
    include_once '../../helpers/auth_check.php';

    // Check user authentication first
    checkAuthentication();

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    $location = new Location($db);

    $data = json_decode(file_get_contents("php://input"));

    $location->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Delete item
    $location->delete();

    // Return success message as JSON
    echo json_encode(
        array('message' => 'Location Deleted')
    );