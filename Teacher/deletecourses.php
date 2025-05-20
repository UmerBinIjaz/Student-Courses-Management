<?php
include '../Includes/dbcon.php';
$statusMs = "";



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

if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['Id']) && isset($_GET['courseId']) && isset($_GET['shiftId'])) {
    $teacherId = $_GET['Id'];
    $courseId = $_GET['courseId'];
    $shiftId = $_GET['shiftId'];

    // Perform the deletion query here
    $deleteQuery = "DELETE FROM tblteacher_course_assignment WHERE teacherId = $teacherId AND courseId = $courseId AND shiftId = $shiftId";
    $result = mysqli_query($conn, $deleteQuery);

    if($result) {
        // Deletion successful, you can redirect to the original page or show a success message
        $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Your Course Deleted Successfully</div>";
        header("Location: viewClasses.php?id=$teacherId&success=1");
        exit();
    } else {
        // Deletion failed, you can redirect to the original page with an error message
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Something Went Wrong</div>";
        header("Location: viewClasses.php?id=$teacherId&error=1");
        exit();
    }
} else {
    // Invalid or missing parameters, handle the error as needed
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Unvalid Parameters</div>";
    header("Location: viewClasses.php?error=invalid_parameters");
    exit();
}
?>
