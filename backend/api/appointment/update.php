<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, 
    Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Appointment.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    $appointment = new Appointment($db);

    $data = json_decode(file_get_contents("php://input"));

    $appointment->id = $data->id;
    $appointment->name = $data->name;
    $appointment->description = $data->description;
    $appointment->startAt = $data->startAt;
    $appointment->endAt = $data->endAt;
    $appointment->location = $data->location;
    $appointment->category = $data->category;
    $appointment->icon = $data->icon;

    if ($appointment->update()) {
        echo json_encode(
            array('message' => 'Appointment Updated')
        );
    } else {
        echo json_encode(
            array('message' => 'Appointment Not Updated')
        );
    }