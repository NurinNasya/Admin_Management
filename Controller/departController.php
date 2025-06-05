<?php
require_once '../Model/Depart.php';
require_once '../db.php';

class DepartController
{
    private $departModel;

    public function __construct()
    {
        $db = new Database();
        $conn = $db->getConnection();
        $this->departModel = new Depart($conn);
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function handleRequests()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['code']) && !isset($_POST['update_department'])) {
                $this->createDepartment();
            } elseif (isset($_POST['update_department'])) {
                $this->updateDepartment();
            }
        } elseif (isset($_GET['delete_id'])) {
            $this->deleteDepartment();
        }
    }

    function getAllDepartments() {
        global $conn;
        
        $result = $conn->query("SELECT * FROM departments");
        $departments = [];
        
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
        
        return $departments;
    }

    public function getDepartmentById($id)
    {
        return $this->departModel->getDepartmentById($id);
    }

    private function createDepartment()
    {
        $code = trim($_POST['code']);
        $name = trim($_POST['name']);
        $status = isset($_POST['status']) ? 1 : 0;

        $result = $this->departModel->findDuplicate($code, $name);
        if ($result && $result->num_rows > 0) {
            $existing = $result->fetch_assoc();
            if ($existing['code'] === $code && $existing['name'] === $name) {
                $_SESSION['error_message'] = "Code and Name already exist.";
            } elseif ($existing['code'] === $code) {
                $_SESSION['error_message'] = "Code already exists.";
            } elseif ($existing['name'] === $name) {
                $_SESSION['error_message'] = "Name already exists.";
            }
        } else {
            if ($this->departModel->insert($code, $name, $status)) {
                $_SESSION['success_message'] = "Department created successfully.";
            } else {
                $_SESSION['error_message'] = "Error creating department.";
            }
        }

        header("Location: ../pages/department.php");
        exit;
    }

    private function updateDepartment()
    {
        $id = (int)$_POST['edit_id'];
        $code = trim($_POST['edit_code']);
        $name = trim($_POST['edit_name']);
        $status = (int)$_POST['edit_status'];

        $result = $this->departModel->findDuplicate($code, $name, $id);
        if ($result && $result->num_rows > 0) {
            $existing = $result->fetch_assoc();
            if ($existing['code'] === $code && $existing['name'] === $name) {
                $_SESSION['error_message'] = "Code and Name already exist.";
            } elseif ($existing['code'] === $code) {
                $_SESSION['error_message'] = "Code already exists.";
            } elseif ($existing['name'] === $name) {
                $_SESSION['error_message'] = "Name already exists.";
            }
        } else {
            if ($this->departModel->update($id, $code, $name, $status)) {
                $_SESSION['success_message'] = "Department updated successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to update department.";
            }
        }

        header("Location: ../pages/department.php");
        exit;
    }

    private function deleteDepartment()
    {
        $id = (int)$_GET['delete_id'];
        if ($this->departModel->delete($id)) {
            $_SESSION['success_message'] = "Department deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to delete department.";
        }
        header("Location: ../pages/department.php");
        exit;
    }
}

// Instantiate and handle the request if this file is accessed directly
if (isset($_SERVER['REQUEST_METHOD']) && (isset($_POST['code']) || isset($_POST['update_department']) || isset($_GET['delete_id']))) {
    $controller = new DepartController();
    $controller->handleRequests();
}