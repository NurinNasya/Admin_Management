<?php
// medical_claim.php
session_start();

// Sample data for medical claim balance
$medical_balance = [
    'validity' => '01/02/2025 - 31/07/2025',
    'entitled' => 'RM500.00',
    'used' => 'RM142.20',
    'current' => 'RM357.80',
    'pending' => 'RM0.00',
    'available' => 'RM357.80'
];

// Handle form submission
if ($_POST) {
    $date_receipt = $_POST['date_receipt'] ?? '';
    $total_rm = $_POST['total_rm'] ?? '';
    $description = $_POST['description'] ?? '';
    
    // Handle file upload
    $attachment = '';
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $attachment = $upload_dir . basename($_FILES['attachment']['name']);
        move_uploaded_file($_FILES['attachment']['tmp_name'], $attachment);
    }
    
    // Process the claim (in real application, save to database)
    $success_message = "Medical claim submitted successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Medical Claim - Argon Dashboard
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
    .medical-balance-card {
      background: linear-gradient(87deg, #f8f9fa 0, #e9ecef 100%) !important;
      color: #344767;
      border-radius: 8px;
      border: 1px solid #dee2e6;
    }
    
    .balance-item {
      display: flex;
      justify-content: space-between;
      padding: 10px;
      margin: 5px 0;
      border-radius: 6px;
      font-weight: 600;
      background-color: white;
      border: 1px solid #dee2e6;
    }
    
    .balance-validity {
      background-color: #f8f9fa;
      border-left: 4px solid #5e72e4;
    }
    
    .balance-entitled {
      border-left: 4px solid #11cdef;
    }
    
    .balance-used {
      border-left: 4px solid #fb6340;
    }
    
    .balance-current {
      border-left: 4px solid #2dce89;
    }
    
    .balance-pending {
      border-left: 4px solid #f5365c;
    }
    
    .balance-available {
      border-left: 4px solid #5e72e4;
    }
    
    .reminder-box {
      background-color: #fff3cd;
      border-left: 4px solid #ffc107;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 4px;
    }
    
    .reminder-title {
      color: #856404;
      font-weight: 600;
      margin-bottom: 8px;
    }
    
    .file-upload {
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
    }
    
    .file-upload-input {
      position: absolute;
      font-size: 100px;
      opacity: 0;
      right: 0;
      top: 0;
    }
    
    .file-upload-label {
      display: flex;
      align-items: center;
      padding: 10px 15px;
      background-color: #f8f9fa;
      border: 1px dashed #dee2e6;
      border-radius: 6px;
      text-align: center;
      cursor: pointer;
      height: 38px;
    }
    
    .file-upload-label:hover {
      background-color: #e9ecef;
    }
    
    .file-name {
      margin-left: 10px;
      font-size: 14px;
      color: #6c757d;
      height: 38px;
      display: flex;
      align-items: center;
    }
    
    .badge-approved {
      background-color: #2dce89;
    }
    
    .badge-pending {
      background-color: #fb6340;
    }
    
    .badge-rejected {
      background-color: #f5365c;
    }
    
    .text-action-btn {
      padding: 0.25rem 0.5rem;
      font-size: 0.75rem;
      border-radius: 0.2rem;
      text-transform: none;
      letter-spacing: normal;
      margin: 0 2px;
    }
    
    .edit-modal {
      display: none;
      position: fixed;
      z-index: 1050;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: hidden;
      outline: 0;
      background-color: rgba(0,0,0,0.5);
    }
    
    .edit-modal-content {
      position: relative;
      margin: 10% auto;
      width: 80%;
      max-width: 600px;
      background-color: #fff;
      border-radius: 0.3rem;
      padding: 20px;
    }
    
    .close-modal {
      position: absolute;
      right: 15px;
      top: 10px;
      font-size: 1.5rem;
      font-weight: 700;
      line-height: 1;
      color: #000;
      opacity: 0.5;
      cursor: pointer;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="?view=dashboard">
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
            <span class="nav-link-text ms-1">Sign Out</span>
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
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Medical Claim</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Medical Claim Management</h6>
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
                <span class="d-sm-inline d-none"><?php echo $_SESSION['username'] ?? 'User'; ?></span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    
    <div class="container-fluid py-4">
      <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <span class="alert-icon"><i class="ni ni-like-2"></i></span>
          <span class="alert-text"><?php echo $success_message; ?></span>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php endif; ?>
      
      <div class="row">
        <!-- Medical Balance Card -->
        <div class="col-xl-4 col-md-6 mb-4">
          <div class="card medical-balance-card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-12">
                  <h6 class="text-dark mb-3">Medical Claim Balance</h6>
                  
                  <div class="balance-item balance-validity">
                    <span>Validity</span>
                    <span><?php echo $medical_balance['validity']; ?></span>
                  </div>
                  
                  <div class="balance-item balance-entitled">
                    <span>Entitled</span>
                    <span><?php echo $medical_balance['entitled']; ?></span>
                  </div>
                  
                  <div class="balance-item balance-used">
                    <span>Used</span>
                    <span><?php echo $medical_balance['used']; ?></span>
                  </div>
                  
                  <div class="balance-item balance-current">
                    <span>Current</span>
                    <span><?php echo $medical_balance['current']; ?></span>
                  </div>
                  
                  <div class="balance-item balance-pending">
                    <span>Pending</span>
                    <span><?php echo $medical_balance['pending']; ?></span>
                  </div>
                  
                  <div class="balance-item balance-available">
                    <span>Available</span>
                    <span><?php echo $medical_balance['available']; ?></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Medical Claim Form -->
        <div class="col-xl-8 col-md-6 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <h6>Medical Claim Form</h6>
            </div>
            <div class="card-body pt-0">
              <!-- Reminder Box -->
              <div class="reminder-box">
                <div class="reminder-title">Reminder</div>
                <div class="mb-2">1. Please attach supporting documents</div>
                <div>2. The form must be verified by HR department</div>
              </div>

              <!-- Claim Form -->
              <form method="POST" enctype="multipart/form-data">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="date_receipt" class="form-control-label">Date Receipt</label>
                      <input class="form-control" type="date" id="date_receipt" name="date_receipt" 
                             value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="total_rm" class="form-control-label">Total (RM)</label>
                      <input class="form-control" type="number" id="total_rm" name="total_rm" 
                             step="0.01" min="0" placeholder="0.00" required>
                    </div>
                  </div>
                </div>
                
                <div class="form-group">
                  <label for="description" class="form-control-label">Description</label>
                  <input class="form-control" type="text" id="description" name="description" 
                         placeholder="Enter description">
                </div>
                
                <div class="form-group">
                  <label class="form-control-label">Attachment</label>
                  <div class="file-upload">
                    <label class="file-upload-label">
                      <i class="fas fa-cloud-upload-alt me-2"></i>Choose file (PDF, JPG, PNG)
                      <input type="file" class="file-upload-input" id="attachment" name="attachment" 
                             accept=".pdf,.jpg,.jpeg,.png,.heic,.heif">
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

      <!-- Claims History Table -->
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
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Description</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Attachment</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">2025-06-15</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">RM75.50</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">Medical consultation</p>
                      </td>
                      <td>
                        <span class="badge badge-sm badge-approved">Approved</span>
                      </td>
                      <td>
                        <a href="#" class="text-xs font-weight-bold mb-0"><i class="fas fa-file-pdf me-1 text-danger"></i> receipt.pdf</a>
                      </td>
                      <td class="action-buttons">
                        <button class="btn btn-sm btn-outline-primary text-action-btn" onclick="viewClaim(1)">
                          View
                        </button>
                        <button class="btn btn-sm btn-outline-warning text-action-btn" onclick="openEditModal(1)">
                          Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger text-action-btn" onclick="confirmDelete(1)">
                          Delete
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">2025-06-10</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">RM125.00</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">Laboratory test</p>
                      </td>
                      <td>
                        <span class="badge badge-sm badge-pending">Pending</span>
                      </td>
                      <td>
                        <a href="#" class="text-xs font-weight-bold mb-0"><i class="fas fa-file-image me-1 text-info"></i> lab_report.jpg</a>
                      </td>
                      <td class="action-buttons">
                        <button class="btn btn-sm btn-outline-primary text-action-btn" onclick="viewClaim(2)">
                          View
                        </button>
                        <button class="btn btn-sm btn-outline-warning text-action-btn" onclick="openEditModal(2)">
                          Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger text-action-btn" onclick="confirmDelete(2)">
                          Delete
                        </button>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <div class="d-flex px-2 py-1">
                          <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">2025-05-28</h6>
                          </div>
                        </div>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">RM200.00</p>
                      </td>
                      <td>
                        <p class="text-xs font-weight-bold mb-0">Dental treatment</p>
                      </td>
                      <td>
                        <span class="badge badge-sm badge-rejected">Rejected</span>
                      </td>
                      <td>
                        <a href="#" class="text-xs font-weight-bold mb-0"><i class="fas fa-file-pdf me-1 text-danger"></i> dental_receipt.pdf</a>
                      </td>
                      <td class="action-buttons">
                        <button class="btn btn-sm btn-outline-primary text-action-btn" onclick="viewClaim(3)">
                          View
                        </button>
                        <button class="btn btn-sm btn-outline-warning text-action-btn" onclick="openEditModal(3)">
                          Edit
                        </button>
                        <button class="btn btn-sm btn-outline-danger text-action-btn" onclick="confirmDelete(3)">
                          Delete
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Edit Claim Modal -->
  <div id="editClaimModal" class="edit-modal">
    <div class="edit-modal-content">
      <span class="close-modal" onclick="closeEditModal()">&times;</span>
      <h5>Edit Medical Claim</h5>
      <form id="editClaimForm">
        <input type="hidden" id="edit_claim_id">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="edit_date_receipt" class="form-control-label">Date Receipt</label>
              <input class="form-control" type="date" id="edit_date_receipt" name="edit_date_receipt" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="edit_total_rm" class="form-control-label">Total (RM)</label>
              <input class="form-control" type="number" id="edit_total_rm" name="edit_total_rm" 
                     step="0.01" min="0" placeholder="0.00" required>
            </div>
          </div>
        </div>
        
        <div class="form-group">
          <label for="edit_description" class="form-control-label">Description</label>
          <input class="form-control" type="text" id="edit_description" name="edit_description" 
                 placeholder="Enter description">
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
          <div id="current_file" class="mt-2"></div>
        </div>
        
        <button type="submit" class="btn bg-gradient-primary w-100 mt-3">
          <i class="fas fa-save me-2"></i> Update Claim
        </button>
      </form>
    </div>
  </div>

  <!--   Core JS Files   -->
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
  
  <script>
    // File upload display
    document.getElementById('attachment').addEventListener('change', function(e) {
      const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
      document.getElementById('file-name').textContent = fileName;
    });

    document.getElementById('edit_attachment').addEventListener('change', function(e) {
      const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
      document.getElementById('edit_file_name').textContent = fileName;
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
      const totalRM = parseFloat(document.getElementById('total_rm').value);
      const availableAmount = parseFloat(<?php echo str_replace(['RM', ','], '', $medical_balance['available']); ?>);
      
      if (totalRM > availableAmount) {
        e.preventDefault();
        alert('Error: Claim amount (RM' + totalRM.toFixed(2) + ') exceeds your available balance (RM' + availableAmount.toFixed(2) + ')');
        return false;
      }
      
      const fileInput = document.getElementById('attachment');
      if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png', 'image/heic', 'image/heif'];
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        if (!allowedTypes.includes(file.type)) {
          e.preventDefault();
          alert('Error: Only PDF, JPG, PNG, HEIC files are allowed');
          return false;
        }
        
        if (file.size > maxSize) {
          e.preventDefault();
          alert('Error: File size must be less than 10MB');
          return false;
        }
      }
    });

    // Edit claim functions
    function openEditModal(claimId) {
      // In a real application, you would fetch the claim data from the server
      // Here we're just simulating with sample data
      const sampleData = {
        1: {
          date_receipt: '2025-06-15',
          total_rm: '75.50',
          description: 'Medical consultation',
          attachment: 'receipt.pdf'
        },
        2: {
          date_receipt: '2025-06-10',
          total_rm: '125.00',
          description: 'Laboratory test',
          attachment: 'lab_report.jpg'
        },
        3: {
          date_receipt: '2025-05-28',
          total_rm: '200.00',
          description: 'Dental treatment',
          attachment: 'dental_receipt.pdf'
        }
      };
      
      const claimData = sampleData[claimId];
      document.getElementById('edit_claim_id').value = claimId;
      document.getElementById('edit_date_receipt').value = claimData.date_receipt;
      document.getElementById('edit_total_rm').value = claimData.total_rm;
      document.getElementById('edit_description').value = claimData.description;
      document.getElementById('current_file').innerHTML = 
        `<small>Current file: <a href="#">${claimData.attachment}</a></small>`;
      
      document.getElementById('editClaimModal').style.display = 'block';
    }

    function closeEditModal() {
      document.getElementById('editClaimModal').style.display = 'none';
    }

    function viewClaim(claimId) {
      alert('Viewing claim ID: ' + claimId);
      // In a real application, you would show the claim details
    }

    function confirmDelete(claimId) {
      if (confirm('Are you sure you want to delete this claim?')) {
        alert('Claim ID ' + claimId + ' deleted');
        // In a real application, you would send a delete request to the server
      }
    }

    // Handle edit form submission
    document.getElementById('editClaimForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const claimId = document.getElementById('edit_claim_id').value;
      alert('Claim ID ' + claimId + ' updated successfully!');
      closeEditModal();
      // In a real application, you would send the updated data to the server
    });

    // Close modal when clicking outside
    window.onclick = function(event) {
      const modal = document.getElementById('editClaimModal');
      if (event.target == modal) {
        closeEditModal();
      }
    }

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
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.1.0"></script>
</body>
</html>