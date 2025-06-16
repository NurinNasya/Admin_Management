<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>
        Overtime Approval - Argon Dashboard
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
        /* Mobile Responsive Adjustments */
        @media (max-width: 767.98px) {
            .sidenav {
                display: none;
            }
            .main-content {
                margin-left: 0;
            }
            .navbar-main {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .card-header {
                padding: 1rem;
            }
            .form-label {
                font-size: 0.9rem;
            }
            .card-body {
                padding: 1rem !important;
            }
            .row.mb-3 > div {
                margin-bottom: 1rem;
            }
            .btn {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            .text-end {
                text-align: left !important;
            }
            .col-md-4 {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            .container-fluid.py-4 {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .alert {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
        }
        
        /* Desktop Enhancements */
        @media (min-width: 768px) {
            .card-body {
                padding: 2rem !important;
            }
            .form-label {
                margin-bottom: 0.5rem;
            }
        }
        
        /* General Improvements */
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
        }
        .text-danger {
            color: #dc3545 !important;
        }
        .form-control, .form-select {
            margin-bottom: 0.5rem;
        }
        textarea.form-control {
            min-height: 100px;
        }
    </style>
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href="dashboard.html" target="_blank">
                <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100"
                    alt="main_logo">
                <span class="ms-1 font-weight-bold">Creative Tim</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="approval_leave.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Leaves</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="staff.php">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Staff</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-toggle="collapse" href="#settingsMenu" role="button"
                        aria-expanded="false" aria-controls="settingsMenu">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-settings text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Settings</span>
                    </a>
                    <div class="collapse" id="settingsMenu">
                        <ul class="nav ms-4 ps-3">
                            <li class="nav-item">
                                <a class="nav-link" href="company.php">
                                    <span class="sidenav-normal">Company</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="department.php">
                                    <span class="sidenav-normal">Department</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="roles.php">
                                    <span class="sidenav-normal">Roles</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="workshift.php">
                                    <span class="sidenav-normal">Work Shift</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="rtl.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-world-2 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">RTL</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="profile.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sign-in.html">
                        <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Sign In</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sign-up.html">
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
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Overtime Approval</li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Overtime Approval</h6>
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
                            <h6>Overtime Approval Form</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <?php if (isset($success_message)): ?>
                                <div class="alert alert-success mx-3 mx-md-4" role="alert">
                                    <?php echo $success_message; ?>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($error_message)): ?>
                                <div class="alert alert-danger mx-3 mx-md-4" role="alert">
                                    <?php echo $error_message; ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" class="mx-3 mx-md-4">
                                <div class="row mb-3">
                                    <div class="col-md-4 col-12">
                                        <label for="date" class="form-label">DATE</label>
                                        <input type="date" class="form-control" id="date" name="date"
                                            value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="applicant" class="form-label">APPLICANT</label>
                                        <input type="text" class="form-control" id="applicant" name="applicant"
                                            placeholder="Enter applicant name" required>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="approval" class="form-label">APPROVAL <span class="text-danger">*</span></label>
                                        <select class="form-select" id="approval" name="approval" required>
                                            <option value="">-- HOD/SBU NAME --</option>
                                            <?php foreach ($approvalStaff as $staff): ?>
                                                <option value="<?php echo $staff['id']; ?>">
                                                    <?php echo htmlspecialchars($staff['name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4 col-12">
                                        <label for="department" class="form-label">DEPARTMENT</label>
                                        <input type="text" class="form-control" id="department" name="department"
                                            placeholder="Enter department" required>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="mode" class="form-label">MODE <span class="text-danger">*</span></label>
                                        <select class="form-select" id="mode" name="mode" required>
                                            <option value="">Select mode</option>
                                            <option value="WORKING DAY">WORKING DAY</option>
                                            <option value="OFF-DAY/WEEKEND">OFF-DAY/WEEKEND</option>
                                            <option value="PUBLIC HOLIDAY">PUBLIC HOLIDAY</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <label for="work_description" class="form-label">WORK DESCRIPTION <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="work_description" name="work_description"
                                            rows="3" placeholder="Enter work description" required></textarea>
                                    </div>
                                </div>

                                <div class="card mt-4">
                                    <div class="card-body">
                                        <h6 class="card-title">REMINDER :</h6>
                                        <ol class="text-sm">
                                            <li>THIS FORM MUST BE SUBMITTED BY YOUR LEAVE APPROVAL / HEAD OF DEPARTMENT
                                                ON SAME DAY OF OVERTIME WORK.</li>
                                            <li>THIS FORM WILL BE LINKED TO OVERTIME CLAIM FOR HUMAN RESOURCE DEPARTMENT
                                                REVIEW.</li>
                                        </ol>
                                    </div>
                                </div>

                                <div class="row mt-4">
                                <div class="col-12 text-end">
                                <div class="d-flex justify-content-end gap-2">
            <button type="reset" class="btn btn-outline-secondary py-2" style="width: 120px;">Reset</button>
            <button type="submit" class="btn btn-primary py-2" style="width: 120px;">Submit</button>
        </div>
    </div>
</div>
            <footer class="footer pt-3">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                ©
                                <script>document.write(new Date().getFullYear())</script>,
                                made with <i class="fa fa-heart"></i> by
                                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative
                                    Tim</a>
                                for a better web.
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
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>