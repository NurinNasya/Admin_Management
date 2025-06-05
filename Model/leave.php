<?php
require_once '../db.php';

class Leave
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // Get all leave records with joined data
    public function getAllLeaves(): array {
        $query = "
            SELECT 
                l.*,
                s.name AS staff_name,
                d.code AS departments_code,
                c.code AS company_code
            FROM leaves l
            JOIN staff s ON l.staff_id = s.id
            LEFT JOIN departments d ON s.departments_id = d.id
            LEFT JOIN companies c ON s.company_id = c.id
            ORDER BY l.id DESC
        ";
        
        $result = $this->conn->query($query);
        
        if (!$result) {
            throw new Exception("Database error: " . $this->conn->error);
        }
        
        $leaves = [];
        while ($row = $result->fetch_assoc()) {
            $leaves[] = $row;
        }
        
        return $leaves;
    }

    // Fetch leave requests by status with all related data
    public function getLeaveRequestsByStatus($status = null): array {
        $baseQuery = "
            SELECT 
                l.*,
                s.name AS staff_name,
                d.code AS departments_code,
                c.code AS company_code
            FROM {$this->table} l
            JOIN staff s ON l.staff_id = s.id
            LEFT JOIN departments d ON s.departments_id = d.id
            LEFT JOIN companies c ON s.company_id = c.id
        ";
        
        if ($status) {
            $status = $this->conn->real_escape_string($status);
            $query = $baseQuery . " WHERE l.status = '$status'";
        } else {
            $query = $baseQuery;
        }
        
        $query .= " ORDER BY l.start_date DESC";
        
        $result = $this->conn->query($query);
        
        if (!$result) {
            throw new Exception("Database error: " . $this->conn->error);
        }
        
        $leaveRequests = [];
        while ($row = $result->fetch_assoc()) {
            $leaveRequests[] = $row;
        }
        
        return $leaveRequests;
    }

    // Get single leave record by ID
    public function getLeaveById($id): ?array {
        $id = $this->conn->real_escape_string($id);
        $query = "
            SELECT 
                l.*,
                s.name AS staff_name,
                d.code AS departments_code,
                c.code AS company_code
            FROM {$this->table} l
            JOIN staff s ON l.staff_id = s.id
            LEFT JOIN departments d ON s.departments_id = d.id
            LEFT JOIN companies c ON s.company_id = c.id
            WHERE l.id = '$id'
            LIMIT 1
        ";
        
        $result = $this->conn->query($query);
        
        if (!$result) {
            throw new Exception("Database error: " . $this->conn->error);
        }
        
        return $result->fetch_assoc();
    }

    // Update leave request status
    public function updateLeaveStatus($id, $status): bool {
        $id = $this->conn->real_escape_string($id);
        $status = $this->conn->real_escape_string($status);

        $query = "UPDATE {$this->table} SET status = '$status' WHERE id = '$id'";
        $result = $this->conn->query($query);

        if (!$result) {
            throw new Exception("Database error: " . $this->conn->error);
        }

        return $this->conn->affected_rows > 0;
    }

    // Create new leave request
    public function createLeave(array $data): bool {
        $escaped = [];
        foreach ($data as $key => $value) {
            $escaped[$key] = $this->conn->real_escape_string($value ?? '');
        }

        $query = "INSERT INTO {$this->table} (
            staff_id, leave_type, start_date, end_date, 
            total_days, reason, status, created_at
        ) VALUES (
            '{$escaped['staff_id']}', '{$escaped['leave_type']}', 
            '{$escaped['start_date']}', '{$escaped['end_date']}',
            '{$escaped['total_days']}', '{$escaped['reason']}', 
            '{$escaped['status']}', NOW()
        )";

        return $this->conn->query($query);
    }
}