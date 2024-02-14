<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<script src="sweetalert/sweetalert.js"></script>
<script src="sweetalert2/package/dist/sweetalert2.all.min.js"></script>




<?php

include_once 'connectdb.php';

error_reporting(0);
session_start();


if (isset($_POST['btn_login'])) {
  $useremail = $_POST['txt_email'];
  $password = $_POST['txt_password'];

  $select = $pdo->prepare("select * from tbl_user where useremail='$useremail' AND password='$password'");
  $select->execute();

  $row = $select->fetch(PDO::FETCH_ASSOC);

  if (!empty($password && $useremail)) {
    if ($row['useremail'] == $useremail and $row['password'] == $password and $row['userrole'] == 'Admin') {

      echo '

      <style>
        body{
          padding-top: 20vh;
        }
      </style>
    
      <script type="text/javascript">
      jQuery(function validation(){
  
      swal.fire({
        title: "Success",
        text: "Login successful, ' . $row['username'] . '",
        icon: "success",
        button: "OK",
      });
      });
      </script>
      
      
      
      ';

      $_SESSION['id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      $_SESSION['useremail'] = $row['useremail'];
      $_SESSION['userrole'] = $row['userrole'];

      header('refresh:1;dashboard.php');
    } else if ($row['useremail'] == $useremail and $row['password'] == $password and $row['userrole'] == 'User') {

      $_SESSION['id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      $_SESSION['useremail'] = $row['useremail'];
      $_SESSION['userrole'] = $row['userrole'];

      echo '
      
      <script type="text/javascript">
      jQuery(function validation(){
  
      swal.fire({
        title: "Success",
        text: "Login Successful, ' . $row['username'] . '",
        icon: "success",
        button: "OK",
      });
  
      });
      
      </script>
        
      ';

      header('refresh:1;userdashboard.php');
    } else {

      echo '

      <style>
        body{
          padding-top: 20vh;
        }
      </style>
      
      <script type="text/javascript">
      jQuery(function validation(){
  
      swal.fire({
        title: "Failed",
        text: "Invalid Credentials",
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
        title: "Empty Fields",
        text: "All fields are required",
        icon: "error",
        button: "OK",
      });
      });
      </script>';
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>POS | Log in (v2)</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="#" class="h1"><b>INVENTORY</b>POS</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="" method="post">
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email" name="txt_email" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password" name="txt_password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember">
                  Remember Me
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block" name="btn_login">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>


        <!-- social-auth-links -->
        <!-- /.social-auth-links -->

        <p class="mb-1">
          <a href="#" onclick="swal.fire('To Get Password', 'Please Contact Admin or your Service Provider', 'error',)">I forgot my password</a>
        </p>
        <!-- <p class="mb-0">
        <a href="register.html" class="text-center">Register a new membership</a>
      </p> -->
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->


</body>

</html>