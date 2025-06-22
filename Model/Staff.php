<?php
require_once '../db.php';

class Staff {
    private $conn;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function getAllStaff() {
        $query = "SELECT 
            s.*, 
            d.code AS departments_code,
            c.code AS company_code,
            r.role_name AS role_role_name,
            sh.description AS shift_description
        FROM staff s
        LEFT JOIN departments d ON s.departments_id = d.id
        LEFT JOIN companies c ON s.company_id = c.id
        LEFT JOIN roles r ON s.roles = r.role_name
        LEFT JOIN shifts sh ON s.shift_id = sh.id
        ORDER BY s.created_at DESC";

        $result = $this->conn->query($query);
        if (!$result) {
            error_log("Database error: " . $this->conn->error);
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getStaffById($id) {
        $id = (int)$this->conn->real_escape_string($id);
        $query = "SELECT 
            s.*, 
            d.name AS department_name,
            c.name AS company_name,
            b.branch_name,
            sh.description AS shift_description
        FROM staff s
        LEFT JOIN departments d ON s.departments_id = d.id
        LEFT JOIN companies c ON s.company_id = c.id
        LEFT JOIN branches b ON s.company_branch = b.id
        LEFT JOIN shifts sh ON s.shift_id = sh.id
        WHERE s.id = $id";
        
        $result = $this->conn->query($query);
        return $result ? $result->fetch_assoc() : null;
    }

    public function create($data) {
        // Validate required fields
        if (empty($data['roles']) || !$this->roleExists($data['roles'])) {
            error_log("Invalid or missing role");
            return false;
        }

        // Escape all input data
        $escapedData = array_map(function($value) {
            return $this->conn->real_escape_string($value);
        }, $data);

        $query = "INSERT INTO staff (
            staff_no, noic, pwd, name, email, phone, roles, 
            departments_id, company_id, company_branch, status, 
            gender, shift_id, leave_approval, permanent_address, 
            mail_address, status_qrcode, status_swafoto, status_monitor,
            status_marital, dependent, working_hours, break_duration,
            start_date, end_date, created_by, profile_pic
        ) VALUES (
            '{$escapedData['staff_no']}',
            '{$escapedData['noic']}',
            '{$escapedData['pwd']}',
            '{$escapedData['name']}',
            '{$escapedData['email']}',
            '{$escapedData['phone']}',
            '{$escapedData['roles']}',
            ".(int)$data['departments_id'].",
            ".(int)$data['company_id'].",
            ".(int)$data['company_branch'].",
            ".(int)$data['status'].",
            '{$escapedData['gender']}',
            ".(int)$data['shift_id'].",
            '{$escapedData['leave_approval']}',
            '{$escapedData['permanent_address']}',
            '{$escapedData['mail_address']}',
            '{$escapedData['status_qrcode']}',
            '{$escapedData['status_swafoto']}',
            '{$escapedData['status_monitor']}',
            '{$escapedData['status_marital']}',
            ".(int)$data['dependent'].",
            ".(int)$data['working_hours'].",
            ".(int)$data['break_duration'].",
            '{$escapedData['start_date']}',
            '{$escapedData['end_date']}',
            ".(int)$data['created_by'].",
            '{$escapedData['profile_pic']}'
        )";

        if (!$this->conn->query($query)) {
            error_log("Database error: " . $this->conn->error);
            return false;
        }
        return $this->conn->insert_id;
    }

    public function update($id, $data) {
        // Validate required fields
        if (empty($data['roles']) || !$this->roleExists($data['roles'])) {
            error_log("Invalid or missing role");
            return false;
        }

        // Escape all input data
        $escapedData = array_map(function($value) {
            return $this->conn->real_escape_string($value);
        }, $data);

        $query = "UPDATE staff SET 
            noic = '{$escapedData['noic']}',
            pwd = '{$escapedData['pwd']}',
            name = '{$escapedData['name']}',
            email = '{$escapedData['email']}',
            phone = '{$escapedData['phone']}',
            roles = '{$escapedData['roles']}',
            departments_id = ".(int)$data['departments_id'].",
            company_id = ".(int)$data['company_id'].",
            company_branch = ".(int)$data['company_branch'].",
            status = ".(int)$data['status'].",
            gender = '{$escapedData['gender']}',
            shift_id = ".(int)$data['shift_id'].",
            leave_approval = '{$escapedData['leave_approval']}',
            permanent_address = '{$escapedData['permanent_address']}',
            mail_address = '{$escapedData['mail_address']}',
            status_qrcode = '{$escapedData['status_qrcode']}',
            status_swafoto = '{$escapedData['status_swafoto']}',
            status_monitor = '{$escapedData['status_monitor']}',
            status_marital = '{$escapedData['status_marital']}',
            dependent = ".(int)$data['dependent'].",
            working_hours = ".(int)$data['working_hours'].",
            break_duration = ".(int)$data['break_duration'].",
            start_date = '{$escapedData['start_date']}',
            end_date = '{$escapedData['end_date']}',
            updated_by = ".(int)$data['updated_by'].",
            profile_pic = '{$escapedData['profile_pic']}'
            WHERE id = ".(int)$id;

        if (!$this->conn->query($query)) {
            error_log("Database error: " . $this->conn->error);
            return false;
        }
        return true;
    }

    public function delete($id) {
        $id = (int)$this->conn->real_escape_string($id);
        $query = "DELETE FROM staff WHERE id = $id";
        return $this->conn->query($query);
    }

    public function generateStaffNumber() {
        $year = date('y');
        $query = "SELECT staff_no FROM staff WHERE staff_no LIKE '$year/%' ORDER BY id DESC LIMIT 1";
        $result = $this->conn->query($query);
        
        if ($result && $row = $result->fetch_assoc()) {
            $parts = explode('/', $row['staff_no']);
            $last = end($parts);
            $new_num = str_pad((int)$last + 1, 4, '0', STR_PAD_LEFT);
            return $year . '/' . $new_num;
        }
        return $year . '/0001';
    }

    public function getStaffByRoles($roles) {
        if (empty($roles)) return [];
        
        // Escape and validate each role
        $escapedRoles = array_map(function($role) {
            $role = $this->conn->real_escape_string($role);
            return "'$role'";
        }, $roles);
        
        $rolesList = implode(',', $escapedRoles);
        
        $query = "SELECT s.id, s.name, s.staff_no, r.role_name 
                 FROM staff s
                 JOIN roles r ON s.roles = r.role_name
                 WHERE r.role_name IN ($rolesList)";
        
        $result = $this->conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function roleExists($role_name) {
        $role_name = $this->conn->real_escape_string($role_name);
        $query = "SELECT role_name FROM roles WHERE role_name = '$role_name'";
        $result = $this->conn->query($query);
        return $result->num_rows > 0;
    }
}