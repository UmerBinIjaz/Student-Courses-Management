
<?php

error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';

$regNos = [];

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


$statusMsg = "";

if (isset($_GET['courseId']) && isset($_GET['shiftId'])) {
    $courseId = $_GET['courseId'];
    $shiftId = $_GET['shiftId'];

    if (isset($_GET['export']) && $_GET['export'] == '1') {
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

        // Fetch the attendance data for each student
        $studentQuery = "SELECT DISTINCT reg FROM tblattend WHERE course_id = '$courseId' AND shift_id = '$shiftId' ORDER BY reg";
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
            <h1 class="h3 mb-0 text-gray-800">Export Attendance Record</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Export Attendance Record</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
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
                        <th>Action</th>
                      </tr>
                    </thead>
                   
                    <tbody>

                  <?php
                      $query = "SELECT DISTINCT
                      t.course_id,
                      c.courseName,
                      t.shift_id,
                      s.shiftName
                  FROM
                      tblattend t
                      INNER JOIN tblcourse c ON t.course_id = c.id
                      INNER JOIN tblshift s ON t.shift_id = s.id;";
                      $rs = $conn->query($query);
                      $num = $rs->num_rows;
                      $sn=0;
                      $status="";
                      if($num > 0)
                      { 
                        while ($rows = $rs->fetch_assoc())
                          {
                             $sn = $sn + 1;
                            echo"
                              <tr>
                                <td>".$sn."</td>
                                <td>".$rows['courseName']."</td>
                                <td>".$rows['shiftName']."</td>
                                <td>
                                    <a href='?export=1&courseId=".$rows['course_id']."&shiftId=".$rows['shift_id']."'>
                                        <i class='fas fa-download' style='margin-right: 8px;'></i>Export
                                    </a>
                                </td>

                              </tr>";
                          }
                      }
                      else
                      {
                           echo   
                           "<div class='alert alert-danger' role='alert'>
                            No Record Found!
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