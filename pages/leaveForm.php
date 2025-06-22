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
function getLeaveApplications($conn, $employee_id) {
    try {
        $sql = "SELECT la.*, lt.type_name AS type_name 
                FROM leave_applications la
                LEFT JOIN leave_types lt ON la.leave_type = lt.id
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
function getApplicationById($conn, $id) {
    $id = (int)$id;
    $sql = "SELECT la.*, lt.name AS type_name 
            FROM leave_applications la
            LEFT JOIN leave_types lt ON la.leave_type = lt.name
            WHERE la.id = $id";
    
    $result = mysqli_query($conn, $sql);
    
    if (!$result || mysqli_num_rows($result) === 0) {
        return null;
    }
    
    return mysqli_fetch_assoc($result);
}

// Function to get leave balance
function getLeaveBalance($conn, $employee_id, $current_year) {
    // Initialize with empty array
    $balances = [];
    
    // Sanitize inputs
    $employee_id = (int)$employee_id;
    $current_year = (int)$current_year;
    
    // Query the database for leave balances
    $sql = "SELECT * FROM leave_balances WHERE employee_id = $employee_id AND year = $current_year";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Calculate values with fallbacks if database fields are missing
            $quota = $row['quota'] ?? 0;
            $used = $row['used'] ?? 0;
            $in_process = $row['in_process'] ?? 0;
            
            $balances[$row['leave_type']] = [
                'quota' => $quota,
                'used' => $used,
                'current' => $row['current_balance'] ?? ($quota - $used),
                'in_process' => $in_process,
                'available' => $row['available_balance'] ?? ($quota - $used - $in_process)
            ];
        }
    } else {
        // Log error if query fails
        error_log("Failed to get leave balance: " . mysqli_error($conn));
    }
    
    // Define all possible leave types with their default values
    $default_types = [
        'Annual Leave' => [
            'quota' => 14, 
            'used' => 0, 
            'current' => 14, 
            'in_process' => 0, 
            'available' => 14
        ],
        'Medical Leave' => [
            'quota' => 14, 
            'used' => 0, 
            'current' => 14, 
            'in_process' => 0, 
            'available' => 14
        ],
        'Unpaid Leave' => [
            'quota' => 0, 
            'used' => 0, 
            'current' => 0, 
            'in_process' => 0, 
            'available' => 0
        ],
        'Paternity Leave' => [
            'quota' => 7, 
            'used' => 0, 
            'current' => 7, 
            'in_process' => 0, 
            'available' => 7
        ],
        'Maternity Leave' => [
            'quota' => 60, 
            'used' => 0, 
            'current' => 60, 
            'in_process' => 0, 
            'available' => 60
        ],
        'Compassionate Leave' => [
            'quota' => 3, 
            'used' => 0, 
            'current' => 3, 
            'in_process' => 0, 
            'available' => 3
        ],
        'Hospitalization Leave' => [
            'quota' => 60, 
            'used' => 0, 
            'current' => 60, 
            'in_process' => 0, 
            'available' => 60
        ],
        'Replacement Leave' => [
            'quota' => 0, 
            'used' => 0, 
            'current' => 0, 
            'in_process' => 0, 
            'available' => 0
        ]
    ];
    
    // Merge database results with default types
    foreach ($default_types as $type => $default) {
        if (!isset($balances[$type])) {
            $balances[$type] = $default;
        } else {
            // Ensure all required keys exist even if some were missing from database
            $balances[$type] = array_merge($default, $balances[$type]);
        }
    }
    
    return $balances;
}
// Get data from database
$leave_applications = getLeaveApplications($conn, $employee_id);
$leave_balance = getLeaveBalance($conn, $employee_id, $current_year);

// Initialize default employee data
$employee_data = [
    'name' => $_SESSION['employee_name'] ?? 'N/A',
    'ic_number' => $_SESSION['employee_ic'] ?? 'N/A',
    'company' => $_SESSION['employee_company'] ?? 'N/A',
    'department' => $_SESSION['employee_department'] ?? 'N/A'
];

