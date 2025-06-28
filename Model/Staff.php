<?php
require_once '../db.php';

class Staff {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // ==============================================
    // GET ALL STAFF
    // ==============================================
    public function getAllStaff() {
        $query = "SELECT 
            s.id,
            s.name,
            s.roles,
            s.phone,
            d.code AS department_code,
            c.code AS company_code,
            r.role_name
        FROM staff s
        LEFT JOIN departments d ON s.departments_id = d.id
        LEFT JOIN companies c ON s.company_id = c.id
        LEFT JOIN roles r ON s.roles = r.role_name
        ORDER BY s.created_at DESC";

        $result = $this->conn->query($query);
        if (!$result) {
            error_log("Database error: " . $this->conn->error);
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // ==============================================
    // GET STAFF BY ID
    // ==============================================
    public function getStaffById($id) {
        $id = (int)$this->conn->real_escape_string($id);
        $query = "SELECT 
            s.*,
            d.code AS department_code,
            c.code AS company_code,
            r.role_name
        FROM staff s
        LEFT JOIN departments d ON s.departments_id = d.id
        LEFT JOIN companies c ON s.company_id = c.id
        LEFT JOIN roles r ON s.roles = r.role_name
        WHERE s.id = $id";
        
        $result = $this->conn->query($query);
        return $result ? $result->fetch_assoc() : null;
    }

    // ==============================================
    // DROPDOWN DATA METHODS
    // ==============================================
    public function getAllRoles() {
        $query = "SELECT * FROM roles ORDER BY role_name";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllDepartments() {
        $query = "SELECT * FROM departments ORDER BY name";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllCompanies() {
        $query = "SELECT * FROM companies ORDER BY name";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllBranches() {
        $query = "SELECT * FROM company_branch ORDER BY branch_name";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getAllShifts() {
        $query = "SELECT * FROM shifts ORDER BY description";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    // ==============================================
    // CRUD OPERATIONS
    // ==============================================
    public function create($data) {
        $escapedData = array_map([$this->conn, 'real_escape_string'], $data);

        $query = "INSERT INTO staff (
            staff_no, noic, pwd, name, email, phone, roles, 
            departments_id, company_id, company_branch, status, 
            gender, shift_id, working_hours, break_duration,
            start_date, end_date, permanent_address, mail_address,
            status_qrcode, status_swafoto, status_monitor,
            status_marital, dependent, created_by, profile_pic
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
            ".(float)$data['working_hours'].",
            ".(float)$data['break_duration'].",
            '{$escapedData['start_date']}',
            '{$escapedData['end_date']}',
            '{$escapedData['permanent_address']}',
            '{$escapedData['mail_address']}',
            ".(int)$data['status_qrcode'].",
            ".(int)$data['status_swafoto'].",
            ".(int)$data['status_monitor'].",
            ".(int)$data['status_marital'].",
            ".(int)$data['dependent'].",
            ".(int)$data['created_by'].",
            '{$escapedData['profile_pic']}'
        )";

        return $this->conn->query($query);
    }

    // ==============================================
    // UPDATE STAFF - FIXED VERSION
    // ==============================================
    public function update($id, $data) {
        $id = (int)$id;
        if ($id <= 0) {
            error_log("Invalid staff ID for update: $id");
            return false;
        }

        // Prepare allowed fields
        $allowedFields = [
            'name', 'email', 'phone', 'roles',
            'departments_id', 'company_id', 'company_branch',
            'status', 'gender', 'shift_id', 'status_marital',
            'dependent', 'permanent_address', 'mail_address',
            'status_qrcode', 'status_swafoto', 'status_monitor',
            'start_date', 'end_date', 'updated_by'
        ];

        // Build SET clauses
        $updates = [];
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $value = $this->conn->real_escape_string($data[$field]);
                if (is_numeric($value) && $value == (int)$value) {
                    $updates[] = "$field = ".(int)$value;
                } else {
                    $updates[] = "$field = '$value'";
                }
            }
        }

        // Add system fields
        $updates[] = "updated_at = NOW()";

        // Build and execute query
        $query = "UPDATE staff SET ".implode(', ', $updates)." WHERE id = $id";
        
        error_log("UPDATE QUERY: $query");
        $result = $this->conn->query($query);
        
        if (!$result) {
            error_log("UPDATE FAILED: ".$this->conn->error);
            return false;
        }
        
        return true; // Return true even if no rows changed
    }

    // ==============================================
    // DELETE STAFF
    // ==============================================
    public function delete($id) {
        $id = (int)$this->conn->real_escape_string($id);
        if ($id <= 0) {
            error_log("Invalid ID for deletion: $id");
            return false;
        }

        $query = "DELETE FROM staff WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        
        if (!$success) {
            error_log("Delete failed: " . $stmt->error);
        }

        $stmt->close();
        return $success;
    }

    // ==============================================
    // UTILITY METHODS
    // ==============================================
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