<?php
require_once '../db.php';
require_once '../Model/Branch.php';
require_once '../Model/Comp.php';

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$branchModel = new Branch();
$companyModel = new Company();

// Handle Add Branch
if (isset($_POST['add_branch'])) {
    try {
        // Debug: Log received data
        error_log("Add branch form submitted: " . print_r($_POST, true));

        // Validate required fields
        if (empty($_POST['company_id']) || empty($_POST['branch_code']) || empty($_POST['branch_name'])) {
            throw new Exception("Company ID, branch code and branch name are required");
        }

        // Check if branch code already exists
        if ($branchModel->isBranchCodeExists($_POST['branch_code'])) {
            throw new Exception("Branch code already exists");
        }

        // Create branch
        $branch_id = $branchModel->createBranch(
            $_POST['company_id'],
            $_POST['branch_code'],
            $_POST['branch_name'],
            $_POST['latitude'] ?? null,
            $_POST['longitude'] ?? null,
            $_POST['status'] ?? 1
        );
        
        $_SESSION['success_message'] = "Branch added successfully! ID: $branch_id";
        error_log("Branch created successfully. ID: $branch_id");
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        error_log("Branch creation error: " . $e->getMessage());
    }
    header("Location: ../pages/company_branch.php");
    exit();
}

// Handle Update Branch
if (isset($_POST['update_branch'])) {
    try {
        // Debug: Log received data
        error_log("Update branch form submitted: " . print_r($_POST, true));

        // Validate required fields
        if (empty($_POST['edit_id']) || empty($_POST['company_id']) || 
            empty($_POST['branch_code']) || empty($_POST['branch_name'])) {
            throw new Exception("All required fields must be filled");
        }

        // Check if branch code exists (excluding current branch)
        if ($branchModel->isBranchCodeExists($_POST['branch_code'], $_POST['edit_id'])) {
            throw new Exception("Branch code already exists");
        }

        if ($branchModel->updateBranch(
            $_POST['edit_id'],
            $_POST['company_id'],
            $_POST['branch_code'],
            $_POST['branch_name'],
            $_POST['latitude'] ?? null,
            $_POST['longitude'] ?? null,
            $_POST['status'] ?? 1
        )) {
            $_SESSION['success_message'] = "Branch updated successfully!";
            error_log("Branch updated successfully. ID: " . $_POST['edit_id']);
        } else {
            throw new Exception("Failed to update branch");
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        error_log("Branch update error: " . $e->getMessage());
    }
    header("Location: ../pages/company_branch.php");
    exit();
}

// Handle Delete Branch
if (isset($_GET['delete_id'])) {
    try {
        error_log("Attempting to delete branch ID: " . $_GET['delete_id']);
        
        if ($branchModel->deleteBranch($_GET['delete_id'])) {
            $_SESSION['success_message'] = "Branch deleted successfully!";
            error_log("Branch deleted successfully. ID: " . $_GET['delete_id']);
        } else {
            throw new Exception("Failed to delete branch");
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        error_log("Branch deletion error: " . $e->getMessage());
    }
    header("Location: ../pages/company_branch.php");
    exit();
}

// If no action matched
header("Location: ../pages/company_branch.php");
exit();