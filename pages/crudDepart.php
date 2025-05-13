<?php
require_once '../db.php';
session_start(); // Needed for flash messages

// CREATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);
    $name = trim($_POST['name']);
    $status = isset($_POST['status']) ? 1 : 0;

    // Validation: Check if code or name exists
    $check = $conn->prepare("SELECT * FROM departments WHERE code = ? OR name = ?");
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
        header("Location: department.php");
        exit;
    }

    // If no duplicate, insert into database
    $stmt = $conn->prepare("INSERT INTO departments (code, name, status) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $code, $name, $status);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Department saved successfully.";
    } else {
        $_SESSION['error_message'] = "Error saving department.";
    }

    header("Location: department.php");
    exit;
}

// UPDATE
if (isset($_POST['update_department'])) {
    $id = $_POST['edit_id'];
    $code = $_POST['edit_code'];
    $name = $_POST['edit_name'];
    $status = $_POST['edit_status'];

    // Check if another department with the same name exists
    $stmt = $conn->prepare("SELECT * FROM department WHERE name = ? AND id != ?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Another department with this name already exists.";
    } else {
        $stmt = $conn->prepare("UPDATE department SET code = ?, name = ?, status = ? WHERE id = ?");
        $stmt->bind_param("ssii", $code, $name, $status, $id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Department updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update department.";
        }
    }
    header("Location: ../pages/department.php");
    exit();
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM department WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Department deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete department.";
    }
    header("Location: ../pages/department.php");
    exit();
}