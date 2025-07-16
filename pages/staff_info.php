<?php 
require_once '../db.php';  
require_once '../Controller/departController.php';
require_once '../Controller/compController.php';
require_once '../model/Staff.php';
require_once '../model/Role.php';
require_once '../model/Branch.php';
require_once '../model/Shift.php';

session_start();

$departModel = new Depart();
$compModel = new Company();
$roleModel = new Role();
$branchModel = new Branch();
$shiftModel = new Shift();

$departments = $departModel->getAllDepartments();
$companies = $compModel->getAllCompanies();
$roles = $roleModel->getAllRoles();
$branches = $branchModel->getAllBranches();
$shifts = $shiftModel->getAllShifts();

$staffModel = new Staff();
$staff = $staffModel->getAllStaff();
//$generatedStaffNo = $staffModel->generateStaffNumber();
?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger">
    <?= htmlspecialchars($_SESSION['error']); ?>
    <?php unset($_SESSION['error']); ?>
  </div>
<?php endif; ?>

<?php include('../includes/header-navbar.php'); ?>
 
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Display success/error messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <!-- Main Form -->
            <form method="POST" action="/Admin_Management/Controller/staffController.php?action=create" enctype="multipart/form-data">
              <!-- Inside your <form> tag -->
              <input type="hidden" name="dob" id="hidden_dob">
              <input type="hidden" name="age" id="hidden_age">
              <input type="hidden" name="gender" id="hidden_gender">
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
                                       value="<?= htmlspecialchars($formData['noic'] ?? '') ?>" 
                                       class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label>Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" 
                                       value="<?= htmlspecialchars($formData['name'] ?? '') ?>" required>
                            </div>

                            <!-- Date of Birth and Age -->
                            <div class="col-md-2">
                                <label>Date Of Birth <span class="text-danger">*</span></label>
                                <input type="date" name="dob" id="dob" class="form-control" 
                                       value="<?= htmlspecialchars($formData['dob'] ?? '') ?>" 
                                       required readonly style="background-color: #f8f9fa;">
                            </div>

                            <div class="col-md-2">
                                <label>Age <span class="text-danger">*</span></label>
                                <input type="text" name="age" id="age" class="form-control" 
                                       value="<?= htmlspecialchars($formData['age'] ?? '') ?>" 
                                       required readonly style="background-color: #f8f9fa;">
                            </div>

                            <div class="col-md-2">
                                <label>Phone <span class="text-danger">*</span></label>
                                <input type="text" name="phone" id="phone" maxlength="11" pattern="\d*" 
                                       value="<?= htmlspecialchars($formData['phone'] ?? '') ?>" 
                                       class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control"
                                       value="<?= htmlspecialchars($formData['email'] ?? '') ?>">
                            </div>
                        
                            <div class="col-md-2">
                                <label>Marital Status <span class="text-danger">*</span></label>
                                <select name="status_marital" class="form-control">
                                    <option value="1" <?= ($formData['status_marital'] ?? '') == '1' ? 'selected' : '' ?>>Single</option>
                                    <option value="2" <?= ($formData['status_marital'] ?? '') == '2' ? 'selected' : '' ?>>Married</option>
                                    <option value="3" <?= ($formData['status_marital'] ?? '') == '3' ? 'selected' : '' ?>>Divorced</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Number of Dependents</label>
                                <input type="number" name="dependent" class="form-control" 
                                       value="<?= htmlspecialchars($formData['dependent'] ?? '0') ?>">
                            </div>

                            <div class="col-md-2">
                                <label>Gender <span class="text-danger">*</span></label>
                                <input type="text" name="gender" id="gender" class="form-control" 
                                       value="<?= htmlspecialchars($formData['gender'] ?? '') ?>" 
                                       readonly placeholder="Auto-detected" style="background-color: #f8f9fa;">
                            </div>

                            <div class="col-md-6">
                                <label>Permanent Address</label>
                                <textarea name="permanent_address" class="form-control" rows="2"><?= 
                                    htmlspecialchars($formData['permanent_address'] ?? '') ?></textarea>
                            </div>

                            <div class="col-md-6">
                                <label>Mailing Address</label>
                                <textarea name="mail_address" class="form-control" rows="2"><?= 
                                    htmlspecialchars($formData['mail_address'] ?? '') ?></textarea>
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
                            <!--<div class="col-md-3">
                                <label>Staff Number</label>
                                <input type="text" name="staff_no" class="form-control" 
                                       value="<?= $generatedStaffNo ?>" readonly>
                            </div>-->
                            <div class="col-md-3">
                                <label>Role <span class="text-danger">* </span></label>
                                <select name="roles" class="form-select"  required>
                              <?php foreach ($roles as $r): ?>
                                  <option value="<?= $r['role_name'] ?>"><?= htmlspecialchars($r['role_name']) ?></option>
                              <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Department <span class="text-danger">*</span></label>
                                <select name="departments_id" class="form-select" required>
                                    <?php foreach ($departments as $d): ?>
                                        <option value="<?= $d['id'] ?>" <?= 
                                            ($formData['departments_id'] ?? '') == $d['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($d['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Company <span class="text-danger">*</span></label>
                                <select name="company_id" class="form-select" required>
                                    <?php foreach ($companies as $c): ?>
                                        <option value="<?= $c['id'] ?>" <?= 
                                            ($formData['company_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($c['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>Branch <span class="text-danger">*</span></label>
                                <select name="company_branch" class="form-select" required>
                                    <?php foreach ($branches as $branch): ?>
                                        <option value="<?= $branch['id'] ?>" <?= 
                                            ($formData['company_branch'] ?? '') == $branch['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($branch['branch_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Daily Working Hours</label>
                                <input type="number" name="working_hours" class="form-control" 
                                       min="1" max="24" step="0.01" value="<?= 
                                       htmlspecialchars($formData['working_hours'] ?? '8.00') ?>" 
                                       readonly style="background-color: #f8f9fa;">
                            </div>
                            <div class="col-md-3">
                                <label>Break Duration (Hours)</label>
                                <input type="number" name="break_duration" class="form-control" 
                                       min="0" max="24" step="0.01" value="<?= 
                                       htmlspecialchars($formData['break_duration'] ?? '1.00') ?>" 
                                       readonly style="background-color: #f8f9fa;">
                            </div>
                            <div class="col-md-3">
                                <label>Shift</label>
                                <select name="shift_id" class="form-select">
                                    <?php foreach ($shifts as $shift): ?>
                                        <option value="<?= $shift['id'] ?>" <?= 
                                            ($formData['shift_id'] ?? '') == $shift['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($shift['description']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>Employment Start Date</label>
                                <input type="date" name="start_date" class="form-select" 
                                       value="<?= htmlspecialchars($formData['start_date'] ?? date('Y-m-d')) ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label>Employment End Date (if applicable)</label>
                                <input type="date" name="end_date" class="form-select" 
                                       value="<?= htmlspecialchars($formData['end_date'] ?? '') ?>">
                            </div>
                            <div class="col-md-3">
                                <label>Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="1" <?= ($formData['status'] ?? '1') == '1' ? 'selected' : '' ?>>Active</option>
                                    <option value="0" <?= ($formData['status'] ?? '1') == '0' ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                            
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label>Role Status</label>
                                <select name="role_status" class="form-select">
                                    <option value="1" <?= ($formData['role_status'] ?? '1') == '1' ? 'selected' : '' ?>>Permanent</option>
                                    <option value="0" <?= ($formData['role_status'] ?? '1') == '0' ? 'selected' : '' ?>>Contract</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>QR Code Status</label>
                                <select name="status_qrcode" class="form-select">
                                    <option value="1" <?= ($formData['status_qrcode'] ?? '1') == '1' ? 'selected' : '' ?>>Enable</option>
                                    <option value="0" <?= ($formData['status_qrcode'] ?? '1') == '0' ? 'selected' : '' ?>>Disable</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Selfie Status</label>
                                <select name="status_swafoto" class="form-select">
                                    <option value="1" <?= ($formData['status_swafoto'] ?? '1') == '1' ? 'selected' : '' ?>>Enable</option>
                                    <option value="0" <?= ($formData['status_swafoto'] ?? '1') == '0' ? 'selected' : '' ?>>Disable</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label>Monitoring Status</label>
                                <select name="status_monitor" class="form-select">
                                    <option value="1" <?= ($formData['status_monitor'] ?? '1') == '1' ? 'selected' : '' ?>>Enable</option>
                                    <option value="0" <?= ($formData['status_monitor'] ?? '1') == '0' ? 'selected' : '' ?>>Disable</option>
                                </select>
                            </div>

                            <div class="col-md-12 d-flex justify-content-end mt-4">
                                <!-- Reset Button - stays on same page -->
                                <button type="reset" class="btn btn-secondary me-2">
                                    <i class="fas fa-undo me-2"></i>Reset Form
                                </button>
                                
                                <!-- Save Button - redirects after submission -->
                                <button type="submit" name="create_staff" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Staff
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> <!-- Closing form tag -->

          <!-- Education Card -->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Education</h6>
                <button class="btn btn-sm btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#educationModal">
                  <i class="fas fa-plus me-2"></i>Add Education
                </button>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Qualification</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Start Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">End Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Institution</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Result</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="6" class="text-center text-sm text-secondary py-4">No education records found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Work Experience Card -->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Work Experience</h6>
                <button class="btn btn-sm btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#workModal">
                  <i class="fas fa-plus me-2"></i>Add Work Experience
                </button>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Company</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Start Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">End Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Position</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Salary</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Responsibilities</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="7" class="text-center text-sm text-secondary py-4">No work experience records found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Skills Card -->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Skills</h6>
                <button class="btn btn-sm btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#skillModal">
                  <i class="fas fa-plus me-2"></i>Add Skill
                </button>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Skill Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Proficiency Level</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="3" class="text-center text-sm text-secondary py-4">No skills records found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Family Card -->
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Family Members</h6>
                <button class="btn btn-sm btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#familyModal">
                  <i class="fas fa-plus me-2"></i>Add Family Member
                </button>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Relationship</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Occupation</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Phone Number</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td colspan="5" class="text-center text-sm text-secondary py-4">No family records found</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Education Modal -->
    <div class="modal fade" id="educationModal" tabindex="-1" aria-labelledby="educationModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="educationModalLabel">Add Education</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="educationForm">
              <div class="mb-3">
                <label class="form-label">Qualification <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="qualification" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Institution</label>
                <input type="text" class="form-control" name="institution" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Result</label>
                <input type="text" class="form-control" name="result">
              </div>
              <div class="mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-control" name="start_date">
              </div>
              <div class="mb-3">
                <label class="form-label">End Date</label>
                <input type="date" class="form-control" name="end_date">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="saveEducation()">Save</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Work Modal -->
    <div class="modal fade" id="workModal" tabindex="-1" aria-labelledby="workModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="workModalLabel">Add Work Experience</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="workForm">
              <div class="mb-3">
                <label class="form-label">Company <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="company" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Position</label>
                <input type="text" class="form-control" name="position">
              </div>
              <div class="mb-3">
                <label class="form-label">Start Date</label>
                <input type="date" class="form-control" name="start_date">
              </div>
              <div class="mb-3">
                <label class="form-label">End Date</label>
                <input type="date" class="form-control" name="end_date">
              </div>
              <div class="mb-3">
                <label class="form-label">Salary</label>
                <input type="number" class="form-control" name="salary">
              </div>
              <div class="mb-3">
                <label class="form-label">Responsibilities</label>
                <textarea class="form-control" name="responsibilities" rows="3"></textarea>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="saveWork()">Save</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Skill Modal -->
    <div class="modal fade" id="skillModal" tabindex="-1" aria-labelledby="skillModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="skillModalLabel">Add Skill</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="skillForm">
              <div class="mb-3">
                <label class="form-label">Skill Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="skill_name" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Proficiency Level <span class="text-danger">*</span></label>
                <select class="form-control" name="proficiency_level">
                  <option value="Beginner">Beginner</option>
                  <option value="Intermediate">Intermediate</option>
                  <option value="Advanced">Advanced</option>
                  <option value="Expert">Expert</option>
                </select>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="saveSkill()">Save</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Family Modal -->
    <div class="modal fade" id="familyModal" tabindex="-1" aria-labelledby="familyModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="familyModalLabel">Add Family Member</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="familyForm">
              <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Relationship <span class="text-danger">*</span></label>
                <select class="form-select" name="relationship" required>
                  <option value="" disabled selected>Select relationship</option>
                  <option value="Father">Father</option>
                  <option value="Mother">Mother</option>
                  <option value="Brother">Brother</option>
                  <option value="Sister">Sister</option>
                  <option value="Spouse">Spouse</option>
                  <option value="Child">Child</option>
                  <option value="Guardian">Guardian</option>
                  <option value="Other">Other</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Occupation</label>
                <input type="text" class="form-control" name="occupation">
              </div>
              <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" class="form-control" name="phone">
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="saveFamily()">Save</button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Perfect Scrollbar -->
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const icInput = document.getElementById('noic');
    const genderInput = document.getElementById('gender');
    const dobInput = document.getElementById('dob');
    const ageInput = document.getElementById('age');
    const phoneInput = document.getElementById('phone');

    function calculateAge(birthDate) {
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    }

    function processICNumber(ic) {
        ic = ic.replace(/\D/g, '').slice(0, 12);
        
        if (ic.length === 12) {
            // Extract birth date parts (YYMMDD)
            const birthYY = ic.substr(0, 2);
            const birthMM = ic.substr(2, 2);
            const birthDD = ic.substr(4, 2);
            
            // Determine century (current year - 2000)
            const currentYearShort = new Date().getFullYear() - 2000;
            const fullBirthYear = (parseInt(birthYY) <= currentYearShort) ? 
                '20' + birthYY : '19' + birthYY;
            
            // Validate date
            const dobDate = new Date(`${fullBirthYear}-${birthMM}-${birthDD}`);
            if (!isNaN(dobDate.getTime())) {
                // Format date as YYYY-MM-DD
                const formattedDob = dobDate.toISOString().split('T')[0];
                dobInput.value = formattedDob;
                ageInput.value = calculateAge(dobDate);
            } else {
                dobInput.value = '';
                ageInput.value = '';
            }
            
            // Determine gender (last digit)
            const lastDigit = parseInt(ic.substr(11, 1));
            genderInput.value = (lastDigit % 2 === 0) ? 'Female' : 'Male';
        } else {
            dobInput.value = '';
            ageInput.value = '';
            genderInput.value = '';
        }
        
        return ic;
    }

    icInput.addEventListener('input', function(e) {
        this.value = processICNumber(this.value);
    });

    icInput.addEventListener('blur', function() {
        if (this.value.length !== 12) {
            alert('IC number must be exactly 12 digits');
            this.focus();
        }
    });

    phoneInput.addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, '').slice(0, 11);
    });

    // Initialize fields if IC is pre-filled
    if (icInput.value.length === 12) {
        processICNumber(icInput.value);
    }
});

