<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include __DIR__ . '/../Model/medClaim.php'; // Make sure DB connection is correct

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $receiptDate = $_POST['receiptDate'];
    $amount = $_POST['receiptAmount'];
    $description = $_POST['description'];

    // Handle file upload
    $attachment = '';
    if (!empty($_FILES['attachment']['name'])) {
        $uploadDir = __DIR__ . '/../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES['attachment']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
            $attachment = 'uploads/' . $fileName;
        } else {
            die("❌ Failed to upload file.");
        }
    }

    // Save into DB
    $stmt = $conn->prepare("INSERT INTO medical_claims (start_date, end_date, receipt_date, amount, description, attachment) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiss", $startDate, $endDate, $receiptDate, $amount, $description, $attachment);

    if ($stmt->execute()) {
        header("Location: ../pages/medClaim.php?success=1");
        exit;
    } else {
        die("❌ DB Error: " . $stmt->error);
    }

    $stmt->close();
}
?>

