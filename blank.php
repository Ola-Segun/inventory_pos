<?php

// include_once 'header.php';
include_once 'adminheader.php';


?>

<style>
    .dataTables_wrapper {
        width: -webkit-fill-available;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Sales Report -> Graph Reports</h1>
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

            <div class="card card-outline card-success card-custom">
                <div class="card-header">
                    <div class="row">

                        <div class="col-md-3">
                            <h3 class="card-title">Graph Report</h3>
                        </div>

                        <div class="col-md-5">
                            <h3 class="card-title">
                                <?php
                                    if(isset($_POST['btnDateFilter'])){
                                        echo '<strong>From :   </strong>'.$_POST['date_1'].'<strong>    To :   </strong>'.$_POST['date_2'];
                                    }
                                ?>
                            </h3>
                        </div>

                    </div>
                </div>
                <!-- /.card-header -->

                <!-- Date filter form -->
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
                <!-- /.Date filter form -->
                <!-- Graph chart -->
                <div class="card-body row">
                    <div>
                        <?php   
                            // Check if the form is submitted
                            if(isset($_POST['btnDateFilter'])){
                                // Validate and sanitize the input dates to prevent SQL injection
                                $fromDate = $_POST['date_1'] ?? '';
                                $toDate = $_POST['date_2'] ?? '';

                                // Perform the query with prepared statements
                                $select = $pdo->prepare("SELECT order_date, sum(total) as price FROM tbl_invoice WHERE order_date BETWEEN :fromdate AND :todate GROUP BY order_date");
                                $select->bindParam(":fromdate", $fromDate);
                                $select->bindParam(":todate", $toDate);
                                $select->execute();

                                // Initialize arrays to store data
                                $total = array();
                                $date = array();

                                // Fetch and process the results
                                while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row);

                                    $total[] = $price;
                                    $date[] = $order_date;
                                }
                            }
                            ?>

                        <div class="chart">
                            <canvas id="myChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
                <!-- /.Graph chart -->
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<?php

include_once 'sidebar.php';

?>


<?php

include_once 'footer.php';

?>