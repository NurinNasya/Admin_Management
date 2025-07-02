<?php
session_start();

// Always load these first (before any MedClaim usage)
require_once __DIR__ . '/../db.php';           // Database connection
require_once __DIR__ . '/../Model/medClaim.php'; // MedClaim class

// Initialize model
$medClaim = new MedClaim($conn);
$staffId = $_SESSION['staff_id'] ?? 1; // Get from session

// Process form submission (if POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include __DIR__ . '/../Controller/medicalClaimController.php';
}

// Get claims data
$currentClaims = $medClaim->getCurrentClaims($staffId);
$claimBalances = $medClaim->getClaimBalances($staffId);

// Calculate medical balance
$medical_balance = [
    'validity' => '2025-12-31',
    'entitled' => 'RM 1,500.00',
    'used' => 'RM ' . number_format($claimBalances['used_amount'] ?? 0, 2),
    'current' => 'RM ' . number_format(1500 - ($claimBalances['used_amount'] ?? 0), 2),
    'pending' => 'RM ' . number_format($claimBalances['pending_amount'] ?? 0, 2),
    'available' => 'RM ' . number_format(1500 - ($claimBalances['used_amount'] ?? 0) - ($claimBalances['pending_amount'] ?? 0), 2)
];
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
  </style>
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
          <a class="nav-link " href="../pages/billing.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Billing</span>
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

<!-- Main Content -->
 <main class="main-content position-relative border-radius-lg">
  <!-- Navbar -->
            <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Settings</a></li>
                    <li class="breadcrumb-item text-sm text-white active" aria-current="page">Company</li>
                </ol>
                <h6 class="font-weight-bolder text-white mb-0">Company</h6>
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
      <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <span class="alert-icon"><i class="ni ni-like-2"></i></span>
          <span class="alert-text"><?php echo $success_message; ?></span>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php endif; ?>
      
      <div class="row">
            <!-- Medical Balance Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card medical-balance-card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-dark mb-3">Medical Claim Balance</h6>
                                
                                <div class="balance-item balance-validity">
                                    <span>Validity</span>
                                    <span><?php echo $medical_balance['validity']; ?></span>
                                </div>
                                
                                <div class="balance-item balance-entitled">
                                    <span>Entitled</span>
                                    <span><?php echo $medical_balance['entitled']; ?></span>
                                </div>
                                
                                <div class="balance-item balance-used">
                                    <span>Used</span>
                                    <span><?php echo $medical_balance['used']; ?></span>
                                </div>
                                
                                <div class="balance-item balance-current">
                                    <span>Current</span>
                                    <span><?php echo $medical_balance['current']; ?></span>
                                </div>
                                
                                <div class="balance-item balance-pending">
                                    <span>Pending</span>
                                    <span><?php echo $medical_balance['pending']; ?></span>
                                </div>
                                
                                <div class="balance-item balance-available">
                                    <span>Available</span>
                                    <span><?php echo $medical_balance['available']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Medical Claim Form -->
            <div class="col-xl-8 col-md-6 mb-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6>Medical Claim Form</h6>
                    </div>
                    <div class="card-body pt-0">
                        <!-- Reminder Box -->
                        <div class="reminder-box">
                            <div class="reminder-title">Reminder</div>
                            <div class="mb-2">1. Please attach supporting documents</div>
                            <div>2. The form must be verified by HR department</div>
                        </div>

                        <!-- Claim Form -->
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="add_claim">
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_receipt" class="form-control-label">Date Receipt<span class="text-danger">*</span></label>
                                        <input class="form-control" type="date" id="date_receipt" name="date_receipt" 
                                               value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="total" class="form-control-label">Total (RM)<span class="text-danger">*</span></label>
                                        <input class="form-control" type="number" id="total" name="total" 
                                               step="0.01" min="0" placeholder="0.00" required>
                                    </div>
                                </div>
                            
                                <div class="col-md-6">
                                    <label for="description" class="form-control-label">Description</label>
                                    <input class="form-control" type="text" id="description" name="description" 
                                          placeholder="Enter description">
                                </div>
                              </div>
                            
                            <div class="col-md-">
                                <label class="form-control-label">Attachment<span class="text-danger">*</span></label>
                                <div class="file-upload">
                                    <label class="file-upload-label">
                                        <i class="fas fa-cloud-upload-alt me-2"></i>Choose file (PDF, JPG, PNG)
                                        <input type="file" class="file-upload-input" id="attachment" name="attachment" 
                                               accept=".pdf,.jpg,.jpeg,.png,.heic,.heif">
                                    </label>
                                    <div class="file-name" id="file-name">No file chosen</div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn bg-gradient-primary w-100 mt-3">
                                <i class="fas fa-paper-plane me-2"></i> Submit Claim
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Claims History Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>Recent Medical Claims</h6>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Description</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Attachment</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($currentClaims['claims'] as $claim): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm"><?php echo date('Y-m-d', strtotime($claim['date_receipt'])); ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">RM<?php echo number_format($claim['total'], 2); ?></p>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0"><?php echo htmlspecialchars($claim['description']); ?></p>
                                        </td>
                                        <td>
                                            <?php
                                            $status = $claim['status'] ?? 'pending'; // Changed from primary_approve_status to status
                                            $badgeClass = $status === 'approved' ? 'badge-approved' : ($status === 'rejected' ? 'badge-rejected' : 'badge-pending');
                                            ?>
                                            <span class="badge badge-sm <?php echo $badgeClass; ?>"><?php echo ucfirst($status); ?></span>
                                        </td>
                                        <td>
    <?php if (!empty($claim['document_name'])): ?>
        <a href="../uploads/<?= htmlspecialchars($claim['document_name']) ?>" target="_blank" class="text-xs font-weight-bold mb-0 document-link">
            <i class="fas fa-file-pdf me-1 text-danger"></i>
            <?= htmlspecialchars($claim['document_name']) ?>
        </a>
    <?php else: ?>
        <span class="text-xs text-muted">No file</span>
    <?php endif; ?>
