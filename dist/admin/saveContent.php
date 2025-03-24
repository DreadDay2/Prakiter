<?php
    header('Content-Type: application/json');
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
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
    
        if (!empty($data['content'])) {
            $content = $data['content'];
    
            $query_prompt = "INSERT INTO content(content) VALUES('$content')";
            $query = mysqli_query($connection, $query_prompt);
            mysqli_close($connection);
    
            echo json_encode(['status' => 'success', 'message' => 'Content saved successfully.']);
        } 
        else {
            echo json_encode(['status' => 'error', 'message' => 'No content provided.']);
        }
    } 
    else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    }
    mysqli_close($connection); 
?>