

<style>
    .card-custom {
        height: 87%;
    }

    .custom-table {
        height: 550px;
        overflow: hidden;
        overflow-y: scroll;
    }

    .container-fluid {
        height: fit-content;
    }

    @media(max-width:768px) {
        .card-custom {
            height: 100%;
        }

        .custom-table {
            height: 550px;
            overflow: hidden;
            overflow-y: scroll;
        }
    }

    .custom-table {
        scrollbar-width: none;
    }

    .custom-table::-webkit-scrollbar {
        width: 3px;
    }

    .custom-table::-webkit-scrollbar-thumb {
        background-color: #4d5161;
    }

    .custom-table::-webkit-scrollbar-thumb {
        background-color: #969baf;
    }
</style>
<!-- Theme style -->
<link rel="stylesheet" href="dist/css/adminlte.min.css">



<?php
include_once 'connectdb.php';

session_start();

include_once 'adminheader.php';

/* we redirect to index.php if empty username and userRole = User */

if ($_SESSION['useremail'] == "" or $_SESSION["userrole"] != "Admin" or $_SESSION["userrole"] != "User") {
    header('location:index.php');
}

// Title for each page (echoed all names in adminheader)
$_SESSION['pagetitle'] = 'Category';

$_SESSION['tbl'] = 'tbl_category';



// Validation on btnsave
if (isset($_POST['btnsave'])) {
    $txtcategory = $_POST['txtcategory'];

    if (!empty($txtcategory)) {

        // Checking if category Already exist 
        $select = $pdo->prepare("select category from tbl_category where category='$txtcategory'");
        $select->execute();
        if ($select->rowCount() > 0) {
            echo '
            
                <script type="text/javascript">
                jQuery(function validation(){
    
                swal.fire({
                title: "Failed",
                text: "Category name Already exist",
                icon: "error",
                button: "OK",
                });
    
                });
                
                </script>
            
            ';
        } else {

            // 
            $insert = $pdo->prepare("insert into tbl_category (category) values(:category)");

            $insert->bindParam(":category", $txtcategory);

            $insert->execute();

            if ($insert->rowcount()) {

                echo '
                
                    <script type="text/javascript">
                    jQuery(function validation(){
        
                    swal.fire({
                    title: "Success",
                    text: "Category Added Successfully",
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
                    text: "Unable to add Category",
                    icon: "error",
                    button: "OK",
                    });
        
                    });
                    
                    </script>
                    
                    ';
            }
        }
    } elseif (empty($txtcategory)) {
        echo '

            <script type="text/javascript">
            jQuery(function validation(){

            swal.fire({
            title: "Empty Fields    ",
            text: "All Fields are required",
            icon: "error",
            button: "Retry",
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
        <div class="container-fluid custom-container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Categories</h1>
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


            <div class="card card-outline card-warning card-custom">
                <div class="card-header">
                    <h3 class="card-title">Category Form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form role="form" action="" method="post">
                    <div class="card-body card-warning row">

                        <!-- From Column -->

                        <div class="col-md-4">


                            <?php

                            // Editing Categories

                            if (isset($_POST['btnedit'])) {


                                // Indicate category is in edit mode session
                                $_SESSION['Edit_icon'] = '<span><p style="font-size: small; padding: 0; margin: 0;">Edit-Mode</p></span>';

                                $select = $pdo->prepare("select * from tbl_category where id=" . $_POST['btnedit']);
                                $select->execute();

                                if ($select) {
                                    $row = $select->fetch(PDO::FETCH_OBJ);

                                    echo $_SESSION['Edit_icon'];
                                    echo '
                                        <?php echo $_SESSION["Edit_icon"]; ?>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Category</label>
                                            <input type="text" value="' . $row->category . '" name="txtcategory" class="form-control" id="exampleInputEmail1" placeholder="Enter Category name">
                                            <input type="hidden" value="' . $row->id . '" id="productPrice" name="txtcatid">
                                        </div>

                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary" name="btnupdate">Update</button>
                                        </div>

                                    ';
                                }
                            } else {
                                $_SESSION['Edit_icon'] = ' ';
                                echo '
                                    
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Category</label>
                                            <input type="text" name="txtcategory" class="form-control" id="exampleInputEmail1" placeholder="Enter Category name">
                                        </div>

                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-warning" name="btnsave">Save</button>
                                        </div>
                                    ';
                            }


                            // Updating Categories after editing

                            if (isset($_POST['btnupdate'])) {
                                $txtcategory = $_POST['txtcategory'];
                                $txtcatid = $_POST['txtcatid'];

                                $update = $pdo->prepare('update tbl_category set category =:category where id=' . $txtcatid);

                                $update->bindParam(':category', $txtcategory);

                                $update->execute();


                                // Category Success/error Message
                                if ($update->rowCount()) {
                                    echo '
                                    
                                        <script type="text/javascript">
                                        jQuery(function validation(){

                                        swal.fire({
                                        title: "Success",
                                        text: "Category Updated Successfully",
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
                                        text: "Unable to Update Category, try changing input and retry",
                                        icon: "error",
                                        button: "OK",
                                        });
                            
                                        });
                                        
                                        </script>
                                        
                                        ';
                                }
                            }

                            ?>


                        </div>

                        <!-- margin -->
                        <div class="col-md-1"></div>

                        <!-- Table Column -->
                        <div id="tablecategory1" class="col-md-7 custom-table">
                            <div id="table-row" class="row">
                                <div class="col-md-12"></div>
                            </div>
                            <table id="tablecategory" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Category</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php

                                    $select = $pdo->prepare("select * from tbl_category order by id desc");

                                    $select->execute();

                                    while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                        echo '
                                        <tr>
                                            <td>' . $row->id . '</td>
                                            <td>' . $row->category . '</td>
                                            <td>
                                                <form method="post">
                                                    <button value="'. $row->id .'"  type="submit" class="btn btn-block bg-olive btn-small" name="btnedit" style="width: fit-content;"><i class="nav-icon fas fa-edit"></i></button>
                                                </form>
                                            </td>
                                            <td>
                                                <div id='. $row->id .' type="submit" class="btn btn-block btn-danger btn-small btndelete" name="btndelete" style="width: fit-content;"><i class="nav-icon fas fa-trash"></i></div>
                                            </td>
                                        </tr>
                                        ';
                                    }

                                    ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                    <!-- /.card-body -->


                </form>
            </div>


        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Page specific script -->
<script>
    $(document).ready(function() {
        $('#tablecategory').DataTable({
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
                    title: 'Confirm Category Delete',
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