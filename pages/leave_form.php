<?php 
require_once '../db.php';  // Ensure the file is included only once
include_once '../Controller/leaveFormController.php';

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

<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Leave Application History</h2>
        </div>
        <div class="card-body">
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">An error occurred. Please try again.</div>
            <?php endif; ?>

            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#leaveFormModal">
                Apply for Leave
            </button>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Leave Period</th>
                            <th>Type</th>
                            <th>Days</th>
                            <th>Document</th>
                            <th>Support Status</th>
                            <th>Approval Status</th>
                            <th>Check Status</th>
                            <th>Balance</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($leaves)): ?>
                            <tr>
                                <td colspan="9" class="text-center">No leave applications found</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($leaves as $leave): ?>
                                <tr>
                                    <td>
                                        <?= htmlspecialchars($leave['start_date']) ?> to<br>
                                        <?= htmlspecialchars($leave['end_date']) ?>
                                    </td>
                                    <td><?= htmlspecialchars($leave['leave_type']) ?></td>
                                    <td><?= htmlspecialchars($leave['total_days']) ?></td>
                                    <td>
                                        <?php if (!empty($leave['document_name'])): ?>
                                            <a href="uploads/leaves/<?= urlencode($leave['document_name']) ?>" 
                                               target="_blank" class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td class="status-<?= strtolower($leave['support_status'] ?? 'pending') ?>">
                                        <?= htmlspecialchars($leave['support_status'] ?? 'Pending') ?>
                                    </td>
                                    <td class="status-<?= strtolower($leave['approve_status'] ?? 'pending') ?>">
                                        <?= htmlspecialchars($leave['approve_status'] ?? 'Pending') ?>
                                    </td>
                                    <td class="status-<?= strtolower($leave['check_status'] ?? 'pending') ?>">
                                        <?= htmlspecialchars($leave['check_status'] ?? 'Pending') ?>
                                    </td>
                                    <td><?= htmlspecialchars($leave['balance_after_checked'] ?? '-') ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-info" 
                                                onclick="showDetails(<?= htmlspecialchars(json_encode($leave)) ?>)">
                                            Details
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Leave Application Modal -->
<div class="modal fade" id="leaveFormModal" tabindex="-1" aria-labelledby="leaveFormModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" enctype="multipart/form-data" action="index.php?action=create_leave">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="leaveFormModalLabel">New Leave Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="staff_id" value="1">
                    <input type="hidden" name="updated_by" value="current_user">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="leave_type" class="form-label">Leave Type</label>
                            <select class="form-select" id="leave_type" name="leave_type" required>
                                <option value="">Select Leave Type</option>
                                <option value="Annual">Annual Leave</option>
                                <option value="Medical">Medical Leave</option>
                                <option value="Emergency">Emergency Leave</option>
                                <option value="Unpaid">Unpaid Leave</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="total_days" class="form-label">Total Days</label>
                            <input type="number" class="form-control" id="total_days" name="total_days" min="1" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="document" class="form-label">Supporting Document (if any)</label>
                        <input type="file" class="form-control" id="document" name="document" accept=".pdf,.jpg,.jpeg,.png">
                        <div class="form-text">Max file size: 5MB (PDF, JPG, PNG)</div>
                    </div>

                    <div class="mb-3">
                        <label for="remark" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remark" name="remark" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Details Modal (will be populated by JavaScript) -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">Leave Application Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailsContent">
                <!-- Details will be inserted here by JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="index.php?action=update_leave_status">
                <input type="hidden" id="statusLeaveId" name="id">
                <input type="hidden" id="statusField" name="field">
                <input type="hidden" name="updated_by" value="current_user">
                
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="statusModalTitle">Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="statusValue" class="form-label">Status</label>
                        <select class="form-select" id="statusValue" name="status" required>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="balanceAfter" class="form-label">Balance After (if applicable)</label>
                        <input type="number" class="form-control" id="balanceAfter" name="balance_after_checked" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showDetails(leave) {
        let details = `
            <p><strong>Leave Period:</strong> ${leave.start_date} to ${leave.end_date}</p>
            <p><strong>Type:</strong> ${leave.leave_type}</p>
            <p><strong>Days:</strong> ${leave.total_days}</p>
            <p><strong>Description:</strong> ${leave.description || '-'}</p>
            <p><strong>Remarks:</strong> ${leave.remark || '-'}</p>
            <p><strong>Support Status:</strong> <span class="status-${(leave.support_status || 'pending').toLowerCase()}">
                ${leave.support_status || 'Pending'} by ${leave.support_by || '-'} at ${leave.support_at || '-'}
            </span></p>
            <p><strong>Approval Status:</strong> <span class="status-${(leave.approve_status || 'pending').toLowerCase()}">
                ${leave.approve_status || 'Pending'} by ${leave.approve_by || '-'} at ${leave.approve_at || '-'}
            </span></p>
            <p><strong>Check Status:</strong> <span class="status-${(leave.check_status || 'pending').toLowerCase()}">
                ${leave.check_status || 'Pending'} by ${leave.check_by || '-'} at ${leave.check_at || '-'}
            </span></p>
            <p><strong>Balance After:</strong> ${leave.balance_after_checked || '-'}</p>
        `;
        
        document.getElementById('detailsContent').innerHTML = details;
        new bootstrap.Modal(document.getElementById('detailsModal')).show();
    }

    function updateStatus(id, field) {
        document.getElementById('statusLeaveId').value = id;
        document.getElementById('statusField').value = field;
        document.getElementById('statusModalTitle').textContent = `Update ${field.charAt(0).toUpperCase() + field.slice(1)} Status`;
        new bootstrap.Modal(document.getElementById('statusModal')).show();
    }

    // Calculate days between dates
    document.getElementById('start_date').addEventListener('change', calculateDays);
    document.getElementById('end_date').addEventListener('change', calculateDays);
    
    function calculateDays() {
        const startDate = new Date(document.getElementById('start_date').value);
        const endDate = new Date(document.getElementById('end_date').value);
        
        if (startDate && endDate && startDate <= endDate) {
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            document.getElementById('total_days').value = diffDays;
        }
    }
</script>
</body>
</html>