<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

// Check user authentication
if (!isset($_SESSION['userId']) || !isset($_GET['id']) || $_SESSION['userId'] != $_GET['id']) {
    header("Location: logout.php"); // Redirect to unauthorized access page
    exit();
}

// Set the allowed user type for the current page
$allowedUserType = 'Student';

// Check user type
if (isset($_SESSION['userType']) && $_SESSION['userType'] !== $allowedUserType) {
    header("Location: logout.php"); // Redirect to unauthorized access page
    exit();
}

// Initialize variables
$statusMsg = "";

// Check for export request
if (isset($_GET['courseId']) && isset($_GET['shiftId']) && isset($_GET['id']) && isset($_GET['export']) && $_GET['export'] == '1') {
    $courseId = $_GET['courseId'];
    $shiftId = $_GET['shiftId'];
    $studentId = $_GET['id'];

    // Verify that the student ID in the URL matches the currently logged-in user ID
    if ($_SESSION['userId'] != $studentId) {
        header("Location: unauthorized.php"); // Redirect to unauthorized access page
        exit();
    }

    // Create a CSV file
    $filename = 'attendance_report.csv';
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Output CSV header
    $output = fopen('php://output', 'w');

    // Fetch the list of classes for the selected course and shift
    $classQuery = "SELECT DISTINCT class_number FROM tblattend WHERE course_id = '$courseId' AND shift_id = '$shiftId' ORDER BY class_number";
    $classResult = mysqli_query($conn, $classQuery);
    $classNumbers = [];

    while ($classRow = mysqli_fetch_assoc($classResult)) {
        $classNumbers[] = $classRow['class_number'];
    }

    // Create the header row with class numbers
    $headerRow = ['Registration Number', 'Name'];
    foreach ($classNumbers as $classNumber) {
        $headerRow[] = "Class $classNumber";
    }
    $headerRow[] = 'Total Classes';
    $headerRow[] = 'Presents';
    $headerRow[] = 'Attendance Percentage';
    fputcsv($output, $headerRow);

    // Fetch the attendance data for the student
    $studentQuery = "SELECT DISTINCT reg FROM tblattend WHERE course_id = '$courseId' AND shift_id = '$shiftId' AND reg = '$studentId' ORDER BY reg";
    $studentResult = mysqli_query($conn, $studentQuery);

    while ($studentRow = mysqli_fetch_assoc($studentResult)) {
        $regNo = $studentRow['reg'];

        // Retrieve names for registration numbers from tblstudents
        $nameQuery = "SELECT * FROM tblstudent WHERE reg = '$regNo'";
        $nameResult = mysqli_query($conn, $nameQuery);
        $nameRow = mysqli_fetch_assoc($nameResult);
        $fullName = $nameRow['name'];

        // Fetch attendance status for each class
        $attendanceData = ['Registration Number' => $regNo, 'Name' => $fullName];
        $totalClasses = 0;
        $presents = 0;

        foreach ($classNumbers as $classNumber) {
            $classQuery = "SELECT attendanceStatus FROM tblattend WHERE course_id = '$courseId' AND shift_id = '$shiftId' AND reg = '$regNo' AND class_number = '$classNumber'";
            $classResult = mysqli_query($conn, $classQuery);
            $classRow = mysqli_fetch_assoc($classResult);
            $attendanceData["Class $classNumber"] = $classRow ? $classRow['attendanceStatus'] : 'N/A';
            $totalClasses++;
            $presents += $classRow && $classRow['attendanceStatus'] == 1 ? 1 : 0;
        }

        // Calculate attendance percentage
        $attendancePercentage = $totalClasses > 0 ? ($presents / $totalClasses) * 100 : 0;

        // Add total classes, presents, and attendance percentage to the data array
        $attendanceData['Total Classes'] = $totalClasses;
        $attendanceData['Presents'] = $presents;
        $attendanceData['Attendance Percentage'] = $attendancePercentage;

        // Output data as CSV rows
        fputcsv($output, $attendanceData);
    }

    fclose($output);
    exit;
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
            <h1 class="h3 mb-0 text-gray-800">Check Your Attendance</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Check Your Attendance</li>
            </ol>
          </div>
              <!-- Input Group -->
           <div class="row">
            <div class="col-lg-12">
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Attendance of Courses You Registered</h6>
                </div>
                <div class="table-responsive p-3">
                  <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                    <thead class="">
                      <tr>
                        <th>#</th>
                        <th>Course Name</th>
                        <th>Shift Name</th>
                        <th>Percentage</th>
                        <!-- <th>Download</th> -->
                      </tr>
                    </thead>
                   
                    <tbody>

                    <?php
                      if (isset($_GET['id'])) {
                          $studentId = $_GET['id'];
                        //   $query = "SELECT scs.id, s.reg, s.name AS student_name, s.id AS student_id, c.courseName, sh.shiftName sh.id As shift_id, c.Id AS course_id
                        //             FROM tblstudent_course_shift scs
                        //             INNER JOIN tblstudent s ON scs.student_id = s.id
                        //             INNER JOIN tblcourse c ON scs.course_id = c.Id
                        //             INNER JOIN tblshift sh ON scs.shift_id = sh.id
                        //             WHERE student_id = '$studentId' ";
                        $query = "SELECT scs.id, s.reg As registration, s.name AS student_name, scs.student_id, scs.course_id, scs.shift_id, c.courseName, sh.shiftName 
                                  FROM tblstudent_course_shift scs 
                                  INNER JOIN tblstudent s ON scs.student_id = s.id
                                  INNER JOIN tblcourse c ON scs.course_id = c.Id
                                  INNER JOIN tblshift sh ON scs.shift_id = sh.id
                                  WHERE student_id = '$studentId'";

                          $result = mysqli_query($conn, $query);

                          if ($result) {
                              $numRows = mysqli_num_rows($result);

                              if ($numRows > 0) {
                                  $sn = 0;
                                  while ($row = mysqli_fetch_assoc($result)) {
                                      $sn++;
                                      echo "
                                          <tr>
                                              <td>" . $sn . "</td>
                                              <td>" . $row['courseName'] . "</td>
                                              <td>" . $row['shiftName'] . "</td>
                                            ";
                                            $fetchAttend = "SELECT MAX(class_number) AS totalclasses, 
                                                                 COUNT(CASE WHEN attendanceStatus = '1' THEN 1 END) AS presentClasses 
                                                            FROM tblattend 
                                                            WHERE course_id = '{$row['course_id']}' 
                                                              AND shift_id = '{$row['shift_id']}' 
                                                              AND reg = '{$row['registration']}'";
                                            
                                            $res = mysqli_query($conn, $fetchAttend);
                                            
                                            if ($res) {
                                                $rows = mysqli_fetch_assoc($res);
                                            
                                                $totalClasses = $rows['totalclasses'];
                                                $PresentClasses = $rows['presentClasses'];
                                                $percentage = ($totalClasses > 0) ? ($PresentClasses / $totalClasses * 100) : 0;
                                            
                                                // echo "<td>" . $percentage . "%</td>";
                                                if($percentage < 80){
                                                    echo "<td>
                                                    <div class='progress'>
                                                        <div style='width: " . $percentage . "%; border-radius: 10px;' aria-valuemax='90' aria-valuemin='0' aria-valuenow='" . $percentage . "%' role='progressbar' class='progress-bar progress-bar-striped progress-bar-animated bg-danger'>
                                                            <span>" . $percentage . "%</span>
                                                        </div>
                                                    </div>
                                                <td>";
                                                }
                                                else{
                                                    echo "<td>
                                                    <div class='progress'>
                                                        <div style='width: " . $percentage . "%; border-radius: 10px;' aria-valuemax='90' aria-valuemin='0' aria-valuenow='" . $percentage . "%' role='progressbar' class='progress-bar progress-bar-striped progress-bar-animated bg-primary'>
                                                            <span>" . $percentage . "%</span>
                                                        </div>
                                                    </div>
                                                <td>";                                                    
                                                }

                                                // Optionally, you can use $totalClasses, $percentage as needed
                                            } else {
                                                echo "<div class='alert alert-danger' role='alert'>
                                                        Error fetching attendance records: " . mysqli_error($conn) . "
                                                    </div>";
                                            }
                                            
                                        }
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
                                    }
                                } else {
                                    echo "<div class='alert alert-danger' role='alert'>
                                            Error fetching student records: " . mysqli_error($conn) . "
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
