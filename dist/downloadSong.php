<?php
session_start();
require_once 'admin/config.php';
$connection = mysqli_connect($db_link, $db_user, $db_password, $db_name);
if(mysqli_connect_errno() > 1){
    echo "Some issue:".mysqli_connect_error();
}

// Pobranie ID utworu
$song_id = isset($_GET['song_id']) ? intval($_GET['song_id']) : 0;

// Sprawdzenie czy utwór istnieje w bazie
$query = "SELECT link FROM songs WHERE id = $song_id";
$result = $connection->query($query);

if ($result && $row = $result->fetch_assoc()) {
    $file_path = $row['link'];

    // Zwiększenie licznika pobrań
    $connection->query("UPDATE songs SET downloads = downloads + 1 WHERE id = $song_id");

    // Ustawienie nagłówków do pobrania pliku
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    header('Content-Length: ' . filesize($file_path));
    readfile($file_path);
    exit;
} 
else {
    echo "Plik nie znaleziony.";
}
$connection->close();
?>
