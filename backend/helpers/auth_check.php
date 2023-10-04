<?php
    function checkAuthentication() {
        session_start();
        // Check if user session is active
        if (!isset($_SESSION["user_id"])) {
            // User is not logged in --> return 401 status code with error message and exit script
            http_response_code(401);
            echo json_encode(
                array('message' => 'Access not granted')
            );
            exit;
        }
    }