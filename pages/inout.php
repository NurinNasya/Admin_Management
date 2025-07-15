<?php include('../includes/header-navbar.php'); ?>
    
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
