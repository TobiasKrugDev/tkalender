<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Includes
    include_once '../../config/Database.php';
    include_once '../../models/Contact.php';
    include_once '../../helpers/auth_check.php';

    // Check user authentication first
    checkAuthentication();

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    $contact = new Contact($db);

    $contact->id = isset($_GET['id']) ? $_GET['id'] : die();
    $contact->read_single();

    $contact_array = array(
        'id' => $contact->id,
        'firstname' => $contact->firstname,
        'lastname' => $contact->lastname,
        'description' => $contact->description,
        'phoneNumber' => $contact->phoneNumber,
        'emailAddress' => $contact->emailAddress,
        'image' => $contact->image,
    );

    // Return item as JSON
    print_r(json_encode($contact_array));