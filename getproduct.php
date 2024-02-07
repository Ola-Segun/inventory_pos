<?php

        // include_once 'connectdb.php';

        // $id = $_GET['id'];

        // $select = $pdo->prepare("SELECT * FROM tbl_product WHERE id = :ppid");
        // $select->bindParam(':ppid', $id);

        // $select->execute();

        // $row = $select->fetch(PDO::FETCH_ASSOC);

        // header('Content-Type: application/json');

        // echo json_encode($row);
        ?>

<?php
include_once 'connectdb.php';

$id = $_GET['id'];

$select = $pdo->prepare("SELECT * FROM tbl_product WHERE id = :id");
$select->bindParam(':id', $id);

if ($select->execute()) {
    $row = $select->fetch(PDO::FETCH_ASSOC);
    header('Content-Type: application/json');
    echo json_encode($row);
} else {
    // Handle database query errors here
    echo json_encode(['error' => 'Database query error']);
}
?>