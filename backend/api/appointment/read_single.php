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


    $appointment->id = isset($_GET['id']) ? $_GET['id'] : die();
    $appointment->read_single();

    $appointment_array = array(
        'id' => $appointment->id,
        'name' => $appointment->name,
        'description' => $appointment->description,
        'startAt' => $appointment->startAt,
        'endAt' => $appointment->endAt,
        'location' => $appointment->location,
        'category' => $appointment->category,
        'icon' => $appointment->icon,
    );

    print_r(json_encode($appointment_array));