<?php
include_once 'connectdb.php';

session_start();


if ($_SESSION['useremail'] == "" or $_SESSION['userrole'] == "User") {
    header('location:index.php');
}

$id = $_GET['id'];
$select = $pdo->prepare("select * from tbl_product where id=$id");
$select->execute();
$row = $select->fetch(PDO::FETCH_ASSOC);

$id_db = $row['id'];
$productname_db = $row['productname'];
$productcategory_db = $row['productcategory'];
$purchaseprice_db = $row['purchaseprice'];
$salesprice_db = $row['salesprice'];
$productstock_db = $row['productstock'];
$productdescription_db = $row['productdescription'];
$productimage_db = $row['productimage'];

// Title for each page (echoed all names in adminheader)
$_SESSION['pagetitle'] = 'Edit Product';

include_once 'adminheader.php';

// Validating btnupdateproduct button
if (isset($_POST['btnupdateproduct'])) {
    $productname = $_POST['txtproductname'];
    $category = $_POST['txtselectcategory'];
    $purchaseprice = $_POST['purchaseprice'];
    $salesprice = $_POST['salesprice'];
    $stock = $_POST['txtstock'];
    $description = $_POST['txtdescription'];
    $txtid = $_POST['txtid'];

    $f_name = $_FILES['productimage']['name'];
    $f_tmp = $_FILES['productimage']['tmp_name'];
    $f_size = $_FILES['productimage']['size'];

    $f_exp = explode(".", $f_name);
    $f_extension = strtolower(end($f_exp));
    $newFileName = uniqid() . "." . $f_extension;
    $store = "productimages/" . $newFileName;
    $max_size = 1000000;


    if (!empty($productname && $category && $purchaseprice && $salesprice && $stock && $description)) {
        
        // when new image is selected
        if (!empty($f_name)) {
            /* this is if logic is to decide the type of extension reqiured to upload
            a file. If the required extension is satisfied, then we can now upload our file  */
            if ($f_extension == "jpg" || $f_extension == "jpeg" || $f_extension == "png" || $f_extension == "gif") {
                if ($f_size < 1000000) {

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

                        $update = $pdo->prepare("update tbl_product set
                            productname=:name, productcategory=:category, purchaseprice=:purprice, salesprice=:salprice, productstock=:stock, productdescription=:des, productimage=:img 
                            where id=$id");

                        $update->bindParam(":name", $productname);
                        $update->bindParam(":category", $category);
                        $update->bindParam(":purprice", $purchaseprice);
                        $update->bindParam(":salprice", $salesprice);
                        $update->bindParam(":stock", $stock);
                        $update->bindParam(":des", $description);
                        $update->bindParam(":img", $newFileName);

                        // echo $productname.' - '.$category.' - '.$purchaseprice.' - '.$salesprice.' - '.$stock.' - '.$description.' - '.$f_name;

                        if ($update->execute()) {
                            echo '
                        
                                        <script type="text/javascript">
                                        jQuery(function validation(){
                    
                                        swal.fire({
                                        title: "Success",
                                        text: "Product Updated Successfully",
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
                                text: "Unable to update Product",
                                icon: "error",
                                button: "OK",
                                });

                                });
                                
                                </script>
                                
                                ';
                        }
                    }
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
                // echo "valid file extension";
            } else {

                $error = '
                    
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
            move_uploaded_file($f_tmp, $store);
        }

        // when new image is not selected
        elseif (empty($f_name)) {


            // Checking if Product Already exist 

            $select = $pdo->prepare("select productname from tbl_product where productname='$productname'");
            $select->execute();
            if ($select->rowCount() > 0) {

                $update = $pdo->prepare("update tbl_product set
                            productname=:name, productcategory=:category, purchaseprice=:purprice, salesprice=:salprice, productstock=:stock, productdescription=:des, productimage=:img 
                            where id=$id");

                $update->bindParam(":name", $productname);
                $update->bindParam(":category", $category);
                $update->bindParam(":purprice", $purchaseprice);
                $update->bindParam(":salprice", $salesprice);
                $update->bindParam(":stock", $stock);
                $update->bindParam(":des", $description);
                $update->bindParam(":img", $productimage_db);

                if ($update->execute()) {
                    echo '
                        
                                        <script type="text/javascript">
                                        jQuery(function validation(){
                    
                                        swal.fire({
                                        title: "Success",
                                        text: "Product Updated Successfully",
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
                                text: "Unable to update Product",
                                icon: "error",
                                button: "OK",
                                });

                                });
                                
                                </script>
                                
                                ';
                }
            }
            move_uploaded_file($f_tmp, $store);
        }
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



    $id = $_GET['id'];
    $select = $pdo->prepare("select * from tbl_product where id=$id");
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);

    $id_db = $row['id'];
    $productname_db = $row['productname'];
    $productcategory_db = $row['productcategory'];
    $purchaseprice_db = $row['purchaseprice'];
    $salesprice_db = $row['salesprice'];
    $productstock_db = $row['productstock'];
    $productdescription_db = $row['productdescription'];
    $productimage_db = $row['productimage'];
}

?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Product</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">


            <div class="card card-outline card-warning card-custom">
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
                <!-- Edit Product form -->
                <div class="">
                    <form role="form" action="" method="post" class="card-body row" enctype="multipart/form-data">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Product name</label>
                                <input type="text" name="txtproductname" value="<?php echo $productname_db ?>" class="form-control" id="exampleInputEmail1" placeholder="Enter Name" required>
                            </div>

                            <!-- select -->
                            <div class="form-group">
                                <label>Category</label>
                                <select class="form-control" name="txtselectcategory">
                                    <option value="<?php echo $productcategory_db ?>" disabled selected>Select Category</option>


                                    <!-- Get Categories from tbl_category -->
                                    <?php

                                    $select = $pdo->prepare("select * from tbl_category order by id desc");
                                    $select->execute();

                                    while ($row = $select->fetch(PDO::FETCH_ASSOC)) {
                                        extract($row);
                                    ?>

                                        <!-- Get category -->

                                        <option <?php if ($row['category'] == $productcategory_db) { ?> selected="selected" <?php } ?>>

                                            <?php echo $row['category'] ?>
                                        </option>
                                    <?php

                                    }
                                    ?>




                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Purchase Price</label>
                                <input type="number" min="1" step="1" name="purchaseprice" value="<?php echo $purchaseprice_db ?>" class="form-control" id="exampleInputEmail1" placeholder="Enter..." required>
                            </div>
                            <div class="form-group">
                                <label for="">Sale Price</label>
                                <input type="number" min="1" step="1" name="salesprice" value="<?php echo $salesprice_db ?>" class="form-control" placeholder="Enter..." required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Stock</label>
                                <input type="number" min="1" step="1" name="txtstock" value="<?php echo $productstock_db ?>" class="form-control" placeholder="Enter..." required>
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea type="text" name="txtdescription" class="form-control" placeholder="Enter..." rows="4" required><?php echo $productdescription_db ?></textarea>
                            </div>
                            <div class="form-group" style="display: grid; gap: 7px;">
                                <label for="">Product Image</label>
                                <img src="productimages/<?php echo $productimage_db ?>" alt="" class="img-rounded img-responsive" width="60px" height="60px" style="box-shadow:#969baf 2px 2px 4px;">
                                <input type="file" name="productimage" placeholder="Enter...">
                                <p>Upload Image</p>
                            </div>

                            <input type="hidden" value="<?php $_GET['id'] ?>" id="productPrice" name="txtid">

                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-warning" name="btnupdateproduct">Update Product</button>
                        </div>
                    </form>

                    <!-- /.card-body -->
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