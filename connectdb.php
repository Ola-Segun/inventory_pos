<?php

try{
    $pdo = new PDO('mysql:host=localhost;dbname=retrive', 'root', '');
    // echo "Connection Checked";
} catch(PDOException $f){
    echo $f->getmessage();
}

?>