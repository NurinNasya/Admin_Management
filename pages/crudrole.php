<?php
session_start();
include_once '../db.php'; // or adjust path based on where your db.php is
?>
// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add new role
    if (isset($_POST['add_role'])) {
        $name = $_POST['name'];
        $status = $_POST['status'];

        $sql = "INSERT INTO roles (name, status, created_at, created_by, updated_at, updated_by) 
                VALUES (?, ?, NOW(), 1, NOW(), 1)"; // Assuming user ID 1 for now

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $name, $status);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Role added successfully!";
        } else {
            $_SESSION['error'] = "Error adding role: " . $conn->error;
        }

        $stmt->close();
        header("Location: role.php");
        exit();
    }

    // Delete role
    if (isset($_POST['delete_role'])) {
        $id = $_POST['role_id'];

        $sql = "DELETE FROM roles WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Role deleted successfully!";
        } else {
            $_SESSION['error'] = "Error deleting role: " . $conn->error;
        }

        $stmt->close();
        header("Location: role.php");
        exit();
    }

    // Update role
    if (isset($_POST['update_role'])) {
        $id = $_POST['role_id'];
        $name = $_POST['name'];
        $status = $_POST['status'];

        $sql = "UPDATE roles SET name = ?, status = ?, updated_at = NOW(), updated_by = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $name, $status, $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Role updated successfully!";
        } else {
            $_SESSION['error'] = "Error updating role: " . $conn->error;
        }

        $stmt->close();
        header("Location: role.php");
        exit();
    }
}