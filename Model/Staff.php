<?php
require_once '../db.php';

class Staff {
    private $conn;
    
    public function __construct() {
        global $conn; // use the shared db connection
        $this->conn = $conn;
    }

    public function getAllStaff() {
        $query = "SELECT 
            s.*, 
            d.name AS department_name,
            c.name AS company_name
        FROM staff s
        LEFT JOIN departments d ON s.departments_id = d.id
        LEFT JOIN companies c ON s.company_id = c.id
        ORDER BY s.created_at DESC";

        $result = $this->conn->query($query);
        if (!$result) {
            error_log("Database error: " . $this->conn->error);
            return false;
        }
        return $result;
    }

    public function getStaffById($id) {
        $id = (int)$id;
        $query = "SELECT 
            s.*, 
            d.name AS department_name,
            c.name AS company_name
        FROM staff s
        LEFT JOIN departments d ON s.departments_id = d.id
        LEFT JOIN companies c ON s.company_id = c.id
        WHERE s.id = $id
        LIMIT 1";

        $result = $this->conn->query($query);
        if (!$result) {
            error_log("Database error: " . $this->conn->error);
            return false;
        }
        return $result;
    }

    public function create($data) {
        $escaped = [];
        foreach ($data as $key => $value) {
            $escaped[$key] = $this->conn->real_escape_string($value ?? '');
        }

        $query = "INSERT INTO $this->table (
            staff_no, noic, pwd, name, email, phone, roles, roles_status,
            departments_id, company_id, status, gender, shift_id, leave_approval,
            permanent_address, mail_address, status_qrcode, status_swafoto, status_monitor,
            status_marital, dependent, document_name, document_size, created_by, profile_pic
        ) VALUES (
            '{$escaped['staff_no']}', '{$escaped['noic']}', '{$escaped['pwd']}',
            '{$escaped['name']}', '{$escaped['email']}', '{$escaped['phone']}',
            '{$escaped['roles']}', '{$escaped['roles_status']}',
            '{$escaped['departments_id']}', '{$escaped['company_id']}',
            '{$escaped['status']}', '{$escaped['gender']}',
            '{$escaped['shift_id']}', '{$escaped['leave_approval']}',
            '{$escaped['permanent_address']}', '{$escaped['mail_address']}',
            '{$escaped['status_qrcode']}', '{$escaped['status_swafoto']}',
            '{$escaped['status_monitor']}', '{$escaped['status_marital']}',
            '{$escaped['dependent']}', '{$escaped['document_name']}',
            '{$escaped['document_size']}', '{$escaped['created_by']}',
            '{$escaped['profile_pic']}'
        )";

        $result = $this->conn->query($query);
        if (!$result) {
            error_log("Database error: " . $this->conn->error);
            return false;
        }
        return $this->conn->insert_id;
    }

    public function update($data) {
        $escaped = [];
        foreach ($data as $key => $value) {
            $escaped[$key] = $this->conn->real_escape_string($value ?? '');
        }

        $query = "UPDATE $this->table SET 
            noic = '{$escaped['noic']}',
            pwd = '{$escaped['pwd']}',
            name = '{$escaped['name']}',
            email = '{$escaped['email']}',
            phone = '{$escaped['phone']}',
            roles = '{$escaped['roles']}',
            roles_status = '{$escaped['roles_status']}',
            departments_id = '{$escaped['departments_id']}',
            company_id = '{$escaped['company_id']}',
            status = '{$escaped['status']}',
            gender = '{$escaped['gender']}',
            shift_id = '{$escaped['shift_id']}',
            leave_approval = '{$escaped['leave_approval']}',
            permanent_address = '{$escaped['permanent_address']}',
            mail_address = '{$escaped['mail_address']}',
            status_qrcode = '{$escaped['status_qrcode']}',
            status_swafoto = '{$escaped['status_swafoto']}',
            status_monitor = '{$escaped['status_monitor']}',
            status_marital = '{$escaped['status_marital']}',
            dependent = '{$escaped['dependent']}',
            document_name = '{$escaped['document_name']}',
            document_size = '{$escaped['document_size']}',
            updated_by = '{$escaped['updated_by']}',
            profile_pic = '{$escaped['profile_pic']}'
        WHERE id = '{$escaped['id']}'";

        $result = $this->conn->query($query);
        if (!$result) {
            error_log("Database error: " . $this->conn->error);
            return false;
        }
        return $this->conn->affected_rows;
    }

    public function delete($id) {
        $id = $this->conn->real_escape_string($id);
        $query = "DELETE FROM $this->table WHERE id = '$id'";
        
        $result = $this->conn->query($query);
        if (!$result) {
            error_log("Database error: " . $this->conn->error);
            return false;
        }
        return $this->conn->affected_rows;
    }

   /* public function generateStaffNumber() {
        $year = date('y');
        $like = $year . '/%';
        $query = "SELECT staff_no FROM $this->table WHERE staff_no LIKE '$like' ORDER BY id DESC LIMIT 1";
        $result = $this->conn->query($query);
        
        if ($result && $row = $result->fetch_assoc()) {
            $last = explode('/', $row['staff_no'])[1];
            $new_num = str_pad((int)$last + 1, 4, '0', STR_PAD_LEFT);
            return $year . '/' . $new_num;
        } else {
            return $year . '/0001';
        }
    }*/
}
?>