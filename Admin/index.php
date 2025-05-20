
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
// if (!isset($_GET['id'])) {
//   //$redirectUrl = "AddStudents.php?id=" . $_SESSION['userId']. "&courseId=";
//   header("Location: logout.php"); // Redirect to unauthorized access page
//   exit();
// }


// Set the allowed user type for the current page
$allowedUserType = 'Admin'; // Change this to the allowed user type for the specific page

// Check if the user is logged in and has the allowed user type
if (isset($_SESSION['userType']) && $_SESSION['userType'] !== $allowedUserType) {
    // Redirect to unauthorized access page
    header("Location: logout.php");
    exit();
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
            <h1 class="h3 mb-0 text-gray-800">Administrator Dashboard</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>

          <div class="row mb-3">
          <!-- Students Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Students</div>
                      <?php
                        $countStudentsQuery = "SELECT COUNT(DISTINCT reg) AS totalStudents FROM tblstudent";
                        $result = mysqli_query($conn, $countStudentsQuery);
                        if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $students = $row['totalStudents'];
                      ?>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $students;?></div>
                       <?php } 
                      else{
                        ?>
                         <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">0</div>
                        <?php
                       }
                       ?>
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 20.4%</span>
                        <span>Since last month</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-info"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Class Card -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Courses</div>
                      <?php
                        $countStudentsQuery = "SELECT COUNT(courseName) AS totalCourses FROM tblcourse";
                        $result = mysqli_query($conn, $countStudentsQuery);
                        if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $Courses = $row['totalCourses'];
                      ?>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $Courses;?></div>
                       <?php } 
                       else{
                        ?>
                         <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">0</div>
                        <?php
                       }
                       ?>                      
                      <!-- <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $class;?></div> -->
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
            <!-- Class Arm Card -->

            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Class Shifts</div>
                      <?php
                        $countStudentsQuery = "SELECT COUNT(shiftName) AS totalShifts FROM tblshift";
                        $result = mysqli_query($conn, $countStudentsQuery);
                        if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $Shifts = $row['totalShifts'];
                      ?>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $Shifts;?></div>
                       <?php } 
                       else{
                        ?>
                         <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">0</div>
                        <?php
                       }
                       ?>                        
                      <!-- <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $classArms;?></div> -->
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
            
            <!-- Std Att Card  -->

            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card h-100">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-uppercase mb-1">Total Student Attendance</div>
                      <?php
                        $countStudentsQuery = "SELECT COUNT(reg) AS totAttendance FROM tblattend";
                        $result = mysqli_query($conn, $countStudentsQuery);
                        if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $totAttendance = $row['totAttendance'];
                      ?>
                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $totAttendance;?></div>
                       <?php } 
                       else{
                        ?>
                         <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">0</div>
                        <?php
                       }
                       ?>                         
                      <!-- <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $totAttendance;?></div> -->
                      <div class="mt-2 mb-0 text-muted text-xs">
                        <!-- <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 1.10%</span>
                        <span>Since yesterday</span> -->
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-secondary"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Teachers Card  -->

                        <div class="col-xl-3 col-md-6 mb-4">
                          <div class="card h-100">
                            <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                  <div class="text-xs font-weight-bold text-uppercase mb-1">Teachers</div>
                                  <?php
                                    $countStudentsQuery = "SELECT COUNT(emailAddress) AS totTeachers FROM tblteacher";
                                    $result = mysqli_query($conn, $countStudentsQuery);
                                    if ($result) {
                                    $row = mysqli_fetch_assoc($result);
                                    $totTeachers = $row['totTeachers'];
                                  ?>
                                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $totTeachers;?></div>
                                  <?php } 
                                  else{
                                    ?>
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">0</div>
                                    <?php
                                  }
                                  ?>                                    
                                  <!-- <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $classTeacher;?></div> -->
                                  <div class="mt-2 mb-0 text-muted text-xs">
                                    <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                    <span>Since last years</span> -->
                                  </div>
                                </div>
                                <div class="col-auto">
                                  <i class="fas fa-chalkboard-teacher fa-2x text-danger"></i>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
          

                         <!-- Session and Terms Card  -->

                        <div class="col-xl-3 col-md-6 mb-4">
                          <div class="card h-100">
                            <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                  <div class="text-xs font-weight-bold text-uppercase mb-1">Session & Terms</div>
                                  <?php
                                    $countStudentsQuery = "SELECT COUNT(sessionName) AS totSessions FROM tblsessionterm";
                                    $result = mysqli_query($conn, $countStudentsQuery);
                                    if ($result) {
                                    $row = mysqli_fetch_assoc($result);
                                    $totSessions = $row['totSessions'];
                                  ?>
                                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $totSessions;?></div>
                                  <?php } 
                                  else{
                                    ?>
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">0</div>
                                    <?php
                                  }
                                  ?>                                   
                                  <!-- <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $sessTerm;?></div> -->
                                  <div class="mt-2 mb-0 text-muted text-xs">
                                    <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                    <span>Since last years</span> -->
                                  </div>
                                </div>
                                <div class="col-auto">
                                  <i class="fas fa-calendar-alt fa-2x text-warning"></i>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>


                        <!-- Terms Card  -->

                        <div class="col-xl-3 col-md-6 mb-4">
                          <div class="card h-100">
                            <div class="card-body">
                              <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                  <div class="text-xs font-weight-bold text-uppercase mb-1">Terms</div>
                                  <?php
                                    $countStudentsQuery = "SELECT COUNT(termName) AS totterm FROM tblterm";
                                    $result = mysqli_query($conn, $countStudentsQuery);
                                    if ($result) {
                                    $row = mysqli_fetch_assoc($result);
                                    $totterm = $row['totterm'];
                                  ?>
                                  <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo $totterm;?></div>
                                  <?php } 
                                  else{
                                    ?>
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">0</div>
                                    <?php
                                  }
                                  ?>                   
                                  <div class="mt-2 mb-0 text-muted text-xs">
                                    <!-- <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                    <span>Since last years</span> -->
                                  </div>
                                </div>
                                <div class="col-auto">
                                  <i class="fas fa-th fa-2x text-info"></i>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
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
</body>

</html>