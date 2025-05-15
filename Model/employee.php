<?php
class Employee {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "adminmanage");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function insertEmployee($data) {
        $sql = "INSERT INTO employees (
            ic_number, name, dob, status, gender, phone, address, mailing_address,
            position, department, grade, staff_no, appointment_date, position_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssssssss",
            $data['ic_number'],
            $data['name'],
            $data['dob'],
            $data['status'],
            $data['gender'],
            $data['phone'],
            $data['address'],
            $data['mailing_address'],
            $data['position'],
            $data['department'],
            $data['grade'],
            $data['staff_no'],
            $data['appointment_date'],
            $data['position_status']
        );
        $stmt->execute();
        $stmt->close();
    }
}
