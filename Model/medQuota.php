<?php
require_once '../db.php'; // adjust path if needed

class MedQuota {
    private $conn;
    private $table = "medical_quota";

    public function __construct() {
        global $conn; // use the existing connection from db.php
        $this->conn = $conn;
    }

    public function getAll() {
        $sql = "SELECT * FROM {$this->table}";
        return mysqli_query($this->conn, $sql);
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = '$id'";
        return mysqli_query($this->conn, $sql);
    }

    public function insert($staff_id, $year, $quota) {
        $sql = "INSERT INTO {$this->table} (staff_id, year, quota) 
                VALUES ('$staff_id', '$year', '$quota')";
        return mysqli_query($this->conn, $sql);
    }

    public function update($id, $staff_id, $year, $quota) {
        $sql = "UPDATE {$this->table} SET 
                    staff_id = '$staff_id', 
                    year = '$year', 
                    quota = '$quota' 
                WHERE id = '$id'";
        return mysqli_query($this->conn, $sql);
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = '$id'";
        return mysqli_query($this->conn, $sql);
    }
}
