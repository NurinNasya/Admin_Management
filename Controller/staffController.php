<?php
require_once '../model/Staff.php';
require_once '../model/Role.php';
require_once '../model/Depart.php';
require_once '../model/Company.php';
require_once '../model/Shift.php';

class StaffController {
    private $staffModel;
    private $roleModel;
    private $departModel;
    private $compModel;
    private $shiftModel;

    public function __construct() {
        $this->staffModel = new Staff();
        $this->roleModel = new Role();
        $this->departModel = new Depart();
        $this->compModel = new Company();
        $this->shiftModel = new Shift();
    }

    public function index() {
        $staff = $this->staffModel->getAllStaff();
        if ($staff === false) {
            $_SESSION['error'] = "Failed to load staff data";
        }
        require_once '../pages/staff.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $noic = trim($_POST['noic']);
                $phone = trim($_POST['phone']);

                $data = [
                    'staff_no' => $this->staffModel->generateStaffNumber(),
                    'noic' => $noic,
                    'pwd' => substr($noic, -6) . '@' . substr($phone, -4),
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'phone' => $phone,
                    'roles' => trim($_POST['roles']),
                    'roles_status' => trim($_POST['status']),
                    'departments_id' => trim($_POST['departments_id']),
                    'company_id' => trim($_POST['company_id']),
                    'status' => trim($_POST['status']),
                    'gender' => ((int)substr($noic, -1) % 2 === 0) ? 'F' : 'M',
                    'shift_id' => trim($_POST['shift_id']),
                    'leave_approval' => trim($_POST['leave_approval']),
                    'permanent_address' => trim($_POST['permanent_address']),
                    'mail_address' => trim($_POST['mail_address']),
                    'status_qrcode' => trim($_POST['status_qrcode']),
                    'status_swafoto' => trim($_POST['status_swafoto']),
                    'status_monitor' => trim($_POST['status_monitor']),
                    'status_marital' => trim($_POST['status_marital']),
                    'dependent' => trim($_POST['dependent']),
                    'profile_pic' => $_FILES['profile_pic']['name'] ?? null,
                    'created_by' => $_POST['created_by'] ?? null,
                    'document_name' => '',
                    'document_size' => 0
                ];

                // Handle file upload
                if (!empty($_FILES['profile_pic']['tmp_name'])) {
                    $targetDir = "../uploads/";
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }
                    $targetFile = $targetDir . basename($_FILES['profile_pic']['name']);
                    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {
                        $data['profile_pic'] = $_FILES['profile_pic']['name'];
                    } else {
                        $_SESSION['error'] = "Failed to upload profile picture";
                    }
                }

                $result = $this->staffModel->create($data);
                if ($result) {
                    $_SESSION['success'] = "Staff created successfully!";
                    $this->redirect('../pages/staff.php');
                } else {
                    $_SESSION['error'] = "Failed to create staff";
                    $this->redirect('../pages/staff_info.php');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
                error_log($e->getMessage());
                $this->redirect('../pages/staff_info.php');
            }
        } else {
            try {
                $roles = $this->roleModel->getAllRoles();
                $departments = $this->departModel->getAllDepartments();
                $companies = $this->compModel->getAllCompanies();
                $shifts = $this->shiftModel->getAllShifts();
                $staff = $this->staffModel->getAllStaff();
                $generatedStaffNo = $this->staffModel->generateStaffNumber();

                require_once '../pages/staff_info.php';
            } catch (Exception $e) {
                $_SESSION['error'] = "Error loading form data: " . $e->getMessage();
                $this->redirect('../pages/staff.php');
            }
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->staffModel->delete($id);
            if ($result) {
                $_SESSION['success'] = "Staff deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete staff";
            }
            $this->redirect('../pages/staff.php');
        } else {
            $this->redirect('../pages/satff.php');
        }
    }

    private function redirect($path) {
        header("Location: /$path");
        exit();
    }
}

// Handle the request
session_start();

$controller = new StaffController();
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'delete':
        if ($id) $controller->delete($id);
        break;
    case 'index':
    default:
        $controller->index();
        break;
}