<?php 
require_once '../db.php';  // Ensure the file is included only once
session_start(); // Start session to access session messages
require_once '../Model/depart.php'; // adjust if path is different
$departModel = new Depart();
$departments = $departModel->getAllRaw(); // ✅ correct variable name & method for table list
?>
<?php require_once '../Controller/departController.php'; ?>

<?php include('../includes/header-navbar.php'); ?>


    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5>Department Management</h5>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">Add Department</button>
            </div>
            <?php if (isset($_SESSION['success_message']) || isset($_SESSION['error_message'])): ?>
            <div class="w-50">
              <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show auto-dismiss text-white" role="alert">
                  <?= htmlspecialchars($_SESSION['success_message']) ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
              <?php elseif (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show auto-dismiss text-white" role="alert">
                  <?= htmlspecialchars($_SESSION['error_message']) ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
              <?php endif; ?>
            </div>
          <?php endif; ?>
            <div class="card-body">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>No</th>
        <th>Code</th>
        <th>Name</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($departments && $departments->num_rows > 0): ?>
        <?php $counter = 1; ?>
        <?php while ($row = $departments->fetch_assoc()): ?>
          <?php
            $id = $row['id'];
            $code = htmlspecialchars($row['code']);
            $name = htmlspecialchars($row['name']);
            $raw_status = $row['status'];
            $statusBadge = $raw_status
              ? '<span class="badge border border-success text-success px-3 py-2">Active</span>'
              : '<span class="badge border border-danger text-danger px-3 py-2">Inactive</span>';
          ?>
          <tr>
            <td><?= $counter++ ?></td>
            <td><?= $code ?></td>
            <td><?= $name ?></td>
            <td><?= $statusBadge ?></td>
            <td>
              <a href="#" class="text-primary me-5 edit-department-btn"
                data-id="<?= $id ?>"
                data-code="<?= $code ?>"
                data-name="<?= $name ?>"
                data-status="<?= $raw_status ?>"
                data-bs-toggle="modal"
                data-bs-target="#editDepartmentModal"
                title="Edit">
                <i class="bi bi-pencil-square fs-4"></i>
              </a>
              <a href="../Controller/departController.php?delete_id=<?= $id ?>" 
                class="text-danger" 
                onclick="return confirm('Are you sure you want to delete this department?');"
                title="Delete">
                <i class="bi bi-trash-fill fs-4"></i>
              </a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center">No departments found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

    <!-- Add Department Modal -->
    <div class="modal fade" id="addDepartmentModal" tabindex="-1">
      <div class="modal-dialog">
        <form action="department.php" method="POST" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add New Department</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="dept-code" class="form-label">Department Code <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="dept-code" name="code" placeholder="Enter code" required>
            </div>
            <div class="mb-3">
              <label for="dept-name" class="form-label">Department Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="dept-name" name="name" placeholder="Enter name" required>
            </div>
            <div class="mb-3">
              <label for="dept-status" class="form-label">Status</label>
              <select class="form-select" id="dept-status" name="status">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="add_department" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Department Modal -->
    <div class="modal fade" id="editDepartmentModal" tabindex="-1">
    <div class="modal-dialog">
      <form action="department.php" method="POST" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Department <span class="text-danger">*</span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="edit_id" id="edit_id">
          <div class="mb-3">
            <label>Code <span class="text-danger">*</span></label>
            <input type="text" name="edit_code" id="edit_code" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" name="edit_name" id="edit_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Status</label>
            <select name="edit_status" id="edit_status" class="form-select">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" name="update_department" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>

  <!-- JS Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script>
  document.querySelectorAll('.edit-department-btn').forEach(btn => {
    btn.addEventListener('click', function () {
      document.getElementById('edit_id').value = this.dataset.id;
      document.getElementById('edit_code').value = this.dataset.code;
      document.getElementById('edit_name').value = this.dataset.name;
      document.getElementById('edit_status').value = this.dataset.status;
    });
  });
  </script>
  <script>
    setTimeout(function () {
      var alert = document.querySelector('.alert');
      if (alert) {
        alert.classList.remove('show');
        alert.classList.add('fade');
        setTimeout(() => alert.remove(), 500);
      }
    }, 3000);
  </script>
  </body>
</html>