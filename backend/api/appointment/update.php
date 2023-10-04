<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, 
    Access-Control-Allow-Methods, Authorization, X-Requested-With');

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

    $appointment->id = isset($_GET['id']) ? $_GET['id'] : die();
    $appointment->name = $data->name;
    $appointment->description = $data->description;
    $appointment->startAt = $data->startAt;
    $appointment->endAt = $data->endAt;
    $appointment->location = $data->location;
    $appointment->category = $data->category;
    $appointment->contacts = $data->contacts;
    $appointment->icon = $data->icon;

    $appointment->update();

    // Return updated item
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

    // Fill contacts array
    $appointment_array['contacts'] = array();

    $contact = new Contact($db);
    $contact->appointmentFilter = $appointment->id;
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

    print_r(json_encode($appointment_array));