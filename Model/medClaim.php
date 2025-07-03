<?php
require_once '../db.php';

class MedClaim { 
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Get all current claims for a user (pending/approved)
    public function getCurrentClaims($staffId, $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $query = "SELECT * FROM medclaims 
                 WHERE staff_id = $staffId 
                 AND (status IS NULL OR status = 'pending' OR status = 'approved')
                 ORDER BY created_at DESC
                 LIMIT $perPage OFFSET $offset";
        $result = mysqli_query($this->conn, $query);
        
        $claims = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $claims[] = $row;
        }
        
        // Get total count for pagination
        $countQuery = "SELECT COUNT(*) as total FROM medclaims 
                       WHERE staff_id = $staffId 
                       AND (status IS NULL OR status = 'pending' OR status = 'approved')";
        $countResult = mysqli_query($this->conn, $countQuery);
        $total = mysqli_fetch_assoc($countResult)['total'];
        
        return [
            'claims' => $claims,
            'total' => $total
        ];
    }

    // Get historical claims (approved/rejected/completed)
    public function getHistoryClaims($staffId, $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        $query = "SELECT * FROM medclaims 
                 WHERE staff_id = $staffId 
                 AND (status IN ('approved', 'rejected'))
                 ORDER BY created_at DESC 
                 LIMIT $perPage OFFSET $offset";
        $result = mysqli_query($this->conn, $query);
        
        $claims = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $claims[] = $row;
        }
        
        // Get total count for pagination
        $countQuery = "SELECT COUNT(*) as total FROM medclaims 
                       WHERE staff_id = $staffId 
                       AND (status IN ('approved', 'rejected'))";
        $countResult = mysqli_query($this->conn, $countQuery);
        $total = mysqli_fetch_assoc($countResult)['total'];
        
        return [
            'claims' => $claims,
            'total' => $total
        ];
    }

    // Add a new claim - Updated to match HTML form fields
    public function addClaim($staffId, $data) {
        $date_receipt = mysqli_real_escape_string($this->conn, $data['date_receipt']);
        $total = floatval($data['total']);
        $description = mysqli_real_escape_string($this->conn, $data['description']);
        $attachment = mysqli_real_escape_string($this->conn, $data['attachment']);
        $documentSize = isset($data['documentSize']) ? intval($data['documentSize']) : 0;
        
        $query = "INSERT INTO medclaims 
                  (staff_id, date_receipt, description, total, 
                   document_name, document_size, created_at, status) 
                  VALUES 
                  ($staffId, '$date_receipt', '$description', $total, 
                   '$attachment', $documentSize, NOW(), 'pending')";
        
        return mysqli_query($this->conn, $query);
    }

    // Update a claim - matching edit form fields
    public function updateClaim($claimId, $staffId, $data) {
        $date_receipt = mysqli_real_escape_string($this->conn, $data['edit_date_receipt']);
        $total = floatval($data['edit_total']);
        $description = mysqli_real_escape_string($this->conn, $data['edit_description']);
        
        $query = "UPDATE medclaims SET 
                  date_receipt = '$date_receipt',
                  total = $total,
                  description = '$description'";
        
        // Update attachment if provided
        if (!empty($data['edit_attachment'])) {
            $attachment = mysqli_real_escape_string($this->conn, $data['edit_attachment']);
            $query .= ", document_name = '$attachment'";
        }
        
        $query .= " WHERE id = $claimId AND staff_id = $staffId";
        
        return mysqli_query($this->conn, $query);
    }

    // Delete a claim
    public function deleteClaim($claimId, $staffId) {
        $query = "DELETE FROM medclaims WHERE id = $claimId AND staff_id = $staffId";
        return mysqli_query($this->conn, $query);
    }

    // Get single claim by ID
    public function getClaimById($claimId, $staffId) {
        $query = "SELECT * FROM medclaims WHERE id = $claimId AND staff_id = $staffId";
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }

    // Get claim balances
    public function getClaimBalances($staffId) {
        $query = "SELECT 
                  SUM(CASE WHEN status = 'approved' THEN total ELSE 0 END) as used_amount,
                  SUM(CASE WHEN status = 'pending' THEN total ELSE 0 END) as pending_amount,
                  SUM(CASE WHEN status = 'approved' THEN balance_after_approve ELSE 0 END) as balance_after
                  FROM medclaims 
                  WHERE staff_id = $staffId";
        
        $result = mysqli_query($this->conn, $query);
        return mysqli_fetch_assoc($result);
    }

    public function getPendingClaims() {
        $query = "SELECT * FROM medclaims 
                WHERE status = 'pending'
                ORDER BY created_at DESC";
        $result = mysqli_query($this->conn, $query);
        
        $claims = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $claims[] = $row;
        }
        
        return $claims;
    }

    public function approveClaim($claimId) {
        $query = "UPDATE medclaims SET 
                status = 'approved',
                approved_at = NOW(),  -- Add this timestamp
                rejected_at = NULL,   -- Clear rejection timestamp
                updated_at = NOW()
                WHERE id = $claimId";
        return mysqli_query($this->conn, $query);
    }

    public function rejectClaim($claimId, $reason) {
        $reason = mysqli_real_escape_string($this->conn, $reason);
        $query = "UPDATE medclaims SET 
                status = 'rejected',
                rejected_at = NOW(),  -- Add this timestamp
                approved_at = NULL,   -- Clear approval timestamp
                reject_reason = '$reason',
                updated_at = NOW()
                WHERE id = $claimId";
        return mysqli_query($this->conn, $query);
    }
    
    public function getRecentApprovals($limit = 20) {
        $query = "SELECT id, date_receipt, total, description, document_name as attachment, 
                        status, approved_at, rejected_at, reject_reason
                FROM medclaims 
                WHERE status IN ('approved', 'rejected')
                ORDER BY COALESCE(approved_at, rejected_at) DESC
                LIMIT $limit";
        $result = mysqli_query($this->conn, $query);
        
        $claims = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $claims[] = $row;
        }
        
        return $claims;
    }
}
?>