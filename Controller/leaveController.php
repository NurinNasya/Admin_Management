<?php
require_once '../db.php';
require_once '../Model/leave.php';
require_once '../Model/Staff.php';
require_once '../Model/depart.php';

class LeaveController {
    private $leaveModel;
    
    public function __construct() {
        global $conn;
        $this->leaveModel = new Leave($conn);
    }

    public function getLeaveRequests($status = null) {
        try {
            return $this->leaveModel->getLeaveRequestsByStatus($status);
        } catch (Exception $e) {
            $_SESSION['error'] = "Error fetching leave requests: " . $e->getMessage();
            return [];
        }
    }
    
    public function approveLeave($leaveId) {
        try {
            if ($this->leaveModel->updateLeaveStatus($leaveId, 'approved')) {
                $_SESSION['message'] = "Leave approved successfully";
            } else {
                $_SESSION['error'] = "Failed to approve leave";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error approving leave: " . $e->getMessage();
        }
    }
    
    public function rejectLeave($leaveId) {
        try {
            if ($this->leaveModel->updateLeaveStatus($leaveId, 'rejected')) {
                $_SESSION['message'] = "Leave rejected successfully";
            } else {
                $_SESSION['error'] = "Failed to reject leave";
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error rejecting leave: " . $e->getMessage();
        }
    }
    
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (isset($_POST['action'])) {
                    $leaveId = filter_var($_POST['leave_id'], FILTER_VALIDATE_INT);
                    $action = $_POST['action'];
                    
                    if (!$leaveId) {
                        throw new Exception("Invalid leave ID");
                    }
                    
                    if ($action === 'approve') {
                        $this->approveLeave($leaveId);
                    } elseif ($action === 'reject') {
                        $this->rejectLeave($leaveId);
                    } else {
                        throw new Exception("Invalid action");
                    }
                    
                    header("Location: ../pages/approval_leave.php");
                    exit();
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Error: " . $e->getMessage();
                header("Location: ../pages/approval_leave.php");
                exit();
            }
        }
    }
}