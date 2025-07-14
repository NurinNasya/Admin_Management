<?php
require_once '../db.php';
require_once '../Model/Staff.php';
session_start();

$staffModel = new Staff();

function redirectWithMessage(string $message, string $type = 'error') {
    $_SESSION["{$type}_message"] = $message;
    header("Location: ../pages/staff.php");
    exit;
}

// Validate required fields
function validateStaffData(array $data): bool {
    $required = ['name', 'email', 'phone', 'roles', 'departments_id', 
                'company_id', 'company_branch', 'status', 'gender'];
    
    foreach ($required as $field) {
        if (empty($data[$field])) {
            redirectWithMessage("Missing required field: $field");
            return false;
        }
    }
    return true;
}

// CREATE STAFF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_staff'])) {
    // Validate data first
    if (!validateStaffData($_POST)) {
        exit; // Validation failed and redirected
    }

    // Process staff creation
    try {
        if ($staffModel->create($_POST)) {
  // =============================================
            // FIX: ADD THESE 2 LINES RIGHT BEFORE REDIRECT
            $_SESSION['staff_data_refreshed'] = true;
            unset($_SESSION['cached_staff_data']);
            // =============================================
            redirectWithMessage("Staff created successfully", "success");
        } else {
            redirectWithMessage("Failed to create staff");
        }
    } catch (Exception $e) {
        error_log("Staff creation error: " . $e->getMessage());
        redirectWithMessage("System error during staff creation");
    }
}

// UPDATE STAFF
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_staff'])) {
    try {
        // 1. Validate required fields
        $requiredFields = ['id', 'name', 'email', 'phone', 'roles', 'departments_id'];
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("Missing required field: $field");
            }
        }

        // 2. Validate and sanitize ID
        $id = (int)$_POST['id'];
        if ($id <= 0) {
            throw new Exception("Invalid staff ID");
        }

        // 3. Verify staff exists
        $existingStaff = $staffModel->getStaffById($id);
        if (!$existingStaff) {
            throw new Exception("Staff record not found");
        }

        // 4. Prepare update data with only allowed fields
        $allowedFields = [
            'name', 'email', 'phone', 'roles',
            'departments_id', 'company_id', 'company_branch',
            'status', 'gender', 'shift_id', 'status_marital',
            'dependent', 'permanent_address', 'mail_address',
            'status_qrcode', 'status_swafoto', 'status_monitor',
            'start_date', 'end_date', 'updated_by'
        ];
        
        $updateData = [];
        foreach ($allowedFields as $field) {
            if (isset($_POST[$field])) {
                $updateData[$field] = $_POST[$field];
            }
        }
        $updateData['updated_by'] = $_SESSION['user_id'] ?? 1;

        // 5. Execute update
        $success = $staffModel->update($id, $updateData);
        
        if (!$success) {
            throw new Exception("No changes were made to staff record");
        }

        // FIX: Force refresh on next load
        $_SESSION['staff_data_refreshed'] = true;
        $_SESSION['success_message'] = "Staff #$id updated successfully";

        // 6. Clear cache and redirect
        unset($_SESSION['staff_list_cache']);
        $_SESSION['success_message'] = "Staff #$id updated successfully";
        
        header("Location: ../pages/staff.php?updated=$id");
        exit;

    } catch (Exception $e) {
        error_log("Staff Update Error: " . $e->getMessage());
        $_SESSION['error_message'] = $e->getMessage();
        header("Location: ../pages/edit_staff.php?id=" . ($_POST['id'] ?? ''));
        exit;
    }
}

// In staffController.php - Replace the existing delete handler with this:

// DELETE STAFF - FIXED VERSION
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_staff'])) {
    try {
        // Get and validate ID
        $id = (int)($_POST['staff_id'] ?? 0);
        if ($id <= 0) {
            throw new Exception("Invalid staff ID");
        }

        // Verify staff exists before deletion
        $staff = $staffModel->getStaffById($id);
        if (!$staff) {
            throw new Exception("Staff record not found");
        }

        // Execute deletion
        if ($staffModel->delete($id)) {
            // Clear any cached staff data
            // if (isset($_SESSION['staff_list_cache'])) {
            //     unset($_SESSION['staff_list_cache']);
            // }

             // WITH THIS FIXED VERSION:
            unset($_SESSION['cached_staff_data']);  // Clear the correct cache key
            $_SESSION['staff_data_refreshed'] = true; // Force refresh on redirect
            // =============================================
            
            
            $_SESSION['success_message'] = "Staff deleted successfully";
        } else {
            throw new Exception("Failed to delete staff");
        }
    } catch (Exception $e) {
        error_log("DELETE ERROR: " . $e->getMessage());
        $_SESSION['error_message'] = $e->getMessage();
    }
    
    // Force refresh by redirecting
    header("Location: ../pages/staff.php");
    exit;
}

// If no valid action detected
redirectWithMessage("Invalid request"); 