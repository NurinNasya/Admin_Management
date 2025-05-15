<?php
require_once '../db.php';
require_once '../Model/depart.php';

$departModel = new DepartModel($conn);
$departmentList = $departModel->getAllDepartments(); // fetch data

// ===================
// CREATE
// ===================
if (isset($_POST['code']) && isset($_POST['name']) && !isset($_POST['update_department'])) {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $status = isset($_POST['status']) ? 1 : 0;

    $result = $departModel->findDuplicate($code, $name);
    if ($result->num_rows > 0) {
        $existing = $result->fetch_assoc();
        if ($existing['code'] === $code && $existing['name'] === $name) {
            $_SESSION['error_message'] = "Code and Name already exist.";
        } elseif ($existing['code'] === $code) {
            $_SESSION['error_message'] = "Code already exists.";
        } elseif ($existing['name'] === $name) {
            $_SESSION['error_message'] = "Name already exists.";
        }
    } else {
        if ($departModel->insert($code, $name, $status)) {
            $_SESSION['success_message'] = "Department saved successfully.";
        } else {
            $_SESSION['error_message'] = "Error saving department.";
        }
    }

    header("Location: ../pages/department.php");
    exit;
}

// ===================
// UPDATE
// ===================
if (isset($_POST['update_department'])) {
    $id = $_POST['edit_id'];
    $code = trim($_POST['edit_code']);
    $name = trim($_POST['edit_name']);
    $status = $_POST['edit_status'];

    $result = $departModel->findDuplicate($code, $name, $id);
    if ($result->num_rows > 0) {
        $existing = $result->fetch_assoc();
        if ($existing['code'] === $code && $existing['name'] === $name) {
            $_SESSION['error_message'] = "Code and Name already exist.";
        } elseif ($existing['code'] === $code) {
            $_SESSION['error_message'] = "Code already exists.";
        } elseif ($existing['name'] === $name) {
            $_SESSION['error_message'] = "Name already exists.";
        }
    } else {
        if ($departModel->update($id, $code, $name, $status)) {
            $_SESSION['success_message'] = "Department updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update department.";
        }
    }

    header("Location: ../pages/department.php");
    exit;
}

// ===================
// DELETE
// ===================
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    if ($departModel->delete($id)) {
        $_SESSION['success_message'] = "Department deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete department.";
    }
    header("Location: ../pages/department.php");
    exit;
}
