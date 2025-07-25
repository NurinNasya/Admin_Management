<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../Controller/roleController.php';
require_once __DIR__ . '/../Model/Role.php';

//session_start();

$roleController = new RoleController($conn);
$roles = $roleController->getAllRoles();

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<?php include('../includes/header-navbar.php'); ?>

  
<div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Roles Management</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                            Add Role
                        </button>
                    </div>
                    
                    <!-- Messages -->
                    <div class="card-body pt-0">
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-success alert-dismissible fade show alert-auto-dismiss text-white" role="alert">
                                <?= htmlspecialchars($_SESSION['message']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['message']); ?>
                        <?php elseif (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show alert-auto-dismiss text-white" role="alert">
                                <?= htmlspecialchars($_SESSION['error']) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                    </div>

                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Role Name</th>
                                    <th>Role Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($roles)): ?>
                                    <?php $counter = 1; foreach ($roles as $row): ?>
                                        <tr>
                                            <td><?= $counter++ ?></td>
                                            <td><?= htmlspecialchars($row['role_name']) ?></td>
                                            <td><?= htmlspecialchars($row['role_type']) ?></td>
                                            <td>
                                                <?php if ($row['status']): ?>
                                                    <span class="badge border border-success text-success px-3 py-2">Active</span>
                                                <?php else: ?>
                                                    <span class="badge border border-danger text-danger px-3 py-2">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="#" class="text-primary me-3 edit-role-btn"
                                                    data-id="<?= $row['id'] ?>"
                                                    data-role-name="<?= htmlspecialchars($row['role_name']) ?>"
                                                    data-role-type="<?= htmlspecialchars($row['role_type']) ?>"
                                                    data-status="<?= $row['status'] ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editRoleModal"
                                                    title="Edit">
                                                    <i class="bi bi-pencil-square fs-4"></i>
                                                </a>
                                                <a href="../Controller/roleController.php?delete_id=<?= $id ?>" 
                                                  class="text-danger" 
                                                  onclick="return confirm('Are you sure you want to delete this role?');"
                                                  title="Delete">
                                                  <i class="bi bi-trash-fill fs-4"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No roles found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="" method="POST" class="modal-content">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="role-type" class="form-label">Role Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="role-type" name="role_type" required>
                            <option value="">Select Type</option>
                            <option value="MANAGEMENT">MANAGEMENT</option>
                            <option value="STAFF">STAFF</option>
                            <option value="INTERN">INTERN</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="role-name" class="form-label">Role Name <span class="text-danger">*</span></label>
                        <select class="form-select" id="role-name" name="role_name" required>
                            <option value="">Select Name</option>
                            <!-- Options will be populated by JavaScript -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_role" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="roles.php" method="POST" class="modal-content">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="role_id" id="edit_role_id">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_role_type" class="form-label">Role Type <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_role_type" name="role_type" required>
                            <option value="MANAGEMENT">MANAGEMENT</option>
                            <option value="STAFF">STAFF</option>
                            <option value="INTERN">INTERN</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_role_name" class="form-label">Role Name <span class="text-danger">*</span></label>
                        <select class="form-select" id="edit_role_name" name="role_name" required>
                            <!-- Options will be populated by JavaScript -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status" id="edit_status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_role" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleNames = {
        'MANAGEMENT': ['MANAGER', 'HR', 'FOUNDER', 'CFO', 'MANAGING-DIRECTOR', 'EXECUTIVE-DIRECTOR'],
        'STAFF': ['STAFF', 'CONTRACT', 'HOD', 'OPERATIONMANAGER', 'EXECUTIVEAVSB', 'SUPERVISORAVSB', 'HR-AGMA'],
        'INTERN': ['INTERN']
    };

    function updateRoleNames(selectId, roleType, currentRoleName = null) {
        const select = document.getElementById(selectId);
        select.innerHTML = currentRoleName ? '' : '<option value="">Select Name</option>';
        
        if (roleType && roleNames[roleType]) {
            roleNames[roleType].forEach(name => {
                if (!currentRoleName || name !== currentRoleName) {
                    const option = document.createElement('option');
                    option.value = name;
                    option.textContent = name;
                    if (currentRoleName && name === currentRoleName) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                }
            });
        }
    }

    // Event listeners
    document.getElementById('role-type').addEventListener('change', function() {
        updateRoleNames('role-name', this.value);
    });

    document.querySelectorAll('.edit-role-btn').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('edit_role_id').value = this.dataset.id;
            document.getElementById('edit_role_type').value = this.dataset.roleType;
            document.getElementById('edit_status').value = this.dataset.status;
            updateRoleNames('edit_role_name', this.dataset.roleType, this.dataset.roleName);
        });
    });

    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert-auto-dismiss').forEach(alert => {
            new bootstrap.Alert(alert).close();
        });
    }, 5000);
});
</script>
</body>
</html>