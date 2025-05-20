<?php
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

$statusMsg = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Perform the delete operation
    $deleteQuery = "DELETE FROM tblshift_course WHERE id = $id";
    $result = mysqli_query($conn, $deleteQuery);

    if ($result) {
        // Redirect to the page where the records are displayed
        $statusMsg = "<div class='alert alert-success' style='margin-right:700px;'>Record Deleted Successfully</div>";
        header("Location: createCoursesShift.php");
        exit();
    } else {
        $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Error in Deleting a Record</div>";
    }
} else {
    $statusMsg = "<div class='alert alert-danger' style='margin-right:700px;'>Invalid Id Found</div>";
}
?>
