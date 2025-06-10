<?php
require_once '../db.php'; // adjust the path if needed

class Depart {
    private $conn;

    public function __construct() {
        global $conn; // use the shared db connection
        $this->conn = $conn;
    }

    // ✅ Use this for dropdowns and anywhere you need array of departments
    public function getAllDepartments(): array {
        $sql = "SELECT id, code, name FROM departments ORDER BY name ASC";
        $result = $this->conn->query($sql);
        $departments = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $departments[] = $row;
            }
        }
 
        return $departments;
    }

    // ✅ Use this for showing in department management table (your original style)
    public function getAllRaw() {
        $sql = "SELECT * FROM departments ORDER BY id DESC";
        return mysqli_query($this->conn, $sql);
    }

    public function insert($code, $name, $status) {
        $sql = "INSERT INTO departments (code, name, status) VALUES ('$code', '$name', '$status')";
        return mysqli_query($this->conn, $sql);
    }

    public function update($id, $code, $name, $status) {
        $sql = "UPDATE departments SET code='$code', name='$name', status='$status' WHERE id='$id'";
        return mysqli_query($this->conn, $sql);
    }

    public function delete($id) {
        $sql = "DELETE FROM departments WHERE id='$id'";
        return mysqli_query($this->conn, $sql);
    }

    public function findDuplicate($code, $name, $excludeId = null) {
        $condition = "code = '$code' OR name = '$name'";
        if ($excludeId !== null) {
            $condition .= " AND id != '$excludeId'";
        }
        $sql = "SELECT * FROM departments WHERE $condition LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_assoc($result);
    }
}