</td>
                                        <td class="action-buttons">
                                            <button class="btn btn-sm btn-outline-warning text-action-btn" onclick="openEditModal(<?php echo $claim['id']; ?>)">
                                                Edit
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger text-action-btn" onclick="confirmDelete(<?php echo $claim['id']; ?>)">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Claim Modal -->
    <div id="editClaimModal" class="edit-modal">
        <div class="edit-modal-content">
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
            <h5>Edit Medical Claim</h5>
            <form method="POST" enctype="multipart/form-data" id="editClaimForm">
                <input type="hidden" name="action" value="edit_claim">
                <input type="hidden" id="edit_claim_id" name="edit_claim_id">
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit_date_receipt" class="form-control-label">Date Receipt</label>
                            <input class="form-control" type="date" id="edit_date_receipt" name="edit_date_receipt" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit_total" class="form-control-label">Total (RM)</label>
                            <input class="form-control" type="number" id="edit_total" name="edit_total" 
                                   step="0.01" min="0" placeholder="0.00" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="edit_description" class="form-control-label">Description</label>
                    <input class="form-control" type="text" id="edit_description" name="edit_description" 
                           placeholder="Enter description">
                </div>
                
                <div class="form-group">
                    <label class="form-control-label">Attachment</label>
                    <div class="file-upload">
                        <label class="file-upload-label">
                            <i class="fas fa-cloud-upload-alt me-2"></i>Choose file (PDF, JPG, PNG)
                            <input type="file" class="file-upload-input" id="edit_attachment" name="edit_attachment" 
                                   accept=".pdf,.jpg,.jpeg,.png,.heic,.heif">
                        </label>
                        <div class="file-name" id="edit_file_name">No file chosen</div>
                    </div>
                    <div id="current_file" class="mt-2"></div>
                </div>
                
                <button type="submit" class="btn bg-gradient-primary w-100 mt-3">
                    <i class="fas fa-save me-2"></i> Update Claim
                </button>
            </form>
        </div>
    </div>

    <!-- JavaScript for modal and file handling -->
    <script>
        // File upload handling
        document.getElementById('attachment').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
            document.getElementById('file-name').textContent = fileName;
        });

        document.getElementById('edit_attachment').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
            document.getElementById('edit_file_name').textContent = fileName;
        });

        // Modal functions
        function openEditModal(claimId) {
            // Fetch claim data via AJAX
            fetch(`../Controller/medicalClaimController.php?get_claim=1&id=${claimId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('edit_claim_id').value = data.id;
                    document.getElementById('edit_date_receipt').value = data.date_receipt;
                    document.getElementById('edit_total').value = data.total;
                    document.getElementById('edit_description').value = data.description;
                    
                    if (data.document_name) {
                        document.getElementById('current_file').innerHTML = 
                            `<small>Current file: <a href="../uploads/${data.document_name}" target="_blank">${data.document_name}</a></small>`;
                    }
                    
                    document.getElementById('editClaimModal').style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading claim data');
                });
        }

        function closeEditModal() {
            document.getElementById('editClaimModal').style.display = 'none';
        }

        function confirmDelete(claimId) {
            if (confirm('Are you sure you want to delete this claim?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete_claim">
                    <input type="hidden" name="claim_id" value="${claimId}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function viewClaim(claimId) {
            // Implement view functionality
            alert('View claim ID: ' + claimId);
        }
    </script>
</body>
</html>