<?php 
require_once '../db.php';  
require_once '../Controller/departController.php';
require_once '../Controller/compController.php';
require_once '../model/Staff.php';
require_once '../model/Role.php'; // Add this line
require_once '../model/Branch.php';// Add this line

$departModel = new Depart();
$compModel = new Company();
$roleModel = new Role();
$branchModel = new Branch();

$departments = $departModel->getAllDepartments(); // returns array of id, code, name
$companies = $compModel->getAllCompanies(); // Assuming you have this method
$roles = $roleModel->getAllRoles();
$branches = $branchModel->getAllBranches();
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
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Personal Information</h6>
            </div>
            <div class="card-body">
              <form method="POST" action="">
                <div class="row">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-control-label">Identification Number <span class="text-danger">*</span></label>
                      <input type="text" name="id_number" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-5">
                    <div class="form-group">
                      <label class="form-control-label">Name <span class="text-danger">*</span></label>
                      <input type="text" name="name" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-control-label">Date of Birth <span class="text-danger">*</span></label>
                      <input type="date" name="birth_date" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-control-label">Age</label>
                      <input type="number" name="age" class="form-control" readonly>
                    </div>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-control-label">Phone Number <span class="text-danger">*</span></label>
                      <div class="input-group">
                        <span class="input-group-text">+60</span>
                        <input type="text" name="phone" class="form-control" required>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-control-label">Email <span class="text-danger">*</span></label>
                      <input type="email" name="email" class="form-control" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-control-label">Marital Status</label>
                      <select name="status" class="form-control">
                        <option value="SINGLE">SINGLE</option>
                        <option value="MARRIED">MARRIED</option>
                        <option value="DIVORCED">DIVORCED</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-control-label">Gender <span class="text-danger">*</span></label>
                      <select name="gender" class="form-control" required>
                        <option value="">SELECT</option>
                        <option value="MALE">MALE</option>
                        <option value="FEMALE">FEMALE</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-control-label">Dependents</label>
                      <input type="number" name="dependents" class="form-control">
                    </div>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label">Permanent Address</label>
                      <textarea name="permanent_address" class="form-control" rows="2"></textarea>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label">Mailing Address</label>
                      <textarea name="mailing_address" class="form-control" rows="2"></textarea>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <!-- Staff Information Card -->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Staff Information</h6>
            </div>
            <div class="card-body">
              <form method="POST" action="">
                <div class="row">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-control-label">Staff Number</label>
                      <input type="text" class="form-control" value="1022/0001" readonly>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-control-label">User ID <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" name="user_id" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-control-label">Role <span class="text-danger">*</span></label>
                      <select class="form-control" name="role" required>
                        <option value="">-- Select --</option>
                        <?php foreach ($roles as $role): ?>
                          <option value="<?= $role['id'] ?>"><?= htmlspecialchars($role['name']) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-control-label">Department <span class="text-danger">*</span></label>
                      <select class="form-control" name="department" required>
                        <option value="">-- Select --</option>
                        <?php foreach ($departments as $dept): ?>
                          <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-control-label">Branch <span class="text-danger">*</span></label>
                      <select class="form-control" name="branch_name" required>
                        <option value="">-- Select --</option>
                        <?php foreach ($branches as $branch): ?>
                          <option value="<?= $branch['id'] ?>"><?= htmlspecialchars($branch['branch_name']) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-control-label">Company <span class="text-danger">*</span></label>
                      <select class="form-control" name="company" required>
                        <option value="">-- Select --</option>
                        <?php foreach ($companies as $comp): ?>
                          <option value="<?= $comp['id'] ?>"><?= htmlspecialchars($comp['name']) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-control-label">Work Hours <span class="text-danger">*</span></label>
                      <input type="time" class="form-control" name="work_hours" value="08:00:00" required>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-control-label">Break Hours <span class="text-danger">*</span></label>
                      <input type="time" class="form-control" name="break_hours" value="01:00:00" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-control-label">Start Date <span class="text-danger">*</span></label>
                      <input type="date" class="form-control" name="start_date" value="<?= date('Y-m-d') ?>" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="form-control-label">End Date</label>
                      <input type="date" class="form-control" name="end_date">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-control-label">Employment Status <span class="text-danger">*</span></label>
                      <select class="form-control" name="employment_status" required>
                        <option value="WORKING">WORKING</option>
                        <option value="RESIGNED">RESIGNED</option>
                        <option value="TERMINATED">TERMINATED</option>
                      </select>
                    </div>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label class="form-control-label">Position Level</label>
                      <input type="text" class="form-control" name="position_level">
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-control-label">Attendance Tracking</label>
                        <div class="d-flex">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" name="attendance_tracking" id="attendance_yes" value="YES" checked>
                            <label class="form-check-label" for="attendance_yes">YES</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="attendance_tracking" id="attendance_no" value="NO">
                            <label class="form-check-label" for="attendance_no">NO</label>
                        </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-control-label">QR Status</label>
                        <div class="d-flex">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" name="qr_status" id="qr_yes" value="YES">
                            <label class="form-check-label" for="qr_yes">YES</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="qr_status" id="qr_no" value="NO" checked>
                            <label class="form-check-label" for="qr_no">NO</label>
                        </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-control-label">Selfie Status</label>
                        <div class="d-flex">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" name="selfie_status" id="selfie_yes" value="YES" checked>
                            <label class="form-check-label" for="selfie_yes">YES</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="selfie_status" id="selfie_no" value="NO">
                            <label class="form-check-label" for="selfie_no">NO</label>
                        </div>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-control-label">Tag Status</label>
                        <div class="d-flex">
                        <div class="form-check me-3">
                            <input class="form-check-input" type="radio" name="tag_status" id="tag_yes" value="YES">
                            <label class="form-check-label" for="tag_yes">YES</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tag_status" id="tag_no" value="NO" checked>
                            <label class="form-check-label" for="tag_no">NO</label>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
              </form>
            </div>
          </div>

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
              <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Major</th>
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
            <label class="form-label">Qualification</label>
            <input type="text" class="form-control" name="qualification" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Institution</label>
            <input type="text" class="form-control" name="institution" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Major</label>
            <input type="text" class="form-control" name="major">
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
            <label class="form-label">Company</label>
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
            <label class="form-label">Skill Name</label>
            <input type="text" class="form-control" name="skill_name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Proficiency Level</label>
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
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Relationship</label>
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


  <!-- Core JS Files -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // These functions would be connected to your backend in a real application
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