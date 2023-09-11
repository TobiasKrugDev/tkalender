<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Contact.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Get Items
    $contact = new Contact($db);

    $contact->appointmentFilter = isset($_GET['filter_appointment']) ? $_GET['filter_appointment'] : null;
    $contact->limit = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    $contact->offset = isset($_GET['page']) ? $contact->limit * ($_GET['page'] - 1) : 0;

    $result = $contact->read();
    $num = $result->rowCount();

    // Get Total Items Number
    $totalItems = $contact->count();

    if($num > 0) {
        $posts_arr = array();
        $posts_arr['items'] = array();
        $posts_arr['totalItems'] = $totalItems;

        while($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $post_item = array(
                'id' => $id,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'description' => $description,
                'phoneNumber' => $phone_number,
                'emailAddress' => $email_address,
                'image' => $image,
            );

            array_push($posts_arr['items'], $post_item);
        }

        echo json_encode($posts_arr);
    } else {
        // No Items
        echo json_encode(
            array('message' => 'No Items Found')
        );
    }