<?php
require_once '../db.php';
session_start();

// CREATE
if (isset($_POST['add_company'])) {
    // Ensure that all required fields are filled
    if (empty($_POST['code']) || empty($_POST['name']) || !isset($_POST['status'])) {
        $_SESSION['error_message'] = "Error: All fields are required.";
        header("Location: ../pages/company.php");
        exit();
    }

    $code = $_POST['code'];
    $name = $_POST['name'];
    $status = $_POST['status'];

    // Check for duplicate company name
    $stmt = $conn->prepare("SELECT * FROM company WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Error: A company with this name already exists.";
        header("Location: ../pages/company.php");
        exit();
    } else {
        // Insert the new company into the database
        $stmt = $conn->prepare("INSERT INTO company (code, name, status) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $code, $name, $status);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Company added successfully.";
        } else {
            $_SESSION['error_message'] = "Error adding company.";
        }
        header("Location: ../pages/company.php");
        exit();
    }
}

// UPDATE
if (isset($_POST['update_company'])) {
    $id = $_POST['edit_id'];
    $code = $_POST['edit_code'];
    $name = $_POST['edit_name'];
    $status = $_POST['edit_status'];

    // Check for name conflict (excluding the current record)
    $stmt = $conn->prepare("SELECT * FROM company WHERE name = ? AND id != ?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Error: A company with this name already exists.";
    } else {
        $stmt = $conn->prepare("UPDATE company SET code = ?, name = ?, status = ? WHERE id = ?");
        $stmt->bind_param("ssii", $code, $name, $status, $id);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Company updated successfully.";
        } else {
            $_SESSION['error_message'] = "Error updating company.";
        }
    }
    header("Location: ../pages/company.php");
    exit();
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    $stmt = $conn->prepare("DELETE FROM company WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Company deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error deleting company.";
    }
    header("Location: ../pages/company.php");
    exit();
}
?>