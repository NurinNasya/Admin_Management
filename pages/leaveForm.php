<?php
session_start();
require_once '../db.php';
require_once '../Model/LeaveForm.php';

// Initialize variables
$staff_id = $_SESSION['staff_id'] ?? 1; // Default or get from session
$current_page = basename($_SERVER['PHP_SELF']);
$user_id = $_SESSION['user_id'] ?? 1;
$user_name = $_SESSION['user_name'] ?? 'User';

// Initialize LeaveForm model
$leaveForm = new LeaveForm($conn);

// Get all leaves for current staff
$leaves = $leaveForm->getAllLeaves($staff_id);

// Handle messages
$message = '';
$error = '';
if (isset($_GET['msg'])) {
    $message = urldecode($_GET['msg']);
}
if (isset($_GET['error'])) {
    $error = urldecode($_GET['error']);
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
  <!-- Custom CSS untuk notifikasi -->
  <style>
    /* Style untuk notifikasi */
    .alert {
    width: 100%;
    max-width: 100%;
    font-size: 12px;
}
    .alert-text {
     white-space: normal;
    word-wrap: break-word;
}
    /* tak boh beri dok tertutup  sidebar */
    .sidenav {
      z-index: 10;
    }
    
    /*  untuk ukuran font dasar */
    body {
      font-size: 16px;


      .card-header {
      background-color: #f8f9fa; /* Warna background untuk header card */
    }
    .alert {
      font-size: 14px !important; /* Pastikan saiz font konsisten */
    }
    }
</style>
</head>


<!-- ini bahagian notifikasi nak ubah butang -->
  <body class="g-sidenav-show bg-gray-100">
  
  <!-- Notifikasi baru (posisi di bawah header) -->

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
          <a class="nav-link <?php echo ($current_page == 'staff.php') ? 'active' : ''; ?>" href="../pages/staff.php">
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
            <!--<span class="nav-link-text ms-1">Sign In</span>-->
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
  </aside> <!--smpai sini-->>
    <main class="main-content position-relative border-radius-lg">
            
            <!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Settings</a></li>
                <li class="breadcrumb-item text-sm text-white active" aria-current="page">Work Shift</li>
            </ol>
            <h6 class="font-weight-bolder text-white mb-0">Leave Application</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                <!-- Ruang kosong untuk nak tambah apa2 elemen tambahan di masa depan -->
            </div>
            <ul class="navbar-nav justify-content-end">
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
               <li class="nav-item pe-2 d-flex align-items-center">
    <a href="javascript:;" class="nav-link text-white p-0">
        <i class="fa fa-bell cursor-pointer"></i>
    </a>
</li>
            </ul>
        </div>
    </div>
</nav>
            <!-- End Navbar -->

 <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
           <div class="card-header pb-0">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h5 class="mb-2">Leave Applications</h5>
      
      <!-- Notifikasi Alert (KIRI) -->
      <div class="d-flex flex-column w-100"> <!-- Ubah width ke 100% -->
        <?php if ($message): ?>
        <div class="alert alert-success alert-dismissible fade show mb-2" role="alert" style="font-size: 14px;">
          <span class="alert-text"><?= $message ?></span>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-2" role="alert" style="font-size: 14px;">
          <span class="alert-text"><?= $error ?></span>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
      </div>
      
      <!-- Text "Total leaves" -->
      <p class="text-dark mb-0" style="font-size: 14px; font-weight: 500;">Total leaves: <?= count($leaves) ?></p>
    </div>
    
    <div>
      <button class="btn btn-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#addLeaveModal">
        <i class="ni ni-fat-add"></i> Apply Leave
      </button>
    </div>
  </div>
</div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <!--kita masukkkan benda baru dekat sini sb nak kasi interface display testing2  -->
                <table class="table align-items-center mb-0 leave-table">
                <thead>
                <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Leave Type</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Start Date</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">End Date</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Days</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Application Date</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($leaves as $leave): ?>
                <tr>
        <td class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($leave['leave_type']) ?></td>
        <td class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($leave['start_date']) ?></td>
        <td class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($leave['end_date']) ?></td>
        <td class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($leave['total_days']) ?></td>
        <td class="text-xs font-weight-bold mb-0"><?= htmlspecialchars($leave['application_date']) ?></td>
        <td class="text-xs font-weight-bold mb-0">
        <span class="badge badge-sm bg-gradient-<?= 
          $leave['status'] === 'Approved' ? 'success' : 
          ($leave['status'] === 'Rejected' ? 'danger' : 'warning') 
        ?>">
          <?= htmlspecialchars($leave['status']) ?>
        </span>
      </td>
      <td class="text-xs font-weight-bold mb-0">
        <a href="#" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#editLeaveModal" 
           onclick="editLeave(<?= $leave['id'] ?>)">Edit</a>
        <a href="../Controller/LeaveFormController.php?action=delete&id=<?= $leave['id'] ?>" 
           class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
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
  </main>

  <!-- Add Leave Modal -->
  <div class="modal fade" id="addLeaveModal" tabindex="-1" aria-labelledby="addLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="addLeaveModalLabel">Apply New Leave</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="../Controller/LeaveFormController.php?action=add" method="post" enctype="multipart/form-data">
            <input type="hidden" name="staff_id" value="<?= $staff_id ?>">
            <input type="hidden" name="created_by" value="<?= $_SESSION['user_id'] ?? 1 ?>">

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="leave_type" class="form-label">Leave Type</label>
                <select class="form-select" id="leave_type" name="leave_type" required>
                  <option value="" disabled selected>Select leave type</option>
                  <option value="Medical Leave">Medical Leave</option>
                  <option value="Annual Leave">Annual Leave</option>
                  <option value="Unpaid Leave">Unpaid Leave</option>
                  <option value="Maternity Leave">Maternity Leave</option>
                  <option value="Paternity Leave">Paternity Leave</option>
                  <option value="Compassionate Leave">Compassionate Leave</option>
                </select>
              </div>
              <div class="col-md-6">
                <label for="application_date" class="form-label">Application Date</label>
                <input type="date" class="form-control" id="application_date" name="application_date" value="<?= date('Y-m-d') ?>" required>
              </div>
            </div>

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
                <label for="total_days" class="form-label">Total Days</label>
                <input type="number" class="form-control" id="total_days" name="total_days" step="0.5" required>
              </div>
            </div>

            <div class="row mb-3" id="document_upload_container" style="display: none;">
              <div class="col-md-12">
                <label for="leave_document" class="form-label">Medical Certificate</label>
                <input type="file" class="form-control" id="leave_document" name="leave_document" accept=".pdf,.jpg,.jpeg,.png">
              </div>
            </div>

            <div class="mb-3">
              <label for="reason" class="form-label">Reason</label>
              <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-primary">Submit Leave</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

   <!-- ni butang edit model -->
<div class="modal fade" id="editLeaveModal" tabindex="-1" aria-labelledby="editLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="editLeaveModalLabel">Edit Leave Application</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../Controller/LeaveFormController.php?action=edit" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="edit_id">
                    <input type="hidden" name="existing_document" id="existing_document">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_leave_type" class="form-label">Leave Type</label>
                            <select class="form-select" id="edit_leave_type" name="leave_type" required>
                                <option value="" disabled>Select leave type</option>
                                <option value="Medical Leave">Medical Leave</option>
                                <option value="Annual Leave">Annual Leave</option>
                                <option value="Unpaid Leave">Unpaid Leave</option>
                                <option value="Maternity Leave">Maternity Leave</option>
                                <option value="Paternity Leave">Paternity Leave</option>
                                <option value="Compassionate Leave">Compassionate Leave</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_application_date" class="form-label">Application Date</label>
                            <input type="date" class="form-control" id="edit_application_date" name="application_date" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="edit_start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="edit_end_date" name="end_date" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="edit_total_days" class="form-label">Total Days</label>
                            <input type="number" class="form-control" id="edit_total_days" name="total_days" step="0.5" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Current Document</label>
                            <div id="current_document"></div>
                        </div>
                    </div>

                    <div class="row mb-3" id="edit_document_upload_container" style="display: none;">
                        <div class="col-md-12">
                            <label for="edit_leave_document" class="form-label">Upload New Document</label>
                            <input type="file" class="form-control" id="edit_leave_document" name="leave_document" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_reason" class="form-label">Reason</label>
                        <textarea class="form-control" id="edit_reason" name="reason" rows="3" required></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Update Leave</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- mesej nok hilang notifikasi selepas 3 saat  -->
<script>
  setTimeout(() => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
      alert.remove();
    });
  }, 3000);
