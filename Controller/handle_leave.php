<?php
// controller/handle_leave.php

require_once '../Model/leave.php'; // Adjust path if necessary
session_start();

// Initialize Leave model
$leaveModel = new Leave();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['leave_id'] ?? null;
    $action = $_POST['action'] ?? null;

    if ($id && in_array($action, ['approve', 'reject'])) {
        $newStatus = ($action === 'approve') ? 'approved' : 'rejected';
        $success = $leaveModel->updateLeaveStatus($id, $newStatus);

        if ($success) {
            $_SESSION['message'] = "Leave request has been $newStatus.";
        } else {
            $_SESSION['error'] = "Failed to update leave request. Please try again.";
        }
    } else {
        $_SESSION['error'] = "Invalid request.";
    }

    // Redirect back to approval panel (adjust view path if necessary)
    header('Location: ../views/approval_panel.php?status=pending');
    exit();
} else {
    // Deny non-POST access
    $_SESSION['error'] = "Unauthorized request method.";
    header('Location: ../views/approval_panel.php');
    exit();
}
