<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../Model/medClaim.php';

$medClaim = new MedClaim($conn);


// Add this function at the top of your controller file
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
            $medClaim->approveClaim($claimId);
            break;
        case 'reject':
            $reason = mysqli_real_escape_string($conn, $_POST['reject_reason']);
            $medClaim->rejectClaim($claimId, $reason);
            break;
    }
    
    header("Location: adminMedClaim.php?updated=1");
    exit;
}

// Get pending claims
$pendingClaims = $medClaim->getPendingClaims();

// Get recent approvals/rejections (last 30 days)
$recentApprovals = $medClaim->getRecentApprovals();
?>