<?php
require_once '../db.php';
require_once '../Model/LeaveForm.php';

$leaveForm = new LeaveForm($conn);
$action = $_GET['action'] ?? '';

function handleFileUpload($file) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $uploadDir = '../uploads/leave_documents/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedTypes)) {
        return null;
    }

    $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
    $uploadPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return $fileName;
    }

    return null;
}

switch ($action) {
    case 'add':
        $documentName = null;
        if (isset($_FILES['leave_document']) && $_FILES['leave_document']['error'] === UPLOAD_ERR_OK) {
            $documentName = handleFileUpload($_FILES['leave_document']);
            if ($documentName === null && $_POST['leave_type'] === 'Medical Leave') {
                header("Location: ../pages/leaveForm.php?error=Invalid+file+type+for+medical+certificate");
                exit;
            }
        }

        $data = array(
            'staff_id' => $_POST['staff_id'],
            'leave_type' => $_POST['leave_type'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'total_days' => $_POST['total_days'],
            'application_date' => $_POST['application_date'],
            'reason' => $_POST['reason'],
            'leave_document' => $documentName,
            'created_by' => $_POST['created_by']
        );

        $result = $leaveForm->addLeave($data);

        if ($result) {
            header("Location: ../pages/leaveForm.php?msg=Leave+Application+Submitted+Successfully");
        } else {
            header("Location: ../pages/leaveForm.php?error=Failed+to+Submit+Leave+Application");
        }
        break;

    case 'edit':
        $id = $_POST['id'];
        $documentName = isset($_POST['existing_document']) ? $_POST['existing_document'] : null;
        
        if (isset($_FILES['leave_document']) && $_FILES['leave_document']['error'] === UPLOAD_ERR_OK) {
            $newDocument = handleFileUpload($_FILES['leave_document']);
            if ($newDocument !== null) {
                $documentName = $newDocument;
            }
        }

        $data = array(
            'leave_type' => $_POST['leave_type'],
            'start_date' => $_POST['start_date'],
            'end_date' => $_POST['end_date'],
            'total_days' => $_POST['total_days'],
            'application_date' => $_POST['application_date'],
            'reason' => $_POST['reason'],
            'leave_document' => $documentName
        );

        $result = $leaveForm->updateLeave($id, $data);

        if ($result) {
            header("Location: ../pages/leaveForm.php?msg=Leave+Application+Updated+Successfully");
        } else {
            header("Location: ../pages/leaveForm.php?error=Failed+to+Update+Leave+Application");
        }
        break;

    case 'delete':
        $id = $_GET['id'];
        $result = $leaveForm->deleteLeave($id);

        if ($result) {
            header("Location: ../pages/leaveForm.php?msg=Leave+Application+Deleted+Successfully");
        } else {
            header("Location: ../pages/leaveForm.php?error=Failed+to+Delete+Leave+Application");
        }
        break;

    case 'updateStatus':
        $id = $_POST['id'];
        $status = $_POST['status'];
        $result = $leaveForm->updateStatus($id, $status);

        if ($result) {
            header("Location: ../pages/leaveForm.php?msg=Status+Updated+Successfully");
        } else {
            header("Location: ../pages/leaveForm.php?error=Failed+to+Update+Status");
        }
        break;


        case 'getLeave':
        $id = $_GET['id'];
        $leave = $leaveForm->getLeaveById($id);
        header('Content-Type: application/json');
        echo json_encode($leave);
        exit;  // edit function


    default:
        header("Location: ../pages/leaveForm.php");
        break;
}

$conn->close();
?>