<?php
require_once '../db.php';

class Staff
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllStaff(): array
    {
        $sql = "SELECT s.*, d.code AS departments_code, c.code AS company_code, r.name AS roles_name
                FROM staff s
                LEFT JOIN departments d ON s.departments_id = d.id
                LEFT JOIN companies c ON s.company_id = c.id
                LEFT JOIN roles r ON s.roles_id = r.id
                ORDER BY s.id DESC";

        $result = $this->conn->query($sql);
        $staffList = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $staffList[] = $row;
            }
        }

        return $staffList;
    }

    public function getMaxStaffNumberSuffixForYear(string $yearPrefix): int
    {
        $yearPrefix = $this->conn->real_escape_string($yearPrefix);
        $sql = "SELECT MAX(CAST(SUBSTRING(staff_no, LOCATE('/', staff_no) + 1) AS UNSIGNED)) AS max_suffix
                FROM staff
                WHERE staff_no LIKE '{$yearPrefix}/%'";

        $result = $this->conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return (int)$row['max_suffix'] > 0 ? (int)$row['max_suffix'] : 999;
        }
        return 999;
    }

    public function generateStaffNoForDisplay(): string
    {
        $yearPrefix = date('y');
        $maxSuffix = $this->getMaxStaffNumberSuffixForYear($yearPrefix);
        $nextNumber = $maxSuffix + 1;
        return $yearPrefix . '/' . $nextNumber;
    }

    private function generateStaffNoForInsert(): string
    {
        return $this->generateStaffNoForDisplay();
    }

    public function insertStaff(array $data): bool
    {
        $escaped = [];
        foreach ($data as $key => $value) {
            $escaped[$key] = $this->conn->real_escape_string($value ?? '');
        }

        if (!empty($escaped['pwd'])) {
            $escaped['pwd'] = password_hash($escaped['pwd'], PASSWORD_DEFAULT);
        }

        $staff_no = $this->generateStaffNoForInsert();

        $sql = "INSERT INTO staff (
            noic, name, pwd, email, phone, gender, status_marital, dependent, staff_no,
            permanent_address, mail_address, roles_id, roles_status, profile_pic, departments_id, company_id
        ) VALUES (
            '{$escaped['noic']}', '{$escaped['name']}', '{$escaped['pwd']}', '{$escaped['email']}',
            '{$escaped['phone']}', '{$escaped['gender']}', '{$escaped['status_marital']}',
            '{$escaped['dependent']}', '$staff_no', '{$escaped['permanent_address']}',
            '{$escaped['mail_address']}', '{$escaped['roles_id']}', '{$escaped['roles_status']}',
            '{$escaped['profile_pic']}', '{$escaped['departments_id']}', '{$escaped['company_id']}'
        )";

        return $this->conn->query($sql);
    }

    public function updateStaff(array $data): bool
    {
        $escaped = [];
        foreach ($data as $key => $value) {
            $escaped[$key] = $this->conn->real_escape_string($value ?? '');
        }

        $sql = "UPDATE staff SET
                name = '{$escaped['name']}',
                noic = '{$escaped['noic']}',
                email = '{$escaped['email']}',
                phone = '{$escaped['phone']}',
                gender = '{$escaped['gender']}',
                status_marital = '{$escaped['status_marital']}',
                dependent = '{$escaped['dependent']}',
                roles_id = '{$escaped['roles_id']}',
                roles_status = '{$escaped['roles_status']}',
                status = '{$escaped['status']}',
                departments_id = '{$escaped['departments_id']}',
                company_id = '{$escaped['company_id']}',
                permanent_address = '{$escaped['permanent_address']}',
                mail_address = '{$escaped['mail_address']}',
                profile_pic = '{$escaped['profile_pic']}'";

        if (!empty($escaped['pwd'])) {
            $hashed_pwd = password_hash($escaped['pwd'], PASSWORD_DEFAULT);
            $sql .= ", pwd = '$hashed_pwd'";
        }

        $sql .= " WHERE id = '{$escaped['id']}'";

        return $this->conn->query($sql);
    }

    // In getStaffById() method, ensure you're joining with roles table correctly:
    public function getStaffById($id) {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT s.*, d.code AS departments_code, c.code AS company_code, r.name AS role_name, r.id AS role_id
                FROM staff s
                LEFT JOIN departments d ON s.departments_id = d.id
                LEFT JOIN companies c ON s.company_id = c.id
                LEFT JOIN roles r ON s.roles_id = r.id
                WHERE s.id = $id
                LIMIT 1";

        $result = $this->conn->query($sql);
        return $result->fetch_assoc();
    }

    public function getDepartments(): array
    {
        $result = $this->conn->query("SELECT * FROM departments");
        $departments = [];
        while ($row = $result->fetch_assoc()) {
            $departments[] = $row;
        }
        return $departments;
    }

    public function getCompanies(): array
    {
        $result = $this->conn->query("SELECT * FROM companies");
        $companies = [];
        while ($row = $result->fetch_assoc()) {
            $companies[] = $row;
        }
        return $companies;
    }

    public function getRoles(): array
    {
        $result = $this->conn->query("SELECT * FROM roles");
        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }
        return $roles;
    }

    public function checkDuplicate($noic, $email, $excludeId = null): bool
    {
        $noic = $this->conn->real_escape_string($noic);
        $email = $this->conn->real_escape_string($email);

        $sql = "SELECT COUNT(*) as count FROM staff 
                WHERE (noic = '$noic' OR email = '$email')";

        if ($excludeId) {
            $excludeId = $this->conn->real_escape_string($excludeId);
            $sql .= " AND id != '$excludeId'";
        }

        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();

        return $row['count'] > 0;
    }
}
