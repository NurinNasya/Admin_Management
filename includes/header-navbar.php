<?php
// Get the current page name for dynamic titles and breadcrumbs
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$page_title = ucfirst(str_replace('-', ' ', $current_page));

// Special cases for page titles
$page_titles = [
    'dashboard' => 'Dashboard',
    'approve' => 'Leaves',
    'staff' => 'Staff Management',
    'company' => 'Company Settings',
    'department' => 'Department Settings',
    'roles' => 'Role Management',
    'workshift' => 'Work Shift',
    'profile' => 'User Profile',
    'sign-in' => 'Sign In',
    'sign-up' => 'Sign Up'
];

// Override default title if we have a special case
if (array_key_exists($current_page, $page_titles)) {
    $page_title = $page_titles[$current_page];
}

// Determine if the current page is in the Settings section
$is_settings_page = in_array($current_page, ['company', 'department', 'roles', 'workshift']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    <?php echo $page_title; ?> | Argon Dashboard 3 by Creative Tim
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
  <!-- Add this in the head section of your header-navbar.php -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">

  <!-- In your header-navbar.php or before </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <style>
    /* Your custom CSS styles */
    .claim-card {
      border-left: 4px solid #fb6340;
      margin-bottom: 20px;
    }
    .approved-card {
      border-left: 4px solid #2dce89;
    }
    .rejected-card {
      border-left: 4px solid #f5365c;
    }
    .file-preview {
      max-width: 100%;
      max-height: 200px;
    }
    .reject-form {
      display: none;
      margin-top: 10px;
    }
    .document-name {
      font-weight: bold;
      margin-top: 5px;
      word-break: break-all;
    }
    .badge-approved {
      background-color: #2dce89;
    }
    .badge-rejected {
      background-color: #f5365c;
    }
    .section-title {
      border-bottom: 2px solid #dee2e6;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }
    .document-link {
      color: inherit;
      text-decoration: none;
      cursor: pointer;
    }
    .document-link:hover {
      text-decoration: underline;
      color: blue;
    }
  </style>
  <style>
    .medical-balance-card {
      background: linear-gradient(87deg, #f8f9fa 0, #e9ecef 100%) !important;
      color: #344767;
      border-radius: 8px;
      border: 1px solid #dee2e6;
    }
    
    .balance-item {
      display: flex;
      justify-content: space-between;
      padding: 10px;
      margin: 5px 0;
      border-radius: 6px;
      font-weight: 600;
      background-color: white;
      border: 1px solid #dee2e6;
    }
    
    .balance-validity {
      background-color: #f8f9fa;
      border-left: 4px solid #5e72e4;
    }
    
    .base-quota {
      border-left: 4px solid #3f97c6;
    }
    
    .add-quota {
      border-left: 4px solid #3f97c6  ;
    }

    .balance-entitled {
      border-left: 4px solid #11cdef;
    }    

    .balance-used {
      border-left: 4px solid #fb6340;
    }
    
    .balance-current {
      border-left: 4px solid #2dce89;
    }
    
    .balance-pending {
      border-left: 4px solid #f5365c;
    }
    
    .balance-available {
      border-left: 4px solid #5e72e4;
    }
    
    .reminder-box {
      background-color: #fff3cd;
      border-left: 4px solid #ffc107;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 4px;
    }
    
    .reminder-title {
      color: #856404;
      font-weight: 600;
      margin-bottom: 8px;
    }
    
    .file-upload {
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
    }
    
    .file-upload-input {
      position: absolute;
      font-size: 100px;
      opacity: 0;
      right: 0;
      top: 0;
    }
    
    .file-upload-label {
      display: flex;
      align-items: center;
      padding: 10px 15px;
      background-color: #f8f9fa;
      border: 1px dashed #dee2e6;
      border-radius: 6px;
      text-align: center;
      cursor: pointer;
      height: 38px;
    }
    
    .file-upload-label:hover {
      background-color: #e9ecef;
    }
    
    .file-name {
      margin-left: 10px;
      font-size: 14px;
      color: #6c757d;
      height: 38px;
      display: flex;
      align-items: center;
    }
    
    .badge-approved {
      background-color: #2dce89;
    }
    
    .badge-pending {
      background-color: #fb6340;
    }
    
    .badge-rejected {
      background-color: #f5365c;
    }
    
    .text-action-btn {
      padding: 0.25rem 0.5rem;
      font-size: 0.75rem;
      border-radius: 0.2rem;
      text-transform: none;
      letter-spacing: normal;
      margin: 0 2px;
    }
    
    .edit-modal {
      display: none;
      position: fixed;
      z-index: 1050;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      outline: 0;
      background-color: rgba(0,0,0,0.5);
    }
    
    .edit-modal-content {
      position: relative;
      margin: 10% auto;
      width: 80%;
      max-width: 600px;
      background-color: #fff;
      border-radius: 0.3rem;
      padding: 20px;
    }
    
    .close-modal {
      position: absolute;
      right: 15px;
      top: 10px;
      font-size: 1.5rem;
      font-weight: 700;
      line-height: 1;
      color: #000;
      opacity: 0.5;
      cursor: pointer;
    }

    .document-link {
      color: inherit;
      text-decoration: none;
      cursor: pointer;
  }
  .document-link:hover {
      text-decoration: underline;
      color: blue;
  }

  /* Add to your existing styles */
.edit-modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    overflow-y: auto;
}

.edit-modal-content {
    position: relative;
    background-color: #fff;
    margin: 2rem auto;
    padding: 1.5rem;
    border-radius: 0.5rem;
    width: 90%;
    max-width: 600px;
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
    animation: modalFadeIn 0.3s;
}

.edit-modal-content button[type="submit"] {
    position: relative;
    z-index: 1000;
}

@keyframes modalFadeIn {
    from {opacity: 0; transform: translateY(-20px);}
    to {opacity: 1; transform: translateY(0);}
}

.close-modal {
    position: absolute;
    right: 1rem;
    top: 1rem;
    font-size: 1.5rem;
    font-weight: bold;
    cursor: pointer;
    color: #6c757d;
    transition: color 0.2s;
}

.close-modal:hover {
    color: #000;
}
  </style>
  <style>
    .status-badge {
      display: inline-block;
      width: 20px;
      height: 20px;
      border-radius: 4px;
      margin-right: 5px;
      vertical-align: middle;
    }
    .status-active {
      background-color: #2dce89;
    }
    .status-inactive {
      background-color: #f5365c;
    }
    .alert-auto-dismiss {
      width: auto;
      display: inline-block;
      min-width: 300px;
    }
    .modal-dialog {
      max-width: 400px;
    }
  </style>
  <style>
    .table-container {
      overflow-x: auto;
      max-width: 100%;
    }
    .dataTables_wrapper .dataTables_filter {
      float: right;
      margin-bottom: 1em;
    }
    .dataTables_wrapper .dataTables_length {
      float: left;
      margin-bottom: 1em;
    }
    .dataTables_wrapper .dataTables_paginate {
      float: right;
      margin-top: 1em;
    }
  </style>
  
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="../pages/dashboard.php">
        <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Creative Tim</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>" href="../pages/dashboard.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'approve') ? 'active' : ''; ?>" href="../pages/approve.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Leaves</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'staff') ? 'active' : ''; ?>" href="../pages/staff.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Staff</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed <?php echo $is_settings_page ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#settingsMenu" role="button" aria-expanded="<?php echo $is_settings_page ? 'true' : 'false'; ?>" aria-controls="settingsMenu">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-settings text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Settings</span>
          </a>
          <div class="collapse <?php echo $is_settings_page ? 'show' : ''; ?>" id="settingsMenu">
            <ul class="nav ms-4 ps-3">
              <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'company') ? 'active' : ''; ?>" href="../pages/company.php">
                  <span class="sidenav-normal">Company</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'branch') ? 'active' : ''; ?>" href="../pages/company_branch.php">
                  <span class="sidenav-normal">Branch</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'department') ? 'active' : ''; ?>" href="../pages/department.php">
                  <span class="sidenav-normal">Department</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'roles') ? 'active' : ''; ?>" href="../pages/roles.php">
                  <span class="sidenav-normal">Roles</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link <?php echo ($current_page == 'workshift') ? 'active' : ''; ?>" href="../pages/workshift.php">
                  <span class="sidenav-normal">Work Shift</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'rtl') ? 'active' : ''; ?>" href="../pages/rtl.php">
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
          <a class="nav-link <?php echo ($current_page == 'profile') ? 'active' : ''; ?>" href="../pages/profile.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'sign-in') ? 'active' : ''; ?>" href="../pages/sign-in.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Sign In</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'sign-up') ? 'active' : ''; ?>" href="../pages/sign-up.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-collection text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Sign Up</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer mx-3">
      <div class="card card-plain shadow-none" id="sidenavCard">
        <div class="card-body text-center p-3 w-100 pt-0">
          <div class="docs-info">
          </div>
        </div>
      </div>
    </div>
  </aside>

  <main class="main-content position-relative border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <?php if ($is_settings_page): ?>
              <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Settings</a></li>
            <?php endif; ?>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page"><?php echo $page_title; ?></li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0"><?php echo $page_title; ?></h6>
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
              <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3">
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
                        <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark me-3">
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
                      <div class="avatar avatar-sm bg-gradient-secondary me-3 my-auto">
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