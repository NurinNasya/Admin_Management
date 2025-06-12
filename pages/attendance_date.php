<?php
// dummy data dulu sb nak uji ui 
$user_data = [
    'name' => 'NURUL ATHIRA BINTI ZULKIFLI',
    'ic_number' => '#',
    'staff_no' => '#',
    'company' => 'AGFRSB',
    'department' => 'IT'
];
//dummy data dulu 
$attendance_data = [
    ['month' => 'Jan', 'total' => 0, 'early' => 0, 'late' => 0],
    ['month' => 'Feb', 'total' => 0, 'early' => 0, 'late' => 0],
    ['month' => 'Mar', 'total' => 15, 'early' => 15, 'late' => 0],
    ['month' => 'Apr', 'total' => 21, 'early' => 21, 'late' => 0],
    ['month' => 'May', 'total' => 21, 'early' => 21, 'late' => 0],
    ['month' => 'Jun', 'total' => 6, 'early' => 6, 'late' => 0],
    ['month' => 'Jul', 'total' => 0, 'early' => 0, 'late' => 0],
    ['month' => 'Aug', 'total' => 0, 'early' => 0, 'late' => 0],
    ['month' => 'Sep', 'total' => 0, 'early' => 0, 'late' => 0],
    ['month' => 'Oct', 'total' => 0, 'early' => 0, 'late' => 0],
    ['month' => 'Nov', 'total' => 0, 'early' => 0, 'late' => 0],
    ['month' => 'Dec', 'total' => 0, 'early' => 0, 'late' => 0]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIMS Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 200px;
            background-color: #2c5aa0;
            color: white;
            padding: 20px 0;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid #3a6bb3;
            margin-bottom: 10px;
        }

        .sidebar-header h3 {
            font-size: 16px;
            font-weight: bold;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            padding: 8px 20px;
            border-bottom: 1px solid #3a6bb3;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-item:hover {
            background-color: #3a6bb3;
        }

        .nav-item.active {
            background-color: #4CAF50;
        }

        .nav-item .arrow {
            font-size: 10px;
        }

        /* Main Content Styles */
        .main-content {
            flex: 1;
            padding: 20px;
            background-color: white;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .breadcrumb {
            color: #666;
            font-size: 12px;
        }

        /* Profile Section */
        .profile-section {
            display: flex;
            gap: 30px;
            margin-bottom: 40px;
        }

        .profile-image {
            width: 80px;
            height: 80px;
            border: 2px solid #ddd;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
        }

        .profile-image svg {
            width: 50px;
            height: 50px;
            fill: #999;
        }

        .profile-info h2 {
            color: #2c5aa0;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .profile-details {
            font-size: 12px;
            line-height: 1.5;
        }

        .profile-details span {
            color: #666;
            margin-right: 10px;
        }

        .change-picture {
            color: #2c5aa0;
            font-size: 10px;
            text-decoration: underline;
            cursor: pointer;
            margin-top: 5px;
        }

        /* Year Selector */
        .year-selector {
            float: right;
            margin-bottom: 20px;
        }

        .year-selector select {
            padding: 5px 10px;
            border: 1px solid #ddd;
            background-color: white;
        }

        /* Performance Analysis */
        .performance-section {
            clear: both;
        }

        .performance-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .performance-header h3 {
            font-size: 16px;
            color: #333;
            margin-right: 10px;
        }

        .menu-icon {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .chart-subtitle {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-bottom: 20px;
        }

        /* Chart Styles */
        .chart-container {
            margin-bottom: 30px;
        }

        .chart {
            display: flex;
            align-items: end;
            justify-content: space-around;
            height: 200px;
            border-bottom: 1px solid #ddd;
            padding: 20px 0;
        }

        .chart-bar {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
        }

        .bar-group {
            display: flex;
            align-items: end;
            gap: 2px;
            margin-bottom: 10px;
        }

        .bar {
            width: 15px;
            border-radius: 2px 2px 0 0;
            position: relative;
        }

        .bar.total {
            background-color: #333;
        }

        .bar.early {
            background-color: #4CAF50;
        }

        .bar.late {
            background-color: #f44336;
        }

        .bar-value {
            position: absolute;
            top: -15px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 10px;
            font-weight: bold;
        }

        .month-label {
            font-size: 10px;
            color: #666;
        }

        /* Legend */
        .legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .legend-color.total {
            background-color: #333;
        }

        .legend-color.early {
            background-color: #4CAF50;
        }

        .legend-color.late {
            background-color: #f44336;
        }

        /* Attendance Summary */
        .attendance-summary {
            background-color: #e8f4f8;
            padding: 20px;
            border-radius: 5px;
        }

        .summary-header {
            background-color: #b8d9e8;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
            border-radius: 3px;
        }

        .summary-row {
            display: flex;
            background-color: white;
            border: 1px solid #ddd;
        }

        .summary-cell {
            flex: 1;
            padding: 10px;
            text-align: center;
            border-right: 1px solid #ddd;
            font-size: 12px;
        }

        .summary-cell:last-child {
            border-right: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- SIDEBAR COMMENTED OUT //nanti akan letak template system-->
        <?php /*
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>AIMS Office Portal</h3>
            </div>
            <ul class="nav-menu">
                <li class="nav-item active">
                    🏠 DASHBOARD
                </li>
                <li class="nav-item">
                    📊 PERSONAL ATTENDANCE ANALYSIS <span class="arrow">▼</span>
                </li>
                <li class="nav-item">
                    📋 LAPORAN KEHADIRAN PERIBADI <span class="arrow">▼</span>
                </li>
                <li class="nav-item">
                    📝 HR FORM
                </li>
                <li class="nav-item">
                    📄 TERMS & CONDITION
                </li>
                <li class="nav-item">
                    🏖️ LEAVE <span class="arrow">▼</span>
                </li>
                <li class="nav-item">
                    📥 IN/OUT <span class="arrow">▼</span>
                </li>
                <li class="nav-item">
                    ⏰ OVERTIME <span class="arrow">▼</span>
                </li>
                <li class="nav-item">
                    🎯 CLAIM <span class="arrow">▼</span>
                </li>
                <li class="nav-item">
                    ✈️ TRAVEL AUTHORIZATION <span class="arrow">▼</span>
                </li>
                <li class="nav-item">
                    🎓 TRAINING REQUEST <span class="arrow">▼</span>
                </li>
                <li class="nav-item">
                    💰 REQUEST CASH ADVANCE <span class="arrow">▼</span>
                </li>
                <li class="nav-item">
                    🏢 CORPORATE ADMINISTRATION <span class="arrow">▼</span>
                </li>
                <li class="nav-item">
                    👥 PENGURUSAN SUMBER MANUSIA <span class="arrow">▼</span>
                </li>
                <li class="nav-item">
                    🛒 PROCUREMENT <span class="arrow">▼</span>
                </li>
            </ul>
        </div>
        */ ?>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
                <div class="breadcrumb">Personal Information</div>
            </div>

            <div class="profile-section">
                <div>
                    <div class="profile-image">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                    <div class="change-picture">Change Picture ✏️</div>
                </div>
                <div class="profile-info">
                    <h2>Welcome, <?php echo $user_data['name']; ?></h2>
                    <div class="profile-details">
                        <div><span>IC Number :</span> <?php echo $user_data['ic_number']; ?></div>
                        <div><span>Staff No :</span> <?php echo $user_data['staff_no']; ?></div>
                        <div><span>Company :</span> <?php echo $user_data['company']; ?></div>
                        <div><span>Department :</span> <?php echo $user_data['department']; ?></div>
                    </div>
                </div>
            </div>

            <div class="year-selector">
                <select>
                    <option value="2025" selected>2025</option>
                    <option value="2024">2024</option>
                    <option value="2023">2023</option>
                </select>
            </div>

            <div class="performance-section">
                <div class="performance-header">
                    <h3>Performance Analysis</h3>
                    <div class="menu-icon">☰</div>
                </div>
                
                <!-- STAFF ATTENDANCE CHART COMMENTED OUT//tutup dulu graff  -->
                <?php /*
                <div class="chart-subtitle">Staff Attendance</div>

                <div class="chart-container">
                    <div class="chart">
                        <?php foreach ($attendance_data as $data): ?>
                        <div class="chart-bar">
                            <div class="bar-group">
                                <?php if ($data['total'] > 0): ?>
                                <div class="bar total" style="height: <?php echo ($data['total'] * 8); ?>px;">
                                    <div class="bar-value"><?php echo $data['total']; ?></div>
                                </div>
                                <div class="bar early" style="height: <?php echo ($data['early'] * 8); ?>px;">
                                    <?php if ($data['early'] > 0): ?>
                                    <div class="bar-value"><?php echo $data['early']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($data['late'] > 0): ?>
                                <div class="bar late" style="height: <?php echo ($data['late'] * 8); ?>px;">
                                    <div class="bar-value"><?php echo $data['late']; ?></div>
                                </div>
                                <?php endif; ?>
                                <?php else: ?>
                                <div class="bar total" style="height: 20px;">
                                    <div class="bar-value">0</div>
                                </div>
                                <div class="bar early" style="height: 20px;"></div>
                                <div class="bar late" style="height: 20px;"></div>
                                <?php endif; ?>
                            </div>
                            <div class="month-label"><?php echo $data['month']; ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-color total"></div>
                        <span>Total Attendance</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color early"></div>
                        <span>Early/Punctual</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color late"></div>
                        <span>Late</span>
                    </div>
                </div>
                */ ?>

                <!-- ATTENDANCE TABLE - KEPT VISIBLE -->
                <div class="attendance-summary">
                    <div class="summary-header">Attendance</div>
                    <div class="summary-row">
                        <div class="summary-cell">Total Working Days</div>
                        <div class="summary-cell">Total of Attendance</div>
                        <div class="summary-cell">Punctuality Percent</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>