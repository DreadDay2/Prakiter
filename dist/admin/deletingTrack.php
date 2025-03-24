<?php
    session_start();
    require_once 'config.php';
    $connection = mysqli_connect($db_link, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno() > 1){
        echo "Some issue:".mysqli_connect_error();
    }
    else{

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

        $id = $_GET['id'];
        $query = mysqli_query($connection, "DELETE FROM songs WHERE id =".$id);
        $_SESSION['deleted_song'] = true; 
        header('Location: manageTracks.php');
        mysqli_close($connection);
    }
?>