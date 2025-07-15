<?php 
require_once '../db.php';  
require_once '../Controller/departController.php';
require_once '../Controller/compController.php';
require_once '../model/Staff.php';
require_once '../model/Role.php'; // Add this line

$departModel = new Depart();
$compModel = new Company();
$roleModel = new Role();

$departments = $departModel->getAllDepartments(); // returns array of id, code, name
$companies = $compModel->getAllCompanies(); // Assuming you have this method
$roles = $roleModel->getAllRoles();
?>

<?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger">
    <?= htmlspecialchars($_SESSION['error']); ?>
    <?php unset($_SESSION['error']); ?>
  </div>
<?php endif; ?>

<?php include('../includes/header-navbar.php'); ?>

            
<!-- pages/add_staff.php -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>Add New Staff</h6>
            </div>
            <div class="card-body px-4 pt-0 pb-2">
              <form action="process_add_staff.php" method="post" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="staff_no" class="form-control-label">Staff Number*</label>
                      <input class="form-control" type="text" id="staff_no" name="staff_no" required>
                    </div>
                  </div>
                  
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="noic" class="form-control-label">IC Number (12 digits)*</label>
                      <input class="form-control" type="text" id="noic" name="noic" 
                            onblur="generateGender()" 
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)"
                            pattern="\d{12}"
                            title="Please enter exactly 12 digits"
                            required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="name" class="form-control-label">Full Name*</label>
                      <input class="form-control" type="text" id="name" name="name" required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="gender" class="form-control-label">Gender</label>
                      <input class="form-control auto-generated" type="text" id="gender" name="gender" readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="email" class="form-control-label">Email</label>
                      <input class="form-control" type="email" id="email" name="email">
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="phone" class="form-control-label">Phone Number (11 digits)*</label>
                      <input class="form-control" type="tel" id="phone" name="phone" 
                            onblur="generatePassword()"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"
                            pattern="\d{11}"
                            title="Please enter exactly 11 digits"
                            required>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="pwd" class="form-control-label">Password</label>
                      <input class="form-control auto-generated" type="text" id="pwd" name="pwd" readonly>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="status" class="form-control-label">Status</label>
                      <select class="form-control" id="status" name="status">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="company_id" class="form-control-label">Company</label>
                      <select class="form-control" id="company_id" name="company_id">
                        <?php foreach ($companies as $company): ?>
                          <option value="<?= $company['id'] ?>"><?= htmlspecialchars($company['name']) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="departments_id" class="form-control-label">Department</label>
                      <select class="form-control" id="departments_id" name="departments_id">
                        <?php foreach ($departments as $dept): ?>
                          <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['name']) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="roles" class="form-control-label">Role</label>
                      <select class="form-control" id="roles" name="roles">
                        <?php foreach ($roles as $role): ?>
                          <option value="<?= $role['name'] ?>"><?= htmlspecialchars($role['name']) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="shift_id" class="form-control-label">Shift</label>
                      <select class="form-control" id="shift_id" name="shift_id">
                        <?php foreach ($shifts as $shift): ?>
                          <option value="<?= $shift['id'] ?>"><?= htmlspecialchars($shift['name']) ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="permanent_address" class="form-control-label">Permanent Address</label>
                      <textarea class="form-control" id="permanent_address" name="permanent_address" rows="3"></textarea>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="mail_address" class="form-control-label">Mailing Address</label>
                      <textarea class="form-control" id="mail_address" name="mail_address" rows="3"></textarea>
                    </div>
                  </div>

                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="profile_pic" class="form-control-label">Profile Picture</label>
                      <input class="form-control" type="file" id="profile_pic" name="profile_pic" accept="image/*">
                    </div>
                  </div>
                </div>

                <div class="row mt-3">
                  <div class="col-md-12">
                    <button type="submit" class="btn bg-primary text-white">Add Staff</button>
                    <a href="staff.php" class="btn bg-secondary text-white">Cancel</a>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  
  <script>
    function generateGender() {
      const icNumber = document.getElementById('noic').value;
      if (icNumber.length > 0) {
        const lastDigit = icNumber.slice(-1);
        const gender = parseInt(lastDigit) % 2 === 0 ? 'F' : 'M';
        document.getElementById('gender').value = gender;
      }
    }

    function generatePassword() {
      const icNumber = document.getElementById('noic').value;
      const phone = document.getElementById('phone').value;
      
      if (icNumber.length >= 6 && phone.length >= 4) {
        const last6Ic = icNumber.slice(-6);
        const last4Phone = phone.slice(-4);
        const password = phone + last6Ic + '@' + last4Phone;
        document.getElementById('pwd').value = password;
      }
    }
  </script>
</body>
</html>