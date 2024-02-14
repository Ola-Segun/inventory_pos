<?php
include_once 'connectdb.php';

session_start();


if ($_SESSION['useremail'] == "" or $_SESSION['userrole'] == "User") {
    header('location:index.php');
}

// Title for each page (echoed all names in adminheader)
$_SESSION['pagetitle'] = 'Edit Order';


$_SESSION['tbl'] = 'tbl_product';
include_once 'adminheader.php';

$id = $_GET['id'];

$select_1 = $pdo->prepare("select * from tbl_invoice where invoice_id = $id");
$select_1->execute();

    $row_1 = $select_1->fetch(PDO::FETCH_ASSOC);

    $customername_db = $row_1["customer_name"];
    $subtotal_db = $row_1["subtotal"];
    $tax_db = $row_1["tax"];
    $discount_db = $row_1["discount"];
    $total_db = $row_1["total"];
    $paid_db = $row_1["paid"];
    $due_db = $row_1["due"];
    $paytype_db = $row_1["payment_type"];
    $ordertime_db = $row_1["order_time"];
    $orderdate_db = $row_1["order_date"];


$select_2 = $pdo->prepare("select * from tbl_invoice_details where invoice_id = $id");
$select_2->execute();

    $row2 = $select_2->fetch(PDO::FETCH_ASSOC);

    // $id_db = $row2["id"];
    // $invoiceid_db = $row2["invoice_id"];
    // $productid_db = $row2["product_id"];
    // $pname_db = $row2["product_name"];
    // $qty_db = $row2["quantity"];
    // $pprice_db = $row2["price"];
?>


<?php

function fill_products($pdo, $productid_db)
{
    $output = '';
    $select = $pdo->prepare("select * from tbl_product order by productname asc");
    $select->execute();

    $result = $select->fetchAll();

    foreach ($result as $row) {
        $output .= '<option value="' . $row["id"] . '"';

        if($productid_db == $row['id']){
            $output .= 'selected';
        }
        
        $output .='>' . $row["productname"] . '</option>';
    }

    return $output;
}

?>
<?php


function phpremoveCommas($text)
{
    // Use str_replace to globally replace commas with an empty string
    return str_replace(',', '', $text);
}

