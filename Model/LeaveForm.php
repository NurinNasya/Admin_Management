<?php
class LeaveForm {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    

    
    public function getApplicationById($id) {
        $id = intval($id);

        $sql = "SELECT 
                    la.*,
                    lt.type_name,
                    e.name as employee_name,
                    DATE_FORMAT(la.start_date, '%d/%m/%Y') as start_date_formatted,
                    DATE_FORMAT(la.end_date, '%d/%m/%Y') as end_date_formatted
                FROM leave_applications la
                LEFT JOIN leave_types lt ON la.leave_type_id = lt.id
                LEFT JOIN employees e ON la.employee_id = e.id
                WHERE la.id = " . $id;

        $result = mysqli_query($this->conn, $sql);

        return ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : null;
    }

    // Get application attachments
    public function getApplicationAttachments($applicationId) {
        $sql = "SELECT attachment FROM leave_applications WHERE id = " . intval($applicationId);
        $result = mysqli_query($this->conn, $sql);
        
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['attachment'] ? [$row['attachment']] : [];
        }
        
        return [];
    }

    // Update application with attachment support
    public function updateApplication($id, $data) {
        $id = intval($id);
        $startDate = mysqli_real_escape_string($this->conn, $data['start_date']);
        $endDate = mysqli_real_escape_string($this->conn, $data['end_date']);
        $totalDays = floatval($data['total_days']);
        $leaveType = mysqli_real_escape_string($this->conn, $data['leave_type']);
        $reason = mysqli_real_escape_string($this->conn, $data['reason']);

        // First get leave type ID
        $typeQuery = "SELECT id FROM leave_types WHERE type_name = '$leaveType' LIMIT 1";
        $typeResult = mysqli_query($this->conn, $typeQuery);
        
        if (!$typeResult || mysqli_num_rows($typeResult) == 0) {
            return false;
        }
        
        $typeRow = mysqli_fetch_assoc($typeResult);
        $leaveTypeId = $typeRow['id'];

        $sql = "UPDATE leave_applications SET 
                    leave_type_id = $leaveTypeId,
                    start_date = '" . $startDate . "',
                    end_date = '" . $endDate . "',
                    total_days = " . $totalDays . ",
                    reason = '" . $reason . "',
                    updated_at = NOW()
                WHERE id = " . $id . " AND status = 'pending'";

        return mysqli_query($this->conn, $sql);
    }

    // Delete application with attachment cleanup
    public function deleteApplication($id) {
        $id = intval($id);
        
        // First get attachment info
        $attachment = '';
        $getQuery = "SELECT attachment FROM leave_applications WHERE id = $id";
        $getResult = mysqli_query($this->conn, $getQuery);
        
        if ($getResult && mysqli_num_rows($getResult) > 0) {
            $row = mysqli_fetch_assoc($getResult);
            $attachment = $row['attachment'];
        }
        
        // Delete the record
        $deleteQuery = "DELETE FROM leave_applications WHERE id = $id AND status = 'pending'";
        $result = mysqli_query($this->conn, $deleteQuery);
        
        if ($result && $attachment) {
            // Delete the attachment file
            $filePath = "../uploads/" . $attachment;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        return $result;
    }

    // =============== TAMAT PENAMBAHAN ===============

    // ... [method asal seperti getConnection() dan lain-lain]
    
    public function getConnection() {
        return $this->conn;
    }
}
?>
