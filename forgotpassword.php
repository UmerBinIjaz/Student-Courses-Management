<?php
include 'Includes/dbcon.php';
$statusmsg = "";
$msg = "";
// Set the default time zone
date_default_timezone_set('Asia/Karachi');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reg = mysqli_real_escape_string($conn, $_POST['reg']);

    // Check if the registration number exists in the database
    $query = "SELECT * FROM tblstudent WHERE reg = '$reg'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(32));

        // Store the token and expiration timestamp in the database (using UTC_TIMESTAMP)
        $expiryTimestamp = strtotime('+1 hour');
        $updateTokenQuery = "UPDATE tblstudent SET reset_token = '$token', reset_token_expiry = UTC_TIMESTAMP() + INTERVAL 1 HOUR WHERE reg = '$reg'";
        $updateTokenResult = mysqli_query($conn, $updateTokenQuery);

        if ($updateTokenResult) {
            // Provide the user with a link containing the reset token
            $statusmsg = "<a href='reset-password.php?token=$token' class='btn btn-info btn-block mb-4'>Click Here</a><br>";

            //echo "Visit this link to reset your password: $resetLink";
        } else {
            echo "Error updating reset token.";
            $msg = "<div class='alert alert-danger' style='width: 64%'>Error updating reset token.</div>";

        }
    } else {
        $msg = "<div class='alert alert-danger' style='width: 64%'>Registration Id Not Found</div>";
    }
}
?>
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
              <h6 class="login-card-description">Forgot Your Account Password?</h6>
                <?php
                    echo $msg;
                ?>
              <form class="user" method="Post" action="">
                <div class="form-group">

                    <label for="reg">Registration Number:</label>
                    <input type="email" required name="reg" id="email exampleInputEmail"  class="form-control"  placeholder="Enter Registration Id">
                </div>
                <div class="form-group">    
                    <button class="btn btn-danger btn-block mb-4" type="submit" value="Login">Reset Password</button>
                    <?php
                        echo $statusmsg;
                    ?>
                </div>
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