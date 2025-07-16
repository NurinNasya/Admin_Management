<?php
namespace Controller;

use Exception;

class LeaveFormController
{
    private $conn;
    private $message;

    public function __construct($connection, array $request = [], array $server = [], array $files = [], array &$session = [])
    {
        $this->conn = $connection;
        $this->message = null;
        $this->handleActions($request, $server, $files, $session);
    }

    public function handleActions(array $request, array $server, array $files, array &$session): void
    {
        $action = $request['action'] ?? null;
        $id = isset($request['id']) ? (int) $request['id'] : null;

        switch ($action) {
            case 'view_attachment':
                $this->viewAttachment($id);
                break;
            case 'view_info':
                $this->viewInfo($id, $session);
                break;
            case 'delete_application':
                $this->deleteApplication($id);
                header('Location: ../pages/leaveForm.php?view=dashboard');
                break;
            case 'update_application':
                $this->handleUpdateApplication($server, $request, $files, $session);
                break;
            case 'approve_application':
                $this->approveApplication($id);
                header('Location: ../pages/leaveForm.php?view=dashboard');
                break;
            case 'reject_application':
                $this->rejectApplication($id);
                header('Location: ../pages/leaveForm.php?view=dashboard');
                break;
        }
    }

public function saveLeaveApplication(array $data): int|false
    {
        mysqli_begin_transaction($this->conn);

        try {
            $employeeId = (int) $data['employee_id'];
            $totalDays = (float) $data['total_days'];
            $leaveType = $this->escape($data['leave_type']);

            // Validate leave type exists and is active
            $leaveTypeData = $this->getLeaveTypeData($leaveType);
            if (!$leaveTypeData) {
                throw new Exception("Jenis cuti '$leaveType' tidak wujud atau tidak aktif");
            }

            // Check if employee has enough leave balance for this leave type
            $usedDays = $this->getEmployeeUsedDays($employeeId, $leaveType);
            $pendingDays = $this->getEmployeePendingDays($employeeId, $leaveType);
            $availableDays = $leaveTypeData['max_days'] - $usedDays - $pendingDays;

            if ($availableDays < $totalDays) {
                throw new Exception("Baki cuti tidak mencukupi. Baki tersedia: " . $availableDays . " hari");
            }

            $applicationDate = date('Y-m-d');
            $startDate = $this->escape($data['start_date']);
            $endDate = $this->escape($data['end_date']);
            $reason = $this->escape($data['reason']);
            $attachment = isset($data['attachment']) ? $this->escape($data['attachment']) : null;

            $query = "INSERT INTO leave_applications 
                     (employee_id, application_date, start_date, end_date, total_days, leave_type, reason, status, attachment) 
                     VALUES 
                     ($employeeId, '$applicationDate', '$startDate', '$endDate', $totalDays, '$leaveType', '$reason', 'pending', " .
                ($attachment ? "'$attachment'" : "NULL") . ")";

            if (!mysqli_query($this->conn, $query)) {
                throw new Exception("Gagal menyimpan permohonan cuti: " . mysqli_error($this->conn));
            }

            $applicationId = mysqli_insert_id($this->conn);

            mysqli_commit($this->conn);
            return $applicationId;
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            error_log("[SaveLeave] " . $e->getMessage());
            throw $e;
        }
    }
     public function updateLeaveApplication(int $id, array $data): bool
    {
        mysqli_begin_transaction($this->conn);

        try {
            $startDate = $this->escape($data['start_date']);
            $endDate = $this->escape($data['end_date']);
            $totalDays = (float) $data['total_days'];
            $leaveType = $this->escape($data['leave_type']);
            $reason = $this->escape($data['reason']);

            // Validate leave type
            $leaveTypeData = $this->getLeaveTypeData($leaveType);
            if (!$leaveTypeData) {
                throw new Exception("Jenis cuti '$leaveType' tidak wujud atau tidak aktif");
            }

            // Dapatkan employee_id permohonan cuti ini
            $result = mysqli_query($this->conn, "SELECT employee_id FROM leave_applications WHERE id = $id");
            if (!$result || mysqli_num_rows($result) === 0) {
                throw new Exception("Permohonan cuti tidak dijumpai untuk dikemaskini");
            }
            $row = mysqli_fetch_assoc($result);
            $employeeId = (int) $row['employee_id'];

            // Semak baki cuti (exclude current application)
            $usedDays = $this->getEmployeeUsedDays($employeeId, $leaveType, $id);
            $pendingDays = $this->getEmployeePendingDays($employeeId, $leaveType);
            $availableDays = $leaveTypeData['max_days'] - $usedDays - $pendingDays;

            if ($availableDays < $totalDays) {
                throw new Exception("Baki cuti tidak mencukupi untuk kemaskini. Baki tersedia: $availableDays hari");
            }

            // Update permohonan
            $query = "UPDATE leave_applications SET 
                start_date = '$startDate', 
                end_date = '$endDate', 
                total_days = $totalDays, 
                leave_type = '$leaveType', 
                reason = '$reason'
                WHERE id = $id";

            $result = mysqli_query($this->conn, $query);
            if (!$result) {
                throw new Exception("Gagal mengemaskini permohonan: " . mysqli_error($this->conn));
            }

            mysqli_commit($this->conn);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            error_log("[UpdateLeave] " . $e->getMessage());
            throw $e;
        }
    }
    public function approveApplication(int $application_id): bool
    {
        mysqli_begin_transaction($this->conn);

        try {
            $app = $this->getLeaveApplication($application_id);
            if (!$app) {
                throw new Exception("Permohonan tidak dijumpai");
            }

            $sql = "UPDATE leave_applications 
                    SET status = 'approved', approved_at = NOW() 
                    WHERE id = $application_id";

            if (!mysqli_query($this->conn, $sql)) {
                throw new Exception("Gagal update status permohonan: " . mysqli_error($this->conn));
            }

            mysqli_commit($this->conn);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            error_log("[ApproveError] " . $e->getMessage());
            return false;
        }
    }

    public function rejectApplication(int $application_id): bool
    {
        mysqli_begin_transaction($this->conn);

        try {
            $app = $this->getLeaveApplication($application_id);
            if (!$app) {
                throw new Exception("Permohonan tidak dijumpai");
            }

            $sql = "UPDATE leave_applications SET status = 'rejected' WHERE id = $application_id";
            if (!mysqli_query($this->conn, $sql)) {
                throw new Exception("Gagal update status permohonan: " . mysqli_error($this->conn));
            }

            mysqli_commit($this->conn);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            error_log($e->getMessage());
            return false;
        }
    }

    public function deleteApplication(int $application_id): bool
    {
        mysqli_begin_transaction($this->conn);

        try {
            $app = $this->getLeaveApplication($application_id);
            if (!$app) {
                throw new Exception("Permohonan tidak dijumpai");
            }

            $sql = "DELETE FROM leave_applications WHERE id = $application_id";
            if (!mysqli_query($this->conn, $sql)) {
                throw new Exception("Gagal hapus permohonan: " . mysqli_error($this->conn));
            }

            mysqli_commit($this->conn);

            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Permohonan cuti berjaya dipadam'
                ]);
                exit;
            }

