<?php
session_start();

require_once '../Controller/LeaveFormController.php';
require_once '../db.php';

use Controller\LeaveFormController;

$controller = new LeaveFormController($conn, $_GET, $_SERVER, $_FILES, $_SESSION);

$current_view = $_GET['view'] ?? 'dashboard';
$employee_id = $_SESSION['employee_id'] ?? 1;
$current_year = date('Y');

// Function to get leave applications with leave type names
function getLeaveApplications($conn, $employee_id)
{
  try {
    $sql = "SELECT la.*, lt.type_name AS type_name 
            FROM leave_applications la
            LEFT JOIN leave_types lt ON la.leave_type = lt.type_name
            WHERE la.employee_id = $employee_id 
            ORDER BY la.application_date DESC";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
      error_log("Database error: " . mysqli_error($conn));
      return [];
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
  } catch (Exception $e) {
    error_log("Error in getLeaveApplications: " . $e->getMessage());
    return [];
  }
}

// Function to get application by ID with leave type name
function getApplicationById($conn, $id)
{
  $id = (int) $id;
  $sql = "SELECT la.*, lt.type_name AS type_name 
          FROM leave_applications la
          LEFT JOIN leave_types lt ON la.leave_type = lt.type_name
          WHERE la.id = $id";
  $result = mysqli_query($conn, $sql);
  if (!$result || mysqli_num_rows($result) === 0) {
    return null;
  }
  return mysqli_fetch_assoc($result);
}

// Get data using controller methods
$leave_applications = $controller->getLeaveApplicationsWithTypes($employee_id);
$leave_types = $controller->getLeaveTypes();
$leave_balance_summary = $controller->getLeaveBalanceSummary($employee_id);

// Reformat the balance summary to match what your view expects
$leave_balance = [];
foreach ($leave_balance_summary as $balance) {
  $leave_balance[$balance['type_name']] = [
    'quota' => $balance['max_days'],
    'used' => $balance['used_days'],
    'current' => $balance['max_days'] - $balance['used_days'],
    'in_process' => $balance['pending_days'],
    'available' => $balance['available_days']
  ];
}








// Initialize default employee data
$employee_data = [
  'name' => $_SESSION['employee_name'] ?? 'N/A',
  'ic_number' => $_SESSION['employee_ic'] ?? 'N/A',
  'company' => $_SESSION['employee_company'] ?? 'N/A',
  'department' => $_SESSION['employee_department'] ?? 'N/A'
];



// Check for messages
$message = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

