<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

if (isset($_POST['courseId'])) {
    $courseId = $_POST['courseId'];
    
    // Fetch shifts associated with the selected course from the tbl_shift table
    $query = "SELECT Id, shiftName FROM tbl_shift WHERE courseId = $courseId";
    $result = mysqli_query($conn, $query);
    
    $shifts = array();
    
    while ($row = mysqli_fetch_assoc($result)) {
        $shifts[$row['Id']] = $row['shiftName'];
    }
    
    echo json_encode($shifts);
} else {
    echo json_encode(array()); // Return an empty JSON object if no course is selected
}
?>
