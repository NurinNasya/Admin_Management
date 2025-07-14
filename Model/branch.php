<?php
require_once '../db.php';

class Branch {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
        
        if ($this->conn->connect_error) {
            error_log("Database connection error: " . $this->conn->connect_error);
            throw new Exception("Database connection failed");
        }
    }

    public function getAllBranches() {
        $sql = "SELECT id, branch_name FROM company_branch WHERE status = 1 ORDER BY branch_name ASC";
        $result = $this->conn->query($sql);
        
        if (!$result) {
            error_log("SQL Error in getAllBranches: " . $this->conn->error);
            throw new Exception("Failed to fetch branches");
        }
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAllBranchesWithCompany() {
        $sql = "SELECT cb.*, c.name AS company_name 
                FROM company_branch cb
                JOIN companies c ON cb.company_id = c.id
                ORDER BY cb.id DESC";
        
        $result = $this->conn->query($sql);
        
        if (!$result) {
            error_log("SQL Error in getAllBranchesWithCompany: " . $this->conn->error);
            throw new Exception("Failed to fetch branches with company details");
        }
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createBranch($company_id, $branch_code, $branch_name, $latitude = null, $longitude = null, $status = 1, $created_by = null) {
        try {
            // Validate inputs
            $company_id = $this->conn->real_escape_string($company_id);
            $branch_code = $this->conn->real_escape_string(trim($branch_code));
            $branch_name = $this->conn->real_escape_string(trim($branch_name));
            
            // Prepare NULL values for empty coordinates
            $latitude = (!empty($latitude) && is_numeric($latitude)) ? $latitude : 'NULL';
            $longitude = (!empty($longitude) && is_numeric($longitude)) ? $longitude : 'NULL';
            $created_by = (!empty($created_by)) ? $this->conn->real_escape_string($created_by) : 'NULL';

            $sql = "INSERT INTO company_branch (
                    company_id, branch_code, branch_name, 
                    latitude, longitude, status, created_by
                ) VALUES (
                    '$company_id', '$branch_code', '$branch_name',
                    $latitude, $longitude, $status, $created_by
                )";

            error_log("Executing SQL: " . $sql); // Debug SQL
            
            if ($this->conn->query($sql)) {
                return $this->conn->insert_id;
            } else {
                throw new Exception("Database error: " . $this->conn->error);
            }
        } catch (Exception $e) {
            error_log("Create Branch Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function updateBranch($id, $company_id, $branch_code, $branch_name, $latitude = null, $longitude = null, $status = 1, $updated_by = null) {
        try {
            // Validate inputs
            $id = $this->conn->real_escape_string($id);
            $company_id = $this->conn->real_escape_string($company_id);
            $branch_code = $this->conn->real_escape_string(trim($branch_code));
            $branch_name = $this->conn->real_escape_string(trim($branch_name));
            
            // Prepare NULL values for empty coordinates
            $latitude = (!empty($latitude) && is_numeric($latitude)) ? $latitude : 'NULL';
            $longitude = (!empty($longitude) && is_numeric($longitude)) ? $longitude : 'NULL';
            $updated_by = (!empty($updated_by)) ? $this->conn->real_escape_string($updated_by) : 'NULL';

            $sql = "UPDATE company_branch SET 
                    company_id = '$company_id', 
                    branch_code = '$branch_code', 
                    branch_name = '$branch_name', 
                    latitude = $latitude, 
                    longitude = $longitude, 
                    status = $status,
                    updated_by = $updated_by
                    WHERE id = '$id'";

            error_log("Executing SQL: " . $sql); // Debug SQL
            
            if ($this->conn->query($sql)) {
                return $this->conn->affected_rows > 0;
            } else {
                throw new Exception("Database error: " . $this->conn->error);
            }
        } catch (Exception $e) {
            error_log("Update Branch Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteBranch($id, $updated_by = null) {
        try {
            $id = $this->conn->real_escape_string($id);
            $updated_by = (!empty($updated_by)) ? $this->conn->real_escape_string($updated_by) : 'NULL';

            $sql = "UPDATE company_branch SET 
                    status = 0,
                    updated_by = $updated_by
                    WHERE id = '$id'";

            if ($this->conn->query($sql)) {
                return $this->conn->affected_rows > 0;
            } else {
                throw new Exception("Database error: " . $this->conn->error);
            }
        } catch (Exception $e) {
            error_log("Delete Branch Error: " . $e->getMessage());
            throw $e;
        }
    }

    public function isBranchCodeExists($branch_code, $exclude_id = null) {
        $branch_code = $this->conn->real_escape_string($branch_code);
        $sql = "SELECT id FROM company_branch WHERE branch_code = '$branch_code'";
        
        if ($exclude_id !== null) {
            $exclude_id = $this->conn->real_escape_string($exclude_id);
            $sql .= " AND id != '$exclude_id'";
        }

        $result = $this->conn->query($sql);
        
        if (!$result) {
            throw new Exception("Database error: " . $this->conn->error);
        }
        
        return $result->num_rows > 0;
    }

    public function getBranchById($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT cb.*, c.name AS company_name 
                FROM company_branch cb
                JOIN companies c ON cb.company_id = c.id
                WHERE cb.id = '$id'";
                
        $result = $this->conn->query($sql);
        
        if (!$result) {
            throw new Exception("Database error: " . $this->conn->error);
        }
        
        return $result->fetch_assoc();
    }
}