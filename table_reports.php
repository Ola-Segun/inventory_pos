<?php
include_once 'connectdb.php';
error_reporting(0);
session_start();

if ($_SESSION['useremail'] == "" or $_SESSION['userrole'] == "User") {
    header('location:index.php');
}


$_SESSION['pagetitle'] = 'Table Reports';
$_SESSION['tbl'] = 'tbl_invoice';

include_once 'adminheader.php';

?>

<style>
    .dataTables_wrapper {
        width: -webkit-fill-available;
    }
    .jn{
        line-height: 0;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sales Report -> Table Reports</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Admin Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <!-- Info boxes -->
            <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-cog"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">CPU Traffic</span>
                    <span class="info-box-number">
                    10
                    <small>%</small>
                    </span>
                </div>
                <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-thumbs-up"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Likes</span>
                    <span class="info-box-number">41,410</span>
                </div>
                <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Sales</span>
                    <span class="info-box-number">760</span>
                </div>
                <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">New Members</span>
                    <span class="info-box-number">2,000</span>
                </div>
                <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="card card-outline card-success card-custom">
                <div class="card-header">
                    <div class="row">

                        <div class="col-md-3">
                            <h3 class="card-title">Table Report</h3>
                        </div>

                        <div class="col-md-5">
                            <h3 class="card-title">
                                <?php
                                    if(isset($_POST['btnDateFilter'])){
                                        echo '<strong>From : </strong>'.$_POST['date_1'].'<strong> -- To : </strong>'.$_POST['date_2'];
                                    }
                                ?>
                            </h3>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->

                <div class="card-body">
                    <form method="post" action="" class="col-md-12 row">

                    <div class="col-md-5">
                        <div class="form-group">
                            <label>From:</label>
                            <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate" id="datein" name="date_1" />                                    
                                <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker" id="dateButton">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label>To:</label>
                        <div class="input-group date" id="reservationdate2" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#reservationdate2" id="datein" name="date_2" />                                    
                            <div class="input-group-append" data-target="#reservationdate2" data-toggle="datetimepicker" id="dateButton">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="btn_cvr" align="center" style="height:100%; display:grid; align-content:center;">
                            <!-- Add a submit button to trigger the form submission -->
                            <input type="submit" class="btn btn-success" name="btnDateFilter" value="Filter By Date">
                        </div>
                    </div>
                    </form>


                    <!-- Table Column -->
                    <hr>
                    
                    <table id="tablereport" class ="table table-striped">
                        <thead>
                            <tr>
                                <th>invoice ID</th>
                                <th><p class="jn">Customer</p><p class="jn">Name</p></th>
                                <th>Subtotal</th>
                                <th>Tax</th>
                                <th>Discount</th>
                                <th>Total</th>
                                <th>Paid</th>
                                <th>Due</th>
                                <th><p class="jn">Order</p><p class="jn">Date</p></th>
                                <th><p class="jn">Payment</p><p class="jn">type</p></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php   
                                // Check if the form is submitted
                                if(isset($_POST['btnDateFilter'])){
                                    // Validate and sanitize the input dates to prevent SQL injection
                                    $fromDate = $_POST['date_1'] ?? '';
                                    $toDate = $_POST['date_2'] ?? '';

                                    // Perform the query with prepared statements
                                    $select = $pdo->prepare("SELECT * FROM tbl_invoice WHERE order_date BETWEEN :fromdate AND :todate");
                                    $select->bindParam(":fromdate", $fromDate);
                                    $select->bindParam(":todate", $toDate);
                                    $select->execute();

                                    // Display the results
                                    while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                        echo '
                                            <tr>
                                                <td class="invoiceId">' . $row->invoice_id . '</td>
                                                <td>' . $row->customer_name . '</td>
                                                <td>' . $row->subtotal . '</td>
                                                <td>' . $row->tax . '</td>
                                                <td>' . $row->discount . '</td> 
                                                <td style="color:#fff;"><span class="badge bg-danger" style="padding:7px 10px; color:#fff; font-size:13px;">' . number_format($row->total, 2) . '</span></td> 
                                                <td><small><em>' . $row->paid . '</em></small></td> 
                                                <td id="nettotal">' . $row->due . '</td> 
                                                <td>' . $row->order_date . '</td> 
                                        ';

                                        if($row->payment_type == 'cash'){
                                            echo'<td style="color:#fff;"><span class="badge bg-primary" style="padding:7px 10px; color:#fff; font-size:13px;">' . $row->payment_type . '</span></td> ';
                                        }elseif($row->payment_type == 'card'){
                                            echo'<td style="color:#fff;"><span class="badge bg-info" style="padding:7px 10px; color:#fff; font-size:13px;">' . $row->payment_type . '</span></td> ';
                                        }else{
                                            echo'<td style="color:#fff;"><span class="badge bg-warning" style="padding:7px 10px; color:#fff; font-size:13px;">' . $row->payment_type . '</span></td> ';
                                        }
                                            echo '</tr>';
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>


                

            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    $(document).ready(function() {
        $('#tablereport').DataTable({
            "order": [
                [0, "desc"]
            ],
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#table-row .col-md-12:eq(0)');
    });
</script>

<?php

include_once 'sidebar.php';

?>


<?php

include_once 'footer.php';

?>