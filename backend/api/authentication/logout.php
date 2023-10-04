<?php
    session_start();
    // Remove session cookie and destroy session
    $params = session_get_cookie_params();
    setcookie(session_name(), '', 0, $params['path'], $params['domain'], $params['secure'], isset($params['httponly']));
    session_destroy();

    // Return logout confirmation
    echo json_encode(
        array('success' => true)
    );