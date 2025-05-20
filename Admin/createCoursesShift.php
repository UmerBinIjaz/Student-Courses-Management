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

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save'])) {

  // Retrieve data from the form
  $courseName = $_POST['courseId'];
  $shiftName = $_POST['shiftId'];

  // Fetch course_id from tblcourse based on courseName
  $courseQuery = "SELECT Id FROM tblcourse WHERE courseName = '$courseName'";
  $courseResult = mysqli_query($conn, $courseQuery);

  if ($courseResult && mysqli_num_rows($courseResult) > 0) {
      $courseRow = mysqli_fetch_assoc($courseResult);
      $courseId = $courseRow['Id'];

      // Fetch shift_id from tblshift based on shiftName
      $shiftQuery = "SELECT id FROM tblshift WHERE shiftName = '$shiftName'";
      $shiftResult = mysqli_query($conn, $shiftQuery);

      if ($shiftResult && mysqli_num_rows($shiftResult) > 0) {
          $shiftRow = mysqli_fetch_assoc($shiftResult);
          $shiftId = $shiftRow['id'];

          // Check if the record already exists in tblshift_course
          $checkQuery = "SELECT * FROM tblshift_course WHERE course_id = '$courseId' AND shift_id = '$shiftId'";
          $checkResult = mysqli_query($conn, $checkQuery);

          if ($checkResult && mysqli_num_rows($checkResult) > 0) {
              $statusMsg = "<div class='alert alert-danger' style='; width: 100%;'>This Course With the Same Shift Already Exists</div>";
          } else {
              // Insert data into tblshift_course
              $insertQuery = "INSERT INTO tblshift_course (course_id, shift_id) VALUES ('$courseId', '$shiftId')";
              $insertResult = mysqli_query($conn, $insertQuery);

              if ($insertResult) {
                  $statusMsg = "<div class='alert alert-success' style=';'>Record Inserted Successfully</div>";
              } else {
                  $statusMsg = "<div class='alert alert-danger' style=';'>Error In Fetching Record From Database</div>";
              }
          }
      } else {
          $statusMsg = "<div class='alert alert-danger' style=';'>Invalid Shift ID: $shiftName</div>";
      }
  } else {
      $statusMsg = "<div class='alert alert-danger' style=';'>Invalid Course ID: $courseName</div>";
  }
}

//--------------------------------DELETE------------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "delete")
{
      $Id= $_GET['Id'];

      $query = mysqli_query($conn,"DELETE FROM tblshift_course WHERE Id='$Id'");

      if ($query == TRUE) {

              echo "<script type = \"text/javascript\">
              window.location = (\"createCoursesShift.php\")
              </script>";  
      }
      else{

          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>"; 
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
  <?php include 'includes/title.php';?>
  <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
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
            <h1 class="h3 mb-0 text-gray-800">Create Course Shift</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Create Course Shift</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Create Course Shift</h6>
                </div>
                
                <div class="card-body">
                <?php echo $statusMsg; ?>
                  <form method="post">
                    <div class="form-group row mb-3">
                      <div class="col-xl-6 search_select_box">
                        <label for="courseId">Course:</label>
                        <br />
                        <select class="w-100 selectpicker" id="courseId" name="courseId" placeholder="" data-live-search="true">
                        <?php
                            // Fetch course names from tblcourse
                            $query = "SELECT courseName FROM tblcourse";
                            $result = mysqli_query($conn, $query);

                            if ($result) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $courseName = $row['courseName'];
                                    echo "<option>$courseName</option>";
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
                      <div class="col-xl-6 search_select_box">
                        <label for="courseId">Shift:</label>
                        <br />
                        <select class="w-100 selectpicker" id="shiftId" name="shiftId" placeholder="" data-live-search="true">
                        <?php
                            // Fetch course names from tblcourse
                            $squery = "SELECT shiftName FROM tblshift";
                            $sresult = mysqli_query($conn, $squery);

                            if ($sresult) {
                                while ($srow = mysqli_fetch_assoc($sresult)) {
                                    $shiftName = $srow['shiftName'];
                                    echo "<option>$shiftName</option>";
                                }
                            } else {
                                echo "<option>Error fetching courses</option>";
                            }?>
                        </select>
                      </div>
                    </div>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                  </form>
                </div>
              </div>
            </div>
          </div>

          

              <!-- Input Group -->
           <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">All Class Teachers</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="thead-light">
                      <tr>
                        <th>#</th>
                        <th>Course Name</th>
                        <th>Shift Name</th>
                        <th>Delete</th>
                      </tr>
                    </thead>
                   
                    <tbody>

                    <?php
                      $query = "SELECT scs.id, c.courseName, sh.shiftName
                      FROM tblshift_course scs
                      INNER JOIN tblcourse c ON scs.course_id = c.id
                      INNER JOIN tblshift sh ON scs.shift_id = sh.id"; 
                        
                        $result = mysqli_query($conn, $query);
                        //$id = 
                            if ($result) {
                                $sn = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $sn++;
                                    echo "
                                        <tr>
                                        <td>" . $sn . "</td>
                                        <td>" . $row['courseName'] . "</td>
                                        <td>" . $row['shiftName'] . "</td>
                                        <td><a href='deleteCourseShift.php?id=" . $row['id'] . "'><i class='fas fa-fw fa-trash'></i>Delete</a></td>
                                        </tr>";
                                }
                            } 
                            else {
                                echo "<div class='alert alert-danger' role='alert'>
                                Error fetching records: " . mysqli_error($conn) . "
                                </div>";
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
          <?php include "Includes/footer.php";?>
        </div>
        <!---Container Fluid-->
      </div>
      <!-- Footer -->
      
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
