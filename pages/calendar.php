<?php 
require_once '../db.php';  // Ensure the file is included only once
?>

<?php include('../includes/header-navbar.php'); ?>

<!-- Main content -->
<div class="container-fluid">
  <div class="row">
    <!-- Work Shift List -->
    <div class="col-lg-8">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header py-3 px-4">
      <h6 class="mb-0">Staff Workday</h6>
    </div>
 <div class="card-body p-3">
  <div class="table-responsive">
     <table class="table table-bordered text-center align-middle" style="font-size: 14px;">
            <thead class="table-light">
              <tr>
                <th style="width: 60px;">Time / Days</th>
                <th style="width: 80px;">Sun</th>
                <th style="width: 80px;">Mon</th>
                <th style="width: 80px;">Tue</th>
                <th style="width: 80px;">Wed</th>
                <th style="width: 80px;">Thu</th>
                <th style="width: 80px;">Fri</th>
                <th style="width: 80px;">Sat</th>
              </tr>
            </thead>
      <tbody>
        <tr>
          <td><strong class="small-time">8:00 a.m. - 9:00 a.m.</strong></td>
          <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
        </tr>
        <tr>
          <td><strong class="small-time">9:00 a.m. - 10:00 a.m.</strong></td>
          <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
        </tr>
        <tr>
          <td><strong class="small-time">10:00 a.m. - 11:00 a.m.</strong></td>
          <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
        </tr>
        <tr>
          <td><strong class="small-time">11:00 a.m. - 12:00 p.m.</strong></td>
          <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
        </tr>
        <tr>
          <td><strong class="small-time">12:00 p.m. - 13:00 p.m.</strong></td>
          <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
        </tr>
        <tr>
          <td><strong class="small-time">13:00 p.m. - 14:00 p.m.</strong></td>
          <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
        </tr>
        <tr>
          <td><strong class="small-time">14:00 p.m. - 15:00 p.m.</strong></td>
          <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
        </tr>
        <tr>
          <td><strong class="small-time">15:00 p.m. - 16:00 p.m.</strong></td>
          <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
        </tr>
        <tr>
          <td><strong class="small-time">16:00 p.m. - 17:00 p.m.</strong></td>
          <td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td><td>-</td>
        </tr>
      </tbody>
          </table>
  </div>
</div>
  </div>
</div>

<!-- Calendar + Remarks Together -->
<div class="col-lg-4">
  <!-- Calendar Card -->
  <div class="card shadow-sm border-0 rounded-3 calendar-sm mb-3">
    <div class="card-header">
      <h6 class="mb-0">Shift Calendar</h6>
    </div>
    <div class="card-body p-3">
      <div id="shiftCalendar" style="min-height: 250px;">
      </div>
    </div>
  </div>

  <!-- Remarks Card -->
  <div class="card shadow-sm border-0 rounded-3">
    <div class="card-body p-3">
    <ul class="list-group list-group-flush">
  <li class="list-group-item d-flex justify-content-between align-items-start">
    <div class="ms-2 me-auto">
      <div class="fw-semibold">Awal Muharram</div>
      <small class="text-muted">June 27, 2025 · Friday</small>
    </div>
  </li>
  <li class="list-group-item d-flex justify-content-between align-items-start">
    <div class="ms-2 me-auto">
      <div class="fw-semibold">Hari Kebangsaan</div>
      <small class="text-muted">August 31, 2025 · Sunday</small>
    </div>
  </li>
  <li class="list-group-item d-flex justify-content-between align-items-start">
    <div class="ms-2 me-auto">
      <div class="fw-semibold">Hari Keputeraan Nabi Muhammad S.A.W</div>
      <small class="text-muted">September 5, 2025 · Friday</small>
    </div>
  </li>
  <li class="list-group-item d-flex justify-content-between align-items-start">
    <div class="ms-2 me-auto">
      <div class="fw-semibold">Hari Malaysia</div>
      <small class="text-muted">September 16, 2025 · Tuesday</small>
    </div>>
  </li>
  <li class="list-group-item d-flex justify-content-between align-items-start">
    <div class="ms-2 me-auto">
      <div class="fw-semibold">Hari Deepavali</div>
      <small class="text-muted">October 20, 2025 · Monday</small>
    </div>
  </li>
  <li class="list-group-item d-flex justify-content-between align-items-start">
    <div class="ms-2 me-auto">
      <div class="fw-semibold">Hari Krismas</div>
      <small class="text-muted">December 25, 2025 · Thursday</small>
    </div>
  </li>
</ul>

    </div>
  </div>
</div>

<style>
  .small-time {
    font-size: 12px;
    line-height: 1.2;
  }
</style>


<style>
  /* Optional: limit calendar height if using a library */
  #shiftCalendar {
  min-height: 250px; /* Make this smaller as you like */
}

  .calendar-sm .card-header {
    padding: 0.5rem 1rem;
  }

  .calendar-sm .card-body {
    padding: 0.75rem;
  }

  .calendar-sm h6 {
    font-size: 0.95rem;
  }
</style>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet" />

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
  <script>
    setTimeout(function () {
      var alert = document.querySelector('.alert');
      if (alert) {
        alert.classList.remove('show');
        alert.classList.add('fade');
        setTimeout(() => alert.remove(), 500);
      }
    }, 3000);
  </script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('shiftCalendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      height: 250, // Adjust height as needed
      events: [
        {
          title: 'Awal Muharram',
          start: '2025-06-27',
          color: '#28a745' // Green (Active)
        },
        {
          title: 'Hari Kebangsaan',
          start: '2025-08-31',
          color: '#28a745'
        },
        {
          title: 'Hari Keputeraan Nabi Muhammad S.A.W',
          start: '2025-09-05',
          color: '#28a745'
        },
        {
          title: 'Hari Malaysia',
          start: '2025-09-16',
          color: '#28a745'
        },
        {
          title: 'Hari Deepavali',
          start: '2025-10-20',
          color: '#28a745'
        },
        {
          title: 'Hari Krismas',
          start: '2025-12-25',
          color: '#28a745'
        }
      ]
    });

    calendar.render();
  });
</script>

        </main>
</html>