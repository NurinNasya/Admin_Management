

<?php
class LeaveForm
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllLeaves($staff_id)
    {
        $staff_id = intval($staff_id);
        $sql = "SELECT * FROM leave_applications WHERE staff_id = $staff_id ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getLeaveById($id)
    {
        $id = intval($id);
        $sql = "SELECT * FROM leave_applications WHERE id = $id";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }

    public function addLeave($data)
    {
        $staff_id = intval($data['staff_id']);
        $leave_type = $this->conn->real_escape_string($data['leave_type']);
        $start_date = $this->conn->real_escape_string($data['start_date']);
        $end_date = $this->conn->real_escape_string($data['end_date']);
        $total_days = floatval($data['total_days']);
        $application_date = $this->conn->real_escape_string($data['application_date']);
        $reason = $this->conn->real_escape_string($data['reason']);
        $leave_document = isset($data['leave_document']) ? $this->conn->real_escape_string($data['leave_document']) : null;
        $created_by = intval($data['created_by']);

        $sql = "INSERT INTO leave_applications (staff_id, leave_type, start_date, end_date, total_days, application_date, reason, leave_document, status, created_by, created_at)
                VALUES ($staff_id, '$leave_type', '$start_date', '$end_date', $total_days, '$application_date', '$reason', " .
            ($leave_document ? "'$leave_document'" : "NULL") . ", 'Pending', $created_by, NOW())";

        return $this->conn->query($sql);
    }

    public function updateLeave($id, $data)
    {
        $id = intval($id);
        $leave_type = $this->conn->real_escape_string($data['leave_type']);
        $start_date = $this->conn->real_escape_string($data['start_date']);
        $end_date = $this->conn->real_escape_string($data['end_date']);
        $total_days = floatval($data['total_days']);
        $application_date = $this->conn->real_escape_string($data['application_date']);
        $reason = $this->conn->real_escape_string($data['reason']);
        $leave_document = isset($data['leave_document']) ? $this->conn->real_escape_string($data['leave_document']) : null;

        $sql = "UPDATE leave_applications
                SET leave_type='$leave_type', start_date='$start_date', end_date='$end_date', 
                    total_days=$total_days, application_date='$application_date', reason='$reason', 
                    leave_document=" . ($leave_document ? "'$leave_document'" : "NULL") . ", updated_at=NOW()
                WHERE id = $id";

        return $this->conn->query($sql);
    }

    public function deleteLeave($id)
    {
        $id = intval($id);
        $sql = "DELETE FROM leave_applications WHERE id = $id";
        return $this->conn->query($sql);
    }

    public function updateStatus($id, $status)
    {
        $id = intval($id);
        $status = $this->conn->real_escape_string($status);
        $sql = "UPDATE leave_applications SET status = '$status', updated_at = NOW() WHERE id = $id";
        return $this->conn->query($sql);
    }
}

