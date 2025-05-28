<?php

require_once '../db.php';

class Staff
{
    private $conn;

    public function __construct()
    {
        $db = new db();
        $this->conn = $db->getConnection();
    }

    public function getStaffById($id)
    {
        $id = mysqli_real_escape_string($this->conn, $id);
        $query = "SELECT * FROM staff WHERE id = '$id'";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }

    public function getAllStaff()
    {
        $query = "SELECT * FROM staff";
        $result = mysqli_query($this->conn, $query);
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
        return $data;
    }

    public function findDuplicate($noic, $email, $id)
    {
        $noic = mysqli_real_escape_string($this->conn, $noic);
        $email = mysqli_real_escape_string($this->conn, $email);
        $id = mysqli_real_escape_string($this->conn, $id);

        $query = "SELECT * FROM staff WHERE (noic = '$noic' OR email = '$email') AND id != '$id'";
        return mysqli_query($this->conn, $query);
    }

    public function updateStaff($data)
    {
        $id = mysqli_real_escape_string($this->conn, $data['id']);
        $name = mysqli_real_escape_string($this->conn, $data['name']);
        $noic = mysqli_real_escape_string($this->conn, $data['noic']);
        $email = mysqli_real_escape_string($this->conn, $data['email']);
        $phone = mysqli_real_escape_string($this->conn, $data['phone']);
        $gender = mysqli_real_escape_string($this->conn, $data['gender']);
        $status_marital = mysqli_real_escape_string($this->conn, $data['status_marital']);
        $dependent = mysqli_real_escape_string($this->conn, $data['dependent']);
        $roles = mysqli_real_escape_string($this->conn, $data['roles']);
        $roles_status = mysqli_real_escape_string($this->conn, $data['roles_status']);
        $staff_no = mysqli_real_escape_string($this->conn, $data['staff_no']);
        $status = mysqli_real_escape_string($this->conn, $data['status']);
        $departments_id = mysqli_real_escape_string($this->conn, $data['departments_id']);
        $permenant_address = mysqli_real_escape_string($this->conn, $data['permenant_address']);
        $mail_address = mysqli_real_escape_string($this->conn, $data['mail_address']);
        $profile_pic = mysqli_real_escape_string($this->conn, $data['profile_pic'] ?? '');

        $setPassword = '';
        if (!empty($data['pwd'])) {
            $password = password_hash($data['pwd'], PASSWORD_DEFAULT);
            $setPassword = ", pwd = '$password'";
        }

        $removePic = '';
        if (!empty($data['remove_profile_pic'])) {
            $profile_pic = '';
        }

        $updateQuery = "
            UPDATE staff SET 
                name = '$name',
                noic = '$noic',
                email = '$email',
                phone = '$phone',
                gender = '$gender',
                status_marital= '$status_marital',
                dependent = '$dependent',
                roles = '$roles',
                roles_status = '$roles_status',
                staff_no = '$staff_no',
                status = '$status',
                departments_id = '$departments_id',
                permenant_address = '$permenant_address',
                mail_address = '$mail_address',
                profile_pic = '$profile_pic'
                $setPassword
            WHERE id = '$id'
        ";

        return mysqli_query($this->conn, $updateQuery);
    }
}
