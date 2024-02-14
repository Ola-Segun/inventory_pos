<?php

include_once 'connectdb.php';

session_start();

if ($_SESSION['useremail'] == "" or $_SESSION['userrole'] == "User") {
    header('location:index.php');
}

$id = $_POST['pidd'];



$sql = "delete from ". $_SESSION['tbl'] ." where id=$id";

$delete = $pdo->prepare($sql);

if($delete->execute()){

} else{
    echo 'Error in deleting';
}

?>