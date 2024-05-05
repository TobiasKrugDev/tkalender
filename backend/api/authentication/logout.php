<?php
    // Header
    header('Access-Control-Allow-Origin: *');

    // ToDo: Add logout token logic if needed

    // Return logout confirmation
    echo json_encode(
        array('success' => true)
    );