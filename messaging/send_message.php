
<?php
include_once 'connectdb.php';

$username = $_GET['username'];
$message = $_GET['message'];


$insert = $pdo->prepare("INSERT INTO tbl_message 
(sent_from, msg_body, sent_to, msg_type)
values(:sender, :message_body, :receiver, :message_type)");

$insert->bindParam(':sender', $username);
$insert->bindParam(':message_body', $message);

if ($insert->execute()) {
    $row = $insert->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($row);
} else {
    // Handle database query errors here
    echo json_encode(['error' => 'Database query error']);
}
?>