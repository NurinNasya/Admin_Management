<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    IN/OUT 
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
  <style>
    /* General Mobile First Styles */
    .form-container {
      padding: 15px;
    }
    
    .form-row {
      display: flex;
      flex-direction: column;
      gap: 15px;
      margin-bottom: 15px;
    }
    
    .form-group {
      width: 100%;
    }
    
    .form-group.full-width {
      width: 100%;
    }
    
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      color: #555;
    }
    
    .required {
      color: #e74c3c;
    }
    
    input[type="date"],
    input[type="time"],
    input[type="file"],
    textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
      box-sizing: border-box;
    }
    
    input[type="date"]:focus,
    input[type="time"]:focus,
    input[type="file"]:focus,
    textarea:focus {
      outline: none;
      border-color: #007bff;
      box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
    }
    
    textarea {
      resize: vertical;
      min-height: 100px;
    }
    
    .file-info {
      font-size: 12px;
      color: #666;
      margin-top: 5px;
    }
    
    .submit-btn {
      background-color: #007bff;
      color: white;
      padding: 12px 30px;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
      width: 100%;
      transition: background-color 0.3s;
    }
    
    .submit-btn:hover {
      background-color: #0056b3;
    }
    
    .success-message {
      background-color: #d4edda;
      color: #155724;
      padding: 12px;
      border-radius: 4px;
      margin-bottom: 20px;
      border: 1px solid #c3e6cb;
    }
    
    .error-message {
      background-color: #f8d7da;
      color: #721c24;
      padding: 12px;
      border-radius: 4px;
      margin-bottom: 20px;
      border: 1px solid #f5c6cb;
    }
    
    /* Mobile Navigation */
    .mobile-menu-btn {
      display: none;
      background: none;
      border: none;
      color: white;
      font-size: 24px;
      cursor: pointer;
      padding: 10px;
    }
    
    /* Sidebar adjustments for mobile */
    @media (max-width: 991.98px) {
      .sidenav {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        position: fixed;
        z-index: 1031;
        height: 100%;
        overflow-y: auto;
      }
      
      .sidenav.show {
        transform: translateX(0);
      }
      
      .mobile-menu-btn {
        display: block;
      }
      
      .main-content {
        margin-left: 0;
      }
      
      .navbar-brand {
        margin-left: 40px;
      }
    }
    
    /* Tablet and Desktop Styles */
    @media (min-width: 768px) {
      .form-container {
        padding: 20px;
      }
      
      .form-row {
        flex-direction: row;
        gap: 20px;
        margin-bottom: 20px;
      }
      
      .form-group {
        flex: 1;
        min-width: 200px;
      }
    }
    
    /* Large Desktop Styles */
    @media (min-width: 1200px) {
      .form-container {
        max-width: 1200px;
        margin: 0 auto;
      }
    }
    
    /* Print Styles */
    @media print {
      .sidenav, .navbar, .footer {
        display: none;
      }
      
      .main-content {
        margin-left: 0;
        width: 100%;
      }
      
      .card {
        border: none;
        box-shadow: none;
      }
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  
  <!-- Mobile Menu Button -->
  <button class="mobile-menu-btn d-lg-none" id="mobileMenuBtn">
    <i class="fas fa-bars"></i>
  </button>
  
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html " target="_blank">
        <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">IN/OUT System</span>
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
          <a class="nav-link active" href="../pages/inout.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-watch-time text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">IN/OUT</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages/approval_leave.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Leaves</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages/staff.php">
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
          <a class="nav-link " href="../pages/profile.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer mx-3 ">
      <div class="card card-plain shadow-none" id="sidenavCard"></div>
    </div>
  </aside>
  
  <main class="main-content position-relative border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">IN/OUT</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">IN/OUT Tracking</h6>
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
                <i class="fa fa-user me-sm-1"></i>
                <span class="d-sm-inline d-none">Sign In</span>
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
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <h6>IN/OUT </h6>
            </div>
            <div class="card-body">
              <div class="form-container">
                <?php if (isset($success_message)): ?>
                  <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
                <?php endif; ?>
                
                <?php if (isset($upload_message)): ?>
                  <div class="<?php echo strpos($upload_message, 'successfully') !== false ? 'success-message' : 'error-message'; ?>">
                    <?php echo htmlspecialchars($upload_message); ?>
                  </div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data">
                  <div class="form-row">
                    <div class="form-group">
                      <label for="date">Date: <span class="required">*</span></label>
                      <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    
                    <div class="form-group">
                      <label for="time_out">Time Out: <span class="required">*</span></label>
                      <input type="time" id="time_out" name="time_out" value="08:00" required>
                    </div>
                    
                    <div class="form-group">
                      <label for="time_in">Time In: <span class="required">*</span></label>
                      <input type="time" id="time_in" name="time_in" value="10:00" required>
                    </div>
                  </div>
                  
                  <div class="form-row">
                    <div class="form-group full-width">
                      <label for="description">Description: <span class="required">*</span></label>
                      <textarea id="description" name="description" placeholder="Enter description..." required></textarea>
                    </div>
                  </div>
                  
                  <div class="form-row">
                    <div class="form-group full-width">
                      <label for="attachment">Attachment (pdf/jpg/jpeg/png/gif/pdf, Max: 4MB)</label>
                      <input type="file" id="attachment" name="attachment" accept=".pdf,.jpg,.jpeg,.png,.gif">
                      <div class="file-info">Supported formats: PDF, JPG, JPEG, PNG, GIF (Maximum size: 4MB)</div>
                    </div>
                  </div>
                  
                  <div class="form-row">
                    <div class="form-group full-width">
                      <button type="submit" class="submit-btn">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
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
                IN/OUT 
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </main>
  
  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    // Mobile menu toggle
    document.getElementById('mobileMenuBtn').addEventListener('click', function() {
      document.getElementById('sidenav-main').classList.toggle('show');
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
      const sidenav = document.getElementById('sidenav-main');
      const mobileBtn = document.getElementById('mobileMenuBtn');
      
      if (!sidenav.contains(event.target) && event.target !== mobileBtn) {
        sidenav.classList.remove('show');
      }
    });
    
    // Initialize scrollbar if needed
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>