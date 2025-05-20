<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

$statusMsg = "";

if (!isset($_SESSION['userId'])) {
  header("Location: logout.php"); // Redirect to the login page
    exit();
}

// Check if the user ID in the session matches the expected user ID
if (isset($_GET['id']) && $_SESSION['userId'] != $_GET['id']) {
  //$redirectUrl = "AddStudents.php?id=" . $_SESSION['userId']. "&courseId=";
  header("Location: logout.php"); // Redirect to unauthorized access page
  exit();
}

// Check if the user ID in the session matches the expected user ID
if (!isset($_GET['id'])) {
  //$redirectUrl = "AddStudents.php?id=" . $_SESSION['userId']. "&courseId=";
  header("Location: logout.php"); // Redirect to unauthorized access page
  exit();
}


// Set the allowed user type for the current page
$allowedUserType = 'Teacher'; // Change this to the allowed user type for the specific page

// Check if the user is logged in and has the allowed user type
if (isset($_SESSION['userType']) && $_SESSION['userType'] !== $allowedUserType) {
    // Redirect to unauthorized access page
    header("Location: logout.php");
    exit();
}


// Check if the form is submitted
if(isset($_POST['update'])) {
    // Retrieve form values
    $name = $_POST['name'];
    $emailAddress = $_POST['emailAddress'];
    $password = $_POST['password']; // New password

    // Hash the new password using MD5
    $hashedPassword = md5($password);

    // Update the teacher's profile in the database
    $updateQuery = "UPDATE tblteacher SET 
                    name = '$name',
                    emailAddress = '$emailAddress', 
                    password = '$hashedPassword' 
                    WHERE Id = '$_SESSION[userId]'";

    $result = mysqli_query($conn, $updateQuery);

    if ($result) {
        $statusMsg = "<div class='alert alert-success'>Profile updated successfully.</div>";
    } else {
        $statusMsg = "<div class='alert alert-danger'>Error updating profile: " . mysqli_error($conn) . "</div>";
    }
}

// Fetch the teacher's current profile information
$fetchProfileQuery = "SELECT * FROM tblteacher WHERE Id = '$_SESSION[userId]'";
$profileResult = mysqli_query($conn, $fetchProfileQuery);
$row = mysqli_fetch_assoc($profileResult);

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
  <title>Dashboard</title>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <!-- Sidebar -->
   <?php include "Includes/sidebar.php";?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <!-- TopBar -->
           <?php include "Includes/topbar.php";?>
        <!-- Topbar -->
        <!-- Container Fluid-->
        <div class="container-fluid" id="container-wrapper">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Teacher Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Your Profile</h6>
                        <?php echo $statusMsg; ?>
                    </div>
                    <div class="card-body">
                  <form method="post">
                   <div class="form-group row mb-3">
                        <div class="col-xl-6">
                          <label class="form-control-label">Name<span class="text-danger ml-2">*</span></label>
                          <input type="text" class="form-control" required name="name" value="" id="exampleInputFirstName">
                        </div>
                        <div class="col-xl-6">
                          <label class="form-control-label">Email Address<span class="text-danger ml-2">*</span></label>
                          <input type="email" class="form-control" required name="emailAddress" value="" id="exampleInputFirstName" >
                        </div>                        
                    </div>
                     <div class="form-group row mb-3">
                        <div class="col-xl-6">
                          <label class="form-control-label">Password<span class="text-danger ml-2">*</span></label>
                          <input type="password" class="form-control" name="password" value="" id="exampleInputFirstName" >
                        </div>
                    </div>
                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                  </form>
                </div>                     
            </div>                   
          </div>
        </div>
        <!---Container Fluid-->
        </div>
      <!-- Footer -->
      <?php include 'includes/footer.php';?>
      <!-- Footer -->
    </div>
  </div>

  <!-- Scroll to top -->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <script src="../vendor/jquery/jquery.min.js"></script>
  <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/ruang-admin.min.js"></script>
  <script src="../vendor/chart.js/Chart.min.js"></script>
  <script src="js/demo/chart-area-demo.js"></script>  
</body>

</html>