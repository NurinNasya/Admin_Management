<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../Model/Quota.php';
require_once __DIR__ . '/../Model/medClaim.php';

$csrf = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf;

$quota = new Quota($conn);
$medClaim = new MedClaim($conn);

$allStaffQuotas = $quota->getAllQuotaAllocations();

// Check for session messages
$successMessage = $_SESSION['success'] ?? null;
$errorMessage = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);
?>
<?php include('../includes/header-navbar.php'); ?>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12 mx-auto">
                

                <!-- Session-based messages
                <?php if ($sessionSuccess): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($sessionSuccess) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif ($sessionError): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($sessionError) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?> -->

                <!-- Original GET parameter messages -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Operation completed successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php elseif (isset($_GET['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($_GET['error']) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Add Quota Button (now triggers modal) -->

<!-- Add Quota Modal -->
<div class="modal fade" id="addQuotaModal" tabindex="-1" aria-labelledby="addQuotaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="../Controller/quotaController.php">
                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                
                <div class="modal-header bg-primary text-white">
    <h5 class="modal-title text-white" id="addQuotaModalLabel">Add Quota for Staff</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
                
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Staff dropdown -->
                        <div class="col-md-12">
                            <label for="staff_id" class="form-label">Staff Member</label>
                            <select class="form-select" id="staff_id" name="staff_id" required>
                                <option value="">Select Staff</option>
                                <?php
                                    $staffQuery = "SELECT id, name FROM staff ORDER BY name";
                                    $staffResult = mysqli_query($conn, $staffQuery);
                                    while ($staff = mysqli_fetch_assoc($staffResult)):
                                        $quotaInfo = $quota->getStaffQuota($staff['id']);
                                ?>
                                    <option value="<?= $staff['id'] ?>" 
                                        data-current-quota="<?= $quotaInfo ? $quotaInfo['additional_quota'] : 0 ?>"
                                        data-current-notes="<?= $quotaInfo ? htmlspecialchars($quotaInfo['notes']) : '' ?>">
                                        <?= htmlspecialchars($staff['name']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <!-- Quota amount -->
                        <div class="col-md-12">
                            <label for="additional_quota" class="form-label">Additional Quota (RM)</label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="number" step="0.01" min="0" class="form-control" 
                                       id="additional_quota" name="additional_quota" required>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="col-12">
                            <label for="notes" class="form-label">Notes / Reason</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_quota" class="btn btn-primary">
                        <i class="bi bi-save"></i> Add Quota
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

                <!-- CURRENT QUOTAS TABLE -->
<div class="card">
    <div class="card-header bg-light text-dark d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="bi bi-list-ul fs-4 me-2"></i>
            <span class="fs-5 fw-bold">Staff Quota List</span>
        </div>
        <div>
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addQuotaModal">
                <i class="bi bi-plus-circle"></i> Add Quota
            </button>
        </div>
    </div>

    
                    <div class="card">
                        <div class="table-responsive">
                            <!--<table class="table table-striped table-hover mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Staff Name</th>
                                        <th>Department</th>
                                        <th>Additional Quota (RM)</th>
                                        <th>Last Updated</th>
                                        <th>Notes</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allStaffQuotas as $q): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($q['staff_name']) ?></td>
                                        <td><?= htmlspecialchars($q['department_name'] ?? 'N/A') ?></td>
                                        <td>RM <?= number_format($q['additional_quota'], 2) ?></td>
                                        <td><?= date('d M Y H:i', strtotime($q['updated_at'])) ?></td>
                                        <td><?= nl2br(htmlspecialchars($q['notes'])) ?></td>
                                        <td> -->
                                            <!-- Edit Button -->
                                            <!-- <button type="button" class="btn btn-sm btn-warning edit-quota-btn" 
                                                data-quota-id="<?= $q['id'] ?>"
                                                data-staff-name="<?= htmlspecialchars($q['staff_name']) ?>"
                                                data-current-quota="<?= $q['additional_quota'] ?>"
                                                data-current-notes="<?= htmlspecialchars($q['notes']) ?>">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button> -->
                                            
                                            <!-- Delete Button -->
                                            <!-- <form method="POST" action="../Controller/quotaController.php" style="display: inline-block;">
                                                <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                                                <input type="hidden" name="quota_id" value="<?= $q['id'] ?>">
                                                <button type="submit" name="delete_quota" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this quota?');">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>

                                    <?php if (empty($allStaffQuotas)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No quota adjustments have been made yet.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table> -->
                            <!-- Modify the table structure to show all entries -->
<table class="table table-striped table-hover mb-0">
  <thead class="table-dark">
        <tr>
            <th>Staff Name</th>
            <th>Department</th>
            <th>Additional Quota (RM)</th>
            <th>Date Added</th>
            <th>Last Updated</th>
            <th>Notes</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($allStaffQuotas as $q): ?>
        <tr>
            <td><?= htmlspecialchars($q['staff_name']) ?></td>
            <td><?= htmlspecialchars($q['department_name'] ?? 'N/A') ?></td>
            <td>RM <?= number_format($q['additional_quota'], 2) ?></td>
            <td><?= date('d M Y H:i', strtotime($q['created_at'])) ?></td>
            <td><?= date('d M Y H:i', strtotime($q['updated_at'])) ?></td>
            <td><?= nl2br(htmlspecialchars($q['notes'])) ?></td>
            <td>
                <!-- Edit Button -->
                <button type="button" class="btn btn-sm btn-warning edit-quota-btn" 
                    data-quota-id="<?= $q['id'] ?>"
                    data-staff-name="<?= htmlspecialchars($q['staff_name']) ?>"
                    data-current-quota="<?= $q['additional_quota'] ?>"
                    data-current-notes="<?= htmlspecialchars($q['notes']) ?>">
                    <i class="bi bi-pencil"></i> Edit
                </button>
                
                <!-- Delete Button -->
                <form method="POST" action="../Controller/quotaController.php" style="display: inline-block;">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <input type="hidden" name="quota_id" value="<?= $q['id'] ?>">
                    <button type="submit" name="delete_quota" class="btn btn-sm btn-danger" 
                        onclick="return confirm('Are you sure you want to delete this quota entry?');">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
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

    <!-- Edit Quota Modal -->
    <div class="modal fade" id="editQuotaModal" tabindex="-1" aria-labelledby="editQuotaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="../Controller/quotaController.php" id="editQuotaForm">
                    <input type="hidden" name="csrf_token" value="<?= $csrf ?>">
                    <input type="hidden" name="quota_id" id="edit_quota_id">
                    <input type="hidden" name="update_quota" value="1"> <!-- Added this line -->
                        
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editQuotaModalLabel">Edit Quota</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Staff Member</label>
                            <input type="text" class="form-control" id="edit_staff_name" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_additional_quota" class="form-label">Additional Quota (RM)</label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="number" step="0.01" min="0" class="form-control" 
                                       id="edit_additional_quota" name="additional_quota" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="edit_notes" name="notes" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_quota" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize modal
        const editModal = new bootstrap.Modal('#editQuotaModal');
        
        // Handle edit button clicks
        document.querySelectorAll('.edit-quota-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit_quota_id').value = this.dataset.quotaId;
                document.getElementById('edit_staff_name').value = this.dataset.staffName;
                document.getElementById('edit_additional_quota').value = this.dataset.currentQuota;
                document.getElementById('edit_notes').value = this.dataset.currentNotes;
                
                editModal.show();
            });
        });

        // Handle staff selection change to populate existing quota
        document.getElementById('staff_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            document.getElementById('additional_quota').value = selectedOption.getAttribute('data-current-quota') || 0;
            document.getElementById('notes').value = selectedOption.getAttribute('data-current-notes') || '';
        });
    });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle form submission
    const editForm = document.getElementById('editQuotaForm');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            // The form will submit normally to quotaController.php
            // The controller will process it and redirect back to adminQuotaManagement.php
            // No need for preventDefault() or AJAX here
        });
    }

    // Rest of your existing JavaScript...
});
</script>
</body>
</html>