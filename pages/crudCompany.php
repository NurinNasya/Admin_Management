<?php
require_once '../db.php';
session_start(); // Needed for flash messages

// ===================
// CREATE
// ===================
if (isset($_POST['code']) && isset($_POST['name']) && !isset($_POST['update_company'])) {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $status = isset($_POST['status']) ? 1 : 0;

    // Validation: Check if code or name exists
    $check = $conn->prepare("SELECT * FROM companies WHERE code = ? OR name = ?");
    $check->bind_param("ss", $code, $name);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $existing = $result->fetch_assoc();
        if ($existing['code'] === $code && $existing['name'] === $name) {
            $_SESSION['error_message'] = "Code and Name already exist.";
        } elseif ($existing['code'] === $code) {
            $_SESSION['error_message'] = "Code already exists.";
        } elseif ($existing['name'] === $name) {
            $_SESSION['error_message'] = "Name already exists.";
        }
        header("Location: company.php");
        exit;
    }

    // If no duplicate, insert into database
    $stmt = $conn->prepare("INSERT INTO companies (code, name, status) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $code, $name, $status);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Company saved successfully.";
    } else {
        $_SESSION['error_message'] = "Error saving company.";
    }

    header("Location: company.php");
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

    // Validate duplicate code or name (excluding current row)
    $stmt = $conn->prepare("SELECT * FROM companies WHERE (code = ? OR name = ?) AND id != ?");
    $stmt->bind_param("ssi", $code, $name, $id);
    $stmt->execute();
    $result = $stmt->get_result();

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
        // Perform the update
        $stmt = $conn->prepare("UPDATE companies SET code = ?, name = ?, status = ? WHERE id = ?");
        $stmt->bind_param("ssii", $code, $name, $status, $id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Company updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update company.";
        }
    }

    header("Location: ../pages/company.php");
    exit();
}

// ===================
// DELETE
// ===================
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM companies WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Company deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete company.";
    }
    header("Location: ../pages/company.php");
    exit();
}