// Get application data for edit view
$application = null;
if ($current_view === 'edit_application' && isset($_GET['id'])) {
  $application = getApplicationById($conn, $_GET['id']);
  if (!$application) {
    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Permohonan tidak dijumpai.'];
    header('Location: ?view=dashboard');
    exit;
  }

  // Format dates for edit form
  $application['start_date_formatted'] = date('d/m/Y', strtotime($application['start_date']));
  $application['end_date_formatted'] = date('d/m/Y', strtotime($application['end_date']));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>AIMS-Office - Leave Management System</title>
  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
  <style>
    .status-supported {
      color: #2dce89;
      font-weight: bold;
    }

    .form-control-label:after {
      content: " *";
      color: red;
    }

    .employee-info-card {
      background: white !important;
      color: #495057 !important;
      border: 1px solid #dee2e6 !important;
    }

    .leave-type-radio label {
      font-weight: normal !important;
    }

    .action-buttons {
      display: flex;
      gap: 5px;
    }

    .status-approved {
      color: #11cdef;
      font-weight: bold;
    }

    .status-pending {
      color: #fb6340;
      font-weight: bold;
    }

    .status-rejected {
      color: #f5365c;
      font-weight: bold;
    }

    .version-badge {
      background-color: #5e72e4;
      color: white;
      padding: 3px 10px;
      border-radius: 15px;
      font-size: 12px;
      float: right;
    }

    .leave-type-radio {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .leave-type-radio label {
      display: flex;
      align-items: center;
      gap: 8px;
      cursor: pointer;
    }

    .leave-type-radio input[type="radio"] {
      margin: 0;
    }

    .action-dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background-color: #f9f9f9;
      min-width: 160px;
      box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
      z-index: 1;
      right: 0;
      border-radius: 8px;
    }

    .dropdown-content a {
      color: black;
      padding: 12px 16px;
      text-decoration: none;
      display: block;
      font-size: 14px;
    }

    .dropdown-content a:hover {
      background-color: #f1f1f1;
    }
    
    select option:disabled {
    color: #ccc;
    background-color: #f8f9fa;
}

    .action-dropdown:hover .dropdown-content {
      display: block;
    }

    .reminder-box {
      background-color: #fff3cd;
      border: 1px solid #ffeaa7;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .reminder-box h4 {
      color: #856404;
      margin-bottom: 10px;
    }

    .employee-info-row {
      display: flex;
      margin-bottom: 10px;
    }

    .employee-info-row label {
      font-weight: bold;
      width: 120px;
      margin-right: 10px;
    }

    .employee-info-row span {
      flex: 1;
    }

    .user-name-display {
      background-color: #e9f7ef;
      padding: 5px 15px;
      border-radius: 20px;
      color: #2dce89;
      font-weight: 600;
    }

    .low-balance {
      background-color: #fff3cd;
    }

    .low-balance td {
      font-weight: bold;
      color: #856404;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <aside
    class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
    id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
        aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#">
        <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100"
          alt="main_logo">
        <span class="ms-1 font-weight-bold">Your Logo</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="../pages/dashboard.html">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pages/approval_leave.php">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Leaves</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pages/staff.php">
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
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pages/profile.html">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pages/sign-in.html">
            <div
              class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Sign In</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content position-relative border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
      data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Dashboard</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Dashboard</h6>
        </nav>
      </div>
    </nav>
    <!-- End Navbar -->

    <!-- Display messages if any -->
    <?php if ($message): ?>
      <div class="container-fluid">
        <div class="alert alert-<?php echo $message['type']; ?> alert-dismissible fade show" role="alert">
          <?php echo $message['message']; ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      </div>
    <?php endif; ?>

    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <?php if ($current_view == 'dashboard'): ?>
            <!-- Combined Leave Balance and Applications View -->
            <div class="card mb-4">
              <div class="card-header pb-0">
                <h6>LEAVE <span class="version-badge">Version 02</span></h6>
              </div>

              <div class="card-body">
                <div class="employee-info-card">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="employee-info-row">
                        <label>Name:</label>
                        <span><?php echo $employee_data['name']; ?></span>
                      </div>
                      <div class="employee-info-row">
                        <label>IC Number:</label>
                        <span><?php echo $employee_data['ic_number']; ?></span>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="employee-info-row">
                        <label>Company:</label>
                        <span><?php echo $employee_data['company']; ?></span>
                      </div>
                      <div class="employee-info-row">
                        <label>Department:</label>
                        <span><?php echo $employee_data['department']; ?></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-4">
                <div class="col-md-3">
                  <label>Show information for the year:</label>
                  <select class="form-control" style="width: 100px;">
                    <option value="<?php echo $current_year; ?>" selected><?php echo $current_year; ?></option>
                  </select>
                </div>
              </div>

              <!-- Dynamic Leave Balance Table -->
              <div class="table-responsive">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Leave Type</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Leave Quota
                      </th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Usage</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Current Balance
                      </th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">In Process</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Available
                        Balance</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($leave_balance as $leave_type_name => $leave_data):
                      $is_low_balance = (isset($leave_data['available']) && $leave_data['available'] <= 3) && ($leave_type_name != 'Unpaid Leave');
                      ?>
                      <tr <?= $is_low_balance ? 'class="low-balance"' : '' ?>>
                        <td>
                          <div class="d-flex px-2 py-1">
                            <div class="d-flex flex-column justify-content-center">
                              <h6 class="mb-0 text-sm"><?= htmlspecialchars($leave_type_name) ?></h6>
                              <p class="text-xs text-secondary mb-0">
                                (01/01/<?= $current_year ?> - 31/12/<?= $current_year ?>)
                              </p>
                            </div>
                          </div>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0">
                            <?= isset($leave_data['quota']) ? ($leave_data['quota'] == 0 ? '- None -' : $leave_data['quota']) : 'N/A' ?>
                          </p>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0">
                            <?= isset($leave_data['used']) ? $leave_data['used'] : 'N/A' ?></p>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0">
                            <?= isset($leave_data['current']) ? ($leave_data['current'] == 0 ? '- None -' : $leave_data['current']) : 'N/A' ?>
                          </p>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0">
                            <?= isset($leave_data['in_process']) ? $leave_data['in_process'] : 'N/A' ?></p>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0">
                            <?= isset($leave_data['available']) ? ($leave_data['available'] == 0 ? '- None -' : $leave_data['available']) : 'N/A' ?>
                          </p>
                        </td>
                      </tr>
                    <?php endforeach; ?>

                    <!-- Applications List Section -->

                    <div class="card mb-4">
                      <div class="card-header pb-0">
                        <div class="row">
                          <div class="col-md-12">


                            <h6>List of individual leave applications</h6>
                          </div>
                        </div>
                      </div>
                      <div class="card-body px-0 pt-0 pb-2">
                        <div class="row px-4 mb-3">
                          <div class="col-md-3">
                            <label>Show: </label>
                            <select class="form-select" style="width: 80px; display: inline-block;">
                              <option value="10">10</option>
                              <option value="25">25</option>
                              <option value="50">50</option>
                              <option value="100">100</option>
                            </select>
                            <span> records</span>
                          </div>
                          <div class="col-md-5 ms-auto text-end">
                            <div class="input-group">
                              <span class="input-group-text text-body"><i class="fas fa-search"
                                  aria-hidden="true"></i></span>
                              <input type="text" class="form-control" placeholder="Search..." id="applicationSearch">
                            </div>
                          </div>
                        </div>

                        <div class="table-responsive p-0">
                          <table class="table align-items-center mb-0" id="applicationsTable">
                            <thead>
                              <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                  Application Date
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Leave
                                  Information
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Reason
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Support
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Approval
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HR Check
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($leave_applications as $index => $app):
                                $status_class = strtolower($app['status'] ?? 'pending') == 'approved' ? 'status-approved' :
                                  (strtolower($app['status'] ?? 'pending') == 'rejected' ? 'status-rejected' : 'status-pending'); ?>
                                <tr>
                                  <td class="ps-4">
                                    <p class="text-xs font-weight-bold mb-0"><?php echo $index + 1; ?></p>
                                  </td>
                                  <td>
                                    <p class="text-xs font-weight-bold mb-0">
                                      <?php echo date('Y-m-d H:i:s', strtotime($app['created_at'] ?? $app['application_date'])); ?>
                                    </p>
                                    <p class="text-xs text-secondary mb-0">
                                      <?php echo date('l', strtotime($app['created_at'] ?? $app['application_date'])); ?>
                                    </p>
                                  </td>
                                  <td>
                                    <p class="text-xs font-weight-bold mb-0"><strong>Date:</strong>
                                      <?php echo date('d/m/Y', strtotime($app['start_date'])); ?> -
                                      <?php echo date('d/m/Y', strtotime($app['end_date'])); ?>
                                    </p>
                                    <p class="text-xs font-weight-bold mb-0"><strong>Total:</strong>
                                      <?php echo $app['total_days']; ?> days</p>
                                    <p class="text-xs font-weight-bold mb-0"><strong>Type:</strong>
                                      <?php
                                      echo !empty($app['type_name']) ? htmlspecialchars($app['type_name']) :
                                        (!empty($app['leave_type']) ? htmlspecialchars($app['leave_type']) : 'Unknown');
                                      ?>
                                    </p>
                                  </td>
                                  <td>
                                    <p class="text-xs font-weight-bold mb-0">
                                      <?php echo !empty($app['reason']) ? htmlspecialchars($app['reason']) : 'No reason provided'; ?>
                                    </p>
                                  </td>
                                  <td>
                                    <span class="text-xs font-weight-bold <?php echo $status_class; ?>">
                                      <?php echo ucfirst($app['status'] ?? 'Pending'); ?>
                                    </span>
                                    <?php if (!empty($app['supported_at'])): ?>
                                      <p class="text-xs text-secondary mb-0">
                                        <?php echo date('d/m/Y H:i', strtotime($app['supported_at'])); ?>
                                      </p>
                                    <?php endif; ?>
                                  </td>
                                  <td>
                                    <span class="text-xs font-weight-bold <?php echo $status_class; ?>">
                                      <?php echo ucfirst($app['status'] ?? 'Pending'); ?>
                                    </span>
                                    <?php if (!empty($app['approved_at'])): ?>
                                      <p class="text-xs text-secondary mb-0">
                                        <?php echo date('d/m/Y H:i', strtotime($app['approved_at'])); ?>
                                      </p>
                                    <?php endif; ?>
                                  </td>
                                  <td>
                                    <span class="text-xs font-weight-bold <?php echo $status_class; ?>">
                                      <?php echo ucfirst($app['status'] ?? 'Pending'); ?>
                                    </span>
                                    <?php if (!empty($app['hr_checked_at'])): ?>
                                      <p class="text-xs text-secondary mb-0">
                                        <?php echo date('d/m/Y H:i', strtotime($app['hr_checked_at'])); ?>
                                      </p>
                                    <?php endif; ?>
                                  </td>
                                  <td>
                                    <div class="action-buttons">
                                      <?php if (!empty($app['attachment'])): ?>
                                        <a href="javascript:void(0);" onclick="viewAttachment(<?php echo $app['id']; ?>)"
                                          class="text-secondary" title="Attachment">
                                          Lampiran
                                        </a>
                                        <?php if (($app['status'] ?? 'pending') == 'pending'): ?> | <?php endif; ?>
                                      <?php endif; ?>
                                      <a href="javascript:void(0);" onclick="viewInfo(<?php echo $app['id']; ?>)"
                                        class="text-info" title="Info" data-toggle="modal" data-target="#infoModal">
                                        Information
                                      </a>
                                      <?php if (($app['status'] ?? 'pending') == 'pending'): ?>
                                        | <a href="javascript:void(0);" onclick="updateApplication(<?php echo $app['id']; ?>)"
                                          class="text-warning" title="Update">
                                          Updated
                                        </a>
                                        | <a href="javascript:void(0);" onclick="deleteApplication(<?php echo $app['id']; ?>)"
                                          class="text-danger" title="Delete" data-toggle="modal" data-target="#deleteModal">
                                          Delete
                                        </a>
                                      <?php endif; ?>
                                    </div>
                                  </td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <div class="card-header pb-0">


                        <div class="col-md-6 text-end">
                          <a href="?view=new_application" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> New Leave
                          </a>
                        </div>
                      </div>
                    </div>

                  <?php elseif ($current_view == 'new_application'): ?>
                    <!-- New Leave Application Form -->


                    <div class="card mb-4">
                      <div class="card-header pb-0">
                        <div class="row">
                          <div class="col-md-6">
                            <h6>CUTI 🔁▶ NEW <span class="version-badge">Switch to Version 01</span></h6>
                          </div>
                          <div class="col-md-6 text-end">
                            <a href="?view=dashboard" class="btn btn-outline-secondary">Back to Dashboard</a>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="employee-info-card">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="employee-info-row">
                                <label>Name:</label>
                                <span><?php echo $employee_data['name']; ?></span>
                              </div>
                              <div class="employee-info-row">
                                <label>IC Number:</label>
                                <span><?php echo $employee_data['ic_number']; ?></span>
                              </div>
                              <div class="employee-info-row">
                                <label>Staff No:</label>
                                <span><?php echo $employee_data['ic_number']; ?></span>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="employee-info-row">
                                <label>Company:</label>
                                <span><?php echo $employee_data['company']; ?></span>
                              </div>
                              <div class="employee-info-row">
                                <label>Department:</label>
                                <span><?php echo $employee_data['department']; ?></span>
                              </div>
                              <div class="employee-info-row">
                                <label>Quota usage for the year:</label>
                                <select class="form-select" style="width: 100px; display: inline-block;">
                                  <option value="<?php echo $current_year; ?>"><?php echo $current_year; ?></option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>



                        <h6 class="mt-4">Leave application form</h6>

                        <div class="reminder-box">
                          <h6>Reminder:</h6>
                          <ol>
                            <li>Use dpilahan ½ (or HALF DAY) (Example: 0.5 / 1.0 / 2.5 / 3.0)</li>
                            <li>Unpaid Leave cannot be used if you still have a balance of Annual Leave</li>
                            <li>Applications must be submitted at least 3 working days in advance</li>
                          </ol>
                        </div>

                        <form method="POST" action="" id="leaveForm" enctype="multipart/form-data">
                          <div class="row">
                            <div class="col-md-3">
                              <div class="form-group">
                                <label class="form-control-label">Application date </label>
                                <input class="form-control" type="date" name="application_date"
                                  value="<?php echo date('Y-m-d'); ?>" required readonly>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label class="form-control-label">Start date </label>
                                <input class="form-control" type="date" name="start_date" id="start_date" required
                                  min="<?php echo date('Y-m-d', strtotime('+3 days')); ?>">
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label class="form-control-label">End Date </label>
                                <input class="form-control" type="date" name="end_date" id="end_date" required
                                  min="<?php echo date('Y-m-d', strtotime('+3 days')); ?>">
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label class="form-control-label">Total Day </label>
                                <input class="form-control" type="number" name="total_days" id="total_days" step="0.5"
                                  min="0.5" placeholder="1.0" required readonly>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
    <label class="form-control-label">Type of leave <span class="text-danger">*</span></label>
    <select class="form-control" name="leave_type" required id="leaveTypeSelect">
        <option value="">-- Select Leave Type --</option>
        <?php foreach ($leave_types as $type_name => $type_data): 
            $available = $leave_balance[$type_name]['available'] ?? 0;
            $disabled = ($available <= 0 && $type_name != 'Unpaid Leave') ? 'disabled' : '';
        ?>
            <option value="<?= htmlspecialchars($type_name) ?>" 
                <?= $disabled ?>
                data-max-days="<?= $type_data['max_days'] ?>"
                data-available="<?= $available ?>">
                <?= htmlspecialchars($type_name) ?> 
                (Max: <?= $type_data['max_days'] ?> days, Available: <?= $available ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <small class="text-muted">Select from available leave types</small>
</div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="form-control-label">Reason for requesting leave </label>
                                <textarea class="form-control" name="reason" rows="8"
                                  placeholder="Enter reason for leave..." required></textarea>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="form-control-label">Attachment(pdf/jpg/jpeg/png/heic/heif, Max:4MB) </label>
                                <input class="form-control" type="file" name="attachment"
                                  accept=".gif,.png,.jpg,.jpeg,.pdf,.heic">
                                <small class="text-xs">No file chosen</small>
                              </div>
                            </div>
                          </div>

                          <div class="text-center mt-4">
                            <button type="submit" class="btn bg-gradient-primary">Submit</button>
                            <a href="?view=dashboard" class="btn btn-outline-secondary">Cancel</a>
                          </div>
                        </form>
                      </div>
                    </div>

                  <?php elseif ($current_view == 'edit_application'): ?>
                    <!-- Edit Leave Application Form -->
                    <div class="card mb-4">
                      <div class="card-header pb-0">
                        <div class="row">
                          <div class="col-md-6">
                            <h6>EDIT LEAVE APPLICATION <span class="version-badge">Version 02</span></h6>
                          </div>
                          <div class="col-md-6 text-end">
                            <a href="?view=dashboard" class="btn btn-outline-secondary">Back to Dashboard</a>
                          </div>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="employee-info-card">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="employee-info-row">
                                <label>Name:</label>
                                <span><?php echo $employee_data['name']; ?></span>
                              </div>
                              <div class="employee-info-row">
                                <label>IC Number:</label>
                                <span><?php echo $employee_data['ic_number']; ?></span>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="employee-info-row">
                                <label>Company:</label>
                                <span><?php echo $employee_data['company']; ?></span>
                              </div>
                              <div class="employee-info-row">
                                <label>Department:</label>
                                <span><?php echo $employee_data['department']; ?></span>
                              </div>
                            </div>
                          </div>
                        </div>

                        <h6 class="mt-4">Leave application form</h6>

                        <div class="reminder-box">
                          <h6>Reminder:</h6>
                          <ol>
                            <li>Use dpilahan ½ (or HALF DAY) (Example: 0.5 / 1.0 / 2.5 / 3.0)</li>
                            <li>Unpaid Leave cannot be used if you still have a balance of Annual Leave</li>
                            <li>Applications must be submitted at least 3 working days in advance</li>
                          </ol>
                        </div>

                        <form method="POST" action="../Controller/LeaveFormController.php?action=update_application"
                          id="editLeaveForm" enctype="multipart/form-data">
                          <input type="hidden" name="id" value="<?php echo $application['id']; ?>">

                          <div class="row">
                            <div class="col-md-3">
                              <div class="form-group">
                                <label class="form-control-label">Application date <span
                                    class="text-danger">*</span></label>
                                <input class="form-control" type="date" name="application_date"
                                  value="<?php echo date('Y-m-d', strtotime($application['created_at'])); ?>" required
                                  readonly>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label class="form-control-label">Start date <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" name="start_date" id="edit_start_date"
                                  value="<?php echo $application['start_date']; ?>" required>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label class="form-control-label">End Date <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" name="end_date" id="edit_end_date"
                                  value="<?php echo $application['end_date']; ?>" required>
                              </div>
                            </div>
                            <div class="col-md-3">
                              <div class="form-group">
                                <label class="form-control-label">Total Day <span class="text-danger">*</span></label>
                                <input class="form-control" type="number" name="total_days" id="edit_total_days"
                                  step="0.5" min="0.5" value="<?php echo $application['total_days']; ?>" required
                                  readonly>
                              </div>
                            </div>
                          </div>

                          <div class="row">
                            <div class="col-md-4">
                              <div class="form-group">
    <label class="form-control-label">Type of leave <span class="text-danger">*</span></label>
    <select class="form-control" name="leave_type" required id="editLeaveTypeSelect">
        <option value="">-- Select Leave Type --</option>
        <?php foreach ($leave_types as $type_name => $type_data): 
            $balance = $leave_balance[$type_name] ?? ['available' => 0];
            $available = $balance['available'];
            $disabled = ($available <= 0 && $type_name != 'Unpaid Leave') ? 'disabled' : '';
            $selected = ($type_name == $application['leave_type']) ? 'selected' : '';
        ?>
            <option value="<?= htmlspecialchars($type_name) ?>" 
                <?= $disabled ?> <?= $selected ?>
                data-max-days="<?= $type_data['max_days'] ?>"
                data-available="<?= $available ?>">
                <?= htmlspecialchars($type_name) ?> 
                (Max: <?= $type_data['max_days'] ?> days, Available: <?= number_format($available, 1) ?>)
            </option>
        <?php endforeach; ?>
    </select>
</div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="form-control-label">Reason for requesting leave <span
                                    class="text-danger">*</span></label>
                                <textarea class="form-control" name="reason" rows="8"
                                  required><?php echo htmlspecialchars($application['reason']); ?></textarea>
                              </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group">
                                <label class="form-control-label">Current Attachment</label>
                                <?php if (!empty($application['attachment'])): ?>
                                  <p><a href="../uploads/<?php echo $application['attachment']; ?>" target="_blank">View
                                      Attachment</a></p>
                                <?php else: ?>
                                  <p>No attachment</p>
                                <?php endif; ?>
                                <label class="form-control-label">New Attachment (optional)</label>
                                <input class="form-control" type="file" name="attachment"
                                  accept=".gif,.png,.jpg,.jpeg,.pdf,.heic">
                              </div>
                            </div>
                          </div>

                          <div class="text-center mt-4">
                            <button type="submit" class="btn bg-gradient-primary">Update Application</button>
                            <a href="?view=dashboard" class="btn btn-outline-secondary">Cancel</a>
                          </div>
                        </form>
                      </div>
                    </div>
                  <?php endif; ?>
            </div>
          </div>

          <footer class="footer pt-3">
            <div class="container-fluid">
              <div class="row align-items-center justify-content-lg-between">
                <div class="col-lg-6 mb-lg-0 mb-4">
                  <div class="copyright text-center text-sm text-muted text-lg-start">
                    ©
                    <script>
                      document.write(new Date().getFullYear())
                    </script>,
                    <strong>MSET-Office</strong>
                    <small></small>
                  </div>
                </div>
              </div>
            </div>
          </footer>
        </div>
  </main>

  <!-- Info Modal -->
  <!--<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="infoModalLabel">Leave Application Details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="infoModalBody">  -->
          
        <!-- Content will be loaded via AJAX -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  



  <!-- Core JS Files -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
    // Enhanced viewInfo function with better error handling
    function viewInfo(applicationId) {
      $('#infoModalBody').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
      $('#infoModal').modal('show');

      $.ajax({
        url: `../Controller/LeaveFormController.php?action=view_info&id=${applicationId}`,
        type: 'GET',
        dataType: 'json',
        success: function (response) {
          if (!response || typeof response !== 'object') {
            $('#infoModalBody').html('<div class="alert alert-danger">Invalid response format from server</div>');
            return;
          }

          if (response.error) {
            $('#infoModalBody').html(`<div class="alert alert-danger">${response.error}</div>`);
            return;
          }

          const formatDate = (dateString) => {
            if (!dateString) return 'N/A';
            try {
              const date = new Date(dateString);
              return isNaN(date) ? 'Invalid date' : date.toLocaleDateString('en-GB');
            } catch (e) {
              return dateString;
            }
          };

          const formatDateTime = (dateString) => {
            if (!dateString) return null;
            try {
              const date = new Date(dateString);
              return isNaN(date) ? null : date.toLocaleString('en-GB');
            } catch (e) {
              return null;
            }
          };

          let html = `
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Application Date:</label>
                  <p>${formatDate(response.application_date)}</p>
                </div>
                <div class="form-group">
                  <label>Leave Type:</label>
                  <p>${response.leave_type || 'N/A'}</p>
                </div>
                <div class="form-group">
                  <label>Start Date:</label>
                  <p>${formatDate(response.start_date)}</p>
                </div>
                <div class="form-group">
                  <label>End Date:</label>
                  <p>${formatDate(response.end_date)}</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Total Days:</label>
                  <p>${response.total_days || '0'}</p>
                </div>
                <div class="form-group">
                  <label>Status:</label>
                  <p class="status-${(response.status || '').toLowerCase()}">${response.status || 'Unknown'}</p>
                </div>
                ${response.supported_at ? `<div class="form-group">
                  <label>Supported At:</label>
                  <p>${formatDateTime(response.supported_at) || 'N/A'}</p>
                </div>` : ''}
                ${response.approved_at ? `<div class="form-group">
                  <label>Approved At:</label>
                  <p>${formatDateTime(response.approved_at) || 'N/A'}</p>
                </div>` : ''}
                ${response.hr_checked_at ? `<div class="form-group">
                  <label>HR Checked At:</label>
                  <p>${formatDateTime(response.hr_checked_at) || 'N/A'}</p>
                </div>` : ''}
              </div>
            </div>
            <div class="form-group">
              <label>Reason:</label>
              <p>${response.reason || 'No reason provided'}</p>
            </div>
            ${response.attachment ? `<div class="form-group">
              <label>Attachment:</label>
              <p><a href="../uploads/${response.attachment}" target="_blank" onclick="event.stopPropagation()">View Attachment</a></p>
            </div>` : ''}
          `;

          $('#infoModalBody').html(html);
        },
        error: function (xhr, status, error) {
          let errorMsg = 'Failed to load application details.';
          if (xhr.responseJSON && xhr.responseJSON.error) {
            errorMsg = xhr.responseJSON.error;
          } else if (xhr.responseText) {
            try {
              const res = JSON.parse(xhr.responseText);
              errorMsg = res.error || errorMsg;
            } catch (e) {
              errorMsg = xhr.responseText || errorMsg;
            }
          }
          $('#infoModalBody').html(`<div class="alert alert-danger">${errorMsg}</div>`);
          console.error("AJAX Error:", status, error);
        }
      });
    }

    // Function to handle update
    function updateApplication(applicationId) {
      window.location.href = `?view=edit_application&id=${applicationId}`;
    }

    // Function to handle delete
    let currentDeleteId = null;

    function deleteApplication(applicationId) {
      if (confirm('Are you sure you want to delete this application?')) {
        $.ajax({
          url: '../Controller/LeaveFormController.php?action=delete_application',
          type: 'POST',
          data: { id: applicationId },
          dataType: 'json',
          success: function (response) {
            if (response.success) {
              alert('Application deleted successfully');
              location.reload();
            } else {
              alert('Error: ' + (response.error || 'Unknown error'));
            }
          },
          error: function (xhr) {
            alert('Failed to delete application. Please try again.');
          }
        });
      }
    }

    // Improved delete confirmation
    $('#confirmDelete').on('click', function () {
      if (currentDeleteId) {
        const $modal = $('#deleteModal');
        const $originalContent = $modal.find('.modal-body').html();

        $modal.find('.modal-body').html(`
          <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p class="mt-2">Deleting application...</p>
          </div>
        `);

        $.ajax({
          url: `../Controller/LeaveFormController.php?action=delete_application&id=${currentDeleteId}`,
          type: 'GET',
          success: function () {
            $modal.modal('hide');
            refreshApplications();
          },
          error: function (xhr) {
            $modal.find('.modal-body').html(`
              <div class="alert alert-danger">
                Failed to delete application: ${xhr.responseText || 'Unknown error'}
                <button type="button" class="btn btn-secondary mt-2" onclick="$('#deleteModal').modal('hide')">Close</button>
              </div>
            `);
          }
        });
      }
    });




      //penambahan lah ma!!!! testingg 
      // Add this to your JavaScript section
document.addEventListener('DOMContentLoaded', function() {
    const leaveTypeSelect = document.querySelector('select[name="leave_type"]');
    const totalDaysInput = document.querySelector('input[name="total_days"]');
    
    if (leaveTypeSelect && totalDaysInput) {
        leaveTypeSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const maxDays = parseFloat(selectedOption.dataset.maxDays);
            const availableDays = parseFloat(selectedOption.dataset.available);
            
            // Update validation for total days
            totalDaysInput.max = maxDays;
            
            // Show warning if trying to exceed available days
            if (availableDays < parseFloat(totalDaysInput.value)) {
                alert(`Warning: You only have ${availableDays} days available for this leave type`);
            }
        });
    }
});  /////kne uji dulu boleh ke tidak 











    // Handle form submission
    document.addEventListener('DOMContentLoaded', function () {
      // Auto-calculate days when dates change
      const startDate = document.querySelector('input[name="start_date"]');
      const endDate = document.querySelector('input[name="end_date"]');
      const totalDays = document.querySelector('input[name="total_days"]');

      function calculateDays() {
        if (startDate.value && endDate.value) {
          const start = new Date(startDate.value);
          const end = new Date(endDate.value);

          if (start > end) {
            alert('Tarikh mula tidak boleh selepas tarikh akhir');
            startDate.value = '';
            endDate.value = '';
            totalDays.value = '';
            return;
          }

          const diffTime = Math.abs(end - start);
          const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
          totalDays.value = diffDays.toFixed(1);
        }
      }

      if (startDate && endDate) {
        startDate.addEventListener('change', function () {
          if (startDate.value && endDate.value && new Date(startDate.value) > new Date(endDate.value)) {
            alert('Tarikh mula tidak boleh selepas tarikh akhir');
            startDate.value = '';
          } else {
            calculateDays();
          }
        });

        endDate.addEventListener('change', function () {
          if (startDate.value && endDate.value && new Date(startDate.value) > new Date(endDate.value)) {
            alert('Tarikh akhir tidak boleh sebelum tarikh mula');
            endDate.value = '';
          } else {
            calculateDays();
          }
        });
      }

      // Edit form date calculation
      const editStartDate = document.getElementById('edit_start_date');
      const editEndDate = document.getElementById('edit_end_date');
      const editTotalDays = document.getElementById('edit_total_days');

      function calculateEditDays() {
        if (editStartDate.value && editEndDate.value) {
          const start = new Date(editStartDate.value);
          const end = new Date(editEndDate.value);

          if (start > end) {
            alert('Tarikh mula tidak boleh selepas tarikh akhir');
            editStartDate.value = '';
            editEndDate.value = '';
            editTotalDays.value = '';
            return;
          }

          const diffTime = Math.abs(end - start);
          const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
          editTotalDays.value = diffDays.toFixed(1);
        }
      }

      if (editStartDate && editEndDate) {
        editStartDate.addEventListener('change', function () {
          if (editStartDate.value && editEndDate.value && new Date(editStartDate.value) > new Date(editEndDate.value)) {
            alert('Tarikh mula tidak boleh selepas tarikh akhir');
            editStartDate.value = '';
          } else {
            calculateEditDays();
          }
        });

        editEndDate.addEventListener('change', function () {
          if (editStartDate.value && editEndDate.value && new Date(editStartDate.value) > new Date(editEndDate.value)) {
            alert('Tarikh akhir tidak boleh sebelum tarikh mula');
            editEndDate.value = '';
          } else {
            calculateEditDays();
          }
        });
      }

      // Validate leave form submission
      const leaveForm = document.getElementById('leaveForm');
      if (leaveForm) {
        leaveForm.addEventListener('submit', function (e) {
          e.preventDefault();

          const leaveType = document.querySelector('input[name="leave_type"]:checked');
          if (!leaveType) {
            alert('Sila pilih jenis cuti');
            return;
          }

          if (!startDate.value || !endDate.value || !totalDays.value) {
            alert('Sila isi tarikh mula dan tarikh akhir');
            return;
          }

          if (!this.reason.value.trim()) {
            alert('Sila isi sebab permohonan cuti');
            return;
          }

          const leaveTypeName = leaveType.value;
          const availableDays = parseFloat(leaveType.dataset.available);
          const requestedDays = parseFloat(totalDays.value);

          if (leaveTypeName !== 'Unpaid Leave' && availableDays < requestedDays) {
            if (!confirm(`Baki cuti ${leaveTypeName} tidak mencukupi. Baki semasa: ${availableDays} hari. Adakah anda ingin teruskan?`)) {
              return;
            }
          }

          this.submit();
        });
      }

      // Validate edit form submission
      const editLeaveForm = document.getElementById('editLeaveForm');
      if (editLeaveForm) {
        editLeaveForm.addEventListener('submit', function (e) {
          e.preventDefault();

          const leaveType = this.querySelector('input[name="leave_type"]:checked');
          if (!leaveType) {
            alert('Sila pilih jenis cuti');
            return;
          }

          if (!this.start_date.value || !this.end_date.value || !this.total_days.value) {
            alert('Sila isi tarikh mula dan tarikh akhir');
            return;
          }

          if (!this.reason.value.trim()) {
            alert('Sila isi sebab permohonan cuti');
            return;
          }

          this.submit();
        });
      }

      // Handle search functionality
      const applicationSearch = document.getElementById('applicationSearch');
      if (applicationSearch) {
        applicationSearch.addEventListener('input', function () {
          const searchTerm = this.value.toLowerCase();
          const tableRows = document.querySelectorAll('#applicationsTable tbody tr');

          tableRows.forEach(row => {
            const rowText = row.textContent.toLowerCase();
            if (rowText.includes(searchTerm)) {
              row.style.display = '';
            } else {
              row.style.display = 'none';
            }
          });
        });
      }
    });

    function refreshApplications() {
      location.reload();
    }

    function viewAttachment(applicationId) {
      window.location.href = `../Controller/LeaveFormController.php?action=view_attachment&id=${applicationId}`;
    }

    // Tambahkan di bagian JavaScript
const leaveSocket = new WebSocket(`ws://${window.location.hostname}:8080/leave-updates`);

leaveSocket.onmessage = function(event) {
    const data = JSON.parse(event.data);
    
    if (data.type === 'leave_update') {
        // Update leave balance display
        const balanceRow = document.querySelector(`tr[data-leave-type="${data.data.type_name}"]`);
        if (balanceRow) {
            balanceRow.querySelector('.leave-quota').textContent = data.data.max_days;
            balanceRow.querySelector('.leave-available').textContent = 
                data.data.max_days - balanceRow.dataset.used - balanceRow.dataset.inProcess;
        }
        
        // Update leave type dropdown if needed
        const leaveTypeSelect = document.querySelector('select[name="leave_type"]');
        if (leaveTypeSelect) {
            const option = leaveTypeSelect.querySelector(`option[value="${data.data.id}"]`);
            if (option) {
                option.textContent = `${data.data.type_name} (${data.data.max_days} days)`;
            }
        }
        
        showAlert('info', `Leave type "${data.data.type_name}" has been updated`);
    }
};

// Subscribe to updates when page loads
window.addEventListener('load', () => {
    leaveSocket.send(JSON.stringify({
        type: 'subscribe',
        channel: 'leave_updates'
    }));
});





  </script>
</body>

</html>