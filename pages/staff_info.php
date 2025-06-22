<?php 
require_once '../db.php';  
require_once '../Controller/departController.php';
require_once '../Controller/compController.php';
require_once '../model/Staff.php';
require_once '../model/Role.php';
require_once '../model/Branch.php';
require_once '../model/Shift.php';

session_start();

$departModel = new Depart();
$compModel = new Company();
$roleModel = new Role();
$branchModel = new Branch();
$shiftModel = new Shift();

$departments = $departModel->getAllDepartments();
$companies = $compModel->getAllCompanies();
$roles = $roleModel->getAllRoles();
$branches = $branchModel->getAllBranches();
$shifts = $shiftModel->getAllShifts();

$staffModel = new Staff();
$staff = $staffModel->getAllStaff();
//$generatedStaffNo = $staffModel->generateStaffNumber();
?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger">
    <?= htmlspecialchars($_SESSION['error']); ?>
    <?php unset($_SESSION['error']); ?>
  </div>
<?php endif; ?>

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
          <a class="nav-link " href="../pages/staff.php">
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
  </aside>

<!--main content-->
<main class="main-content position-relative border-radius-lg">
  <!-- Navbar -->
            <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Settings</a></li>
                    <li class="breadcrumb-item text-sm text-white active" aria-current="page">Department</li>
                </ol>
                <h6 class="font-weight-bolder text-white mb-0">Department</h6>
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
 
      <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <!-- Main Form -->
          <form method="POST" action="/Admin_Management/Controller/staffController.php?action=create" enctype="multipart/form-data">
            
            <!-- Personal Information Card -->
            <div class="card mb-4">
              <div class="card-header pb-0">
                <h6>Personal Information</h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-2">
                    <label>IC Number<span class="text-danger">*</span></label>
                    <input type="text" name="noic" id="noic" maxlength="12" pattern="\d*" class="form-control" required>
                  </div>

                  <div class="col-md-6">
                    <label>Full Name<span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required>
                  </div>

                  <div class="col-md-2">
                    <label>Date Of Birth<span class="text-danger">*</span></label>
                    <input type="date" name="dob" id="dob" class="form-control" required readonly>
                  </div>

                  <div class="col-md-2">
                    <label>Age<span class="text-danger">*</span></label>
                    <input type="text" name="age" id="age" class="form-control" required readonly>
                  </div>

                  <div class="col-md-2">
                    <label>Phone<span class="text-danger">*</span></label>
                    <input type="text" name="phone" id="phone" maxlength="11" pattern="\d*" class="form-control" required>
                  </div>

                  <div class="col-md-4">
                    <label>Email<span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control">
                  </div>
                
                  <div class="col-md-2">
                    <label>Marital Status<span class="text-danger">*</span></label>
                    <select name="status_marital" class="form-control">
                      <option value="1">Single</option>
                      <option value="2">Married</option>
                      <option value="3">Divorced</option>
                    </select>
                  </div>

                  <div class="col-md-2">
                    <label>Number of Dependents</label>
                    <input type="number" name="dependent" class="form-control" required>
                  </div>

                  <div class="col-md-2">
                    <label>Gender<span class="text-danger">*</span></label>
                    <input type="text" name="gender" id="gender" class="form-control" readonly placeholder="Auto-detected">
                  </div>

                  <div class="col-md-6">
                    <label>Permanent Address</label>
                    <textarea name="permanent_address" class="form-control" rows="2"></textarea>
                  </div>

                  <div class="col-md-6">
                    <label>Mailing Address</label>
                    <textarea name="mail_address" class="form-control" rows="2"></textarea>
                  </div>
                </div>
              </div>
            </div>

            <!-- Staff Information Card -->
            <div class="card mb-4">
              <div class="card-header pb-0">
                <h6>Staff Information</h6>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-3">
                    <label>Staff Number</label>
                    <input type="text" name="staff_no" class="form-control" value="<?= $generatedStaffNo ?? '' ?>" readonly>
                  </div>
                  <div class="col-md-3">
                    <label>Role<span class="text-danger">*</span></label>
                    <select name="roles" class="form-control">
                      <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['name'] ?>"><?= $r['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label>Department<span class="text-danger">*</span></label>
                    <select name="departments_id" class="form-control">
                      <?php foreach ($departments as $d): ?>
                        <option value="<?= $d['id'] ?>"><?= $d['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label>Company<span class="text-danger">*</span></label>
                    <select name="company_id" class="form-control">
                      <?php foreach ($companies as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-md-3">
                    <label>Branch<span class="text-danger">*</span></label>
                    <select name="company_branch" class="form-control">
                      <?php foreach ($branches as $branch): ?>
                        <option value="<?= htmlspecialchars($branch['id']) ?>">
                          <?= htmlspecialchars($branch['branch_name']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label>Daily Working Hours</label>
                    <input type="number" name="working_hours" class="form-control" min="1" max="24" value="8" required>
                  </div>
                  <div class="col-md-3">
                    <label>Break Duration (minutes)</label>
                    <input type="number" name="break_duration" class="form-control" min="0" max="180" value="60" required>
                  </div>
                  <div class="col-md-3">
                    <label>Shift</label>
                    <select name="shift_id" class="form-control">
                      <?php foreach ($shifts as $shift): ?>
                        <option value="<?= $shift['id'] ?>"><?= $shift['description'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-md-3">
                    <label>Employment Start Date</label>
                    <input type="date" name="start_date" class="form-control" required>
                  </div>
                  <div class="col-md-3">
                    <label>Employment End Date (if applicable)</label>
                    <input type="date" name="end_date" class="form-control">
                  </div>
                  <div class="col-md-3">
                    <label>Status<span class="text-danger">*</span></label>
                    <select name="status" class="form-control">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <label>Leave Approval<span class="text-danger">*</span></label>
                    <select name="leave_approval" class="form-control" required>
                      <option value="">-- Select Approver --</option>
                      <?php 
                        $approvers = $staffModel->getStaffByRoles(['HOD', 'HOC', 'FOUNDER']);
                        foreach ($approvers as $approver): ?>
                        <option value="<?= htmlspecialchars($approver['id']) ?>">
                          <?= htmlspecialchars($approver['name']) ?> (<?= htmlspecialchars($approver['staff_no']) ?> - <?= htmlspecialchars($approver['role_name']) ?>)
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                  
                <div class="row mt-3">
                  <div class="col-md-3">
                    <label>Role Status</label>
                    <select name="role_status" class="form-control">
                      <option value="1">Permanent</option>
                      <option value="0">Contract</option>
                    </select>
                  </div>

                  <div class="col-md-3">
                    <label>QR Code Status</label>
                    <select name="status_qrcode" class="form-control">
                      <option value="1">Enable</option>
                      <option value="0">Disable</option>
                    </select>
                  </div>

                  <div class="col-md-3">
                    <label>Selfie Status</label>
                    <select name="status_swafoto" class="form-control">
                      <option value="1">Enable</option>
                      <option value="0">Disable</option>
                    </select>
                  </div>

                  <div class="col-md-3">
                    <label>Monitoring Status</label>
                    <select name="status_monitor" class="form-control">
                      <option value="1">Enable</option>
                      <option value="0">Disable</option>
                    </select>
                  </div>

                  <div class="col-md-12 d-flex justify-content-end mt-4">
                    <!-- mt-4 adds margin top (1.5rem gap) -->
                    <button type="reset" class="btn btn-secondary me-2">
                      <i class="fas fa-undo me-2"></i>Reset Form
                    </button>
                    <button type="submit" class="btn btn-primary">
                      <i class="fas fa-save me-2"></i>Save Staff
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </form> <!-- Closing form tag -->

          <!-- Education Card -->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Education</h6>
                <button class="btn btn-sm btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#educationModal">
                  <i class="fas fa-plus me-2"></i>Add Education
                </button>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Qualification</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Start Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">End Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Institution</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Result</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="6" class="text-center text-sm text-secondary py-4">No education records found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Work Experience Card -->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Work Experience</h6>
                <button class="btn btn-sm btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#workModal">
                  <i class="fas fa-plus me-2"></i>Add Work Experience
                </button>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Company</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Start Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">End Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Position</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Salary</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Responsibilities</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="7" class="text-center text-sm text-secondary py-4">No work experience records found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Skills Card -->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Skills</h6>
                <button class="btn btn-sm btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#skillModal">
                  <i class="fas fa-plus me-2"></i>Add Skill
                </button>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Skill Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Proficiency Level</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="3" class="text-center text-sm text-secondary py-4">No skills records found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Family Card -->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Family Members</h6>
                <button class="btn btn-sm btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#familyModal">
                  <i class="fas fa-plus me-2"></i>Add Family Member
                </button>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Relationship</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Occupation</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Phone Number</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="5" class="text-center text-sm text-secondary py-4">No family records found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Education Modal -->
    <div class="modal fade" id="educationModal" tabindex="-1" aria-labelledby="educationModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="educationModalLabel">Add Education</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="educationForm">
              <div class="mb-3">
                <label class="form-label">Qualification <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="qualification" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Institution</label>
                <input type="text" class="form-control" name="institution" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Result</label>
                <input type="text" class="form-control" name="result">
              </div>
              <div class="mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-control" name="start_date">
              </div>
              <div class="mb-3">
                <label class="form-label">End Date</label>
                <input type="date" class="form-control" name="end_date">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="saveEducation()">Save</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Work Modal -->
    <div class="modal fade" id="workModal" tabindex="-1" aria-labelledby="workModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="workModalLabel">Add Work Experience</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="workForm">
              <div class="mb-3">
                <label class="form-label">Company <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="company" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Position</label>
                <input type="text" class="form-control" name="position">
              </div>
              <div class="mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-control" name="start_date">
              </div>
              <div class="mb-3">
                <label class="form-label">End Date</label>
                <input type="date" class="form-control" name="end_date">
              </div>
              <div class="mb-3">
                <label class="form-label">Salary</label>
                <input type="number" class="form-control" name="salary">
              </div>
              <div class="mb-3">
                <label class="form-label">Responsibilities</label>
                <textarea class="form-control" name="responsibilities" rows="3"></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="saveWork()">Save</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Skill Modal -->
    <div class="modal fade" id="skillModal" tabindex="-1" aria-labelledby="skillModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="skillModalLabel">Add Skill</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="skillForm">
              <div class="mb-3">
                <label class="form-label">Skill Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="skill_name" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Proficiency Level <span class="text-danger">*</span></label>
                <select class="form-control" name="proficiency_level">
                  <option value="Beginner">Beginner</option>
                  <option value="Intermediate">Intermediate</option>
                  <option value="Advanced">Advanced</option>
                  <option value="Expert">Expert</option>
                </select>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="saveSkill()">Save</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Family Modal -->
    <div class="modal fade" id="familyModal" tabindex="-1" aria-labelledby="familyModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="familyModalLabel">Add Family Member</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="familyForm">
              <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Relationship <span class="text-danger">*</span></label>
                <select class="form-select" name="relationship" required>
                  <option value="" disabled selected>Select relationship</option>
                  <option value="Father">Father</option>
                  <option value="Mother">Mother</option>
                  <option value="Brother">Brother</option>
                  <option value="Sister">Sister</option>
                  <option value="Spouse">Spouse</option>
                  <option value="Child">Child</option>
                  <option value="Guardian">Guardian</option>
                  <option value="Other">Other</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Occupation</label>
                <input type="text" class="form-control" name="occupation">
              </div>
              <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" class="form-control" name="phone">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="saveFamily()">Save</button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- Perfect Scrollbar -->
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
      const icInput = document.getElementById('noic');
      const genderInput = document.getElementById('gender');
      const dobInput = document.getElementById('dob');
      const ageInput = document.getElementById('age');
      const phoneInput = document.getElementById('phone');

      function calculateAge(birthDate) {
          const today = new Date();
          let age = today.getFullYear() - birthDate.getFullYear();
          const monthDiff = today.getMonth() - birthDate.getMonth();
          
          if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
              age--;
          }
          return age;
      }

      function processICNumber(ic) {
          ic = ic.replace(/\D/g, '').slice(0, 12);
          
          if (ic.length === 12) {
              // Extract birth date parts (YYMMDD)
              const birthYY = ic.substr(0, 2);
              const birthMM = ic.substr(2, 2);
              const birthDD = ic.substr(4, 2);
              
              // Determine century (current year - 2000)
              const currentYearShort = new Date().getFullYear() - 2000;
              const fullBirthYear = (parseInt(birthYY) <= currentYearShort) ? 
                  '20' + birthYY : '19' + birthYY;
              
              // Validate date
              const dobDate = new Date(`${fullBirthYear}-${birthMM}-${birthDD}`);
              if (!isNaN(dobDate.getTime())) {
                  // Format date as YYYY-MM-DD
                  const formattedDob = dobDate.toISOString().split('T')[0];
                  dobInput.value = formattedDob;
                  ageInput.value = calculateAge(dobDate);
              } else {
                  dobInput.value = '';
                  ageInput.value = '';
              }
              
              // Determine gender (last digit)
              const lastDigit = parseInt(ic.substr(11, 1));
              genderInput.value = (lastDigit % 2 === 0) ? 'Female' : 'Male';
          } else {
              dobInput.value = '';
              ageInput.value = '';
              genderInput.value = '';
          }
          
          return ic;
      }

      icInput.addEventListener('input', function(e) {
          this.value = processICNumber(this.value);
      });

      icInput.addEventListener('blur', function() {
          if (this.value.length !== 12) {
              alert('IC number must be exactly 12 digits');
              this.focus();
          }
      });

      phoneInput.addEventListener('input', function(e) {
          this.value = this.value.replace(/\D/g, '').slice(0, 11);
      });

      // Initialize fields if IC is pre-filled
      if (icInput.value.length === 12) {
          processICNumber(icInput.value);
      }
  });

  function resetICFields() {
      document.getElementById('noic').value = '';
      document.getElementById('dob').value = '';
      document.getElementById('age').value = '';
      document.getElementById('gender').value = '';
  }

  // Modal save functions
  function saveEducation() {
      // Save logic here
      alert('Education saved successfully!');
      $('#educationModal').modal('hide');
  }
  
  function saveWork() {
      // Save logic here
      alert('Work experience saved successfully!');
      $('#workModal').modal('hide');
  }
  
  function saveSkill() {
      // Save logic here
      alert('Skill saved successfully!');
      $('#skillModal').modal('hide');
  }
  
  function saveFamily() {
      // Save logic here
      alert('Family member saved successfully!');
      $('#familyModal').modal('hide');
  }
  </script>
</body>
</html>