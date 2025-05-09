<?php
include_once '../db.php';
session_start();

// Fetch all roles
$sql = "SELECT * FROM roles ORDER BY id DESC";
$result = $conn->query($sql);

// Generate CSRF token for security
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="../assets/img/favicon.png">
    <title>Roles Management</title>
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show bg-gray-100">
    <div class="min-height-300 bg-dark position-absolute w-100"></div>
    <aside
        class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 "
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html "
                target="_blank">
                <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100"
                    alt="main_logo">
                <span class="ms-1 font-weight-bold">Creative Tim</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0">
        <div class="collapse navbar-collapse w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../pages/dashboard.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="../pages/tables.html">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Tables</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="../pages/billing.html">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Billing</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="collapse" href="#settingsMenu" role="button"
                        aria-expanded="true" aria-controls="settingsMenu">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-settings text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Settings</span>
                    </a>
                    <div class="collapse show" id="settingsMenu">
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
                                <a class="nav-link active" href="../pages/role.php">
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
                <li class="nav-item">
                    <a class="nav-link " href="../pages/rtl.html">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-world-2 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">RTL</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="../pages/profile.html">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Profile</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="../pages/sign-in.html">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Sign In</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="../pages/sign-up.html">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-collection text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Sign Up</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="sidenav-footer mx-3 ">
            <div class="card card-plain shadow-none" id="sidenavCard">
                <img class="w-50 mx-auto" src="../assets/img/illustrations/icon-documentation.svg"
                    alt="sidebar_illustration">
                <div class="card-body text-center p-3 w-100 pt-0">
                    <div class="docs-info">
                        <h6 class="mb-0">Need help?</h6>
                        <p class="text-xs font-weight-bold mb-0">Please check our docs</p>
                    </div>
                </div>
            </div>
            <a href="https://www.creative-tim.com/learning-lab/bootstrap/license/argon-dashboard" target="_blank"
                class="btn btn-dark btn-sm w-100 mb-3">Documentation</a>
            <a class="btn btn-primary btn-sm mb-0 w-100"
                href="https://www.creative-tim.com/product/argon-dashboard-pro?ref=sidebarfree" type="button">Upgrade to
                pro</a>
        </div>
    </aside>

    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
            data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white"
                                href="javascript:;">Pages</a></li>
                        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Role Management
                        </li>
                    </ol>
                    <h6 class="font-weight-bolder text-white mb-0">Role Management</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group">
                            <span class="input-group-text text-body"><i class="fas fa-search"
                                    aria-hidden="true"></i></span>
                            <input type="text" class="form-control" placeholder="Type here...">
                        </div>
                    </div>
                    <ul class="navbar-nav  justify-content-end">
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
                        <li class="nav-item dropdown pe-2 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell cursor-pointer"></i>
                            </a>
                            <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4"
                                aria-labelledby="dropdownMenuButton">
                                <li class="mb-2">
                                    <a class="dropdown-item border-radius-md" href="javascript:;">
                                        <div class="d-flex py-1">
                                            <div class="my-auto">
                                                <img src="../assets/img/team-2.jpg" class="avatar avatar-sm  me-3 ">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal mb-1">
                                                    <span class="font-weight-bold">New message</span> from Laur
                                                </h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    <i class="fa fa-clock me-1"></i>
                                                    13 minutes ago
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            </ul>
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
                            <div class="d-flex justify-content-between">
                                <h6>Roles Management</h6>
                                <button class="btn btn-primary btn-sm mb-0" type="button" data-bs-toggle="modal"
                                    data-bs-target="#addRoleModal">Add New Role</button>
                            </div>
                        </div>

                        <!-- Alert Messages -->
                        <?php if (isset($_SESSION['message'])): ?>
                            <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                                <?= htmlspecialchars($_SESSION['message']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <?php unset($_SESSION['message']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                                <?= htmlspecialchars($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <?php unset($_SESSION['error']); ?>
                            </div>
                        <?php endif; ?>

                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                ID</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Name</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                Status</th>
                                            <th class="text-secondary opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($result->num_rows > 0): ?>
                                            <?php while ($row = $result->fetch_assoc()): ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex px-3 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm"><?= htmlspecialchars($row['id']); ?>
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="text-sm font-weight-bold mb-0">
                                                            <?= htmlspecialchars($row['name']); ?></p>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-sm <?= $row['status'] ? 'bg-gradient-success' : 'bg-gradient-secondary' ?>">
                                                            <?= $row['status'] ? "Active" : "Inactive" ?>
                                                        </span>
                                                    </td>
                                                    <td class="align-middle">
                                                        <button class="btn btn-link text-secondary mb-0 edit-role-btn"
                                                            data-id="<?= $row['id']; ?>"
                                                            data-name="<?= htmlspecialchars($row['name']); ?>"
                                                            data-status="<?= $row['status']; ?>" data-bs-toggle="modal"
                                                            data-bs-target="#editRoleModal">
                                                            <i class="fa fa-edit text-xs"></i>
                                                        </button>
                                                        <button class="btn btn-link text-danger mb-0 delete-role-btn"
                                                            data-id="<?= $row['id']; ?>"
                                                            data-name="<?= htmlspecialchars($row['name']); ?>"
                                                            data-bs-toggle="modal" data-bs-target="#deleteRoleModal">
                                                            <i class="fa fa-trash text-xs"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="text-center">No roles found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Role Modal -->
            <div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addRoleModalLabel">Add New Role</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="crudrole.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Role Name</label>
                                    <input class="form-control" type="text" id="name" name="name" required
                                        maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="status" class="form-control-label">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="add_role" class="btn btn-primary">Add Role</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Role Modal -->
            <div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="crudrole.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                            <input type="hidden" id="edit_role_id" name="role_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="edit_name" class="form-control-label">Role Name</label>
                                    <input class="form-control" type="text" id="edit_name" name="name" required
                                        maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="edit_status" class="form-control-label">Status</label>
                                    <select class="form-control" id="edit_status" name="status" required>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" name="update_role" class="btn btn-primary">Update Role</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Role Modal -->
            <div class="modal fade" id="deleteRoleModal" tabindex="-1" role="dialog"
                aria-labelledby="deleteRoleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteRoleModalLabel">Delete Role</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="crudrole.php" method="POST">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
                            <input type="hidden" id="delete_role_id" name="role_id">
                            <div class="modal-body">
                                <p>Are you sure you want to delete the role: <strong><span
                                            id="delete_role_name"></span></strong>?</p>
                                <p class="text-danger">This action cannot be undone.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" name="delete_role" class="btn btn-danger">Delete</button>
                            </div>
                        </form>
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
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Script to handle edit role modal data
        document.addEventListener('DOMContentLoaded', function () {
            // Edit Role Button Handler
            var editButtons = document.querySelectorAll('.edit-role-btn');
            editButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var id = this.getAttribute('data-id');
                    var name = this.getAttribute('data-name');
                    var status = this.getAttribute('data-status');

                    document.getElementById('edit_role_id').value = id;
                    document.getElementById('edit_name').value = name;
                    document.getElementById('edit_status').value = status;
                });
            });

            // Delete Role Button Handler
            var deleteButtons = document.querySelectorAll('.delete-role-btn');
            deleteButtons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var id = this.getAttribute('data-id');
                    var name = this.getAttribute('data-name');

                    document.getElementById('delete_role_id').value = id;
                    document.getElementById('delete_role_name').textContent = name;
                });
            });
        });
    </script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>

</html>
<?php
// Close connection
$conn->close();
?>