<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>MSET-Office - Staff Profile</title>
  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/nucleo/1.0.0/css/nucleo-icons.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/nucleo/1.0.0/css/nucleo-svg.min.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="https://cdnjs.cloudflare.com/ajax/libs/argon-dashboard/2.0.4/css/argon-dashboard.min.css" rel="stylesheet" />
  <style>
    /* Mobile-first responsive styles */
    .profile-header {
        background: linear-gradient(135deg, #2c5530 0%, #4fc3f7 100%);
        color: white;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        background-color: #ddd;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #999;
        margin: 0 auto 10px;
    }

    .profile-content {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .profile-info {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .info-label {
        font-size: 12px;
        color: #666;
        font-weight: 500;
    }

    .info-value {
        font-size: 14px;
        color: #333;
        font-weight: 600;
        word-break: break-word;
    }

    .phone-link {
        color: #1976d2;
        text-decoration: none;
    }

    .edit-photo {
        color: #1976d2;
        font-size: 12px;
        cursor: pointer;
        margin-top: 5px;
        text-align: center;
    }

    .tabs {
        display: flex;
        flex-direction: column;
        background-color: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
    }

    .tab {
        padding: 12px;
        text-align: center;
        cursor: pointer;
        font-weight: 500;
        border-bottom: 1px solid #eee;
    }

    .tab:last-child {
        border-bottom: none;
    }

    .tab.active {
        background-color: #4fc3f7;
        color: white;
    }

    .content-section {
        background-color: white;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .section-header {
        background-color: #e3f2fd;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 15px;
        font-weight: 600;
        color: #1976d2;
        font-size: 14px;
    }

    .controls {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 15px;
    }

    .controls-left, .controls-right {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
    }

    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        width: 100%;
    }

    .btn-primary {
        background: #007bff;
        color: white;
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-success {
        background: #28a745;
        color: white;
    }

    .btn-cancel {
        background: #dc3545;
        color: white;
    }

    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
        width: auto;
    }

    select, input {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        width: 100%;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
        font-size: 14px;
        overflow-x: auto;
        display: block;
    }

    .table th, .table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #dee2e6;
    }

    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }

    .no-data {
        text-align: center;
        padding: 20px;
        color: #666;
        font-style: italic;
    }

    .pagination {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        margin-top: 15px;
        font-size: 14px;
        color: #666;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 20px;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        position: relative;
    }

    .close {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
    }

    /* Desktop styles */
    @media (min-width: 768px) {
        .profile-avatar {
            width: 100px;
            height: 100px;
            font-size: 40px;
            margin: 0;
        }

        .profile-content {
            flex-direction: row;
            gap: 30px;
        }

        .profile-info {
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
        }

        .tabs {
            flex-direction: row;
        }

        .tab {
            flex: 1;
            border-right: 1px solid #eee;
            border-bottom: none;
        }

        .tab:last-child {
            border-right: none;
        }

        .controls {
            flex-direction: row;
            justify-content: space-between;
        }

        .btn {
            width: auto;
        }

        .pagination {
            flex-direction: row;
            justify-content: space-between;
        }
    }

    /* Sidebar adjustments for mobile */
    @media (max-width: 1199.98px) {
        .sidenav {
            display: none;
        }
        .main-content {
            margin-left: 0;
        }
    }

    /* Navbar adjustments */
    .navbar-main {
        padding: 0.5rem 1rem;
    }

    /* Card adjustments */
    .card-body {
        padding: 1rem;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <div class="min-height-300 bg-dark position-absolute w-100"></div>

  <!-- Sidebar - Hidden on mobile -->
  <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 bg-white" id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="#">
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
          <a class="nav-link active" href="#">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Profile</span>
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
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Profile</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Profile</h6>
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
              <h6>Update Profile</h6>
            </div>
            <div class="card-body">
              <!-- Profile Content -->
              <div class="profile-card">
                <div class="profile-header">STAFF INFORMATION</div>
                <div class="profile-content">
                  <div>
                    <div class="profile-avatar">👤</div>
                    <div class="edit-photo">Delete Photo</div>
                  </div>
                  <div class="profile-info">
                    <div class="info-item">
                      <span class="info-label">Staff No:</span>
                      <span class="info-value"></span>
                    </div>
                    <div class="info-item">
                      <span class="info-label">Role:</span>
                      <span class="info-value"></span>
                    </div>
                    <div class="info-item">
                      <span class="info-label">Branch Code:</span>
                      <span class="info-value"></span>
                    </div>
                    <div class="info-item">
                      <span class="info-label">Name:</span>
                      <span class="info-value"></span>
                    </div>
                    <div class="info-item">
                      <span class="info-label">Start Date:</span>
                      <span class="info-value"></span>
                    </div>
                    <div class="info-item">
                      <span class="info-label">Branch/Unit:</span>
                      <span class="info-value"></span>
                    </div>
                    <div class="info-item">
                      <span class="info-label">Phone No:</span>
                      <a href="tel:" class="info-value phone-link">📱</a>
                    </div>
                    <div class="info-item">
                      <span class="info-label">End Date:</span>
                      <span class="info-value"></span>
                    </div>
                    <div class="info-item">
                      <span class="info-label">Department:</span>
                      <span class="info-value"></span>
                    </div>
                    <div class="info-item">
                      <span class="info-label">Status:</span>
                      <span class="info-value"></span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tabs">
                <div class="tab active" data-tab="education">Education</div>
                <div class="tab" data-tab="work">Work</div>
                <div class="tab" data-tab="skills">Skills</div>
                <div class="tab" data-tab="family">Family</div>
              </div>

              <div class="content-section">
                <div class="section-header" id="sectionHeader">EDUCATION INFORMATION</div>

                <div class="controls">
                  <div class="controls-left">
                    <span>Show</span>
                    <select>
                      <option>5</option>
                      <option>10</option>
                      <option>25</option>
                      <option>50</option>
                    </select>
                    <span>records</span>
                  </div>
                  <div class="controls-right">
                    <button class="btn btn-success" id="addNewBtn">Add New</button>
                  </div>
                </div>

                <div class="controls">
                  <div></div>
                  <div class="controls-right">
                    <span>Search:</span>
                    <input type="text" placeholder="Search...">
                  </div>
                </div>

                <div id="tableContainer">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Achievement</th>
                        <th>Institution</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td colspan="4" class="no-data">No data available</td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="pagination">
                  <span>Showing 0 to 0 of 0 records</span>
                  <div>
                    <button class="btn btn-secondary">Previous</button>
                    <button class="btn btn-secondary">Next</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- Modals -->
  <div class="modal" id="educationModal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3>Add Education</h3>
      <form id="educationForm">
        <label>Achievement</label>
        <input type="text" name="achievement" required>
        <label>Institution</label>
        <input type="text" name="institution" required>
        <label>Start Year</label>
        <input type="number" name="startYear" required>
        <label>End Year</label>
        <input type="number" name="endYear" required>
        <label>Result</label>
        <input type="text" name="result">
        <button type="submit" class="btn btn-primary">Add Education</button>
      </form>
    </div>
  </div>

  <div class="modal" id="employmentModal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3>Add Employment</h3>
      <form id="employmentForm">
        <label>Workplace</label>
        <input type="text" name="workplace">
        <label>Position</label>
        <input type="text" name="position">
        <label>Role</label>
        <input type="text" name="role">
        <label>Start Date</label>
        <input type="date" name="startDate">
        <label>End Date</label>
        <input type="date" name="endDate">
        <label>Salary</label>
        <input type="number" name="salary" step="0.01">
        <button type="submit" class="btn btn-primary">Add Employment</button>
      </form>
    </div>
  </div>

  <div class="modal" id="skillModal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3>Add Skill</h3>
      <form id="skillForm">
        <label>Skill</label>
        <input type="text" name="skill" required>
        <label>Level</label>
        <select name="level" required>
          <option value="">Select Level</option>
          <option value="Beginner">Beginner</option>
          <option value="Intermediate">Intermediate</option>
          <option value="Advanced">Advanced</option>
          <option value="Expert">Expert</option>
        </select>
        <button type="submit" class="btn btn-primary">Add Skill</button>
      </form>
    </div>
  </div>

  <div class="modal" id="familyModal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h3>Add Family Member</h3>
      <form id="familyForm">
        <label>Name</label>
        <input type="text" name="name" required>
        <label>Occupation</label>
        <input type="text" name="occupation">
        <label>Relationship</label>
        <select name="relationship" required>
          <option value="">Select Relationship</option>
          <option value="Father">Father</option>
          <option value="Mother">Mother</option>
          <option value="Spouse">Spouse</option>
          <option value="Child">Child</option>
          <option value="Sibling">Sibling</option>
          <option value="Other">Other</option>
        </select>
        <label>Phone</label>
        <input type="tel" name="phone">
        <button type="submit" class="btn btn-primary">Add Family</button>
      </form>
    </div>
  </div>

  <!-- Core JS Files -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/argon-dashboard/2.0.4/js/core/popper.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/argon-dashboard/2.0.4/js/core/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/argon-dashboard/2.0.4/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/argon-dashboard/2.0.4/js/argon-dashboard.min.js"></script>

  <script>
    // Data storage
    const data = {
      education: [],
      work: [],
      skills: [],
      family: []
    };

    // Table configurations
    const tableConfigs = {
      education: {
        headers: ['#', 'Achievement', 'Institution', 'Start Year', 'End Year', 'Result', 'Action'],
        fields: ['achievement', 'institution', 'startYear', 'endYear', 'result']
      },
      work: {
        headers: ['#', 'Workplace', 'Position', 'Role', 'Start Date', 'End Date', 'Salary', 'Action'],
        fields: ['workplace', 'position', 'role', 'startDate', 'endDate', 'salary']
      },
      skills: {
        headers: ['#', 'Skill', 'Level', 'Action'],
        fields: ['skill', 'level']
      },
      family: {
        headers: ['#', 'Name', 'Occupation', 'Relationship', 'Phone', 'Action'],
        fields: ['name', 'occupation', 'relationship', 'phone']
      }
    };

    let currentTab = 'education';

    // Initialize
    document.addEventListener('DOMContentLoaded', function () {
      updateTable();
      setupEventListeners();
    });

    function setupEventListeners() {
      // Tab switching
      document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function () {
          document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
          this.classList.add('active');

          currentTab = this.dataset.tab;
          updateSectionHeader();
          updateTable();
        });
      });

      // Add new button
      document.getElementById('addNewBtn').addEventListener('click', function () {
        const modalId = currentTab === 'work' ? 'employmentModal' :
          currentTab === 'skills' ? 'skillModal' :
            currentTab + 'Modal';
        openModal(modalId);
      });

      // Form submissions
      document.getElementById('educationForm').addEventListener('submit', handleFormSubmit);
      document.getElementById('employmentForm').addEventListener('submit', handleFormSubmit);
      document.getElementById('skillForm').addEventListener('submit', handleFormSubmit);
      document.getElementById('familyForm').addEventListener('submit', handleFormSubmit);

      // Close modals when clicking outside
      window.addEventListener('click', function (event) {
        if (event.target.classList.contains('modal')) {
          event.target.style.display = 'none';
        }
      });

      // Close buttons
      document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.addEventListener('click', function () {
          this.closest('.modal').style.display = 'none';
        });
      });
    }

    function updateSectionHeader() {
      const headers = {
        education: 'EDUCATION INFORMATION',
        work: 'WORK EXPERIENCE INFORMATION',
        skills: 'SKILLS INFORMATION',
        family: 'FAMILY INFORMATION'
      };
      document.getElementById('sectionHeader').textContent = headers[currentTab];
    }

    function updateTable() {
      const config = tableConfigs[currentTab];
      const tableData = data[currentTab];

      let tableHTML = `
        <table class="table">
          <thead>
            <tr>
              ${config.headers.map(header => `<th>${header}</th>`).join('')}
            </tr>
          </thead>
          <tbody>
      `;

      if (tableData.length === 0) {
        tableHTML += `<tr><td colspan="${config.headers.length}" class="no-data">No data available</td></tr>`;
      } else {
        tableData.forEach((item, index) => {
          tableHTML += `<tr>`;
          tableHTML += `<td>${index + 1}</td>`;
          config.fields.forEach(field => {
            tableHTML += `<td>${item[field] || ''}</td>`;
          });
          tableHTML += `<td>
            <button class="btn btn-primary btn-sm" onclick="editItem('${currentTab}', ${index})">Edit</button>
            <button class="btn btn-cancel btn-sm" onclick="deleteItem('${currentTab}', ${index})">Delete</button>
          </td>`;
          tableHTML += `</tr>`;
        });
      }

      tableHTML += `
          </tbody>
        </table>
      `;

      document.getElementById('tableContainer').innerHTML = tableHTML;
    }

    function openModal(modalId) {
      document.getElementById(modalId).style.display = 'block';
    }

    function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
    }

    function handleFormSubmit(event) {
      event.preventDefault();

      const formData = new FormData(event.target);
      const formObject = {};

      for (let [key, value] of formData.entries()) {
        formObject[key] = value;
      }

      // Determine which data array to update based on form
      let dataType;
      if (event.target.id === 'educationForm') dataType = 'education';
      else if (event.target.id === 'employmentForm') dataType = 'work';
      else if (event.target.id === 'skillForm') dataType = 'skills';
      else if (event.target.id === 'familyForm') dataType = 'family';

      data[dataType].push(formObject);

      // Close modal and update table
      const modalId = dataType === 'work' ? 'employmentModal' :
        dataType === 'skills' ? 'skillModal' :
          dataType + 'Modal';
      closeModal(modalId);
      event.target.reset();

      if (currentTab === dataType) {
        updateTable();
      }

      alert(`${dataType.charAt(0).toUpperCase() + dataType.slice(1)} added successfully!`);
    }

    function editItem(type, index) {
      alert(`Edit functionality for ${type} item ${index + 1} would be implemented here`);
    }

    function deleteItem(type, index) {
      if (confirm('Are you sure you want to delete this item?')) {
        data[type].splice(index, 1);
        if (currentTab === type) {
          updateTable();
        }
        alert('Item deleted successfully!');
      }
    }

    // Search functionality
    document.querySelector('input[type="text"]').addEventListener('input', function () {
      const searchTerm = this.value.toLowerCase();
      console.log('Searching for:', searchTerm);
      // 
    });
  </script>
</body>

</html>