<?php
    // Header
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Authorization, Content-Type');

    // Includes
    include_once '../../config/Database.php';

    // Prevent CORS error by returning status code 200 for OPTIONS preflight requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        http_response_code(200);
        exit;
    }

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    $data = json_decode(file_get_contents("php://input"));

    $username = htmlspecialchars(strip_tags($data->username));
    $password = htmlspecialchars(strip_tags($data->password));

    $query = 'SELECT * FROM users WHERE username = ?';
    $stmt = $db->prepare($query);
    $stmt->bindParam(1, $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $isValid = password_verify($password, $user["password_hash"]);

        if ($isValid) {
            session_start();
            session_regenerate_id();
            $_SESSION["user_id"] = $user["id"];

            // Valid user credentials --> return status code 200
            http_response_code(200);
            echo json_encode(
                array('success' => true)
            );
        } else {
            // Invalid password -->  return status code 401
            http_response_code(401);
        }
    } else {
        // Not a valid user --> return status code 401
        http_response_code(401);
    }