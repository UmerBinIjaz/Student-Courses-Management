<?php

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



// Initialize variables to store form data
$reg = $firstName = $lastName = $sessionTerm = $emailAddress = $password = $courseId = $shiftId = $statusMsg = '';
$Id = $_GET['id'];

if (isset($_GET['courseId']) && isset($_GET['shiftId'])) {
  $courseId = $_GET['courseId'];
  $shiftId = $_GET['shiftId'];

  if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save'])) {
      // Retrieve data from the form
      //$studentName = $_POST['studentname'];
      $studentReg = $_POST['studentreg'];

      // Get shiftId and courseId from the URL
      // Fetch student_id from tblstudent based on the studentName
      $query = "SELECT id FROM tblstudent WHERE reg = '$studentReg'";
      $result = mysqli_query($conn, $query);

      if ($result) {
          $row = mysqli_fetch_assoc($result);
          $studentId = $row['id'];

          // Check if the shiftId exists in tblshift
          $shiftQuery = "SELECT id FROM tblshift WHERE id = $shiftId";
          $shiftResult = mysqli_query($conn, $shiftQuery);

          if ($shiftResult && mysqli_num_rows($shiftResult) > 0) {
              // Insert data into tblstudent_course_shift
              $insertQuery = "INSERT INTO tblstudent_course_shift (student_id, course_id, shift_id) VALUES ('$studentId', '$courseId', '$shiftId')";
              $insertResult = mysqli_query($conn, $insertQuery);

              if ($insertResult) {
                  $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Student Registered Successfully.</div>";
              } else {
                  echo "<div class='alert alert-danger'>Error inserting record: " . mysqli_error($conn) . "</div>";
                  $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Error in Inserting Record</div>";
              }
          } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Invalid Shift ID: $shiftId</div>";
          }
      } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Error in Fetching Record</div>";
      }
  }
}


// For Deleting Student ID

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
  <?php include 'includes/title.php';?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
  <link href="css/ruang-admin.min.css" rel="stylesheet">
  <script>
function classArmDropdown(courseId) {
    var teacherId = <?php echo isset($_GET['id']) ? $_GET['id'] : 'null'; ?>;

    if (courseId === "") {
        document.getElementById("shiftDropdown").innerHTML = "";
        return;
    }

    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        // code for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("shiftDropdown").innerHTML = this.responseText;
        }
    };

    // Pass both teacherId and courseId to the PHP script
    xmlhttp.open("GET", "ajaxClassShift.php?teacherId=" + teacherId + "&courseId=" + courseId, true);
    xmlhttp.send();
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
            <h1 class="h3 mb-0 text-gray-800">Create Student</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Student</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Student</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6 search_select_box">
                        <label for="studentreg">Course:</label>
                        <br />
                        <select class="w-100 selectpicker" id="studentreg" name="studentreg" placeholder="" data-live-search="true">
                        <?php
                            // Fetch course names from tblcourse
                            $query = "SELECT reg, name FROM tblstudent";
                            $result = mysqli_query($conn, $query);

                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $name = $row['name'];
                                    $reg = $row['reg'];
                                    echo "<option>$reg</option>";
                                }
                            } else {
                                echo "<option>Error fetching courses</option>";
                            }
                        ?>                        
                          <!-- <option>China</option>
                          <option>Malaysia</option>
                          <option>Singapore</option> -->
                        </select>
                      </div>
                    </div>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                  </form>
                </div>
              </div>

              <!-- Input Group -->
                 <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <?php
                            if (isset($_GET['id'])) {
                                $teacherId = $_GET['id'];
                                $query = "SELECT tca.teacherId, name AS teacher_name, 
                                          COUNT(*) AS course_count
                                          FROM tblteacher_course_assignment AS tca
                                          JOIN tblteacher AS te ON tca.teacherId = te.Id
                                          WHERE tca.teacherId = $teacherId
                                          GROUP BY tca.teacherId";
                                $result = mysqli_query($conn, $query);      

                                if ($result) {
                                    $row = mysqli_fetch_assoc($result);
                                    $teacherName = $row['teacher_name'];
                                    $courseCount = $row['course_count'];
                            ?>                              
                            <h6 class="m-0 font-weight-bold text-primary">Students of <?php echo $teacherName?></h6>
                            <?php }
                            }                            
                            ?>
                            </div>
                            <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Reg No</th>
                                    <th>Student Name</th>
                                    <!-- <th>Semester</th> -->
                                    <!-- <th>Email Address</th> -->
                                    <th>Course Name</th>
                                    <th>Shift Name</th>                                   
                                    <!-- <th>Edit</th> -->
                                    <th>Delete</th>
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

                                        if ($num > 0) {
                                            while ($rows = $rs->fetch_assoc()) {
                                                $sn++;
                                                $studentId = $rows['student_id']; // Corrected the field name
  
                                                echo '
                                                    <tr>
                                                        <td>' . $sn . '</td>
                                                        <td>' . $rows['student_registration'] . '</td>
                                                        <td>' . $rows['student_name'] . '</td>
                                                        <td>' . $rows['courseName'] . '</td>
                                                        <td>' . $rows['shiftName'] . '</td>
                                                        <td><a href="DeleteStudents.php?id='.$teacherId.'&courseId='.$courseId.'&shiftId='.$shiftId.'&studentId='.$studentId.'"><i class="fas fa-fw fa-trash"></i></a></td>
                                                    </tr>';    
                                            }
                                        } else {
                                            echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
                                        }
                                    }
                                }
                                ?>



                                </tbody>
                            </table>
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
  <!-- Latest compiled and minified JavaScript -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
  <!-- (Optional) Latest compiled and minified JavaScript translation files -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('.selectpicker').selectpicker();
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>
</html>