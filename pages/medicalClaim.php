<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../Controller/medicalClaimController.php';
require_once __DIR__ . '/../Model/medClaim.php';

// Remove session_start() here since controller handles it
$staffId = isset($_GET['staff_id']) ? (int)$_GET['staff_id'] : 0; // Default to 0 if not provided

$controller = new MedicalClaimController($conn);
$viewData = $controller->handleRequest($staffId); // Pass the staffId
extract($viewData);

// At the very top of medicalClaim.php
$staffId = $_GET['staff_id'] ?? $_SESSION['staff_id'] ?? null;
if (!$staffId) {
    die("Staff ID not specified");
}

// All links on the page should include staff_id
function buildUrl($params = []) {
    global $staffId;
    $base = 'medicalClaim.php';
    $params['staff_id'] = $staffId;
    return $base . '?' . http_build_query($params);
}
?>

<?php include('../includes/header-navbar.php'); ?>
    
<div class="container-fluid py-4">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                    <span class="alert-text">Claim submitted successfully!</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Medical Balance Card -->
            <div class="row">
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card medical-balance-card">
                        <div class="card-body p-3">
                            <div class="balance-item balance-validity">
                                <span>Valid Until</span>
                                <span><?= $medical_balance['validity'] ?></span>
                            </div>
                            <div class="balance-item base-quota">
                                <span>Base Quota</span>
                                <span><?= $medical_balance['base'] ?></span>
                            </div>
                            <div class="balance-item add-quota">
                                <span>Additional Quota</span>
                                <span><?= $medical_balance['additional'] ?></span>
                            </div>
                            <div class="balance-item balance-entitled">
                                <span>Total Entitled</span>
                                <span><?= $medical_balance['entitled'] ?></span>
                            </div>
                            <div class="balance-item balance-used">
                                <span>Used Amount</span>
                                <span><?= $medical_balance['used'] ?></span>
                            </div>
                            <div class="balance-item balance-current">
                                <span>Current Balance</span>
                                <span><?= $medical_balance['current'] ?></span>
                            </div>
                            <div class="balance-item balance-pending">
                                <span>Pending Claims</span>
                                <span><?= $medical_balance['pending'] ?></span>
                            </div>
                            <div class="balance-item balance-available">
                                <span>Available Balance</span>
                                <span><?= $medical_balance['available'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical Claim Form -->
                <div class="col-xl-8 col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6>Recent Medical Claims</h6>
                            <?php if(isset($staffId) && $staffId > 0): ?>
                                <span class="badge bg-primary">Viewing Claims for Staff ID: <?= $staffId ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body pt-0">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="add_claim">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date_receipt" class="form-control-label">Date of Receipt</label>
                                            <input class="form-control" type="date" id="date_receipt" name="date_receipt" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="total" class="form-control-label">Amount (RM)</label>
                                            <input class="form-control" type="number" id="total" name="total" step="0.01" min="0" placeholder="0.00" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="description" class="form-control-label">Description</label>
                                    <input class="form-control" type="text" id="description" name="description" placeholder="Enter description">
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label">Attachment</label>
                                    <div class="file-upload">
                                        <label class="file-upload-label">
                                            <i class="fas fa-cloud-upload-alt me-2"></i>Choose file (PDF, JPG, PNG)
                                            <input type="file" class="file-upload-input" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.heic,.heif">
                                        </label>
                                        <div class="file-name" id="file-name">No file chosen</div>
                                    </div>
                                </div>
                                <button type="submit" class="btn bg-gradient-primary w-100 mt-3">
                                    <i class="fas fa-paper-plane me-2"></i> Submit Claim
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Claims Table
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Recent Medical Claims</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Attachment</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($currentClaims['claims'])): ?>
                                            <?php foreach ($currentClaims['claims'] as $claim): ?>
                                            <tr>
                                                <td><?= date('Y-m-d', strtotime($claim['date_receipt'])) ?></td>
                                                <td>RM<?= number_format($claim['total'], 2) ?></td>
                                                <td><?= htmlspecialchars($claim['description']) ?></td>
                                                <td>
                                                    <?php
                                                    $status = $claim['status'] ?? 'pending';
                                                    $badgeClass = $status === 'approved' ? 'badge-approved' : 
                                                                ($status === 'rejected' ? 'badge-rejected' : 'badge-pending');
                                                    ?>
                                                    <span class="badge badge-sm <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($claim['document_name'])): ?>
                                                        <a href="../uploads/<?= htmlspecialchars($claim['document_name']) ?>" target="_blank" class="document-link">
                                                            <i class="fas fa-file-pdf me-1 text-danger"></i>
                                                            <?= htmlspecialchars($claim['document_name']) ?>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-xs text-muted">No file</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="action-buttons">
                                                    <button class="btn btn-sm btn-warning text-action-btn" onclick="openEditModal(<?= $claim['id'] ?>)">
                                                        Edit
                                                    </button>
                                                    <button class="btn btn-sm btn-danger text-action-btn" onclick="confirmDelete(<?= $claim['id'] ?>)">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No claims found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

