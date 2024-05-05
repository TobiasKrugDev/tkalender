<?php
    include_once '../../config/Database.php';
    include_once '../../vendor/autoload.php';
    include_once '../../config/jwt_secret.php';
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    // ToDo: Error Handling
    function connectToDatabase() {
        // Get username from JWT
        $token = $_GET['token'];

        $userData = JWT::decode($token, new Key($GLOBALS['jwt_secret'], 'HS256'));
        $username = $userData->sub;

        // Get user db_hash JWT from user database
        $database = new Database();
        $db = $database->connect(null);

        $query = 'SELECT * FROM users WHERE username = ?';
        $stmt = $db->prepare($query);
        $stmt->bindParam(1, $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $dbHash = $user["db_hash"];

        // Get database access data from JWT and pass it on
        $dbData = JWT::decode($dbHash, new Key($GLOBALS['jwt_secret'], 'HS256'));

        return $database->connect($dbData);
    }