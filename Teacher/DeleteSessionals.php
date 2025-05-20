<?php
// Include database connection and other necessary files
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
  

// Check if studentId is provided in the URL
if(isset($_GET['id']) && isset($_GET['courseId']) && isset($_GET['shiftId']) && isset($_GET['sessionalId'])) {
    $teacherId = $_GET['id'];
    $courseId = $_GET['courseId'];
    $shiftId = $_GET['shiftId'];
    $sessionalId = $_GET['sessionalId'];

    // Query to delete the student record
    $deleteQuery = "DELETE FROM tblsessionals_course_shift WHERE id = $sessionalId AND course_id = $courseId AND shift_id = $shiftId";

    // Perform deletion
    if(mysqli_query($conn, $deleteQuery)) {
        // Student deleted successfully, redirect to CreateStudents.php
        header("Location: AddSessionalQuizesStatus.php?id=$teacherId&courseId=$courseId&shiftId=$shiftId");
        exit();
    } else {
        // Error occurred during deletion, handle it as needed
        echo "Error deleting student: " . mysqli_error($conn);
    }
} else {
    // Invalid or incomplete parameters in the URL, handle it as needed
    echo "Invalid or incomplete parameters in the URL";
}
?>
