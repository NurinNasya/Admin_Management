<?php
// Include the Shift model and DB connection
include_once '../Model/Shift.php';
include_once '../db.php';

// Create an instance of the Shift model
$shiftModel = new Shift($conn);
$shifts = $shiftModel->getAllShifts();

// Handle the Create shift request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
    $code = $_POST['code'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $work_hour = $_POST['work_hour'];
    $break_hour = $_POST['break_hour'];
    $status = ($_POST['status'] === 'Active') ? 'Active' : 'Inactive';

    // Check for duplicate code
    if ($shiftModel->checkDuplicateCode($code)) {
        header("Location: workshift.php?error=code");
        exit();
    }

    // Check for duplicate start time
    if ($shiftModel->checkDuplicateStartTime($start_time)) {
        header("Location: workshift.php?error=time");
        exit();
    }

    // Create the shift
    if ($shiftModel->createShift($code, $description, $start_time, $work_hour, $break_hour, $status)) {
        header("Location: workshift.php?success=1");
        exit();
    } else {
        echo "Error inserting shift";
    }
}

// Handle the Update shift request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_shift'])) {
    $id = $_POST['edit_id'];
    $description = $_POST['edit_description'];
    $start_time = $_POST['edit_start_time'];
    $work_hour = $_POST['edit_work_hour'];
    $break_hour = $_POST['edit_break_hour'];
    $status = ($_POST['edit_status'] === 'Active') ? 'Active' : 'Inactive';

    // Update the shift
    if ($shiftModel->updateShift($id, $description, $start_time, $work_hour, $break_hour, $status)) {
        header("Location: workshift.php?updated=1");
        exit();
    } else {
        echo "Error updating shift";
    }
}

// Handle the Delete shift request
if (isset($_POST['delete_shift'])) {
    $delete_code = $_POST['delete_code'] ?? null;

    if ($delete_code !== null) {
        // Delete the shift
        if ($shiftModel->deleteShift($delete_code)) {
            header("Location: workshift.php?deleted=1");
            exit();
        } else {
            echo "Error deleting shift";
        }
    } else {
        echo "Error: Shift code not provided.";
    }
}
?>
