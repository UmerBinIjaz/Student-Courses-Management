
<?php 
include '../Includes/dbcon.php';
include '../Includes/session.php';

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


    // $query = "SELECT tbcourse.courseName,tbl_shift.shiftName
    // FROM tblteacher
    // INNER JOIN tbcourse ON tbcourse.Id = tblteacher.classId
    // INNER JOIN tbl_shift ON tbl_shift.Id = tblteacher.classArmId
    // Where tblteacher.Id = '$_SESSION[userId]'";

    // $rs = $conn->query($query);
    // $num = $rs->num_rows;
    // $rrw = $rs->fetch_assoc();
    if (isset($_GET['Id'])) {
      $Id = $_GET['Id'];
      $query = "SELECT COUNT(*) AS course_count
      FROM tblteacher_course_assignment
      WHERE Id = $Id";    
      $result = mysqli_query($conn, $query);      
      if ($result) {
        $row = mysqli_fetch_assoc($result);
        $courseCount = $row['course_count'];
      }  
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
  <link href="../img/favicon.png" rel="icon">
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
          <?php
            if (isset($_GET['id'])) {
                $teacherId = $_GET['id'];
                $query = "SELECT * FROM tblteacher
                          WHERE Id = $teacherId";
                $result = mysqli_query($conn, $query);      

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    $teacherName = $row['name'];
                    //$courseCount = $row['course_count'];
            ?>

            <h1 class="h3 mb-0 text-gray-800">Teacher Dashboard (<?php echo $teacherName; ?>)</h1>

            <?php
                }
            }
            ?>

            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
          <!-- New User Card Example -->
            <!-- Earnings (Monthly) Card Example -->
             <?php 
// $query1=mysqli_query($conn,"SELECT * from tbcourse");                       
// $class = mysqli_num_rows($query1);
?>
            <div class="col-xl-6 col-md-12 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Courses</div>
                      <?php
                        if (isset($_GET['id'])) {
                          $teacherId = $_GET['id'];
                          $query = "SELECT COUNT(*) AS course_count
                                    FROM tblteacher_course_assignment
                                    WHERE teacherId = $teacherId";
                          $result = mysqli_query($conn, $query);      
                          if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $courseCount = $row['course_count'];

                    ?>                           
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $courseCount;?></div>
                     <?php } }?> 
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                        <span>Since last month</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-chalkboard fa-2x text-primary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Earnings (Annual) Card Example -->
             <?php 
// $query1=mysqli_query($conn,"SELECT * from tbl_shift");                       
// $classArms = mysqli_num_rows($query1);
?>
            <div class="col-xl-6 col-md-12 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Course Shifts</div>
                      <?php
                        if (isset($_GET['id'])) {
                          $teacherId = $_GET['id'];
                          $query = "SELECT COUNT(*) AS course_count
                                    FROM tblteacher_course_assignment
                                    WHERE teacherId = $teacherId";
                          $result = mysqli_query($conn, $query);      
                          if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $courseCount = $row['course_count'];

                    ?>                           
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $courseCount;?></div>
                     <?php } }?> 
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                        <span>Since last years</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-code-branch fa-2x text-success"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Pending Requests Card Example -->
            <?php 
// $query1=mysqli_query($conn,"SELECT * from tblattendance where classId = '$_SESSION[classId]' and classArmId = '$_SESSION[classArmId]'");                       
// $totAttendance = mysqli_num_rows($query1);
?>

          
          <!--Row-->

          <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>Do you like this template ? you can download from <a href="https://github.com/indrijunanda/RuangAdmin"
                  class="btn btn-primary btn-sm" target="_blank"><i class="fab fa-fw fa-github"></i>&nbsp;GitHub</a></p>
            </div>
          </div> -->

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
  <script src="js/style.js"></script>
</body>

</html>