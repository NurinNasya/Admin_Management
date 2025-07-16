<?php 
/*require_once '../db.php';
require_once '../Model/leave.php';
require_once '../Controller/leaveController.php';

session_start();

// Initialize the controller
$leaveController = new LeaveController();

// Get leave requests (default to pending)
$status = $_GET['status'] ?? 'pending';
$leaveRequests = $leaveController->getLeaveRequests($status);

// Check for messages
$message = $_SESSION['message'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);*/
?>
<?php
require_once '../db.php';
require_once '../Model/leave.php';
require_once '../Controller/leaveController.php';

// Initialize Staff Model
$leaveModel = new Leave();

try {
    // Fetch all staff with department and company codes
    $allLeaves = $leaveModel->getAllLeaves();
    
    // Check for success/error messages
    $successMsg = $_SESSION['success'] ?? '';
    $errorMsg = $_SESSION['error'] ?? '';
    
    // Clear the messages after displaying them
    unset($_SESSION['success']);
    unset($_SESSION['error']);
    
} catch (Exception $e) {
    $errorMsg = "Error loading leave data: " . $e->getMessage();
}
?>

<?php include('../includes/header-navbar.php'); ?>

<!-- Main content -->
    <div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5>Leave > Approval Panel</h5>
        </div>

        <div class="card-body">
          <h5 class="card-title">Leave Requests</h5>

          <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
              <tr>
                <th>Name</th>
                <th>Department</th>
                <th>Company</th>
                <th>Application Date</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Days</th>
                <th>Reason</th>
                <th>Document</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($leaveRequests)): ?>
                <?php foreach ($leaveRequests as $request): ?>
                  <tr>
                    <td><?= htmlspecialchars($request['staff_name'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($request['departments_code'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($request['company_code'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($request['applied_date'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($request['start_date'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($request['end_date'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($request['total_days'] ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($request['reason'] ?? 'N/A') ?></td>
                    <td>
                      <?php if (!empty($request['attachment'])): ?>
                        <a href="../uploads/<?= htmlspecialchars($request['attachment']) ?>" target="_blank">View</a>
                      <?php else: ?>
                        <span class="text-muted">No document</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?php if (($request['status'] ?? '') === 'pending'): ?>
                        <form method="post" action="../controller/handle_leave.php">
                          <input type="hidden" name="leave_id" value="<?= $request['id'] ?>">
                          <button type="submit" name="action" value="approve" class="btn btn-sm btn-success">Approve</button>
                          <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
                        </form>
                      <?php else: ?>
                        <span class="text-muted">Processed</span>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="10" class="text-center text-muted">No leave requests found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>