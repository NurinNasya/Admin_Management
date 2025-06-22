<?php
require_once __DIR__ . '/../db.php';

class Role
{
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
        
        if (!$this->conn || $this->conn->connect_error) {
            throw new Exception("Database connection failed");
        }
    }

   public function getAllRoles() {
    // Simple version without filtering
    $query = "SELECT * FROM roles ORDER BY id DESC";
    
    // Or if you want to check for non-null created_at
    // $query = "SELECT * FROM roles WHERE created_at IS NOT NULL ORDER BY id DESC";
    
    $result = $this->conn->query($query);

    if (!$result) {
        throw new Exception("Query failed: " . $this->conn->error);
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
        $role_name = $this->conn->real_escape_string($role_name);
        $role_type = $this->conn->real_escape_string($role_type);
        
        $sql = "SELECT id FROM roles 
                WHERE role_name = '$role_name' 
                AND role_type = '$role_type' 
                AND created_at IS NULL";
        
        if ($exclude_id) {
            $sql .= " AND id != " . (int)$exclude_id;
        }
        
        $result = $this->conn->query($sql);
        return $result->num_rows > 0;
    }

    public function create($role_name, $role_type, $status, $user_id) {
        $role_name = $this->conn->real_escape_string($role_name);
        $role_type = $this->conn->real_escape_string($role_type);
        $status = (int)$status;
        $user_id = (int)$user_id;
        
        $sql = "INSERT INTO roles 
                (role_name, role_type, status, created_at, updated_at) 
                VALUES 
                ('$role_name', '$role_type', $status, $user_id, $user_id)";
        
        $result = $this->conn->query($sql);
        
        if (!$result) {
            throw new Exception("Create failed: " . $this->conn->error);
        }
        
        return $this->conn->insert_id;
    }

    public function update($id, $role_name, $role_type, $status, $user_id) {
    $role_name = $this->conn->real_escape_string($role_name);
    $role_type = $this->conn->real_escape_string($role_type);
    $status = (int)$status;
    $id = (int)$id;
    
    // Removed updated_by since column doesn't exist
    $sql = "UPDATE roles SET 
            role_name = '$role_name', 
            role_type = '$role_type', 
            status = $status
            WHERE id = $id";
            
    $result = $this->conn->query($sql);
    
    if (!$result) {
        throw new Exception("Update failed: " . $this->conn->error);
    }
    
    return $result;
}

public function softDelete($id, $user_id) {
    $id = (int)$id;
    
    // Check if soft delete columns exist
    $check = $this->conn->query("SHOW COLUMNS FROM roles LIKE 'deleted_at'");
    if ($check->num_rows > 0) {
        // Soft delete
        $sql = "UPDATE roles SET 
                deleted_at = NOW() 
                WHERE id = $id";
    } else {
        // Physical delete
        $sql = "DELETE FROM roles WHERE id = $id";
    }
    
    return $this->conn->query($sql);
}

public function existsById($id) {
    $id = (int)$id;
    // Simplified since we don't have deleted_at column
    $sql = "SELECT id FROM roles WHERE id = $id";
    $result = $this->conn->query($sql);
    return $result->num_rows > 0;
}

    public function isAssignedToUsers($role_id) {
        $role_id = (int)$role_id;
        $sql = "SELECT COUNT(*) as count FROM users WHERE role_id = $role_id";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['count'] > 0;
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