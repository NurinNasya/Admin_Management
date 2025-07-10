<?php
session_start();

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Initialize default values
$editMode = false;
$editData = null;

require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../Model/LeaveType.php';
require_once __DIR__ . '/../Controller/LeaveTypeController.php';

use App\Model\LeaveType;
use App\Controller\LeaveTypeController;

// Initialize model and controller
$leaveTypeModel = new LeaveType($conn);
$controller = new LeaveTypeController($leaveTypeModel, isset($_POST['ajax']));

// Handle edit mode
if (isset($_GET['edit'])) {
    $editMode = true;
    $editData = $controller->getLeaveType($_GET['edit']);
    
    if ($editData === null) {
        $_SESSION['error_message'] = 'Leave type not found';
        header('Location: adminmanageleavetype.php?r=' . time());
        exit;
    }
}



// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax'])) {
    header('Content-Type: application/json');
    
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
        exit;
    }
    
    echo json_encode($controller->handleRequest());
    exit;
}

// Handle regular form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error_message'] = 'Invalid CSRF token';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?r=' . time());
        exit;
    }
    
    $result = $controller->handleRequest();
    
    if ($result['success']) {
        $_SESSION['success_message'] = $result['message'];
    } else {
        $_SESSION['error_message'] = $result['message'];
        $_SESSION['form_errors'] = $result['errors'] ?? [];
    }
    
    header('Location: ' . $_SERVER['PHP_SELF'] . '?r=' . time());
    exit;
}

