<?php

include_once 'connectdb.php';

session_start();

if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "User") {
    header('location:index.php');
}

$id = $_POST['pidd'];



$sql = "delete from ". $_SESSION['tbl'] ." where invoice_id=$id";

$delete = $pdo->prepare($sql);

$sql_1 = "delete from ". $_SESSION['tbl_1'] ." where invoice_id=$id";

$delete_1 = $pdo->prepare($sql_1);

if($delete->execute() && $delete_1->execute()){

} else{
    echo 'Error in deleting';
}

?>