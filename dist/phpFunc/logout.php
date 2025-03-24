<?php
session_start();

if (isset($_SESSION['ULogin'])) {
    require_once '../admin/config.php';
    $connection = new mysqli($db_link, $db_user, $db_password, $db_name);

    if ($connection->connect_error) {
        die("Błąd połączenia: " . $connection->connect_error);
    }

    $user = $_SESSION['ULogin'];
    $updateQuery = "UPDATE users SET token = NULL WHERE login = ?";
    
    $stmt = $connection->prepare($updateQuery);
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $stmt->close();
    $connection->close();
}

// niszczy wszystkie dane zawarte w sesji
$_SESSION = [];
session_unset();
session_destroy();

// usuwa cookie
setcookie('auth_token', '', time() - 3600, '/', '', false, true);

header('Location: ../login.html');
exit();
?>