<!-- Recent Claims Table -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0">
                <h6>Recent Medical Claims</h6>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Rejection Reason</th>
                                <th>Attachment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($currentClaims['claims'])): ?>
                                <?php foreach ($currentClaims['claims'] as $claim): ?>
                                <tr>
                                    <td><?= date('Y-m-d', strtotime($claim['date_receipt'])) ?></td>
                                    <td>RM<?= number_format($claim['total'], 2) ?></td>
                                    <td><?= htmlspecialchars($claim['description']) ?></td>
                                    <td>
                                        <?php
                                        $status = $claim['status'] ?? 'pending';
                                        $badgeClass = $status === 'approved' ? 'badge-approved' : 
                                                    ($status === 'rejected' ? 'badge-rejected' : 'badge-pending');
                                        ?>
                                        <span class="badge badge-sm <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($status === 'rejected' && !empty($claim['reject_reason'])): ?>
                                            <span class="rejection-reason"><?= htmlspecialchars($claim['reject_reason']) ?></span>
                                        <?php else: ?>
                                            <span class="text-xs text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($claim['document_name'])): ?>
                                            <a href="../uploads/<?= htmlspecialchars($claim['document_name']) ?>" target="_blank" class="document-link">
                                                <i class="fas fa-file-pdf me-1 text-danger"></i>
                                                <?= htmlspecialchars($claim['document_name']) ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-xs text-muted">No file</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-buttons">
                                        <?php if ($status === 'pending'): ?>
                                            <button class="btn btn-sm btn-warning text-action-btn" onclick="openEditModal(<?= $claim['id'] ?>)">
                                                Edit
                                            </button>
                                            <button class="btn btn-sm btn-danger text-action-btn" onclick="confirmDelete(<?= $claim['id'] ?>)">
                                                Delete
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted">No actions</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center">No claims found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- Edit Claim Modal -->
        <div id="editClaimModal" class="edit-modal">
            <div class="edit-modal-content">
                <span class="close-modal" onclick="closeEditModal()">&times;</span>
                <h5 class="mb-4">Edit Medical Claim</h5>
                <form method="POST" enctype="multipart/form-data" id="editClaimForm">
                    <input type="hidden" name="action" value="edit_claim">
                    <input type="hidden" id="edit_claim_id" name="edit_claim_id">
                    <!-- Make sure this staff_id input exists and has the correct value -->
                    <!-- <input type="hidden" name="staff_id" value="<?= htmlspecialchars($staffId ?? '') ?>"> -->
                    <input type="hidden" name="staff_id" value="<?= htmlspecialchars($_GET['staff_id'] ?? $staffId ?? '') ?>">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_date_receipt" class="form-control-label">Date Receipt</label>
                                <input class="form-control" type="date" id="edit_date_receipt" name="edit_date_receipt" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="edit_total" class="form-control-label">Total (RM)</label>
                                <input class="form-control" type="number" id="edit_total" name="edit_total" step="0.01" min="0" placeholder="0.00" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_description" class="form-control-label">Description</label>
                        <textarea class="form-control" id="edit_description" name="edit_description" rows="2"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-control-label">Attachment</label>
                        <div class="file-upload">
                            <label class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt me-2"></i>Choose file (PDF, JPG, PNG)
                                <input type="file" class="file-upload-input" id="edit_attachment" name="edit_attachment" 
                                      accept=".pdf,.jpg,.jpeg,.png,.heic,.heif">
                            </label>
                            <div class="file-name" id="edit_file_name">No file chosen</div>
                        </div>
                        <div id="current_file" class="mt-2 small text-muted"></div>
                        <input type="hidden" id="existing_attachment" name="existing_attachment">
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <button type="button" class="btn btn-outline-secondary" onclick="closeEditModal()">
                            Cancel
                        </button>
                        <button type="submit" class="btn bg-gradient-primary">
                            <i class="fas fa-save me-2"></i> Update Claim
                        </button>
                    </div>
                </form>
            </div>
        </div>

