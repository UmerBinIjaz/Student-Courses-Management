<?php
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
  
// session_start();
include '../Includes/dbcon.php';
include '../Includes/session.php';

require '../vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$statusMsg = "";
// Initialize variables to store form data
$reg = $firstName = $lastName = $semester = $emailAddress = $password = $courseId = $shiftId = '';
$Id = $_GET['id'];

date_default_timezone_set('Asia/Karachi');
$attendanceDateTime = date("Y-m-d H:i:s");

if (isset($_GET['courseId']) && isset($_GET['shiftId'])) {
    $courseId = $_GET['courseId'];
    $shiftId = $_GET['shiftId'];
    if(isset($_POST['save_excel_data']))
    {
        $fileName = $_FILES['import_file']['name'];
        $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

        $allowed_ext = ['xls','csv','xlsx'];

        if(in_array($file_ext, $allowed_ext))
        {
            $inputFileNamePath = $_FILES['import_file']['tmp_name'];
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
            $data = $spreadsheet->getActiveSheet()->toArray();

            $count = "0";
            foreach($data as $row)
            {
                if($count > 0)
                {
                    $reg = $row['0'];
                    $firstName = $row['1'];
                    $lastName = $row['2'];
                    $semester = $row['3'];
                    $emailAddress = $row['4'];
                    $password = $row['5'];

                    $studentQuery = "INSERT INTO tblstudent (reg, firstName, lastName, semester, emailAddress, password, courseId, shiftId, teach_id, dateCreated) VALUES ('$reg','$firstName','$lastName','$semester','$emailAddress','$password','$courseId','$shiftId','$Id','$attendanceDateTime')";
                    $result = mysqli_query($conn, $studentQuery);
                    $msg = true;
                }
                else
                {
                    $count = "1";
                }
            }

            if(isset($msg))
            {
                $_SESSION['message'] = "Successfully Imported";
                header('Location: index.php');
                exit(0);
            }
            else
            {
                $_SESSION['message'] = "Not Imported";
                header('Location: index.php');
                exit(0);
            }
        }
        else
        {
            $_SESSION['message'] = "Invalid File";
            header('Location: index.php');
            exit(0);
        }
    }
}    
?>