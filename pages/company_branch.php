<?php
require_once '../Model/Branch.php';
require_once '../Model/Comp.php';

$branchModel = new Branch();
$companyModel = new Company();

// Get all branches with company details
try {
    $branches = $branchModel->getAllBranchesWithCompany();
} catch (Exception $e) {
    $error_message = "Error fetching branches: " . $e->getMessage();
    error_log($error_message);
    $branches = [];
}

// Get all companies for dropdown
try {
    $companies = $companyModel->getAllCompanies();
} catch (Exception $e) {
    $companies = [];
    error_log("Error fetching companies: " . $e->getMessage());
}
?>

<?php include('../includes/header-navbar.php'); ?>

<div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5>Branch Management</h5>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBranchModal">Add Branch</button>
                    </div>
                    
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success alert-dismissible fade show m-3">
                            <?= $_SESSION['success_message'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show m-3">
                            <?= $_SESSION['error_message'] ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Company</th>
                                        <th>Branch Code</th>
                                        <th>Branch Name</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($branches)): ?>
                                        <?php foreach ($branches as $index => $branch): ?>
                                            <?php 
                                                $statusBadge = $branch['status']
                                                    ? '<span class="badge border border-success text-success px-3 py-2">Active</span>'
                                                    : '<span class="badge border border-danger text-danger px-3 py-2">Inactive</span>';
                                            ?>
                                            <tr>
                                                <td><?= $index + 1 ?></td>
                                                <td><?= htmlspecialchars($branch['company_name']) ?></td>
                                                <td><?= htmlspecialchars($branch['branch_code']) ?></td>
                                                <td><?= htmlspecialchars($branch['branch_name']) ?></td>
                                                <td>
                                                    <?php if ($branch['latitude'] && $branch['longitude']): ?>
                                                        <a href="https://maps.google.com/?q=<?= $branch['latitude'] ?>,<?= $branch['longitude'] ?>" target="_blank" class="text-primary">
                                                            <i class="fas fa-map-marker-alt me-1"></i> View Map
                                                        </a>
                                                    <?php else: ?>
                                                        Not specified
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= $statusBadge ?></td>
                                                <td>
    <div class="d-flex">
        <a href="#" class="text-primary me-3 edit-branch-btn"
            data-id="<?= $branch['id'] ?>"
            data-company-id="<?= $branch['company_id'] ?>"
            data-branch-code="<?= htmlspecialchars($branch['branch_code']) ?>"
            data-branch-name="<?= htmlspecialchars($branch['branch_name']) ?>"
            data-latitude="<?= $branch['latitude'] ?>"
            data-longitude="<?= $branch['longitude'] ?>"
            data-status="<?= $branch['status'] ?>"
            data-bs-toggle="modal"
            data-bs-target="#editBranchModal"
            title="Edit">
            <i class="bi bi-pencil-square fs-4"></i>
        </a>
        <a href="../Controller/branchController.php?delete_id=<?= $branch['id'] ?>" 
            class="text-danger" 
            onclick="return confirm('Are you sure you want to delete this branch?');"
            title="Delete">
            <i class="bi bi-trash-fill fs-4"></i>
        </a>
    </div>
</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No branches found.</td>
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

    <!-- Add Branch Modal -->
    <div class="modal fade" id="addBranchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../Controller/branchController.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <div class="modal-body">
                        <div class="mb-3">
                            <div class="form-group">
                                <label class="form-control-label">Company <span class="text-danger">*</span></label>
                                <select class="form-control" name="company_id" required>
                                    <option value="">-- Select --</option>
                                    <?php foreach ($companies as $comp): ?>
                                    <option value="<?= $comp['id'] ?>"><?= htmlspecialchars($comp['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Branch Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="branch_code" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="branch_name" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="number" step="0.000001" class="form-control" name="latitude">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="number" step="0.000001" class="form-control" name="longitude">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_branch" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Branch Modal -->
    <div class="modal fade" id="editBranchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../Controller/branchController.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Company <span class="text-danger">*</span></label>
                            <select class="form-select" name="company_id" id="edit_company_id" required>
                                <option value="">Select Company</option>
                                <?php foreach ($companies as $company): ?>
                                    <option value="<?= $company['id'] ?>"><?= htmlspecialchars($company['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Branch Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="branch_code" id="edit_branch_code" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Branch Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="branch_name" id="edit_branch_name" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="number" step="0.000001" class="form-control" name="latitude" id="edit_latitude">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="number" step="0.000001" class="form-control" name="longitude" id="edit_longitude">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" id="edit_status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_branch" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fill edit modal with data
        document.querySelectorAll('.edit-branch-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('edit_id').value = btn.dataset.id;
                document.getElementById('edit_company_id').value = btn.dataset.companyId;
                document.getElementById('edit_branch_code').value = btn.dataset.branchCode;
                document.getElementById('edit_branch_name').value = btn.dataset.branchName;
                document.getElementById('edit_latitude').value = btn.dataset.latitude;
                document.getElementById('edit_longitude').value = btn.dataset.longitude;
                document.getElementById('edit_status').value = btn.dataset.status;
            });
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.classList.remove('show');
                alert.classList.add('fade');
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    });
    </script>
</body>
</html>