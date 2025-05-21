<?php
require_once '../db.php'; // adjust the path if needed

class DepartModel {
    private $conn;

    public function __construct() {
        global $conn; // get the $conn from db.php
        $this->conn = $conn;
    }

    public function getAllDepartments() {
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