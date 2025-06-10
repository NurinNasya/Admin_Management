<?php
require_once '../db.php'; // adjust path if needed

class Shift {
    private $conn;

    public function __construct() {
        global $conn; // get the connection from db.php
        $this->conn = $conn;
    }

    public function getAllShifts() {
        $sql = "SELECT * FROM shifts ORDER BY id DESC";
        return mysqli_query($this->conn, $sql);
    }

    public function checkDuplicateCode($code) {
        $sql = "SELECT id FROM shifts WHERE code = '$code'";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_num_rows($result) > 0;
    }

    public function checkDuplicateStartTime($start_time) {
        $sql = "SELECT id FROM shifts WHERE start_time = '$start_time'";
        $result = mysqli_query($this->conn, $sql);
        return mysqli_num_rows($result) > 0;
    }

    public function createShift($code, $description, $start_time, $work_hour, $break_hour, $status) {
        $sql = "INSERT INTO shifts (code, description, start_time, work_hour, break_hour, status) 
                VALUES ('$code', '$description', '$start_time', '$work_hour', '$break_hour', '$status')";
        return mysqli_query($this->conn, $sql);
    }

    public function updateShift($id, $description, $start_time, $work_hour, $break_hour, $status) {
        $sql = "UPDATE shifts SET description='$description', start_time='$start_time', work_hour='$work_hour', break_hour='$break_hour', status='$status' WHERE id='$id'";
        return mysqli_query($this->conn, $sql);
    }

    public function deleteShift($code) {
        $sql = "DELETE FROM shifts WHERE code='$code'";
        return mysqli_query($this->conn, $sql);
    }
}
