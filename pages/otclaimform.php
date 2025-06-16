
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    OT Claim Form - Argon Dashboard
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
  <style>
    .alert-info {
      background-color: #e3f2fd;
      border-color: #bbdefb;
      color: #1976d2;
    }
    .text-danger {
      color: #dc3545 !important;
    }
    .form-control:focus {
      border-color: #5e72e4;
      box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.25);
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="../pages/dashboard.html" target="_blank">
        <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Creative Tim</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="../pages/dashboard.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pages/approval_leave.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Leaves</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pages/staff.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Staff</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../pages/ot_claim.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-time-alarm text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">OT Claim</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-toggle="collapse" href="#settingsMenu" role="button" aria-expanded="false" aria-controls="settingsMenu">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-settings text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Settings</span>
          </a>
          <div class="collapse" id="settingsMenu">
            <ul class="nav ms-4 ps-3">
              <li class="nav-item">
                <a class="nav-link" href="../pages/company.php">
                  <span class="sidenav-normal">Company</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../pages/department.php">
                  <span class="sidenav-normal">Department</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../pages/roles.php">
                  <span class="sidenav-normal">Roles</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../pages/workshift.php">
                  <span class="sidenav-normal">Work Shift</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
      </ul>
    </div>
  </aside>

  <main class="main-content position-relative border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">OT Claim</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">OT Claim Form</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group">
              <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
              <input type="text" class="form-control" placeholder="Type here...">
            </div>
          </div>
          <ul class="navbar-nav justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white font-weight-bold px-0">
                <i class="fa fa-user me-sm-1"></i>
                <span class="d-sm-inline d-none">Sign In</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex align-items-center">
                <p class="mb-0 font-weight-bold text-lg">OT CLAIM FORM</p>
              </div>
              <?php if (isset($success_message)): ?>
            <div class="alert alert-dismissible fade show border-0" role="alert" style="background-color: transparent;">
            <span class="alert-icon"><i class="ni ni-like-2"></i></span>
            <span class="alert-text"><?php echo $success_message; ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
             </div>
            <?php endif; ?>
            <div class="alert border-0" role="alert" style="background-color: transparent;">
            <strong>P/S: YOUR OVERTIME CLAIM OF CURRENT MONTH ARE ONLY ACCEPTING DATE BEFORE 15th.</strong><br>
            <span class="text-primary">FOR OVERTIME SUBMITTED AFTER 15th, YOUR CLAIM WILL BE PROCEED NEXT MONTH.</span>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="month_claim" class="form-control-label">Month Claim <span class="text-danger">*</span></label>
                      <select class="form-control" id="month_claim" name="month_claim" required>
                        <option value="">Select Month</option>
                        <option value="<?php echo date('Y-m'); ?>"><?php echo $currentMonth; ?></option>
                        <option value="<?php echo date('Y-m', strtotime('+1 month')); ?>"><?php echo $nextMonth; ?></option>
                        <option value="<?php echo date('Y-m', strtotime('+2 months')); ?>"><?php echo $nextTwoMonths; ?></option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="start_time" class="form-control-label">Start <span class="text-danger">*</span></label>
                      <input class="form-control" type="time" id="start_time" name="start_time" required onchange="calculateHours()">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="end_time" class="form-control-label">End <span class="text-danger">*</span></label>
                      <input class="form-control" type="time" id="end_time" name="end_time" required onchange="calculateHours()">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label for="total_hours" class="form-control-label">Total Hours <span class="text-danger">*</span></label>
                      <input class="form-control" type="text" id="total_hours" name="total_hours" placeholder="00H 00M" readonly>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="date" class="form-control-label">Date (Day)</label>
                      <input class="form-control" type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="attachment" class="form-control-label">Attachment <span class="text-danger">(Only pdf/png/jpg/png format file are allowed)</span></label>
                      <input class="form-control" type="file" id="attachment" name="attachment" accept=".pdf,.png,.jpg,.jpeg">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="work_description" class="form-control-label">Work Description</label>
                      <textarea class="form-control" id="work_description" name="work_description" rows="4" placeholder="Enter work description..."></textarea>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 text-center">
                    <button type="submit" class="btn btn-primary btn-lg px-5">Submit</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <footer class="footer pt-3">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>document.write(new Date().getFullYear())</script>,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                for a better web.
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>

  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  
  <script>
    function calculateHours() {
      const startTimeInput = document.getElementById('start_time');
      const endTimeInput = document.getElementById('end_time');
      const totalHoursInput = document.getElementById('total_hours');
      
      if (startTimeInput.value && endTimeInput.value) {
        const startTime = new Date('2000-01-01 ' + startTimeInput.value);
        const endTime = new Date('2000-01-01 ' + endTimeInput.value);
        
        // Handle case where end time is next day
        if (endTime < startTime) {
          endTime.setDate(endTime.getDate() + 1);
        }
        
        const diffInMs = endTime - startTime;
        const diffInHours = Math.floor(diffInMs / (1000 * 60 * 60));
        const diffInMinutes = Math.floor((diffInMs % (1000 * 60 * 60)) / (1000 * 60));
        
        totalHoursInput.value = diffInHours.toString().padStart(2, '0') + 'H ' + diffInMinutes.toString().padStart(2, '0') + 'M';
      }
    }

    // Auto-update day name when date changes
    document.getElementById('date').addEventListener('change', function() {
      const dateValue = this.value;
      if (dateValue) {
        const date = new Date(dateValue);
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const dayName = days[date.getDay()];
        // 
      }
    });

    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  
  <!-- Control Center for Argon Dashboard -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>