<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Includes
    include_once '../../models/Appointment.php';
    include_once '../../helpers/auth_check.php';
    include_once '../../helpers/database_connect.php';

    // Check user authentication first
    checkAuthentication();

    // Instantiate DB & connect
    $db = connectToDatabase();

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

    // Return item as JSON
    print_r(json_encode($appointment_array));