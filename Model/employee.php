<?php
require_once '../db.php';

class Employee {
    private $conn; 

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllStaffWithDepartment() {
        $sql = "SELECT s.*, d.name AS department 
                FROM staff s
                LEFT JOIN departments d ON s.departments_id = d.id
                ORDER BY s.name ASC";

        $result = mysqli_query($this->conn, $sql);
        $staff = [];

        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $staff[] = $row;
            }
        }

        return $staff;
    }

    public function insertEmployee($data) {
        $sql = "INSERT INTO staff (
            noic, name, pwd, email, phone, gender, status_marital, dependent, staff_no,
            permenant_address, mail_address, roles, roles_status, profile_pic, departments_id
        ) VALUES (
            '{$data['noic']}', '{$data['name']}', '{$data['pwd']}', '{$data['email']}',
            '{$data['phone']}', '{$data['gender']}', '{$data['status_marital']}',
            '{$data['dependent']}', '{$data['staff_no']}', '{$data['permenant_address']}',
            '{$data['mail_address']}', '{$data['roles']}', '{$data['roles_status']}',
            '{$data['profile_pic']}', '{$data['departments_id']}'
        )";

        return $this->conn->query($sql);
    }

    // ✅ New method to fetch one employee by ID
    public function getEmployeeById($id) {
        $id = intval($id); // sanitize ID
        $sql = "SELECT * FROM staff WHERE id = $id LIMIT 1";
        $result = mysqli_query($this->conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            return mysqli_fetch_assoc($result);
        }

        return null;
    }
}
