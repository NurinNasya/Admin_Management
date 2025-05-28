<?php
require_once '../db.php';

class LeaveForm {
    private $conn;

    public function __construct() {
        $this->conn = db_connect();
        if (!$this->conn) {
            throw new Exception("Database connection failed");
        }
    }

    public function create($data) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO leave_form (
                    staff_id, start_date, end_date, leave_type, total_days, 
                    description, document_name, document_size, created_at, 
                    updated_by, remark
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)
            ");
            
            $stmt->bind_param(
                "isssisssss",
                $data['staff_id'],
                $data['start_date'],
                $data['end_date'],
                $data['leave_type'],
                $data['total_days'],
                $data['description'],
                $data['document_name'],
                $data['document_size'],
                $data['updated_by'],
                $data['remark']
            );
            
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("LeaveForm Create Error: " . $e->getMessage());
            return false;
        }
    }

    public function getByStaff($staff_id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT * FROM leave_form 
                WHERE staff_id = ? 
                ORDER BY created_at DESC
            ");
            $stmt->bind_param("i", $staff_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        } catch (Exception $e) {
            error_log("LeaveForm GetByStaff Error: " . $e->getMessage());
            return [];
        }
    }

    public function updateStatus($id, $field, $status, $updated_by) {
        try {
            $validFields = ['support', 'approve', 'check'];
            if (!in_array($field, $validFields)) {
                throw new Exception("Invalid status field");
            }

            $stmt = $this->conn->prepare("
                UPDATE leave_form 
                SET {$field}_status = ?, 
                    {$field}_at = NOW(), 
                    {$field}_by = ?,
                    updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->bind_param("ssi", $status, $updated_by, $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("LeaveForm UpdateStatus Error: " . $e->getMessage());
            return false;
        }
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}