function resetICFields() {
    document.getElementById('noic').value = '';
    document.getElementById('dob').value = '';
    document.getElementById('age').value = '';
    document.getElementById('gender').value = '';
}

function parseIC(icNumber) {
    // Remove any non-digit characters
    icNumber = icNumber.replace(/\D/g, '');
    
    // Extract birth date components
    const yearPart = parseInt(icNumber.substring(0, 2));
    const month = icNumber.substring(2, 4);
    const day = icNumber.substring(4, 6);
    
    // Determine the full birth year (handles 1900s and 2000s)
    const currentYear = new Date().getFullYear();
    const currentShortYear = currentYear % 100; // Last 2 digits of current year
    
    // If year part is <= current last 2 digits, assume 2000s, otherwise 1900s
    const fullBirthYear = yearPart <= currentShortYear 
        ? 2000 + yearPart 
        : 1900 + yearPart;
    
    // Format DOB (YYYY-MM-DD)
    const dob = `${fullBirthYear}-${month}-${day}`;
    
    // Calculate age
    const birthDate = new Date(fullBirthYear, parseInt(month) - 1, parseInt(day));
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    
    // Adjust age if birthday hasn't occurred yet this year
    if (today.getMonth() < birthDate.getMonth() || 
        (today.getMonth() === birthDate.getMonth() && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    // Determine gender (odd = M, even = F)
    const gender = parseInt(icNumber.substr(-1)) % 2 === 1 ? "M" : "F";
    
    // Update visible fields
    document.getElementById("dob").value = dob;
    document.getElementById("age").value = age;
    document.getElementById("gender").value = gender;
    
    // Update hidden fields for form submission
    document.getElementById("hidden_dob").value = dob;
    document.getElementById("hidden_age").value = age;
    document.getElementById("hidden_gender").value = gender;
}

// Trigger this when IC number is entered
document.getElementById("noic").addEventListener("change", function() {
    parseIC(this.value);
});
</script>
</body>
</html>