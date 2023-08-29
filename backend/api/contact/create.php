<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Origin, Content-Type, 
    Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Contact.php';

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

    if ($contact->create()) {
        echo json_encode(
            array('message' => 'Contact Created')
        );
    } else {
        echo json_encode(
            array('message' => 'Contact Not Created')
        );
    }