// Get data for display
$result = $controller->getAllLeaveTypes();
$leaveTypes = $result['data'] ?? [];
$totalCount = $result['total'] ?? 0;
$activeLeaveTypes = $controller->getActiveLeaveTypes();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Leave Management System | Admin
  </title>
  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
  <style>
    .badge.bg-gradient-success {
      background: linear-gradient(310deg, #2dce89, #2dcecc);
    }

    .badge.bg-gradient-secondary {
      background: linear-gradient(310deg, #627594, #a8b8d8);
    }

    .alert-auto-dismiss {
      animation: fadeOut 0.5s ease-in-out 4.5s forwards;
    }

    @keyframes fadeOut {
      to {
        opacity: 0;
        transform: translateY(-10px);
      }
    }
    .is-invalid {
      border-color: #fd5c70 !important;
    }
    .invalid-feedback {
      color: #fd5c70;
      font-size: 0.8rem;
    }
    .cursor-pointer {
      cursor: pointer;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="#">
                <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
                <span class="ms-1 font-weight-bold">Agro Dashboard</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../pages/dashboard.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="../pages/leave_types.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Leave Management</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../pages/staff.php">
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
                            <li class="nav-item">
                                <a class="nav-link active" href="../pages/leave_types.php">
                                    <span class="sidenav-normal">Leave Types</span>
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
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Profile</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <main class="main-content position-relative border-radius-lg">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Settings</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Leave Types</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Leave Management</h6>
                </nav>
            </div>
        </nav>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <!-- Dynamic Success/Error Messages -->
            <div id="messageContainer">
                <?php if (isset($_SESSION['success_message'])): ?>
                      <div class="alert alert-success alert-dismissible fade show alert-auto-dismiss">
                          <i class="fas fa-check-circle me-2"></i>
                          <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>
                      <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                      <div class="alert alert-danger alert-dismissible fade show alert-auto-dismiss">
                          <i class="fas fa-exclamation-circle me-2"></i>
                          <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                      </div>
                      <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
            </div>

            <div class="row">
                <!-- Add/Edit Form -->
                <div class="col-md-5">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6><?php echo $editMode ? 'Edit Leave Type' : 'Add New Leave Type'; ?></h6>
                        </div>
                        <div class="card-body">
                            <form id="leaveTypeForm" method="POST" action="">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="ajax" value="1">
                                <input type="hidden" name="action" value="<?php echo $editMode ? 'edit' : 'create'; ?>">
                                <?php if ($editMode && $editData): ?>
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($editData['id']); ?>">
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label class="form-label">Leave Type Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="type_name" required
                                        value="<?php echo $editData ? htmlspecialchars($editData['type_name']) : ''; ?>"
                                        placeholder="Enter leave type name">
                                    <div class="invalid-feedback" id="type_name_error"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3"
                                        placeholder="Enter description"><?php echo $editData ? htmlspecialchars($editData['description']) : ''; ?></textarea>
                                    <div class="invalid-feedback" id="description_error"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Maximum Days <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="max_days" min="1" max="365" required
                                        value="<?php echo $editData ? $editData['max_days'] : ''; ?>" placeholder="Enter maximum days">
                                    <div class="invalid-feedback" id="max_days_error"></div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="is_active" required>
                                        <option value="1" <?php echo (!$editMode || ($editData && $editData['is_active'] == 1)) ? 'selected' : ''; ?>>Active</option>
                                        <option value="0" <?php echo ($editMode && $editData && $editData['is_active'] == 0) ? 'selected' : ''; ?>>Inactive</option>
                                    </select>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm" id="submitBtn">
                                        <i class="fas fa-save me-1"></i>
                                        <span class="btn-text"><?php echo $editMode ? 'Update' : 'Create'; ?></span>
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                    <?php if ($editMode): ?>
                                        <a href="adminmanageleavetype.php" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-times me-1"></i>
                                            Cancel
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Bulk Leave Allocation Form -->
                    <div class="card mt-4">
                        <div class="card-header pb-0">
                            <h6>Bulk Leave Allocation</h6>
                        </div>
                        <div class="card-body">
                            <form id="bulkAllocationForm" method="POST" action="">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                <input type="hidden" name="ajax" value="1">
                                <input type="hidden" name="action" value="allocate_leave">

                                <div class="mb-3">
                                    <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                                    <select class="form-select" name="leave_type" required>
                                        <option value="">Select Leave Type</option>
                                        <?php foreach ($activeLeaveTypes as $type): ?>
                                              <option value="<?php echo $type['id']; ?>">
                                                  <?php echo htmlspecialchars($type['type_name']); ?>
                                              </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="start_date" required
                                        value="<?php echo date('Y-01-01'); ?>" min="<?php echo date('Y-01-01'); ?>"
                                        max="<?php echo date('Y-12-31'); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="end_date" required
                                        value="<?php echo date('Y-12-31'); ?>" min="<?php echo date('Y-01-01'); ?>"
                                        max="<?php echo date('Y-12-31'); ?>">
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm" id="allocateBtn">
                                    <i class="fas fa-users me-1"></i>
                                    <span class="btn-text">Allocate to All Users</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Leave Types List -->
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6>Leave Types List</h6>
                                <span class="badge bg-primary">
                                    Total: <?php echo $totalCount; ?>
                                </span>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Leave Type</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Max Days</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($leaveTypes)): ?>
                                              <tr>
                                                  <td colspan="4" class="text-center py-4">
                                                      <div class="text-muted">
                                                          <i class="fas fa-inbox fa-2x mb-2"></i>
                                                          <p>No leave types found</p>
                                                      </div>
                                                  </td>
                                              </tr>
                                        <?php else: ?>
                                              <?php foreach ($leaveTypes as $type): ?>
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex px-2 py-1">
                                                                <div class="d-flex flex-column justify-content-center">
                                                                    <h6 class="mb-0 text-sm"><?php echo htmlspecialchars($type['type_name']); ?></h6>
                                                                    <p class="text-xs text-secondary mb-0">
                                                                        <?php echo htmlspecialchars($type['description'] ?: 'No description'); ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="text-secondary text-xs font-weight-bold">
                                                                <?php echo isset($type['max_days']) ? $type['max_days'] . ' days' : '-'; ?>
                                                            </span>
                                                        </td>
                                                        <td class="align-middle text-center text-sm">
                                                            <span class="badge badge-sm <?php echo $type['is_active'] ? 'bg-gradient-success' : 'bg-gradient-secondary'; ?>">
                                                                <?php echo $type['is_active'] ? 'Active' : 'Inactive'; ?>
                                                            </span>
                                                        </td>
                                                        <td class="align-middle text-center">
                                                            <a href="?edit=<?php echo $type['id']; ?>"
                                                            class="btn btn-link text-primary text-gradient px-3 mb-0">
                                                                <i class="fas fa-edit text-primary me-2"></i>Edit
                                                            </a>
                                                            <button type="button" class="btn btn-link text-danger text-gradient px-3 mb-0 delete-btn"
                                                                data-id="<?php echo $type['id']; ?>"
                                                                data-name="<?php echo htmlspecialchars($type['type_name']); ?>">
                                                                <i class="fas fa-trash text-danger me-2"></i>Delete
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
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the leave type "<span id="deleteTypeName"></span>"?</p>
                    <p class="text-warning small">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        This action cannot be undone.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form id="deleteForm" method="POST" action="" style="display: inline;">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="ajax" value="1">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="deleteId">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </form>
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
    <!-- Control Center for Soft Dashboard -->
    <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>

    <script>
        $(document).ready(function() {
            // Initialize variables
            let currentEditId = null;

            // Function to show loading state
            function showLoading(button, show) {
                const btnText = button.find('.btn-text');
                const spinner = button.find('.spinner-border');
                
                if (show) {
                    btnText.addClass('d-none');
                    spinner.removeClass('d-none');
                    button.prop('disabled', true);
                } else {
                    btnText.removeClass('d-none');
                    spinner.addClass('d-none');
                    button.prop('disabled', false);
                }
            }

            // Function to show messages
            function showMessage(type, message) {
                const messageContainer = $('#messageContainer');
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

                messageContainer.empty();
                messageContainer.append(`
                    <div class="alert ${alertClass} alert-dismissible fade show alert-auto-dismiss">
                        <i class="fas ${iconClass} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);

                // Auto-dismiss after 5 seconds
                setTimeout(() => {
                    messageContainer.find('.alert').alert('close');
                }, 5000);
            }

            // Function to attach all event handlers
            function attachEventHandlers() {
                // Delete button handlers
                $('.delete-btn').off('click').on('click', function() {
                    const id = $(this).data('id');
                    const name = $(this).data('name');

                    $('#deleteId').val(id);
                    $('#deleteTypeName').text(name);
                    new bootstrap.Modal($('#deleteModal')[0]).show();
                });
            }

            // Handle leave type form submission
            $('#leaveTypeForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const submitBtn = form.find('#submitBtn');
                
                showLoading(submitBtn, true);

                // Clear previous errors
                $('.invalid-feedback').text('');
                $('.is-invalid').removeClass('is-invalid');

                $.ajax({
                    url: window.location.href,
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log('Response:', response);
                        
                        if (response.success) {
                            showMessage('success', response.message);
                            
                            // Reset form for create mode
                            if (form.find('input[name="action"]').val() === 'create') {
                                form[0].reset();
                            }
                            
                            // Reload page after successful operation
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showMessage('danger', response.message);
                            
                            // Show field-specific errors
                            if (response.errors) {
                                Object.entries(response.errors).forEach(([field, message]) => {
                                    $(`[name="${field}"]`).addClass('is-invalid');
                                    $(`#${field}_error`).text(message);
                                });
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            error: error
                        });
                        
                        let errorMessage = 'An error occurred while processing your request';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                if (response.message) {
                                    errorMessage = response.message;
                                }
                            } catch (e) {
                                // Ignore parsing error, use default message
                            }
                        }
                        
                        showMessage('danger', errorMessage);
                    },
                    complete: function() {
                        showLoading(submitBtn, false);
                    }
                });
            });

            // Handle bulk allocation form submission
            $('#bulkAllocationForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const allocateBtn = form.find('#allocateBtn');

                if (!confirm('Are you sure you want to allocate this leave type to all users?')) {
                    return;
                }

                showLoading(allocateBtn, true);

                $.ajax({
                    url: window.location.href,
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log('Allocation Response:', response);
                        
                        if (response.success) {
                            showMessage('success', response.message);
                            form[0].reset();
                            
                            // Set default dates
                            form.find('input[name="start_date"]').val('<?php echo date('Y-01-01'); ?>');
                            form.find('input[name="end_date"]').val('<?php echo date('Y-12-31'); ?>');
                        } else {
                            showMessage('danger', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Allocation AJAX Error:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            error: error
                        });
                        
                        let errorMessage = 'An error occurred during allocation';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        showMessage('danger', errorMessage);
                    },
                    complete: function() {
                        showLoading(allocateBtn, false);
                    }
                });
            });

            // Handle delete form submission
            $('#deleteForm').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                const modal = $('#deleteModal');
                const deleteBtn = form.find('button[type="submit"]');

                showLoading(deleteBtn, true);

                $.ajax({
                    url: window.location.href,
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        console.log('Delete Response:', response);
                        
                        modal.modal('hide');
                        
                        if (response.success) {
                            showMessage('success', response.message);
                            
                            // Reload page after successful deletion
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                        } else {
                            showMessage('danger', response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete AJAX Error:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            error: error
                        });
                        
                        modal.modal('hide');
                        
                        let errorMessage = 'An error occurred while deleting';
                        
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        
                        showMessage('danger', errorMessage);
                    },
                    complete: function() {
                        showLoading(deleteBtn, false);
                    }
                });
            });

            // Date validation for bulk allocation
            const startDateInput = $('input[name="start_date"]');
            const endDateInput = $('input[name="end_date"]');

            startDateInput.on('change', function() {
                endDateInput.attr('min', $(this).val());
                if (new Date(endDateInput.val()) < new Date($(this).val())) {
                    endDateInput.val($(this).val());
                }
            });

            endDateInput.on('change', function() {
                startDateInput.attr('max', $(this).val());
            });

            // Form validation
            $('form').on('submit', function() {
                const form = $(this);
                let isValid = true;

                // Check required fields
                form.find('input[required], select[required], textarea[required]').each(function() {
                    const field = $(this);
                    if (!field.val().trim()) {
                        field.addClass('is-invalid');
                        isValid = false;
                    } else {
                        field.removeClass('is-invalid');
                    }
                });

                // Check number fields
                form.find('input[type="number"]').each(function() {
                    const field = $(this);
                    const value = parseInt(field.val());
                    const min = parseInt(field.attr('min'));
                    const max = parseInt(field.attr('max'));

                    if (value < min || value > max) {
                        field.addClass('is-invalid');
                        isValid = false;
                    } else {
                        field.removeClass('is-invalid');
                    }
                });

                return isValid;
            });

            // Clear validation errors on input
            $('input, select, textarea').on('input change', function() {
                $(this).removeClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('');
            });

            // Initial load
            attachEventHandlers();
        });
    </script>
</body>
</html>