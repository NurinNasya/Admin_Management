<?php
class LeaveQuota {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllLeaves() {
        try {
            $query = "SELECT * FROM leave_quota";
            $result = $this->conn->query($query);
            if ($result) {
                return $result->fetch_all(MYSQLI_ASSOC);
            } else {
                return [];
            }
        } catch (Exception $e) {
            error_log("Database error in getAllLeaves: " . $e->getMessage());
            return false;
        }
    }

    public function updateLeaveQuota($userId, $leaveType, $days, $action) {
        try {
            // Escape variables for security
            $userId = (int)$userId;
            $days = (int)$days;
            $leaveType = $this->conn->quote($leaveType);
            
            $quota = $this->getUserQuota($userId, $leaveType);
            if (!$quota) {
                throw new Exception("Leave quota not found for user $userId and type $leaveType");
            }

            switch($action) {
                case 'request':
                    $newInProcess = $quota['in_process'] + $days;
                    $sql = "UPDATE leave_quota SET in_process = $newInProcess 
                            WHERE user_id = $userId AND leave_type = $leaveType";
                    break;
                    
                case 'approve':
                    $newUsed = $quota['used'] + $days;
                    $newInProcess = $quota['in_process'] - $days;
                    $sql = "UPDATE leave_quota SET used = $newUsed, in_process = $newInProcess 
                            WHERE user_id = $userId AND leave_type = $leaveType";
                    break;
                    
                case 'reject':
                case 'cancel':
                    $newInProcess = $quota['in_process'] - $days;
                    $sql = "UPDATE leave_quota SET in_process = $newInProcess 
                            WHERE user_id = $userId AND leave_type = $leaveType";
                    break;
                    
                default:
                    throw new Exception("Invalid action: $action");
            }

            $this->conn->exec($sql);
            return $this->getUserQuota($userId, $leaveType);
        } catch (PDOException $e) {
            error_log("Database error in updateLeaveQuota: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            error_log("Error in updateLeaveQuota: " . $e->getMessage());
            return false;
        }
    }
    
    public function getUserQuota($userId, $leaveType) {
        try {
            // Escape variables for security
            $userId = (int)$userId;
            $leaveType = $this->conn->quote($leaveType);
            
            $sql = "SELECT * FROM leave_quota WHERE user_id = $userId AND leave_type = $leaveType";
            $result = $this->conn->query($sql);
            return $result->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error in getUserQuota: " . $e->getMessage());
            return false;
        }
    }
}
?>