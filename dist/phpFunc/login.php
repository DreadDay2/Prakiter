<?php
require_once '../admin/config.php';
session_start();
$connection = new mysqli($db_link, $db_user, $db_password, $db_name);

if ($connection->connect_error) {
    die("Błąd połączenia: " . $connection->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $user = $connection->real_escape_string($_POST['login']);
    $pass = $_POST['password'];


    $query = "SELECT * FROM users WHERE login = '$user'";
    $result = $connection->query($query);

    if ($result && $result->num_rows > 0) {
        $userRecord = $result->fetch_assoc();

        if (password_verify($pass, $userRecord['password'])) {
            $token = bin2hex(random_bytes(16));

            $updateQuery = "UPDATE users SET token = '$token' WHERE login = '$user'";
            $connection->query($updateQuery);

            setcookie('auth_token', $token, time() + (86400 * 7), '/', '', false, true);

            $_SESSION['loggedIn'] = true; // boolean czy jest zalogowany czy nie
            $_SESSION['ULogin'] = $user; // nazwa uzytkownika do sesji
            
            header('Location: ../songs.php');
            exit();
        } else {
            header('Location: ../login.html');
            exit();
        }
    } else {
        header('Location: ../login.html');
        exit();
    }
}
?>
