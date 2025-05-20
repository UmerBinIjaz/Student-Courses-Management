<?php 
$regNos = [];
error_reporting(E_ALL);
ini_set('display_errors', 1);
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


$statusMsg = "";

if (isset($_GET['courseId']) && isset($_GET['shiftId'])) {
    $courseId = $_GET['courseId'];
    $shiftId = $_GET['shiftId'];

    // Fetch the existing maximum class number for the given course and shift
    $classNoQuery = "SELECT MAX(class_number) AS maxClassNo FROM tblattend WHERE course_id = '$courseId' AND shift_id = '$shiftId'";
    $classNoResult = mysqli_query($conn, $classNoQuery);
    $classNoRow = mysqli_fetch_assoc($classNoResult);
    $classNo = ($classNoRow['maxClassNo'] !== null) ? $classNoRow['maxClassNo'] + 1 : 1;

    if (isset($_POST['save'])) {
        $reg = $_POST['reg'];

        $attendanceDate = $_POST['attendanceDate'];


        // Check if 'check' is set in the POST data
        if (isset($_POST['check'])) {
            $check = $_POST['check'];
        } else {
            $check = array(); // Set it as an empty array if not set
        }

        $N = count($reg);
        // date_default_timezone_set('Asia/Karachi');
        // $attendanceTime = date("h:i:s");
        // $attendanceDateTime = $attendanceDate . ' ' . $attendanceTime;

        for ($i = 0; $i < $N; $i++) {
            $reg[$i];

            // Check if the 'check' checkbox is selected for this student
            $attendanceStatus = in_array($reg[$i], $check) ? '1' : '0';

            $qquery = mysqli_query($conn, "INSERT INTO tblattend (reg, course_id, shift_id, class_number, attendanceStatus, attendance_date) VALUES ('$reg[$i]', '$courseId', '$shiftId', '$classNo' ,'$attendanceStatus', '$attendanceDate')");

            if ($qquery) {
                $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Attendance Taken Successfully!</div>";
            } else {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
            }
        }
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



   <script>
    function classArmDropdown(str) {
    if (str == "") {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("txtHint").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","ajaxClassArms2.php?cid="+str,true);
        xmlhttp.send();
    }
}
</script>
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
            <h1 class="h3 mb-0 text-gray-800">Take Attendance (Today's Date : <?php echo $todaysDate = date("m-d-Y");?>)</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">All Student in Class</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->


              <!-- Input Group -->
        <form method="post">
            <div class="row">
              <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center ">
                        <!-- <label for="attendanceDate">Select Attendance Date:</label> -->
                        <h6 class="m-0 font-weight-bold text-primary w-100">Select Attendance Date</h6>

                        <!-- <input type="date" name="attendanceDate" class="form-control" required> -->
                        <input
  id="party"
  type="datetime-local"
  name="attendanceDate"
  value="" required />
                    </div>
              </div>

              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <?php
                  if (isset($_GET['id'])) {
                      $teacherId = $_GET['id'];
                      $query = "SELECT
                                          s.id AS student_id,
                                          s.reg AS student_registration,
                                          s.name AS student_name,
                                          s.password AS student_password,
                                          s.dateCreated AS student_date_created,
                                          scs.id AS student_course_shift_id,
                                          tc.courseName,
                                          ts.shiftName
                                      FROM
                                      tblstudent AS s
                                      JOIN
                                      tblstudent_course_shift AS scs ON s.id = scs.student_id
                                      JOIN
                                      tblcourse AS tc ON scs.course_id = tc.id
                                      JOIN
                                      tblshift AS ts ON scs.shift_id = ts.id
                                      WHERE
                                          ts.id = $shiftId
                                          AND tc.id = $courseId ORDER BY student_registration";
                      $result = mysqli_query($conn, $query);

                      // Check if there are rows returned
                      if ($result && mysqli_num_rows($result) > 0) {
                          $row = mysqli_fetch_assoc($result);
                      ?>
                          <h6 class="m-0 font-weight-bold text-primary">All Student in (<?php echo $row['courseName'].' - '.$row['shiftName'];?>) Class</h6>
                          <h6 class="m-0 font-weight-bold text-danger">Note: <i>Click on the checkboxes beside each student to take attendance!</i></h6>
                        <?php 
                            } else {
                        ?>
                          <h6 class="m-0 font-weight-bold text-danger">No records found! Please insert students into the database first. Then, you will be able to take attendance.</h6>
                        <?php
                            }
                  }
                ?>

                </div>
                <div class="table-responsive p-3">
                <?php echo $statusMsg; ?>
                  <table class="table align-items-center table-flush table-hover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>RegNo</th>
                        <th>Name</th>
                        <th>CourseName</th>
                        <!-- <th>Select Attendance Date</th> -->
                        <th>ShiftName</th>
                        <th>Check(checkbox)</th>
                      </tr>
                    </thead>
                    
                    <tbody>
                    <?php
                                  if (isset($_GET['courseId']) && isset($_GET['shiftId'])) {
                                      $courseId = $_GET['courseId'];
                                      $shiftId = $_GET['shiftId'];

                                      if (isset($_GET['id'])) {
                                          $teacherId = $_GET['id'];
                                          $query = "SELECT
                                          s.id AS student_id,
                                          s.reg AS student_registration,
                                          s.name AS student_name,
                                          s.password AS student_password,
                                          s.dateCreated AS student_date_created,
                                          scs.id AS student_course_shift_id,
                                          tc.courseName,
                                          ts.shiftName
                                      FROM
                                      tblstudent AS s
                                      JOIN
                                      tblstudent_course_shift AS scs ON s.id = scs.student_id
                                      JOIN
                                      tblcourse AS tc ON scs.course_id = tc.id
                                      JOIN
                                      tblshift AS ts ON scs.shift_id = ts.id
                                      WHERE
                                          ts.id = $shiftId
                                          AND tc.id = $courseId ORDER BY student_registration";
                                      

                                          $rs = $conn->query($query);
                                          $num = $rs->num_rows;
                                          $sn = 0;
                                          $status = "";

                          if($num > 0)
                          { 
                            while ($rows = $rs->fetch_assoc()) {
                              $sn++;
                              $studentId = $rows['student_id']; // Use the correct alias
                          
                              echo '
                              <tr>
                                  <td>' . $sn . '</td>
                                  <td>' . $rows['student_registration'] . '</td>
                                  <td>' . $rows['student_name'] . '</td>
                                  <td>' . $rows['courseName'] . '</td>
                                  <td>' . $rows['shiftName'] . '</td>
                                  
                                  <td><input name="check[]" type="checkbox" value=' . $rows["student_registration"] . ' class="form-control"></td>
                              </tr>';
                              echo "<input name='reg[]' value=" . $rows["student_registration"] . " type='hidden' class='form-control'>";
                          }
                          
                          }
                          else
                          {
                              echo   
                              "<div class='alert alert-danger' role='alert'>
                                  No records found! Please insert students into the database first. Then, you will be able to take attendance.
                                </div>";
                          }
                      }
                      else {
                            echo "<div class='alert alert-danger' role='alert'>Course ID and Shift ID are not provided in the URL.</dib>";
                        }
                        ?>
                    </tbody>
                  </table>

                  <br>
                  <button type="submit" name="save" class="btn btn-primary">Take Attendance</button>
                  <?php
                  } 
                
                  ?>                  
                  </form>
                </div>
              </div>
            </div>
            </div>
          </div>
          <!--Row-->

          <!-- Documentation Link -->
          <!-- <div class="row">
            <div class="col-lg-12 text-center">
              <p>For more documentations you can visit<a href="https://getbootstrap.com/docs/4.3/components/forms/"
                  target="_blank">
                  bootstrap forms documentations.</a> and <a
                  href="https://getbootstrap.com/docs/4.3/components/input-group/" target="_blank">bootstrap input
                  groups documentations</a></p>
            </div>
          </div> -->

        
        
        
        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
       <?php include "Includes/footer.php";?>
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
   <!-- Page level plugins -->
  <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>
