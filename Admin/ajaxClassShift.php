<?php

include '../Includes/dbcon.php';

    $cid = intval($_GET['cid']);//

        $queryss=mysqli_query($conn,"select * from tbl_shift where courseId=".$cid." and isAssigned = '0'");                        
        $countt = mysqli_num_rows($queryss);

        echo '
        <select required name="ShiftId" class="form-control mb-3">';
        echo'<option value="">--Select Class Arm--</option>';
        while ($row = mysqli_fetch_array($queryss)) {
        echo'<option value="'.$row['Id'].'" >'.$row['shiftName'].'</option>';
        }
        echo '</select>';
?>

