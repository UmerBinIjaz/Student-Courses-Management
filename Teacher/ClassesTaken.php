<?php
error_reporting(0);

$statusMsg = '';
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
$courseId = $shiftId  = $coursestaken = '';
$Id = $_GET['id'];

if (isset($_GET['id']) && isset($_GET['courseId']) && isset($_GET['shiftId'])) {
    $teacherId = $_GET['id'];
    $courseId = $_GET['courseId'];
    $shiftId = $_GET['shiftId'];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $classestaken = $_POST['classestaken'];

        // Check if the record already exists
        $checkQuery = "SELECT * FROM cla_tak WHERE courseId = '$courseId' AND shiftId = '$shiftId'";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            // Update the existing record
            $updateQuery = "UPDATE cla_tak SET classestaken = '$classestaken' WHERE courseId = '$courseId' AND shiftId = '$shiftId'";
            if ($conn->query($updateQuery)) {
                $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Total Classes Updated Successfully!</div>";
            } else {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while updating the record.</div>";
            }
        } else {
            // Insert a new record
            $insertQuery = "INSERT INTO cla_tak (courseId, shiftId, classestaken) VALUES ('$courseId', '$shiftId', '$classestaken')";
            if ($conn->query($insertQuery)) {
                $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Total Classes Added Successfully!</div>";
            } else {
                $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while inserting the record.</div>";
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
            <h1 class="h3 mb-0 text-gray-800">No of Total Classes Taken</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">No of Total Classes Taken</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">No of Total Classes Taken</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                  <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Total Number of Classes You will Take<span class="text-danger ml-2">*</span></label>
                            <input type="text" class="form-control" name="classestaken" value="" id="exampleInputFirstName">                            
                        </div>
                    </div>              
   

                      <?php
                    if (isset($Id))
                    {
                    ?>
                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php
                    } else {           
                    ?>
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <?php
                    }         
                    ?>
                  </form>
                </div>
              </div>
              <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <?php
                            if (isset($_GET['id'])) {
                                $teacherId = $_GET['id'];
                                $query = "SELECT tca.teacherId, CONCAT(te.firstName, ' ', te.lastname) AS teacher_name, 
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
                                    <th>Course Name</th>
                                    <th>Shift Name</th>
                                    <th>Total Classes will be Taken</th>                     
                                </tr>
                                </thead>
                            
                                <tbody>

                                <?php
                                if (isset($_GET['id']) && isset($_GET['courseId']) && isset($_GET['shiftId'])) {
                                    $teacherId = $_GET['id'];
                                    $courseId = $_GET['courseId'];
                                    $shiftId = $_GET['shiftId'];

                                    $query = "SELECT c.courseName, s.shiftName, ct.classestaken
                                              FROM cla_tak ct
                                              JOIN tblcourse c ON ct.courseId = c.Id
                                              JOIN tbl_shift s ON ct.shiftId = s.Id
                                              WHERE ct.courseId = $courseId
                                              AND ct.shiftId = $shiftId";

                                    $rs = $conn->query($query);
                                    $num = $rs->num_rows;
                                    $sn = 0;

                                    if ($num > 0) {
                                        while ($rows = $rs->fetch_assoc()) {
                                            $sn = $sn + 1;
                                            echo "
                                            <tr>
                                                <td>".$sn."</td>
                                                <td>" . $rows['courseName'] . "</td>
                                                <td>" . $rows['shiftName'] . "</td>
                                                <td>" . $rows['classestaken'] . "</td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<div class='alert alert-danger' role='alert'>No Record Found!</div>";
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

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>