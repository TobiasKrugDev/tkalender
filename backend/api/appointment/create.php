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

    $appointment->name = $data->name;
    $appointment->description = $data->description;
    $appointment->startAt = $data->startAt;
    $appointment->endAt = $data->endAt;
    $appointment->location = $data->location;
    $appointment->category = $data->category;
    $appointment->icon = $data->icon;

    $createdAppointmentID = $appointment->create();

    // Return data of created item
    $createdAppointment = new Appointment($db);
    $createdAppointment->id = $createdAppointmentID;
    $createdAppointment->read_single();

    $appointment_array = array(
        'id' => $createdAppointment->id,
        'name' => $createdAppointment->name,
        'description' => $createdAppointment->description,
        'startAt' => $createdAppointment->startAt,
        'endAt' => $createdAppointment->endAt,
        'location' => $createdAppointment->location,
        'category' => $createdAppointment->category,
        'icon' => $createdAppointment->icon,
    );

    print_r(json_encode($appointment_array));