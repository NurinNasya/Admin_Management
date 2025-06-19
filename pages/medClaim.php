<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Argon Dashboard 3 by Creative Tim
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
</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html " target="_blank">
        <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">Creative Tim</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="../pages/dashboard.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages/tables.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Tables</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'staff.php') ? 'active' : ''; ?>" href="../pages/staff.php">
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
          <span class="nav-link-text ms-1">Settings</span> <!-- copy ini-->
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
        <!--<li class="nav-item">
          <a class="nav-link " href="../pages/virtual-reality.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-app text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Virtual Reality</span>
          </a>
        </li>-->
        <li class="nav-item">
          <a class="nav-link " href="../pages/rtl.html">
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
          <a class="nav-link " href="../pages/profile.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages/sign-in.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Sign In</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="../pages/sign-up.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-collection text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Sign Up</span>
          </a>
        </li>
      </ul>
    </div>
    <div class="sidenav-footer mx-3 ">
      <div class="card card-plain shadow-none" id="sidenavCard">
        <div class="card-body text-center p-3 w-100 pt-0">
          <div class="docs-info">
          </div>
        </div>
      </div>
    </div>
  </aside> <!--smpai sini-->>
    <main class="main-content position-relative border-radius-lg">
            <!-- Navbar -->
            <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                    <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Settings</a></li>
                    <li class="breadcrumb-item text-sm text-white active" aria-current="page">Work Shift</li>
                </ol>
                <h6 class="font-weight-bolder text-white mb-0">Work Shift</h6>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                    <div class="input-group">
                    <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
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
                    <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell cursor-pointer"></i>
                    </a>
                    <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
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
                        <li class="mb-2">
                        <a class="dropdown-item border-radius-md" href="javascript:;">
                            <div class="d-flex py-1">
                            <div class="my-auto">
                                <img src="../assets/img/small-logos/logo-spotify.svg" class="avatar avatar-sm bg-gradient-dark  me-3 ">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="text-sm font-weight-normal mb-1">
                                <span class="font-weight-bold">New album</span> by Travis Scott
                                </h6>
                                <p class="text-xs text-secondary mb-0">
                                <i class="fa fa-clock me-1"></i>
                                1 day
                                </p>
                            </div>
                            </div>
                        </a>
                        </li>
                        <li>
                        <a class="dropdown-item border-radius-md" href="javascript:;">
                            <div class="d-flex py-1">
                            <div class="avatar avatar-sm bg-gradient-secondary  me-3  my-auto">
                                <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <title>credit-card</title>
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                                    <g transform="translate(1716.000000, 291.000000)">
                                        <g transform="translate(453.000000, 454.000000)">
                                        <path class="color-background" d="M43,10.7482083 L43,3.58333333 C43,1.60354167 41.3964583,0 39.4166667,0 L3.58333333,0 C1.60354167,0 0,1.60354167 0,3.58333333 L0,10.7482083 L43,10.7482083 Z" opacity="0.593633743"></path>
                                        <path class="color-background" d="M0,16.125 L0,32.25 C0,34.2297917 1.60354167,35.8333333 3.58333333,35.8333333 L39.4166667,35.8333333 C41.3964583,35.8333333 43,34.2297917 43,32.25 L43,16.125 L0,16.125 Z M19.7083333,26.875 L7.16666667,26.875 L7.16666667,23.2916667 L19.7083333,23.2916667 L19.7083333,26.875 Z M35.8333333,26.875 L28.6666667,26.875 L28.6666667,23.2916667 L35.8333333,23.2916667 L35.8333333,26.875 Z"></path>
                                        </g>
                                    </g>
                                    </g>
                                </g>
                                </svg>
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="text-sm font-weight-normal mb-1">
                                Payment successfully completed
                                </h6>
                                <p class="text-xs text-secondary mb-0">
                                <i class="fa fa-clock me-1"></i>
                                2 days
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
      <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <h3 class="mb-0">Medical Claims</h3>
          <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#medicalClaimModal">
            <i class="fas fa-plus-circle me-2"></i>Add Claim
          </button>
        </div>
        
        <div class="card-body">
          <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
              <tr>
                <th>Period (Start-End)</th>
                <th>Entitled Balance</th>
                <th>Used</th>
                <th>Current Balance</th>
                <th>Pending Approval</th>
                <th>Available Balance</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="claimsTableBody"></tbody>
          </table>
        </div>
        <nav aria-label="Current claims pagination">
                <ul class="pagination justify-content-center" id="currentClaimsPagination">
                    <!-- Pagination will be inserted here by JavaScript -->
                </ul>
            </nav>
      </div>
    </div>

    <!-- Medical Claim Modal -->
    <div class="modal fade" id="medicalClaimModal" tabindex="-1" aria-labelledby="medicalClaimModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="medicalClaimModalLabel">Add Medical Claim</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="reminder-box alert alert-info">
              <h6><i class="fas fa-exclamation-circle me-2"></i>Reminder</h6>
              <ol>
                <li>Please attach supporting documents</li>
                <li>The form must be verified by human resource department</li>
                <li>Claims will be deducted from your available balance</li>
              </ol>
            </div>

            <form id="medicalClaimForm">
              <h6 class="mb-3">Medical Claim Form</h6>
              
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="startDate" class="form-label">Start Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="startDate" required>
                </div>
                <div class="col-md-6">
                  <label for="endDate" class="form-label">End Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="endDate" required>
                </div>
              </div>
              
              <div class="row mb-3">
                <div class="col-md-6">
                  <label for="receiptDate" class="form-label">Receipt Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="receiptDate" required>
                </div>
                <div class="col-md-6">
                  <label for="receiptAmount" class="form-label">Amount (RM) <span class="text-danger">*</span></label>
                  <input type="number" step="0.01" class="form-control" id="receiptAmount" required>
                </div>
              </div>
              
              <div class="mb-3">
                <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" rows="2" required placeholder="Brief description of medical treatment"></textarea>
              </div>
              
              <div class="mb-4">
                <label for="attachment" class="form-label">Attachment <span class="text-danger">*</span> (pdf/jpg/png/HEIC/HEIF)</label>
                <input type="file" class="form-control" id="attachment" accept=".pdf,.jpg,.jpeg,.png,.heic,.heif" required>
                <small class="text-muted">Max file size: 5MB</small>
              </div>
              
              <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-paper-plane me-2"></i>Submit Claim
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>


        <!-- History Claims Table -->
      <div class="container-fluid py-4">
      <div class="card">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h3 class="mb-0">History Claims</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Date Submitted</th>
                        <th>Period (Start-End)</th>
                        <th>Claim Amount</th>
                        <th>Status</th>
                        <th>Approved By</th>
                        <th>Approval Date</th>
                        <th>Receipt</th>
                    </tr>
                </thead>
                <tbody id="historyTableBody"></tbody>
                </table>
            </div>
            <nav aria-label="History claims pagination">
                <ul class="pagination justify-content-center" id="historyClaimsPagination">
                    <!-- Pagination will be inserted here by JavaScript -->
                </ul>
            </nav>
        </div>
    </div>
