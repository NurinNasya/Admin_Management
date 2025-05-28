<?php
include_once '../db.php';
include_once '../Model/leavequota.php';

$leaveModel = new leavequota($conn);
$leaves = $leaveModel->getAllLeaves();

class LeaveQuotaController {
    // ... existing code ...

    public function handleLeaveApplication($applicationData) {
        // When a leave is requested
        $this->leaveQuotaModel->updateLeaveQuota(
            $applicationData['user_id'],
            $applicationData['leave_type'],
            $applicationData['days'],
            'request'
        );
        
        // Save the application
        $this->leaveApplicationModel->create($applicationData);
    }
    
    public function approveLeave($applicationId) {
        $application = $this->leaveApplicationModel->getById($applicationId);
        
        if($application) {
            // Update quota
            $updatedQuota = $this->leaveQuotaModel->updateLeaveQuota(
                $application['user_id'],
                $application['leave_type'],
                $application['days'],
                'approve'
            );
            
            // Update application status
            $this->leaveApplicationModel->updateStatus($applicationId, 'approved');
            
            return $updatedQuota;
        }
        
        return false;
    }
}
?>
