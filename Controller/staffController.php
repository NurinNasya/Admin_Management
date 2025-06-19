<?php
require_once '../model/Staff.php';
require_once '../model/Role.php';
require_once '../model/Depart.php';
require_once '../model/Comp.php';
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
        try {
            // Get all staff data from the model
            $staffData = $this->staffModel->getAllStaff();
            
            if ($staffData === false) {
                error_log("Failed to fetch staff data");
                return false;
            }
            
            return $staffData;
        } catch (Exception $e) {
            error_log("Error in StaffController::index(): " . $e->getMessage());
            return false;
        }
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $noic = trim($_POST['noic']);
                $phone = trim($_POST['phone']);
                $gender = '';
                $pwd = '';

                if (strlen($noic) === 12) {
                    $lastDigit = (int)substr($noic, -1);
                    $gender = ($lastDigit % 2 === 0) ? 'F' : 'M';
                }

                if (strlen($noic) === 12 && strlen($phone) === 11) {
                    $icLast6 = substr($noic, -6);
                    $phoneLast4 = substr($phone, -4);
                    $pwd = $icLast6 . '@' . $phoneLast4;
                }

                $data = [
                    //'staff_no' => $this->staffModel->generateStaffNumber(),
                    'noic' => $noic,
                    'pwd' => $pwd,
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'phone' => $phone,
                    'roles' => trim($_POST['roles']),
                    'roles_status' => trim($_POST['status']),
                    'departments_id' => trim($_POST['departments_id']),
                    'company_id' => trim($_POST['company_id']),
                    'status' => trim($_POST['status']),
                    'gender' => $gender,
                    'shift_id' => trim($_POST['shift_id']),
                    'leave_approval' => trim($_POST['leave_approval']),
                    'permanent_address' => trim($_POST['permanent_address']),
                    'mail_address' => trim($_POST['mail_address']),
                    'status_qrcode' => trim($_POST['status_qrcode']),
                    'status_swafoto' => trim($_POST['status_swafoto']),
                    'status_monitor' => trim($_POST['status_monitor']),
                    'status_marital' => trim($_POST['status_marital']),
                    'dependent' => trim($_POST['dependent']),
                    'document_name' => '',
                    'document_size' => 0,
                    'created_by' => $_POST['created_by'] ?? 0,
                    'updated_by' => $_POST['created_by'] ?? 0,
                    'profile_pic' => ''
                ];

                if (!empty($_FILES['profile_pic']['tmp_name'])) {
                    $targetDir = "../uploads/";
                    if (!is_dir($targetDir)) mkdir($targetDir, 0755, true);
                    $filename = basename($_FILES['profile_pic']['name']);
                    $targetFile = $targetDir . $filename;

                    if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {
                        $data['profile_pic'] = $filename;
                    }
                }

                if ($this->staffModel->create($data)) {
                    $_SESSION['success'] = "Staff created successfully!";
                    $this->redirect('pages/staff.php');
                } else {
                    $_SESSION['error'] = "Failed to create staff";
                    $this->redirect('pages/staff_info.php');
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
                $this->redirect('pages/staff_info.php');
            }
        } else {
            $roles = $this->roleModel->getAllRoles();
            $departments = $this->departModel->getAllDepartments();
            $companies = $this->compModel->getAllCompanies();
            $shifts = $this->shiftModel->getAllShifts();
            $staff = $this->staffModel->getAllStaff();
            $generatedStaffNo = $this->staffModel->generateStaffNumber();

            require_once '../pages/staff_info.php';
        }
    }

    private function redirect($path) {
        $base_url = "http://" . $_SERVER['HTTP_HOST'] . "/Admin_Management";
        header("Location: " . $base_url . "/" . ltrim($path, '/'));
        exit();
    }
}

// Handle the request
session_start();

$controller = new StaffController();
$action = $_GET['action'] ?? 'index';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
}

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'delete':
        $id = $_GET['id'] ?? $_POST['id'] ?? null;
        if ($id) $controller->delete($id);
        break;
    case 'index':
    default:
        $controller->index();
        break;
}
