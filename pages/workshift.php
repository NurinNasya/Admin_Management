<?php 
require_once '../db.php';  // Ensure the file is included only once
?>
<?php
include_once '../Controller/shiftController.php';
?>

<?php include('../includes/header-navbar.php'); ?>

<!-- Main content -->
<div class="container-fluid">
  <div class="row">
    <!-- Work Shift List -->
    <div class="col-lg-6">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header py-3 px-4">
          <h6 class="mb-0">Work Shift Management</h6>
        </div>
        <div class="card-body p-3">
          
        <!-- Success/Error Messages -->
                  <?php if (isset($_GET['success'])): ?>
              <div class="alert alert-success alert-dismissible fade show text-white" role="alert">
                Shift added successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php elseif (isset($_GET['updated'])): ?>
              <div class="alert alert-info alert-dismissible fade show text-white" role="alert">
                Shift updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php elseif (isset($_GET['deleted'])): ?>
              <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
                Shift deleted successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php elseif (isset($_GET['error']) && $_GET['error'] === 'code'): ?>
              <div class="alert alert-warning alert-dismissible fade show text-white" role="alert">
                Duplicate shift code detected.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            <?php elseif (isset($_GET['error']) && $_GET['error'] === 'time'): ?>
              <div class="alert alert-warning alert-dismissible fade show text-white" role="alert">
                Duplicate start time detected.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
          <?php endif; ?>

          <ul class="list-group">
        <?php if (!empty($shifts)): ?>
            <?php foreach ($shifts as $row): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center mb-2 p-3 border-radius-lg">
                <div class="d-flex flex-column">
                  <h6 class="mb-1 text-dark font-weight-bold text-sm">
                    <?= htmlspecialchars($row['code']) ?> - <?= htmlspecialchars($row['description']) ?>
                  </h6>
                  <span class="text-xs">
                    <?= htmlspecialchars($row['start_time']) ?> |
                    Work: <?= htmlspecialchars($row['work_hour']) ?>h |
                    Break: <?= htmlspecialchars($row['break_hour']) ?>h |
                    Status: <?= $row['status'] == 1 ? 'Active' : 'Inactive' ?>
                  </span>
                </div>
                <div class="d-flex">
                  <i class="bi bi-pencil-square fs-5 text-primary ms-3" role="button" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id'] ?>"></i>
                  <form method="POST" action="../Controller/shiftController.php" onsubmit="return confirm('Are you sure you want to delete this shift?');" class="d-inline">
                    <input type="hidden" name="delete_code" value="<?= htmlspecialchars($row['code']) ?>">
                    <button type="submit" name="delete_shift" class="btn p-0 border-0 bg-transparent ms-3">
                      <i class="bi bi-trash-fill fs-5 text-danger"></i>
                    </button>
                  </form>
                </div>
              </li>

              <!-- Edit Modal -->
              <div class="modal fade" id="editModal<?= $row['id'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                  <form method="POST" action="../Controller/shiftController.php">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel<?= $row['id'] ?>">Edit Shift</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
                        <div class="mb-3">
                          <label>Shift Code</label>
                          <input type="text" class="form-control" value="<?= htmlspecialchars($row['code']) ?>" disabled>
                        </div>
                        <div class="mb-3">
                          <label>Description<span class="text-danger"> *</span></label>
                          <input type="text" class="form-control" name="edit_description" value="<?= htmlspecialchars($row['description']) ?>" required>
                        </div>
                        <div class="mb-3">
                          <label>Start Time<span class="text-danger"> *</span></label>
                          <input type="time" class="form-control" name="edit_start_time" value="<?= htmlspecialchars($row['start_time']) ?>" required>
                        </div>
                        <div class="mb-3">
                          <label>Work Hour<span class="text-danger"> *</span></label>
                          <input type="number" class="form-control" name="edit_work_hour" value="<?= htmlspecialchars($row['work_hour']) ?>" required>
                        </div>
                        <div class="mb-3">
                          <label>Break Hour<span class="text-danger"> *</span></label>
                          <input type="number" class="form-control" name="edit_break_hour" value="<?= htmlspecialchars($row['break_hour']) ?>" required>
                        </div>
                        <div class="mb-3">
                          <label>Status</label>
                          <select class="form-select" name="edit_status" required>
                            <option value="1" <?= $row['status'] == 1 ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= $row['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                          </select>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" name="update_shift" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <li class="list-group-item">No shifts available.</li>
          <?php endif; ?>

            <?php
            //ori
            /*include '../db.php';
            $sql = "SELECT * FROM shifts ORDER BY id DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
              while ($row = $result->fetch_assoc()) {
                echo '
                <li class="list-group-item d-flex justify-content-between align-items-center mb-2 p-3 border-radius-lg">
                  <div class="d-flex flex-column">
                    <h6 class="mb-1 text-dark font-weight-bold text-sm">'
                      . htmlspecialchars($row['code']) . ' - ' . htmlspecialchars($row['description']) . '</h6>
                    <span class="text-xs">'
                      . htmlspecialchars($row['start_time']) .
                      ' | Work: ' . htmlspecialchars($row['work_hour']) . 'h | Break: ' . htmlspecialchars($row['break_hour']) .
                      'h | Status: ' . ($row['status'] == 1 ? 'Active' : 'Inactive') .
                    '</span>
                  </div>
                  <div class="d-flex">
                    <!-- Edit Icon -->
                <i class="bi bi-pencil-square fs-5 text-primary ms-3" role="button" data-bs-toggle="modal" data-bs-target="#editModal' . $row['id'] . '"></i>

                <!-- Delete Icon inside a form -->
                <form method="POST" action="crudshift.php" onsubmit="return confirm(\'Are you sure you want to delete this shift?\');" class="d-inline">
                  <input type="hidden" name="delete_code" value="' . htmlspecialchars($row['code']) . '">
                  <button type="submit" name="delete_shift" class="btn p-0 border-0 bg-transparent ms-3">
                    <i class="bi bi-trash-fill fs-5 text-danger"></i>
                  </button>
                </form>
                  </div>
                </li>';

                // Static Edit Modal
                echo '
                <div class="modal fade" id="editModal' . $row['id'] . '" tabindex="-1" aria-labelledby="editModalLabel' . $row['id'] . '" aria-hidden="true">
                  <div class="modal-dialog">
                    <form method="POST" action="crudshift.php">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="editModalLabel' . $row['id'] . '">Edit Shift</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <input type="hidden" name="edit_id" value="' . $row['id'] . '">
                          <!-- Do not allow editing the code -->
                          <div class="mb-3">
                            <label>Shift Code</label>
                            <input type="text" class="form-control" value="' . htmlspecialchars($row['code']) . '" disabled>
                          </div>
                          <div class="mb-3">
                            <label>Description<span class="text-danger"> *</span></label>
                            <input type="text" class="form-control" name="edit_description" value="' . htmlspecialchars($row['description']) . '" required>
                          </div>
                          <div class="mb-3">
                            <label>Start Time<span class="text-danger"> *</span></label>
                            <input type="time" class="form-control" name="edit_start_time" value="' . htmlspecialchars($row['start_time']) . '" required>
                          </div>
                          <div class="mb-3">
                            <label>Work Hour<span class="text-danger"> *</span></label>
                            <input type="number" class="form-control" name="edit_work_hour" value="' . htmlspecialchars($row['work_hour']) . '" required>
                          </div>
                          <div class="mb-3">
                            <label>Break Hour<span class="text-danger"> *</span></label>
                            <input type="number" class="form-control" name="edit_break_hour" value="' . htmlspecialchars($row['break_hour']) . '" required>
                          </div>
                          <div class="mb-3">
                            <label>Status</label>
                            <select class="form-select" name="edit_status" required>
                              <option value="1" ' . ($row['status'] == 1 ? 'selected' : '') . '>Active</option>
                              <option value="0" ' . ($row['status'] == 0 ? 'selected' : '') . '>Inactive</option>
                            </select>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" name="update_shift" class="btn btn-primary">Save Changes</button>
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>';
                  //</div>
                //</li>';
              }
            } else {
              echo '<li class="list-group-item">No shifts available.</li>';
            }*/
            ?>
          </ul>
        </div>
      </div>
    </div>

    <!-- Add Shift Form -->
    <div class="col-lg-6 mt-4 mt-lg-0">
      <div class="card shadow-sm border-0 rounded-3" id="addShiftForm">
        <div class="card-header py-3 px-4">
          <h6 class="mb-0">Add New Shift</h6>
        </div>
        <div class="card-body p-3">
          <form method="POST" action="../Controller/shiftController.php">
            <div class="mb-3">
              <label for="code" class="form-label">Shift Code<span class="text-danger"> *</span></label>
              <input type="text" class="form-control" id="code" name="code" required>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Description<span class="text-danger"> *</span></label>
              <input type="text" class="form-control" id="description" name="description" required>
            </div>
            <div class="mb-3">
              <label for="start_time" class="form-label">Start Time<span class="text-danger"> *</span></label>
              <input type="time" class="form-control" id="start_time" name="start_time" required>
            </div>
          <div class="row">
  <div class="col-md-6 mb-3">
    <label for="work_hour" class="form-label">Work Hour<span class="text-danger"> *</span></label>
    <input type="number" class="form-control" id="work_hour" name="work_hour" required>
  </div>
  <div class="col-md-6 mb-3">
    <label for="break_hour" class="form-label">Break Hour<span class="text-danger"> *</span></label>
    <input type="number" class="form-control" id="break_hour" name="break_hour" required>
  </div>
</div>

            <div class="mb-3">
              <label for="status" class="form-label">Status</label>
              <select class="form-select" id="status" name="status" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
           <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Add Shift</button>
          </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
        </main>
</html>