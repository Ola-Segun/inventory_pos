<?php
include_once 'connectdb.php';

session_start();


if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "User") {
    header('location:index.php');
}


//On page 2
$_SESSION['pagetitle'] = 'View Product';
include_once 'adminheader.php';

?>

<style>
    .custom-img {
        max-height: 59.5vh;
        height: auto;
        width: auto;
        max-width: 100%;
        justify-self: center;
    }

    .custom-list-group-item {
        display: grid;
    }

    .bg_cs{
        padding: 5px;
        font-size: 14px;
        font-weight: 600;
        font-family: 'Courier New', Courier, monospace;
        box-shadow:#969baf 2px 2px 4px;
    }
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">View Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">View Product</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <script>
                $(function() {
                    $("#example1").DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                    $('#example2').DataTable({
                        "paging": true,
                        "lengthChange": false,
                        "searching": false,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                    });
                });
            </script>
            <div class="card card-outline card-success card-custom">
                <div class="card-header">
                    <div class="row">

                        <div class="col-10">
                            <h3 class="card-title">View Product</h3>
                        </div>
                        <div class="col-md-2">
                            <h3 class="card-title">
                                <!-- Back to Product list button -->
                                <a href="productlist.php" class="btn btn-info" style="display:flex; align-items:center; gap:5px;">
                                    <i class="fas fa-angle-left right"></i>Productlist
                                </a>
                            </h3>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->

                <div class="card-body row">

                    <?php

                    $id = $_GET['id'];
                    $select = $pdo->prepare("select * from tbl_product where id=$id");
                    $select->execute();

                    while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                        echo '
                    
                    
                        <div class="col-md-6">
                            <ul class="list-group">
                                <center>
                                    <p class="list-group-item list-group-item-success">
                                        <b>Product Details</b>
                                    </p>
                                </center>
                                <li class="list-group-item" style="display: flex; justify-content:space-between;">ID <span class="badge bg_cs bg-secondary">' . $row->id . '</span></li>
                                <li class="list-group-item" style="display: flex; justify-content:space-between;">Product Name <span class="badge bg_cs bg-info">' . $row->productname . '</span></li>
                                <li class="list-group-item" style="display: flex; justify-content:space-between;">Product Category <span class="badge bg_cs bg-primary">' . $row->productcategory . '</span></li>
                                <li class="list-group-item" style="display: flex; justify-content:space-between;">Sales Price <span class="badge bg_cs bg-warning">' . $row->salesprice . '</span></li>
                                <li class="list-group-item" style="display: flex; justify-content:space-between;">purchase Price <span class="badge bg_cs bg-warning">' . $row->purchaseprice . '</span></li>
                                <li class="list-group-item" style="display: flex; justify-content:space-between;">Profit <span class="badge bg_cs bg-success">' . $row->salesprice - $row->purchaseprice . '</span></li>
                                <li class="list-group-item" style="display: flex; justify-content:space-between;">Stock <span class="badge bg_cs bg-danger">' . $row->productstock . '</span></li>
                                <li class="list-group-item h6" style="display: grid; gap:7px;">Description:<p class="" style="font-weight:400;">' . $row->productdescription . '</p></li>

                            </ul>
                        </div>
                        
                        <div class="col-md-6">
                            <ul class="list-group">
                                <center>
                                    <p class="list-group-item list-group-item-success">
                                        <b>Product image</b>
                                    </p>
                                </center>
                                <li class="list-group-item custom-list-group-item"><img src="productimages/' . $row->productimage . '" alt="" class="img-rounded custom-img" width="100%" height="100%     " style="box-shadow:#bbbbbb 2px 2px 10px;"></li>
                            </ul>
                        </div>
                    
                    ';
                    }

                    ?>


                </div>


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