<?php
include 'Includes/dbcon.php';

// Set the default time zone
date_default_timezone_set('Asia/Karachi');

// Retrieve the token from the POST or GET parameters
$token = isset($_POST['token']) ? mysqli_real_escape_string($conn, $_POST['token']) : (isset($_GET['token']) ? mysqli_real_escape_string($conn, $_GET['token']) : '');

$msg = "";

if ($token) {
    // Check if the token exists in the database and has not expired
    $query = "SELECT * FROM tblstudent WHERE reset_token = '$token' AND reset_token_expiry > UTC_TIMESTAMP()";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Token is valid, allow the user to reset their password
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = mysqli_real_escape_string($conn, $_POST['new_password']);

            // Update the user's password in the database
            $hashedPassword = md5($newPassword); // You may use a stronger hash function, like password_hash
            $updatePasswordQuery = "UPDATE tblstudent SET password = '$hashedPassword', reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = '$token'";
            $updatePasswordResult = mysqli_query($conn, $updatePasswordQuery);

            if ($updatePasswordResult) {
                $msg = "<div class='alert alert-success' style='width: 64%'>Password Updated Successfully</div>";
            } else {
                $msg = "<div class='alert alert-danger' style='width: 64%'>Error in Updating Password</div>";
            }
        }
    } else {
        $msg = "<div class='alert alert-danger' style='width: 64%'>Invalid / Expire Token Found</div>";
    }
} else {
    $msg = "<div class='alert alert-danger' style='width: 64%'>Token Not Provided</div>";
}
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
              <h6 class="login-card-description">Forgot Your Account Password?</h6>
                <?php
                    echo $msg;
                ?>
              <form class="user" method="Post" action="">
                <input type="hidden" name="token"  value="<?php echo $token; ?>">
                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" required id="email exampleInputEmail"  class="form-control"  placeholder="Enter Your New Password">
                <button class="btn btn-danger btn-block mb-4" type="submit" value="Login">Submit</button>
                <a href="index.php" class="text-danger">Go to Login Page</a>
              </form>
            </div>
          </div>
        </div>
      </div>

    </div>
  </main>


    <!-- Login Content -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
</body>

</html>