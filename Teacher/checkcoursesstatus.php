<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Includes/dbcon.php';
include '../Includes/session.php';

if (!isset($_SESSION['userId'])) {
    header("Location: logout.php");
    exit();
}

if (!isset($_GET['id']) || $_SESSION['userId'] != $_GET['id']) {
    header("Location: logout.php");
    exit();
}

$allowedUserType = 'Teacher';

if (isset($_SESSION['userType']) && $_SESSION['userType'] !== $allowedUserType) {
    header("Location: logout.php");
    exit();
}

$Id = $_GET['id'];
$statusMsg = '';

if (isset($_GET['courseId']) && isset($_GET['shiftId'])) {
    $courseId = $_GET['courseId'];
    $shiftId = $_GET['shiftId'];

    $courseCheck = mysqli_query($conn, "SELECT * FROM tblcourse WHERE Id='$courseId'");
    $shiftCheck = mysqli_query($conn, "SELECT * FROM tblshift WHERE id='$shiftId'");

    if ($courseCheck && $shiftCheck) {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save'])) {
            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $marks = mysqli_real_escape_string($conn, $_POST['marks']);
            $deadline = mysqli_real_escape_string($conn, $_POST['deadline']);
            $status = mysqli_real_escape_string($conn, $_POST['status']);

            $checkduplication = mysqli_query($conn, "SELECT * FROM tblsessionals_course_shift WHERE course_id='$courseId' AND shift_id = '$shiftId' AND name ='$name'");
            $numRows = mysqli_num_rows($checkduplication);
            if($numRows == 0){
                $insertQuery = "INSERT INTO tblsessionals_course_shift (course_id, shift_id, name, marks, deadline, status) VALUES ('$courseId', '$shiftId', '$name', '$marks', '$deadline', '$status')";
                $insertResult = mysqli_query($conn, $insertQuery);

                if ($insertResult) {
                  $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Sessional Added Successfully.</div>";
                } else {
                  $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Error in Inserting Record: " . mysqli_error($conn) . "</div>";
                }
            }
            else{
              $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Duplicate Record Found.</div>";
            }
        }
    } else {
      $statusMsg = "<div class='alert alert-danger'>Invalid Course ID: $courseId or Shift ID: $shiftId</div>";
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

              <!-- Input Group -->
              <div class="row">
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Marks Distribution
                            </h6>

                        </div>
                        <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Course Name</th>
                                    <th>Shift Name</th>
                                    <!-- <th>Edit</th> -->
                                    <th>View</th>
                                </tr>
                                </thead>

                                <tbody>
                                <?php
                                    $fetchRecordsQuery = "SELECT * FROM tblshift_course";
                                    $recordsResult = mysqli_query($conn, $fetchRecordsQuery);

                                    if ($recordsResult) {
                                        $counter = 1;

                                        while ($record = mysqli_fetch_assoc($recordsResult)) {
                                            $teacherID = $_GET['id'];
                                            $Courseid = $record['course_id'];
                                            $Shiftid = $record['shift_id'];

                                            $fetchCQuery = "SELECT * FROM tblcourse WHERE Id = '$Courseid'";
                                            $recordCResult = mysqli_query($conn, $fetchCQuery);
                                            $courseRecord = mysqli_fetch_assoc($recordCResult); // Fetch course details

                                            $fetchSQuery = "SELECT * FROM tblshift WHERE id = '$Shiftid'";
                                            $recordSResult = mysqli_query($conn, $fetchSQuery);
                                            $shiftRecord = mysqli_fetch_assoc($recordSResult); // Fetch shift details
                                            ?>
                                            <tr>
                                                <td><?php echo $counter++; ?></td>
                                                <td><?php echo $courseRecord['courseName']; ?></td>
                                                <td><?php echo $shiftRecord['shiftName']; ?></td>
                                                <td>
                                                    <a href="SpecificCoursesStatus.php?id=<?php echo $teacherID; ?>&courseId=<?php echo $Courseid; ?>&shiftId=<?php echo $Shiftid; ?>">
                                                        <i class="fas fa-fw fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
                                    }
                                ?>

                                </tbody>
                            </table>
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