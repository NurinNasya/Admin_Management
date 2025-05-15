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
                'staff_no'          => $_POST['staff_no'] ?? '',
                'noic'              => $_POST['noic'] ?? '',
                'pwd'               => $_POST['pwd'] ?? '',
                'name'              => $_POST['name'] ?? '',
                'email'             => $_POST['email'] ?? '',
                'roles'             => $_POST['roles'] ?? '',
                'roles_status'      => $_POST['roles_status'] ?? '',
                'dept_id'           => $_POST['dept_id'] ?? 0,
                'company_id'        => $_POST['company_id'] ?? 0,
                'status'            => $_POST['status'] ?? 1,
                'gender'            => $_POST['gender'] ?? '',
                'shift_id'          => $_POST['shift_id'] ?? 0,
                'leave_approval'    => $_POST['leave_approval'] ?? 0,
                'permenant_address' => $_POST['permenant_address'] ?? '',
                'mail_address'      => $_POST['mail_address'] ?? '',
                'status_qrcode'     => $_POST['status_qrcode'] ?? 0,
                'status_swafoto'    => $_POST['status_swafoto'] ?? 0,
                'status_monitor'    => $_POST['status_monitor'] ?? 0,
                'status_martial'    => $_POST['status_martial'] ?? 0,
                'dependent'         => $_POST['dependent'] ?? 0,
                'document_name'     => $_POST['document_name'] ?? '',
                'document_size'     => $_POST['document_size'] ?? 0,
                'created_by'        => $_POST['created_by'] ?? 0,
                'updated_by'        => $_POST['updated_by'] ?? 0,
            ];

            // Save to database
            $this->employeeModel->insertStaff($data); // assuming insertStaff() matches the table name

            // Redirect with success flag
            header("Location: pages/employee.php?success=1");
            exit;
        }
    }
}
