<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
  

$statusMsg = "";
$courseId = NULL;
$shiftId = NULL;
if (isset($_GET['courseId']) && isset($_GET['shiftId'])) {
    $courseId = $_GET['courseId'];
    $shiftId = $_GET['shiftId'];
    $sql = "SELECT ROW_NUMBER() OVER (ORDER BY `attendanceDate`) AS `Sr NO`, DATE_FORMAT(`attendanceDate`, '%d-%m-%Y') AS `Date`, DATE_FORMAT(`attendanceDate`, '%H:%i') AS `Time`
            FROM  (SELECT DISTINCT `attendanceDate` FROM `tbl_attendance` WHERE `courseId` = $courseId AND `shiftId` = $shiftId) AS subquery ORDER BY  `attendanceDate`";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>An error occurred while fetching data.</div>";
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
    <link href="img/favicon.png" rel="icon">
    <title>Attendance Records</title>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="container">
        <h1>Attendance Records</h1>
        <?php echo $statusMsg; ?>
        <?php if ($courseId && $shiftId) { ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Sr No</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['Sr NO'] . "</td>";
                        echo "<td>" . $row['Date'] . "</td>";
                        echo "<td>" . $row['Time'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        <?php } ?>
    </div>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>
