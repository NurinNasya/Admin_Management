<?php 
require_once '../db.php';  
require_once '../Controller/departController.php';
require_once '../Controller/compController.php';
require_once '../model/Staff.php';
$departModel = new Depart();
$compModel = new Company();
$departments = $departModel->getAllDepartments(); // returns array of id, code, name
$companies = $compModel->getAllCompanies(); // Assuming you have this method
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

<!-- pages/employee.php -->
<div class="container-fluid py-4">
  <div class="row">
    <div class="col-12 mb-4">
      <div class="card">
        <div class="card-header pb-0">
          <h5 class="mb-0">Add New Employee</h5>
        </div>
        <div class="card-body pt-0">
          <form action="../controller/employeeController.php?action=save" method="POST" enctype="multipart/form-data">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">IC Number <span class="text-danger">*</span></label>
                <input type="text" name="noic" class="form-control" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                <input type="tel" name="phone" class="form-control" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Password <span class="text-danger">*</span></label>
                <input type="text" name="pwd" id="passwordField" class="form-control" required>
                <small class="text-muted">Will auto-generate from IC and phone number</small>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Gender <span class="text-danger">*</span></label>
                <select name="gender" id="genderSelect" class="form-control" required>
                  <option value="">-- Select --</option>  <!-- Keep this for validation -->
                  <option value="M">Male</option>        <!-- Keep as fallback -->
                  <option value="F">Female</option>      <!-- Keep as fallback -->
                </select>
                <small class="text-muted" id="genderFeedback">
                  Will auto-detect from IC number
                </small>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Marital Status <span class="text-danger">*</span></label>
                <select name="status_marital" class="form-control" required>
                  <option value="">-- Select --</option>
                  <option value="1">Single</option>
                  <option value="2">Married</option>
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Dependent <span class="text-danger">*</span></label>
                <input type="number" name="dependent" class="form-control" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Position <span class="text-danger">*</span></label>
                <input type="text" name="roles" class="form-control" required>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Employment Status <span class="text-danger">*</span></label>
                <select name="roles_status" class="form-control" required>
                  <option value="">-- Select --</option>
                  <option value="Permanent">Permanent</option>
                  <option value="Contract">Contract</option>
                </select>
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Staff Number <span class="text-danger">*</span></label>
                <input type="text" name="staff_no" class="form-control" required readonly value="<?= htmlspecialchars($generatedStaffNo ?? '') ?>">
              </div>
              <div class="col-md-4 mb-3">
                <label class="form-label">Status</label>
                <input type="text" class="form-control" value="Active" disabled>
                <input type="hidden" name="status" value="1">
              </div>
              <div class="row">
              <!-- Department Column -->
              <div class="col-md-6 mb-3">
                  <div class="form-group">
                      <label class="form-label">Department <span class="text-danger">*</span></label>
                      <select name="departments_id" class="form-control" required>
                          <option value="">-- Select Department --</option>
                          <?php 
                          if (!empty($departments)): 
                              foreach ($departments as $dept): 
                          ?>
                              <option value="<?= htmlspecialchars($dept['id']) ?>">
                                  <?= htmlspecialchars($dept['code'] . ' - ' . $dept['name']) ?>
                              </option>
                          <?php 
                              endforeach;
                          else:
                          ?>
                              <option value="" disabled>No departments available</option>
                          <?php endif; ?>
                      </select>
                  </div>
              </div>
              
              <!-- Company Column -->
              <div class="col-md-6 mb-3">
                  <div class="form-group">
                      <label class="form-label">Company <span class="text-danger">*</span></label>
                      <select name="companies_id" class="form-control" required>
                          <option value="">-- Select Company --</option>
                          <?php 
                          if (!empty($companies)): 
                              foreach ($companies as $comp): 
                          ?>
                              <option value="<?= htmlspecialchars($comp['id']) ?>">
                                  <?= htmlspecialchars($comp['code'] . ' - ' . $comp['name']) ?>
                              </option>
                          <?php 
                              endforeach;
                          else:
                          ?>
                              <option value="" disabled>No companies available</option>
                          <?php endif; ?>
                      </select>
                  </div>
              </div>
          </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Permanent Address <span class="text-danger">*</span></label>
                <textarea name="permanent_address" class="form-control" rows="3" required></textarea>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">Mailing Address <span class="text-danger">*</span></label>
                <textarea name="mail_address" class="form-control" rows="3" required></textarea>
              </div>
            <div class="row mt-3">
              <div class="col-md-4 mb-3">
                <label class="form-label">Profile Picture</label>
                <input type="file" class="form-control" name="profile_pic" accept="image/*">
              </div>
            </div>

            <div class="mt-4 text-end">
              <a href="staff.php" class="btn btn-secondary">Back</a>
              <button type="submit" name="save_and_next" class="btn btn-primary">Next</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div> 
</div>

<!--script for auto generate gender-->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const icInput = document.querySelector('input[name="noic"]');
    const genderSelect = document.getElementById('genderSelect');
    const genderFeedback = document.getElementById('genderFeedback');

    if (icInput && genderSelect) {
      icInput.addEventListener('blur', function() {
        const icNumber = this.value.replace(/\D/g, ''); // Remove non-digits
        
        if (/^\d{12}$/.test(icNumber)) {
          const lastDigit = parseInt(icNumber.slice(-1));
          const gender = lastDigit % 2 === 1 ? 'M' : 'F';
          
          // Auto-select but keep options available
          genderSelect.value = gender;
          genderFeedback.textContent = `Auto-detected as ${gender === 'M' ? 'Male' : 'Female'}`;
          
          // Visual highlight
          genderSelect.classList.add('bg-light');
          setTimeout(() => genderSelect.classList.remove('bg-light'), 2000);
        }
      });
    }
  });
</script>

<!--script for auto generate pwd-->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const icInput = document.querySelector('input[name="noic"]');
    const phoneInput = document.querySelector('input[name="phone"]');
    const passwordField = document.getElementById('passwordField');
    
    function generatePassword() {
      if (icInput && phoneInput && passwordField) {
        // Get last 6 digits of IC number (remove non-digits first)
        const icDigits = icInput.value.replace(/\D/g, '').slice(-6);
        
        // Get last 4 digits of phone number
        const phoneDigits = phoneInput.value.replace(/\D/g, '').slice(-4);
        
        // Only generate if we have enough digits
        if (icDigits.length >= 6 && phoneDigits.length >= 4) {
          const autoPassword = icDigits + '@' + phoneDigits;
          passwordField.value = autoPassword;
          
          // Visual feedback
          passwordField.classList.add('bg-light');
          setTimeout(() => passwordField.classList.remove('bg-light'), 1000);
        }
      }
    }
    
    // Generate when either field changes
    if (icInput) icInput.addEventListener('blur', generatePassword);
    if (phoneInput) phoneInput.addEventListener('blur', generatePassword);
  });
</script>
</body>
</head>