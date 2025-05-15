<?php
require_once '../db.php';
require_once '../Model/comp.php';

$compModel = new CompModel($conn);
$companyList = $compModel->getAllCompanies(); // fetch data

// ===================
// CREATE
// ===================
if (isset($_POST['code']) && isset($_POST['name']) && !isset($_POST['update_company'])) {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $status = isset($_POST['status']) ? 1 : 0;

    $result = $compModel->findDuplicate($code, $name);
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
        if ($compModel->insert($code, $name, $status)) {
            $_SESSION['success_message'] = "Company saved successfully.";
        } else {
            $_SESSION['error_message'] = "Error saving company.";
        }
    }

    header("Location: ../pages/company.php");
    exit;
}

// ===================
// UPDATE
// ===================
if (isset($_POST['update_company'])) {
    $id = $_POST['edit_id'];
    $code = trim($_POST['edit_code']);
    $name = trim($_POST['edit_name']);
    $status = $_POST['edit_status'];

    $result = $compModel->findDuplicate($code, $name, $id);
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
        if ($compModel->update($id, $code, $name, $status)) {
            $_SESSION['success_message'] = "Company updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update company.";
        }
    }

    header("Location: ../pages/company.php");
    exit;
}

// ===================
// DELETE
// ===================
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    if ($compModel->delete($id)) {
        $_SESSION['success_message'] = "Company deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete company.";
    }
    header("Location: ../pages/company.php");
    exit;
}
