<?php
include '../db.php';  // Go up one level if db.php is outside 'pages'

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// CREATE SHIFT
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
    $code = $_POST['code'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $work_hour = $_POST['work_hour'];
    $break_hour = $_POST['break_hour'];
    $status = ($_POST['status'] === 'Active') ? 1 : 0;


   // Check for duplicate code
    $checkCode = $conn->prepare("SELECT id FROM shifts WHERE code = ?");
    $checkCode->bind_param("s", $code);
    $checkCode->execute();
    $checkCode->store_result();

    if ($checkCode->num_rows > 0) {
        header("Location: workshift.php?error=code");
        exit();
    }
    $checkCode->close();

    // Check for duplicate start_time
    $checkTime = $conn->prepare("SELECT id FROM shifts WHERE start_time = ?");
    $checkTime->bind_param("s", $start_time);
    $checkTime->execute();
    $checkTime->store_result();

    if ($checkTime->num_rows > 0) {
        header("Location: workshift.php?error=time");
        exit();
    }
    $checkTime->close();

    $stmt = $conn->prepare("INSERT INTO shifts (code, description, start_time, work_hour, break_hour, status) 
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $code, $description, $start_time, $work_hour, $break_hour, $status);

    if ($stmt->execute()) {
        header("Location: workshift.php?success=1");
        exit();
    } else {
        echo "Error inserting shift: " . $stmt->error;
    }
    $stmt->close();
}


    //UPDATE SHIFT
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_shift'])) {
        $id = $_POST['edit_id'];  // Keep the shift's ID hidden
        $description = $_POST['edit_description'];
        $start_time = $_POST['edit_start_time'];
        $work_hour = $_POST['edit_work_hour'];
        $break_hour = $_POST['edit_break_hour'];
        $status = ($_POST['edit_status'] === 'Active') ? 1 : 0;


        $stmt = $conn->prepare("UPDATE shifts SET description=?, start_time=?, work_hour=?, break_hour=?, status=? WHERE id=?");

        if (!$stmt) {
            die("Update prepare failed: " . $conn->error);
        }

        $stmt->bind_param("sssssi", $description, $start_time, $work_hour, $break_hour, $status, $id);

        if ($stmt->execute()) {
            header("Location: workshift.php?updated=1");
            exit();
        } else {
            echo "Error updating shift: " . $stmt->error;
        }

        $stmt->close();
    }


// DELETE SHIFT
if (isset($_POST['delete_shift'])) {
    $delete_code = $_POST['delete_code'] ?? null;

    if ($delete_code !== null) {
        $stmt = $conn->prepare("DELETE FROM shifts WHERE code = ?");
        $stmt->bind_param("s", $delete_code);

        if ($stmt->execute()) {
            header("Location: workshift.php?deleted=1");
            exit();
        } else {
            echo "Error deleting shift: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: Shift code not provided.";
    }
}
?>