</script>

    <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
 <script>
  // Show document upload only for Medical Leave
  document.getElementById('leave_type').addEventListener('change', function() {
    const docContainer = document.getElementById('document_upload_container');
    docContainer.style.display = this.value === 'Medical Leave' ? 'block' : 'none';
    
    document.getElementById('leave_document').required = this.value === 'Medical Leave';
  });

  document.getElementById('start_date').addEventListener('change', calculateDays);
  document.getElementById('end_date').addEventListener('change', calculateDays);

  function calculateDays() {
    const startDate = new Date(document.getElementById('start_date').value);
    const endDate = new Date(document.getElementById('end_date').value);
    
    if (startDate && endDate && endDate >= startDate) {
      const diffTime = Math.abs(endDate - startDate);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
      document.getElementById('total_days').value = diffDays;
    }
  }

  // ini adalah function nak edit leave form tuuu
  function editLeave(id) {
    // Fetch leave details via AJAX
    fetch(`../Controller/LeaveFormController.php?action=getLeave&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                // Populate the form with the retrieved data
                document.getElementById('edit_id').value = data.id;
                document.getElementById('edit_leave_type').value = data.leave_type;
                document.getElementById('edit_start_date').value = data.start_date;
                document.getElementById('edit_end_date').value = data.end_date;
                document.getElementById('edit_total_days').value = data.total_days;
                document.getElementById('edit_application_date').value = data.application_date;
                document.getElementById('edit_reason').value = data.reason;
                
                // Handle document display
                if (data.leave_document) {
                    document.getElementById('existing_document').value = data.leave_document;
                    document.getElementById('current_document').innerHTML = 
                        `<a href="../uploads/leave_documents/${data.leave_document}" target="_blank">View Current Document</a>`;
                } else {
                    document.getElementById('current_document').innerHTML = 'No document uploaded';
                }
                
                // Show document upload if medical leave
                const docContainer = document.getElementById('edit_document_upload_container');
                docContainer.style.display = data.leave_type === 'Medical Leave' ? 'block' : 'none';
                
                // Add event listener for leave type change
                document.getElementById('edit_leave_type').addEventListener('change', function() {
                    docContainer.style.display = this.value === 'Medical Leave' ? 'block' : 'none';
                });
                
                // Add event listeners for date changes to calculate days
                document.getElementById('edit_start_date').addEventListener('change', editCalculateDays);
                document.getElementById('edit_end_date').addEventListener('change', editCalculateDays);
            }
        })
        .catch(error => console.error('Error:', error));
  }

  function editCalculateDays() {
    const startDate = new Date(document.getElementById('edit_start_date').value);
    const endDate = new Date(document.getElementById('edit_end_date').value);
    
    if (startDate && endDate && endDate >= startDate) {
        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        document.getElementById('edit_total_days').value = diffDays;
    }
  }
</script>
</body>
</html>