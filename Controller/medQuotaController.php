<?php
require_once "../db.php";
require_once "../Model/medQuota.php";

$medQuota = new MedQuota($conn);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
    $action = $_POST["action"];

    // Retrieve fields from POST
    $id = $_POST["id"] ?? null;
    $staff_id = $_POST["staff_id"] ?? '';
    $start_date = $_POST["start_date"] ?? '';
    $end_date = $_POST["end_date"] ?? '';
    $entitled_balance = $_POST["entitled_balance"] ?? '';
    $current_balance = $_POST["current_balance"] ?? '';

    switch ($action) {
        case "add":
            if ($staff_id && $start_date && $end_date && $entitled_balance && $current_balance) {
                $medQuota->insert($staff_id, $start_date, $end_date, $entitled_balance, $current_balance);
            }
            break;

        case "edit":
            if ($id && $staff_id && $start_date && $end_date && $entitled_balance && $current_balance) {
                $medQuota->update($id, $staff_id, $start_date, $end_date, $entitled_balance, $current_balance);
            }
            break;

        case "delete":
            if ($id) {
                $medQuota->delete($id);
            }
            break;
    }

    header("Location: ../pages/med_quota.php");
    exit();
}