<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';
$statusMsg = "";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['userId'])) {
    header("Location: logout.php"); // Redirect to the login page
    exit();
}

// Check if the user ID in the session matches the expected user ID
if (isset($_GET['id']) && $_SESSION['userId'] != $_GET['id']) {
    header("Location: logout.php"); // Redirect to unauthorized access page
    exit();
}

// Check if the user ID in the session matches the expected user ID
if (!isset($_GET['id'])) {
    header("Location: logout.php"); // Redirect to unauthorized access page
    exit();
}

// Set the allowed user type for the current page
$allowedUserType = 'Teacher'; // Change this to the allowed user type for the specific page

// Check if the user is logged in and has the allowed user type
if (isset($_SESSION['userType']) && $_SESSION['userType'] !== $allowedUserType) {
    header("Location: logout.php");
    exit();
}

if (isset($_GET['courseId'], $_GET['shiftId'], $_GET['id'], $_GET['Class_No'], $_GET['reg'])) {
    $courseId = $_GET['courseId'];
    $shiftId = $_GET['shiftId'];
    $teacherId = $_GET['id'];
    $Class_No = $_GET['Class_No'];
    $reg = $_GET['reg'];

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['save'])) {

        // Retrieve data from the form
        $updateAttend = $_POST['updateAttend'];

        // Prepare the SQL statement
        $usql = "UPDATE tblattend SET attendanceStatus = ? WHERE reg = ? AND course_id = ? AND shift_id = ? AND class_number = ?";
        
        // Prepare and bind parameters
        $stmt = $conn->prepare($usql);
        $stmt->bind_param("iiiii", $updateAttend, $reg, $courseId, $shiftId, $Class_No);
        
        // Execute the statement
        if ($stmt->execute()) {
            //$statusMsg = "Attendance updated successfully";
            //$statusMsg = "<div class='alert alert-success' style=';'>Attendance updated successfully</div>";
        echo "<script type=\"text/javascript\">
            window.location = 'edit_records.php?id=$teacherId&courseId=$courseId&shiftId=$shiftId&Class_No=$Class_No';
      </script>";
        $statusMsg = "<div class='alert alert-success' style=';'>Attendance updated successfully</div>";

        } else {
            $statusMsg = "<div class='alert alert-danger' style=';'>Error updating attendance</div>";
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
                        <h1 class="h3 mb-0 text-gray-800">Attendance Report</h1>
                        <?php
                        if (isset($_GET['courseId']) && isset($_GET['shiftId'])) {
                            $courseId = $_GET['courseId'];
                            $shiftId = $_GET['shiftId'];
                            ?>
                            
                            <?php
                        }
                        ?>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="./">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Attendance Report</li>
                        </ol>
                    </div>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <?php
                                            if (isset($_GET['courseId']) && isset($_GET['shiftId']) && isset($_GET['id']) && isset($_GET['Class_No']) && isset($_GET['reg'])) {

                                                $courseId = $_GET['courseId'];
                                                $shiftId = $_GET['shiftId'];
                                                $teacherId = $_GET['id'];
                                                $Class_No = $_GET['Class_No'];
                                                $reg = $_GET['reg'];

                                                // Prepare the SQL statement
                                                $stmt = $conn->prepare("SELECT ta.reg, ts.name, ta.attendanceStatus, ta.class_number 
                                                                        FROM tblattend ta 
                                                                        INNER JOIN tblstudent ts ON ta.reg = ts.reg 
                                                                        WHERE ta.course_id = ? AND ta.shift_id = ? AND ta.class_number = ? AND ta.reg = ?");

                                                // Bind parameters
                                                $stmt->bind_param("iiis", $courseId, $shiftId, $Class_No, $reg);

                                                // Execute the statement
                                                $stmt->execute();

                                                // Get the result
                                                $result = $stmt->get_result();
                                                $sn = 0;
                                                if ($result) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        $reg = $row['reg'];
                                                        $name = $row['name'];
                                                        $attendanceStatus = $row['attendanceStatus'];
                                                        $class_number = $row['class_number'];
                                            
                                ?>
                                    <h6 class="m-0 font-weight-bold text-primary">Attendance Report of <?php echo $name ?> For Class Number <?php echo $Class_No ?></h6>
                                <?php } } } ?>    
                                </div>
                                
                                

                                <div class="card-body">
                                    <?php echo $statusMsg; ?>
                                    <form method="post">
                                        <div class="form-group row mb-3">
                                        <div class="col-xl-6 search_select_box">
                                            <label for="courseId">Attendance Status</label>
                                            <br />
                                            <select class="w-100 selectpicker" id="updateAttend" name="updateAttend" placeholder="" data-live-search="true">
                                                <option value="0">Absent</option>
                                                <option value="1">Present</option>
                                            </select>
                                        </div>
                                        </div>
                                        <button type="submit" name="save" class="btn btn-primary">Save</button>
                                    </form>
                                </div>

                                
                                


                            </div>
                        </div>
                    </div>
                    <!--Row-->
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