</div>
  </main>

  <!-- Bootstrap JS Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Main application controller
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize date fields
      initDates();
      
      // Setup form submission handler
      setupFormSubmission();
      
      // Setup table event handlers
      setupTableEvents();
    });

    // Initialize date fields with default values
    function initDates() {
      const today = new Date();
      const oneYearLater = new Date();
      oneYearLater.setFullYear(today.getFullYear() + 1);
      
      document.getElementById('receiptDate').valueAsDate = today;
      document.getElementById('startDate').valueAsDate = today;
      document.getElementById('endDate').valueAsDate = oneYearLater;
    }

    // Configure form submission handler
    function setupFormSubmission() {
      const form = document.getElementById('medicalClaimForm');
      
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Process form submission
        const claimData = getFormData();
        logSubmission(claimData);
        addClaimToTable(claimData);
        showSuccessMessage();
        resetForm();
        closeModal();
      });
    }

    // Get form data as an object
    function getFormData() {
      return {
        startDate: document.getElementById('startDate').value,
        endDate: document.getElementById('endDate').value,
        receiptDate: document.getElementById('receiptDate').value,
        amount: parseFloat(document.getElementById('receiptAmount').value),
        description: document.getElementById('description').value
      };
    }

    // Log submission data (would be API call in real app)
    function logSubmission(data) {
      console.log('Claim submitted:', data);
    }

    // Add new claim to the table
    function addClaimToTable(data) {
      const tbody = document.getElementById('claimsTableBody');
      const demoValues = getDemoBalanceValues(data.amount);
      
      const newRow = document.createElement('tr');
      newRow.innerHTML = createTableRowHTML(data, demoValues);
      tbody.insertBefore(newRow, tbody.firstChild);
    }

   function getDemoBalanceValues(newAmount = 0, previousAvailableBalance = null) {
  // For first row: fixed entitled balance (1000.00)
  // For subsequent rows: previous available balance becomes entitled balance
  const entitledBalance = previousAvailableBalance !== null ? previousAvailableBalance : 1000.00;
  
  // Used amount is ONLY what the user enters (no initial 300.00)
  const usedAmount = newAmount;
  
  // Current balance is entitled minus used
  const currentBalance = entitledBalance - usedAmount;
  
  // Pending amount starts at 0 (only grows when requests are awaiting approval)
  const pendingAmount = 0;
  
  // Available balance is current minus pending (since pending amounts are reserved)
  const availableBalance = currentBalance - pendingAmount;
  
  return {
    entitledBalance,
    usedAmount,
    currentBalance,
    pendingAmount,
    availableBalance
  };
}

    // Create HTML for table row
    function createTableRowHTML(data, balances) {
      return `
        <td>${data.startDate} to ${data.endDate}</td>
        <td>${balances.entitledBalance.toFixed(2)}</td>
        <td>${balances.usedAmount.toFixed(2)}</td>
        <td>${balances.currentBalance.toFixed(2)}</td>
        <td>${balances.pendingAmount.toFixed(2)} <span class="badge bg-warning">Pending HOD</span></td>
        <td>${balances.availableBalance.toFixed(2)}</td>
        <td>
          <button class="btn btn-sm btn-warning me-1">Edit</button>
          <button class="btn btn-sm btn-danger">Delete</button>
        </td>
      `;
    }

    // Show success message
    function showSuccessMessage() {
      alert('Medical claim submitted successfully! It is now pending approval.');
    }

    // Reset form fields
    function resetForm() {
      document.getElementById('medicalClaimForm').reset();
      document.getElementById('receiptDate').valueAsDate = new Date();
    }

    // Close the modal
    function closeModal() {
      const modal = bootstrap.Modal.getInstance(document.getElementById('medicalClaimModal'));
      modal.hide();
    }

    // Setup table event handlers
    function setupTableEvents() {
      document.getElementById('claimsTableBody').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-warning')) {
          handleEditClick(e);
        } else if (e.target.classList.contains('btn-danger')) {
          handleDeleteClick(e);
        }
      });
    }

    // Handle edit button click
    function handleEditClick(event) {
      const row = event.target.closest('tr');
      alert('Edit functionality would load claim data into the modal for editing');
    }

    // Handle delete button click
    function handleDeleteClick(event) {
      if (confirm('Are you sure you want to delete this claim?')) {
        const row = event.target.closest('tr');
        row.remove();
        alert('Claim deleted (this would be permanent in a real app)');
      }
    }
    // Function to add a claim to history table (would be called when a claim is approved)
function addToHistoryTable(claimData, status, approvedBy) {
    const tbody = document.getElementById('historyTableBody');
    const approvalDate = new Date().toISOString().split('T')[0];
    
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td>${new Date().toISOString().split('T')[0]}</td>
        <td>${claimData.startDate} to ${claimData.endDate}</td>
        <td>${claimData.amount.toFixed(2)}</td>
        <td><span class="badge ${getStatusBadgeClass(status)}">${status}</span></td>
        <td>${approvedBy}</td>
        <td>${approvalDate}</td>
        <td><a href="#" class="btn btn-sm btn-info">View</a></td>
    `;
    
    tbody.insertBefore(newRow, tbody.firstChild);
}

// Helper function to get badge class based on status
function getStatusBadgeClass(status) {
    switch(status.toLowerCase()) {
        case 'approved': return 'bg-success';
        case 'rejected': return 'bg-danger';
        case 'completed': return 'bg-primary';
        default: return 'bg-secondary';
    }
}

// Example usage (would be called when a claim is approved)
// addToHistoryTable(claimData, 'Approved', 'John Doe (HOD)');
  </script>
</body>
</html>