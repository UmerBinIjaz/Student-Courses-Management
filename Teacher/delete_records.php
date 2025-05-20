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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_date'])) {

    $deleteDate = mysqli_real_escape_string($conn, $_POST['delete_date']);

    if (isset($_GET['courseId']) && isset($_GET['shiftId']) && isset($_GET['id']) && isset($_GET['Class_No'])) {
        
        $courseId = $_GET['courseId'];
        $shiftId = $_GET['shiftId'];
        $teacherId = $_GET['id'];
        $Class_No = $_GET['Class_No'];

        // Delete the selected record
        $deleteQuery = "DELETE FROM tblattend WHERE course_id='$courseId' AND shift_id='$shiftId' AND class_number='$Class_No'";

        if (mysqli_query($conn, $deleteQuery)) {

            // Update the class numbers of the remaining records
            $updateQuery = "UPDATE tblattend
                            SET class_number = class_number - 1
                            WHERE course_id='$courseId' AND shift_id='$shiftId' AND class_number > '$Class_No'";

            if (mysqli_query($conn, $updateQuery)) {
                echo '<div class="alert alert-success">Record deleted and class numbers updated successfully.</div>';
                header("Location: AttendenceRecord.php?id=$teacherId&courseId=$courseId&shiftId=$shiftId");
                exit();
            } else {
                echo '<div class="alert alert-danger">Error updating class numbers: ' . mysqli_error($conn) . '</div>';
            }

        } else {
            echo '<div class="alert alert-danger">Error deleting record: ' . mysqli_error($conn) . '</div>';
        }
    }
}
?>
