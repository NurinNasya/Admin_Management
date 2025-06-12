<?php
// dummy data dulu sebab nak uji layout 
$employee = [
    'name' => 'NURUL ATHIRA BINTI ZULKIFLI',
    'ic_number' => '#',
    'staff_no' => '#',
    'role' => 'INTERN',
    'company' => 'AGIRSB - AIMS GLOBAL INTEGRITY RESOURCES SDN BHD',
    'location' => 'HQ - AIMS GROUP'
];
//dummy data 
$attendance_summary = [
    'total_working_days' => 22,
    'total_attendance' => 6,
    'early' => 6,
    'punctual' => 0,
    'late' => 0,
    'attendance_percentage' => 27.27
];
//dummy data 
$attendance_records = [
    [
        'no' => 1,
        'day' => 'Thursday',
        'date_time_in' => '2025-06-12 07:51:54',
        'date_time_out' => '',
        'platform_in' => 'MOBILE-IMBASANCR',
        'platform_out' => '',
        'location_in' => 'HQ',
        'location_out' => '',
        'status' => 'EARLY',
        'reason' => '',
        'action' => ''
    ],
    [
        'no' => 2,
        'day' => 'Wednesday',
        'date_time_in' => '2025-06-11 07:51:10',
        'date_time_out' => '2025-06-11 17:18:28',
        'platform_in' => 'MOBILE-IMBASANCR',
        'platform_out' => 'MOBILE-IMBASANCR',
        'location_in' => 'HQ',
        'location_out' => 'HQ',
        'status' => 'EARLY',
        'reason' => '09:27:18',
        'action' => ''
    ],
    [
        'no' => 3,
        'day' => 'Tuesday',
        'date_time_in' => '2025-06-10 07:49:26',
        'date_time_out' => '2025-06-10 17:03:34',
        'platform_in' => 'MOBILE-IMBASANCR',
        'platform_out' => 'MOBILE-IMBASANCR',
        'location_in' => 'HQ',
        'location_out' => 'HQ',
        'status' => 'EARLY',
        'reason' => '09:14:34',
        'action' => ''
    ],
    [
        'no' => 4,
        'day' => 'Thursday',
        'date_time_in' => '2025-06-09 07:50:14',
        'date_time_out' => '2025-06-09 17:06:52',
        'platform_in' => 'MOBILE-IMBASANCR',
        'platform_out' => 'MOBILE-IMBASANCR',
        'location_in' => 'HQ',
        'location_out' => 'HQ',
        'status' => 'EARLY',
        'reason' => '09:16:38',
        'action' => ''
    ]
];
// nanti boleh letak side bar template ,ni sekadar dummy data 
$menu_items = [
    ['icon' => '👤', 'title' => 'AKAUN/AIMS-OFFICE', 'active' => false],
    ['icon' => '📊', 'title' => 'DASHBOARD PERIBADI', 'active' => false],
    ['icon' => '📈', 'title' => 'DASHBOARD', 'active' => false],
    ['icon' => '📋', 'title' => 'PERSONAL ATTENDANCE ANALYSIS', 'active' => true],
    ['icon' => '📑', 'title' => 'LAPORAN KEHADIRAN PERIBADI', 'active' => false],
    ['icon' => '📄', 'title' => 'HR FORM', 'active' => false],
    ['icon' => '📜', 'title' => 'TERMS & CONDITION', 'active' => false],
    ['icon' => '🚪', 'title' => 'LEAVE', 'active' => false],
    ['icon' => '📥', 'title' => 'IN/OUT', 'active' => false],
    ['icon' => '⏰', 'title' => 'OVERTIME', 'active' => false],
    ['icon' => '💰', 'title' => 'CLAIM', 'active' => false],
    ['icon' => '✈️', 'title' => 'TRAVEL AUTHORIZATION', 'active' => false],
    ['icon' => '🎓', 'title' => 'TRAINING REQUEST', 'active' => false],
    ['icon' => '💵', 'title' => 'REQUEST CASH ADVANCE', 'active' => false],
    ['icon' => '🏢', 'title' => 'CORPORATE ADMINISTRATION', 'active' => false],
    ['icon' => '📊', 'title' => 'PENGURUSAN SUMBER MANUSIA', 'active' => false],
    ['icon' => '🛒', 'title' => 'PROCUREMENT', 'active' => false]
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIMS-Office - Personal Attendance Analysis</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            overflow-y: auto;
            position: fixed;
            height: 100vh;
            left: 0;
            top: 0;
        }

        .sidebar-header {
            padding: 15px;
            border-bottom: 1px solid #34495e;
            background-color: #34495e;
        }

        .sidebar-header h3 {
            font-size: 14px;
            color: #ecf0f1;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            border-bottom: 1px solid #34495e;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #bdc3c7;
            text-decoration: none;
            font-size: 12px;
            transition: background-color 0.3s;
        }

        .sidebar-menu a:hover {
            background-color: #34495e;
            color: white;
        }

        .sidebar-menu a.active {
            background-color: #3498db;
            color: white;
        }

        .sidebar-menu .icon {
            margin-right: 10px;
            font-size: 14px;
        }

        .main-content {
            margin-left: 0;
            flex: 1;
            padding: 20px;
            background-color: #ecf0f1;
        }

        .header {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .employee-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            font-size: 14px;
        }

        .employee-info div {
            display: flex;
        }

        .employee-info label {
            font-weight: bold;
            color: #2c3e50;
            width: 100px;
            margin-right: 10px;
        }

        .employee-info span {
            color: #7f8c8d;
        }

        .attendance-summary {
            background-color: #e74c3c;
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .attendance-summary h3 {
            text-align: center;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 10px;
            text-align: center;
        }

        .summary-item {
            background-color: rgba(255,255,255,0.1);
            padding: 10px;
            border-radius: 4px;
        }

        .summary-item h4 {
            font-size: 12px;
            margin-bottom: 5px;
            opacity: 0.9;
        }

        .summary-item .value {
            font-size: 18px;
            font-weight: bold;
        }

        .summary-item.total-days .value {
            color: #ffffff;
        }

        .summary-item.total-attendance .value {
            color: #3498db;
        }

        .summary-item.early .value {
            color: #2ecc71;
        }

        .summary-item.punctual .value {
            color: #9b59b6;
        }

        .summary-item.late .value {
            color: #e74c3c;
        }

        .summary-item.percentage .value {
            color: #f39c12;
        }

        .attendance-table {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .table-header {
            background-color: #34495e;
            color: white;
            padding: 15px;
            font-size: 14px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 8px;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
            font-size: 12px;
        }

        th {
            background-color: #bdc3c7;
            color: #2c3e50;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tr:hover {
            background-color: #e8f4f8;
        }

        .status-early {
            color: #27ae60;
            font-weight: bold;
        }

        .status-late {
            color: #e74c3c;
            font-weight: bold;
        }

        .date-in {
            color: #3498db;
        }

        .date-out {
            color: #e67e22;
        }

        .platform {
            color: #8e44ad;
            font-size: 11px;
        }

        .location {
            color: #16a085;
            font-weight: bold;
        }

            @media (max-width: 768px) {
                .summary-grid {
                    grid-template-columns: repeat(3, 1fr);
                }
            }
    </style>
</head>
<body>
    <!-- Sidebar commented out -->
    <!--
    <div class="sidebar">
        <div class="sidebar-header">
            <h3>AIMS-Office</h3>
            <small>Performance through People</small>
        </div>
        <ul class="sidebar-menu">
            <?php foreach ($menu_items as $item): ?>
            <li>
                <a href="#" class="<?php echo $item['active'] ? 'active' : ''; ?>">
                    <span class="icon"><?php echo $item['icon']; ?></span>
                    <?php echo $item['title']; ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    -->

    <div class="main-content">
        <div class="header">
            <h1>Personal Attendance Analysis</h1>
            <div class="employee-info">
                <div>
                    <label>Name:</label>
                    <span><?php echo $employee['name']; ?></span>
                </div>
                <div>
                    <label>IC Number:</label>
                    <span><?php echo $employee['ic_number']; ?></span>
                </div>
                <div>
                    <label>Staff No:</label>
                    <span><?php echo $employee['staff_no']; ?></span>
                </div>
                <div>
                    <label>Role:</label>
                    <span><?php echo $employee['role']; ?></span>
                </div>
                <div>
                    <label>Company:</label>
                    <span><?php echo $employee['company']; ?></span>
                </div>
                <div>
                    <label>Location:</label>
                    <span><?php echo $employee['location']; ?></span>
                </div>
            </div>
        </div>

        <div class="attendance-summary">
            <h3>Personal Attendance Analysis</h3>
            <div class="summary-grid">
                <div class="summary-item total-days">
                    <h4>Total Working Days</h4>
                    <div class="value"><?php echo $attendance_summary['total_working_days']; ?></div>
                </div>
                <div class="summary-item total-attendance">
                    <h4>Total of Attendance</h4>
                    <div class="value"><?php echo $attendance_summary['total_attendance']; ?></div>
                </div>
                <div class="summary-item early">
                    <h4>Early</h4>
                    <div class="value"><?php echo $attendance_summary['early']; ?></div>
                </div>
                <div class="summary-item punctual">
                    <h4>Punctual</h4>
                    <div class="value"><?php echo $attendance_summary['punctual']; ?></div>
                </div>
                <div class="summary-item late">
                    <h4>Late</h4>
                    <div class="value"><?php echo $attendance_summary['late']; ?></div>
                </div>
                <div class="summary-item percentage">
                    <h4>Attendance Percentage</h4>
                    <div class="value"><?php echo $attendance_summary['attendance_percentage']; ?>%</div>
                </div>
            </div>
        </div>

        <div class="attendance-table">
            <div class="table-header">
                Personal Attendance Information OVERALL
            </div>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Day</th>
                        <th>Date & Time</th>
                        <th>Platform</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Reason for Late</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($attendance_records as $record): ?>
                    <tr>
                        <td><?php echo $record['no']; ?></td>
                        <td><?php echo $record['day']; ?></td>
                        <td>
                            <div>
                                <strong>In:</strong> <span class="date-in"><?php echo $record['date_time_in']; ?></span><br>
                                <?php if ($record['date_time_out']): ?>
                                <strong>Out:</strong> <span class="date-out"><?php echo $record['date_time_out']; ?></span>
                                <?php else: ?>
                                <strong>Out:</strong> <span style="color: #95a5a6;">-</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>In:</strong> <span class="platform"><?php echo $record['platform_in']; ?></span><br>
                                <strong>Out:</strong> <span class="platform"><?php echo $record['platform_out'] ?: '-'; ?></span>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>In:</strong> <span class="location"><?php echo $record['location_in']; ?></span><br>
                                <strong>Out:</strong> <span class="location"><?php echo $record['location_out'] ?: '-'; ?></span>
                            </div>
                        </td>
                        <td>
                            <span class="status-<?php echo strtolower($record['status']); ?>">
                                <?php echo $record['status']; ?>
                            </span>
                        </td>
                        <td><?php echo $record['reason'] ?: '-'; ?></td>
                        <td><?php echo $record['action'] ?: '-'; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>