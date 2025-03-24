<?php
require_once 'config.php'; 
$connection = mysqli_connect($db_link, $db_user, $db_password, $db_name);

if (!$connection) {
    die("Błąd połączenia: " . mysqli_connect_error());
}

// Pobieramy dane: tytuł piosenki + liczba pobrań
$query = "SELECT title, downloads FROM songs ORDER BY downloads DESC";
$result = mysqli_query($connection, $query);

$data = [["Piosenka", "Liczba pobrań"]]; // Nagłówki dla Google Charts

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [$row['title'], (int)$row['downloads']];
}

mysqli_close($connection);

// Wysyłamy JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
