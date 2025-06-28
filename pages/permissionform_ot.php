<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Overtime Permission
  </title>
  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
  <style>
    /* Reset and base styles */
    *, *::before, *::after {
      box-sizing: border-box;
    }
    
    .badge-warning {
      background-color: #ffc107;
      color: #212529;
      padding: 3px 8px;
      border-radius: 3px;
      font-size: 12px;
    }
    .badge-success {
      background-color: #28a745;
      color: white;
      padding: 3px 8px;
      border-radius: 3px;
      font-size: 12px;
    }
    .badge-danger {
      background-color: #dc3545;
      color: white;
      padding: 3px 8px;
      border-radius: 3px;
      font-size: 12px;
    }
    .form-row {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 15px;
    }
    .form-row .form-group {
      flex: 1;
      min-width: 200px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      font-size: 13px;
    }
    .form-group input, .form-group select, .form-group textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 3px;
      font-size: 14px;
    }
    .form-group textarea {
      min-height: 100px;
    }
    .reminder {
      background-color: #fff3cd;
      border: 1px solid #ffeaa7;
      border-radius: 5px;
      padding: 15px;
      margin: 20px 0;
    }
    .reminder h4 {
      margin-bottom: 10px;
      color: #856404;
    }
    .reminder ol {
      margin-left: 20px;
    }
    .reminder li {
      margin-bottom: 5px;
      color: #856404;
      font-size: 13px;
    }
    .success-message {
      background-color: #d4edda;
      color: #155724;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 20px;
    }
    
    /* Fixed filter section with equal heights */
    .filters {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-bottom: 20px;
      align-items: flex-end;
    }
    .filter-group {
      display: flex;
      flex-direction: column;
      gap: 5px;
      flex: 1;
      min-width: 120px;
    }
    .filter-group label {
      font-size: 12px;
      color: #666;
      margin-bottom: 0;
    }
    .filter-group select, 
    .btn-filter {
      height: 38px;
      padding: 6px 12px;
      border: 1px solid #ced4da;
      border-radius: 4px;
      font-size: 14px;
      line-height: 1.5;
      box-sizing: border-box;
    }
    .filter-group select {
      appearance: none;
      -webkit-appearance: none;
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
      background-repeat: no-repeat;
      background-position: right 0.75rem center;
      background-size: 16px 12px;
    }
    .btn-filter {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      min-width: 80px;
    }
    .btn-primary.btn-filter {
      background-color: #5e72e4;
      border-color: #5e72e4;
    }
    .btn-secondary.btn-filter {
      background-color: #6c757d;
      border-color: #6c757d;
    }
    
    .no-data {
      text-align: center;
      padding: 40px;
      color: #666;
    }
    .table-responsive {
      overflow-x: auto;
    }
    .page-size-selector {
      margin: 15px 0;
      font-size: 13px;
    }
    .pagination-info {
      margin-top: 15px;
      font-size: 13px;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      align-items: center;
    }
    .user-info-section {
      margin-bottom: 15px;
    }
    .user-info-item {
      margin-bottom: 5px;
    }
    .required-field::after {
      content: " *";
      color: red;
    }
    .work-description-label {
      color: #000;
    }
    .work-description-label::after {
      content: " *";
      color: red;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .form-row .form-group {
        min-width: 100%;
      }
      .filter-group {
        min-width: 100%;
      }
      .btn-filter {
        width: 100%;
      }
      .filters {
        flex-direction: column;
        align-items: stretch;
      }
      .page-size-selector {
        flex-direction: column;
        gap: 10px;
      }
      .page-size-selector > span {
        width: 100%;
        margin-top: 10px;
      }
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#">
        <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">AIMS-Office</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="../pages/dashboard.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pages/approval_leave.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Leaves</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pages/staff.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Staff</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="../pages/overtime.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-watch-time text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Overtime</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link collapsed" data-bs-toggle="collapse" href="#settingsMenu" role="button" aria-expanded="false" aria-controls="settingsMenu">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-settings text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Settings</span>
          </a>
          <div class="collapse" id="settingsMenu">
            <ul class="nav ms-4 ps-3">
              <li class="nav-item">
                <a class="nav-link" href="../pages/company.php">
                  <span class="sidenav-normal">Company</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../pages/department.php">
                  <span class="sidenav-normal">Department</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../pages/roles.php">
                  <span class="sidenav-normal">Roles</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../pages/workshift.php">
                  <span class="sidenav-normal">Work Shift</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pages/profile.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <main class="main-content position-relative border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Overtime Permission</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Overtime Permission</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group">
              <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
            <!-- <input type="text" class="form-control" placeholder="Type here...">-->
            </div>
          </div>
          <ul class="navbar-nav  justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white font-weight-bold px-0">
                <i class="fa fa-user me-sm-1"></i>
                <span class="d-sm-inline d-none">#</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <?php 
      // Simulate URL parameter for success message
      $show_success = isset($_GET['success']) || true; // Force showing for demo
      if ($show_success): ?>
        <div class="alert alert-success text-white">
          Overtime permission berhasil ditambahkan!
        </div>
      <?php endif; ?>

      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between">
                <h6>Overtime Permission</h6>
                <?php 
                // Simulate action parameter
                $action = isset($_GET['action']) ? $_GET['action'] : 'list';
                if ($action == 'list'): ?>
                  <a href="?action=create" class="btn btn-sm btn-primary">Add Permission</a>
                <?php else: ?>
                  <a href="?action=list" class="btn btn-sm btn-primary">← Back to list</a>
                <?php endif; ?>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <?php if ($action == 'list'): ?>
                <div class="px-4">
                  <div class="user-info-section">
                    <div class="user-info-item"><strong>Name</strong> : #</div>
                    <div class="user-info-item"><strong>IC No</strong> : #</div>
                    <div class="user-info-item"><strong>Staff No</strong> : #</div>
                    <div class="user-info-item"><strong>Company</strong> : AGIRSB</div>
                    <div class="user-info-item"><strong>Department</strong> : IT</div>
                  </div>

                  <div class="filters">
                    <div class="filter-group">
                      <label>Month</label>
                      <select name="month" class="form-control" onchange="filterData()">
                        <option value="">All Month</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5" selected>May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                      </select>
                    </div>
                    <div class="filter-group">
                      <label>Year</label>
                      <select name="year" class="form-control" onchange="filterData()">
                        <option value="2023">2023</option>
                        <option value="2024" selected>2024</option>
                        <option value="2025">2025</option>
                      </select>
                    </div>
                    <button class="btn btn-primary btn-filter" onclick="filterData()">Filter</button>
                    <button class="btn btn-secondary btn-filter" onclick="resetFilter()">Reset</button>
                  </div>
                </div>

                <div class="table-responsive p-0">
                  <div class="page-size-selector px-4">
                    Papar <select class="form-control form-control-sm d-inline-block w-auto" onchange="changePageSize()">
                      <option>10</option>
                      <option>25</option>
                      <option>50</option>
                    </select> rekod
                    <span class="float-end">
                      Carian: <input type="text" class="form-control form-control-sm d-inline-block w-auto" placeholder="Search...">
                    </span>
                  </div>

                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mode</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Approval</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Work Description</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Remark</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      // Dummy data for permissions
                      $permissions = [
                        [
                          'mode' => 'WORKING DAY',
                          'date' => '2024-05-15',
                          'approval' => 'Manager IT',
                          'work_description' => 'System maintenance and updates',
                          'status' => 'Approved',
                          'remark' => 'Completed'
                        ],
                        [
                          'mode' => 'WEEKEND',
                          'date' => '2024-05-18',
                          'approval' => 'Head of Department',
                          'work_description' => 'Server migration project',
                          'status' => 'Pending',
                          'remark' => 'Waiting for HR approval'
                        ],
                        [
                          'mode' => 'PUBLIC HOLIDAY',
                          'date' => '2024-05-01',
                          'approval' => 'Senior Manager',
                          'work_description' => 'Emergency system outage resolution',
                          'status' => 'Rejected',
                          'remark' => 'Not enough justification'
                        ]
                      ];
                      
                      if (empty($permissions)): 
                      ?>
                        <tr>
                          <td colspan="8" class="no-data">Tiada data</td>
                        </tr>
                      <?php else: ?>
                        <?php foreach ($permissions as $i => $permission): ?>
                        <tr>
                          <td class="ps-4">
                            <p class="text-xs font-weight-bold mb-0"><?php echo $i + 1; ?></p>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0"><?php echo $permission['mode']; ?></p>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0"><?php echo date('d/m/Y', strtotime($permission['date'])); ?></p>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0"><?php echo $permission['approval']; ?></p>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0"><?php echo $permission['work_description']; ?></p>
                          </td>
                          <td>
                            <?php if ($permission['status'] == 'Approved'): ?>
                              <span class="badge badge-success"><?php echo $permission['status']; ?></span>
                            <?php elseif ($permission['status'] == 'Pending'): ?>
                              <span class="badge badge-warning"><?php echo $permission['status']; ?></span>
                            <?php else: ?>
                              <span class="badge badge-danger"><?php echo $permission['status']; ?></span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <p class="text-xs font-weight-bold mb-0"><?php echo $permission['remark']; ?></p>
                          </td>
                          <td class="align-middle">
                            <button class="btn btn-xs btn-primary mb-0">View</button>
                            <button class="btn btn-xs btn-secondary mb-0">Edit</button>
                            <button class="btn btn-xs btn-danger mb-0">Delete</button>
                          </td>
                        </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>

                  <div class="pagination-info px-4 py-3">
                    <p class="mb-0">Papar 1 hingga 3 daripada 3 rekod</p>
                    <div>
                      <button class="btn btn-sm btn-outline-secondary mb-0">Sebelum</button>
                      <button class="btn btn-sm btn-outline-secondary mb-0">Seterusnya</button>
                    </div>
                  </div>
                </div>

              <?php elseif ($action == 'create'): ?>
                <div class="px-4 py-3">
                  <form method="POST">
                    <div class="form-row">
                      <div class="form-group">
                        <label class="required-field">DATE</label>
                        <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                      </div>
                      <div class="form-group">
                        <label class="required-field">APPLICANT</label>
                        <input type="text" name="applicant" class="form-control" value="NURUL ATHIRA BINTI ZULKIFLI" readonly>
                      </div>
                      <div class="form-group">
                        <label class="required-field">APPROVAL</label>
                        <select name="approval" class="form-control" required>
                          <option value="">-- HOD/SBU NAME --</option>
                          <option value="Manager IT">Manager IT</option>
                          <option value="Head of Department">Head of Department</option>
                          <option value="Senior Manager">Senior Manager</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group">
                        <label class="required-field">DEPARTMENT</label>
                        <input type="text" name="department" class="form-control" value="IT" readonly>
                      </div>
                      <div class="form-group">
                        <label class="required-field">MODE</label>
                        <select name="mode" class="form-control" required>
                          <option value="">Select Mode</option>
                          <option value="WORKING DAY">WORKING DAY</option>
                          <option value="WEEKEND">WEEKEND</option>
                          <option value="PUBLIC HOLIDAY">PUBLIC HOLIDAY</option>
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="work-description-label">WORK DESCRIPTION</label>
                      <textarea name="work_description" class="form-control" rows="4" placeholder="Describe the work to be performed..." required>System upgrade and database migration for the new financial module.</textarea>
                    </div>

                    <div class="reminder">
                      <h4>REMINDER :</h4>
                      <ol>
                        <li>THIS FORM MUST BE SUBMITTED BY YOUR LEAVE APPROVAL / HEAD OF DEPARTMENT ON SAME DAY OF OVERTIME WORK</li>
                        <li>THIS FORM WILL BE LINKED TO OVERTIME CLAIM FOR HUMAN RESOURCE DEPARTMENT REVIEW</li>
                      </ol>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                      <button type="button" class="btn btn-secondary me-2" onclick="window.location='?action=list'">Reset</button>
                      <button type="submit" name="submit" class="btn btn-success">Submit</button>
                    </div>
                  </form>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
  <!-- Core JS Files -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <!-- Control Center for Soft Dashboard -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
  <script>
    function filterData() {
      const month = document.querySelector('select[name="month"]').value;
      const year = document.querySelector('select[name="year"]').value;
      window.location = `?action=list&month=${month}&year=${year}`;
    }
    
    function resetFilter() {
      window.location = '?action=list';
    }
    
    function changePageSize() {
      const pageSize = document.querySelector('.page-size-selector select').value;
      alert(`Page size changed to ${pageSize} records (simulated)`);
    }
    
    // For demo purposes, handle form submission
    document.querySelector('form')?.addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Form submitted successfully! (simulated)');
      window.location.href = '?action=list&success=1';
    });
  </script>
</body>
</html>