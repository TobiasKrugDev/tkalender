<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Appointment.php';
    include_once '../../models/Contact.php';
    include_once '../../helpers/auth_check.php';

    // Check user authentication first
    checkAuthentication();

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    // Get Items
    $appointment = new Appointment($db);

    $appointment->contactFilter = isset($_GET['filter_contact']) ? $_GET['filter_contact'] : null;
    $appointment->locationFilter = isset($_GET['filter_location']) ? $_GET['filter_location'] : null;
    $appointment->categoryFilter = isset($_GET['filter_category']) ? $_GET['filter_category'] : null;
    $appointment->limit = isset($_GET['itemsPerPage']) ? $_GET['itemsPerPage'] : 10;
    $appointment->offset = isset($_GET['page']) ? $appointment->limit * ($_GET['page'] - 1) : 0;
    $appointment->sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : 'id';
    $appointment->orderDirection = isset($_GET['orderDirection']) ? $_GET['orderDirection'] : 'ASC';
    $appointment->timespanStart = isset($_GET['futureAppointmentsOnly']) && $_GET['futureAppointmentsOnly'] == true ? date("Y-m-d H:i:s") : "1970-01-01 00:00:00";
    $appointment->searchQuery = isset($_GET['filter']) ? $_GET['filter'] : '';
    $appointment->calendarMode = isset($_GET['calendarMode']) ? $_GET['calendarMode'] : false;
    $appointment->calendarTimespanStart = isset($_GET['calendarTimespanStart']) ? $_GET['calendarTimespanStart'] : date("Y-m-d H:i:s");
    $appointment->calendarTimespanEnd = isset($_GET['calendarTimespanEnd']) ? $_GET['calendarTimespanEnd'] : date("Y-m-d H:i:s");

    $result = $appointment->read();

    // Get Total Items Number
    $totalItems = $appointment->count();

    $appointment_array = array();
    $appointment_array['items'] = array();
    $appointment_array['totalItems'] = $totalItems;

    $isEntityFilterSet = isset($appointment->contactFilter) || isset($appointment->locationFilter) || isset($appointment->categoryFilter);

    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        if ($isEntityFilterSet) {
            $appointment_item = array(
                'id' => $id,
                'name' => $name,
                'startAt' => $startAt,
            );
        } else {
            $appointment_item = array(
                'id' => $id,
                'name' => $appointmentName,
                'description' => $appointmentDescription,
                'startAt' => $startAt,
                'endAt' => $endAt,
                'icon' => $icon
            );

            if (isset($locationID)) {
                $appointment_item['location'] = array(
                    'id' => $locationID,
                    'name' => $locationName,
                    'description' => $locationDescription,
                    'streetAddress' => $streetAddress,
                    'postalCode' => $postalCode,
                    'city' => $city
                );
            } else {
                $appointment_item['location'] = null;
            }

            if (isset($categoryID)) {
                $appointment_item['category'] = array(
                    'id' => $categoryID,
                    'name' => $categoryName,
                    'description' => $categoryDescription,
                    'color' => $color
                );
            } else {
                $appointment_item['category'] = null;
            }

            $appointment_item['contacts'] = array();

            $contact = new Contact($db);
            $contact->appointmentFilter = $id;
            $contactsResult = $contact->read();

            while($contactRow = $contactsResult->fetch(PDO::FETCH_ASSOC)) {
                extract($contactRow);
                $contact_appointment_item = array(
                    'id' => $id,
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'description' => $description,
                    'phoneNumber' => $phone_number,
                    'emailAddress' => $email_address,
                    'image' => $image,
                );

                array_push($appointment_item['contacts'], $contact_appointment_item);
            }
        }

        array_push($appointment_array['items'], $appointment_item);

    }

    echo json_encode($appointment_array);