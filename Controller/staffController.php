<?php
session_start();
require_once '../Model/Staff.php';
require_once '../Model/Depart.php';
require_once '../db.php';

class StaffController {
    private $staffModel;
    private $departModel;

    public function __construct() {
        $this->staffModel = new Staff();
        $this->departModel = new Depart();
    }

    public function index(): void {
        try {
            $staffs = $this->staffModel->getAllStaff();
            $departments = $this->departModel->getAllDepartments();

            if (empty($staffs)) {
                $_SESSION['warning'] = "No staff records found";
            }

            include '../pages/staff.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Error loading staff data: " . $e->getMessage();
            header("Location: error.php");
            exit();
        }
    }

    public function create(): void {
        try {
            $generatedStaffNo = $this->staffModel->generateStaffNoForDisplay();
            $departments = $this->departModel->getAllDepartments();
            $companies = $this->staffModel->getCompanies();
            $roles = $this->staffModel->getRoles(); // ✅ Get roles

            include '../pages/add_staff.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Error preparing staff form: " . $e->getMessage();
            header("Location: ../pages/staff.php");
            exit();
        }
    }

    public function show(int $id): void {
        try {
            $staff = $this->staffModel->getStaffById($id);
            if (!$staff) {
                throw new Exception("Staff not found");
            }

            $departments = $this->departModel->getAllDepartments();
            $companies = $this->staffModel->getCompanies();
            $roles = $this->staffModel->getRoles(); // ✅ Get roles

            include '../pages/edit_staff.php';
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: ../pages/staff.php");
            exit();
        }
    }

    public function store(): void {
        try {
            $profilePicName = $this->handleFileUpload('profile_pic');

            $data = [
                'noic' => $_POST['noic'] ?? '',
                'name' => $_POST['name'] ?? '',
                'pwd' => $_POST['pwd'] ?? '',
                'email' => $_POST['email'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'gender' => $_POST['gender'] ?? '',
                'status_marital' => $_POST['status_marital'] ?? '',
                'dependent' => $_POST['dependent'] ?? 0,
                'permanent_address' => $_POST['permanent_address'] ?? '',
                'mail_address' => $_POST['mail_address'] ?? '',
                'roles_id' => $_POST['roles_id'] ?? '',
                'roles_status' => $_POST['roles_status'] ?? '',
                'profile_pic' => $profilePicName,
                'departments_id' => (int)($_POST['departments_id'] ?? 0),
                'company_id' => (int)($_POST['company_id'] ?? 0),
            ];

            if ($this->staffModel->insertStaff($data)) {
                $_SESSION['success'] = "Staff added successfully with staff number: " . $this->staffModel->generateStaffNoForDisplay();
            } else {
                throw new Exception("Failed to add staff");
            }

            header("Location: ../pages/staff.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: ../pages/add_staff.php");
            exit();
        }
    }

    public function update(): void {
        try {
            $id = (int)($_POST['edit_id'] ?? 0);
            $staff = $this->staffModel->getStaffById($id);

            if (!$staff) {
                throw new Exception("Staff not found");
            }

            $profilePicName = $this->handleFileUpload('edit_profile_pic', $staff['profile_pic']);

            $data = [
                'id' => $id,
                'noic' => $_POST['edit_noic'] ?? '',
                'name' => $_POST['edit_name'] ?? '',
                'email' => $_POST['edit_email'] ?? '',
                'phone' => $_POST['edit_phone'] ?? '',
                'gender' => $_POST['edit_gender'] ?? '',
                'status_marital' => $_POST['edit_status_marital'] ?? '',
                'dependent' => (int)($_POST['edit_dependent'] ?? 0),
                'permanent_address' => $_POST['edit_permanent_address'] ?? '',
                'mail_address' => $_POST['edit_mail_address'] ?? '',
                'roles_id' => $_POST['edit_roles_id'] ?? '',
                'roles_status' => $_POST['edit_roles_status'] ?? '',
                'profile_pic' => $profilePicName,
                'departments_id' => (int)($_POST['edit_departments_id'] ?? 0),
                'company_id' => (int)($_POST['edit_company_id'] ?? 0),
                'status' => $_POST['edit_status'] ?? 'Active',
                'pwd' => $_POST['edit_pwd'] ?? '',
            ];

            if ($this->staffModel->updateStaff($data)) {
                $_SESSION['success'] = "Staff updated successfully!";
            } else {
                throw new Exception("Failed to update staff");
            }

            header("Location: ../pages/staff.php");
            exit();
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: ../pages/edit_staff.php?id=$id");
            exit();
        }
    }

    private function handleFileUpload(string $inputName, string $existingFile = ''): string {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES[$inputName]['tmp_name'];
            $fileName = basename($_FILES[$inputName]['name']);
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

            if (!in_array($fileExt, $allowedExt)) {
                throw new Exception("Invalid file type for profile picture");
            }

            $newFileName = uniqid('profile_', true) . '.' . $fileExt;
            $uploadDir = '../assets/uploads/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $destPath = $uploadDir . $newFileName;

            if (!move_uploaded_file($fileTmpPath, $destPath)) {
                throw new Exception("Failed to move uploaded file");
            }

            if ($existingFile && file_exists($uploadDir . $existingFile)) {
                unlink($uploadDir . $existingFile);
            }

            return $newFileName;
        } elseif ($existingFile) {
            return $existingFile;
        } else {
            return '';
        }
    }
}

// Handle actions
if (isset($_GET['action'])) {
    $controller = new StaffController();
    $action = $_GET['action'];

    try {
        switch ($action) {
            case 'create':
                $controller->create();
                break;
            case 'show':
                $id = (int)($_GET['id'] ?? 0);
                $controller->show($id);
                break;
            case 'save':
                $controller->store();
                break;
            case 'update':
                $controller->update();
                break;
            default:
                $controller->index();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: ../pages/staff.php");
        exit();
    }
} else {
    $controller = new StaffController();
    $controller->index();
}
