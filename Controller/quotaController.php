<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../Model/Quota.php';

session_start();

// Initialize variables
$redirectUrl = '/Admin_Management/pages/adminQuotaManagement.php';

// Verify CSRF token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $_SESSION['error'] = "CSRF token validation failed";
        header("Location: $redirectUrl");
        exit;
    }
}

$quota = new Quota($conn);

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
    //     if (isset($_POST['add_quota'])) {
    // $staffId = intval($_POST['staff_id']);
    // $additionalQuota = floatval($_POST['additional_quota']);
    // $notes = htmlspecialchars($_POST['notes']);
    
    // if ($quota->addQuota($staffId, $additionalQuota, $notes)) {
    //     $existing = $quota->getStaffQuota($staffId);
    //     if ($existing && $existing['additional_quota'] > $additionalQuota) {
    //         $_SESSION['success'] = "Quota added to existing amount. New total: RM " . number_format($existing['additional_quota'], 2);
    //     } else {
    //         $_SESSION['success'] = "Quota added successfully";
    //     }
    // } else {
    //     $_SESSION['error'] = "Failed to add quota";
    // }


    if (isset($_POST['add_quota'])) {
    $staffId = intval($_POST['staff_id']);
    $additionalQuota = floatval($_POST['additional_quota']);
    $notes = htmlspecialchars($_POST['notes']);
    
    if ($quota->addQuota($staffId, $additionalQuota, $notes)) {
        $_SESSION['success'] = "Quota added successfully";
    } else {
        $_SESSION['error'] = "Failed to add quota";
    }
}
        
        elseif (isset($_POST['update_quota'])) {
            $quotaId = intval($_POST['quota_id']);
            $additionalQuota = floatval($_POST['additional_quota']);
            $notes = htmlspecialchars($_POST['notes']);
            
            if ($quota->updateQuota($quotaId, $additionalQuota, $notes)) {
                $_SESSION['success'] = "Quota updated successfully";
            } else {
                $_SESSION['error'] = "Failed to update quota";
            }
        }
        
        elseif (isset($_POST['delete_quota'])) {
            $quotaId = intval($_POST['quota_id']);
            
            if ($quota->deleteQuotaById($quotaId)) {
                $_SESSION['success'] = "Quota deleted successfully";
            } else {
                $_SESSION['error'] = "Failed to delete quota";
            }
        }
        
        // Always redirect back to adminQuotaManagement.php
        header("Location: $redirectUrl");
        exit;
        
    } catch (Exception $e) {
        $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        header("Location: $redirectUrl");
        exit;
    }
}

// Get all quota allocations for admin view
$allQuotas = $quota->getAllQuotaAllocations();