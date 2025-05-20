<?php
include '../Includes/dbcon.php';
include '../Includes/session.php';

if (!isset($_SESSION['userId'])) {
    header("Location: logout.php");
    exit();
}

if (isset($_GET['courseId']) && isset($_GET['shiftId'])) {
    $courseId = $_GET['courseId'];
    $shiftId = $_GET['shiftId'];

    $sql = "SELECT DISTINCT class_number, attendance_date 
            FROM tblattend 
            WHERE course_id = $courseId AND shift_id = $shiftId";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Create a CSV file
        $filename = 'attendance_report.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Output CSV header
        $output = fopen('php://output', 'w');

        // Set the header row
        $headerRow = ['Class No', 'Date', 'Time', 'Day'];
        fputcsv($output, $headerRow);

        // Fetch and output data
        while ($row = mysqli_fetch_assoc($result)) {
            $date = date('Y-m-d', strtotime($row['attendance_date']));
            $time = date('H:i:s', strtotime($row['attendance_date']));
            $day = date('l', strtotime($row['attendance_date'])); // Day of the week

            $dataRow = [
                $row['class_number'],
                $date,
                $time,
                $day
            ];
            fputcsv($output, $dataRow);
        }

        fclose($output);
        exit();
    } else {
        echo "No data found for export.";
    }
} else {
    header("Location: attendance_report.php");
    exit();
}
?>
