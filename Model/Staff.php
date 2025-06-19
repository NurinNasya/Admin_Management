<?php
require_once '../db.php';

class Staff {
    private $conn;
    private $table = 'staff';

    public function __construct() {
        global $conn;
        $this->conn = $conn;
        
        if ($this->conn->connect_error) {
            throw new Exception("Database connection failed: " . $this->conn->connect_error);
        }
    }

    public function getAllStaff() {
        try {
            $query = "SELECT 
                s.id, 
                s.name,
                s.email,
                s.status,
                d.code AS department_code,
                c.code AS company_code,
                r.name AS role_name
            FROM staff s
            LEFT JOIN departments d ON s.departments_id = d.id
            LEFT JOIN companies c ON s.company_id = c.id
            LEFT JOIN roles r ON s.roles_id = r.id
            ORDER BY s.created_at DESC";

            $result = $this->conn->query($query);
            
            if (!$result) {
                throw new Exception("Query failed: " . $this->conn->error);
            }

            $staffData = [];
            while ($row = $result->fetch_assoc()) {
                $staffData[] = $row;
            }
            
            return $staffData;
            
        } catch (Exception $e) {
            error_log("Error in Staff::getAllStaff(): " . $e->getMessage());
            return false;
        }
    }

    public function create($data) {
        try {
            $required = ['staff_no', 'noic', 'name', 'email', 'phone', 'roles', 'departments_id', 'company_id'];
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            $query = "INSERT INTO staff (
                staff_no, noic, pwd, name, email, phone, roles, roles_status,
                departments_id, company_id, status, gender, shift_id, leave_approval,
                permanent_address, mail_address, status_qrcode, status_swafoto, status_monitor,
                status_marital, dependent, document_name, document_size, created_by, profile_pic
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }

            $stmt->bind_param(
                "ssssssssiiisssssssssssss",
                $data['staff_no'],
                $data['noic'],
                $data['pwd'] ?? '',
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['roles'],
                $data['roles_status'] ?? 1,
                $data['departments_id'],
                $data['company_id'],
                $data['status'] ?? 1,
                $data['gender'] ?? '',
                $data['shift_id'] ?? null,
                $data['leave_approval'] ?? null,
                $data['permanent_address'] ?? '',
                $data['mail_address'] ?? '',
                $data['status_qrcode'] ?? 0,
                $data['status_swafoto'] ?? 0,
                $data['status_monitor'] ?? 0,
                $data['status_marital'] ?? '',
                $data['dependent'] ?? 0,
                $data['document_name'] ?? '',
                $data['document_size'] ?? 0,
                $data['created_by'] ?? 0,
                $data['profile_pic'] ?? ''
            );

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            return $stmt->insert_id;
            
        } catch (Exception $e) {
            error_log("Error in Staff::create(): " . $e->getMessage());
            return false;
        }
    }

    public function update($data) {
        try {
            if (empty($data['id'])) {
                throw new Exception("Missing staff ID");
            }

            $query = "UPDATE staff SET 
                noic = ?,
                pwd = ?,
                name = ?,
                email = ?,
                phone = ?,
                roles = ?,
                roles_status = ?,
                departments_id = ?,
                company_id = ?,
                status = ?,
                gender = ?,
                shift_id = ?,
                leave_approval = ?,
                permanent_address = ?,
                mail_address = ?,
                status_qrcode = ?,
                status_swafoto = ?,
                status_monitor = ?,
                status_marital = ?,
                dependent = ?,
                document_name = ?,
                document_size = ?,
                updated_by = ?,
                profile_pic = ?
            WHERE id = ?";

            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }

            $stmt->bind_param(
                "sssssssiiisssssssssssssi",
                $data['noic'],
                $data['pwd'] ?? '',
                $data['name'],
                $data['email'],
                $data['phone'],
                $data['roles'],
                $data['roles_status'] ?? 1,
                $data['departments_id'],
                $data['company_id'],
                $data['status'] ?? 1,
                $data['gender'] ?? '',
                $data['shift_id'] ?? null,
                $data['leave_approval'] ?? null,
                $data['permanent_address'] ?? '',
                $data['mail_address'] ?? '',
                $data['status_qrcode'] ?? 0,
                $data['status_swafoto'] ?? 0,
                $data['status_monitor'] ?? 0,
                $data['status_marital'] ?? '',
                $data['dependent'] ?? 0,
                $data['document_name'] ?? '',
                $data['document_size'] ?? 0,
                $data['updated_by'] ?? 0,
                $data['profile_pic'] ?? '',
                $data['id']
            );

            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            return $stmt->affected_rows;
            
        } catch (Exception $e) {
            error_log("Error in Staff::update(): " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            if (!is_numeric($id)) {
                throw new Exception("Invalid staff ID");
            }

            $query = "DELETE FROM staff WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            if (!$stmt) {
                throw new Exception("Prepare failed: " . $this->conn->error);
            }

            $stmt->bind_param("i", $id);
            if (!$stmt->execute()) {
                throw new Exception("Execute failed: " . $stmt->error);
            }

            return $stmt->affected_rows;
            
        } catch (Exception $e) {
            error_log("Error in Staff::delete(): " . $e->getMessage());
            return false;
        }
    }
}
?>