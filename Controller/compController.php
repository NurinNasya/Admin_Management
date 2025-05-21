<?php
require_once '../db.php';
require_once '../model/comp.php'; // make sure this path is correct

$compModel = new Company(); // ← Class name must match the one in comp.php
$companyList = $compModel->getAllCompanies();

// ===================
// CREATE
// ===================
if (isset($_POST['code']) && isset($_POST['name']) && !isset($_POST['update_company'])) {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $status = isset($_POST['status']) ? 1 : 0;

    $existing = $compModel->getCompanyByCodeOrName($code, $name);
    if ($existing) {
        if ($existing['code'] === $code && $existing['name'] === $name) {
            $_SESSION['error_message'] = "Code and Name already exist.";
        } elseif ($existing['code'] === $code) {
            $_SESSION['error_message'] = "Code already exists.";
        } elseif ($existing['name'] === $name) {
            $_SESSION['error_message'] = "Name already exists.";
        }
    } else {
        if ($compModel->createCompany($code, $name, $status)) {
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

    $existing = $compModel->getCompanyByCodeOrName($code, $name, $id);
    if ($existing) {
        if ($existing['code'] === $code && $existing['name'] === $name) {
            $_SESSION['error_message'] = "Code and Name already exist.";
        } elseif ($existing['code'] === $code) {
            $_SESSION['error_message'] = "Code already exists.";
        } elseif ($existing['name'] === $name) {
            $_SESSION['error_message'] = "Name already exists.";
        }
    } else {
        if ($compModel->updateCompany($id, $code, $name, $status)) {
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
    if ($compModel->deleteCompany($id)) {
        $_SESSION['success_message'] = "Company deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete company.";
    }
    header("Location: ../pages/company.php");
    exit;
}
