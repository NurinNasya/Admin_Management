<?php
require_once '../db.php';
require_once '../Model/Branch.php';
require_once '../Model/Comp.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Generate CSRF token if not exists
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$branchModel = new Branch();
$companyModel = new Company();

// Debug form submission
error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST data: " . print_r($_POST, true));
}

// Handle Add Branch
if (isset($_POST['add_branch'])) {
    try {
        error_log("Add branch form submitted: " . print_r($_POST, true));

        // Verify required fields
        $required = ['company_id', 'branch_code', 'branch_name'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("$field is required");
            }
        }

        // Get current user ID from session
        $created_by = $_SESSION['user_id'] ?? null;

        // Create branch
        $branch_id = $branchModel->createBranch(
            $_POST['company_id'],
            $_POST['branch_code'],
            $_POST['branch_name'],
            $_POST['latitude'] ?? null,
            $_POST['longitude'] ?? null,
            $_POST['status'] ?? 1,
            $created_by
        );
        
        $_SESSION['success_message'] = "Branch added successfully!";
        error_log("Branch created successfully. ID: $branch_id");
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        error_log("Branch creation error: " . $e->getMessage());
    }
    header("Location: ../pages/company_branch.php");
    exit();
}

// Handle Update Branch
if (isset($_POST['update_branch'])) {
    try {
        error_log("Update branch form submitted: " . print_r($_POST, true));

        // Verify required fields
        $required = ['edit_id', 'company_id', 'branch_code', 'branch_name'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("$field is required");
            }
        }

        // Get current user ID from session
        $updated_by = $_SESSION['user_id'] ?? null;

        // Update branch
        if ($branchModel->updateBranch(
            $_POST['edit_id'],
            $_POST['company_id'],
            $_POST['branch_code'],
            $_POST['branch_name'],
            $_POST['latitude'] ?? null,
            $_POST['longitude'] ?? null,
            $_POST['status'] ?? 1,
            $updated_by
        )) {
            $_SESSION['success_message'] = "Branch updated successfully!";
            error_log("Branch updated successfully. ID: " . $_POST['edit_id']);
        } else {
            throw new Exception("No changes made to the branch");
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        error_log("Branch update error: " . $e->getMessage());
    }
    header("Location: ../pages/company_branch.php");
    exit();
}

// Handle Delete Branch
if (isset($_GET['delete_id'])) {
    try {
        error_log("Attempting to delete branch ID: " . $_GET['delete_id']);
        
        // Get current user ID from session
        $updated_by = $_SESSION['user_id'] ?? null;

        if ($branchModel->deleteBranch($_GET['delete_id'], $updated_by)) {
            $_SESSION['success_message'] = "Branch deactivated successfully!";
            error_log("Branch deactivated successfully. ID: " . $_GET['delete_id']);
        } else {
            throw new Exception("Branch not found or already deactivated");
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        error_log("Branch deletion error: " . $e->getMessage());
    }
    header("Location: ../pages/company_branch.php");
    exit();
}

// If no action matched
header("Location: ../pages/company_branch.php");
exit();