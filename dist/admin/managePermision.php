<?php
    session_start();
    require_once 'config.php';
    $connection = mysqli_connect($db_link, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno() > 1){
        echo "Some issue:".mysqli_connect_error();
    }
    $query_prompt = "SELECT id, downloading FROM users";
    $query = mysqli_query($connection, $query_prompt);
    mysqli_close($connection);

    $result = mysqli_fetch_assoc($query);
    $permision = $result['downloading'];
    $checkbox = $_POST['permision'];
    if($permision == 'NO' && isset($checkbox)){
        $_SESSION['prohibited'] = true;
    }
    header('Location: admin.php');
?>