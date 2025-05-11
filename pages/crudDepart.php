<?php
require_once '../db.php';
session_start(); // Needed for flash messages

// CREATE
if (isset($_POST['add_department'])) {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $status = $_POST['status'];

    // Check if department name already exists
    $stmt = $conn->prepare("SELECT * FROM department WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "A department with this name already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO department (code, name, status) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $code, $name, $status);
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Department added successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to add department.";
        }
    }
    header("Location: ../pages/department.php");
    exit();
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