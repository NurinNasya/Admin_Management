<?php
require_once '../db.php';

// CREATE
// Inside crudCompany.php (where you handle form submissions)
if (isset($_POST['add_company'])) {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $status = $_POST['status'];

    // Check if company name already exists
    $stmt = $conn->prepare("SELECT * FROM company WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Company name already exists
        session_start();
        $_SESSION['error_message'] = "Error: A company with this name already exists.";
        header("Location: ../pages/company.php");
        exit(); // Ensure the rest of the code doesn't execute
    } else {
        // Insert new company into the database
        $stmt = $conn->prepare("INSERT INTO company (code, name, status) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $code, $name, $status);

        if ($stmt->execute()) {
            header("Location: ../pages/company.php?success=1");
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $conn->close();
}


// UPDATE
if (isset($_POST['update_company'])) {
    $id = $_POST['edit_id'];
    $code = $_POST['edit_code'];
    $name = $_POST['edit_name'];
    $status = $_POST['edit_status'];

    $stmt = $conn->prepare("UPDATE company SET code=?, name=?, status=? WHERE id=?");
    $stmt->bind_param("ssii", $code, $name, $status, $id);

    if ($stmt->execute()) {
        header("Location: company.php?updated=1");
    } else {
        echo "Error updating company: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
    exit();
}

// DELETE
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    if (is_numeric($id)) {
        $stmt = $conn->prepare("DELETE FROM company WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: company.php?deleted=1");
        } else {
            echo "Error deleting company: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Invalid company ID.";
    }
    $conn->close();
    exit();
}
