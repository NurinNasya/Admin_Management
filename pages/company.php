<?php 
require_once '../db.php';  // Ensure the file is included only once
session_start(); // Start session to access session messages
require_once '../Controller/compController.php';
$compModel = new company();
$companies = $compModel->getAllRaw();
?>

<?php include('../includes/header-navbar.php'); ?>
            
    <!--start main content-->        
    <div class="container-fluid py-4">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center" style="margin-bottom: 5px;">
            <h5 style="margin-bottom: 0;">Company Management</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCompanyModal" style="margin-top: 0;">Add Company</button>
          </div>
          <div class="card-body" style="padding-top: 10px;">

      <!-- Alert Messages -->
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
                  <?php if ($companies && $companies->num_rows > 0): ?>
                      <?php $counter = 1; ?>
                      <?php while ($row = $companies->fetch_assoc()): ?>
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
                                  <a href="#" class="text-primary me-3 edit-company-btn"
                                      data-id="<?= $id ?>"
                                      data-code="<?= $code ?>"
                                      data-name="<?= $name ?>"
                                      data-status="<?= $raw_status ?>"
                                      data-bs-toggle="modal"
                                      data-bs-target="#editCompanyModal"
                                      title="Edit">
                                      <i class="bi bi-pencil-square fs-4"></i>
                                  </a>
                                  <a href="../Controller/compController.php?delete_id=<?= $id ?>" 
                                    class="text-danger" 
                                    onclick="return confirm('Are you sure you want to delete this company?');"
                                    title="Delete">
                                    <i class="bi bi-trash-fill fs-4"></i>
                                  </a>
                              </td>
                          </tr>
                      <?php endwhile; ?>
                  <?php else: ?>
                      <tr>
                          <td colspan="5" class="text-center">No companies found.</td>
                      </tr>
                  <?php endif; ?>
              </tbody>
  </table>
</div>


    <!-- Add Company Modal -->
    <div class="modal fade" id="addCompanyModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog">
        <form action="company.php" method="POST" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add New Company</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="company-code" class="form-label">Company Code <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="company-code" name="code" required>
            </div>
            <div class="mb-3">
              <label for="company-name" class="form-label">Company Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="company-name" name="name" required>
            </div>
            <div class="mb-3">
              <label for="company-status" class="form-label">Status</label>
              <select class="form-select" id="company-status" name="status">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="add_company" class="btn btn-primary">Save</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Company Modal -->
    <div class="modal fade" id="editCompanyModal" tabindex="-1">
      <div class="modal-dialog">
        <form action="company.php" method="POST" class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Company</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="edit_id" id="edit_id">
            <div class="mb-3">
              <label for="edit_code" class="form-label">Company Code <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="edit_code" id="edit_code" required>
            </div>
            <div class="mb-3">
              <label for="edit_name" class="form-label">Company Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="edit_name" id="edit_name" required>
            </div>
            <div class="mb-3">
              <label for="edit_status" class="form-label">Status</label>
              <select class="form-select" name="edit_status" id="edit_status">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="update_company" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>
    </div>
  </main>

  <!-- JS Scripts -->
  <script src="../assets/js/core/bootstrap.bundle.min.js"></script>
  <script>
    // Fill edit modal with selected data
    document.querySelectorAll('.edit-company-btn').forEach(button => {
      button.addEventListener('click', () => {
        document.getElementById('edit_id').value = button.dataset.id;
        document.getElementById('edit_code').value = button.dataset.code;
        document.getElementById('edit_name').value = button.dataset.name;
        document.getElementById('edit_status').value = button.dataset.status;
      });
    });

    
  </script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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