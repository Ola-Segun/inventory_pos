<?php
include_once 'connectdb.php';

session_start();


if ($_SESSION['useremail'] == "" or $_SESSION['role'] == "User") {
    header('location:index.php');
}


//On page 2
$_SESSION['pagetitle'] = 'Product list';
$_SESSION['tbl'] = 'tbl_product';
include_once 'adminheader.php';

?>

<!-- Theme style -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Product List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Product List</li>
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

                        <div class="col-10">
                            <h3 class="card-title">Product list</h3>
                        </div>
                        <div class="col-md-2">
                            <h3 class="card-title">
                                <!-- Back to Product list button -->
                                <a href="addproduct.php" class="btn btn-info" style="display:flex; align-items:center; gap:5px;">
                                    <i class="fas fa-angle-left right"></i>Add Product
                                </a>
                            </h3>
                        </div>
                    </div>
                </div>
                <!-- Table Column -->
                <div id="tablecategory1" class="col-12 custom-table">
                    <div id="table-row" class="row">
                        <div class="col-md-12"></div>
                    </div>
                    <table id="tablecategory" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Product Categoty</th>
                                <th>Puchase Price</th>
                                <th>Sales Price</th>
                                <th>Product Stock</th>
                                <th>Product Des</th>
                                <th>Product image</th>
                                <th>view</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $select = $pdo->prepare("select * from tbl_product order by id desc");

                            $select->execute();

                            while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                echo '
                                    <tr>
                                        <td>' . $row->id . '</td>
                                        <td>' . $row->productname . '</td>
                                        <td>' . $row->productcategory . '</td>
                                        <td>' . $row->purchaseprice . '</td>
                                        <td>' . $row->salesprice . '</td> 
                                        <td>' . $row->productstock . '</td> 
                                        <td><small><em>' . $row->productdescription . '</em></small></td> 
                                        <td><img src="productimages/' . $row->productimage . '" alt="" class="img-rounded" width="40px" height="40px" style="box-shadow:#969baf 2px 2px 4px;"></td> 
                                        
                                        <td>
                                        <form method="post">
                                            <a href="productview.php?id=' . $row->id . '"  type="submit" class="btn btn-block btn-primary btn-xs" name="btnview" style="width:fit-content; padding: 7px 15px;"><i class="nav-icon fas fa-eye"></i></a>
                                        </form>
                                        </td>

                                        <td>
                                        <form method="post">
                                            <a href="editproduct.php?id=' . $row->id . '" type="submit" class="btn btn-block btn-success btn-xs" name="btnedit" style="width:fit-content; padding: 7px 15px;"><i class="nav-icon fas fa-edit"></i></a>
                                        </form>
                                        </td>

                                        <td>
                                        <div>
                                            <button id= ' . $row->id . '  type="submit" class="btn btn-block btn-danger btn-xs btndelete" name="btndelete" style="width:fit-content; padding: 7px 15px;"><i class="nav-icon fas fa-trash"></i></button>
                                        </div>
                                        </td>

                                    </tr>
                                    ';
                            }


                            ?>
                        </tbody>
                    </table>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Page specific script -->
<script>
    $(document).ready(function() {
        $('#tablecategory').DataTable({
            "order": [
                [0, "desc"]
            ],
            "buttons": ["copy", "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#table-row .col-md-12:eq(0)');
    });
</script>

<!-- <script>
    $(function() {
        $("#tablecategory").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#table-row .col-md-12:eq(0)');
    });
</script> -->

<script>
    $(document).ready(
        function() {
            $('.btndelete').click(function() {
                var tdh = $(this);
                var id = $(this).attr("id");

                Swal.fire({
                    title: 'Confirm Delete',
                    text: "Recovery is impossible after deletion",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {

                        // AJAX for deletion
                        $.ajax({
                            url: 'itemdelete.php',
                            type: 'post',
                            data: {
                                pidd: id
                            },
                            success: function(data) {
                                // console.log("Done");
                                tdh.parents('tr').hide();
                            }
                        })
                        // AJAX ends.


                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }
                })
            })
        }
    )
</script>

<?php

include_once 'sidebar.php';

?>


<?php

include_once 'footer.php';

?>