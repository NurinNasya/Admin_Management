<?php
require_once __DIR__ . '/../Model/medClaim.php';

class MedicalClaimController {
    private $medClaim;
    
    public function __construct($conn) {
        $this->medClaim = new MedClaim($conn);
    }

    public function handleRequest($passedStaffId = null) {
        session_start();
        
        // Get staff ID from session or parameter
        $staffId = $passedStaffId ?? ($_SESSION['staff_id'] ?? 1);
        
        // Handle GET requests (API calls)
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['action']) && $_GET['action'] === 'get_claim_details') {
                $this->handleGetClaimDetails($staffId);
                exit; // Add this to prevent further processing
            }
        }
        
        // Handle POST requests (form submissions)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest($staffId);
        }
        
        // Return view data for regular page loads
        return $this->prepareViewData($staffId);
    }

    private function handlePostRequest($staffId) {
        if (!isset($_POST['action'])) {
            return;
        }

        switch ($_POST['action']) {
            case 'add_claim':
                $this->handleAddClaim($staffId);
                break;
            case 'edit_claim':
                $this->handleEditClaim($staffId);
                break;
            case 'delete_claim':
                $this->handleDeleteClaim($staffId);
                break;
        }
    }

    private function handleGetClaimDetails($staffId) {
        if (empty($_GET['claim_id'])) {
            $this->jsonResponse(['error' => 'Claim ID required'], 400);
        }

        $claimId = (int)$_GET['claim_id'];
        $claim = $this->medClaim->getClaimById($claimId, $staffId);
        
        if (!$claim) {
            $this->jsonResponse(['error' => 'Claim not found or not authorized'], 404);
        }

        // Format date for HTML input
        $claim['date_receipt'] = date('Y-m-d', strtotime($claim['date_receipt']));
        
        $this->jsonResponse($claim);
    }

    private function handleAddClaim($staffId) {
        $attachment = '';
        $documentSize = 0;
        
        if (!empty($_FILES['attachment']['name'])) {
            $uploadResult = $this->handleFileUpload('attachment');
            if ($uploadResult) {
                $attachment = $uploadResult['name'];
                $documentSize = $uploadResult['size'];
            }
        }

        $claimData = [
            'date_receipt' => $_POST['date_receipt'],
            'total' => (float)$_POST['total'],
            'description' => $_POST['description'] ?? '',
            'attachment' => $attachment,
            'documentSize' => $documentSize
        ];

        if ($this->medClaim->addClaim($staffId, $claimData)) {
            $this->redirectWithSuccess('medicalClaim.php', 'Claim added successfully');
        }
    }

private function handleEditClaim($staffId) {
    // Get staff_id from POST first, then fall back to passed value
    $effectiveStaffId = $_POST['staff_id'] ?? $staffId;
    
    if (empty($_POST['edit_claim_id'])) {
        $this->jsonResponse(['error' => 'Claim ID required'], 400);
    }

    $claimId = (int)$_POST['edit_claim_id'];
    $existingClaim = $this->medClaim->getClaimById($claimId, $effectiveStaffId);
    
    if (!$existingClaim) {
        $this->jsonResponse(['error' => 'Claim not found or not authorized'], 404);
    }

    // Use existing attachment if no new file uploaded
    $edit_attachment = $existingClaim['document_name'];
    if (!empty($_FILES['edit_attachment']['name'])) {
        $uploadResult = $this->handleFileUpload('edit_attachment');
        if ($uploadResult) {
            $edit_attachment = $uploadResult['name'];
            // Delete old file if it exists
            if (!empty($existingClaim['document_name'])) {
                $oldFile = __DIR__ . '/../uploads/' . $existingClaim['document_name'];
                if (file_exists($oldFile)) {
                    unlink($oldFile);
                }
            }
        }
    }

    $claimData = [
        'edit_date_receipt' => $_POST['edit_date_receipt'],
        'edit_total' => (float)$_POST['edit_total'],
        'edit_description' => $_POST['edit_description'] ?? '',
        'edit_attachment' => $edit_attachment
    ];

     if ($this->medClaim->updateClaim($claimId, $effectiveStaffId, $claimData)) {
        // Always redirect with staff_id
        $redirectUrl = 'medicalClaim.php?staff_id='.$effectiveStaffId.'&updated='.time();
        header("Location: $redirectUrl");
        exit();
    } else {
        $this->jsonResponse(['error' => 'Failed to update claim'], 500);
    }
}

    private function handleDeleteClaim($staffId) {
        if (empty($_POST['claim_id'])) {
            return;
        }

        $claimId = (int)$_POST['claim_id'];
        $existingClaim = $this->medClaim->getClaimById($claimId, $staffId);
        
        if ($existingClaim && $this->medClaim->deleteClaim($claimId, $staffId)) {
            $this->redirectWithSuccess('medicalClaim.php', 'Claim deleted successfully');
        }
    }

    public function prepareViewData($staffId) {
        $currentClaims = $this->medClaim->getCurrentClaims($staffId, 1, 100);
        $claimBalances = $this->medClaim->getClaimBalances($staffId);
        $staffQuota = $this->medClaim->getStaffQuota($staffId);
        $recentApprovals = $this->medClaim->getRecentApprovalsByStaff($staffId, 20);

        // Calculate medical balance
        $baseQuota = 1500;
        $additionalQuota = $staffQuota['additional_quota'] ?? 0;
        $totalEntitled = $baseQuota + $additionalQuota;
        $usedAmount = $claimBalances['used_amount'] ?? 0;
        $pendingAmount = $claimBalances['pending_amount'] ?? 0;

        return [
            'currentClaims' => $currentClaims,
            'claimBalances' => $claimBalances,
            'recentApprovals' => $recentApprovals,
            'medical_balance' => [
                'validity' => '2025-12-31',
                'base' => 'RM ' . number_format($baseQuota, 2),
                'additional' => 'RM ' . number_format($additionalQuota, 2),
                'entitled' => 'RM ' . number_format($totalEntitled, 2),
                'used' => 'RM ' . number_format($usedAmount, 2),
                'current' => 'RM ' . number_format($totalEntitled - $usedAmount, 2),
                'pending' => 'RM ' . number_format($pendingAmount, 2),
                'available' => 'RM ' . number_format($totalEntitled - $usedAmount - $pendingAmount, 2)
            ]
        ];
    }

    public function getClaimForEdit($claimId, $staffId) {
        return $this->medClaim->getClaimById($claimId, $staffId);
    }

    private function handleFileUpload($fieldName) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES[$fieldName]['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $targetPath)) {
            return [
                'name' => $fileName,
                'size' => $_FILES[$fieldName]['size']
            ];
        }
        return null;
    }

    private function redirectWithSuccess($url, $message) {
    $_SESSION['flash_message'] = $message;
    header("Location: $url");
    exit();
}

    private function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}