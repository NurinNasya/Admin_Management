<?php
require_once '../db.php';

class Branch {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    /**
     * Get all branches for dropdown (simple version)
     * @return array Array of branches with id and branch_name
     */
    public function getAllBranches() {
        $sql = "SELECT id, branch_name FROM company_branch WHERE status = 1 ORDER BY branch_name ASC";
        $result = $this->conn->query($sql);
        
        if (!$result) {
            throw new Exception("Database error: " . $this->conn->error);
        }
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Get all branches with company details (for tables)
     * @return array Array of branches with company info
     */
    public function getAllBranchesWithCompany() {
        $sql = "SELECT cb.*, c.name AS company_name 
                FROM company_branch cb
                JOIN companies c ON cb.company_id = c.id
                ORDER BY cb.id DESC";
        
        $result = $this->conn->query($sql);
        
        if (!$result) {
            throw new Exception("Database error: " . $this->conn->error);
        }
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Create new branch
     */
    public function createBranch($company_id, $branch_code, $branch_name, $latitude = null, $longitude = null, $status = 1) {
        $company_id = $this->conn->real_escape_string($company_id);
        $branch_code = $this->conn->real_escape_string($branch_code);
        $branch_name = $this->conn->real_escape_string($branch_name);
        $latitude = $latitude !== null ? $this->conn->real_escape_string($latitude) : 'NULL';
        $longitude = $longitude !== null ? $this->conn->real_escape_string($longitude) : 'NULL';
        $status = $this->conn->real_escape_string($status);

        $sql = "INSERT INTO company_branch (
                company_id, branch_code, branch_name, 
                latitude, longitude, status, created_at
            ) VALUES (
                '$company_id', '$branch_code', '$branch_name',
                $latitude, $longitude, '$status', NOW()
            )";

        if ($this->conn->query($sql)) {
            return $this->conn->insert_id;
        } else {
            throw new Exception("Failed to add branch: " . $this->conn->error);
        }
    }

    /**
     * Update existing branch
     */
    public function updateBranch($id, $company_id, $branch_code, $branch_name, $latitude = null, $longitude = null, $status = 1) {
        $id = $this->conn->real_escape_string($id);
        $company_id = $this->conn->real_escape_string($company_id);
        $branch_code = $this->conn->real_escape_string($branch_code);
        $branch_name = $this->conn->real_escape_string($branch_name);
        $latitude = $latitude !== null ? "'" . $this->conn->real_escape_string($latitude) . "'" : 'NULL';
        $longitude = $longitude !== null ? "'" . $this->conn->real_escape_string($longitude) . "'" : 'NULL';
        $status = $this->conn->real_escape_string($status);

        $sql = "UPDATE company_branch SET 
                company_id = '$company_id', 
                branch_code = '$branch_code', 
                branch_name = '$branch_name', 
                latitude = $latitude, 
                longitude = $longitude, 
                status = '$status', 
                updated_at = NOW() 
                WHERE id = '$id'";

        if (!$this->conn->query($sql)) {
            throw new Exception("Failed to update branch: " . $this->conn->error);
        }

        return $this->conn->affected_rows > 0;
    }

    /**
     * Delete branch
     */
    public function deleteBranch($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "DELETE FROM company_branch WHERE id = '$id'";
        
        if (!$this->conn->query($sql)) {
            throw new Exception("Failed to delete branch: " . $this->conn->error);
        }

        return $this->conn->affected_rows > 0;
    }

    /**
     * Check if branch code exists
     */
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

    /**
     * Get branch by ID
     */
    public function getBranchById($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT * FROM company_branch WHERE id = '$id'";
        $result = $this->conn->query($sql);
        
        if (!$result) {
            throw new Exception("Database error: " . $this->conn->error);
        }
        
        return $result->fetch_assoc();
    }
}