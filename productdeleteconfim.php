<?php
include_once 'connectdb.php';

session_start();


if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "User") {
    header('location:index.php');
}


//On page 2
$_SESSION['pagetitle'] = 'Delete Product';
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
</style>

<?php


if (isset($_POST['btndelete'])) {
        // echo $_GET['id']." IS READY TO DELETE";
        $delete = $pdo->prepare("delete from tbl_product where id=". $_GET['id']);
        $delete->execute();
        if ($delete->rowCount()) {
            echo '
        
                <script type="text/javascript">
                jQuery(function validation(){
    
                swal({
                title: "Success",
                text: "Product deleted Successfully",
                icon: "success",
                button: "OK",
                });
    
                });
                
                </script>
                
                ';
        } else {
            echo '
        
                <script type="text/javascript">
                jQuery(function validation(){
    
                swal({
                title: "Error",
                text: "Unable to delete Product",
                icon: "error",
                button: "OK",
                });
    
                });
                
                </script>
                
                ';
        }
    }
                    



?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Delete Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Delete Product</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <div class="card card-outline card-danger card-custom">
                <div class="card-header">
                    <div class="row">

                        <div class="col-10">
                            <h3 class="card-title">Delete Product</h3>
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
                                    <p class="list-group-item list-group-item-danger">
                                        <b>Product Details</b>
                                    </p>
                                </center>
                                <li class="list-group-item" style="display: flex; justify-content:space-between;">ID <span class="badge bg-secondary">' . $row->id . '</span></li>
                                <li class="list-group-item" style="display: flex; justify-content:space-between;">Product Name <span class="badge bg-info">' . $row->productname . '</span></li>
                                <li class="list-group-item" style="display: flex; justify-content:space-between;">Product Category <span class="badge bg-primary">' . $row->productcategory . '</span></li>
                                <li class="list-group-item" style="display: flex; justify-content:space-between;">Sales Price <span class="badge bg-warning">' . $row->salesprice . '</span></li>
                                <li class="list-group-item" style="display: flex; justify-content:space-between;">purchase Price <span class="badge bg-warning">' . $row->purchaseprice . '</span></li>
                                <li class="list-group-item" style="display: flex; justify-content:space-between;">Profit <span class="badge bg-success">' . $row->salesprice - $row->purchaseprice . '</span></li>
                                <li class="list-group-item" style="display: flex; justify-content:space-between;">Stock <span class="badge bg-danger">' . $row->productstock . '</span></li>
                                <li class="list-group-item h6" style="display: grid; gap:7px;">Description:<p class="" style="font-weight:400;">' . $row->productdescription . '</p></li>
                                <li class="list-group-item h6" style="display: flex; justify-content:space-between; gap:7px;">Confirm Delete:
                                    <p class="" style="font-weight:400; display:flex; gap:30px;">
                                        <a href="productlist.php" type="submit" class="btn btn-block btn-warning btn-xs" name="btncancel" style="width:fit-content; padding: 7px 15px;">Cancel</a>
                                        <form method="post">
                                        <button type="submit" class="btn btn-block btn-danger btn-xs" name="btndelete" style="width:fit-content; padding: 7px 15px;">Delete</button>
                                        </form>
                                    </p>
                                </li>

                            </ul>
                        </div>
                        <div class="col-md-6">
                        
                            <ul class="list-group">
                                <center>
                                    <p class="list-group-item list-group-item-danger">
                                        <b>Product image</b>
                                    </p>
                                </center>
                                <li class="list-group-item custom-list-group-item"><img src="productimages/' . $row->productimage . '" alt="" class="img-rounded custom-img img-responsive" width="100%" height="100%" style="box-shadow:#bbbbbb 2px 2px 10px;"></li>
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