<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    MSET-Office - IN/OUT System
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
  <style>
   
  /* IN/OUT Styles */
.employee-info {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  height: fit-content;
  margin-bottom: 20px;
}

.employee-details {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 15px;
  margin-bottom: 20px;
}

.detail-item {
  display: flex;
  gap: 10px;
}

.detail-label {
  font-weight: 600;
  min-width: 80px;
  color: #555;
}

.year-selector {
  margin: 20px 0;
}

.year-selector select {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
  margin-left: 10px;
}

.notice {
  background-color: #fff3cd;
  border: 1px solid #ffeaa7;
  color: #856404;
  padding: 15px;
  border-radius: 4px;
  margin-top: 15px;
}

/* Compact Quota Table */
.quota-section {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  height: fit-content;
}

.quota-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 14px;
}

.quota-table th {
  background-color: #34495e;
  color: white;
  padding: 10px 12px;
  text-align: center;
  font-weight: 600;
  font-size: 13px;
  border-right: 1px solid #2c3e50;
}

.quota-table th:last-child {
  border-right: none;
}

.quota-table td {
  padding: 8px 12px;
  text-align: center;
  border-bottom: 1px solid #ecf0f1;
  border-right: 1px solid #ecf0f1;
  font-size: 13px;
}

.quota-table td:last-child {
  border-right: none;
}

.month-cell {
  background-color: #95a5a6;
  color: white;
  font-weight: 600;
  text-align: left;
  padding-left: 15px;
}

.quota-cell {
  background-color: #3498db;
  color: white;
  font-weight: 500;
}

.used-cell {
  color: #7f8c8d;
  font-style: italic;
}

.progress-cell {
  color: #f39c12;
  font-weight: 500;
}

.available-cell {
  background-color: #27ae60;
  color: white;
  font-weight: 600;
}

/* Permission List */
.permission-section {
  background-color: white;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  padding: 20px;
  margin-top: 20px;
}

.permission-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  flex-wrap: wrap;
}

.permission-controls {
  display: flex;
  gap: 10px;
  align-items: center;
  flex-wrap: wrap;
}

.permission-controls select,
.permission-controls input {
  padding: 8px 12px;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.permission-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 15px;
  overflow-x: auto;
  display: block;
}

.permission-table th {
  background-color: #ecf0f1;
  padding: 12px;
  text-align: left;
  font-weight: 600;
  color: #2c3e50;
  border-bottom: 2px solid #bdc3c7;
}

.permission-table td {
  padding: 12px;
  border-bottom: 1px solid #ecf0f1;
}

.no-data {
  text-align: center;
  color: #95a5a6;
  padding: 40px;
  font-style: italic;
}

.pagination {
  margin-top: 15px;
  color: #666;
  font-size: 14px;
}

/* Modal */
.modal {
  display: none;
  position: fixed;
  z-index: 9999 !important;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  overflow-y: auto;
}

.modal-content {
  background-color: white;
  margin: 5% auto;
  border-radius: 8px;
  width: 500px;
  max-width: 95%;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
  position: relative;
  z-index: 10000;
}

.modal-header {
  background-color: #3498db;
  color: white;
  padding: 20px;
  border-radius: 8px 8px 0 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-header h3 {
  margin: 0;
  font-size: 18px;
  color: white;
}

.close {
  color: white;
  font-size: 24px;
  font-weight: bold;
  cursor: pointer;
  line-height: 1;
}

.close:hover {
  opacity: 0.7;
}

.modal-body {
  padding: 20px;
}

.form-group {
  margin-bottom: 15px;
}

.form-row {
  display: flex;
  gap: 15px;
}

.form-row .form-group {
  flex: 1;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 600;
  color: #555;
}

.required {
  color: #e74c3c;
}

.form-group input,
.form-group textarea,
.form-group select {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 14px;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
  outline: none;
  border-color: #3498db;
  box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
}

.form-group textarea {
  min-height: 80px;
  resize: vertical;
}

.file-info {
  font-size: 12px;
  color: #666;
  margin-top: 5px;
}

.submit-btn {
  background-color: #3498db;
  color: white;
  padding: 12px 30px;
  border: none;
  border-radius: 4px;
  font-size: 16px;
  cursor: pointer;
  width: 100%;
  font-weight: 500;
}

.submit-btn:hover {
  background-color: #2980b9;
}

/* Main Content Adjustments */
.main-content {
  margin-left: 20px;
  transition: margin-left 0.3s ease;
}

.g-sidenav-pinned .main-content {
  margin-left: 280px;
}

/* Custom buttons */
.version-switch {
  background-color: #34495e;
  color: white;
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 12px;
}

.new-inout-btn {
  background-color: #3498db;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
  white-space: nowrap;
}

.new-inout-btn:hover {
  background-color: #2980b9;
}

.version-badge {
  background-color: #3498db;
  color: white;
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: 500;
}

/* Force hide sidebar when modal is open */
.modal-open .sidenav {
  transform: translateX(-100%) !important;
  visibility: hidden !important;
}

/* Responsive Styles */
@media (max-width: 1200px) {
  .main-content {
    margin-left: 0;
  }

  .g-sidenav-pinned .main-content {
    margin-left: 0;
  }
}

@media (max-width: 992px) {
  .employee-details {
    grid-template-columns: 1fr 1fr;
  }

  .permission-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }

  .permission-controls {
    width: 100%;
    justify-content: space-between;
  }
}

