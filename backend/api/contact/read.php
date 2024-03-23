<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    // Includes
    include_once '../../config/Database.php';
    include_once '../../models/Contact.php';
    include_once '../../helpers/auth_check.php';

    // Check user authentication first
    checkAuthentication($_GET['sessionID']);

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Get Items
    $contact = new Contact($db);

    $contact->appointmentFilter = isset($_GET['filter_appointment']) ? $_GET['filter_appointment'] : null;
    $contact->limit = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    $contact->offset = isset($_GET['page']) ? $contact->limit * ($_GET['page'] - 1) : 0;
    $contact->sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'id';
    $contact->orderDirection = isset($_GET['orderDirection']) ? $_GET['orderDirection'] : 'ASC';
    $contact->searchQuery = isset($_GET['filter']) ? $_GET['filter'] : '';

    $result = $contact->read();
    $num = $result->rowCount();

    // Get Total Items Number
    $totalItems = $contact->count();

    $contact_array = array();
    $contact_array['items'] = array();
    $contact_array['totalItems'] = $totalItems;

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $contact_item = array(
            'id' => $id,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'description' => $description,
            'phoneNumber' => $phone_number,
            'emailAddress' => $email_address,
            'image' => $image,
        );

        array_push($contact_array['items'], $contact_item);
    }

    // Return items as JSON
    echo json_encode($contact_array);