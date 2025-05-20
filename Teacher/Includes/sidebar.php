 <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center bg-gradient-primary justify-content-center" href="index.php">
        <div class="sidebar-brand-icon" >
          <img src="../img/favicon.png">
        </div>
        <div class="sidebar-brand-text mx-3">IIT</div>
      </a>
      <hr class="sidebar-divider my-0">
      <li class="nav-item active">
        <a class="nav-link" href="index.php?id=<?php echo $_GET['id']; ?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li> 
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Classes
      </div>
      </li>
       <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClass"
          aria-expanded="true" aria-controls="collapseClass">
          <i class="fas fa-user-graduate"></i>
          <span>Manage Classes</span>
        </a>
        <div id="collapseClass" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 classpse-hea="collader">Manage Classes</h6>
            <!-- \"Teacher/index.php?id=" . $rows['Id'] . "\" -->
            <a class="collapse-item" href="viewClasses.php?id=<?php echo $_GET['id']; ?>">View Classes</a>
            <!-- <a class="collapse-item" href="#">Assets Type</a> -->
          </div>
        </div>
      </li>
      <hr class="sidebar-divider">

<?php
        if (isset($_GET['id'])) {
          $teacherId = $_GET['id'];
          $newquery = "SELECT batchCor FROM tblteacher WHERE Id = '$teacherId'";                
          $nrs = $conn->query($newquery);
          $nnum = $nrs->num_rows;
          $sn = 0;
          $status = "";
          if($nnum > 0){
            $nrows = $nrs->fetch_assoc();
            $batchcheck = $nrows['batchCor'];
            if($batchcheck == 1){
?>

      <div class="sidebar-heading">
        Attendance Notification
      </div>
      </li>
       <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAtt"
          aria-expanded="true" aria-controls="collapseAtt">
          <i class="fas fa-user-graduate"></i>
          <span>Attendance Notification</span>
        </a>
        <div id="collapseAtt" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 classpse-hea="collader">Attendance Notification</h6>
            <!-- \"Teacher/index.php?id=" . $rows['Id'] . "\" -->
            <a class="collapse-item" href="viewAttendanceStatus.php?id=<?php echo $_GET['id']; ?>">View Notification</a>
            <!-- <a class="collapse-item" href="#">Assets Type</a> -->
          </div>
        </div>
      </li>
      <hr class="sidebar-divider">
<?php
            }
            else{

            }
          }
        }           
     
?>








      <?php
        if (isset($_GET['id'])) {
          $teacherId = $_GET['id'];
          $newquery = "SELECT batchCor FROM tblteacher WHERE Id = '$teacherId'";                
          $nrs = $conn->query($newquery);
          $nnum = $nrs->num_rows;
          $sn = 0;
          $status = "";
          if($nnum > 0){
            $nrows = $nrs->fetch_assoc();
            $batchcheck = $nrows['batchCor'];
            if($batchcheck == 1){
?>

      <div class="sidebar-heading">
        Check Courses Status
      </div>
      </li>
       <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCSt"
          aria-expanded="true" aria-controls="collapseCSt">
          <i class="fas fa-user-graduate"></i>
          <span>Check Courses Status</span>
        </a>
        <div id="collapseCSt" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 classpse-hea="collader">Check Courses Status</h6>
            <!-- \"Teacher/index.php?id=" . $rows['Id'] . "\" -->
            <a class="collapse-item" href="checkcoursesstatus.php?id=<?php echo $_GET['id']; ?>">View Courses Status</a>
            <!-- <a class="collapse-item" href="#">Assets Type</a> -->
          </div>
        </div>
      </li>
      <hr class="sidebar-divider">
<?php
            }
            else{

            }
          }
        }           
     
