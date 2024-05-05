<?php
    include_once '../../vendor/autoload.php';
    include_once '../../config/jwt_secret.php';
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    function checkAuthentication() {
        $token = $_GET['token'];
        
        if (!$token) {
            denyAccess();
        }

        // ToDo: Add check for token expiration
        try {
            JWT::decode($token, new Key($GLOBALS['jwt_secret'], 'HS256'));
        } catch (Exception $e) {
            denyAccess();
        }
    }

    function denyAccess() {
        http_response_code(401);
        echo json_encode(
            array('message' => 'Access not granted')
        );
        exit;
    }