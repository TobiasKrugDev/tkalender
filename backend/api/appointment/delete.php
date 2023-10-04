<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, 
    Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Appointment.php';
    include_once '../../helpers/auth_check.php';

    // Check user authentication first
    checkAuthentication();

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    $appointment = new Appointment($db);

    $data = json_decode(file_get_contents("php://input"));

    $appointment->id = isset($_GET['id']) ? $_GET['id'] : die();

    if ($appointment->delete()) {
        echo json_encode(
            array('message' => 'Appointment Deleted')
        );
    } else {
        echo json_encode(
            array('message' => 'Appointment Not Deleted')
        );
    }