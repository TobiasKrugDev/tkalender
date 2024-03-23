<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Authorization, Content-Type');

    // Includes
    include_once '../../config/Database.php';
    include_once '../../models/Appointment.php';
    include_once '../../models/Contact.php';
    include_once '../../helpers/auth_check.php';

    // Check user authentication first
    checkAuthentication();

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
    $appointment->contacts = $data->contacts;
    $appointment->icon = $data->icon;

    // Create item and get ID
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

    // Fill contacts array
    $appointment_array['contacts'] = array();

    $contact = new Contact($db);
    $contact->appointmentFilter = $createdAppointment->id;
    $contactsResult = $contact->read();

    while($contactRow = $contactsResult->fetch(PDO::FETCH_ASSOC)) {
        extract($contactRow);
        $contact_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'description' => $description,
            'phoneNumber' => $phone_number,
            'emailAddress' => $email_address,
            'image' => $image,
        );

        array_push($appointment_array['contacts'], $contact_item);
    }

    // Return items as JSON
    print_r(json_encode($appointment_array));