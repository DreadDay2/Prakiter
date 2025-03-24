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

    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $new_login = htmlentities($_POST['new_login']);
        $new_permission = htmlentities($_POST['new_permission']);
        if($new_permission == "TAK"){
            $new_permission = "YES";
        }
        else if($new_permission == "NIE"){
            $new_permission = "NO";
        }
        $query_prompt = "UPDATE users SET login = '".$new_login."', downloading = '".$new_permission."' WHERE id = ".$user_id;
        $query = mysqli_query($connection, $query_prompt);
        header('Location: manageUsers.php');
        mysqli_close($connection);
    }
?>
