<?php
require_once '../db.php';

class CompModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

       public function getAllCompanies() {
            $stmt = $this->conn->prepare("SELECT * FROM companies ORDER BY id DESC");
            $stmt->execute();
            return $stmt->get_result(); // returns result object, not array
        }
        
    public function findDuplicate($code, $name, $excludeId = null) {
        if ($excludeId) {
            $stmt = $this->conn->prepare("SELECT * FROM companies WHERE (code = ? OR name = ?) AND id != ?");
            $stmt->bind_param("ssi", $code, $name, $excludeId);
        } else {
            $stmt = $this->conn->prepare("SELECT * FROM companies WHERE code = ? OR name = ?");
            $stmt->bind_param("ss", $code, $name);
        }

        $stmt->execute();
        return $stmt->get_result();
    }

    public function insert($code, $name, $status) {
        $stmt = $this->conn->prepare("INSERT INTO companies (code, name, status) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $code, $name, $status);
        return $stmt->execute();
    }

    public function update($id, $code, $name, $status) {
        $stmt = $this->conn->prepare("UPDATE companies SET code = ?, name = ?, status = ? WHERE id = ?");
        $stmt->bind_param("ssii", $code, $name, $status, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM companies WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
