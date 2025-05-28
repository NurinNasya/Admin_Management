<?php 
require_once '../db.php';  // Ensure the file is included only once
include_once '../Controller/medFormController.php';

// Define current page if not set
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">  

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>Argon Dashboard 3 by Creative Tim</title>

  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>

  <!-- Sidebar -->
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html" target="_blank">
        <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Creative Tim</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">

    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>" href="../pages/dashboard.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pages/tables.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Tables</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= ($current_page == 'staff.php') ? 'active' : '' ?>" href="../pages/staff.php">
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
            <span class="nav-link-text ms-1">Settings</span>
          </a>
          <div class="collapse" id="settingsMenu">
            <ul class="nav ms-4 ps-3">
              <li class="nav-item"><a class="nav-link" href="../pages/company.php"><span class="sidenav-normal">Company</span></a></li>
              <li class="nav-item"><a class="nav-link" href="../pages/department.php"><span class="sidenav-normal">Department</span></a></li>
              <li class="nav-item"><a class="nav-link" href="../pages/roles.php"><span class="sidenav-normal">Roles</span></a></li>
              <li class="nav-item"><a class="nav-link" href="../pages/workshift.php"><span class="sidenav-normal">Work Shift</span></a></li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pages/rtl.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-world-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">RTL</span>
          </a>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
        </li>
        <li class="nav-item"><a class="nav-link" href="../pages/profile.html"><i class="ni ni-single-02 text-dark text-sm opacity-10 me-2"></i><span class="nav-link-text ms-1">Profile</span></a></li>
        <li class="nav-item"><a class="nav-link" href="../pages/sign-in.html"><i class="ni ni-single-copy-04 text-dark text-sm opacity-10 me-2"></i><span class="nav-link-text ms-1">Sign In</span></a></li>
        <li class="nav-item"><a class="nav-link" href="../pages/sign-up.html"><i class="ni ni-collection text-dark text-sm opacity-10 me-2"></i><span class="nav-link-text ms-1">Sign Up</span></a></li>
      </ul>
    </div>
  </aside>
  <!-- Sidebar Ends -->

  <!-- Main Content -->
  <main class="main-content position-relative border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
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
              <span class="input-group-text text-body"><i class="fas fa-search"></i></span>
              <input type="text" class="form-control" placeholder="Type here...">
            </div>
          </div>
          <ul class="navbar-nav justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white font-weight-bold px-0">
                <i class="fa fa-user me-sm-1"></i><span class="d-sm-inline d-none">Sign In</span>
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
                <li><a class="dropdown-item" href="#">No new notifications</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- Navbar Ends -->
    
    <!-- Content goes here -->
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-5">
                <div class="card-header">
                    <h2>Medical Forms History</h2>
                    
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger">
                            There was an error processing your request.
                        </div>
                    <?php endif; ?>

                    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#medicalFormModal">
                        New Medical Form
                    </button>

                    <?php
                    // Initialize $forms if not set
                    $forms = $forms ?? [];
                    ?>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Amount (RM)</th>
                                <th>Document</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($forms)): ?>
                                <tr>
                                    <td colspan="5" class="text-center">No medical forms found</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($forms as $form): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($form['receipt_data'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($form['description'] ?? '') ?></td>
                                        <td><?= isset($form['total_amount']) ? number_format($form['total_amount'], 2) : '0.00' ?></td>
                                        <td>
                                            <?php if (!empty($form['document_name'])): ?>
                                                <a href="uploads/<?= urlencode($form['document_name']) ?>" target="_blank">View</a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($form['verify_status'] ?? 'Pending') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="medicalFormModal" tabindex="-1" aria-labelledby="medicalFormModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" action="index.php?action=create_medical_form">
                <div class="modal-header">
                    <h5 class="modal-title" id="medicalFormModalLabel">Submit Medical Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="staff_id" value="1">
                    <input type="hidden" name="updated_by" value="admin">

                    <div class="mb-3">
                        <label for="receipt_data" class="form-label">Receipt Date:</label>
                        <input type="date" id="receipt_data" name="receipt_data" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea id="description" name="description" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="total_amount" class="form-label">Total Amount (RM):</label>
                        <input type="number" id="total_amount" name="total_amount" class="form-control" step="0.01" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="document" class="form-label">Upload Document (PDF/JPEG/PNG, max 5MB):</label>
                        <input type="file" id="document" name="document" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    </div>

                    <div class="mb-3">
                        <label for="remark" class="form-label">Remark:</label>
                        <textarea id="remark" name="remark" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>