<?php
require_once '../db.php';

class Company {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllRaw() {
        $result = $this->conn->query("SELECT * FROM companies ORDER BY id DESC");
        if (!$result) {
            error_log("Query failed: " . $this->conn->error);
            return false;
        }
        return $result;
    }

    public function getAllCompanies(): array {
        $companies = [];
        $result = $this->conn->query("SELECT id, code, name, status FROM companies ORDER BY id DESC");
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $companies[] = $row;
            }
        }
        
        return $companies;
    }

    public function createCompany(string $code, string $name, int $status): bool {
        if (empty($code) || empty($name)) {
            throw new InvalidArgumentException("Company code and name cannot be empty");
        }

        $code = $this->conn->real_escape_string($code);
        $name = $this->conn->real_escape_string($name);
        $status = (int)$status;
        
        $sql = "INSERT INTO companies (code, name, status) VALUES ('$code', '$name', $status)";
        return $this->conn->query($sql);
    }

    public function updateCompany(int $id, string $code, string $name, int $status): bool {
        if (empty($code) || empty($name)) {
            throw new InvalidArgumentException("Company code and name cannot be empty");
        }

        $id = (int)$id;
        $code = $this->conn->real_escape_string($code);
        $name = $this->conn->real_escape_string($name);
        $status = (int)$status;
        
        $sql = "UPDATE companies SET code='$code', name='$name', status=$status WHERE id=$id";
        return $this->conn->query($sql);
    }

    public function deleteCompany(int $id): bool {
        $id = (int)$id;
        
        // Check if company exists first
        $check = $this->conn->query("SELECT id FROM companies WHERE id=$id");
        if (!$check || $check->num_rows === 0) {
            return false;
        }

        return $this->conn->query("DELETE FROM companies WHERE id=$id");
    }

    public function getCompanyByCodeOrName(string $code, string $name, ?int $excludeId = null): ?array {
        $code = $this->conn->real_escape_string($code);
        $name = $this->conn->real_escape_string($name);
        
        $sql = "SELECT * FROM companies WHERE (code = '$code' OR name = '$name')";
        
        if ($excludeId !== null) {
            $excludeId = (int)$excludeId;
            $sql .= " AND id != $excludeId";
        }
        
        $sql .= " LIMIT 1";
        $result = $this->conn->query($sql);
        
        return $result ? $result->fetch_assoc() : null;
    }
}