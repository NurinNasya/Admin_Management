<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../Model/medClaim.php';

$medClaim = new MedClaim($conn);

// File size formatting helper
function formatFileSize($bytes) {
    if ($bytes == 0) return '0 Bytes';
    $units = ['Bytes', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes, 1024));
    return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
}

// Handle approval/rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $claimId = intval($_POST['claim_id']);
    
    switch ($_POST['action']) {
        case 'approve':
            if ($medClaim->approveClaim($claimId)) {
                header("Location: adminMedClaim.php?approved=1");
                exit;
            }
            break;
            
        case 'reject':
            if (isset($_POST['reject_reason'])) {
                $reason = mysqli_real_escape_string($conn, $_POST['reject_reason']);
                if ($medClaim->rejectClaim($claimId, $reason)) {
                    header("Location: adminMedClaim.php?rejected=1");
                    exit;
                }
            }
            break;
    }
    
    // If we get here, something went wrong
    header("Location: adminMedClaim.php?error=1");
    exit;
}

// Get claims data
$pendingClaims = $medClaim->getPendingClaims();
$recentApprovals = $medClaim->getRecentApprovals(30); // Last 30 days