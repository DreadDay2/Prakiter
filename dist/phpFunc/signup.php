<?php
require_once '../admin/config.php';
session_start();
$connection = new mysqli($db_link, $db_user, $db_password, $db_name);

if ($connection->connect_error) {
    die("Błąd połączenia: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user = $connection->real_escape_string($_POST['login']);
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Check if user already exists
    $checkQuery = "SELECT * FROM users WHERE login = '$user'";
    $checkResult = $connection->query($checkQuery);

    if ($checkResult && $checkResult->num_rows > 0) {
        $_SESSION['error'] = "Błąd rejestracji: Ten użytknownik już istnieje.";
        header('Location: ../signup.php');
        exit();
    } else {
        $insertQuery = "INSERT INTO users (login, password) VALUES ('$user', '$pass')";

        if ($connection->query($insertQuery)) {
            header('Location: ../login.html');
            exit();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Registration failed']);
        }
    }
}
?>