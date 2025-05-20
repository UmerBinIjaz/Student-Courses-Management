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

// Initialize variables to store form data
$id = $teacherId = $courseId = $shiftId;

//---------------------------------------EDIT-------------------------------------------------------------

if (isset($_GET['Id']) && isset($_GET['action']) && $_GET['action'] == "edit") {
    $Id = $_GET['Id'];
    
    // Change the query to use the correct form field names
    $query = mysqli_query($conn, "SELECT * FROM tblteacher_course_assignment WHERE Id ='$Id'");
    $row = mysqli_fetch_array($query);

    if (isset($_POST['update'])) {
        $courseId = $_POST['courseId'];
        $shiftId = $_POST['shiftId'];
        $teacherId = $_POST['teacherId'];

        $que = mysqli_query($conn, "UPDATE tblteacher_course_assignment SET courseId='$courseId', shiftId='$shiftId', teacherId='$teacherId' WHERE Id='$Id'");

        if ($que) {
            echo "<script type = \"text/javascript\">
            window.location = (\"assignCourses.php\")
            </script>";
        } else {
            $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error Occurred!</div>";
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
        xmlhttp.open("GET","ajaxClassShift.php?cid="+str,true);
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
            <h1 class="h3 mb-0 text-gray-800">Assign Courses</h1>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="./">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Assign Courses</li>
            </ol>
          </div>

          <div class="row">
            <div class="col-lg-12">
              <!-- Form Basic -->
              <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-primary">Assign Courses</h6>
                    <?php echo $statusMsg; ?>
                </div>
                <div class="card-body">
                  <form method="post">
                   <div class="form-group row mb-3">
                        <div class="col-xl-6">
                        <label class="form-control-label">Select Class<span class="text-danger ml-2">*</span></label>
                         <?php
                        $qry= "SELECT * FROM tblcourse ORDER BY courseName ASC";
                        $result = $conn->query($qry);
                        $num = $result->num_rows;		
                            if ($num > 0){
                                echo ' <select required name="courseId" onchange="classArmDropdown(this.value)" class="form-control mb-3">';
                                echo'<option value="">--Select Class--</option>';
                                while ($rows = $result->fetch_assoc()){
                                echo'<option value="'.$rows['Id'].'" >'.$rows['courseName'].'</option>';
                                    }
                                        echo '</select>';
                                    }
                            ?>         
                        </div>

                        <div class="col-xl-6">
                        <!-- <label class="form-control-label">Firstname<span class="text-danger ml-2">*</span></label> -->

                        <label class="form-control-label">Class Shift<span class="text-danger ml-2">*</span></label>
                            <?php
                                echo"<div id='txtHint'></div>";
                            ?>
                        <!-- <input type="text" class="form-control" required name="firstName" value="<?php echo $row['firstName'];?>" id="exampleInputFirstName"> -->
                        </div>
                    </div>
                     <div class="form-group row mb-3">
                        <div class="col-xl-6">
                            <label class="form-control-label">Select Teacher<span class="text-danger ml-2">*</span></label>
                            <?php
                            $qry= "SELECT * FROM tblteacher ORDER BY firstName, lastname ASC";
                            $result = $conn->query($qry);
                            $num = $result->num_rows;		
                                if ($num > 0){
                                    echo ' <select required name="teacherId" class="form-control mb-3">';
                                    echo'<option value="">--Select Teacher--</option>';
                                    while ($rows = $result->fetch_assoc()){
                                    echo'<option value="'.$rows['Id'].'" >'.$rows['firstName']."  ".$rows['lastname'].'</option>';
                                        }
                                            echo '</select>';
                                        }
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
                            <h6 class="m-0 font-weight-bold text-primary">All Class Teachers</h6>
                            </div>
                            <div class="table-responsive p-3">
                            <table class="table align-items-center table-flush table-hover" id="dataTableHover">
                                <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Course Name</th>
                                    <th>Course Shift</th>
                                    <th>Teacher Name</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                                </thead>
                            
                                <tbody>

                            <?php
                                $query = "SELECT tca.Id, 
                                CONCAT(te.firstName, ' ', te.lastname) AS teacherName,
                                co.courseName,
                                s.shiftName
                                FROM tblteacher_course_assignment AS tca
                                JOIN tblteacher AS te ON tca.teacherId = te.Id
                                JOIN tblcourse AS co ON tca.courseId = co.Id
                                JOIN tbl_shift AS s ON tca.shiftId = s.Id";                  
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
                                            <td>" . $rows['courseName'] ."</td>
                                            <td>". $rows['shiftName'] ."</td>
                                            <td>" . $rows['teacherName'] ."</td>
                                            <td><a href='editCourseAssign.php?action=edit&Id=".$rows['Id']."'><i class='fas fa-fw fa-edit'></i>Edit</a></td>
                                            <td><a href='?action=delete&Id=".$rows['Id']."'><i class='fas fa-fw fa-trash'></i></a></td>

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