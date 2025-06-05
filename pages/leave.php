<?php 
session_start();
require_once '../db.php';
require_once '../Model/leave.php';
require_once '../Model/Staff.php';

$leaveModel = new Leave($conn);

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $staff_id = (int)$_GET['id'];
    $staffModel = new Staff($conn);
    $staff = $staffModel->getStaffById($staff_id);
} else {
    header("Location: staff.php");
    exit();
}

// Display error message if present
if (isset($_GET['error'])) {
    $error_message = htmlspecialchars($_GET['error']);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Argon Dashboard 3 by Creative Tim
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
</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html " target="_blank">
        <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Creative Tim</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="../pages/dashboard.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages/tables.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Tables</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'staff.php') ? 'active' : ''; ?>" href="../pages/staff.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Staff</span>
          </a>
        </li>
        <li class="nav-item">
        <a class="nav-link collapsed" data-bs-toggle="collapse" href="#settingsMenu" role="button" aria-expanded="false" aria-controls="settingsMenu">
          <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
            <i class="ni ni-settings text-dark text-sm opacity-10"></i>
          </div>
          <span class="nav-link-text ms-1">Settings</span> <!-- copy ini-->
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
        <!--<li class="nav-item">
          <a class="nav-link " href="../pages/virtual-reality.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-app text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Virtual Reality</span>
          </a>
        </li>-->
        <li class="nav-item">
          <a class="nav-link " href="../pages/rtl.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-world-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">RTL</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages/profile.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages/sign-in.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Sign In</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages/sign-up.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-collection text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Sign Up</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer mx-3 ">
      <div class="card card-plain shadow-none" id="sidenavCard">
        <div class="card-body text-center p-3 w-100 pt-0">
          <div class="docs-info">
          </div>
        </div>
      </div>
    </div>
  </aside> <!--smpai sini-->>
    <main class="main-content position-relative border-radius-lg">
            <!-- Navbar -->
            <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Settings</a></li>
                    <li class="breadcrumb-item text-sm text-white active" aria-current="page">Work Shift</li>
                </ol>
                <h6 class="font-weight-bolder text-white mb-0">Work Shift</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    <div class="input-group">
                    <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                    <input type="text" class="form-control" placeholder="Type here...">
                    </div>
                </div>
                <ul class="navbar-nav  justify-content-end">
                    <li class="nav-item d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white font-weight-bold px-0">
                        <i class="fa fa-user me-sm-1"></i>
                        <span class="d-sm-inline d-none">Sign In</span>
                    </a>
                    </li>
                    <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line bg-white"></i>
                        <i class="sidenav-toggler-line bg-white"></i>
                        <i class="sidenav-toggler-line bg-white"></i>
                        </div>
                    </a>
                    </li>
                    <li class="nav-item px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0">
                        <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                    </a>
                    </li>
                    <li class="nav-item dropdown pe-2 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell cursor-pointer"></i>
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                        <li class="mb-2">
                        <a class="dropdown-item border-radius-md" href="javascript:;">
                            <div class="d-flex py-1">
                            <div class="my-auto">
                                <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="text-sm font-weight-normal mb-1">
                                <span class="font-weight-bold">New message</span> from Laur
                                </h6>
                                <p class="text-xs text-secondary mb-0">
                                <i class="fa fa-clock me-1"></i>
                                13 minutes ago
                                </p>
                            </div>
                            </div>
                        </a>
                        </li>
                        <li class="mb-2">
                        <a class="dropdown-item border-radius-md" href="javascript:;">
                            <div class="d-flex py-1">
                            <div class="my-auto">
                                <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="text-sm font-weight-normal mb-1">
                                <span class="font-weight-bold">New album</span> by Travis Scott
                                </h6>
                                <p class="text-xs text-secondary mb-0">
                                <i class="fa fa-clock me-1"></i>
                                1 day
                                </p>
                            </div>
                            </div>
                        </a>
                        </li>
                        <li>
                        <a class="dropdown-item border-radius-md" href="javascript:;">
                            <div class="d-flex py-1">
                            <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                                <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>credit-card</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(453.000000, 454.000000)">
                                        <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                        <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                        </g>
                                    </g>
                                    </g>
                                </g>
                                </svg>
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="text-sm font-weight-normal mb-1">
                                Payment successfully completed
                                </h6>
                                <p class="text-xs text-secondary mb-0">
                                <i class="fa fa-clock me-1"></i>
                                2 days
                                </p>
                            </div>
                            </div>
                        </a>
                        </li>
                    </ul>
                    </li>
                </ul>
                </div>
            </div>
            </nav>
            <!-- End Navbar -->

<!-- Main content -->
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5>Leave > Quota Configuration</h5>
            <!-- Updated New Leave button -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLeaveModal">New Leave</button>
          </div>
          
          <div class="card-body">
            <h5 class="card-title">Employee Info</h5>
            <?php if ($staff): ?>
              <p class="card-text"><strong>Name:</strong> <?= htmlspecialchars($staff['name']) ?></p>
              <p class="card-text"><strong>IC:</strong> <?= htmlspecialchars($staff['noic']) ?></p>
              <p class="card-text"><strong>Company:</strong> <?= htmlspecialchars($staff['company_code']) ?></p>
              <p class="card-text"><strong>Department:</strong> <?= htmlspecialchars($staff['departments_code']) ?></p>
            <?php else: ?>
              <div class="alert alert-warning">Employee data not found.</div>
            <?php endif; ?>
            
              <!-- Label outside the table -->
              <div class="mb-3 p-2 bg-secondary text-start text-white rounded">
                <strong>Individual Leave Quota List</strong>
              </div>
                <div class="card-body">


            <table class="table table-bordered table-striped mt-3">
              <thead class="table-dark">
                <tr>
                  <th>Leave Type</th>
                  <th>Leave Quota</th>
                  <th>Used</th>
                  <th>Current Balance</th>
                  <th>In Process</th>
                  <th>Available Balance</th>
                  <th>Action</th>
                </tr>
              </thead>

              <tbody>
                <!-- Expired Medical Leave -->
                <tr>
                  <td><span class="text-danger fst-italic">Expired!</span> Medical Leave</td>
                  <td>10</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>
                    <a href="#" class="btn btn-sm btn-primary">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger">Delete</a>
                  </td>
                </tr>

                <!-- Expired Annual Leave -->
                <tr>
                  <td><span class="text-danger fst-italic">Expired!</span> Annual Leave</td>
                  <td>15</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>0</td>
                  <td>
                    <a href="#" class="btn btn-sm btn-primary">Edit</a>
                    <a href="#" class="btn btn-sm btn-danger">Delete</a>
                  </td>
                </tr>

                <!-- Section Header for Non-Quota Leaves -->
                <tr class="table-secondary text-center">
                  <td colspan="7"><strong></strong></td>
                </tr>

                <!-- Non-Quota Leave Rows -->
                <tr>
                  <td>Unpaid Leave</td>
                  <td>-None-</td><td>0</td><td>-None-</td><td>0</td><td>-None-</td><td></td>
                </tr>
                <tr>
                  <td>Paternity Leave</td>
                  <td>-None-</td><td>0</td><td>-None-</td><td>0</td><td>-None-</td><td></td>
                </tr>
                <tr>
                  <td>Maternity Leave</td>
                  <td>-None-</td><td>0</td><td>-None-</td><td>0</td><td>-None-</td><td></td>
                </tr>
                <tr>
                  <td>Compassionate Leave</td>
                  <td>-None-</td><td>0</td><td>-None-</td><td>0</td><td>-None-</td><td></td>
                </tr>
                <tr>
                  <td>Hospitalization Leave</td>
                  <td>-None-</td><td>0</td><td>-None-</td><td>0</td><td>-None-</td><td></td>
                </tr>
                <tr>
                  <td>Replacement Leave</td>
                  <td>-None-</td><td>0</td><td>-None-</td><td>0</td><td>-None-</td><td></td>
                </tr>
              </tbody>
            </table>

  <!-- New Leave Modal -->
  <div class="modal fade" id="addLeaveModal" tabindex="-1" aria-labelledby="addLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="addLeaveModalLabel">Apply New Leave</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <!-- Tabs -->
          <ul class="nav nav-tabs" id="leaveTab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="medical-tab" data-bs-toggle="tab" data-bs-target="#medical" type="button" role="tab">Medical Leave</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other" type="button" role="tab">Other Leave</button>
            </li>
          </ul>

          <!-- Tab Content -->
          <div class="tab-content pt-3" id="leaveTabContent">
            <!-- Medical Leave Form -->
            <div class="tab-pane fade show active" id="medical" role="tabpanel">
              <form action="../Controller/leaveController.php?action=add" method="post" enctype="multipart/form-data">
                <input type="hidden" name="staff_id" value="<?= $staff_id ?>">
                <input type="hidden" name="created_by" value="<?= $_SESSION['user_id'] ?? 1 ?>">
                <input type="hidden" name="leave_type" value="Medical Leave">

                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="start_date_med" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date_med" name="start_date" required>
                  </div>
                  <div class="col-md-6">
                    <label for="end_date_med" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date_med" name="end_date" required>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="total_days_med" class="form-label">Total Days</label>
                    <input type="number" class="form-control" id="total_days_med" name="total_days" step="0.5" required>
                  </div>
                  <div class="col-md-6">
                    <label for="leave_document" class="form-label">Medical Certificate</label>
                    <input type="file" class="form-control" id="leave_document" name="leave_document" accept=".pdf,.jpg,.jpeg,.png">
                  </div>
                </div>

                <div class="mb-3">
                  <label for="reason_med" class="form-label">Reason</label>
                  <textarea class="form-control" id="reason_med" name="reason" rows="3" required></textarea>
                </div>

                <div class="modal-footer px-0">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Submit Medical Leave</button>
                </div>
              </form>
            </div>

            <!-- Other Leave Form -->
            <div class="tab-pane fade" id="other" role="tabpanel">
              <form action="../Controller/leaveController.php?action=add" method="post">
                <input type="hidden" name="staff_id" value="<?= $staff_id ?>">
                <input type="hidden" name="created_by" value="<?= $_SESSION['user_id'] ?? 1 ?>">

                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="leave_type_other" class="form-label">Leave Type</label>
                    <select class="form-select" id="leave_type_other" name="leave_type" required>
                      <option value="" disabled selected>Select leave type</option>
                      <option value="Annual Leave">Annual Leave</option>
                      <option value="Unpaid Leave">Unpaid Leave</option>
                      <option value="Maternity Leave">Maternity Leave</option>
                      <option value="Paternity Leave">Paternity Leave</option>
                      <option value="Compassionate Leave">Compassionate Leave</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="total_days_other" class="form-label">Total Days</label>
                    <input type="number" class="form-control" id="total_days_other" name="total_days" step="0.5" required>
                  </div>
                </div>

                <div class="row mb-3">
                  <div class="col-md-6">
                    <label for="start_date_other" class="form-label">Start Date</label>
                    <input type="date" class="form-control" id="start_date_other" name="start_date" required>
                  </div>
                  <div class="col-md-6">
                    <label for="end_date_other" class="form-label">End Date</label>
                    <input type="date" class="form-control" id="end_date_other" name="end_date" required>
                  </div>
                </div>

                <div class="mb-3">
                  <label for="reason_other" class="form-label">Reason</label>
                  <textarea class="form-control" id="reason_other" name="reason" rows="3" required></textarea>
                </div>

                <div class="modal-footer px-0">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Submit Other Leave</button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>


  <!-- Required JavaScript -->
  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const today = new Date().toISOString().split('T')[0];

      const forms = [
        {
          startDate: document.getElementById('start_date_med'),
          endDate: document.getElementById('end_date_med'),
          totalDays: document.getElementById('total_days_med')
        },
        {
          startDate: document.getElementById('start_date_other'),
          endDate: document.getElementById('end_date_other'),
          totalDays: document.getElementById('total_days_other')
        }
      ];

      forms.forEach(({ startDate, endDate, totalDays }) => {
        // Set min value to today
        startDate.min = today;
        endDate.min = today;

        // Auto-fill today if empty
        if (!startDate.value) startDate.value = today;

        const calculateDays = () => {
          if (startDate.value && endDate.value) {
            const start = new Date(startDate.value);
            const end = new Date(endDate.value);
            const diff = (end - start) / (1000 * 60 * 60 * 24) + 1;

            if (diff >= 0 && (totalDays.value === '' || parseFloat(totalDays.value) === diff)) {
              totalDays.value = diff;
            }
          }
        };

        startDate.addEventListener('change', () => {
          endDate.min = startDate.value;
          if (endDate.value && endDate.value < startDate.value) {
            endDate.value = '';
            totalDays.value = '';
          }
          calculateDays();
        });

        endDate.addEventListener('change', () => {
          calculateDays();
        });

        totalDays.addEventListener('focus', () => {
          totalDays.placeholder = "E.g., 1.5 for 1 and a half days";
        });

        totalDays.addEventListener('input', () => {
          if (!/^\d*\.?\d*$/.test(totalDays.value)) {
            totalDays.value = totalDays.value.replace(/[^\d.]/g, '');
          }
        });
      });
    });
  </script>
</body>
</html>