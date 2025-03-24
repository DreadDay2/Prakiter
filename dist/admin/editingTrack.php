<?php
    session_start();
    require_once 'config.php';
    $connection = mysqli_connect($db_link, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno() > 1){
        echo "Some issue:".mysqli_connect_error();
    }

    $validToken = false;
    if (isset($_COOKIE['auth_token'])) {
        $token = $_COOKIE['auth_token'];
        $tokenQuery = "SELECT * FROM users WHERE token = '$token' AND isAdmin = 1";
        $tokenResult = mysqli_query($connection, $tokenQuery);
    
        if ($tokenResult && mysqli_num_rows($tokenResult) > 0) {
            $validToken = true;
        } else {
            header('Location: ../index.html');
            exit();
        }
    } else {
        header('Location: ../index.html');
        exit();
    }

    if (isset($_POST['song_id'])) {
        $song_id = $_POST['song_id'];
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $new_performer = htmlentities($_POST['new_performer']);
        $new_title = htmlentities($_POST['new_title']);
        $new_cover_link = $_POST['new_cover_link'];
        $new_link = htmlentities($_POST['new_link']);
        $query_prompt = "UPDATE songs SET performer = '".$new_performer."', title = '".$new_title."', cover_link = '".$new_cover_link."', link = '".$new_link."' WHERE id = ".$song_id;
        $query = mysqli_query($connection, $query_prompt);
        header('Location: manageTracks.php');
        mysqli_close($connection);
    }
?>