            return true;

        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            error_log($e->getMessage());

            if ($this->isAjaxRequest()) {
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
                exit;
            }

            return false;
        }
    }










    private function isAjaxRequest(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function getLeaveApplication(int $id): array|false
    {
        $query = "SELECT la.*, 
                 lt.type_name,
                 lt.description,
                 lt.max_days,
                 la.supported_at,
                 la.approved_at, 
                 la.hr_checked_at,
                 la.attachment
                 FROM leave_applications la
                 LEFT JOIN leave_types lt ON la.leave_type = lt.type_name
                 WHERE la.id = $id";
        $result = mysqli_query($this->conn, $query);
        return ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : false;
    }

    public function getEmployeeData(): array
    {
        $query = "SELECT * FROM employees LIMIT 1";
        $result = mysqli_query($this->conn, $query);
        return $result ? mysqli_fetch_assoc($result) : [];
    }

    /**
     * Get all active leave types from database
     * Returns array with numerical indices
     */
    public function getLeaveTypes()
    {
        $leave_types = [];

        $query = "SELECT * FROM leave_types";
        $result = mysqli_query($this->conn, $query);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $leave_types[$row['type_name']] = $row;  // Gunakan type_name sebagai key
            }
        }

        return $leave_types;
    }

    /**
     * Get leave types formatted for view (associative array with type_name as key)
     * This method returns data in the format expected by your view
     */
    public function getLeaveTypesForView(): array
    {
        $query = "SELECT * FROM leave_types WHERE is_active = 1 ORDER BY type_name";
        $result = mysqli_query($this->conn, $query);
        $types = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $types[$row['type_name']] = $row; // Use type_name as key
            }
        }
        return $types;
    }

    /**
     * Get leave types formatted for dropdown/select options
     */
    public function getLeaveTypesForDropdown(): array
    {
        $types = $this->getLeaveTypes();
        $options = [];
        foreach ($types as $type) {
            $options[] = [
                'value' => $type['type_name'],
                'text' => $type['type_name'],
                'description' => $type['description'] ?? '',
                'max_days' => (int) $type['max_days'],
                'is_active' => (int) $type['is_active']
            ];
        }
        return $options;
    }

    /**
     * Prepare data for leave form view
     * This method consolidates all data needed for the form
     */
    public function prepareLeaveFormData(int $applicationId = null): array
    {
        $data = [];

        // Get leave types for radio buttons (formatted for view)
        $data['leave_types'] = $this->getLeaveTypesForView();

        // Get employee data
        $data['employee'] = $this->getEmployeeData();

        // If editing, get application data
        if ($applicationId) {
            $data['application'] = $this->getLeaveApplication($applicationId);
        } else {
            $data['application'] = null;
        }

        return $data;
    }

    /**
     * Get specific leave type data
     */
    private function getLeaveTypeData(string $leaveType): array|false
    {
        $leaveType = $this->escape($leaveType);
        $query = "SELECT * FROM leave_types WHERE type_name = '$leaveType' AND is_active = 1";
        $result = mysqli_query($this->conn, $query);
        return ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : false;
    }

    /**
     * Get employee's used days for a specific leave type
     */
    private function getEmployeeUsedDays(int $employeeId, string $leaveType, int $excludeApplicationId = null): float
    {
        $leaveType = $this->escape($leaveType);
        $excludeClause = $excludeApplicationId ? "AND id != $excludeApplicationId" : "";
        
        $query = "SELECT COALESCE(SUM(total_days), 0) as used_days 
                 FROM leave_applications 
                 WHERE employee_id = $employeeId 
                 AND leave_type = '$leaveType' 
                 AND status = 'approved'
                 AND YEAR(start_date) = YEAR(CURDATE())
                 $excludeClause";

        $result = mysqli_query($this->conn, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return (float) $row['used_days'];
        }
        return 0.0;
    }

    /**
     * Get leave balance summary for an employee
     */
    public function getLeaveBalanceSummary($employee_id): array
    {
        $current_year = date('Y');
        $summary = [];

        // Get all active leave types
        $leave_types = $this->getLeaveTypes();

        foreach ($leave_types as $type) {
            // Skip if type_name is not set
            if (empty($type['type_name'])) {
                continue;
            }

            // Calculate used days
            $used = $this->calculateUsedDays($employee_id, $type['type_name'], $current_year);

            // Calculate pending days
            $pending = $this->calculatePendingDays($employee_id, $type['type_name']);

            $summary[] = [
                'type_name' => $type['type_name'],
                'max_days' => (float) $type['max_days'],
                'used_days' => (float) $used,
                'pending_days' => (float) $pending,
                'available_days' => (float) ($type['max_days'] - $used - $pending)
            ];
        }

        return $summary;
    }

    //1-penambahan baru 
    public function getLeaveBalance(int $employeeId): array
    {
        $balance = [];
        $leaveTypes = $this->getLeaveTypesForView();
        
        foreach ($leaveTypes as $typeName => $typeData) {
            $usedDays = $this->getEmployeeUsedDays($employeeId, $typeName);
            $pendingDays = $this->getEmployeePendingDays($employeeId, $typeName);
            $availableDays = $typeData['max_days'] - $usedDays - $pendingDays;
            
            $balance[$typeName] = [
                'max_days' => (float) $typeData['max_days'],
                'used' => $usedDays,
                'pending' => $pendingDays,
                'available' => max(0, $availableDays) // Pastikan tidak negatif
            ];
        }
        
        return $balance;
    }

    //2-penamabahan yang baru

    private function getEmployeePendingDays(int $employeeId, string $leaveType): float
    {
        $leaveType = $this->escape($leaveType);
        $query = "SELECT COALESCE(SUM(total_days), 0) as pending_days 
                 FROM leave_applications 
                 WHERE employee_id = $employeeId 
                 AND leave_type = '$leaveType' 
                 AND status = 'pending'
                 AND YEAR(start_date) = YEAR(CURDATE())";

        $result = mysqli_query($this->conn, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return (float) $row['pending_days'];
        }
        return 0.0;
    }












    private function calculateUsedDays($employee_id, $leave_type_name, $year): float
    {
        $leave_type_name = $this->escape($leave_type_name);
        $sql = "SELECT COALESCE(SUM(total_days), 0) AS total 
                FROM leave_applications 
                WHERE employee_id = $employee_id 
                AND leave_type = '$leave_type_name' 
                AND status = 'approved'
                AND YEAR(start_date) = $year";

        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return (float) $row['total'];
        }
        return 0.0;
    }

    private function calculatePendingDays($employee_id, $leave_type_name): float
    {
        $leave_type_name = $this->escape($leave_type_name);
        $sql = "SELECT COALESCE(SUM(total_days), 0) AS total 
                FROM leave_applications 
                WHERE employee_id = $employee_id 
                AND leave_type = '$leave_type_name' 
                AND status = 'pending'";

        $result = mysqli_query($this->conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return (float) $row['total'];
        }
        return 0.0;
    }

    // In LeaveFormController.php

