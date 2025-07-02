<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection and model
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../Model/medClaim.php'; // Load model ONCE here

$medClaim = new MedClaim($conn);

// Get staff ID from session (assuming it's stored in session)
session_start();
$staffId = $_SESSION['staff_id'] ?? 1; // Default to 1 for testing
// In your controller or before this HTML section:
$recentApprovals = $medClaim->getRecentApprovals(); // This should be called

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Handle different form submissions
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        
        switch ($action) {
            case 'add_claim':
                handleAddClaim($medClaim, $staffId);
                break;
            case 'edit_claim':
                handleEditClaim($medClaim, $staffId);
                break;
            case 'delete_claim':
                handleDeleteClaim($medClaim, $staffId);
                break;
        }
    } else {
        // Default action - add claim (for forms without action field)
        handleAddClaim($medClaim, $staffId);
    }
}

function handleAddClaim($medClaim, $staffId) {
    // Get form data matching HTML form field names
    $date_receipt = $_POST['date_receipt'] ?? '';
    $total = $_POST['total'] ?? 0;
    $description = $_POST['description'] ?? '';
    
    // Handle file upload
    $attachment = '';
    $documentSize = 0;
    
    if (!empty($_FILES['attachment']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES['attachment']['name']);
        $targetPath = $uploadDir . $fileName;
        
        // Get file size
        $documentSize = $_FILES['attachment']['size'];

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
            $attachment = $fileName;
        } else {
            die("❌ Failed to upload file.");
        }
    }

    // Prepare data array
    $claimData = [
        'date_receipt' => $date_receipt,
        'total' => $total,
        'description' => $description,
        'attachment' => $attachment,
        'documentSize' => $documentSize
    ];

    // Save to database
    if ($medClaim->addClaim($staffId, $claimData)) {
        header("Location: ../pages/medicalClaim.php?success=1");
        exit;
    } else {
        die("❌ DB Error: Failed to add claim");
    }
}

function handleEditClaim($medClaim, $staffId) {
    $claimId = $_POST['edit_claim_id'] ?? 0;
    
    // Get form data matching edit form field names
    $edit_date_receipt = $_POST['edit_date_receipt'] ?? '';
    $edit_total = $_POST['edit_total'] ?? 0;
    $edit_description = $_POST['edit_description'] ?? '';
    
    // Handle file upload for edit
    $edit_attachment = '';
    if (!empty($_FILES['edit_attachment']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = time() . '_' . basename($_FILES['edit_attachment']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['edit_attachment']['tmp_name'], $targetPath)) {
            $edit_attachment = $fileName;
        } else {
            die("❌ Failed to upload file.");
        }
    }

    // Prepare data array
    $claimData = [
        'edit_date_receipt' => $edit_date_receipt,
        'edit_total' => $edit_total,
        'edit_description' => $edit_description,
        'edit_attachment' => $edit_attachment
    ];

    // Update in database
    if ($medClaim->updateClaim($claimId, $staffId, $claimData)) {
        header("Location: ../pages/medicalClaim.php?updated=1");
        exit;
    } else {
        die("❌ DB Error: Failed to update claim");
    }
}

function handleDeleteClaim($medClaim, $staffId) {
    $claimId = $_POST['claim_id'] ?? 0;
    
    if ($medClaim->deleteClaim($claimId, $staffId)) {
        header("Location: ../pages/medicalClaim.php?deleted=1");
        exit;
    } else {
        die("❌ DB Error: Failed to delete claim");
    }
}

// Handle AJAX requests for getting claim data
if (isset($_GET['get_claim']) && isset($_GET['id'])) {
    $claimId = intval($_GET['id']);
    $claim = $medClaim->getClaimById($claimId, $staffId);
    
    if ($claim) {
        header('Content-Type: application/json');
        echo json_encode($claim);
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Claim not found']);
        exit;
    }
}
?>