// Initialize dynamic leave types if not set
if (empty($dynamic_leave_types)) {
    $dynamic_leave_types = [
        ['id' => 1, 'type_name' => 'Annual Leave', 'quota' => 14],
        ['id' => 2, 'type_name' => 'Medical Leave', 'quota' => 14],
        ['id' => 3, 'type_name' => 'Unpaid Leave', 'quota' => 0],
        ['id' => 4, 'type_name' => 'Paternity Leave', 'quota' => 7],
        ['id' => 5, 'type_name' => 'Maternity Leave', 'quota' => 60],
        ['id' => 6, 'type_name' => 'Compassionate Leave', 'quota' => 3],
        ['id' => 7, 'type_name' => 'Hospitalization Leave', 'quota' => 60],
        ['id' => 8, 'type_name' => 'Replacement Leave', 'quota' => 0]
    ];
}

// Handle form submission for new leave application
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['leave_type'])) {
    $leave_data = [
        'employee_id' => $employee_id,
        'application_date' => $_POST['application_date'] ?? date('Y-m-d'),
        'start_date' => $_POST['start_date'] ?? '',
        'end_date' => $_POST['end_date'] ?? '',
        'total_days' => floatval($_POST['total_days'] ?? 0),
        'leave_type' => $_POST['leave_type'] ?? '',
        'reason' => $_POST['reason'] ?? ''
    ];

    if ($controller->saveLeaveApplication($leave_data)) {
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Permohonan cuti berjaya dihantar.'];
        header('Location: ?view=dashboard');
        exit;
    } else {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal menghantar permohonan cuti.'];
    }
}

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
  <title>
    AIMS-Office - Leave Management System
  </title>
  <!--     Fonts and icons     -->
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
      box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
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
    .employee-info-card {
      background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%);
      color: white;
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 20px;
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
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  
  <!-- Sidebar -->
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="?view=dashboard">
        <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">AIMS-Office</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?php echo $current_view == 'dashboard' ? 'active' : ''; ?>" href="?view=dashboard">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">AKAUN@AIMS-OFFICE</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo $current_view == 'dashboard_peribadi' ? 'active' : ''; ?>" href="#">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-chart-bar-32 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">DASHBOARD PERIBADI</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo $current_view == 'dashboard' || $current_view == 'applications' || $current_view == 'new_application' || $current_view == 'edit_application' ? 'active' : ''; ?>" href="?view=dashboard">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">LEAVE</span>
          </a>
        </li>     
        <li class="nav-item">
          <a class="nav-link <?php echo $current_view == 'in_out' ? 'active' : ''; ?>" href="#">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-watch-time text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">IN/OUT</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo $current_view == 'overtime' ? 'active' : ''; ?>" href="#">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-time-alarm text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">OVERTIME</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo $current_view == 'claim' ? 'active' : ''; ?>" href="#">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-money-coins text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">CLAIM</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo $current_view == 'travel' ? 'active' : ''; ?>" href="#">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-air-baloon text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">TRAVEL AUTHORIZATION</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo $current_view == 'training' ? 'active' : ''; ?>" href="#">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-books text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">TRAINING REQUEST</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">LEAVE</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">LEAVE MANAGEMENT</h6>
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
                <span class="d-sm-inline d-none user-name-display"><?php echo $employee_data['name']; ?></span>
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
          </ul>
        </div>
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
                <div class="employee-info-card" style="background: #f8f9fa; color: #495057; border: 1px solid #dee2e6; border-radius: 8px; padding: 20px;">
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
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Leave Quota</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Usage</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Current Balance</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">In Process</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Available Balance</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($leave_balance as $leave_type_name => $leave_data): 
                      $is_low_balance = $leave_data['available'] <= 3 && $leave_type_name != 'Unpaid Leave';
                    ?>
                    <tr <?php echo $is_low_balance ? 'class="low-balance"' : ''; ?>>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($leave_type_name); ?></h6>
                            <p class="text-xs text-secondary mb-0">
                              <?php echo in_array($leave_type_name, ['Annual Leave', 'Medical Leave']) ? 
                                "(09/03/$current_year - 24/06/$current_year)" : 
                                "(01/01/$current_year - 31/12/$current_year)"; ?>
                            </p>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">
                          <?php echo $leave_data['quota'] == 0 ? '- None -' : $leave_data['quota']; ?>
                        </p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo $leave_data['used']; ?></p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">
                          <?php echo $leave_data['current'] == 0 ? '- None -' : $leave_data['current']; ?>
                        </p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0"><?php echo $leave_data['in_process']; ?></p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">
                          <?php echo $leave_data['available'] == 0 ? '- None -' : $leave_data['available']; ?>
                        </p>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>

              <div class="text-end mt-4">
                <a href="?view=new_application" class="btn bg-gradient-primary">New Leave</a>
              </div>
            </div>

            <!-- Applications List Section -->
            <div class="card mb-4">
              <div class="card-header pb-0">
                <div class="row">
                  <div class="col-md-6">
                    <h6>List of individual leave applications</h6>
                  </div>
                  <div class="col-md-6 text-end">
                    <button class="btn btn-sm btn-outline-primary" onclick="refreshApplications()">
                      <i class="fas fa-sync-alt"></i> Refresh
                    </button>
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
                      <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                      <input type="text" class="form-control" placeholder="Search..." id="applicationSearch">
                    </div>
                  </div>
                </div>

                <div class="table-responsive p-0">
                  <table class="table align-items-center mb-0" id="applicationsTable">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Application Date</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Leave Information</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Support</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Approval</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">HR Check</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Reason</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Remarks</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($leave_applications as $index => $app): 
                        $status_class = strtolower($app['status'] ?? 'pending') == 'approved' ? 'status-approved' : 
                                       (strtolower($app['status'] ?? 'pending') == 'rejected' ? 'status-rejected' : 'status-pending');
                      ?>
                      <tr>
                        <td class="ps-4">
                          <p class="text-xs font-weight-bold mb-0"><?php echo $index + 1; ?></p>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0"><?php echo date('Y-m-d H:i:s', strtotime($app['created_at'] ?? $app['application_date'])); ?></p>
                          <p class="text-xs text-secondary mb-0"><?php echo date('l', strtotime($app['created_at'] ?? $app['application_date'])); ?></p>
                        </td>
                        <td>
    <p class="text-xs font-weight-bold mb-0"><strong>Date:</strong> 
        <?php echo date('d/m/Y', strtotime($app['start_date'])); ?> - 
        <?php echo date('d/m/Y', strtotime($app['end_date'])); ?>
    </p>
    <p class="text-xs font-weight-bold mb-0"><strong>Total:</strong> <?php echo $app['total_days']; ?> days</p>
    <p class="text-xs font-weight-bold mb-0"><strong>Type:</strong> 
        <?php 
        // Tampilkan jenis cuti dengan fallback
        echo !empty($app['type_name']) ? $app['type_name'] : 
             (!empty($app['leave_type']) ? $app['leave_type'] : 'Unknown');
        ?>
    </p>
