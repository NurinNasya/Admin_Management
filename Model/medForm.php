<?php
require_once '../db.php';

class medForm {
    private $conn;

    public function __construct() {
        $this->conn = db_connect();
        if (!$this->conn) {
            throw new Exception("Database connection failed");
        }
    }

    public function insert($data) {
        try {
            // Prepare statement
            $stmt = $this->conn->prepare("
                INSERT INTO medical_form (
                    staff_id, receipt_data, description, total_amount,
                    document_name, document_size, created_at, updated_by, remark
                ) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?)
            ");
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }

            // Bind parameters
            $documentName = $data['document_name'] ?? null;
            $documentSize = $data['document_size'] ?? null;
            
            $stmt->bind_param(
                "issdisss", 
                $data['staff_id'],
                $data['receipt_data'],
                $data['description'],
                $data['total_amount'],
                $documentName,
                $documentSize,
                $data['updated_by'],
                $data['remark']
            );

            // Execute and return result
            return $stmt->execute();
            
        } catch (Exception $e) {
            error_log("Model Insert Error: " . $e->getMessage());
            return false;
        }
    }

    public function getAll($staff_id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT * FROM medical_form 
                WHERE staff_id = ? 
                ORDER BY created_at DESC
            ");
            
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }
            
            $stmt->bind_param("i", $staff_id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);
            
        } catch (Exception $e) {
            error_log("Model GetAll Error: " . $e->getMessage());
            return [];
        }
    }

    public function __destruct() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}