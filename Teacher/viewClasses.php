
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

    // $query = "SELECT tbcourse.courseName,tblshift.shiftName
    // FROM tblteacher
    // INNER JOIN tbcourse ON tbcourse.Id = tblteacher.classId
    // INNER JOIN tblshift ON tblshift.Id = tblteacher.classArmId
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
                $query = "SELECT * FROM tblteacher WHERE Id = $teacherId";
                $result = mysqli_query($conn, $query);      

                if ($result) {
                    $row = mysqli_fetch_assoc($result);
                    // $teacherName = $row['teacher_name'];
                    // $courseCount = $row['course_count'];
            ?>

            <h1 class="h3 mb-0 text-gray-800">Class Teacher Dashboard (<?php echo $teacherName; ?>)</h1>

            <?php
                }
            }
            ?>

            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
          </div>
          <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <?php
                        if (isset($_GET['id'])) {
                            $teacherId = $_GET['id'];
                            $query = "SELECT * FROM tblteacher WHERE Id = $teacherId";
                            $result = mysqli_query($conn, $query);      

                            if ($result) {
                                $row = mysqli_fetch_assoc($result);
                                // $teacherName = $row['teacher_name'];
                                // $courseCount = $row['course_count'];
                        ?>                          
                            <h6 class="m-0 font-weight-bold text-primary">Course Assigned to <?php echo $teacherName; ?></h6>
                        <?php 
                              }
                        
                        }?>    
                        </div>
                        <div class="table-responsive p-3">
                            
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Course Name</th>
                                    <th>Course Shift</th>
                                
                                    <!-- <th>Edit</th> -->
                                    <th>Delete</th>
                                </tr>
                                </thead>
                            
                                <tbody>

                                <?php
                                    if (isset($_GET['id'])) {
                                        $teacherId = $_GET['id'];
                                        $query = "SELECT tca.*, co.courseName, s.shiftName
                                                FROM tblteacher_course_assignment AS tca
                                                JOIN tblcourse AS co ON tca.courseId = co.Id
                                                JOIN tblshift AS s ON tca.shiftId = s.Id
                                                WHERE tca.teacherId = $teacherId";                  
                                        $rs = $conn->query($query);
                                        $num = $rs->num_rows;
                                        $sn = 0;
                                        $status = "";
                                        if ($num > 0) { 
                                            while ($rows = $rs->fetch_assoc()) {
                                                $sn = $sn + 1;
                                                echo "
                                                <tr>
                                                    <td>".$sn."</td>
                                                    <td>" . $rows['courseName'] ."</td>
                                                    <td>". $rows['shiftName'] ."</td>
                                                    <td><a href='deletecourses.php?action=delete&Id=".$teacherId."&courseId=".$courseId."&shiftId=".$shiftId."'><i class='fas fa-fw fa-trash'></i></a></td>

                                                </tr>";
                                            }
                                        } else {
                                            echo "<div class='alert alert-danger' role='alert'>
                                                    No Record Found!
                                                </div>";
                                        }
                                    }
                                ?>

                                </tbody>
                            </table>
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