?>


      
      <?php
        if (isset($_GET['id'])) {
            $teacherId = $_GET['id'];
            $query = "SELECT tca.*, co.courseName, s.shiftName
                    FROM tblteacher_course_assignment AS tca
                    JOIN tblcourse AS co ON tca.courseId = co.Id
                    JOIN tblshift AS s ON tca.shiftId = s.Id
                    WHERE tca.teacherId = $teacherId";                  
                    $rs = $conn->query($query);
                    $num = $rs->num_rows;
                    $sn = 0;
                    $status = "";
                    if ($num > 0) { 
                        while ($rows = $rs->fetch_assoc()) {
                        $sn = $sn - 1;
                        $collapseId = 'collapseBootstrap' . $sn ;
                        $courseId = $rows['courseId']; // Get courseId
                        $shiftId = $rows['shiftId']; // Get shiftId                        
?>
                    <div class="sidebar-heading">
                      Students
                    </div>
                    
                    <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#<?php echo $collapseId; ?>"
                      aria-expanded="true" aria-controls="<?php echo $collapseId; ?>">
                      <i class="fas fa-user-graduate"></i>
                      <?php echo "<span>". $rows['courseName'] . "</span>" ?>
                    </a>
                    <div id="<?php echo $collapseId; ?>" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
                      <div class="bg-white py-2 collapse-inner rounded">
                      <?php echo "<h6 class='collapse-header'>". $rows['shiftName'] . "</h6>" ?>
                        <a class="collapse-item" href="AddStudents.php?id=<?php echo $teacherId; ?>&courseId=<?php echo $courseId; ?>&shiftId=<?php echo $shiftId; ?>">Add Students</a>
                        <a class="collapse-item" href="BulkImport.php?id=<?php echo $teacherId; ?>&courseId=<?php echo $courseId; ?>&shiftId=<?php echo $shiftId; ?>">Bulk Import</a>
                        <!-- <h6 class="collapse-header">Manage Students</h6> -->
                        <a class="collapse-item" href="AddSessionalQuizesStatus.php?id=<?php echo $teacherId; ?>&courseId=<?php echo $courseId; ?>&shiftId=<?php echo $shiftId; ?>">Add Marks Distribution</a>
                        <a class="collapse-item" href="TakeAttendace.php?id=<?php echo $teacherId; ?>&courseId=<?php echo $courseId; ?>&shiftId=<?php echo $shiftId; ?>">Take Attendance</a>
                        <a class="collapse-item" href="CustomDateAttend.php?id=<?php echo $teacherId; ?>&courseId=<?php echo $courseId; ?>&shiftId=<?php echo $shiftId; ?>">Custom Date Attendance</a>
                        <!-- <a class="collapse-item" href="#">Assets Type</a> -->
                        <a class="collapse-item" href="GetReport.php?id=<?php echo $teacherId; ?>&courseId=<?php echo $courseId; ?>&shiftId=<?php echo $shiftId; ?>">View Attendance Report</a>
                        <a class="collapse-item" href="AttendenceRecord.php?id=<?php echo $teacherId; ?>&courseId=<?php echo $courseId; ?>&shiftId=<?php echo $shiftId; ?>">Check Attendance Dates</a>
                      </div>
                    </div>
                  </li>
                  <hr class="sidebar-divider">
      <?php
                }
          }
        }    
?>
                    

      <!-- <hr class="sidebar-divider"> -->




      <!-- <div class="sidebar-heading">
      Attendance
      </div>
      </li>
       <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrapcon"
          aria-expanded="true" aria-controls="collapseBootstrapcon">
          <i class="fa fa-calendar-alt"></i>
          <span>Manage Attendance</span>
        </a>
        <div id="collapseBootstrapcon" class="collapse" aria-labelledby="headingBootstrap" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Manage Attendance</h6>
            <a class="collapse-item" href="takeAttendance.php">Take Attendance</a>
            <a class="collapse-item" href="viewAttendance.php">View Class Attendance</a>
            <a class="collapse-item" href="viewStudentAttendance.php">View Student Attendance</a>
            <a class="collapse-item" href="downloadRecord.php">Today's Report (xls)</a>
            <a class="collapse-item" href="addMemberToContLevel.php ">Add Member to Level</a>
          </div>
        </div>
      </li> -->

     
      <!-- <li class="nav-item">
        <a class="nav-link" href="forms.html">
          <i class="fab fa-fw fa-wpforms"></i>
          <span>Forms</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTable" aria-expanded="true"
          aria-controls="collapseTable">
          <i class="fas fa-fw fa-table"></i>
          <span>Tables</span>
        </a>
        <div id="collapseTable" class="collapse" aria-labelledby="headingTable" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Tables</h6>
            <a class="collapse-item" href="simple-tables.html">Simple Tables</a>
            <a class="collapse-item" href="datatables.html">DataTables</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="ui-colors.html">
          <i class="fas fa-fw fa-palette"></i>
          <span>UI Colors</span>
        </a>
      </li>
      <hr class="sidebar-divider">
      <div class="sidebar-heading">
        Examples
      </div>
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePage" aria-expanded="true"
          aria-controls="collapsePage">
          <i class="fas fa-fw fa-columns"></i>
          <span>Pages</span>
        </a>
        <div id="collapsePage" class="collapse" aria-labelledby="headingPage" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Example Pages</h6>
            <a class="collapse-item" href="login.html">Login</a>
            <a class="collapse-item" href="register.html">Register</a>
            <a class="collapse-item" href="404.html">404 Page</a>
            <a class="collapse-item" href="blank.html">Blank Page</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="charts.html">
          <i class="fas fa-fw fa-chart-area"></i>
          <span>Charts</span>
        </a>
      </li> -->

     
    </ul>