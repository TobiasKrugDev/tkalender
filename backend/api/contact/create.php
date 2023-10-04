<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, 
    Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Contact.php';
    include_once '../../helpers/auth_check.php';

    // Check user authentication first
    checkAuthentication();

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    $contact = new Contact($db);

    $data = json_decode(file_get_contents("php://input"));

    $contact->firstname = $data->firstname;
    $contact->lastname = $data->lastname;
    $contact->description = $data->description;
    $contact->phoneNumber = $data->phoneNumber;
    $contact->emailAddress = $data->emailAddress;
    $contact->image = $data->image;

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

    print_r(json_encode($contact_array));