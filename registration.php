
<style>
    .my-card {
        height: 87%;
    }

    .my-table {
        height: 550px;
        overflow: hidden;
        overflow-y: scroll;
    }

    .container-fluid {
        height: fit-content;
    }

    @media(max-width:768px) {
        .my-card {
            height: 100%;
        }

        .my-table {
            height: 550px;
            overflow: hidden;
            overflow-y: scroll;
        }
    }

    .my-table {
        scrollbar-width: none;
    }

    .my-table::-webkit-scrollbar {
        width: 3px;
    }

    .my-table::-webkit-scrollbar-thumb {
        background-color: #4d5161;
    }

    .my-table::-webkit-scrollbar-thumb {
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

if ($_SESSION['useremail'] == "" or $_SESSION["userrole"] != "Admin") {
    header('location:index.php');
}

$_SESSION['pagetitle'] = 'Registration';
$_SESSION['tbl'] = 'tbl_user';


// We check if the form button is clicked

if (isset($_POST['btnsave'])) {

    $txtusername = $_POST['txtusername'];
    $txtemail = $_POST['txtemail'];
    $txtpassword = $_POST['txtpassword'];
    $txtselect_option = $_POST['txtselect_option'];

    // echo "Result ::::>";
    // echo $txtusername.' - '.$txtemail.' - '.$txtpassword.'-'.$txtselect_option;

    $select = $pdo->prepare("select useremail from tbl_user where useremail='$txtemail'");

    $select->execute();

    if ($select->rowCount() > 0) {
        echo '
    
                    <script type="text/javascript">
                    jQuery(function validation(){

                    swal.fire({
                    title: "Failed",
                    text: "Email Already in Use.",
                    icon: "error",
                    button: "OK",
                    });

                    });
                    
                    </script>
                    
                    ';
    } else {

        if (!empty($txtusername && $txtemail && $txtpassword && $txtselect_option)) {
            $insert = $pdo->prepare("insert into tbl_user
            (username, useremail, password, userrole) 
            values(:name, :email, :pass, :role)");

            $insert->bindParam(":name", $txtusername);
            $insert->bindParam(":email", $txtemail);
            $insert->bindParam(":pass", $txtpassword);
            $insert->bindParam(":role", $txtselect_option);

            $insert->execute();

            if ($insert->rowCount()) {
                echo '
        
                        <script type="text/javascript">
                        jQuery(function validation(){
    
                        swal.fire({
                        title: "Success",
                        text: "User added Successfully",
                        icon: "success",
                        button: "OK",
                        });
    
                        });
                        
                        </script>
                        
                        ';
            }
        } else {
            echo '
        
                <script type="text/javascript">
                jQuery(function validation(){

                swal.fire({
                title: "Failed",
                text: "Unable to add User",
                icon: "error",
                button: "Retry",
                });

                });
                
                </script>
                
                ';
        }
    }
}

// A delete Query without AJAX

// if (isset($_POST['btndelete'])) {

//     // $txtemail = $_POST['txtemail'];

//     $delete = $pdo->prepare("delete from tbl_user where id=" . $_POST["btndelete"]);
//     $delete->execute();
//     if ($delete->rowCount()) {
//         echo '
    
//             <script type="text/javascript">
//             jQuery(function validation(){

//             swal.fire({
//             title: "Success",
//             text: "User deleted Successfully",
//             icon: "success",
//             button: "OK",
//             });

//             });
            
//             </script>
            
//             ';
//     } else {
//         echo "unable to delete";
//     }
// }



?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Registration</h1>
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


            <div class="card card-outline card-info my-card">
                <div class="card-header">
                    <h3 class="card-title">Registration Form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <div class="card-body row">
                    <form role="form" action="" method="post" class="col-md-4">

                        <div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Username</label>
                                <input type="text" name="txtusername" class="form-control" id="exampleInputEmail1" placeholder="Enter Username" required>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" name="txtemail" class="form-control" id="exampleInputEmail1" placeholder="Enter email" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" name="txtpassword" class="form-control" id="exampleInputPassword1" placeholder="Password" required>
                            </div>

                            <!-- select -->
                            <div class="form-group">
                                <label>Role</label>
                                <select class="form-control" name="txtselect_option" required>
                                    <option value="" disabled selected>Select Role</option>
                                    <option>User</option>
                                    <option>Admin</option>
                                </select>
                            </div>
                        </div>

                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-info" name="btnsave">Save</button>
                        </div>
                    </form>
                    <div class="col-md-7">
                        <div id="table-row6" class="row">
                            <div class="col-md-6"></div>
                        </div>

                        <table id="tablecategory" class="table table-striped">
                        <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                    <th>Role</th>
                                    <th>Delete</th>
                                </tr>
                        </thead>
                        <tbody>
                                <?php

                                $select = $pdo->prepare("select * from tbl_user order by id desc");

                                $select->execute();

                                while ($row = $select->fetch(PDO::FETCH_OBJ)) {
                                    echo '
                                    <tr>
                                        <td>' . $row->id . '</td>
                                        <td>' . $row->username . '</td>
                                        <td>' . $row->useremail . '</td>
                                        <td>' . $row->password . '</td>
                                        <td>' . $row->userrole . '</td> 
                                        <td>
                                            <button id="' . $row->id . '"  type="submit" class="btn btn-block btn-danger btn-xs btndelete" name="btndelete">Delete</button>
                                        </td>
                                    </tr>
                                    ';
                                }


                                ?>
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>


        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Page specific script -->
<script>
    $(document).ready(function() {
        $('#tableregister').DataTable();
    });
</script>

<!-- <script>
        $(function() {
            $("#tableregister").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["colvis"]
            }).buttons().container().appendTo('#table-row6 .col-md-6:eq(0)');
            $('#tableregister').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script> -->


<script>
    $(document).ready(
        function() {
            $('.btndelete').click(function() {
                var tdh = $(this);
                var id = $(this).attr("id");

                Swal.fire({
                    title: 'Confirm User Delete',
                    text: "Account Recovery is impossible after deletion",
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