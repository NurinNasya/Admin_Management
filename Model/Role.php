<?php
require_once '../db.php';

class Role
{
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllRoles() {
        $query = "SELECT * FROM roles WHERE created_at IS NULL ORDER BY id DESC";
        $result = $this->conn->query($query);

        if (!$result) {
            throw new Exception("Database error: " . $this->conn->error);
        }

        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }

        return $roles;
    }

    public function validate($role_name, $role_type) {
        $role_name = trim($role_name);
        $role_type = trim($role_type);
        
        if (empty($role_name)) return "Role name cannot be empty";
        if (empty($role_type)) return "Role type cannot be empty";
        if (strlen($role_name) > 50) return "Role name cannot exceed 50 characters";
        if (strlen($role_type) > 50) return "Role type cannot exceed 50 characters";
        
        return null;
    }

    public function existsByNameAndType($role_name, $role_type, $exclude_id = null) {
        $sql = "SELECT id FROM roles WHERE role_name = '" . $this->conn->real_escape_string($role_name) . "' 
                AND role_type = '" . $this->conn->real_escape_string($role_type) . "'";
        
        if ($exclude_id) {
            $sql .= " AND id != " . (int)$exclude_id;
        }
        
        $result = $this->conn->query($sql);
        return $result->num_rows > 0;
    }

     public function create($role_name, $role_type, $status, $user_id) {
        $sql = "INSERT INTO roles (role_name, role_type, status, created_by, updated_by) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $this->conn->error);
        }
        
        $stmt->bind_param("ssiii", $role_name, $role_type, $status, $user_id, $user_id);
        $result = $stmt->execute();
        
        if (!$result) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        
        return $result;
    }

    /*public function create($role_name, $role_type, $status, $user_id) {
        $sql = "INSERT INTO roles (role_name, role_type, status, created_by, updated_by) 
                VALUES ('" . $this->conn->real_escape_string($role_name) . "', 
                        '" . $this->conn->real_escape_string($role_type) . "', 
                        " . (int)$status . ", 
                        " . (int)$user_id . ", 
                        " . (int)$user_id . ")";
        return $this->conn->query($sql);
    }*/

    public function update($id, $role_name, $role_type, $status, $user_id) {
        $sql = "UPDATE roles SET 
                role_name = '" . $this->conn->real_escape_string($role_name) . "', 
                role_type = '" . $this->conn->real_escape_string($role_type) . "', 
                status = " . (int)$status . ", 
                updated_by = " . (int)$user_id . ", 
                updated_at = NOW() 
                WHERE id = " . (int)$id;
        return $this->conn->query($sql);
    }

    public function softDelete($id, $user_id) {
        $sql = "UPDATE roles SET 
                deleted_at = NOW(), 
                deleted_by = " . (int)$user_id . " 
                WHERE id = " . (int)$id;
        return $this->conn->query($sql);
    }

    public function isAssignedToUsers($role_id) {
        $sql = "SELECT COUNT(*) as count FROM users WHERE role_id = " . (int)$role_id;
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
    }

    public function existsById($id) {
        $sql = "SELECT id FROM roles WHERE id = " . (int)$id . " AND deleted_at IS NULL";
        $result = $this->conn->query($sql);
        return $result->num_rows > 0;
    }

    public function getRoleTypes() {
        return ['MANAGEMENT', 'STAFF', 'INTERN'];
    }

    public function getRoleNamesByType($type) {
        $names = [
            'MANAGEMENT' => ['MANAGER', 'HR', 'FOUNDER', 'CFO', 'MANAGING-DIRECTOR', 'EXECUTIVE-DIRECTOR'],
            'STAFF' => ['STAFF', 'CONTRACT', 'HOD', 'OPERATIONMANAGER', 'EXECUTIVEAVSB', 'SUPERVISORAVSB', 'HR-AGMA'],
            'INTERN' => ['INTERN']
        ];
        
        return $names[$type] ?? [];
    }
}