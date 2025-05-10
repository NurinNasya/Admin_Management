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
    $status = $_POST['status'];

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
//CREATE
/*if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $code = $_POST['code'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $work_hour = $_POST['work_hour'];
    $break_hour = $_POST['break_hour'];
    $status = $_POST['status'];

    // Prepare the SQL query with placeholders (no 'end_time' in the query)
    $stmt = $conn->prepare("INSERT INTO shifts (code, description, start_time, work_hour, break_hour, status) 
                            VALUES (?, ?, ?, ?, ?, ?)");

    // Bind the parameters to the placeholders
    // 'sssssi' means: 
    // 4 strings (code, description, start_time, status) 
    // 2 integers (work_hour, break_hour)
    $stmt->bind_param("sssssi", $code, $description, $start_time, $work_hour, $break_hour, $status);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: workshift.php"); // Redirect to the main page after successful insertion
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}*/

// DELETE
// Deletion logic
//if (isset($_POST['delete_shift'])) {
    // Ensure we have the 'delete_code' from the form
    //$deleteCode = $_POST['delete_code'];

    // Safety check
    /*if (!empty($deleteCode)) {
        // Prepare the delete query
        $stmt = $conn->prepare("DELETE FROM shifts WHERE code = ?");
        $stmt->bind_param("s", $deleteCode);

        if ($stmt->execute()) {
            // Redirect after successful deletion
            header("Location: workshift.php?deleted=1");
            exit();
        } else {
            echo "Error deleting shift: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid shift code.";
    }*/


/*if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    if (is_numeric($id)) {
        $stmt = $conn->prepare("DELETE FROM shifts WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: workshift.php?deleted=1");
        } else {
            echo "Error deleting company: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Invalid shift code.";
    }
    $conn->close();
    exit();
}*/

/*if (isset($_POST['delete_shift'])) {
    $delete_code = $_POST['delete_code'] ?? null;

    if ($delete_code !== null) {
        $stmt = $conn->prepare("DELETE FROM shifts WHERE code = ?");
        $stmt->bind_param("s", $delete_code);
        $stmt->execute();
        $stmt->close();
        header("Location: workshift.php?deleted=1");
        exit();
    } else {
        echo "Error: shift code not provided.";
    }
}*/
?>
