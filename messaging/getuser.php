
<?php
include_once 'connectdb.php';

// $id = $_GET['id'];

$select = $pdo->prepare("SELECT username FROM tbl_user");
// $select->bindParam(':id', $id);

if ($select->execute()) {
    $row = $select->fetch(PDO::FETCH_OBJ);
    header('Content-Type: application/json');
    echo json_encode($row);
} else {
    // Handle database query errors here
    echo json_encode(['error' => 'Database query error']);
}
?>