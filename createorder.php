<?php
include_once 'connectdb.php';

session_start();


// if ($_SESSION['useremail'] == "" or $_SESSION['userrole'] == "User") {
//     header('location:index.php');
// }


// Title for each page (echoed all names in adminheader)
$_SESSION['pagetitle'] = 'Create Order';
$_SESSION['tbl'] = 'tbl_product';

if ($_SESSION['useremail'] == "") {
    header('location:index.php');
}


if ($_SESSION['userrole'] == "Admin") {
    include_once 'adminheader.php';
}elseif($_SESSION['userrole'] == "User"){
    include_once 'userheader.php';
}
?>


<?php

function fill_products($pdo)
{
    $output = '';
    $select = $pdo->prepare("select * from tbl_product order by productname asc");
    $select->execute();

    $result = $select->fetchAll();

    foreach ($result as $row) {
        $output .= '<option value="' . $row["id"] . '">' . $row["productname"] . '</option>';
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

// on btnsaveorder validation
if (isset($_POST['btnsaveorder'])) {
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
    // print_r($arr_productname);
    $arr_stock = $_POST['stock'];
    $arr_price = $_POST['price'];
    $arr_qty = $_POST['qty'];
    $arr_total = $_POST['total'];


    $insert = $pdo->prepare("insert into tbl_invoice
    (customer_name, order_date, order_time, subtotal, tax, discount, total, paid, due, payment_type) 
    values(:name, :date, :time, :stotal, :tax, :disc, :total, :paid, :due, :ptype)");

    $insert->bindParam(":name", $customer_name);
    $insert->bindParam(":date", $order_date);
    $insert->bindParam(":time", $order_time);
    $insert->bindParam(":stotal", $subtotal);
    $insert->bindParam(":tax", $tax);
    $insert->bindParam(":disc", $discount);
    $insert->bindParam(":total", $total);
    $insert->bindParam(":paid", $paid);
    $insert->bindParam(":due", $due);
    $insert->bindParam(":ptype", $payment_type);

    $insert->execute();


    // tbl_invoice_details insert query STARTS

    $invoice_id = $pdo->lastInsertId();

    if ($invoice_id != null) {
        for ($i = 0; $i < count($arr_productid); $i++) {

            $rem_qty = $arr_stock[$i] - $arr_qty[$i];

            if($rem_qty < 0){
                echo "order not Successfully";
            } else {
                $update = $pdo->prepare("update tbl_product SET productstock='$rem_qty' where id ='$arr_productid[$i]'");

                $update->execute();

                echo '
        
                        <script type="text/javascript">
                        jQuery(function validation(){
    
                        swal.fire({
                        title: "Success",
                        text: "Order Created Successfully",
                        icon: "success",
                        button: "OK",
                        });
    
                        });
                        
                        </script>
                        
                        ';

            }
            

            $insert = $pdo->prepare("insert into tbl_invoice_details
            (invoice_id, product_id, product_name, quantity, price, order_date, order_time)
            values(:invid, :pid, :pname, :qty, :price, :ordate, :ortime)");

            $insert->bindParam(':invid', $invoice_id);
            $insert->bindParam(':pid', $arr_productid[$i]);
            $insert->bindParam(':pname', $arr_productname[$i]);
            $insert->bindParam(':qty', $arr_qty[$i]);
            $insert->bindParam(':price', $arr_price[$i]);
            $insert->bindParam(':ordate', $order_date);
            $insert->bindParam(':ortime', $order_time);

            $insert->execute();
        }
        // header('location:orderlist.php');/
    }
    // tbl_invoice_details insert query ENDS
}

?>
<!-- <input type="text" name="arr_productname" id="arr_productname" value="<?php echo $a_pname?>"> -->

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
                    <h1 class="m-0">Create Order</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
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

                            <div class="col-12">
                                <div class="col-md-6">
                                    <h3 class="card-title">New Order</h3>
                                </div>
                            </div>
                            <!-- <div class="col-md-2">
                                <h3 class="card-title">
                                    <a href="addproduct.php" class="btn btn-info" style="display:flex; align-items:center; gap:5px;">
                                        <i class="fas fa-angle-left right"></i>Add Product
                                    </a>
                                </h3>
                            </div> -->
                        </div>
                    </div>
                    <div class="card-body card-warning row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Customer Name</label>
                                <div class="input-group" id="">
                                    <input type="text" name="txtcustomername" class="form-control" id="exampleInputEmail1" placeholder="Enter Name" required></input>
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-user"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Date:</label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="datein" name="orderdate" value="<?php echo date("Y-m-d");?>" />                                    
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker" id="dateButton">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Time:</label>

                                <div class="input-group date" id="timepicker" data-target-input="nearest">
                                    <input type="text" class="form-control datetimepicker-input" data-target="#timepicker" name="ordertime" value="<?php echo date('h:i:s A')?>"/>
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
                            <div style="overflow-x:auto; ">
                                <table id="producttable" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 90px; max-width: 90px; min-width: 50px;">#</th>
                                            <th style="width: 5px; max-width: 20px;">.</th>
                                            <th>Search Product</th>
                                            <th>Stock</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th class="th-btn">
                                                <button type="submit" class="btn btn-block btn-success btn-xs btnadd" name="btnadd" style="width:fit-content; padding: 7px 15px;"><i class="nav-icon fas fa-plus"></i></button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="productbody_desktop">

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
                                    <input type="text" name="txtsubtotal" class="form-control" id="subtotal" placeholder="Enter..." required readonly></input>

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
                                    <input type="text" name="txttax" class="form-control" id="txttax" placeholder="Enter..." required readonly></input>

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
                                    <input type="text" name="txtdiscount" id="txtdiscount" class="form-control" placeholder="Enter..." rows="4"></input>

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
                                    <input type="text" name="txttotal" id="txtnetttotal" class="form-control" placeholder="Enter..." required readonly></input>

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
                                    <input type="text" name="txtpaid" id="txtpaid" class="form-control" placeholder="Enter..." rows="4" required></input>

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
                                    <input type="text" name="txtdue" id="txtdue" class="form-control" placeholder="Enter..." rows="4" required readonly></input>
                                </div>
                            </div>

                            <label style="padding-top:20px;">Payment Method</label>
                            <div class="form-group clearfix">
                                <div class="icheck-danger d-inline">
                                    <input type="radio" name="rb" value="cash" checked id="radioDanger1">
                                    <label for="radioDanger1">
                                        Cash
                                    </label>
                                </div>
                                <div class="icheck-danger d-inline">
                                    <input type="radio" name="rb" value="card" id="radioDanger2">
                                    <label for="radioDanger2">
                                        Card
                                    </label>
                                </div>
                                <div class="icheck-danger d-inline">
                                    <input type="radio" name="rb" value="check" id="radioDanger3">
                                    <label for="radioDanger3">
                                        Check
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" align="end">
                        <button type="submit" class="btn btn-info" name="btnsaveorder" >Save Order</button>
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


<!-- Select2 -->
<script src="plugins/select2/js/select2.full.min.js"></script>
<!-- date-range-picker -->
<script>

    // unit seperation by comma function (1,000,000)
    function separatebycomma(input) {
        var input = String(input);
        var position = 1;
        var input_len = input.length;
        var val = 0;
        var counter = 1;
        var fixed = 3;
        var counts = 1;
        var reader = 0;

        var s_input = "";

        while (position < input_len) {
            for (var x = 1; x <= 3; x++) {
                if (reader < input_len) {
                    val = input_len - counts;
                    s_input = input[val] + s_input;
                    counts++;
                }
                reader++;
            }
            if (reader < input_len) {
                if (input[val] != ".") {
                    s_input = "," + s_input;
                }
            }
            position = counter * fixed;
            counter++;
        }
        return s_input;
    }

    function removeCommas(text) {
        // Use a regular expression to globally replace commas with an empty string
        return text.replace(/,/g, '');
    }


    // Get subtotal, discount, tax, net_total, paid_amt, due of all the items in the order table
    function calculate(dis, paid, net_total) {
        var subtotal = 0;
        var discount = dis;
        var tax = 0;
        var tax_percent = 0.05;
        // net_total is the rounded up total
        // var net_total = 0; 
        var paid_amt = paid;
        var due = 0;

        $(".total").each(function() {
            var clean_total = parseFloat($(this).val().replace(/,/g, '')) || 0;
            subtotal += clean_total;
            tax += clean_total;
            net_total = (tax * tax_percent) + subtotal;
        })

        if (due != NaN) {
            due = net_total - discount;
            net_total = due;
        } else {
            due = 0;
        }
        due = net_total - paid_amt;
        // format subtotal with comma
        var frm_subtotal = separatebycomma(subtotal.toFixed(2));
        var frm_tax_total = separatebycomma((tax * tax_percent).toFixed(2));
        var frm_net_total = separatebycomma((net_total).toFixed(2));
        var frm_due = separatebycomma((due).toFixed(2));

        function NegativeNumberWithCommas(due) {
            // Check if the due is negative
            if (due < 0) {
                // Use the toLocaleString() method with appropriate options
                return due.toLocaleString(undefined, {
                    style: 'decimal',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            } else {
                // If the due is not negative, simply format it with commas
                return due.toLocaleString();
            }
        }

        $("#subtotal").val(frm_subtotal);
        $("#txttax").val(frm_tax_total);
        $("#txtnetttotal").val(frm_net_total);
        $("#txtdiscount").val(discount);
        $("#txtdue").val(NegativeNumberWithCommas(due));

        }
    // Calculate function ends here

    

    $(document).ready(function() {
        var cus_id = 1;
        var sales_p;
        var qty_input_error = "";
        var total = 0;

        // window.onload = console.log($("#timebtn").val()); 

        // Function to handle the click event when adding a new row
        $(document).on('click', '.btnadd', function() {
            var html = '';
            html += '<tr>';
            html += '<td><input type="text" class="form-control tableid productnum" readonly value="' + (cus_id) + '" style="text-align: center;"></td>';
            html += '<td><input type="hidden" name="productname[]" class="form-control pname" readonly></td>'
            // html += '<td><select type="text" name="productname[]" class="form-control productname"><option value="">Select value</option><?php echo fill_products($pdo);?></select></td>'
            html += '<td><select type="text" name="productid[]" class="form-control productid"><option value="">Select value</option><?php echo fill_products($pdo);?></select></td>'
            html += '<td><input type="text" name="stock[]" class="form-control stock" readonly style="text-align: center;"></td>';
            html += '<td><div class="input-group" id=""><div class="input-group-prepend"> <span class = "input-group-text" > <i class ="fa fa-dollar"> </i></span ></div><input type="text" name="price[]" class="form-control price" readonly ></div></td>';
            html += '<td><input type="number" min="1" max="10000" name="qty[]" class="form-control qty qty-error-not" style="text-align: center;" value="" required ></td>';
            html += '<td><div class="input-group" id=""><div class="input-group-prepend"> <span class = "input-group-text" > <i class ="fa fa-dollar"> </i></span ></div><input type="text" name="total[]" class="form-control total" value="0" readonly ></div></td>';
            html += '<td><button type="button" class="btn btn-block btn-danger btn-xs btnremove" name="" style="width:fit-content; padding: 7px 15px;"><i class="nav-icon fas fa-minus"></i></button></td>';
            html += '</tr>';

            var html_mob = '';
            html_mob += '<tr>';
            html_mob += '<td><input type="text" class="form-control tableid productnum" readonly value="' + (cus_id) + '" style="text-align: center;"></td>';
            html_mob += '<td><input type="hidden" name="productname[]" class="form-control pname" readonly></td>'
            // html_mob += '<td><select type="text" name="productname[]" class="form-control productname"><option value="">Select value</option><?php echo fill_products($pdo);?></select></td>'
            html_mob += '<td><select type="text" name="productid[]" class="form-control productid"><option value="">Select value</option><?php echo fill_products($pdo);?></select></td>'
            html_mob += '<td><input type="text" name="stock[]" class="form-control stock" readonly style="text-align: center;"></td>';
            html_mob += '<td><div class="input-group" id=""><input type="text" name="price[]" class="form-control price" style="text-align:center;" readonly ></div></td>';
            html_mob += '<td><input type="number" min="1" max="" name="qty[]" class="form-control qty qty-error-not" style="text-align: center;" value="" required ></td>';
            html_mob += '<td><div class="input-group" id=""><input type="text" name="total[]" class="form-control total" style="text-align:center;" value="0" readonly ></div></td>';
            html_mob += '<td><button type="button" class="btn btn-block btn-danger btn-xs btnremove" name="" style="width:fit-content; padding: 7px 15px;"><i class="nav-icon fas fa-minus"></i></button></td>';
            html_mob += '</tr>';

            
            var screenwidth = $(window).width();
            if (screenwidth < 768){
                // console.log(screenwidth);
                $('#productbody_mobile').append(html_mob);
            } else{
                $('#productbody_desktop').append(html);
                }
            
            // Increment product id for the next row
            cus_id++;

            // Initialize Select2 Elements
            // Get items
            $('.productid').select2();

            $(".productid").on('change', function() {
                var tr = $(this).parent().parent();
                var productid = this.value;

                var paid = removeCommas($('#txtpaid').val());
                var net_total = removeCommas($('#txtnetttotal').val());

                $.ajax({
                    url: "getproduct.php",
                    method: "get",
                    data: {
                        id: productid
                    },
                    success: function(data) {
                        tr.find(".stock").val(data["productstock"]);
                        tr.find(".price").val(data["salesprice"]);
                        tr.find(".qty").val(1);
                        tr.find(".pname").val(data["productname"]); 

                        // tr.find(".total").val(data["salesprice"]);

                        sales_p = data["salesprice"];

                        var qty = tr.find(".qty").val();
                        var price = tr.find(".price").val();
                        var stock = tr.find(".stock").val();
                        var frm_total_1 = separatebycomma(qty * price);
                        tr.find(".total").val(frm_total_1);
                        calculate(0, paid, net_total);


                        // Get the input element by name
                        var qty_input = document.querySelector('input[name="qty[]"]');

                        // stock = qty_input.getAttribute('max');
                        // Get the maximum value using getAttribute
                        qty_input.setAttribute('max', stock);
                        var qty_maxValue = qty_input.getAttribute('max');
                        // Log the maximum value
                    }
                });
            });
        });
        
        
       
        $(document).on('input', '.qty', function() {

            // Get the quantity input value
            var tr = $(this).parent().parent();
            var quantityValue = parseFloat($(this).val()) || 0;
            var stock = parseFloat(tr.find(".stock").val()) || 0;
            var paid = removeCommas($('#txtpaid').val());
            var net_total = removeCommas($('#txtnetttotal').val());
            // calculate(0, 0, 0);

            // Check if quantity is greater than stock
            if (quantityValue >= stock) {
                $(this).addClass("is-invalid");
                <?php
                echo '
                
                    Swal.fire({
                    position: "top-end",
                    icon: "warning",
                    title: "Stock Limit Reached",
                    button: "OK",
                    timer: 1500
                    })
                
                ';

                ?>
            } else {
                $(this).removeClass("is-invalid");
            }

            var salesprice = parseFloat(tr.find(".price").val()) || 0;

            total = salesprice * quantityValue;

            // Format the total value with commas for display
            frm_total = separatebycomma(total);
            tr.find(".total").val(frm_total);
            calculate(0, paid, net_total);
        });



        // Removing a row
        var id_list;
        $(document).on('click', '.btnremove', function() {
            var paid = removeCommas($('#txtpaid').val());
            var net_total = removeCommas($('#txtnetttotal').val());
            $(this).closest('tr').remove();

            // Recalculate product IDs for remaining rows
            $('.productnum').each(function(index) {
                calculate(0, paid, net_total);

                $(this).val(index + 1);

                // total count of the id_list remaining
                id_list = $(this).val();

            });


            // reset the cus_id value 
            cus_id = Number(id_list) + 1;
        });
    });

    // On discount input
    $(document).on('input', '#txtdiscount', function() {
        var discount = removeCommas($(this).val());
        var total = removeCommas($("#txtnetttotal").val());
        var paid = removeCommas($("#txtpaid").val());
        calculate(discount, paid, total);
    })

    $(document).on('input', '#txtpaid', function(){
        var rawpaid = $(this).val();
        var paid = removeCommas(rawpaid);
        var discount = $('#txtdiscount').val();
        calculate(discount, paid,);
        // Remove existing commas
        var inputValue = event.target.value.replace(/,/g, '');

        
        // Add commas for formatting
        var formattedValue = inputValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        
        // Update the input field with the formatted value
        event.target.value = formattedValue;
   })

</script>
<?php

include_once 'sidebar.php';

?>


<?php

include_once 'footer.php';

?>