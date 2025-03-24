<?php
require_once 'config.php';
$connection = mysqli_connect($db_link, $db_user, $db_password, $db_name);
if(mysqli_connect_errno() > 1){
    echo "Some issue:".mysqli_connect_error();
}

$last_update = isset($_GET['last_update']) ? $_GET['last_update'] : '1970-01-01 00:00:00';

while (true) {
    $query_prompt = "SELECT content, created_at FROM content ORDER BY created_at DESC LIMIT 1";
    $query = mysqli_query($connection, $query_prompt);
    $result = mysqli_fetch_assoc($query);
    if ($result && $result['created_at'] > $last_update) {
        echo json_encode([
            'status' => 'success',
            'content' => $result['content'],
            'created_at' => $result['created_at']
        ]);
        exit;
    } 
    else {
        echo json_encode(['status' => 'no_change']);
    }
    sleep(1);
}
mysqli_close($connection);
?>
