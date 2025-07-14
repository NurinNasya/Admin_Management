<?php
require_once __DIR__ . '/../db.php';

class Quota {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addQuota($staffId, $additionalQuota, $notes) {
        // Check if staff already has a quota record
        $existingQuota = $this->getStaffQuota($staffId);
        
        if ($existingQuota) {
            // If exists, update by adding to the existing quota
            $newQuota = $existingQuota['additional_quota'] + $additionalQuota;
            $combinedNotes = $existingQuota['notes'] . "\n\n" . date('Y-m-d H:i') . ": Added RM " . number_format($additionalQuota, 2) . "\n" . $notes;
            
            $stmt = $this->conn->query("
                UPDATE staff_medical_quota 
                SET additional_quota = $newQuota,
                    notes = '$combinedNotes',
                    updated_at = NOW()
                WHERE staff_id = $staffId
            ");
        } else {
            // If doesn't exist, insert new record
            $stmt = $this->conn->query("
                INSERT INTO staff_medical_quota 
                (staff_id, additional_quota, notes, created_at, updated_at)
                VALUES ($staffId, $additionalQuota, '$notes', NOW(), NOW())
            ");
        }
        
        return $stmt;
    }

    public function deleteQuotaById($quotaId) {
        $stmt = $this->conn->query("
            DELETE FROM staff_medical_quota 
            WHERE id = $quotaId
        ");
        return $stmt;
    }

    public function updateQuota($quotaId, $additionalQuota, $notes) {
        $stmt = $this->conn->query("
            UPDATE staff_medical_quota 
            SET additional_quota = $additionalQuota,
                notes = '$notes',
                updated_at = NOW()
            WHERE id = $quotaId
        ");
        return $stmt;
    }

    public function getAllQuotaAllocations() {
        $query = "
            SELECT 
                q.id,
                q.additional_quota,
                q.notes,
                q.updated_at,
                s.name as staff_name,
                s.id as staff_id,
                d.name as department_name,
                d.id as department_id
            FROM staff_medical_quota q
            JOIN staff s ON q.staff_id = s.id
            LEFT JOIN departments d ON s.departments_id = d.id
            ORDER BY q.updated_at DESC
        ";
        
        $result = mysqli_query($this->conn, $query);
        
        if (!$result) {
            error_log("[Quota] Database error: " . mysqli_error($this->conn));
            return [];
        }
        
        $quotas = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $quotas[] = $row;
        }
        
        return $quotas;
    }

    public function getStaffQuota($staffId) {
        $result = $this->conn->query("
            SELECT * FROM staff_medical_quota 
            WHERE staff_id = $staffId
        ");
        return $result->fetch_assoc();
    }
}