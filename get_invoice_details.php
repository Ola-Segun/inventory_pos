<?php

include_once 'connectdb.php';

session_start();

    $id = $_GET['id']; // Use the correct parameter name here
    $select = $pdo->prepare("SELECT * FROM tbl_invoice_details WHERE invoice_id = :id");
    $select->bindParam(':id', $id);
    $select->execute();
    
    while ($row = $select->fetch(PDO::FETCH_OBJ)) {
        echo '
            <tr>
                <td>' . $row->product_name . '</td>
                <td>' . $row->quantity . '</td>
                <td>' . $row->price . '</td>
                <td>' . $row->order_time . '</td>
                <td>' . $row->order_date . '</td>
            </tr>
        ';

        
    }
?>
