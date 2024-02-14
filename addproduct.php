<?php
include_once 'connectdb.php';

session_start();


if ($_SESSION['useremail'] == "" or $_SESSION['userrole'] == "User") {
    header('location:index.php');
}

// Title for each page (echoed all names in adminheader)
$_SESSION['pagetitle'] = 'Add Product';
include_once 'adminheader.php';

// validate form fields on btnaddproduct
if (isset($_POST['btnaddproduct'])) {
    $productname = $_POST['txtproductname'];
    $category = $_POST['txtselectcategory'];
    $purchaseprice = $_POST['purchaseprice'];
    $salesprice = $_POST['salesprice'];
    $stock = $_POST['txtstock'];
    $description = $_POST['txtdescription'];

    $f_name = $_FILES['productimage']['name'];
    $f_tmp = $_FILES['productimage']['tmp_name'];
    $f_size = $_FILES['productimage']['size'];

    $f_exp = explode(".", $f_name);
    $f_extension = strtolower(end($f_exp));
    $newFileName = uniqid() . "." . $f_extension;
    $store = "productimages/" . $newFileName;
    $max_size = 1000000;

    /* this is if logic is to decide the type of extension reqiured to upload
    a file. If the required extension is satisfied, then we can now upload our file  */


    /* 
    CHECK - 
            File extension
                File size
                    Empty Fields
                         Existence of item
                                    Add item

    
    */


    // Check for file extensions
    if ($f_extension == "jpg" || $f_extension == "jpeg" || $f_extension == "png" || $f_extension == "gif") {

        // Check for file size
        if ($f_size < 1000000) {

            // Check if fields are empty
            if (!empty($productname && $category && $purchaseprice && $salesprice && $stock && $description)) {

                // Checking if Product Already exist 
                $select = $pdo->prepare("select productname from tbl_product where productname='$productname'");
                $select->execute();
                if ($select->rowCount() > 0) {
                    echo '
                    
                        <script type="text/javascript">
                        jQuery(function validation(){

                        swal.fire({
                        title: "Failed",
                        text: "Product Already exist",
                        icon: "error",
                        button: "OK",
                        });

                        });
                        
                        </script>
                
                    ';
                } else {

                    $insert = $pdo->prepare("insert into tbl_product 
                    (productname, productcategory, purchaseprice, salesprice, productstock, productdescription, productimage) 
                    values(:name, :category, :purprice, :salprice, :stock, :des, :img)");

                    $insert->bindParam(":name", $productname);
                    $insert->bindParam(":category", $category);
                    $insert->bindParam(":purprice", $purchaseprice);
                    $insert->bindParam(":salprice", $salesprice);
                    $insert->bindParam(":stock", $stock);
                    $insert->bindParam(":des", $description);
                    $insert->bindParam(":img", $newFileName);

                    if ($insert->execute()) {
                        echo '
                
                                <script type="text/javascript">
                                jQuery(function validation(){
            
                                swal.fire({
                                title: "Success",
                                text: "Product added Successfully",
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

                        swal.fire({
                        title: "Failed",
                        text: "Unable to add Product",
                        icon: "error",
                        button: "OK",
                        });

                        });
                        
                        </script>
                        
                        ';
                    }
                }
            
            // check if fields are not empty
            } elseif (empty($productname && $category && $purchaseprice && $salesprice && $stock && $description)) {
                echo '
                
                    <script type="text/javascript">
                    jQuery(function validation(){

                    swal.fire({
                    title: "Empty Fields",
                    text: "All Fields are required",
                    icon: "Warning",
                    button: "OK",
                    });

                    });
                    
                    </script>
                
                ';
            }
            move_uploaded_file($f_tmp, $store);
        } else {
            $error = '
            
                    <script type="text/javascript">
                    jQuery(function validation(){
    
                    swal.fire({
                    title: "warning",
                    text: "max file size is 1Mb",
                    icon: "Warning",
                    button: "OK",
                    });
    
                    });
                    
                    </script>
                    
                    ';
            echo $error;
        }
    } else {

        $error =  '
            
                    <script type="text/javascript">
                    jQuery(function validation(){
        
                    swal.fire({
                    title: "Warning",
                    text: "Files extension can only be Jpeg, jpg, png, gif",
                    icon: "error",
                    button: "OK",
                    });
        
                    });
                    
                    </script>
            
            ';
        echo $error;
        // exit();
    }
}

?>

<?php


function fill_categories($pdo)
{
    $output = '';
    $select = $pdo->prepare("select * from tbl_category order by id desc");
    $select->execute();

    $result = $select->fetchAll();

    foreach ($result as $row) {
        $output .= '<option value="' . $row["category"] . '">' . $row["category"] . '</option>';
    }

    return $output;
}

?>

<!-- Select2 -->
<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

<script src="plugins/select2/js/select2.full.min.js"></script>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Add Product</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">

            <!-- CARD -->
            <div class="card card-outline card-success card-custom">
                
                <!-- card-header -->
                <div class="card-header">
                    <div class="row">

                        <div class="col-md-10">
                            <h3 class="card-title">Product Form</h3>
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


                <!-- form start -->
                <!-- Add Product form -->
                <div class="">
                    <form role="form" action="" method="post" class="card-body row" enctype="multipart/form-data">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Product name</label>
                                <input type="text" name="txtproductname" class="form-control" id="exampleInputEmail1" placeholder="Enter Name" required>
                            </div>

                            <!-- select -->
                            <div class="form-group">
                                <label>Category</label>
                                <select type="text" class="form-control select2" id="selectcategory" name="txtselectcategory">
                                    <option value="" disabled selected>Select Category</option>
                                    <!-- Get Categories from tbl_category -->
                                    <?php echo fill_categories($pdo); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Purchase Price</label>
                                <input type="number" min="1" step="1" name="purchaseprice" class="form-control" id="exampleInputEmail1" placeholder="Enter..." required>
                            </div>
                            <div class="form-group">
                                <label for="">Sale Price</label>
                                <input type="number" min="1" step="1" name="salesprice" class="form-control" placeholder="Enter..." required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Stock</label>
                                <input type="number" min="1" step="1" name="txtstock" class="form-control" placeholder="Enter..." required>
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea type="text" name="txtdescription" class="form-control" placeholder="Enter..." rows="4" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="">Product Image</label>
                                <input type="file" name="productimage" placeholder="Enter..." required accept=".jpg, .jpeg, .gif, png">
                                <p>Upload Image</p>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success" name="btnaddproduct">Add Product</button>
                        </div>
                    </form>

                    <!-- /.card-body -->
                </div>

            </div>
            <!-- /.CARD -->
            

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