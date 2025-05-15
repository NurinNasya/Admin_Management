<?php
require_once 'model/Employee.php';

class EmployeeController {
    private $employeeModel;

    public function __construct() {
        $this->employeeModel = new Employee();
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'ic_number' => $_POST['ic_number'] ?? '',
                'name' => $_POST['name'] ?? '',
                'dob' => $_POST['dob'] ?? '',
                'status' => $_POST['status'] ?? '',
                'gender' => $_POST['gender'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'mailing_address' => $_POST['mailing_address'] ?? '',
                'position' => $_POST['position'] ?? '',
                'department' => $_POST['department'] ?? '',
                'grade' => $_POST['grade'] ?? '',
                'staff_no' => $_POST['staff_no'] ?? '',
                'appointment_date' => $_POST['appointment_date'] ?? '',
                'position_status' => $_POST['position_status'] ?? '',
            ];

            // Save to database
            $this->employeeModel->insertEmployee($data);
            
            // Redirect with success flag
            header("Location: pages/employee.php?success=1");
            exit;
        }
    }
}
