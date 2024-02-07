<?php
include_once 'connectdb.php';

session_start();


if ($_SESSION['useremail'] == "" OR $_SESSION['role'] == "User") {
    header('location:index.php');
}


$_SESSION['pagetitle'] = 'Dashboard';
include_once 'adminheader.php';

$select = $pdo->prepare("SELECT sum(total) as t , count(invoice_id) as inv from tbl_invoice");
$select->execute();
$row = $select->fetch(PDO::FETCH_OBJ);

$total_order=$row->inv;
$net_total=$row->t;


?>

<style>
    .chart{
        width: 75vw;
        max-width: 85vw;
        justify-self: center;
    }
    .mychart{
        display: inline-block;
        width: 100%;
    }

    /* @media (max-width: 992px) {
        .chart{
        width: 87vw;
        justify-self: center;
        background-color: aquamarine;
        }
        .mychart{
            display: inline-block;
            width: 100%;
        }
    } */
    
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Admin Dashboard</h1>
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

            <!-- Small boxes (Stat box) -->
            <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                <div class="inner">
                    <h3><?php echo $total_order;?></h3>

                    <p>Total Order</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                <div class="inner">
                    <h3><?php echo '$'.number_format($net_total,2);?></h3>

                    <p>Total Revenue</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

            <?php
            
                $select = $pdo->prepare("SELECT count(productname) as tp from tbl_product");
                $select->execute();
                $row = $select->fetch(PDO::FETCH_OBJ);

                $total_product=$row->tp;
            
            ?>
            
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                <div class="inner">
                    <h3><?php echo $total_product;?></h3>

                    <p>Total Products</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->


            <?php
            
                $select = $pdo->prepare("SELECT count(category) as pc from tbl_category");
                $select->execute();
                $row = $select->fetch(PDO::FETCH_OBJ);

                $total_categories=$row->pc;
        
            ?>


            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                <div class="inner">
                    <h3><?php echo $total_categories;?></h3>

                    <p>Product category</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            </div>
            <!-- /.row -->

            <!-- Bar Chart -->
            <div class="card card-outline card-success card-custom">
                <div class="card-header">
                    <h3 class="card-title">Earnings by date <small>(Bar-chart)</small></h3>
                    <!-- card tools -->
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->

                <!-- Graph chart -->
                <div class="card-body row">
                    <div>

                        <?php   
                                // Perform the query with prepared statements
                                $select1 = $pdo->prepare("SELECT order_date, total FROM tbl_invoice GROUP BY order_date LIMIT 40");
                                $select1->execute();

                                // Initialize arrays to store data
                                $ttl = array();
                                $date = array();

                                // Fetch and process the results
                                while ($row1 = $select1->fetch(PDO::FETCH_ASSOC)) {
                                    extract($row1);

                                    $ttl[] = $total;
                                    $date[] = $order_date;
                                }

                        ?>

                        <div class="chart">
                            <canvas id="mychart" class="mychart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- /.Graph chart -->
                
                
            </div>
            <!-- /.Bar Chart -->
            

            <!-- Main row -->
            <div class="row">
            <!-- Left col -->
            <section class="col-lg-7 connectedSortable">
                <!-- card -->

                <!-- Best Selling Products -->
                <div class="card card-outline card-success">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                        Best Selling Products
                        </h3>
                        <!-- card tools -->
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <div class="card-body">
                    <table id="tablecategory" class="table table-striped">
                        <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Total</th>
                                </tr>
                        </thead>
                        <tbody>
                                <?php

                                $select = $pdo->prepare("SELECT product_id, product_name, price, sum(quantity) as q, sum(quantity*price) as total
                                                        FROM tbl_invoice_details group by product_name ORDER BY sum(quantity) DESC LIMIT 7");

                                $select->execute();

                                while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                    echo '
                                    <tr>
                                        <td>' . $row->product_id . '</td>
                                        <td>' . $row->product_name . '</td>
                                        <td>' . $row->q . '</td>
                                        <td>$' .number_format($row->price,2). '</td> 
                                        <td>$' .number_format($row->total,2). '</td> 
                                    </tr>
                                    ';
                                }


                                ?>
                        </tbody>
                    </table>
                    </div>
                <!-- /.card-body-->
                    <div class="card-footer bg-transparent">

                    </div>
                    <!-- /.row -->
                </div>
                <!-- Best Selling Products -->
                

                <!-- /.card -->
            </section>
            <!-- /.Left col -->
            
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-5 connectedSortable">

                <!-- Map card -->
                <div class="card card-outline card-success">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                        Recent Orders
                        </h3>
                        <!-- card tools -->
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <div class="card-body">
                    <table id="tablecategory2" class="table table-striped">
                        <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Time</th>
                                    <th>Total</th>
                                </tr>
                        </thead>
                        <tbody>
                                <?php

                                $select = $pdo->prepare("SELECT * FROM tbl_invoice WHERE DATE(order_date) = CURDATE() LIMIT 10");

                                $select->execute();

                                while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                    echo '
                                    <tr>
                                        <td>' . $row->invoice_id . '</td>
                                        <td>' . $row->customer_name . '</td>
                                        <td>' . $row->order_time . '</td>
                                        <td>$' .number_format($row->total,2). '</td> 
                                    </tr>
                                    ';
                                }


                                ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body-->
                    <div class="card-footer bg-transparent">

                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.card -->


                <!-- Calendar -->
                <div class="card bg-gradient-success">
                <div class="card-header border-0">

                    <h3 class="card-title">
                    <i class="far fa-calendar-alt"></i>
                    Calendar
                    </h3>
                    <!-- tools card -->
                    <div class="card-tools">
                    <!-- button with a dropdown -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" data-offset="-52">
                        <i class="fas fa-bars"></i>
                        </button>
                        <div class="dropdown-menu" role="menu">
                        <a href="#" class="dropdown-item">Add new event</a>
                        <a href="#" class="dropdown-item">Clear events</a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">View calendar</a>
                        </div>
                    </div>
                    <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                    </div>
                    <!-- /. tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body pt-0">
                    <!--The calendar -->
                    <div id="calendar" style="width: 100%"></div>
                </div>
                <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </section>
            <!-- right col -->
            </div>
            <!-- /.row (main row) -->

        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    const ctx = document.getElementById('mychart');

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($date)?>,
            datasets: [{
                label: 'Total Earnings',
                data: <?php echo json_encode($ttl)?>,
                borderWidth: 1,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

<script>
    $(document).ready(function() {
        $('#tablecategory2').DataTable({
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#table-row .col-md-12:eq(0)');
    });
    
    $(document).ready(function() {
        $('#tablecategory3').DataTable({
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#table-row .col-md-12:eq(0)');
    });
</script>

<script>
    function listenToScreenWidth() {
        window.addEventListener('resize', function() {
            // Get the current width of the screen
            var screenWidth = window.innerWidth;

            // Log the current width
            // console.log('Screen width: ' + screenWidth);

            if(screenWidth > 992){
                // Get the chart element
                var chartElement = document.querySelector('.chart');

                // Set width to 87vw
                chartElement.style.width = '75vw';

                // Get the mychart element
                var myChartElement = document.querySelector('.mychart');

                // Set display to inline-block
                myChartElement.style.display = 'inline-block';

                // Set width to 100%
                myChartElement.style.width = '100%';

            }

            if(screenWidth < 992){
                // Get the chart element
                var chartElement = document.querySelector('.chart');

                // Set width to 87vw
                chartElement.style.width = '89vw';

                // Get the mychart element
                var myChartElement = document.querySelector('.mychart');

                // Set display to inline-block
                myChartElement.style.display = 'inline-block';

                // Set width to 100%
                myChartElement.style.width = '100%';

            }
            
            // You can add more logic here based on the screen width
            // For example, you could trigger different actions or apply different styles
        });
    }

    // Call the function to start listening to screen width changes
    listenToScreenWidth();
</script>

<?php

include_once 'sidebar.php';

?>


<?php

include_once 'footer.php';

?>