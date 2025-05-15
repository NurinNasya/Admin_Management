<?php 
require_once '../db.php';  // Ensure the file is included only once
?>
<?php include_once '../Controller/userController.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>User Information Update Form</title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
  
  <!-- User Information Form Styles -->
  <style>
    .nav-tabs {
        display: flex;
        margin: 0;
        padding: 0;
        list-style: none;
        border-bottom: 2px solid #e3e3e3;
    }
    .nav-tab {
        padding: 15px 20px;
        margin: 0;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
    }
    .nav-tab.active {
        background-color: #1baee2;
        color: white;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
    }
    .tab-content {
        background-color: #f9f9f9;
        padding: 20px;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
    }
    .section-title {
        background-color: #f2f2f2;
        padding: 10px 15px;
        margin-bottom: 20px;
        font-weight: bold;
        text-transform: uppercase;
    }
    .form-group {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    label {
        margin-right: 10px;
    }
    .sort-icon::after {
        content: "▼";
        font-size: 10px;
        margin-left: 5px;
    }
    .empty-data {
        padding: 15px;
        text-align: center;
        color: #666;
    }
    .page-info {
        margin-top: 15px;
        color: #666;
        font-size: 14px;
    }
    .btn-add {
        padding: 8px 15px;
        background-color: #1baee2;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        margin-top: 15px;
    }
    .btn-add:hover {
        background-color: #1695c3;
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
        <span class="ms-1 font-weight-bold">Creative Tim</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
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
          <a class="nav-link" href="../pages/tables.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Tables</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../pages/medleave.php">
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
        <li class="nav-item">
          <a class="nav-link" href="../pages/rtl.html">
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
    <div class="sidenav-footer mx-3">
      <div class="card card-plain shadow-none" id="sidenavCard">
        <div class="card-body text-center p-3 w-100 pt-0">
          <div class="docs-info">
          </div>
        </div>
      </div>
    </div>
  </aside>
  
<main class="main-content position-relative border-radius-lg">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Staff</a></li>
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">User Information</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">User Information</h6>
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
            <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                <!-- Notification items here -->
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->

    <!-- User Information Form Content -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-body px-0 pt-0 pb-2">
              <!-- User Information Form -->
              <?php
              // Define tab data
              $tabs = [
                  'maklumat_pekerja' => 'Maklumat Pekerja',
                  'maklumat_pendidikan' => 'Maklumat Pendidikan',
                  'maklumat_pekerjaan' => 'Maklumat Pekerjaan Terdahulu',
                  'maklumat_kemahiran' => 'Maklumat Kemahiran',
                  'maklumat_keluarga' => 'Maklumat Keluarga'
              ];
              
              // Default active tab
              $activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'maklumat_pekerja';
              
              // Number of records to display
              $recordsPerPage = isset($_GET['records']) ? intval($_GET['records']) : 5;
              ?>

              <div class="container">
                <form method="post" action="">
                  <!-- Navigation Tabs -->
                  <ul class="nav-tabs">
                    <?php foreach ($tabs as $tabId => $tabName): ?>
                      <li class="nav-tab <?php echo ($activeTab === $tabId) ? 'active' : ''; ?>" 
                          onclick="window.location.href='?tab=<?php echo $tabId; ?>'">
                        <?php echo $tabName; ?>
                      </li>
                    <?php endforeach; ?>
                  </ul>

                  <!-- Tab Content -->
                  <div class="tab-content">
                    <!-- Maklumat Pekerja -->
                    <?php if ($activeTab === 'maklumat_pekerja'): ?>
                      <div class="section-title">MAKLUMAT PEKERJA</div>
                      
                      <div class="employee-form">
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="nama">Nama Penuh</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="no_ic">No. Kad Pengenalan</label>
                            <input type="text" class="form-control" id="no_ic" name="no_ic" required>
                          </div>
                        </div>
                        
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="nombor_telefon">Nombor Telefon</label>
                            <input type="tel" class="form-control" id="nombor_telefon" name="nombor_telefon" required>
                          </div>
                        </div>
                        
                        <div class="form-row">
                          <div class="form-group col-md-4">
                            <label for="jantina">Jantina</label>
                            <select class="form-control" id="jantina" name="jantina" required>
                              <option value="">Pilih Jantina</option>
                              <option value="Lelaki">Lelaki</option>
                              <option value="Perempuan">Perempuan</option>
                            </select>
                          </div>
                          <div class="form-group col-md-4">
                            <label for="agama">Agama</label>
                            <select class="form-control" id="agama" name="agama" required>
                              <option value="">Pilih Agama</option>
                              <option value="Islam">Islam</option>
                              <option value="Kristian">Kristian</option>
                              <option value="Buddha">Buddha</option>
                              <option value="Hindu">Hindu</option>
                              <option value="Lain-lain">Lain-lain</option>
                            </select>
                          </div>
                          <div class="form-group col-md-4">
                            <label for="taraf_perkahwinan">Taraf Perkahwinan</label>
                            <select class="form-control" id="taraf_perkahwinan" name="taraf_perkahwinan" required>
                              <option value="">Pilih Taraf</option>
                              <option value="Bujang">Bujang</option>
                              <option value="Kahwin">Kahwin</option>
                              <option value="Duda">Duda</option>
                              <option value="Janda">Janda</option>
                            </select>
                          </div>
                        </div>
                        
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="department">Department</label>
                            <select class="form-control" id="department" name="department" required>
                              <option value="">Pilih Department</option>
                              <option value="IT">IT</option>
                              <option value="HR">HR</option>
                              <option value="Finance">Finance</option>
                              <option value="Operations">Operations</option>
                              <option value="Marketing">Marketing</option>
                            </select>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="jawatan">Jawatan</label>
                            <input type="text" class="form-control" id="jawatan" name="jawatan" required>
                          </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Simpan Maklumat</button>
                      </div>
                    
                    <!-- Maklumat Pendidikan -->
                    <?php elseif ($activeTab === 'maklumat_pendidikan'): ?>
                      <div class="section-title">MAKLUMAT PENDIDIKAN</div>
                      
                      <div class="form-group">
                        <label for="records">Papar</label>
                        <select id="records" name="records" onchange="this.form.submit()">
                          <option value="5" <?php echo ($recordsPerPage === 5) ? 'selected' : ''; ?>>5</option>
                          <option value="10" <?php echo ($recordsPerPage === 10) ? 'selected' : ''; ?>>10</option>
                          <option value="25" <?php echo ($recordsPerPage === 25) ? 'selected' : ''; ?>>25</option>
                          <option value="50" <?php echo ($recordsPerPage === 50) ? 'selected' : ''; ?>>50</option>
                        </select>
                        <span style="margin-left: 5px;">rekod</span>
                      </div>

                      <table class="table">
                        <thead>
                          <tr>
                            <th width="5%">#<span class="sort-icon"></span></th>
                            <th width="30%">Pencapaian<span class="sort-icon"></span></th>
                            <th width="30%">Tempat<span class="sort-icon"></span></th>
                            <th width="17.5%">Tahun Mula<span class="sort-icon"></span></th>
                            <th width="17.5%">Tahun Akhir</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan="5" class="empty-data">Tiada data</td>
                          </tr>
                        </tbody>
                      </table>

                      <div class="page-info">
                        Papar 0 hingga 0 daripada 0 rekod
                      </div>

                      <button type="button" class="btn btn-primary" onclick="showEducationForm()">
                        Tambah Rekod Pendidikan
                      </button>
                      
                      <!-- Education Form (Hidden by default) -->
                      <div id="educationForm" style="display: none; margin-top: 20px;">
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="peringkat">Peringkat Pendidikan</label>
                            <select class="form-control" id="peringkat" name="peringkat" required>
                              <option value="">Pilih Peringkat</option>
                              <option value="SPM">SPM</option>
                              <option value="STPM">STPM</option>
                              <option value="Diploma">Diploma</option>
                              <option value="Ijazah Sarjana Muda">Ijazah Sarjana Muda</option>
                              <option value="Ijazah Sarjana">Ijazah Sarjana</option>
                              <option value="PhD">PhD</option>
                            </select>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="nama_institusi">Nama Institusi</label>
                            <input type="text" class="form-control" id="nama_institusi" name="nama_institusi" required>
                          </div>
                        </div>
                        
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="tahun_mula">Tahun Mula</label>
                            <input type="number" class="form-control" id="tahun_mula" name="tahun_mula" min="1900" max="2099" required>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="tahun_tamat">Tahun Tamat</label>
                            <input type="number" class="form-control" id="tahun_tamat" name="tahun_tamat" min="1900" max="2099">
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label for="bidang_pengajian">Bidang Pengajian</label>
                          <input type="text" class="form-control" id="bidang_pengajian" name="bidang_pengajian">
                        </div>
                        
                        <div class="form-group">
                          <label for="keputusan">Keputusan/CGPA</label>
                          <input type="text" class="form-control" id="keputusan" name="keputusan">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Simpan Pendidikan</button>
                        <button type="button" class="btn btn-secondary" onclick="hideEducationForm()">Batal</button>
                      </div>
                    
                    <!-- Maklumat Pekerjaan Terdahulu -->
                    <?php elseif ($activeTab === 'maklumat_pekerjaan'): ?>
                      <div class="section-title">MAKLUMAT PEKERJAAN TERDAHULU</div>
                      
                      <div class="form-group">
                        <label for="records">Papar</label>
                        <select id="records" name="records" onchange="this.form.submit()">
                          <option value="5" <?php echo ($recordsPerPage === 5) ? 'selected' : ''; ?>>5</option>
                          <option value="10" <?php echo ($recordsPerPage === 10) ? 'selected' : ''; ?>>10</option>
                          <option value="25" <?php echo ($recordsPerPage === 25) ? 'selected' : ''; ?>>25</option>
                          <option value="50" <?php echo ($recordsPerPage === 50) ? 'selected' : ''; ?>>50</option>
                        </select>
                        <span style="margin-left: 5px;">rekod</span>
                      </div>

                      <table class="table">
                        <thead>
                          <tr>
                            <th width="5%">#<span class="sort-icon"></span></th>
                            <th width="25%">Nama Syarikat<span class="sort-icon"></span></th>
                            <th width="20%">Jawatan<span class="sort-icon"></span></th>
                            <th width="15%">Tarikh Mula<span class="sort-icon"></span></th>
                            <th width="15%">Tarikh Tamat<span class="sort-icon"></span></th>
                            <th width="20%">Sebab Berhenti</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan="6" class="empty-data">Tiada data</td>
                          </tr>
                        </tbody>
                      </table>

                      <div class="page-info">
                        Papar 0 hingga 0 daripada 0 rekod
                      </div>

                      <button type="button" class="btn btn-primary" onclick="showEmploymentForm()">
                        Tambah Rekod Pekerjaan
                      </button>
                      
                      <!-- Employment Form (Hidden by default) -->
                      <div id="employmentForm" style="display: none; margin-top: 20px;">
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="nama_syarikat">Nama Syarikat</label>
                            <input type="text" class="form-control" id="nama_syarikat" name="nama_syarikat" required>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="jawatan_terdahulu">Jawatan</label>
                            <input type="text" class="form-control" id="jawatan_terdahulu" name="jawatan_terdahulu" required>
                          </div>
                        </div>
                        
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="tarikh_mula">Tarikh Mula</label>
                            <input type="date" class="form-control" id="tarikh_mula" name="tarikh_mula" required>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="tarikh_tamat">Tarikh Tamat</label>
                            <input type="date" class="form-control" id="tarikh_tamat" name="tarikh_tamat">
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label for="tugas">Tugas dan Tanggungjawab</label>
                          <textarea class="form-control" id="tugas" name="tugas" rows="3"></textarea>
                        </div>
                        
                        <div class="form-group">
                          <label for="sebab_berhenti">Sebab Berhenti</label>
                          <textarea class="form-control" id="sebab_berhenti" name="sebab_berhenti" rows="2"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Simpan Pekerjaan</button>
                        <button type="button" class="btn btn-secondary" onclick="hideEmploymentForm()">Batal</button>
                      </div>
                    
                    <!-- Maklumat Kemahiran -->
                    <?php elseif ($activeTab === 'maklumat_kemahiran'): ?>
                      <div class="section-title">MAKLUMAT KEMAHIRAN</div>
                      
                      <div class="form-group">
                        <label for="records">Papar</label>
                        <select id="records" name="records" onchange="this.form.submit()">
                          <option value="5" <?php echo ($recordsPerPage === 5) ? 'selected' : ''; ?>>5</option>
                          <option value="10" <?php echo ($recordsPerPage === 10) ? 'selected' : ''; ?>>10</option>
                          <option value="25" <?php echo ($recordsPerPage === 25) ? 'selected' : ''; ?>>25</option>
                          <option value="50" <?php echo ($recordsPerPage === 50) ? 'selected' : ''; ?>>50</option>
                        </select>
                        <span style="margin-left: 5px;">rekod</span>
                      </div>

                      <table class="table">
                        <thead>
                          <tr>
                            <th width="5%">#<span class="sort-icon"></span></th>
                            <th width="30%">Jenis Kemahiran<span class="sort-icon"></span></th>
                            <th width="25%">Tahap Kemahiran<span class="sort-icon"></span></th>
                            <th width="20%">Sijil/Tahun<span class="sort-icon"></span></th>
                            <th width="20%">Pengiktirafan</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan="5" class="empty-data">Tiada data</td>
                          </tr>
                        </tbody>
                      </table>

                      <div class="page-info">
                        Papar 0 hingga 0 daripada 0 rekod
                      </div>

                      <button type="button" class="btn btn-primary" onclick="showSkillForm()">
                        Tambah Rekod Kemahiran
                      </button>
                      
                      <!-- Skill Form (Hidden by default) -->
                      <div id="skillForm" style="display: none; margin-top: 20px;">
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="jenis_kemahiran">Jenis Kemahiran</label>
                            <select class="form-control" id="jenis_kemahiran" name="jenis_kemahiran" required>
                              <option value="">Pilih Kemahiran</option>
                              <option value="Bahasa">Bahasa</option>
                              <option value="Teknikal">Teknikal</option>
                              <option value="Pengurusan">Pengurusan</option>
                              <option value="Komputer">Komputer</option>
                              <option value="Lain-lain">Lain-lain</option>
                            </select>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="tahap_kemahiran">Tahap Kemahiran</label>
                            <select class="form-control" id="tahap_kemahiran" name="tahap_kemahiran" required>
                              <option value="">Pilih Tahap</option>
                              <option value="Pemula">Pemula</option>
                              <option value="Sederhana">Sederhana</option>
                              <option value="Mahir">Mahir</option>
                              <option value="Pakar">Pakar</option>
                            </select>
                          </div>
                        </div>
                        
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="sijil">Mempunyai Sijil?</label>
                            <select class="form-control" id="sijil" name="sijil">
                              <option value="Ya">Ya</option>
                              <option value="Tidak">Tidak</option>
                            </select>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="tahun_kemahiran">Tahun Diperoleh</label>
                            <input type="number" class="form-control" id="tahun_kemahiran" name="tahun_kemahiran" min="1900" max="2099">
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label for="pengiktirafan">Pengiktirafan (Jika ada)</label>
                          <input type="text" class="form-control" id="pengiktirafan" name="pengiktirafan">
                        </div>
                        
                        <div class="form-group">
                          <label for="keterangan">Keterangan Tambahan</label>
                          <textarea class="form-control" id="keterangan" name="keterangan" rows="2"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Simpan Kemahiran</button>
                        <button type="button" class="btn btn-secondary" onclick="hideSkillForm()">Batal</button>
                      </div>
                    
                    <!-- Maklumat Keluarga -->
                    <?php elseif ($activeTab === 'maklumat_keluarga'): ?>
                      <div class="section-title">MAKLUMAT KELUARGA</div>
                      
                      <div class="form-group">
                        <label for="records">Papar</label>
                        <select id="records" name="records" onchange="this.form.submit()">
                          <option value="5" <?php echo ($recordsPerPage === 5) ? 'selected' : ''; ?>>5</option>
                          <option value="10" <?php echo ($recordsPerPage === 10) ? 'selected' : ''; ?>>10</option>
                          <option value="25" <?php echo ($recordsPerPage === 25) ? 'selected' : ''; ?>>25</option>
                          <option value="50" <?php echo ($recordsPerPage === 50) ? 'selected' : ''; ?>>50</option>
                        </select>
                        <span style="margin-left: 5px;">rekod</span>
                      </div>

                      <table class="table">
                        <thead>
                          <tr>
                            <th width="5%">#<span class="sort-icon"></span></th>
                            <th width="25%">Nama<span class="sort-icon"></span></th>
                            <th width="15%">Hubungan<span class="sort-icon"></span></th>
                            <th width="15%">No. KP<span class="sort-icon"></span></th>
                            <th width="15%">No. Telefon<span class="sort-icon"></span></th>
                            <th width="25%">Pekerjaan</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td colspan="6" class="empty-data">Tiada data</td>
                          </tr>
                        </tbody>
                      </table>

                      <div class="page-info">
                        Papar 0 hingga 0 daripada 0 rekod
                      </div>

                      <button type="button" class="btn btn-primary" onclick="showFamilyForm()">
                        Tambah Ahli Keluarga
                      </button>
                      
                      <!-- Family Form (Hidden by default) -->
                      <div id="familyForm" style="display: none; margin-top: 20px;">
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="nama_ahli_keluarga">Nama Penuh</label>
                            <input type="text" class="form-control" id="nama_ahli_keluarga" name="nama_ahli_keluarga" required>
                          </div>
                          <div class="form-group col-md-6">
                            <label for="hubungan">Hubungan</label>
                            <select class="form-control" id="hubungan" name="hubungan" required>
                              <option value="">Pilih Hubungan</option>
                              <option value="Isteri">Isteri</option>
                              <option value="Suami">Suami</option>
                              <option value="Anak">Anak</option>
                              <option value="Bapa">Bapa</option>
                              <option value="Ibu">Ibu</option>
                              <option value="Adik">Adik</option>
                              <option value="Kakak">Kakak</option>
                              <option value="Abang">Abang</option>
                            </select>
                          </div>
                        </div>
                        
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="no_kp_ahli">No. Kad Pengenalan</label>
                            <input type="text" class="form-control" id="no_kp_ahli" name="no_kp_ahli">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="no_tel_ahli">No. Telefon</label>
                            <input type="tel" class="form-control" id="no_tel_ahli" name="no_tel_ahli">
                          </div>
                        </div>
                        
                        <div class="form-row">
                          <div class="form-group col-md-6">
                            <label for="tarikh_lahir_ahli">Tarikh Lahir</label>
                            <input type="date" class="form-control" id="tarikh_lahir_ahli" name="tarikh_lahir_ahli">
                          </div>
                          <div class="form-group col-md-6">
                            <label for="pekerjaan_ahli">Pekerjaan</label>
                            <input type="text" class="form-control" id="pekerjaan_ahli" name="pekerjaan_ahli">
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label for="alamat_ahli">Alamat (Jika berbeza)</label>
                          <textarea class="form-control" id="alamat_ahli" name="alamat_ahli" rows="2"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Simpan Ahli Keluarga</button>
                        <button type="button" class="btn btn-secondary" onclick="hideFamilyForm()">Batal</button>
                      </div>
                    <?php endif; ?>
                  </div>
                </form>
              </div>
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
  <script>
    // Function to show/hide education form
    function showEducationForm() {
      document.getElementById('educationForm').style.display = 'block';
    }
    function hideEducationForm() {
      document.getElementById('educationForm').style.display = 'none';
    }
    
    // Function to show/hide employment form
    function showEmploymentForm() {
      document.getElementById('employmentForm').style.display = 'block';
    }
    function hideEmploymentForm() {
      document.getElementById('employmentForm').style.display = 'none';
    }
    
    // Function to show/hide skill form
    function showSkillForm() {
      document.getElementById('skillForm').style.display = 'block';
    }
    function hideSkillForm() {
      document.getElementById('skillForm').style.display = 'none';
    }
    
    // Function to show/hide family form
    function showFamilyForm() {
      document.getElementById('familyForm').style.display = 'block';
    }
    function hideFamilyForm() {
      document.getElementById('familyForm').style.display = 'none';
    }
  </script>
</body>
</html>

  