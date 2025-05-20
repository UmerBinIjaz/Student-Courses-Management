<?php

error_reporting(0);
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
$reg = $firstName = $lastName = $sessionTerm = $emailAddress = $password = $courseId = $shiftId = '';
$Id = $_GET['id'];

if (isset($_GET['courseId']) && isset($_GET['shiftId'])) {
  $courseId = $_GET['courseId'];
  $shiftId = $_GET['shiftId'];

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      // Retrieve and sanitize form data
      $reg = $_POST['reg'];
      $firstName = $_POST['firstName'];
      $lastName = $_POST['lastName'];
      $semester = $_POST['semester'];
      // $emailAddress = $_POST['emailAddress'];
      // $password = $_POST['password'];
      $dateCreated = date("Y-m-d");

      $sqlquery = mysqli_query($conn, "SELECT * FROM tblstudents WHERE reg = '$reg' && courseId ='$courseId' && shiftId='$shiftId'");
      $ret = mysqli_fetch_array($sqlquery);

      if ($ret > 0) {
          $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>This Student is Already enrolled in This Course</div>";
      } else {
          $query = mysqli_query($conn, "INSERT INTO tblstudents (reg, firstName, lastName, semester, courseId, teach_id, shiftId, dateCreated) 
          VALUES ('$reg', '$firstName', '$lastName', '$semester', '$courseId', '$Id', '$shiftId', '$dateCreated')");

          if ($query) {
              $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Student Added Successfully!</div>";
          } else {
              $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
          }
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
                        <div class="col-xl-6">
                            <label class="form-control-label">Registration Number<span class="text-danger ml-2">*</span></label>
                            <input type="text" class="form-control" name="reg" value="<?php echo $row['reg'];?>" id="exampleInputFirstName" >                            
                        </div>
                        <div class="col-xl-6">
                            <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label>
                            <input type="text" class="form-control" name="firstName" value="<?php echo $row['firstName'];?>" id="exampleInputFirstName" >
                        </div>
                    </div>              
                    <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Lastname<span class="text-danger ml-2">*</span></label>
                            <input type="text" class="form-control" name="lastName" value="<?php echo $row['lastName'];?>" id="exampleInputFirstName" >
                        </div>
                        <div class="col-xl-6">
                            <label class="form-control-label">Semester<span class="text-danger ml-2">*</span></label>                          
                            <?php
                                // Query to fetch session terms based on conditions
                                $query = "SELECT * FROM tblsessionterm WHERE termId = 1 AND isActive = 1";
                                $result = $conn->query($query);
                                $Snum = $result->num_rows;

                                if ($Snum > 0) {
                                    echo ' <select required name="semester" class="form-control mb-3">';
                                    echo '<option value="">Select Semester</option>';
                                    echo '<option value="BS 1st Sem">BS 1st Sem</option>';
                                    echo '<option value="BS 3rd Sem">BS 3rd Sem</option>';
                                    echo '<option value="BS 5th Sem">BS 5th Sem</option>';
                                    echo '<option value="BS 7th Sem">BS 7th Sem</option>';
                                    echo '<option value="LB 1st Sem">LB 1st Sem</option>';
                                    echo '<option value="LB 3rd Sem">LB 3rd Sem</option>';
                                    echo '<option value="LB 5th Sem">LB 5th Sem</option>';
                                    echo '<option value="LB 7th Sem">LB 7th Sem</option>';
                                    echo '<option value="BSMphil 1st Sem">Mphil 1st Sem</option>';
                                    echo '<option value="Mphil 2nd Sem">Mphil 2nd Sem</option>';
                                    echo '<option value="Mphil 3rd Sem<">Mphil 3rd Sem</option>';
                                    echo '<option value="Mphil 4th Sem">Mphil 4th Sem</option>';
                                } else {
                                    echo ' <select required name="semester" class="form-control mb-3">';
                                    echo '<option value="">Select Semester</option>';
                                    echo '<option value="BS 2nd Sem">BS 2nd Sem</option>';
                                    echo '<option value="BS 4th Sem">BS 4th Sem</option>';
                                    echo '<option value="BS 6th Sem">BS 6th Sem</option>';
                                    echo '<option value="BS 8th Sem">BS 8th Sem</option>';
                                    echo '<option value="LB 2nd Sem">LB 2nd Sem</option>';
                                    echo '<option value="LB 4th Sem">LB 4th Sem</option>';
                                    echo '<option value="LB 6th Sem">LB 6th Sem</option>';
                                    echo '<option value="LB 8th Sem">LB 8th Sem</option>';                                    
                                    echo '<option value="Mphil 1st Sem">Mphil 1st Sem</option>';
                                    echo '<option value="Mphil 2nd Sem">Mphil 2nd Sem</option>';
                                    echo '<option value="Mphil 3rd Sem<">Mphil 3rd Sem</option>';
                                    echo '<option value="Mphil 4th Sem">Mphil 4th Sem</option>';                           
                                }
                                echo '</select>';
                            ?>
                     
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

              <!-- Input Group -->
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
                                    <th>Reg No</th>
                                    <th>Name</th>
                                    <th>Semester</th>
                                    <!-- <th>Email Address</th> -->
                                    <th>courseId</th>
                                    <th>Shift Id</th>                                   
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
        $query = "SELECT s.*, c.courseName, s2.shiftName
                  FROM tblstudents s
                  JOIN tblcourse c ON s.courseId = c.id
                  JOIN tbl_shift s2 ON s.shiftId = s2.id
                  WHERE s.teach_id = $teacherId AND c.id = $courseId AND s.shiftId = $shiftId";

        $rs = $conn->query($query);
        $num = $rs->num_rows;
        $sn = 0;
        $status = "";

        if ($num > 0) {
            while ($rows = $rs->fetch_assoc()) {
                $sn++;
                $fullName = $rows['firstName'] . " " . $rows['lastName'];
                $studentId = $rows['id']; // Get the studentId from the fetched row
                echo "
                <tr>
                    <td>".$sn."</td>
                    <td>" . $rows['reg'] ."</td>
                    <td>". $fullName ."</td>
                    <td>" . $rows['semester'] ."</td>
                    <td>" . $rows['courseName'] ."</td>
                    <td>" . $rows['shiftName'] . "</td>
                    <td><a href='DeleteStudents.php?id=".$teacherId."&courseId=".$courseId."&shiftId=".$shiftId."&studentId=".$studentId."'><i class='fas fa-fw fa-trash'></i></a></td>
                </tr>";
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

  <!-- Page level custom scripts -->
  <script>
    $(document).ready(function () {
      $('#dataTable').DataTable(); // ID From dataTable 
      $('#dataTableHover').DataTable(); // ID From dataTable with Hover
    });
  </script>
</body>

</html>