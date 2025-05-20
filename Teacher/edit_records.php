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
    <title>Attendance Report</title>
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
                                    <h6 class="m-0 font-weight-bold text-primary">Attendance Report</h6>
                                    <?php echo $statusMsg; ?>
                                </div>
                                <div class="table-responsive p-3">
                                    <?php echo $statusMsg; ?>
                                    <?php if ($courseId && $shiftId) { ?>
                                    <!-- Table to display the report -->
                                    <table class="table align-items-center table-flush table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Sr</th>
                                                <th>Reg</th>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th>Class</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                if (isset($_GET['courseId']) && isset($_GET['shiftId']) && isset($_GET['id']) && isset($_GET['Class_No'])) {

                                                    $courseId = $_GET['courseId'];
                                                    $shiftId = $_GET['shiftId'];
                                                    $teacherId = $_GET['id'];
                                                    $Class_No = $_GET['Class_No'];                                                    

                                                    $sql = "SELECT ta.reg, ts.name, ta.attendanceStatus, ta.class_number 
                                                            FROM tblattend ta 
                                                            INNER JOIN tblstudent ts ON ta.reg = ts.reg 
                                                            WHERE ta.course_id = $courseId AND ta.shift_id = $shiftId AND ta.class_number = $Class_No";

                                                    $result = mysqli_query($conn, $sql);
                                                    $sn = 0;
                                                    if ($result) {
                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $reg = $row['reg'];
                                                            $name = $row['name'];
                                                            $attendanceStatus = ($row['attendanceStatus'] == 0) ? '<span class="text-danger">Absent</span>' : '<span class="text-primary">Present</span>'; // Conditional statement with Bootstrap classes
                                                            $class_number = $row['class_number'];
                                                            $sn = $sn + 1;
                                                            echo '
                                                                <tr>
                                                                    <td>' . $sn . '</td>
                                                                    <td>' . $reg . '</td>
                                                                    <td>' . $name . '</td>
                                                                    <td style="font-weight: 900">' . $attendanceStatus . '</td> <!-- Displaying "Absent" or "Present" with color styling -->
                                                                    <td>' . $class_number . '</td>
                                                                    <td>
                                                                        <form method="post" action="edit_attend.php?id=' . $teacherId . '&courseId=' . $courseId . '&shiftId=' . $shiftId . '&Class_No=' . $class_number . '&reg=' . $reg . '">
                                                                            <input type="hidden" name="edit_date" value="">
                                                                            <button type="submit" class="btn btn-primary">Edit</button>
                                                                        </form>
                                                                    </td>
                                                                </tr>';
                                                        }                                                        
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
</body>

</html>
