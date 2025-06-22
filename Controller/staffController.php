<?php
require_once '../model/Staff.php';
require_once '../model/Role.php';
require_once '../model/Depart.php';
require_once '../model/Comp.php';
require_once '../model/Shift.php';
require_once '../model/Branch.php';

class StaffController {
    private $staffModel;
    private $roleModel;
    private $departModel;
    private $compModel;
    private $shiftModel;
    private $branchModel;

    public function __construct() {
        $this->staffModel = new Staff();
        $this->roleModel = new Role();
        $this->departModel = new Depart();
        $this->compModel = new Company();
        $this->shiftModel = new Shift();
        $this->branchModel = new Branch();
    }

    public function handleRequest() {
        $action = $_GET['action'] ?? $_POST['action'] ?? 'index';

        try {
            switch ($action) {
                case 'create':
                    $this->create();
                    break;
                case 'update':
                    $this->update();
                    break;
                case 'delete':
                    $this->delete();
                    break;
                case 'index':
                default:
                    $this->index();
                    break;
            }
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }

    private function index() {
        $staff = $this->staffModel->getAllStaff();
        $roles = $this->roleModel->getAllRoles();
        $departments = $this->departModel->getAllDepartments();
        $companies = $this->compModel->getAllCompanies();
        $shifts = $this->shiftModel->getAllShifts();
        $branches = $this->branchModel->getAllBranches();
        
        require_once '../view/staff/index.php';
    }

    private function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showCreateForm();
            return;
        }

        $data = $this->validateAndSanitizeInput($_POST);
        
        if (!empty($_FILES['profile_pic']['tmp_name'])) {
            $data['profile_pic'] = $this->handleFileUpload($_FILES['profile_pic']);
        }

        $data['staff_no'] = $this->staffModel->generateStaffNumber();
        $data['pwd'] = $this->generatePassword($data['noic'], $data['phone']);
        $data['gender'] = $this->determineGender($data['noic']);

        if ($this->staffModel->create($data)) {
            $_SESSION['success'] = "Staff created successfully!";
            $this->redirect('/staff');
        } else {
            $_SESSION['error'] = "Failed to create staff";
            $this->redirect('/staff/create');
        }
    }

    private function showCreateForm() {
        $generatedStaffNo = $this->staffModel->generateStaffNumber();
        $roles = $this->roleModel->getAllRoles();
        $departments = $this->departModel->getAllDepartments();
        $companies = $this->compModel->getAllCompanies();
        $shifts = $this->shiftModel->getAllShifts();
        $branches = $this->branchModel->getAllBranches();
        $approvers = $this->staffModel->getStaffByRoles(['HOD', 'HOC', 'FOUNDER']);

        require_once '../view/staff/create.php';
    }

    private function validateAndSanitizeInput($postData) {
        return [
            'noic' => preg_replace('/[^0-9]/', '', $postData['noic'] ?? ''),
            'name' => htmlspecialchars(trim($postData['name'] ?? '')),
            'email' => filter_var($postData['email'] ?? '', FILTER_SANITIZE_EMAIL),
            'phone' => preg_replace('/[^0-9]/', '', $postData['phone'] ?? ''),
            'roles' => htmlspecialchars(trim($postData['roles'] ?? '')),
            'departments_id' => (int)($postData['departments_id'] ?? 0),
            'company_id' => (int)($postData['company_id'] ?? 0),
            'company_branch' => (int)($postData['company_branch'] ?? 0),
            'status' => (int)($postData['status'] ?? 1),
            'shift_id' => (int)($postData['shift_id'] ?? 0),
            'leave_approval' => (int)($postData['leave_approval'] ?? 0),
            'permanent_address' => htmlspecialchars(trim($postData['permanent_address'] ?? '')),
            'mail_address' => htmlspecialchars(trim($postData['mail_address'] ?? '')),
            'status_qrcode' => (int)($postData['status_qrcode'] ?? 1),
            'status_swafoto' => (int)($postData['status_swafoto'] ?? 1),
            'status_monitor' => (int)($postData['status_monitor'] ?? 1),
            'status_marital' => (int)($postData['status_marital'] ?? 1),
            'dependent' => (int)($postData['dependent'] ?? 0),
            'working_hours' => (int)($postData['working_hours'] ?? 8),
            'break_duration' => (int)($postData['break_duration'] ?? 60),
            'start_date' => $postData['start_date'] ?? date('Y-m-d'),
            'end_date' => $postData['end_date'] ?? null,
            'created_by' => $_SESSION['user_id'] ?? 0
        ];
    }

    private function handleFileUpload($file) {
        $targetDir = "../uploads/profiles/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $extension;
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $filename;
        }
        return '';
    }

    private function generatePassword($noic, $phone) {
        if (strlen($noic) === 12 && strlen($phone) >= 4) {
            $icLast6 = substr($noic, -6);
            $phoneLast4 = substr($phone, -4);
            return $icLast6 . '@' . $phoneLast4;
        }
        return bin2hex(random_bytes(8)); // Fallback random password
    }

    private function determineGender($noic) {
        if (strlen($noic) === 12) {
            $lastDigit = (int)substr($noic, -1);
            return ($lastDigit % 2 === 0) ? 'F' : 'M';
        }
        return 'U'; // Unknown
    }

    private function redirect($path) {
        header("Location: " . BASE_URL . $path);
        exit();
    }

    private function handleError(Exception $e) {
        error_log($e->getMessage());
        $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        $this->redirect('/staff');
    }
}

// Initialize and handle request
session_start();
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/Admin_Management');

$controller = new StaffController();
$controller->handleRequest();