<?php
include __DIR__ . '/../Controller/adminMedClaimController.php';
?>

<?php include('../includes/header-navbar.php'); ?>

 <div class="container-fluid py-4">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5>Medical Claim Approvals</h5>
        </div>
    
    <?php if (isset($_GET['updated'])): ?>
      <div class="alert alert-success alert-dismissible fade show">
        Claim status updated successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>
    
    <!-- Pending Claims Section -->
<div class="card-body">
          <h5 class="card-title">Pending Claims</h5>
  
  <?php if (empty($pendingClaims)): ?>
    <div class="alert alert-info">No pending claims to review.</div>
  <?php else: ?>
    <div class="card">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th>Claim ID</th>
                    <th>Staff Name</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Description</th>
                    <th>Attachment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingClaims as $claim): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($claim['id'] ?? '') ?></td>
                        <td><?= htmlspecialchars($claim['staff_name'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($claim['department_name'] ?? 'N/A') ?></td>
                        <td><?= date('d M Y', strtotime($claim['date_receipt'] ?? '')) ?></td>
                        <td>RM <?= number_format($claim['total'] ?? 0, 2) ?></td>
                        <td><?= htmlspecialchars($claim['description'] ?? '') ?></td>
                        <td>
                            <?php if (!empty($claim['document_name'])): ?>
                                <a href="../uploads/<?= htmlspecialchars($claim['document_name']) ?>" target="_blank" class="document-link">
                                    <?= htmlspecialchars($claim['document_name']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">No document</span>
                            <?php endif; ?>
                        </td>
                  <td>
                    <div class="d-flex gap-2">
                      <form method="POST" class="d-inline">
                        <input type="hidden" name="claim_id" value="<?= $claim['id'] ?>">
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" class="btn btn-success btn-sm">Approve
                        </button>
                      </form>
                      
                      <button class="btn btn-danger btn-sm show-reject-form" data-claim-id="<?= $claim['id'] ?>">Reject
                      </button>
                    </div>
                    
                    <form method="POST" class="reject-form mt-2" id="reject-form-<?= $claim['id'] ?>" style="display: none;">
                      <input type="hidden" name="claim_id" value="<?= $claim['id'] ?>">
                      <input type="hidden" name="action" value="reject">
                      <div class="mb-2">
                        <label for="reject_reason_<?= $claim['id'] ?>" class="form-label small">Reason:</label>
                        <textarea class="form-control form-control-sm" id="reject_reason_<?= $claim['id'] ?>" 
                                  name="reject_reason" required rows="2"></textarea>
                      </div>
                      <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-check-circle"></i> Confirm Reject
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
  <?php endif; ?>
</div>
    
<!-- Recent Approvals Section -->
<div class="card-body">
    <h5 class="card-title">Recent Approvals/Rejections</h5>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Claim ID</th>
                            <th>Staff Name</th>
                            <th>Department</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Document</th>
                            <th>Action Date</th>
                            <?php if (!empty($recentApprovals) && isset($recentApprovals[0]['reject_reason'])): ?>
                                <th>Reason</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($recentApprovals)): ?>
                            <tr>
                                <td colspan="<?= (!empty($recentApprovals) && isset($recentApprovals[0]['reject_reason'])) ? '9' : '8' ?>" class="text-center">
                                    No recent approvals
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentApprovals as $claim): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($claim['id'] ?? '') ?></td>
                                    <td><?= htmlspecialchars($claim['staff_name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($claim['department_name'] ?? 'N/A') ?></td>
                                    <td><?= date('d M Y', strtotime($claim['date_receipt'] ?? '')) ?></td>
                                    <td>RM <?= number_format($claim['total'] ?? 0, 2) ?></td>
                                    <td>
                                        <span class="badge <?= ($claim['status'] ?? '') == 'approved' ? 'badge-approved' : 'badge-rejected' ?>">
                                            <?= ucfirst($claim['status'] ?? '') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($claim['attachment'])): ?>
                                            <a href="../uploads/<?= htmlspecialchars($claim['attachment']) ?>" target="_blank" class="document-link">
                                                <?= htmlspecialchars($claim['attachment']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">None</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $actionDate = ($claim['status'] ?? '') == 'approved' 
                                            ? ($claim['approved_at'] ?? null)
                                            : (($claim['status'] ?? '') == 'rejected' ? ($claim['rejected_at'] ?? null) : null);
                                        echo $actionDate ? date('d M Y H:i', strtotime($actionDate)) : 'N/A';
                                        ?>
                                    </td>
                                    <?php if (isset($claim['reject_reason'])): ?>
                                        <td><?= htmlspecialchars($claim['reject_reason'] ?? '') ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

  <!-- Font Awesome for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
  // Toggle reject reason form for table rows
  document.querySelectorAll('.show-reject-form').forEach(button => {
    button.addEventListener('click', function() {
      const claimId = this.getAttribute('data-claim-id');
      const form = document.getElementById(`reject-form-${claimId}`);
      form.style.display = form.style.display === 'block' ? 'none' : 'block';
    });
  });
</script>
  <!-- <script>
    // Toggle reject reason form
    document.querySelectorAll('.show-reject-form').forEach(button => {
      button.addEventListener('click', function() {
        const form = this.closest('.card-body').querySelector('.reject-form');
        form.style.display = form.style.display === 'block' ? 'none' : 'block';
      });
    });
  </script> -->
</body>
</html>