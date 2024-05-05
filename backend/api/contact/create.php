<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Authorization, Content-Type');

    // Includes
    include_once '../../models/Contact.php';
    include_once '../../helpers/auth_check.php';
    include_once '../../helpers/database_connect.php';

    // Prevent CORS error by returning status code 200 for OPTIONS preflight requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    // Check user authentication first
    checkAuthentication();

    // Instantiate DB & connect
    $db = connectToDatabase();

    $contact = new Contact($db);

    $data = json_decode(file_get_contents("php://input"));

    $contact->firstname = $data->firstname;
    $contact->lastname = $data->lastname;
    $contact->description = $data->description;
    $contact->phoneNumber = $data->phoneNumber;
    $contact->emailAddress = $data->emailAddress;
    $contact->image = $data->image;

    // Create item and get ID
    $createdContactID = $contact->create();

    // Return data of created item
    $createdContact = new Contact($db);
    $createdContact->id = $createdContactID;
    $createdContact->read_single();

    $contact_array = array(
        'id' => $createdContact->id,
        'firstname' => $createdContact->firstname,
        'lastname' => $createdContact->lastname,
        'description' => $createdContact->description,
        'phoneNumber' => $createdContact->phoneNumber,
        'emailAddress' => $createdContact->emailAddress,
        'image' => $createdContact->image,
    );

    // Return item as JSON
    print_r(json_encode($contact_array));