/**
 * Get active leave types for dropdown/select
 */
public function getActiveLeaveTypes(): array
{
    $types = [];
    $query = "SELECT type_name, max_days FROM leave_types WHERE is_active = 1 ORDER BY type_name";
    $result = mysqli_query($this->conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $types[$row['type_name']] = $row;
        }
    }
    return $types;
}


/**
 * Validate if a leave type exists and is active
 */
public function isValidLeaveType(string $leaveType): bool
{
    $leaveType = $this->escape($leaveType);
    $query = "SELECT 1 FROM leave_types WHERE type_name = '$leaveType' AND is_active = 1";
    $result = mysqli_query($this->conn, $query);
    return ($result && mysqli_num_rows($result) > 0);
}







    

    /**
     * Get employee's pending days for a specific leave type
     */
    /*private function getEmployeePendingDays(int $employeeId, string $leaveType): float
    {
        $leaveType = $this->escape($leaveType);
        $query = "SELECT COALESCE(SUM(total_days), 0) as pending_days 
                 FROM leave_applications 
                 WHERE employee_id = $employeeId 
                 AND leave_type = '$leaveType' 
                 AND status = 'pending'
                 AND YEAR(start_date) = YEAR(CURDATE())";

        $result = mysqli_query($this->conn, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return (float) $row['pending_days'];
        }
        return 0.0;
    }     */

    /**
     * Get leave applications with proper type details from database
     */
    public function getLeaveApplicationsWithTypes(int $employeeId): array
    {
        $query = "SELECT la.*, 
                        lt.type_name,
                        lt.description,
                        lt.max_days,
                        lt.is_active
                 FROM leave_applications la 
                 LEFT JOIN leave_types lt ON la.leave_type = lt.type_name 
                 WHERE la.employee_id = $employeeId 
                 ORDER BY la.application_date DESC";

        $result = mysqli_query($this->conn, $query);
        $applications = [];

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $row['start_date_formatted'] = date('d/m/Y', strtotime($row['start_date']));
                $row['end_date_formatted'] = date('d/m/Y', strtotime($row['end_date']));
                $row['application_date_formatted'] = date('d/m/Y', strtotime($row['application_date']));
                $row['leave_type_display'] = $row['type_name'] ?? $row['leave_type'];
                $applications[] = $row;
            }
        }

        return $applications;
    }

    /**
     * Generate radio buttons HTML for leave types
     * Updated to use the correct data format
     */
    public function generateLeaveTypesRadioButtons(string $selectedValue = ''): string
    {
        $leaveTypes = $this->getLeaveTypesForView(); // Use the view-formatted data
        $html = '';

        foreach ($leaveTypes as $type_name => $type_data) {
            $checked = ($selectedValue === $type_name) ? 'checked' : '';
            $html .= '<label class="leave-type-radio-item">';
            $html .= '<input type="radio" name="leave_type" value="' . htmlspecialchars($type_name) . '" ' . $checked . '>';
            $html .= '<span class="leave-type-name">' . htmlspecialchars($type_name) . '</span>';
            $html .= '<span class="leave-type-desc">' . htmlspecialchars($type_data['description'] ?? '') . '</span>';
            $html .= '<span class="leave-type-max">Max: ' . $type_data['max_days'] . ' hari</span>';
            $html .= '</label>';
        }

        return $html;
    }

    /**
     * Debug method to check leave types data
     * Remove this after fixing the issue
     */
    public function debugLeaveTypes(): void
    {
        echo "<h3>Debug Leave Types Data:</h3>";
        echo "<pre>";
        echo "Raw leave types from database:\n";
        print_r($this->getLeaveTypes());
        echo "\nFormatted for view:\n";
        print_r($this->getLeaveTypesForView());
        echo "\nPrepared form data:\n";
        print_r($this->prepareLeaveFormData());
        echo "</pre>";
    }

    public function getMessage(): ?array
    {
        return $this->message;
    }

    public function setMessage(string $type, string $text): void
    {
        $this->message = ['type' => $type, 'text' => $text];
    }

    private function viewAttachment(int $applicationId): void
    {
        $query = "SELECT attachment FROM leave_applications WHERE id = $applicationId";
        $result = mysqli_query($this->conn, $query);

        if (!$result || mysqli_num_rows($result) === 0) {
            throw new Exception('Lampiran tidak dijumpai');
        }

        $row = mysqli_fetch_assoc($result);
        if (empty($row['attachment'])) {
            throw new Exception('Lampiran kosong');
        }

        $filePath = "../uploads/" . $row['attachment'];
        if (!file_exists($filePath)) {
            throw new Exception('Fail tidak dijumpai');
        }

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($filePath) . '"');
        readfile($filePath);
    }

    private function viewInfo(int $applicationId, array &$session): void
    {
        $application = $this->getLeaveApplication($applicationId);

        if (!$application) {
            header('HTTP/1.1 404 Not Found');
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Application not found']);
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'id' => $application['id'],
            'application_date' => date('d/m/Y', strtotime($application['application_date'])),
            'leave_type' => $application['type_name'] ?? $application['leave_type'],
            'start_date' => date('d/m/Y', strtotime($application['start_date'])),
            'end_date' => date('d/m/Y', strtotime($application['end_date'])),
            'total_days' => $application['total_days'],
            'status' => $application['status'],
            'reason' => $application['reason'],
            'attachment' => $application['attachment'] ?? null,
            'supported_at' => $application['supported_at'] ? date('d/m/Y H:i', strtotime($application['supported_at'])) : null,
            'approved_at' => $application['approved_at'] ? date('d/m/Y H:i', strtotime($application['approved_at'])) : null,
            'hr_checked_at' => $application['hr_checked_at'] ? date('d/m/Y H:i', strtotime($application['hr_checked_at'])) : null
        ]);
        exit;
    }

    private function handleUpdateApplication(array $server, array $request, array $files, array &$session): void
    {
        if (($server['REQUEST_METHOD'] ?? '') !== 'POST') {
            return;
        }

        $applicationId = (int) ($request['id'] ?? 0);
        if (!$applicationId) {
            throw new Exception('ID tidak sah');
        }

        $data = [
            'start_date' => $request['start_date'] ?? '',
            'end_date' => $request['end_date'] ?? '',
            'total_days' => (float) ($request['total_days'] ?? 0),
            'leave_type' => $request['leave_type'] ?? '',
            'reason' => $request['reason'] ?? ''
        ];

        $uploadResult = $this->handleFileUpload($applicationId, $files);
        if ($uploadResult !== true) {
            $this->setMessage('error', $uploadResult);
            header('Location: ../pages/leaveForm.php?view=edit_application&id=' . $applicationId);
            return;
        }

        if ($this->updateLeaveApplication($applicationId, $data)) {
            $this->setMessage('success', 'Permohonan cuti berjaya dikemaskini');
        } else {
            $this->setMessage('error', 'Gagal mengemaskini permohonan');
        }

        header('Location: ../pages/leaveForm.php?view=dashboard');
    }

    private function handleFileUpload(int $applicationId, array $files): bool|string
    {
        if (empty($files['attachment']['name'])) {
            return true;
        }

        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = basename($files["attachment"]["name"]);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedTypes = ['pdf', 'png', 'jpg', 'jpeg', 'gif', 'heic'];

        if (!in_array($fileType, $allowedTypes, true)) {
            return "Hanya fail PDF, PNG, JPG, GIF atau HEIC dibenarkan";
        }

        if ($files["attachment"]["size"] > 5000000) {
            return "Saiz fail terlalu besar. Maksimum 5MB dibenarkan";
        }

        $newFileName = "leave_{$applicationId}_" . time() . "." . $fileType;
        $targetFile = $targetDir . $newFileName;

        if (!move_uploaded_file($files["attachment"]["tmp_name"], $targetFile)) {
            return "Gagal memuat naik fail";
        }

        $query = "UPDATE leave_applications SET attachment = '$newFileName' WHERE id = $applicationId";
        return mysqli_query($this->conn, $query) ?: "Gagal menyimpan maklumat lampiran: " . mysqli_error($this->conn);
    }












    private function escape(?string $value): string
    {
        if ($value === null) {
            return '';
        }
        return mysqli_real_escape_string($this->conn, trim($value));
    }
}