if (isset($_POST['btneditorder'])) {

    // Get Values from text fields and from arraies in variables
    $customer_name = $_POST['txtcustomername'];
    $order_date = date('Y-m-d', strtotime($_POST['orderdate']));
    $order_time = date('h:i:s A', strtotime($_POST['ordertime']));
    $subtotal = phpremoveCommas($_POST['txtsubtotal']);
    $tax = phpremoveCommas($_POST['txttax']);
    $discount = phpremoveCommas($_POST['txtdiscount']);
    $total = phpremoveCommas($_POST['txttotal']);
    $paid = phpremoveCommas($_POST['txtpaid']);
    $due = phpremoveCommas($_POST['txtdue']);
    $payment_type = $_POST['rb'];

    /////////////////////////////////////

    $arr_productid = $_POST['productid'];
    $arr_productname = $_POST['productname'];
    $arr_stock = $_POST['stock'];
    $arr_price = $_POST['price'];
    $arr_qty = $_POST['qty'];
    $arr_total = $_POST['total'];

    $select_invoice_details = $pdo->prepare("select * from tbl_invoice_details where invoice_id=$id");
    $select_invoice_details->execute();
    $row_invoice_details = $select_invoice_details->fetchAll();

    // print_r($arr_stock);

    
    // write update query for tbl_product stock.
    
    foreach($row_invoice_details as $item_invoice_details){

        $myproductstock = $arr_stock[0] + $item_invoice_details['quantity'];
        
        $updateproductstock = $pdo->prepare("update tbl_product set productstock=:pstockroduct where id='".$item_invoice_details['product_id']."'");
        
        $updateproductstock->bindParam(":pstockroduct", $myproductstock);
        
        $updateproductstock->execute();
    }


    // write delete query for tbl_invoice_details tables where invoice_id=$id

    $delete_invoice_details=$pdo->prepare("delete from tbl_invoice_details where invoice_id=$id");
    $delete_invoice_details->execute();
    
    
    // write update query for tbl_invoice table data.
    
    $update_invoice = $pdo->prepare("update tbl_invoice set
    customer_name=:name, order_date=:date, order_time=:time, subtotal=:stotal, tax=:tax, discount=:disc, 
    total=:total, paid=:paid, due=:due, payment_type=:ptype where invoice_id=$id");

    $update_invoice->bindParam(":name", $customer_name);
    $update_invoice->bindParam(":date", $order_date);
    $update_invoice->bindParam(":time", $order_time);
    $update_invoice->bindParam(":stotal", $subtotal);
    $update_invoice->bindParam(":tax", $tax);
    $update_invoice->bindParam(":disc", $discount);
    $update_invoice->bindParam(":total", $total);
    $update_invoice->bindParam(":paid", $paid);
    $update_invoice->bindParam(":due", $due);
    $update_invoice->bindParam(":ptype", $payment_type);

    $update_invoice->execute();


    // tbl_invoice_details insert query STARTS

    $invoice_id = $pdo->lastInsertId();

    if ($invoice_id != null) {
        for ($i = 0; $i < count($arr_productid); $i++) {

            // write select query for tbl_product table to get out stock value

            $selectpdt = $pdo->prepare("select * from tbl_product where id='".$arr_productid[$i]."'");
            $selectpdt->execute();

            while($rowpdt=$selectpdt->fetch(PDO::FETCH_OBJ)){
                $db_stock[$i] = $rowpdt->productstock;
            

                $rem_qty =$db_stock[$i] - $arr_qty[$i];

                if($rem_qty < 0){
                    echo '
            
                    <script type="text/javascript">
                    jQuery(function validation(){

                    swal.fire({
                    title: "Failed",
                    text: "Unable to update order",
                    icon: "danger",
                    button: "OK",
                    });

                    });
                    
                    </script>
                    
                    ';
                } else {

                    // write update query for tbl_product table to update value.
                    $update = $pdo->prepare("update tbl_product SET productstock='$rem_qty' where id ='$arr_productid[$i]'");

                    $update->execute();

                    echo '
            
                            <script type="text/javascript">
                            jQuery(function validation(){
        
                            swal.fire({
                            title: "Success",
                            text: "Order Updated Successfully",
                            icon: "success",
                            button: "OK",
                            });
        
                            });
                            
                            </script>
                            
                            ';

                }
        }
            

            $insert = $pdo->prepare("insert into tbl_invoice_details
            (invoice_id, product_id, product_name, quantity, price, order_date, order_time)
            values(:invid, :pid, :pname, :qty, :price, :ordate, :ortime)");

            $insert->bindParam(':invid', $id);
            $insert->bindParam(':pid', $arr_productid[$i]);
            $insert->bindParam(':pname', $arr_productname[$i]);
            $insert->bindParam(':qty', $arr_qty[$i]);
            $insert->bindParam(':price', $arr_price[$i]);
            $insert->bindParam(':ordate', $order_date);
            $insert->bindParam(':ortime', $order_time);

            $insert->execute();
        }
        // header('location:orderlist.php');
    }
}

?>

<!-- STYLES FOR DATE PICKER IS IN ADMINHEADER.PHP (STYLE FOR DATE PICKER) -->
<!-- Select2 -->
<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Order</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Order</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <form action="" method="post">

                <div class="card card-outline card-warning card-custom">
                    <div class="card-header">
                        <div class="row">

                            <div class="col-12" style="display:contents;">
                                <div class="col-md-6">
                                    <h3 class="card-title">New Order</h3>
                                </div>
                                <div class="col-md-6" style="display:flex; gap:20px; justify-self:end;">
                                <div style="display:flex; gap:10px;">
                                    <label>Order Date:</label>
                                    <div><?php echo $orderdate_db?></div>
                                </div>
                                <div style="display:flex; gap:10px;">
                                    <label>Order Time:</label>
                                    <div><?php echo $ordertime_db?></div>
                                </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-body card-warning row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Customer Name</label>
                                <div class="input-group" id="">
                                    <input type="text" name="txtcustomername" class="form-control" id="exampleInputEmail1" placeholder="Enter Name" value="<?php echo $customername_db ?>" required></input>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-user"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Current Date:</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="datein" name="orderdate"  value="<?php echo date("Y-m-d");?>" />                                    
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker" id="dateButton">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Current Time:</label>

                                <div class="input-group date" id="timepicker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#timepicker" name="ordertime"  value="<?php echo date('h:i:s A')?>"/>
                                    <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker" id="timebtn">
                                        <div class="input-group-text"><i class="far fa-clock"></i></div>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                            <!-- /.form group -->
                        </div>
                    </div>

                    <div class="card-body card-warning row">
                        <div class="col-md-12">
                            <div style="overflow-x:auto;">
                                <table id="producttable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 90px; max-width: 90px;">#</th>
                                            <th style="width: 5px; max-width: 90px;">.</th>
                                            <th>Search Product</th>
                                            <th>Stock</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th class="th-btn">
                                                <div class="btn btn-block btn-success btn-xs btnaddedit" name="btnaddedit" style="width:fit-content; padding: 7px 15px;"><i class="nav-icon fas fa-plus"></i></div>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="productbody_desktop">

                                        <?php 
                                            $id = $_GET['id'];
                                            $select = $pdo->prepare("select * from tbl_invoice_details where invoice_id = $id");
                                            $select->execute();
                                            
                                            $cus_id = 1;
                                            

                                            while($row = $select->fetch(PDO::FETCH_ASSOC)){
                                                $select_stock = $pdo->prepare("select * from tbl_product where id = ". $row["product_id"] ."");
                                                $select_stock->execute();
                                                $select_stk = $select_stock->fetch(PDO::FETCH_ASSOC);
                                                extract($row);
                                                extract($select_stk);
                                            
                                        ?>
                                                <tr>
                                                    <td><input type="text" class="form-control productid productnum" readonly value="<?php echo $cus_id?>" style="text-align: center;"></td>
                                                    <td><input type="hidden" name="productname[]" class="form-control pname" readonly value="<?php echo $row["product_name"]?>"></td>
                                                    <td><select type="text" name="productid[]" class="form-control productidedit" ><option value="">Select value</option><?php echo fill_products($pdo, $row['product_id']);?></select></td>
                                                    <td><input type="text" name="stock[]" class="form-control stock" readonly style="text-align: center;" value="<?php echo $select_stk['productstock']?>"></td>
                                                    <td><div class="input-group" id=""><div class="input-group-prepend"> <span class = "input-group-text" > <i class ="fa fa-dollar"> </i></span ></div><input type="text" name="price[]"  value="<?php echo $select_stk['salesprice']?>" class="form-control price" readonly ></div></td>
                                                    <td><input type="number" min="1" max="" name="qty[]" class="form-control qty qty-error-not" style="text-align: center;" value="<?php echo $row['quantity']?>" required ></td>
                                                    <td><div class="input-group" id=""><div class="input-group-prepend"> <span class = "input-group-text" > <i class ="fa fa-dollar"> </i></span ></div><input type="text" name="total[]" class="form-control total" value="<?php echo $select_stk['salesprice'] * $row['quantity'] ?>" readonly ></div></td>
                                                    <td><button type="button" class="btn btn-block btn-danger btn-xs btnremove" name="" style="width:fit-content; padding:7px 15px;"><i class="nav-icon fas fa-minus"></i></button></td>
                                                </tr>
                                    <?php 
                                            $cus_id++;
                                            }
                                        ?>
                                    </tbody>
                                    <tbody id="productbody_mobile">

                                    </tbody>
                                </table>
                                <div style="width: 100%; display: flex;  justify-content: end;">
                                    <div class="btn btn-info" name="btncalculatetable" onclick="calculate(_, 0, 0)">Calculate Table</div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="card-body card-warning row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">subTotal</label>
                                <div class="input-group" id="">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-dollar"> </i>
                                        </span>
                                    </div>
                                    <input type="text" name="txtsubtotal" class="form-control" id="subtotal" placeholder="Enter..." value="<?php echo $subtotal_db?>" required readonly></input>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Tax(5%)</label>
                                <div class="input-group" id="">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-dollar"> </i>
                                        </span>
                                    </div>
                                    <input type="text" name="txttax" class="form-control" id="txttax" placeholder="Enter..." value="<?php echo $tax_db?>" required readonly></input>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Discount</label>
                                <div class="input-group" id="">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-dollar"> </i>
                                        </span>
                                    </div>
                                    <input type="text" name="txtdiscount" id="txtdiscount" class="form-control" placeholder="Enter..." value="<?php echo $discount_db?>" rows="4"></input>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Total</label>
                                <div class="input-group" id="">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-dollar"> </i>
                                        </span>
                                    </div>
                                    <input type="text" name="txttotal" id="txtnetttotal" class="form-control" placeholder="Enter..." value="<?php echo $total_db?>" required readonly></input>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Paid</label>
                                <div class="input-group" id="">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-dollar"> </i>
                                        </span>
                                    </div>
                                    <input type="text" name="txtpaid" id="txtpaid" class="form-control" placeholder="Enter..." rows="4" value="<?php echo $paid_db?>" required></input>

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Change / Due</label>
                                <div class="input-group" id="">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-dollar"> </i>
                                        </span>
                                    </div>
                                    <input type="text" name="txtdue" id="txtdue" class="form-control" placeholder="Enter..." rows="4" value="<?php echo $due_db?>" required readonly></input>
                                </div>
                            </div>

                            <label style="padding-top:20px;">Payment Method</label>
                            <div class="form-group clearfix">
                                <div class="icheck-danger d-inline">
                                    <input type="radio" name="rb" value="cash" checked id="radioDanger1" <?php echo ($paytype_db == 'cash')? 'checked': ''?>>
                                    <label for="radioDanger1">
                                        Cash
                                    </label>
                                </div>
                                <div class="icheck-danger d-inline">
                                    <input type="radio" name="rb" value="card" id="radioDanger2" <?php echo ($paytype_db == 'card')? 'checked': ''?>>
                                    <label for="radioDanger2">
                                        Card
                                    </label>
                                </div>
                                <div class="icheck-danger d-inline">
                                    <input type="radio" name="rb" value="check" id="radioDanger3" <?php echo ($paytype_db == 'check')? 'checked': ''?>>
                                    <label for="radioDanger3">
                                        Check
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" align="end">
                        <button type="submit" class="btn btn-warning" name="btneditorder" >Update Order</button>
                        <!-- <a href="./orderlist.php" class="btn btn-info" name="btnsaveorder" type="submit">Save_Order</a> -->
                    </div>
                    <!-- /.card-body -->
                </div>
            </form>
            <!-- /.card -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script src="plugins/select2/js/select2.full.min.js"></script>


<?php 

include_once 'crumbles.php';

?>