@media (max-width: 768px) {
  .employee-details {
    grid-template-columns: 1fr;
  }

  .form-row {
    flex-direction: column;
    gap: 0;
  }

  .quota-table {
    font-size: 12px;
  }

  .quota-table th,
  .quota-table td {
    padding: 6px 8px;
  }

  .modal-content {
    margin: 20px auto;
  }
}

@media (max-width: 576px) {
  .container-fluid {
    padding-left: 15px;
    padding-right: 15px;
  }

  .page-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 15px;
  }

  .permission-controls {
    flex-direction: column;
    align-items: flex-start;
  }

  .quota-table {
    display: block;
    overflow-x: auto;
  }
}

  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html" target="_blank">
        <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">MSET-Office</span>
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
          <a class="nav-link active" href="../pages/inout.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fas fa-clock text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">In/Out</span>
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
        <li class="nav-item">
          <a class="nav-link" href="../pages/sign-in.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Sign In</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pages/sign-up.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-collection text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Sign Up</span>
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
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">In/Out</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">In/Out System</h6>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            <div class="input-group">
              <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
              <input type="text" class="form-control" placeholder="Type here...">
            </div>
          </div>
          <ul class="navbar-nav justify-content-end">
            <li class="nav-item d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white font-weight-bold px-0">
               
              </a>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0">
                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
              </a>
            </li>
            <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3">
                      </div>
                      
                <li class="mb-2">
                  <a class="dropdown-item border-radius-md" href="javascript:;">
                    <div class="d-flex py-1">
                      <div class="my-auto">
                        <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark me-3">
                      </div>
                      <div class="d-flex flex-column justify-content-center">
                        <h6 class="text-sm font-weight-normal mb-1">
                         
                        </h6>
                        <p class="text-xs text-secondary mb-0">
                          <i class="fa fa-clock me-1"></i>
                          
                        </p>
                      </div>
                    </div>
                  </a>
                </li>
                <li>
                 
    </nav>
    <!-- End Navbar -->
    
    <div class="container-fluid py-4">
      <!-- Page Header -->
      <div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
        <div class="page-title">
          <h1>IN/OUT</h1>
        </div>
        <div>
          <button class="new-inout-btn" onclick="openModal()">New In/out</button>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="row">
        <!-- Left Column - Employee Info -->
        <div class="col-lg-8 mb-4">
          <div class="employee-info">
            <div class="employee-details">
              <div class="detail-item">
                <span class="detail-label">Name</span>
                <span>#</span>
              </div>
              <div class="detail-item">
                <span class="detail-label">IC Number</span>
                <span>#</span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Department</span>
                <span>AGIRSB</span>
              </div>
              <div class="detail-item">
                <span class="detail-label">Unit</span>
                <span>-</span>
              </div>
            </div>

            <div class="year-selector">
              <label for="year-select">Show info for year:</label>
              <select id="year-select">
                <option value="2025" selected>2025</option>
                <option value="2024">2024</option>
              </select>
            </div>

            <div class="notice">
              Every staff is eligible to apply in/out for medical purpose 2 times each month with maximum period of 2 hours
              for each application
            </div>
          </div>
        </div>

        <!-- Right Column - Quota Table -->
        <div class="col-lg-4 mb-4">
          <div class="quota-section">
            <table class="quota-table">
              <thead>
                <tr>
                  <th style="width: 20%;">Month</th>
                  <th style="width: 20%;">In/Out Quota</th>
                  <th style="width: 20%;">Used</th>
                  <th style="width: 20%;">In Progress</th>
                  <th style="width: 20%;">Available</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="month-cell">June 2025</td>
                  <td class="quota-cell">2 x 2.0 hours</td>
                  <td class="used-cell">No usage</td>
                  <td class="progress-cell">No usage</td>
                  <td class="available-cell">2 x 2.0 hours</td>
                </tr>
                <tr>
                  <td class="month-cell">May 2025</td>
                  <td class="quota-cell">2 x 2.0 hours</td>
                  <td class="used-cell">No usage</td>
                  <td class="progress-cell">No usage</td>
                  <td class="available-cell">2 x 2.0 hours</td>
                </tr>
                <tr>
                  <td class="month-cell">April 2025</td>
                  <td class="quota-cell">2 x 2.0 hours</td>
                  <td class="used-cell">No usage</td>
                  <td class="progress-cell">No usage</td>
                  <td class="available-cell">2 x 2.0 hours</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Permission List -->
      <div class="row">
        <div class="col-12">
          <div class="permission-section">
            <h3>In/Out Permission List</h3>

            <div class="permission-header">
              <div class="permission-controls">
                <div>
                  <label>Papar:</label>
                  <select>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                  </select>
                  <span>rekod</span>
                </div>
              </div>
              <div class="permission-controls">
                <label>Carian:</label>
                <input type="text" placeholder="Search...">
              </div>
            </div>

            <table class="permission-table">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Applied On</th>
                  <th>In/Out Info</th>
                  <th>Support</th>
                  <th>Approval</th>
                  <th>HR Review</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td colspan="7" class="no-data">Tiada data</td>
                </tr>
              </tbody>
            </table>

            <div class="pagination">
              Papar 0 hingga 0 daripada 0 rekod
            </div>
          </div>
        </div>
      </div>
      
      <footer class="footer pt-3">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                for a better web.
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>

  <!-- Modal -->
