<?php
class Staff
{
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli("localhost", "root", "", "adminmanage");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getAllStaff(): array
    {
        $sql = "SELECT * FROM staff";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getStaffById(int $id): ?array
    {
        $sql = "SELECT * FROM staff WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc() ?: null;
    }

    public function insertStaff(array $data): bool
    {
        $sql = "INSERT INTO staff (staff_no, name, email, phone, department, role, profile_pic, medical_leave_quota) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['staff_no'],
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['department'],
            $data['role'],
            $data['profile_pic'],
            $data['medical_leave_quota']
        ]);
    }

    public function updateStaff(int $id, array $data): bool
    {
        $sql = "UPDATE staff SET 
                staff_no=?, name=?, email=?, phone=?,
                department=?, role=?, profile_pic=?, medical_leave_quota=?
                WHERE id=?";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['staff_no'],
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['department'],
            $data['role'],
            $data['profile_pic'],
            $data['medical_leave_quota'],
            $id
        ]);
    }

    public function deleteStaff(int $id): bool
    {
        $sql = "DELETE FROM staff WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}