<?php
require_once '../db.php';


class Role
{
    private $conn;
    
    public function __construct() {
        global $conn; // use the shared db connection
        $this->conn = $conn;
    }

      public function getAllRoles() {
        $query = "SELECT * FROM roles ORDER BY id DESC";
        $result = $this->conn->query($query);

        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }

        return $roles;
    }

    public function validate($name)
    {
        $name = trim($name);
        if (empty($name)) return "Role name cannot be empty";
        if (strlen($name) > 255) return "Role name cannot exceed 255 characters";
        return null;
    }

    public function existsByName($name, $exclude_id = null)
    {
        $sql = "SELECT id FROM roles WHERE name = ?" . ($exclude_id ? " AND id != ?" : "");
        $stmt = $this->conn->prepare($sql);
        $exclude_id ? $stmt->bind_param("si", $name, $exclude_id) : $stmt->bind_param("s", $name);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function create($name, $status, $user_id)
    {
        $sql = "INSERT INTO roles (name, status, created_by, updated_by) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siii", $name, $status, $user_id, $user_id);
        return $stmt->execute();
    }

    public function update($id, $name, $status, $user_id)
    {
        $sql = "UPDATE roles SET name = ?, status = ?, updated_by = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("siii", $name, $status, $user_id, $id);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM roles WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function isAssignedToUsers($role_id)
    {
        $sql = "SELECT COUNT(*) as count FROM users WHERE role_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $role_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] > 0;
    }

    public function existsById($id)
    {
        $sql = "SELECT id FROM roles WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}
