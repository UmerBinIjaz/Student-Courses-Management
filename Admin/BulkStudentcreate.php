<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../Includes/dbcon.php';
include '../Includes/session.php';

require '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$statusMsg = "";
$count = 0;

// $Id = $_GET['id'];

date_default_timezone_set('Asia/Karachi');
$attendanceDateTime = date("Y-m-d H:i:s");

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




    if (isset($_POST['save_excel_data'])) {
        $fileName = $_FILES['import_file']['name'];
        $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

        $allowed_ext = ['xls', 'csv', 'xlsx'];
        $dateCreated = date("Y-m-d");

        if (in_array($file_ext, $allowed_ext)) {
            $inputFileNamePath = $_FILES['import_file']['tmp_name'];
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray();

            $headerRow = array_shift($data);

            foreach ($data as $row) {
                $regIndex = array_search('reg', $headerRow);
                $nameIndex = array_search('name', $headerRow);
                $passwordIndex = array_search('password', $headerRow);
            
                $reg = isset($row[$regIndex]) ? $row[$regIndex] : '';
                $name = isset($row[$nameIndex]) ? $row[$nameIndex] : '';
                $password = isset($row[$passwordIndex]) ? $row[$passwordIndex] : '';
                $HashPass = md5($password);
            
                if (!empty($reg)) {
                    // Query to check if the student exists
                    $sqlquery = mysqli_query($conn, "SELECT * FROM tblstudent WHERE reg ='$reg'");
                    $ret = mysqli_fetch_array($sqlquery);
            
                    if ($ret > 0) {
                        // Student with this registration already exists
                        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Student with Registration '$reg' already exists!</div>";
                    } else {
                        // Student does not exist, proceed with insertion
                        $insertQuery = "INSERT INTO tblstudent(reg, name, password, dateCreated) 
                                        VALUES ('$reg', '$name', '$HashPass', '$dateCreated')";
            
                        $resultInsert = mysqli_query($conn, $insertQuery);
            
                        if (!$resultInsert) {
                            // Error during insertion
                            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Error: " . mysqli_error($conn) . "</div>";
                        } else {
                            // Insertion successful
                            //$statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Students Imported Successfully!</div>";
                        }
                    }
                }
            
                // Check if all insertions were successful
                if (empty($statusMsg)) {
                    // All students imported successfully
                    $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Students Imported Successfully!</div>";
                }
            }
        }     
        else {
            // Invalid File Format
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Invalid File Format.</div>";
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
            <h1 class="h3 mb-0 text-gray-800">Bulk Students</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Bulk Students</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Bulk Students</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">

                        <form  method="post" enctype="multipart/form-data">
                            <div class="form-group">
                              <label for="excelFile">Upload Excel File:</label>
                              <input type="file" name="import_file" id="excelFile" />
                            </div>
                            <button type="submit" name="save_excel_data" class="btn btn-primary mt-3">Import</button>
                            <!-- <button type="submit" name="save_excel_data" class="btn btn-primary">Upload and Import</button> -->
                        </form>
                   
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
