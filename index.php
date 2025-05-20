<?php 
include 'Includes/dbcon.php';
  session_start();
  error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/favicon.png" rel="icon">
    <title>IIT Attendance System - Login</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    
    <link href="css/login.css" rel="stylesheet" href="style.css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">

</head>

<body>

<main class="d-flex align-items-center min-vh-100 py-3 py-md-0">
    <div class="container">
      <div class="card login-card">
        <div class="row no-gutters">
          <div class="col-md-5">
            <img src="img/Page.jpg" alt="login" class="login-card-img">
          </div>
          <div class="col-md-7">
            <div class="card-body">
              <div class="brand-wrapper">
                <img src="img/ITLogo.png" alt="logo" class="logo">
              </div>
              <p class="login-card-description">Sign into your account</p>
              <form class="user" method="Post" action="">
              <?php

if(isset($_POST['login'])){

  $userType = $_POST['userType'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  $password = md5($password);
  //$hash = password_hash($password, PASSWORD_DEFAULT); 
  if($userType == "Administrator"){
    
    $query = "SELECT * FROM tbladmin WHERE emailAddress = '$username' AND password = '$password'";
    $rs = $conn->query($query);
    $num = $rs->num_rows;
    $rows = $rs->fetch_assoc();

    if($num > 0){

      $_SESSION['userId'] = $rows['Id'];
      $_SESSION['firstName'] = $rows['firstName'];
      $_SESSION['lastName'] = $rows['lastName'];
      $_SESSION['emailAddress'] = $rows['emailAddress'];

      echo "<script type = \"text/javascript\">
      window.location = (\"Admin/index.php\")
      </script>";
    }

    else{

      echo "<div class='alert alert-danger' role='alert'>
      Invalid Username/Password!
      </div>";

    }
  }
  else if($userType == "Teacher"){

    $query = "SELECT * FROM tblteacher WHERE emailAddress = '$username' AND password = '$password'";
    $rs = $conn->query($query);
    $num = $rs->num_rows;
    $rows = $rs->fetch_assoc();

    if ($num > 0) {
      $_SESSION['userId'] = $rows['Id'];
      $_SESSION['Name'] = $rows['name'];
      $_SESSION['emailAddress'] = $rows['emailAddress'];
      // $_SESSION['classId'] = $rows['classId'];
      // $_SESSION['classArmId'] = $rows['classArmId'];
  
      echo "<script type = \"text/javascript\">
      window.location = (\"Teacher/index.php?id=" . $rows['Id'] . "\");
      </script>";
    }
  }
  else if ($userType == "Student") {
    
    $query = "SELECT * FROM tblstudent WHERE reg = '$username' AND password = '$password'";
    $rss = $conn->query($query);

    if (!$rss) {
        echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
    } else {
        $nums = $rss->num_rows;
        $frows = $rss->fetch_assoc();

        if ($nums > 0) {
            $_SESSION['userId'] = $frows['id'];
            $_SESSION['Reg'] = $frows['reg'];
            $_SESSION['Name'] = $frows['name'];
            
            echo "<script type = \"text/javascript\">
                window.location = (\"Student/index.php?id=" . $frows['id'] . "\");
            </script>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Invalid Username/Password!</div>";
        }
    }
  }

  else{

      echo "<div class='alert alert-danger' role='alert'>
      Invalid Username/Password!
      </div>";

    }
}

?>
              
                  <div class="form-group">
                    <select required name="userType" class="form-control mb-3">
                        <option value="">--Select User Roles--</option>
                        <option value="Administrator">Administrator</option>
                        <option value="Teacher">Teacher</option>
                        <option value="Student">Student</option>
                    </select>
                  </div>                
                  <div class="form-group">
                    <label for="email" class="sr-only">Email</label>
                    <input type="email" required name="username" id="email exampleInputEmail"  class="form-control"  placeholder="Enter Email Address">
                    <!-- <input type="email" name="email" id="email" class="form-control" placeholder="Email address"> -->
                  </div>
                  <div class="form-group mb-4">
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" name="password" required class="form-control" id="password exampleInputPassword" placeholder="Enter Password">
                  </div>
                  <!-- <input type="submit" class="btn btn-success btn-block" value="Login" name="login" /> -->
                  <input name="login" id="login" class="btn btn-block login-btn mb-4" type="submit" value="Login">

                  <!-- <a href="forgotpassword.php" class="btn btn-block text-danger">Go to Student Forgot Passowrd</a> -->
                  
                </form>
                <!-- <a href="#!" class="forgot-password-link">Forgot password?</a>
                <p class="login-card-footer-text">Don't have an account? <a href="#!" class="text-reset">Register here</a></p>
                <nav class="login-card-footer-nav">
                  <a href="#!">Terms of use.</a>
                  <a href="#!">Privacy policy</a>
                </nav> -->
            </div>
          </div>
        </div>
      </div>
      <!-- <div class="card login-card">
        <img src="img/login.jpg" alt="login" class="login-card-img">
        <div class="card-body">
          <h2 class="login-card-title">Login</h2>
          <p class="login-card-description">Sign in to your account to continue.</p>
          <form action="#!">
            <div class="form-group">
              <label for="email" class="sr-only">Email</label>
              <input type="email" name="email" id="email" class="form-control" placeholder="Email">
            </div>
            <div class="form-group">
              <label for="password" class="sr-only">Password</label>
              <input type="password" name="password" id="password" class="form-control" placeholder="Password">
            </div>
            <div class="form-prompt-wrapper">
              <div class="custom-control custom-checkbox login-card-check-box">
                <input type="checkbox" class="custom-control-input" id="customCheck1">
                <label class="custom-control-label" for="customCheck1">Remember me</label>
              </div>              
              <a href="#!" class="text-reset">Forgot password?</a>
            </div>
            <input name="login" id="login" class="btn btn-block login-btn mb-4" type="button" value="Login">
          </form>
          <p class="login-card-footer-text">Don't have an account? <a href="#!" class="text-reset">Register here</a></p>
        </div>
      </div> -->
    </div>
  </main>


    <!-- Login Content -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
</body>

</html>