<?php
require_once '../db.php';  // Ensure the file is included only once
?>
<?php/* include_once '../Controller/staffController.php'; */?>

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
  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
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

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <aside
    class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
    id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
        aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html "
        target="_blank">
        <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100"
          alt="main_logo">
        <span class="ms-1 font-weight-bold">Creative Tim</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="../pages/dashboard.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages/tables.html">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Tables</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages/staff.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Staff</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-toggle="collapse" href="#settingsMenu" role="button"
            aria-expanded="false" aria-controls="settingsMenu">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
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
        <li class="nav-item">
          <a class="nav-link " href="../pages/rtl.html">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
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
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages/sign-in.html">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Sign In</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages/sign-up.html">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
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
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
      data-scroll="false">
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
              <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown"
                aria-expanded="false">
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
                        <img src="../assets/img/small-logos/logo-spotify.svg"
                          class="avatar avatar-sm bg-gradient-dark  me-3 ">
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
                        <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1"
                          xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                          <title>credit-card</title>
                          <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                              <g transform="translate(1716.000000, 291.000000)">
                                <g transform="translate(453.000000, 454.000000)">
                                  <path class="color-background"
                                    d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z"
                                    opacity="0.593633743"></path>
                                  <path class="color-background"
                                    d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z">
                                  </path>
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


    <div class="container-fluid py-4">
      <!-- Success/Error Messages -->
      <?php if (isset($_GET['success'])): ?>
        <?php if ($_GET['success'] === 'medical'): ?>
          <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
            Medical leave applied successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php elseif ($_GET['success'] === 'quota'): ?>
          <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
            Medical leave quota updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
      <?php elseif (isset($_GET['error'])): ?>
        <?php if ($_GET['error'] === 'medical'): ?>
          <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
            Error applying medical leave!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php elseif ($_GET['error'] === 'quota'): ?>
          <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
            Error updating medical leave quota!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h6>Employee List</h6>
                <a href="employee.php" class="btn btn-primary btn-sm">
                  <i class="fas fa-plus me-1"></i> Add Employee
                </a>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table id="employeeTable" class="table align-items-center mb-0 display nowrap" style="width:100%">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Profile</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Employee Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Role</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Department</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Phone</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    // Create dummy data if no staffs found
                    if (empty($staffs)) {
                      $departments = ['HR', 'Finance', 'IT', 'Marketing', 'Operations'];
                      $roles = ['Manager', 'Developer', 'Designer', 'Accountant', 'HR Specialist'];
                      
                      for ($i = 1; $i <= 50; $i++) {
                        $staffs[] = [
                          'id' => $i,
                          'profile_pic' => '../assets/img/default-avatar.png',
                          'name' => 'Staff ' . $i,
                          'role' => $roles[array_rand($roles)],
                          'department' => $departments[array_rand($departments)],
                          'phone' => '012-3456' . str_pad($i, 3, '0', STR_PAD_LEFT),
                          'medical_leave_quota' => rand(5, 20)
                        ];
                      }
                    }
                    ?>
                    
                    <?php if (!empty($staffs)): ?>
                      <?php foreach ($staffs as $index => $staff): ?>
                        <tr>
                          <td class="ps-4">
                            <p class="text-xs font-weight-bold mb-0"><?= $index + 1 ?></p>
                          </td>
                          <td>
                            <div>
                              <img src="<?= $staff['profile_pic'] ?: '../assets/img/default-avatar.png' ?>"
                                class="avatar avatar-sm me-3" alt="user1">
                            </div>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($staff['name']) ?></p>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($staff['role']) ?></p>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($staff['department']) ?></p>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($staff['phone']) ?></p>
                          </td>
                          <td class="align-middle">
                            <div class="dropdown">
                              <button class="btn btn-sm btn-icon-only text-light" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end px-2 py-3">
                                <li>
                                  <a class="dropdown-item border-radius-md" href="#" data-bs-toggle="modal" data-bs-target="#editStaffModal<?= $staff['id'] ?>">
                                    <i class="fas fa-pen me-2"></i> Edit
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item border-radius-md" href="medleavehr.php">
                                    <i class="fas fa-file-medical me-2"></i> Medical Claim
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item border-radius-md" href="medleave.php">
                                    <i class="fas fa-file-medical me-2"></i> Leave Quota
                                  </a>
                                </li>
                                <li>
                                  <form method="POST" action="../Controller/staffController.php"
                                    onsubmit="return confirm('Are you sure you want to delete this staff?');">
                                    <input type="hidden" name="staff_id" value="<?= $staff['id'] ?>">
                                    <button type="submit" name="delete_staff"
                                      class="dropdown-item border-radius-md text-danger">
                                      <i class="fas fa-trash me-2"></i> Delete
                                    </button>
                                  </form>
                                </li>
                              </ul>
                            </div>
                          </td>
                        </tr>

                        <!-- Edit staff Modal -->
                        <div class="modal fade" id="editStaffModal<?= $staff['id'] ?>" tabindex="-1"
                          aria-labelledby="editStaffModalLabel<?= $staff['id'] ?>" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="editStaffModalLabel<?= $staff['id'] ?>">Edit Staff</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <form method="POST" action="../Controller/staffController.php"
                                enctype="multipart/form-data">
                                <div class="modal-body">
                                  <input type="hidden" name="staff_id" value="<?= $staff['id'] ?>">
                                  <div class="mb-3">
                                    <label class="form-label">Nama Pekerja</label>
                                    <input type="text" class="form-control" name="name"
                                      value="<?= htmlspecialchars($staff['name']) ?>" required>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label">Kumpulan Peranan</label>
                                    <input type="text" class="form-control" name="role"
                                      value="<?= htmlspecialchars($staff['role']) ?>" required>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label">Bahagian</label>
                                    <input type="text" class="form-control" name="department"
                                      value="<?= htmlspecialchars($staff['department']) ?>" required>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label">No Talefon</label>
                                    <input type="text" class="form-control" name="phone"
                                      value="<?= htmlspecialchars($staff['phone']) ?>" required>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label">Gambar Profil</label>
                                    <input type="file" class="form-control" name="profile_pic">
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  <button type="submit" name="update_staff" class="btn btn-primary">Save Changes</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>

                        <!-- Medical Leave Modal -->
                        <div class="modal fade" id="medicalLeaveModal<?= $staff['id'] ?>" tabindex="-1"
                          aria-labelledby="medicalLeaveModalLabel<?= $staff['id'] ?>" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="medicalLeaveModalLabel<?= $staff['id'] ?>">Apply Medical
                                  Leave</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <form method="POST" action="../Controller/staffController.php">
                                <div class="modal-body">
                                  <input type="hidden" name="staff_id" value="<?= $staff['id'] ?>">
                                  <div class="mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" required>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="end_date" required>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label">Reason</label>
                                    <textarea class="form-control" name="reason" rows="3" required></textarea>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  <button type="submit" name="medical_leave" class="btn btn-primary">Submit</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>

                        <!-- Quota Modal -->
                        <div class="modal fade" id="quotaModal<?= $staff['id'] ?>" tabindex="-1"
                          aria-labelledby="quotaModalLabel<?= $staff['id'] ?>" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="quotaModalLabel<?= $staff['id'] ?>">Medical Leave Quota</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <form method="POST" action="../Controller/staffController.php">
                                <div class="modal-body">
                                  <input type="hidden" name="staff_id" value="<?= $staff['id'] ?>">
                                  <div class="mb-3">
                                    <label class="form-label">Current Quota</label>
                                    <input type="number" class="form-control" name="quota"
                                      value="<?= $staff['medical_leave_quota'] ?? 0 ?>" required>
                                  </div>
                                  <div class="mb-3">
                                    <label class="form-label">Leave History</label>
                                    <ul class="list-group">
                                      <?php
                                      // For dummy data, we'll just show some random leave history
                                      if (rand(0, 1)): ?>
                                        <?php for ($j = 1; $j <= rand(1, 3); $j++): 
                                          $start = date('d M Y', strtotime('-'.rand(1, 30).' days'));
                                          $end = date('d M Y', strtotime($start.' +'.rand(1, 5).' days'));
                                          $statuses = ['Approved', 'Pending', 'Rejected'];
                                          $status = $statuses[array_rand($statuses)];
                                        ?>
                                          <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <?= $start ?> - <?= $end ?>
                                            <span class="badge bg-<?= $status === 'Approved' ? 'success' : ($status === 'Rejected' ? 'danger' : 'warning') ?>">
                                              <?= $status ?>
                                            </span>
                                          </li>
                                        <?php endfor; ?>
                                      <?php else: ?>
                                        <li class="list-group-item">No medical leave records</li>
                                      <?php endif; ?>
                                    </ul>
                                  </div>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  <button type="submit" name="update_quota" class="btn btn-primary">Update Quota</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="7" class="text-center py-4">No staff found</td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Employee Modal//PAGE hr isi nama pekerja tutup dulu -->  
    <!-- <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="POST" action="../Controller/employeeController.php" enctype="multipart/form-data">
            <div class="modal-body">
              <div class="mb-3">
                <label class="form-label">Employee Name</label>
                <input type="text" class="form-control" name="name" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Role</label>
                <input type="text" class="form-control" name="role" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Department</label>
                <input type="text" class="form-control" name="department" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Phone</label>
                <input type="text" class="form-control" name="phone" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Profile Picture</label>
                <input type="file" class="form-control" name="profile_pic">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" name="add_employee" class="btn btn-primary">Add Employee</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    
    <script>
      // Initialize DataTable
      $(document).ready(function() {
        $('#staffTable').DataTable({
          scrollX: true,
          responsive: true,
          lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
          dom: '<"top"lf>rt<"bottom"ip>',
          language: {
            search: "_INPUT_",
            searchPlaceholder: "Search staffs...",
            lengthMenu: "Show _MENU_ staffs per page",
            zeroRecords: "No matching staffs found",
            info: "Showing _START_ to _END_ of _TOTAL_ staffs",
            infoEmpty: "No staffs available",
            infoFiltered: "(filtered from _MAX_ total staffs)"
          }
        });
      });

      // Auto-dismiss alerts after 5 seconds
      setTimeout(function () {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function (alert) {
          var bsAlert = new bootstrap.Alert(alert);
          bsAlert.close();
        });
      }, 5000);
    </script>
</body>

</html>