<?php
session_start();
require_once '../db.php';
require_once '../Model/employee.php';
require_once '../Model/depart.php';

try {
    $employeeModel = new Employee();
    $departModel = new Depart();
    $allStaff = $employeeModel->getAllStaffWithDepartment();
    $departments = $departModel->getAllDepartments();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

class EmployeeController {
    public $model;
    private $departModel;

    public function __construct() {
        $this->model = new Employee();
        $this->departModel = new Depart();
    }

    public function getAllEmployees(): array {
        return $this->model->getAllEmployees();
    }

    public function getDepartments(): array {
        return $this->departModel->getAllDepartments();
    }

    public function store() {
        $profilePicName = '';

        if (!empty($_FILES['profile_pic']['name'])) {
            $targetDir = '../uploads/';
            $profilePicName = basename($_FILES['profile_pic']['name']);
            $targetFile = $targetDir . $profilePicName;
            move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile);
        }

        $data = [
            'noic' => $_POST['noic'] ?? '',
            'name' => $_POST['name'] ?? '',
            'pwd' => $_POST['pwd'] ?? '',
            'email' => $_POST['email'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'gender' => $_POST['gender'] ?? '',
            'status_marital' => $_POST['status_marital'] ?? '',
            'dependent' => $_POST['dependent'] ?? '',
            'staff_no' => $_POST['staff_no'] ?? '',
            'permenant_address' => $_POST['permenant_address'] ?? '',
            'mail_address' => $_POST['mail_address'] ?? '',
            'roles' => $_POST['roles'] ?? '',
            'roles_status' => $_POST['roles_status'] ?? '',
            'profile_pic' => $profilePicName,
            'departments_id' => $_POST['departments_id'] ?? ''
        ];

        $this->model->insertEmployee($data);
        $_SESSION['success_message'] = "Employee added successfully.";
        header("Location: ../pages/staff.php");
        exit();
    }

    public function update() {
        $id = $_POST['edit_id'];
        $noic = trim($_POST['edit_noic']);
        $email = trim($_POST['edit_email']);

        $existing = $this->model->checkDuplicate($noic, $email, $id);

        if ($existing->num_rows > 0) {
            $record = $existing->fetch_assoc();
            if ($record['noic'] === $noic && $record['email'] === $email) {
                $_SESSION['error_message'] = "NRIC and Email already exist.";
            } elseif ($record['noic'] === $noic) {
                $_SESSION['error_message'] = "NRIC already exists.";
            } elseif ($record['email'] === $email) {
                $_SESSION['error_message'] = "Email already exists.";
            }
            header("Location: ../pages/staff.php");
            exit;
        }

        $profilePicName = $_POST['existing_pic'] ?? '';

        if (!empty($_FILES['edit_profile_pic']['name'])) {
            $targetDir = '../uploads/';
            $profilePicName = basename($_FILES['edit_profile_pic']['name']);
            $targetFile = $targetDir . $profilePicName;
            move_uploaded_file($_FILES['edit_profile_pic']['tmp_name'], $targetFile);
        }

        $data = [
            'noic' => $noic,
            'name' => $_POST['edit_name'] ?? '',
            'pwd' => $_POST['edit_pwd'] ?? '',
            'email' => $email,
            'phone' => $_POST['edit_phone'] ?? '',
            'gender' => $_POST['edit_gender'] ?? '',
            'statusmarital_' => $_POST['edit_status_marital'] ?? '',
            'dependent' => $_POST['edit_dependent'] ?? '',
            'staff_no' => $_POST['edit_staff_no'] ?? '',
            'permenant_address' => $_POST['edit_permenant_address'] ?? '',
            'mail_address' => $_POST['edit_mail_address'] ?? '',
            'roles' => $_POST['edit_roles'] ?? '',
            'roles_status' => $_POST['edit_roles_status'] ?? '',
            'profile_pic' => $profilePicName,
            'departments_id' => $_POST['edit_departments_id'] ?? ''
        ];

        if ($this->model->updateEmployee($id, $data)) {
            $_SESSION['success_message'] = "Employee updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update employee.";
        }

        header("Location: ../pages/staff.php");
        exit;
    }
}

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new EmployeeController();

    if (isset($_GET['action']) && $_GET['action'] === 'save') {
        $controller->store();
    } elseif (isset($_GET['action']) && $_GET['action'] === 'update') {
        $controller->update();
    }
}
