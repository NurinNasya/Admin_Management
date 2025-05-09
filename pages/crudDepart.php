<?php
require_once '../db.php';

// CREATE
// Inside crudDepart.php (where you handle form submissions)
if (isset($_POST['add_department'])) {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $status = $_POST['status'];

    // Check if department name already exists
    $stmt = $conn->prepare("SELECT * FROM department WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Department name already exists
        session_start();
        $_SESSION['error_message'] = "Error: A department with this name already exists.";
        header("Location: ../pages/department.php");
        exit(); // Ensure the rest of the code doesn't execute
    } else {
        // Insert new department into the database
        $stmt = $conn->prepare("INSERT INTO department (code, name, status) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $code, $name, $status);

        if ($stmt->execute()) {
            header("Location: ../pages/department.php?success=1");
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $conn->close();
}

// UPDATE
if (isset($_POST['update_department'])) {
    $id = $_POST['edit_id'];
    $code = $_POST['edit_code'];
    $name = $_POST['edit_name'];
    $status = $_POST['edit_status'];

    $stmt = $conn->prepare("UPDATE department SET code=?, name=?, status=? WHERE id=?");
    $stmt->bind_param("ssii", $code, $name, $status, $id);

    if ($stmt->execute()) {
        header("Location: ../pages/department.php?updated=1");
    } else {
        echo "Error updating department: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    exit();
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];  // Get the ID from the URL

    // Check if ID is valid
    if (is_numeric($id)) {
        // Prepare DELETE query
        $stmt = $conn->prepare("DELETE FROM department WHERE id = ?");
        $stmt->bind_param("i", $id);

        // Execute query
        if ($stmt->execute()) {
            // Redirect back to department page after successful deletion
            header("Location: ../pages/department.php?deleted=1");
        } else {
            // Output error message if deletion fails
            echo "Error deleting department: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Invalid department ID.";
    }
    $conn->close();
    exit();  // Stop further execution
}
?>
