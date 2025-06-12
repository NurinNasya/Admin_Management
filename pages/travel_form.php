<?php
// guna dummy data dulu sb nak tengok design 
$staff_data = [
    'staff_no' => '#',
    'name' => 'NURUL ATHIRA BINTI ZULKIFLI',
    'company' => 'AGIRSB',
    'department' => 'IT'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Authorization Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header {
            background-color: #2c5aa0;
            color: white;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
        }

        .breadcrumb {
            background-color: #f8f9fa;
            padding: 10px 20px;
            border-bottom: 1px solid #dee2e6;
            font-size: 12px;
            color: #6c757d;
        }

        .breadcrumb a {
            color: #007bff;
            text-decoration: none;
        }

        .content {
            padding: 20px;
        }

        .section-header {
            background-color: #2c5aa0;
            color: white;
            padding: 10px 15px;
            margin: 20px 0 15px 0;
            font-weight: bold;
            font-size: 14px;
        }

        .section-header:first-child {
            margin-top: 0;
        }

        .form-row {
            display: flex;
            margin-bottom: 15px;
            gap: 20px;
        }

        .form-group {
            flex: 1;
        }

        .form-group.half {
            flex: 0.5;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 13px;
        }

        .form-group label .required {
            color: red;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 13px;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 8px;
            font-size: 13px;
        }

        .info-table td:first-child {
            font-weight: bold;
            width: 120px;
        }

        .radio-group {
            display: flex;
            gap: 15px;
            margin-top: 5px;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .date-range {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .date-input {
            flex: 1;
        }

        .until-label {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .checkbox-group {
            margin: 20px 0;
        }

        .checkbox-item {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-top: 10px;
        }

        .checkbox-item input[type="checkbox"] {
            margin-top: 3px;
        }

        .form-actions {
            text-align: right;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 10px;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-primary {
            background-color: #007bff;
            color: white;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .footer {
            background-color: #2c5aa0;
            color: white;
            padding: 15px 20px;
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
        }

        .footer-links {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        textarea.form-control {
            min-height: 80px;
            resize: vertical;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 TRAVEL AUTHORIZATION FORM</h1>
        </div>
        
        <div class="breadcrumb">
            <a href="#">Dashboard</a> / <a href="#">Travel Authorization</a> / Form
        </div>

        <div class="content">
            <form method="POST" action="">
                <!-- Staff Information Section -->
                <div class="section-header">STAFF INFORMATION</div>
                <table class="info-table">
                    <tr>
                        <td>Staff No</td>
                        <td>: <?php echo htmlspecialchars($staff_data['staff_no']); ?></td>
                    </tr>
                    <tr>
                        <td>Name</td>
                        <td>: <?php echo htmlspecialchars($staff_data['name']); ?></td>
                    </tr>
                    <tr>
                        <td>Company</td>
                        <td>: <?php echo htmlspecialchars($staff_data['company']); ?></td>
                    </tr>
                    <tr>
                        <td>Department</td>
                        <td>: <?php echo htmlspecialchars($staff_data['department']); ?></td>
                    </tr>
                </table>

                <div class="form-row">
                    <!-- Travel Authorization Section -->
                    <div class="form-group">
                        <div class="section-header">TRAVEL AUTHORIZATION</div>
                        
                        <div class="form-group">
                            <label>Company Claim <span class="required">*</span></label>
                            <select name="company_claim" class="form-control" required>
                                <option value="">-- Select Company Claim --</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Mode of Travel <span class="required">*</span></label>
                                <select name="mode_of_travel" class="form-control" required>
                                    <option value="">-- Select Mode --</option>
                                    <option value="company_car" selected>COMPANY CAR</option>
                                    <option value="flight">Flight</option>
                                    <option value="bus">Bus</option>
                                    <option value="train">Train</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Destination <span class="required">*</span></label>
                                <input type="text" name="destination" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Departure <span class="required">*</span> <small>(Estimate departure)</small></label>
                                <input type="datetime-local" name="departure" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Return <span class="required">*</span> <small>(Estimate return)</small></label>
                                <input type="datetime-local" name="return" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label>Advance Required <span class="required">*</span></label>
                                <input type="text" name="advance_required" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Job No</label>
                                <input type="text" name="job_no" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Purpose <span class="required">*</span></label>
                            <textarea name="purpose" class="form-control" required placeholder="Enter purpose of travel..."></textarea>
                        </div>
                    </div>

                    <!-- Apartment/Hotel and Transportation Section -->
                    <div class="form-group">
                        <div class="section-header">APARTMENT / HOTEL ACCOMMODATION</div>
                        
                        <div class="form-group">
                            <label>Request hotel <span class="required">*</span></label>
                            <div class="radio-group">
                                <div class="radio-item">
                                    <input type="radio" name="request_hotel" value="yes" id="hotel_yes">
                                    <label for="hotel_yes">Yes</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" name="request_hotel" value="no" id="hotel_no">
                                    <label for="hotel_no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Check In / Out</label>
                            <div class="date-range">
                                <input type="date" name="check_in" class="form-control date-input" placeholder="dd/mm/yyyy">
                                <span class="until-label">until</span>
                                <input type="date" name="check_out" class="form-control date-input" placeholder="dd/mm/yyyy">
                            </div>
                        </div>

                        <div class="section-header">TRANSPORTATION</div>
                        
                        <div class="form-group">
                            <label>Transportation From Airport (KT/KUA) <span class="required">*</span></label>
                            <div class="radio-group">
                                <div class="radio-item">
                                    <input type="radio" name="transport_from" value="yes" id="transport_from_yes">
                                    <label for="transport_from_yes">Yes</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" name="transport_from" value="no" id="transport_from_no">
                                    <label for="transport_from_no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Transportation To Airport (KT/KUA) <span class="required">*</span></label>
                            <div class="radio-group">
                                <div class="radio-item">
                                    <input type="radio" name="transport_to" value="yes" id="transport_to_yes">
                                    <label for="transport_to_yes">Yes</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" name="transport_to" value="no" id="transport_to_no">
                                    <label for="transport_to_no">No</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Request for Company Driver <span class="required">*</span></label>
                            <div class="radio-group">
                                <div class="radio-item">
                                    <input type="radio" name="company_driver" value="yes" id="driver_yes">
                                    <label for="driver_yes">Yes</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" name="company_driver" value="no" id="driver_no">
                                    <label for="driver_no">No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="checkbox-group">
                    <div class="checkbox-item">
                        <input type="checkbox" name="acknowledgment" id="acknowledgment" required>
                        <label for="acknowledgment">I am fit for travel, and I acknowledge that any issues arising during the journey are my responsibility.</label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
        <!--TUTUP DULU NANTI KALAU NAK EDIT KITA BUKAK BALIK 
        <div class="footer">
            <div class="footer-links">
                <a href="#">Official Website</a>
                <a href="#">Facebook</a>
                <a href="#">MyFutureJob</a>
            </div>
            <div>
                <strong>MSET-Office ● IT-AIMS</strong><br>
                Hakcipta Terpelihara<br>
                © 2023 AIMS-Global Holdings Sdn Bhd℠
            </div>
        </div>
    </div>  -->

    <script>
        // Simple form validation and interaction
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Basic validation
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.style.borderColor = 'red';
                        isValid = false;
                    } else {
                        field.style.borderColor = '#ced4da';
                    }
                });
                
                if (isValid) {
                    alert('Form submitted successfully!');
                    // y send the data to server
                    console.log('Form data:', new FormData(form));
                } else {
                    alert('Please fill in all required fields.');
                }
            });
            
            // Reset form styling on input
            form.addEventListener('input', function(e) {
                if (e.target.hasAttribute('required')) {
                    e.target.style.borderColor = '#ced4da';
                }
            });
        });
    </script>
</body>
</html>

<?php
// Handle form submission
if ($_POST) {
    echo "<script>console.log('Form submitted with data: " . json_encode($_POST) . "');</script>";
    
    //SINI KITA AKAN process the form data
    // For example: save to database, send email, etc.
    
    /*
    Example processing:
    $travel_data = [
        'staff_no' => $staff_data['staff_no'],
        'company_claim' => $_POST['company_claim'] ?? '',
        'mode_of_travel' => $_POST['mode_of_travel'] ?? '',
        'destination' => $_POST['destination'] ?? '',
        'departure' => $_POST['departure'] ?? '',
        'return' => $_POST['return'] ?? '',
        'advance_required' => $_POST['advance_required'] ?? '',
        'job_no' => $_POST['job_no'] ?? '',
        'purpose' => $_POST['purpose'] ?? '',
        'request_hotel' => $_POST['request_hotel'] ?? '',
        'check_in' => $_POST['check_in'] ?? '',
        'check_out' => $_POST['check_out'] ?? '',
        'transport_from' => $_POST['transport_from'] ?? '',
        'transport_to' => $_POST['transport_to'] ?? '',
        'company_driver' => $_POST['company_driver'] ?? '',
        'acknowledgment' => isset($_POST['acknowledgment']) ? 1 : 0,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // Save to database or process as needed
    */
}
?>