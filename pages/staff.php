<?php
session_start();
require_once '../db.php';
require_once '../Model/Staff.php';

$staffModel = new Staff();
// WITH THIS FIXED VERSION:
// FIX: Simplified cache control - refresh only when needed
if (!isset($_SESSION['cached_staff_data']) || isset($_SESSION['staff_data_refreshed'])) {
    // Clear refresh flag if it exists
    if (isset($_SESSION['staff_data_refreshed'])) {
        unset($_SESSION['staff_data_refreshed']);
    }
    
    // Always get fresh data when cache is invalid
    $_SESSION['cached_staff_data'] = $staffModel->getAllStaff();

// // Force fresh data load if we just updated a staff member
// $forceRefresh = isset($_SESSION['staff_data_refreshed']);
// if ($forceRefresh) {
//     unset($_SESSION['staff_data_refreshed']);
//     // Clear any cached staff data
//     if (isset($_SESSION['cached_staff_data'])) {
//         unset($_SESSION['cached_staff_data']);
//     }
// }

// // Get staff data using your existing method
// if (!isset($_SESSION['cached_staff_data']) || $forceRefresh) {
//     $_SESSION['cached_staff_data'] = $staffModel->getAllStaff(); // Using your existing method
}
$allStaff = $_SESSION['cached_staff_data'];

// Handle messages
$successMsg = $_SESSION['success_message'] ?? '';
$errorMsg = $_SESSION['error_message'] ?? '';

// Clear messages after displaying
unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

// require_once '../db.php';
// require_once '../Model/Staff.php';

// // Initialize Staff Model
// $staffModel = new Staff();

// try {
//     // Fetch all staff with department and company codes
//     $allStaff = $staffModel->getAllStaff();
    
//     // Check for success/error messages
//     $successMsg = $_SESSION['success'] ?? '';
//     $errorMsg = $_SESSION['error'] ?? '';
    
//     // Clear the messages after displaying them
//     unset($_SESSION['success']);
//     unset($_SESSION['error']);
    
// } catch (Exception $e) {
//     $errorMsg = "Error loading staff data: " . $e->getMessage();
// }
?>


<?php include('../includes/header-navbar.php'); ?>

<div class="container-fluid py-4">
      <?php if (!empty($successMsg)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($successMsg) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>
      
      <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($errorMsg) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center" style="margin-bottom: 5px;">
              <h4 style="margin-bottom: 0;">Employee List</h4>
              <a href="staff_info.php" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Add Employee
              </a>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table id="employeeTable" class="table align-items-center mb-0 display" style="width:100%">
                  <thead class="thead-dark">
                    <tr>
                      <th>ID</th>
                      <th>NAME</th>
                      <th>DEPARTMENT</th>
                      <th>COMPANY</th>
                      <th>POSITION</th>
                      <th>PHONE</th>
                      <th>ACTION</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (!empty($allStaff)): ?>
                      <?php foreach ($allStaff as $staff): ?>
                        <tr>
                          <td><?= htmlspecialchars($staff['id'] ?? 'N/A') ?></td>
                          <td><?= htmlspecialchars($staff['name'] ?? 'N/A') ?></td>
                          <td><?= htmlspecialchars($staff['department_code'] ?? 'N/A') ?></td>
                          <td><?= htmlspecialchars($staff['company_code'] ?? 'N/A') ?></td>
                          <td><?= htmlspecialchars($staff['role_name'] ?? $staff['roles'] ?? 'N/A') ?></td>
                          <td><?= htmlspecialchars($staff['phone'] ?? 'N/A') ?></td>
                          <td class="align-middle">
                            <div class="dropdown">
                              <button class="btn btn-sm btn-icon-only text-light" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end px-2 py-3">
                                <li>
                                  <a class="dropdown-item border-radius-md" href="edit_staff.php?id=<?= $staff['id'] ?>">
                                    <i class="fas fa-pen me-2"></i> Edit
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item border-radius-md" href="medicalClaim.php?staff_id=<?= $staff['id'] ?>">
                                  <!-- <a class="dropdown-item border-radius-md" href="medClaim.php?id=<?= $staff['id'] ?>"> -->
                                    <i class="fas fa-file-medical me-2"></i> Claim
                                  </a>
                                </li>
                                <li>
                                  <a class="dropdown-item border-radius-md" href="leave.php?id=<?= $staff['id'] ?>">
                                    <i class="fas fa-file-medical me-2"></i> Leave
                                  </a>
                                </li>
                                <li>
                                  <!-- In your table row actions, replace the delete form with this: -->
                                  <form method="POST" action="../Controller/staffController.php" 
                                        onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                      <input type="hidden" name="staff_id" value="<?= $staff['id'] ?>">
                                      <input type="hidden" name="delete_staff" value="1">
                                      <button type="submit" class="dropdown-item border-radius-md text-danger">
                                          <i class="fas fa-trash me-2"></i> Delete
                                      </button>
                                  </form>
                                </li>
                              </ul>
                            </div>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else: ?>
                      <tr>
                        <td colspan="9" class="text-center">No Staff Records Found</td>
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
  </main>

  <!-- Required Scripts -->
  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- DataTables JS -->
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
  
  <script>
document.getElementById('staffEditForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Disable button during submission
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
    
    fetch(form.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
        } else {
            return response.text().then(text => {
                throw new Error(text || 'Update failed');
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during update');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save me-2"></i>Update Staff';
    });
});
// Auto-dismiss alerts after 5 seconds
      setTimeout(function() {
        $('.alert').alert('close');
      }, 5000);
</script>

  <script>
// Force clear cache on page load
window.onload = function() {
    if (performance.navigation.type === 1) {
        // Page was reloaded
        if (typeof(Storage) !== "undefined") {
            sessionStorage.removeItem("staffDataLoaded");
        }
    }
};

// Initialize DataTable with AJAX if you want real-time updates
$(document).ready(function() {
    var employeeTable = $('#employeeTable').DataTable({
        responsive: true,
        destroy: true, // Allows reinitialization
        // ... your other DataTable options
    });

    // Optional: Add this if you want to use AJAX for deletion
    $(document).on('submit', 'form[action*="staffController.php"]', function(e) {
        e.preventDefault();
        var form = this;
        
        if (confirm('Are you sure you want to delete this staff member?')) {
            $.ajax({
                url: form.action,
                method: 'POST',
                data: $(form).serialize(),
                success: function() {
                    // Reload the table after successful deletion
                    employeeTable.ajax.reload(null, false);
                    // Show success message
                    location.reload(); // Full refresh to ensure consistency
                },
                error: function() {
                    alert('Error deleting staff');
                }
            });
        }
    });
});
</script>
</body>
</html>