<script>
document.getElementById('editClaimForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

    // Get staff_id from form or URL
    const staffId = document.querySelector('input[name="staff_id"]').value || 
                   new URLSearchParams(window.location.search).get('staff_id');

    const formData = new FormData(this);
    formData.set('staff_id', staffId); // Ensure staff_id is set

    fetch('medicalClaim.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Update failed');
        // Always redirect with staff_id
        window.location.href = `medicalClaim.php?staff_id=${staffId}&updated=${Date.now()}`;
    })
    .catch(error => {
        console.error('Error:', error);
        // On error, still redirect with staff_id
        window.location.href = `medicalClaim.php?staff_id=${staffId}`;
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Update Claim';
    });
});
</script>

<script>
// Handle file name display for both forms
document.getElementById('attachment').addEventListener('change', function(e) {
    document.getElementById('file-name').textContent = e.target.files[0]?.name || 'No file chosen';
});

document.getElementById('edit_attachment').addEventListener('change', function(e) {
    document.getElementById('edit_file_name').textContent = e.target.files[0]?.name || 'No file chosen';
});

// Edit modal functions
function openEditModal(claimId) {
    // Get staff_id from URL or form
    const staffId = new URLSearchParams(window.location.search).get('staff_id') || 
                   document.querySelector('input[name="staff_id"]')?.value;
    
    fetch(`medicalClaim.php?action=get_claim_details&claim_id=${claimId}&staff_id=${staffId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }

            // Populate form fields
            document.getElementById('edit_claim_id').value = claimId;
            document.getElementById('edit_date_receipt').value = data.date_receipt;
            document.getElementById('edit_total').value = data.total;
            document.getElementById('edit_description').value = data.description;
            
            // Handle attachment display
            const currentFileEl = document.getElementById('current_file');
            if (data.document_name) {
                currentFileEl.innerHTML = `Current file: <a href="../uploads/${data.document_name}" target="_blank">${data.document_name}</a>`;
            } else {
                currentFileEl.innerHTML = 'No current file';
            }
            
            // Ensure form has staff_id
            document.querySelector('input[name="staff_id"]').value = staffId;
            
            // Show modal
            document.getElementById('editClaimModal').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load claim details: ' + error.message);
        });
}

// Form submission handler
document.getElementById('editClaimForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';

    fetch('medicalClaim.php', {
        method: 'POST',
        body: new FormData(this)
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
            return;
        }
        return response.text();
    })
    .then(text => {
        try {
            const data = JSON.parse(text);
            if (data.error) {
                throw new Error(data.error);
            }
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        } catch (e) {
            // If not JSON, assume it's a redirect
            window.location.href = 'medicalClaim.php';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: ' + error.message);
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Update Claim';
    });
});

function closeEditModal() {
    document.getElementById('editClaimModal').style.display = 'none';
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editClaimModal');
    if (event.target == modal) {
        closeEditModal();
    }
}

function confirmDelete(claimId) {
    if (confirm('Are you sure you want to delete this claim?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'medicalClaim.php';
        
        const inputAction = document.createElement('input');
        inputAction.type = 'hidden';
        inputAction.name = 'action';
        inputAction.value = 'delete_claim';
        form.appendChild(inputAction);
        
        const inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'claim_id';
        inputId.value = claimId;
        form.appendChild(inputId);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>

        <script>
        // Auto-refresh every 30 seconds to show latest claims
        setTimeout(() => {
            window.location.reload();
        }, 30000);
        </script>
    </main>
</body>
</html>