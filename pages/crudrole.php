<?php
session_start();
require_once '../db.php';

// Get current user ID from session
$current_user_id = $_SESSION['user_id'] ?? 0;

// Function to validate role input
function validateRoleInput($name)
{
    $name = trim($name);
    if (empty($name)) {
        return "Role name cannot be empty";
    }
    if (strlen($name) > 100) {
        return "Role name cannot exceed 100 characters";
    }
    return null;
}

// Handle Add Role
if (isset($_POST['add_role'])) {
    try {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception("Invalid security token");
        }


        $name = $_POST['name'];
        $status = isset($_POST['status']) ? (int) $_POST['status'] : 0;

        // Validate input
        if ($error = validateRoleInput($name)) {
            throw new Exception($error);
        }

        // Check for duplicate role name
        $check_sql = "SELECT id FROM roles WHERE name = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $name);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            throw new Exception("A role with this name already exists");
        }
        $check_stmt->close();

        // Insert new role
        $insert_sql = "INSERT INTO roles (name, status, created_at, created_by, updated_at, updated_by) 
                      VALUES (?, ?, NOW(), ?, NOW(), ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("siii", $name, $status, $current_user_id, $current_user_id);

        if (!$insert_stmt->execute()) {
            throw new Exception("Database error: " . $insert_stmt->error);
        }

        $_SESSION['message'] = "Role added successfully";
        $insert_stmt->close();
        header("Location: roles.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        error_log("Role creation error: " . $e->getMessage());
        header("Location: roles.php");
        exit();
    }
}

// Handle Update Role
if (isset($_POST['update_role'])) {
    try {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception("Invalid security token");
        }

        $role_id = (int) $_POST['role_id'];
        $name = $_POST['name'];
        $status = isset($_POST['status']) ? (int) $_POST['status'] : 0;

        // Validate input
        if ($error = validateRoleInput($name)) {
            throw new Exception($error);
        }

        // Check if role exists
        $check_sql = "SELECT id FROM roles WHERE id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $role_id);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows === 0) {
            throw new Exception("Role not found");
        }
        $check_stmt->close();

        // Check for duplicate name (excluding current role)
        $duplicate_sql = "SELECT id FROM roles WHERE name = ? AND id != ?";
        $duplicate_stmt = $conn->prepare($duplicate_sql);
        $duplicate_stmt->bind_param("si", $name, $role_id);
        $duplicate_stmt->execute();
        $duplicate_stmt->store_result();

        if ($duplicate_stmt->num_rows > 0) {
            throw new Exception("Another role with this name already exists");
        }
        $duplicate_stmt->close();

        // Update role
        $update_sql = "UPDATE roles SET 
                      name = ?, 
                      status = ?, 
                      updated_at = NOW(), 
                      updated_by = ? 
                      WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("siii", $name, $status, $current_user_id, $role_id);

        if (!$update_stmt->execute()) {
            throw new Exception("Database error: " . $update_stmt->error);
        }

        $_SESSION['message'] = "Role updated successfully";
        $update_stmt->close();
        header("Location: roles.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        error_log("Role update error: " . $e->getMessage());
        header("Location: roles.php");
        exit();
    }
}

// Handle Delete Role
if (isset($_POST['delete_role'])) {
    try {
        // Validate CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            throw new Exception("Invalid security token");
        }

        $role_id = (int) $_POST['role_id'];

        // Start transaction
        $conn->begin_transaction();

        try {
            // 1. Check if role exists
            $check_sql = "SELECT id FROM roles WHERE id = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("i", $role_id);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows === 0) {
                throw new Exception("Role not found");
            }
            $check_stmt->close();

            // 2. Check if role is assigned to any users
            $check_usage_sql = "SELECT COUNT(*) as user_count FROM users WHERE role_id = ?";
            $check_usage_stmt = $conn->prepare($check_usage_sql);
            $check_usage_stmt->bind_param("i", $role_id);
            $check_usage_stmt->execute();
            $result = $check_usage_stmt->get_result();
            $row = $result->fetch_assoc();

            if ($row['user_count'] > 0) {
                throw new Exception("Cannot delete role because it's assigned to users. Reassign users first.");
            }
            $check_usage_stmt->close();

            // 3. Delete the role
            $delete_sql = "DELETE FROM roles WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $role_id);

            if (!$delete_stmt->execute()) {
                throw new Exception("Database error: " . $delete_stmt->error);
            }

            $conn->commit();
            $_SESSION['message'] = "Role deleted successfully";
            $delete_stmt->close();
            header("Location: roles.php");
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        error_log("Role deletion error: " . $e->getMessage());
        header("Location: roles.php");
        exit();
    }
}

// If no valid action was performed
$_SESSION['error'] = "Invalid request";
header("Location: roles.php");
exit();
?>