<div id="inoutModal" class="modal" onclick="event.stopPropagation()">
  <div class="modal-content" onclick="event.stopPropagation()">
    <div class="modal-header">
      <h3>In/Out Form</h3>
      <span class="close" onclick="closeModal()">&times;</span>
    </div>
    <div class="modal-body">
      <form id="inoutForm">
        <div class="form-group">
          <label>Date: <span class="required">*</span></label>
          <input type="date" name="date" required>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Time Out: <span class="required">*</span></label>
            <input type="time" name="time_out" required value="08:00">
          </div>
          <div class="form-group">
            <label>Time In: <span class="required">*</span></label>
            <input type="time" name="time_in" required value="10:00">
          </div>
        </div>
        <div class="form-group">
          <label>Description: <span class="required">*</span></label>
          <textarea name="description" required placeholder="Enter description..."></textarea>
        </div>
        <div class="form-group">
          <label>Attachment (pdf/jpg/jpeg/png/heic/heif, Max: 4MB)</label>
          <input type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.heic,.heif">
          <div class="file-info">Maximum file size: 4MB</div>
        </div>
        <button type="submit" class="submit-btn">Submit</button>
      </form>
    </div>
  </div>
</div>

 <!--   Core JS Files   -->
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="../assets/js/plugins/chartjs.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  // Set current date as default
  const dateInput = document.querySelector('input[name="date"]');
  if (dateInput) {
    dateInput.value = new Date().toISOString().split('T')[0];
  }

  // Variable untuk simpan status asal sidebar
  let sidebarOriginallyOpen = false;

  // Sidebar toggle functionality
  const sidebarToggle = document.getElementById('iconNavbarSidenav');
  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function () {
      document.body.classList.toggle('g-sidenav-pinned');
      document.body.classList.toggle('g-sidenav-show');
    });
  }

  // Modal functions
  window.openModal = function () {
    sidebarOriginallyOpen = document.body.classList.contains('g-sidenav-show') ||
                            document.body.classList.contains('g-sidenav-pinned');

    // Hide sidebar
    document.body.classList.remove('g-sidenav-show', 'g-sidenav-pinned');
    document.body.classList.add('modal-open');

    // Show modal after short delay
    setTimeout(() => {
      const modal = document.getElementById('inoutModal');
      if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
      }
    }, 100);
  }

  window.closeModal = function () {
    const modal = document.getElementById('inoutModal');
    if (modal) {
      modal.style.display = 'none';
    }

    document.body.style.overflow = 'auto';
    document.body.classList.remove('modal-open');

    if (sidebarOriginallyOpen) {
      document.body.classList.add('g-sidenav-show', 'g-sidenav-pinned');
    }
  }

  // Close modal if clicking outside content
  window.onclick = function (event) {
    const modal = document.getElementById('inoutModal');
    if (modal && event.target === modal) {
      closeModal();
    }
  }

  // Handle form submission
  const form = document.getElementById('inoutForm');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(this);
      const data = {
        date: formData.get('date'),
        time_out: formData.get('time_out'),
        time_in: formData.get('time_in'),
        description: formData.get('description'),
        attachment: formData.get('attachment')
      };

      alert('In/Out request submitted successfully!');
      closeModal();

      // Reset form & set date again
      this.reset();
      if (dateInput) {
        dateInput.value = new Date().toISOString().split('T')[0];
      }
    });
  }

  // Year select change handler
  const yearSelect = document.getElementById('year-select');
  if (yearSelect) {
    yearSelect.addEventListener('change', function () {
      console.log('Year changed to:', this.value);
      // Reload data if needed
    });
  }

  // Responsive table handler
  function handleResponsiveTables() {
    const tables = document.querySelectorAll('.quota-table, .permission-table');
    const screenWidth = window.innerWidth;

    tables.forEach(table => {
      if (screenWidth < 768) {
        table.style.display = 'block';
        table.style.overflowX = 'auto';
      } else {
        table.style.display = 'table';
        table.style.overflowX = '';
      }
    });
  }

  window.addEventListener('load', handleResponsiveTables);
  window.addEventListener('resize', handleResponsiveTables);
});
</script>

<script>
  var win = navigator.platform.indexOf('Win') > -1;
  if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
      damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
  }
</script>

<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>

<!-- Control Center for Soft Dashboard -->
<script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>
</html>
