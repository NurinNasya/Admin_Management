<?php
require_once '../db.php'; // make sure this path is correct

class Company {
    private $conn;

    public function __construct() {
        global $conn; // get the $conn from db.php
        $this->conn = $conn;
    }

    public function getAllCompanies() {
        $sql = "SELECT * FROM companies ORDER BY id DESC";
        return mysqli_query($this->conn, $sql);
    }

    public function createCompany($code, $name, $status) {
        $sql = "INSERT INTO companies (code, name, status) VALUES ('$code', '$name', '$status')";
        return mysqli_query($this->conn, $sql);
    }

    public function updateCompany($id, $code, $name, $status) {
        $sql = "UPDATE companies SET code='$code', name='$name', status='$status' WHERE id='$id'";
        return mysqli_query($this->conn, $sql);
    }

    public function deleteCompany($id) {
        $sql = "DELETE FROM companies WHERE id='$id'";
        return mysqli_query($this->conn, $sql);
    }

    public function getCompanyByCodeOrName($code, $name, $excludeId = null) {
        $condition = "code = '$code' OR name = '$name'";
        if ($excludeId !== null) {
            $condition .= " AND id != '$excludeId'";
        }
        $sql = "SELECT * FROM companies WHERE $condition LIMIT 1";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_fetch_assoc($result);
    }
}
