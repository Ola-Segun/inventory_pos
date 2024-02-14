<?php
include_once 'connectdb.php';

session_start();

// we redirect to index.php if empty useremail
if ($_SESSION['useremail'] == "") {
    header('location:index.php');
}


// Title for each page (echoed all names in adminheader)
$_SESSION['pagetitle'] = 'Change Password';

// Allow both Admin and User to access the change password page

if ($_SESSION['userrole'] == 'Admin'){
    include_once 'adminheader.php';
} else {
    include_once 'userheader.php';
}


// When the Update button is clicked
if (isset($_POST['btnUpdate'])) {
    $txtOldPass = $_POST['txtOldPass'];
    $txtNewPass = $_POST['txtNewPass'];
    $txtConfirmPass = $_POST['txtConfirmPass'];

    $email = $_SESSION['useremail'];

    $select = $pdo->prepare("select * from tbl_user where useremail= '$email'");
    $select->execute();

    $row = $select->fetch(PDO::FETCH_ASSOC);
    $username_db = $row['username'];
    $password = $row["password"];

    // we compare userinput and database values

    if ($txtOldPass == $password) {

        // we compare new and confirm password input
        if ($txtNewPass == $txtConfirmPass) {

            $update = $pdo->prepare("update tbl_user set password=:pass where useremail=:email");
            $update->bindParam(':pass', $txtConfirmPass);
            $update->bindParam(':email', $email);

            if ($update->execute()) {

                $select = $pdo->prepare("select * from tbl_user where useremail= '$email'");
                $select->execute();

                $row = $select->fetch(PDO::FETCH_ASSOC);
                $username_db = $row['username'];
                $password = $row["password"];

                echo '
    
                    <script type="text/javascript">
                    jQuery(function validation(){

                    swal.fire({
                    title: "Success",
                    text: "Password Updated Successfully",
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
                    text: "Password Update Failed",
                    icon: "error",
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
                title: "Oops!!!",
                text: "Password and Confirm Password didn\'t match",
                icon: "warning",
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
            title: "Warning !!!",
            text: "Incorrect old Password ",
            icon: "warning",
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
            <div class="row mb- required2">
                <div class="col-sm-6">
                    <h1 class="m-0">Change Password</h1>
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


            <!-- general form elements -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Change Password form</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form method="post">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="InputOldPassword">Old Password</label>
                            <input type="password" class="form-control" id="InputOldPassword" placeholder="Password" name="txtOldPass" required>
                        </div>
                        <div class="form-group">
                            <label for="InputPassword1">Password</label>
                            <input type="password" class="form-control" id="InputPassword1" placeholder="New Password" name="txtNewPass" required>
                        </div>
                        <div class="form-group">
                            <label for="InputPassword2">Confirm Password</label>
                            <input type="password" class="form-control" id="InputPassword2" placeholder="Confirm Password" name="txtConfirmPass" required>
                        </div>
                    </div>

                    <!-- /.card-body -->

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" name="btnUpdate">Update</button>
                    </div>
                </form>
            </div>
            <!-- /.card -->


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