
<nav class="navbar navbar-expand navbar-light bg-gradient-primary topbar mb-4 static-top">
          <button id="sidebarToggleTop" class="btn btn-link rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
        <div class="text-white big" style="margin-left:100px;"></div>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                aria-labelledby="searchDropdown">
                <form class="navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-1 small" placeholder="What do you want to look for?"
                      aria-label="Search" aria-describedby="basic-addon2" style="border-color: #3f51b5;">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>
            
           
            <div class="topbar-divider d-none d-sm-block"></div>
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <img class="img-profile rounded-circle" src="img/user-icn.png" style="max-width: 60px">
                <?php
                  if (isset($_GET['id'])) {
                    $teacherId = $_GET['id'];
                    // $query = "SELECT tca.teacherId, name AS teacher_name, 
                    //           COUNT(*) AS course_count
                    //           FROM tblteacher_course_assignment AS tca
                    //           JOIN tblteacher AS te ON tca.teacherId = te.Id
                    //           WHERE tca.teacherId = $teacherId
                    //           GROUP BY tca.teacherId";
                    $query = "SELECT * FROM tblteacher WHERE Id = $teacherId";
                    $result = mysqli_query($conn, $query);      

                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $teacherName = $row['name'];
                        //$courseCount = $row['course_count'];
                ?>
                <span class="ml-2 d-none d-lg-inline text-white small"><b>Welcome <?php echo $teacherName;?></b></span>


                <?php
                  }
                }
                ?>                
                
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <!-- <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a> -->
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="Profile.php?id=<?php echo $_GET['id']; ?>">
                <i class="fas fa-user fa-fw mr-2 text-danger"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="logout.php">
                <i class="fas fa-power-off fa-fw mr-2 text-danger"></i>
                  Logout
                </a>                
              </div>
            </li>
          </ul>
        </nav>