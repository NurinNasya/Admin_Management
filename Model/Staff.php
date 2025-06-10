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
        $sql = "SELECT s.*, d.code AS departments_code, c.code AS company_code
                FROM staff s
                LEFT JOIN departments d ON s.departments_id = d.id
                LEFT JOIN companies c ON s.company_id = c.id
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

    // Get max numeric suffix of staff_no for a given year prefix (e.g. "25")
    public function getMaxStaffNumberSuffixForYear(string $yearPrefix): int
    {
        $yearPrefix = $this->conn->real_escape_string($yearPrefix);
        $sql = "SELECT MAX(CAST(SUBSTRING_INDEX(staff_no, '/', -1) AS UNSIGNED)) AS max_suffix
                FROM staff
                WHERE staff_no LIKE '{$yearPrefix}/%'";

        $result = $this->conn->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return (int)$row['max_suffix'];
        }
        return 0;
    }

    // Generate staff_no for display (e.g. when showing the add form)
    public function generateStaffNoForDisplay(): string
    {
        $yearPrefix = date('y'); // last two digits of current year
        $maxSuffix = $this->getMaxStaffNumberSuffixForYear($yearPrefix);
        $nextNumber = $maxSuffix > 0 ? $maxSuffix + 1 : 1000;

        return $yearPrefix . '/' . $nextNumber;
    }

    // Private method to generate staff_no for insert (internal use)
    private function generateStaffNoForInsert(): string
    {
        return $this->generateStaffNoForDisplay();
    }

    // Insert new staff record with auto-generated staff_no
    public function insertStaff(array $data): bool
    {
        $escaped = [];
        foreach ($data as $key => $value) {
            $escaped[$key] = $this->conn->real_escape_string($value ?? '');
        }

        // Hash password if provided
        if (!empty($escaped['pwd'])) {
            $escaped['pwd'] = password_hash($escaped['pwd'], PASSWORD_DEFAULT);
        }

        // Generate staff_no here, ignoring any staff_no from $data
        $staff_no = $this->generateStaffNoForInsert();

        $sql = "INSERT INTO staff (
            noic, name, pwd, email, phone, gender, status_marital, dependent, staff_no,
            permanent_address, mail_address, roles, roles_status, profile_pic, departments_id, company_id
        ) VALUES (
            '{$escaped['noic']}', '{$escaped['name']}', '{$escaped['pwd']}', '{$escaped['email']}',
            '{$escaped['phone']}', '{$escaped['gender']}', '{$escaped['status_marital']}',
            '{$escaped['dependent']}', '$staff_no', '{$escaped['permanent_address']}',
            '{$escaped['mail_address']}', '{$escaped['roles']}', '{$escaped['roles_status']}',
            '{$escaped['profile_pic']}', '{$escaped['departments_id']}', '{$escaped['company_id']}'
        )";

        return $this->conn->query($sql);
    }

    public function getStaffById($id)
    {
        $id = $this->conn->real_escape_string($id);
        $sql = "SELECT s.*, d.code AS departments_code, c.code AS company_code
                FROM staff s
                LEFT JOIN departments d ON s.departments_id = d.id
                LEFT JOIN companies c ON s.company_id = c.id
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

    // Update staff info - staff_no is NOT updated here
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
                roles = '{$escaped['roles']}',
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
}
