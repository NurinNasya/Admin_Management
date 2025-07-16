<?php
require_once '../db.php';
require_once '../Model/Staff.php';

// Check if staff ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: staff.php");
    exit;
}

$staffModel = new Staff();
$staffId = (int)$_GET['id'];
$staff = $staffModel->getStaffById($staffId);

// Redirect if staff not found
if (!$staff) {
    header("Location: staff.php?error=staff_not_found");
    exit;
}

// Fetch dropdown options
$roles = $staffModel->getAllRoles();
$departments = $staffModel->getAllDepartments();
$companies = $staffModel->getAllCompanies();
$branches = $staffModel->getAllBranches();
$shifts = $staffModel->getAllShifts();
?>

<?php include('../includes/header-navbar.php'); ?>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                        <h6>Edit Staff: <?= htmlspecialchars($staff['name']) ?></h6>
                        <a href="staff.php" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Staff List
                        </a>
                    </div>
                    
                    <!-- Display success/error messages -->
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
                       <form method="POST" action="../Controller/staffController.php" id="staffEditForm">
                        <!-- CRITICAL FIELDS - MUST BE PRESENT -->
                        <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                        <input type="hidden" name="update_staff" value="1">
                        
                        <!-- <form method="POST" action="../Controller/staffController.php">
                            <input type="hidden" name="id" value="<?= $staff['id'] ?>">
                            <input type="hidden" name="update_staff" value="1"> -->
                            
                            <!-- Auto-generated fields -->
                            <input type="hidden" name="dob" id="hidden_dob" value="<?= htmlspecialchars($staff['dob']) ?>">
                            <input type="hidden" name="age" id="hidden_age" value="<?= htmlspecialchars($staff['age']) ?>">
                            <input type="hidden" name="gender" id="hidden_gender" value="<?= htmlspecialchars($staff['gender']) ?>">
                            
                            <!-- Personal Information Card -->
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6>Personal Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>IC Number <span class="text-danger">*</span></label>
                                            <input type="text" name="noic" id="noic" maxlength="12" pattern="\d{12}" 
                                                   value="<?= htmlspecialchars($staff['noic']) ?>" 
                                                   class="form-control" required readonly style="background-color: #f8f9fa;">
                                        </div>

                                        <div class="col-md-6">
                                            <label>Full Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control" 
                                                   value="<?= htmlspecialchars($staff['name']) ?>" required>
                                        </div>

                                        <!-- Date of Birth and Age -->
                                        <div class="col-md-2">
                                            <label>Date Of Birth <span class="text-danger">*</span></label>
                                            <input type="date" id="dob" class="form-control readonly-field" 
                                                   value="<?= htmlspecialchars($staff['dob']) ?>" 
                                                   readonly style="background-color: #f8f9fa;">
                                        </div>

                                        <div class="col-md-2">
                                            <label>Age <span class="text-danger">*</span></label>
                                            <input type="text" id="age" class="form-control readonly-field" 
                                                   value="<?= htmlspecialchars($staff['age']) ?>" 
                                                   readonly style="background-color: #f8f9fa;">
                                        </div>

                                        <div class="col-md-2">
                                            <label>Phone <span class="text-danger">*</span></label>
                                            <input type="text" name="phone" id="phone" maxlength="11" pattern="\d*" 
                                                   value="<?= htmlspecialchars($staff['phone']) ?>" 
                                                   class="form-control" required>
                                        </div>

                                        <div class="col-md-4">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input type="email" name="email" class="form-control"
                                                   value="<?= htmlspecialchars($staff['email']) ?>" required>
                                        </div>
                                    
                                        <div class="col-md-2">
                                            <label>Marital Status <span class="text-danger">*</span></label>
                                            <select name="status_marital" class="form-control" required>
                                                <option value="1" <?= $staff['status_marital'] == '1' ? 'selected' : '' ?>>Single</option>
                                                <option value="2" <?= $staff['status_marital'] == '2' ? 'selected' : '' ?>>Married</option>
                                                <option value="3" <?= $staff['status_marital'] == '3' ? 'selected' : '' ?>>Divorced</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <label>Number of Dependents</label>
                                            <input type="number" name="dependent" class="form-control" 
                                                   value="<?= htmlspecialchars($staff['dependent']) ?>">
                                        </div>

                                        <div class="col-md-2">
                                            <label>Gender <span class="text-danger">*</span></label>
                                            <input type="text" id="gender" class="form-control readonly-field" 
                                                   value="<?= htmlspecialchars($staff['gender']) ?>" 
                                                   readonly style="background-color: #f8f9fa;">
                                        </div>

                                        <div class="col-md-6">
                                            <label>Permanent Address</label>
                                            <textarea name="permanent_address" class="form-control" rows="2"><?= 
                                                htmlspecialchars($staff['permanent_address']) ?></textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Mailing Address</label>
                                            <textarea name="mail_address" class="form-control" rows="2"><?= 
                                                htmlspecialchars($staff['mail_address']) ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Staff Information Card -->
                            <div class="card mb-4">
                                <div class="card-header pb-0">
                                    <h6>Staff Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label>Staff Number</label>
                                            <input type="text" name="staff_no" class="form-control readonly-field" 
                                                   value="<?= htmlspecialchars($staff['staff_no']) ?>" readonly>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label>Role <span class="text-danger">*</span></label>
                                            <select name="roles" class="form-control" required>
                                                <?php foreach ($roles as $r): ?>
                                                    <option value="<?= $r['role_name'] ?>" <?= 
                                                        $staff['roles'] == $r['role_name'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($r['role_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label>Department <span class="text-danger">*</span></label>
                                            <select name="departments_id" class="form-control" required>
                                                <?php foreach ($departments as $d): ?>
                                                    <option value="<?= $d['id'] ?>" <?= 
                                                        $staff['departments_id'] == $d['id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($d['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label>Company <span class="text-danger">*</span></label>
                                            <select name="company_id" class="form-control" required>
                                                <?php foreach ($companies as $c): ?>
                                                    <option value="<?= $c['id'] ?>" <?= 
                                                        $staff['company_id'] == $c['id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($c['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label>Branch <span class="text-danger">*</span></label>
                                            <select name="company_branch" class="form-control" required>
                                                <?php foreach ($branches as $branch): ?>
                                                    <option value="<?= $branch['id'] ?>" <?= 
                                                        $staff['company_branch'] == $branch['id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($branch['branch_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label>Daily Working Hours</label>
                                            <input type="number" name="working_hours" class="form-control readonly-field" 
                                                   min="1" max="24" step="0.01" value="<?= 
                                                   htmlspecialchars($staff['working_hours']) ?>" 
                                                   readonly style="background-color: #f8f9fa;">
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label>Break Duration (Hours)</label>
                                            <input type="number" name="break_duration" class="form-control readonly-field" 
                                                   min="0" max="24" step="0.01" value="<?= 
                                                   htmlspecialchars($staff['break_duration']) ?>" 
                                                   readonly style="background-color: #f8f9fa;">
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label>Shift</label>
                                            <select name="shift_id" class="form-control">
                                                <?php foreach ($shifts as $shift): ?>
                                                    <option value="<?= $shift['id'] ?>" <?= 
                                                        $staff['shift_id'] == $shift['id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($shift['description']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label>Employment Start Date</label>
                                            <input type="date" name="start_date" class="form-control" 
                                                   value="<?= htmlspecialchars($staff['start_date']) ?>" required>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label>Employment End Date (if applicable)</label>
                                            <input type="date" name="end_date" class="form-control" 
                                                   value="<?= htmlspecialchars($staff['end_date']) ?>">
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <select name="status" class="form-control" required>
                                                <option value="1" <?= $staff['status'] == '1' ? 'selected' : '' ?>>Active</option>
                                                <option value="0" <?= $staff['status'] == '0' ? 'selected' : '' ?>>Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                        
                                    <div class="row mt-3">
                                        <div class="col-md-3">
                                            <label>Role Status</label>
                                            <select name="role_status" class="form-control">
                                                <option value="1" <?= ($formData['role_status'] ?? '1') == '1' ? 'selected' : '' ?>>Permanent</option>
                                                <option value="0" <?= ($formData['role_status'] ?? '1') == '0' ? 'selected' : '' ?>>Contract</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label>QR Code Status</label>
                                            <select name="status_qrcode" class="form-control">
                                                <option value="1" <?= $staff['status_qrcode'] == '1' ? 'selected' : '' ?>>Enable</option>
                                                <option value="0" <?= $staff['status_qrcode'] == '0' ? 'selected' : '' ?>>Disable</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label>Selfie Status</label>
                                            <select name="status_swafoto" class="form-control">
                                                <option value="1" <?= $staff['status_swafoto'] == '1' ? 'selected' : '' ?>>Enable</option>
                                                <option value="0" <?= $staff['status_swafoto'] == '0' ? 'selected' : '' ?>>Disable</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label>Monitoring Status</label>
                                            <select name="status_monitor" class="form-control">
                                                <option value="1" <?= $staff['status_monitor'] == '1' ? 'selected' : '' ?>>Enable</option>
                                                <option value="0" <?= $staff['status_monitor'] == '0' ? 'selected' : '' ?>>Disable</option>
                                            </select>
                                        </div>

                                        <div class="col-md-12 d-flex justify-content-end mt-4">
                                            <button type="reset" class="btn btn-secondary me-2">
                                                <i class="fas fa-undo me-2"></i>Reset Form
                                            </button>
                                            
                                                <!-- CHANGE your submit button to this: -->
                                            <button type="submit" class="btn btn-primary" name="submit_update">
                                                <i class="fas fa-save me-2"></i>Update Staff
                                            </button>
                                            <!-- <button type="submit" name="update_staff" class="btn btn-primary">
                                                <i class="fas fa-save me-2"></i>Update Staff
                                            </button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Make IC number field read-only in edit mode
        document.getElementById('noic').readOnly = true;
        
        // If you need to process IC number on load (for dob/age/gender)
        const icNumber = document.getElementById('noic').value;
        if (icNumber.length === 12) {
            // Extract birth date parts (YYMMDD)
            const birthYY = icNumber.substr(0, 2);
            const birthMM = icNumber.substr(2, 2);
            const birthDD = icNumber.substr(4, 2);
            
            // Determine century (current year - 2000)
            const currentYearShort = new Date().getFullYear() - 2000;
            const fullBirthYear = (parseInt(birthYY) <= currentYearShort) ? 
                '20' + birthYY : '19' + birthYY;
            
            // Format date as YYYY-MM-DD
            const dob = `${fullBirthYear}-${birthMM}-${birthDD}`;
            
            // Calculate age
            const dobDate = new Date(dob);
            const age = new Date().getFullYear() - dobDate.getFullYear();
            
            // Determine gender (last digit)
            const lastDigit = parseInt(icNumber.substr(11, 1));
            const gender = (lastDigit % 2 === 1) ? 'M' : 'F';
            
            // Update fields (though they should already be set from server)
            document.getElementById("dob").value = dob;
            document.getElementById("age").value = age;
            document.getElementById("gender").value = gender;
        }
    });
document.getElementById('staffEditForm').addEventListener('submit', function(e) {
    console.log("Form submitted with data:", new FormData(this));
});
    </script>
</body>
</html>