</td>
                          <span class="text-xs font-weight-bold <?php echo $status_class; ?>">
                            <?php echo ucfirst($app['status'] ?? 'Pending'); ?>
                          </span>
                          <?php if (!empty($app['supported_at'])): ?>
                            <p class="text-xs text-secondary mb-0"><?php echo date('d/m/Y H:i', strtotime($app['supported_at'])); ?></p>
                          <?php endif; ?>
                        </td>
                        <td>
                          <span class="text-xs font-weight-bold <?php echo $status_class; ?>">
                            <?php echo ucfirst($app['status'] ?? 'Pending'); ?>
                          </span>
                          <?php if (!empty($app['approved_at'])): ?>
                            <p class="text-xs text-secondary mb-0"><?php echo date('d/m/Y H:i', strtotime($app['approved_at'])); ?></p>
                          <?php endif; ?>
                        </td>
                        <td>
                          <span class="text-xs font-weight-bold <?php echo $status_class; ?>">
                            <?php echo ucfirst($app['status'] ?? 'Pending'); ?>
                          </span>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0"><?php echo $app['reason'] ?? '-'; ?></p>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0"><?php echo $app['remarks'] ?? '-'; ?></p>
                        </td>
                        <td>
                          <div class="action-dropdown">
                            <button class="btn btn-sm btn-outline-secondary">⚙</button>
                            <div class="dropdown-content">
                              <a href="#" onclick="viewAttachment(<?php echo $app['id']; ?>)"><i class="fas fa-paperclip me-2"></i>Attachment</a>
                              <a href="#" onclick="viewInfo(<?php echo $app['id']; ?>)"><i class="fas fa-info-circle me-2"></i>Info</a>
                              <?php if (($app['status'] ?? 'pending') == 'pending'): ?>
                                <a href="#" onclick="updateApplication(<?php echo $app['id']; ?>)"><i class="fas fa-edit me-2"></i>Update</a>
                                <a href="#" onclick="deleteApplication(<?php echo $app['id']; ?>)" style="color: #f5365c;"><i class="fas fa-trash-alt me-2"></i>Delete</a>
                              <?php endif; ?>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
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
                        <label class="form-control-label">Application date *</label>
                        <input class="form-control" type="date" name="application_date" value="<?php echo date('Y-m-d'); ?>" required readonly>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-control-label">Start date *</label>
                        <input class="form-control" type="date" name="start_date" id="start_date" required 
                               min="<?php echo date('Y-m-d', strtotime('+3 days')); ?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-control-label">End Date *</label>
                        <input class="form-control" type="date" name="end_date" id="end_date" required 
                               min="<?php echo date('Y-m-d', strtotime('+3 days')); ?>">
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-control-label">Total Day *</label>
                        <input class="form-control" type="number" name="total_days" id="total_days" 
                               step="0.5" min="0.5" placeholder="1.0" required readonly>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-control-label">Type of leave *</label>
                        <div class="leave-type-radio">
                          <?php foreach ($dynamic_leave_types as $leave_type): 
                            $type_name = $leave_type['type_name'] ?? $leave_type['name'] ?? 'Unknown';
                            $type_id = $leave_type['id'] ?? '';
                            $quota = $leave_type['quota'] ?? 0;
                            $available = $leave_balance[$type_name]['available'] ?? $quota;
                          ?>
                          <label>
                            <input type="radio" name="leave_type" value="<?php echo htmlspecialchars($type_name); ?>" 
                                   data-id="<?php echo $type_id; ?>" 
                                   data-quota="<?php echo $quota; ?>"
                                   data-available="<?php echo $available; ?>">
                            <?php echo htmlspecialchars($type_name); ?>
                            <span class="text-xs text-secondary">(Available: <?php echo $available; ?> days)</span>
                          </label>
                          <?php endforeach; ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-control-label">Reason for requesting leave *</label>
                        <textarea class="form-control" name="reason" rows="8" placeholder="Enter reason for leave..." required></textarea>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-control-label">Attachment/Document (gif|png|jpeg|pdf|HEIC)</label>
                        <input class="form-control" type="file" name="attachment" accept=".gif,.png,.jpg,.jpeg,.pdf,.heic">
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

                <form method="POST" action="../Controller/LeaveFormController.php?action=update_application" id="editLeaveForm" enctype="multipart/form-data">
                  <input type="hidden" name="id" value="<?php echo $application['id']; ?>">
                  
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-control-label">Application date *</label>
                        <input class="form-control" type="date" name="application_date" 
                               value="<?php echo date('Y-m-d', strtotime($application['created_at'])); ?>" required readonly>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-control-label">Start date *</label>
                        <input class="form-control" type="date" name="start_date" id="edit_start_date" 
                               value="<?php echo $application['start_date']; ?>" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-control-label">End Date *</label>
                        <input class="form-control" type="date" name="end_date" id="edit_end_date" 
                               value="<?php echo $application['end_date']; ?>" required>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <label class="form-control-label">Total Day *</label>
                        <input class="form-control" type="number" name="total_days" id="edit_total_days" 
                               step="0.5" min="0.5" value="<?php echo $application['total_days']; ?>" required readonly>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-control-label">Type of leave *</label>
                        <div class="leave-type-radio">
                          <?php foreach ($dynamic_leave_types as $leave_type): 
                            $type_name = $leave_type['type_name'] ?? $leave_type['name'] ?? 'Unknown';
                            $checked = ($type_name == $application['type_name']) ? 'checked' : '';
                          ?>
                          <label>
                            <input type="radio" name="leave_type" value="<?php echo htmlspecialchars($type_name); ?>" 
                                   <?php echo $checked; ?>>
                            <?php echo htmlspecialchars($type_name); ?>
                          </label>
                          <?php endforeach; ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-control-label">Reason for requesting leave *</label>
                        <textarea class="form-control" name="reason" rows="8" required><?php echo htmlspecialchars($application['reason']); ?></textarea>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="form-control-label">Current Attachment</label>
                        <?php if (!empty($application['attachment'])): ?>
                          <p><a href="../uploads/<?php echo $application['attachment']; ?>" target="_blank">View Attachment</a></p>
                        <?php else: ?>
                          <p>No attachment</p>
                        <?php endif; ?>
                        <label class="form-control-label">New Attachment (optional)</label>
                        <input class="form-control" type="file" name="attachment" accept=".gif,.png,.jpg,.jpeg,.pdf,.heic">
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
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                <strong>MSET-Office & IT-AIMS</strong>
                <small>Platform Tahunan Sistem</small>
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
    // Handle form submission
    document.addEventListener('DOMContentLoaded', function() {
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
          
          // Calculate difference in days (inclusive of start and end dates)
          const diffTime = Math.abs(end - start);
          const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
          
          totalDays.value = diffDays.toFixed(1);
        }
      }
      
      if (startDate && endDate) {
        startDate.addEventListener('change', function() {
          if (startDate.value && endDate.value && new Date(startDate.value) > new Date(endDate.value)) {
            alert('Tarikh mula tidak boleh selepas tarikh akhir');
            startDate.value = '';
          } else {
            calculateDays();
          }
        });
        
        endDate.addEventListener('change', function() {
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
          editStartDate.addEventListener('change', function() {
              if (editStartDate.value && editEndDate.value && new Date(editStartDate.value) > new Date(editEndDate.value)) {
                  alert('Tarikh mula tidak boleh selepas tarikh akhir');
                  editStartDate.value = '';
              } else {
                  calculateEditDays();
              }
          });
          
          editEndDate.addEventListener('change', function() {
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
        leaveForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          // Validate leave type selection
          const leaveType = document.querySelector('input[name="leave_type"]:checked');
          if (!leaveType) {
            alert('Sila pilih jenis cuti');
            return;
          }
          
          // Validate dates and total days
          if (!startDate.value || !endDate.value || !totalDays.value) {
            alert('Sila isi tarikh mula dan tarikh akhir');
            return;
          }
          
          // Validate reason
          if (!this.reason.value.trim()) {
            alert('Sila isi sebab permohonan cuti');
            return;
          }
          
          // Check leave balance for paid leave types
          const leaveTypeName = leaveType.value;
          const availableDays = parseFloat(leaveType.dataset.available);
          const requestedDays = parseFloat(totalDays.value);
          
          if (leaveTypeName !== 'Unpaid Leave' && availableDays < requestedDays) {
            if (!confirm(`Baki cuti ${leaveTypeName} tidak mencukupi. Baki semasa: ${availableDays} hari. Adakah anda ingin teruskan?`)) {
              return;
            }
          }
          
          // Submit form if all validations pass
          this.submit();
        });
      }

      // Validate edit form submission
      const editLeaveForm = document.getElementById('editLeaveForm');
      if (editLeaveForm) {
        editLeaveForm.addEventListener('submit', function(e) {
          e.preventDefault();
          
          // Validate leave type selection
          const leaveType = this.querySelector('input[name="leave_type"]:checked');
          if (!leaveType) {
            alert('Sila pilih jenis cuti');
            return;
          }
          
          // Validate dates and total days
          if (!this.start_date.value || !this.end_date.value || !this.total_days.value) {
            alert('Sila isi tarikh mula dan tarikh akhir');
            return;
          }
          
          // Validate reason
          if (!this.reason.value.trim()) {
            alert('Sila isi sebab permohonan cuti');
            return;
          }
          
          // Submit form if all validations pass
          this.submit();
        });
      }
      
      // Handle search functionality
      const applicationSearch = document.getElementById('applicationSearch');
      if (applicationSearch) {
        applicationSearch.addEventListener('input', function() {
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

    function viewInfo(applicationId) {
      window.location.href = `../Controller/LeaveFormController.php?action=view_info&id=${applicationId}`;
    }

    function updateApplication(applicationId) {
      window.location.href = `?view=edit_application&id=${applicationId}`;
    }

    function deleteApplication(applicationId) {
      if (confirm('Are you sure you want to delete this application?')) {
        window.location.href = `../Controller/LeaveFormController.php?action=delete_application&id=${applicationId}`;
      }
    }
  </script>
</body>
</html>