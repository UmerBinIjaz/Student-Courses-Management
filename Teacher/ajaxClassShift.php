<?php
include '../Includes/dbcon.php';

if (isset($_GET['teacherId']) && isset($_GET['courseId'])) {
    $teacherId = $_GET['teacherId'];
    $courseId = $_GET['courseId'];

    $query = "SELECT DISTINCT ts.id, ts.shiftName 
              FROM tbl_shift AS ts
              INNER JOIN tblteacher_course_assignment AS tca ON ts.id = tca.shiftId
              WHERE tca.courseId = $courseId AND tca.teacherId = $teacherId
              ORDER BY ts.shiftName ASC";

    $result = $conn->query($query);
    $num = $result->num_rows;

    if ($num > 0) {
        echo '<select required name="shiftId" class="form-control mb-3">';
        echo '<option value="">--Select Shift--</option>';
        while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['id'] . '">' . $row['shiftName'] . '</option>';
        }
        echo '</select>';
    } else {
        echo '<p>No shifts assigned to this teacher for the selected course.</p>';
    }
} else {
    echo '<p>Invalid request.</p>';
}
?>
