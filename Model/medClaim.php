<?php
require_once __DIR__ . '/../db.php';

class MedClaim {
    private $conn;
    
    public function __construct($conn) {
        if (!$conn) {
            throw new Exception("Database connection failed");
        }
        $this->conn = $conn;
    }

    // Get current claims with pagination
    // public function getCurrentClaims($staffId, $page = 1, $perPage = 100) {
    //     $offset = ($page - 1) * $perPage;
        
    //     $result = $this->conn->query("
    //         SELECT * FROM medclaims 
    //         WHERE staff_id = $staffId
    //         AND (status = 'pending' OR status = 'approved')
    //         ORDER BY created_at DESC, id DESC
    //         LIMIT $perPage OFFSET $offset
    //     ");
        
    //     $claims = [];
    //     while ($row = $result->fetch_assoc()) {
    //         $claims[] = $row;
    //     }
        
    //     return [
    //         'claims' => $claims,
    //         'total' => $this->getClaimsCount($staffId, ['pending', 'approved'])
    //     ];
    // }

    // Modify getCurrentClaims() to include rejected claims and their reasons
public function getCurrentClaims($staffId, $page = 1, $perPage = 100) {
    $offset = ($page - 1) * $perPage;
    
    $result = $this->conn->query("
        SELECT * FROM medclaims 
        WHERE staff_id = $staffId
        AND (status = 'pending' OR status = 'approved' OR status = 'rejected')
        ORDER BY created_at DESC, id DESC
        LIMIT $perPage OFFSET $offset
    ");
    
    $claims = [];
    while ($row = $result->fetch_assoc()) {
        $claims[] = $row;
    }
    
    return [
        'claims' => $claims,
        'total' => $this->getClaimsCount($staffId, ['pending', 'approved', 'rejected'])
    ];
}

    // Get historical claims
    public function getHistoryClaims($staffId, $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $result = $this->conn->query("
            SELECT * FROM medclaims 
            WHERE staff_id = $staffId 
            AND status IN ('approved', 'rejected')
            ORDER BY created_at DESC 
            LIMIT $perPage OFFSET $offset
        ");
        
        return [
            'claims' => $result->fetch_all(MYSQLI_ASSOC),
            'total' => $this->getClaimsCount($staffId, ['approved', 'rejected'])
        ];
    }

    // Add new claim
    public function addClaim($staffId, $data) {
        $dateReceipt = $this->conn->real_escape_string($data['date_receipt']);
        $description = $this->conn->real_escape_string($data['description']);
        $total = (float)$data['total'];
        $attachment = $this->conn->real_escape_string($data['attachment']);
        $documentSize = (int)$data['documentSize'];
        
        return $this->conn->query("
            INSERT INTO medclaims 
            (staff_id, date_receipt, description, total, 
             document_name, document_size, created_at, status) 
            VALUES ($staffId, '$dateReceipt', '$description', $total, 
                   '$attachment', $documentSize, NOW(), 'pending')
        ");
    }

    // Update existing claim
    public function updateClaim($claimId, $staffId, $data) {
        try {
            $this->conn->begin_transaction();

            $dateReceipt = $this->conn->real_escape_string($data['edit_date_receipt']);
            $total = (float)$data['edit_total'];
            $description = $this->conn->real_escape_string($data['edit_description']);
            
            $query = "UPDATE medclaims SET date_receipt='$dateReceipt', total=$total, description='$description'";
            
            if (!empty($data['edit_attachment'])) {
                $attachment = $this->conn->real_escape_string($data['edit_attachment']);
                $query .= ", document_name='$attachment'";
            }
            
            $query .= " WHERE id=$claimId AND staff_id=$staffId";
            
            $result = $this->conn->query($query);
            if (!$result) {
                throw new Exception("Query failed: " . $this->conn->error);
            }
            
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Update Claim Error: " . $e->getMessage());
            return false;
        }
    }

    // Delete claim
    public function deleteClaim($claimId, $staffId) {
        return $this->conn->query("
            DELETE FROM medclaims 
            WHERE id = $claimId AND staff_id = $staffId
        ");
    }

    // Get claim by ID with staff verification
    public function getClaimById($claimId, $staffId) {
        $result = $this->conn->query("
            SELECT * FROM medclaims 
            WHERE id = $claimId AND staff_id = $staffId
        ");
        return $result->fetch_assoc();
    }

    // Get claim without staff verification (for debugging)
    public function getClaimByIdWithoutStaffCheck($claimId) {
        $result = $this->conn->query("
            SELECT * FROM medclaims 
            WHERE id = $claimId
        ");
        return $result->fetch_assoc();
    }

    // Get claim balances
    public function getClaimBalances($staffId) {
        $result = $this->conn->query("
            SELECT 
                SUM(CASE WHEN status = 'approved' THEN total ELSE 0 END) as used_amount,
                SUM(CASE WHEN status = 'pending' THEN total ELSE 0 END) as pending_amount
            FROM medclaims 
            WHERE staff_id = $staffId
        ");
        return $result->fetch_assoc();
    }

    // Get recent approvals
    public function getRecentApprovalsByStaff($staffId, $limit = 20) {
        $result = $this->conn->query("
            SELECT 
                id, date_receipt, total, description, 
                document_name as attachment, status,
                approved_at, rejected_at, reject_reason
            FROM medclaims 
            WHERE status IN ('approved', 'rejected')
            AND staff_id = $staffId
            ORDER BY COALESCE(approved_at, rejected_at) DESC
            LIMIT $limit
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get pending claims (admin view)
    public function getPendingClaims() {
        $result = $this->conn->query("
            SELECT 
                mc.*, 
                s.name as staff_name,
                d.name as department_name
            FROM medclaims mc
            JOIN staff s ON mc.staff_id = s.id
            JOIN departments d ON s.departments_id = d.id
            WHERE mc.status = 'pending'
            ORDER BY mc.created_at DESC
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get staff medical quota
    public function getStaffQuota($staffId) {
        $result = $this->conn->query("
            SELECT additional_quota 
            FROM staff_medical_quota 
            WHERE staff_id = $staffId
        ");
        return $result->fetch_assoc() ?: ['additional_quota' => 0];
    }

    // Helper method to get claims count
    private function getClaimsCount($staffId, $statuses) {
        $escapedStatuses = array_map(function($status) {
            return "'" . $this->conn->real_escape_string($status) . "'";
        }, $statuses);
        $statusList = implode(',', $escapedStatuses);
        
        $result = $this->conn->query("
            SELECT COUNT(*) as total 
            FROM medclaims 
            WHERE staff_id = $staffId 
            AND status IN ($statusList)
        ");
        return $result->fetch_assoc()['total'];
    }

    // Get recent approvals (admin view)
    public function getRecentApprovals($limit = 20) {
        $result = $this->conn->query("
            SELECT 
                mc.id, mc.date_receipt, mc.total, mc.description, 
                mc.document_name as attachment, mc.status,
                mc.approved_at, mc.rejected_at, mc.reject_reason,
                s.name as staff_name,
                d.name as department_name
            FROM medclaims mc
            JOIN staff s ON mc.staff_id = s.id
            JOIN departments d ON s.departments_id = d.id
            WHERE mc.status IN ('approved', 'rejected')
            ORDER BY COALESCE(mc.approved_at, mc.rejected_at) DESC
            LIMIT $limit
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function approveClaim($claimId) {
        return $this->conn->query("
            UPDATE medclaims 
            SET status = 'approved', approved_at = NOW() 
            WHERE id = $claimId
        ");
    }

    public function rejectClaim($claimId, $reason) {
        $escapedReason = $this->conn->real_escape_string($reason);
        return $this->conn->query("
            UPDATE medclaims 
            SET status = 'rejected', reject_reason = '$escapedReason', approved_at = NOW() 
            WHERE id = $claimId
        ");
    }
}