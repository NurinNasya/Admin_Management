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

            if ($leaveType !== 'Unpaid Leave') {
                $this->ensureLeaveBalanceExists($employeeId, $leaveType);
                
                $balanceCheck = "SELECT (quota - used - in_process) AS available 
                                FROM leave_balances 
                                WHERE employee_id = $employeeId 
                                AND leave_type = '$leaveType'";
                $result = mysqli_query($this->conn, $balanceCheck);
                
                if (!$result) {
                    throw new Exception("Gagal mendapatkan maklumat baki cuti: " . mysqli_error($this->conn));
                }
                
                $balance = mysqli_fetch_assoc($result);
                
                if (!$balance) {
                    throw new Exception("Gagal membuat rekod baki cuti untuk jenis cuti ini");
                }
                
                if ($balance['available'] < $totalDays) {
                    throw new Exception("Baki cuti tidak mencukupi. Baki tersedia: " . $balance['available'] . " hari");
                }
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

            if ($leaveType !== 'Unpaid Leave') {
                $update_sql = "UPDATE leave_balances 
                              SET 
                                  in_process = in_process + $totalDays,
                                  available_balance = (quota - used - (in_process + $totalDays))
                              WHERE employee_id = $employeeId 
                              AND leave_type = '$leaveType'";

                if (!mysqli_query($this->conn, $update_sql)) {
                    throw new Exception("Gagal mengemaskini baki cuti: " . mysqli_error($this->conn));
                }
            }

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
        $startDate = $this->escape($data['start_date']);
        $endDate = $this->escape($data['end_date']);
        $totalDays = (float) $data['total_days'];
        $leaveType = $this->escape($data['leave_type']);
        $reason = $this->escape($data['reason']);

        $query = "UPDATE leave_applications SET 
            start_date = '$startDate', 
            end_date = '$endDate', 
            total_days = $totalDays, 
            leave_type = '$leaveType', 
            reason = '$reason'
            WHERE id = $id";

        $result = mysqli_query($this->conn, $query);
        if (!$result) {
            error_log("Update failed: " . mysqli_error($this->conn));
        }
        return $result;
    }

    public function approveApplication(int $application_id): bool
    {
        mysqli_begin_transaction($this->conn);

        try {
            $app = $this->getLeaveApplication($application_id);
            if (!$app) throw new Exception("Permohonan tidak dijumpai");

            if ($app['leave_type'] !== 'Unpaid Leave') {
                $sql = "UPDATE leave_balances 
                        SET 
                            in_process = in_process - {$app['total_days']},
                            used = used + {$app['total_days']},
                            available_balance = (quota - (used + {$app['total_days']}) - (in_process - {$app['total_days']}))
                        WHERE employee_id = {$app['employee_id']} 
                        AND leave_type = '{$app['leave_type']}'";
                
                if (!mysqli_query($this->conn, $sql)) {
                    throw new Exception("Gagal update baki cuti: " . mysqli_error($this->conn));
                }
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
            if (!$app) throw new Exception("Permohonan tidak dijumpai");

            if ($app['leave_type'] !== 'Unpaid Leave') {
                $sql = "UPDATE leave_balances 
                        SET in_process = in_process - {$app['total_days']} 
                        WHERE employee_id = {$app['employee_id']} AND leave_type = '{$app['leave_type']}'";
                if (!mysqli_query($this->conn, $sql)) {
                    throw new Exception("Gagal update baki cuti: " . mysqli_error($this->conn));
                }
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
        // Dapatkan maklumat permohonan
        $app = $this->getLeaveApplication($application_id);
        if (!$app) {
            throw new Exception("Permohonan tidak dijumpai");
        }

        // Jika status pending dan bukan Unpaid Leave, update baki cuti
        if ($app['status'] === 'pending' && $app['leave_type'] !== 'Unpaid Leave') {
            $sql = "UPDATE leave_balances 
                    SET in_process = in_process - {$app['total_days']},
                        available_balance = available_balance + {$app['total_days']}
                    WHERE employee_id = {$app['employee_id']} 
                    AND leave_type = '{$app['leave_type']}'";
            
            if (!mysqli_query($this->conn, $sql)) {
                throw new Exception("Gagal update baki cuti: " . mysqli_error($this->conn));
            }
        }

        // Delete permohonan dari database
        $sql = "DELETE FROM leave_applications WHERE id = $application_id";
        if (!mysqli_query($this->conn, $sql)) {
            throw new Exception("Gagal hapus permohonan: " . mysqli_error($this->conn));
        }

        mysqli_commit($this->conn);
        
        // Bahagian baru untuk return JSON response
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
        
        // Bahagian baru untuk return error JSON
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

// Tambahkan fungsi baru ini dalam class untuk check AJAX request
private function isAjaxRequest(): bool
{
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}
    public function getLeaveApplication(int $id): array|false
    {
        $query = "SELECT la.*, 
                 lt.type_name AS type_name,
                 la.supported_at,
                 la.approved_at, 
                 la.hr_checked_at,
                 la.attachment
                 FROM leave_applications la
                 LEFT JOIN leave_types lt ON la.leave_type = lt.id
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

    public function getLeaveTypes(): array
    {
        $query = "SELECT * FROM leave_types";
        $result = mysqli_query($this->conn, $query);
        $types = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $types[] = $row;
            }
        }
        return $types;
    }

    public function getLeaveBalanceWithUsage(int $employeeId, string $year): array
    {
        $query = "SELECT lt.type_name as type_name, lt.quota,
                 IFNULL(SUM(la.total_days), 0) as usage
                 FROM leave_types lt
                 LEFT JOIN leave_applications la 
                 ON la.leave_type = lt.type_name 
                 AND la.employee_id = $employeeId 
                 AND YEAR(la.start_date) = '$year'
                 GROUP BY lt.type_name";

        $result = mysqli_query($this->conn, $query);
        $balances = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $balance = (float)$row['quota'] - (float)$row['usage'];
                $balances[$row['type_name']] = [
                    'type_name' => $row['type_name'],
                    'quota' => $row['quota'],
                    'usage' => $row['usage'],
                    'available' => max($balance, 0)
                ];
            }
        }
        return $balances;
    }

    public function getLeaveApplicationsWithTypes(int $employeeId): array
    {
        $query = "SELECT la.*, lt.type_name AS type_name 
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
                $applications[] = $row;
            }
        }
        return $applications;
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
            $this->setMessage('success', 'Leave application successfully updated');
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

    private function escape(string $value): string
    {
        return mysqli_real_escape_string($this->conn, trim($value));
    }

    private function ensureLeaveBalanceExists(int $employeeId, string $leaveType): void
    {
        $checkQuery = "SELECT id FROM leave_balances 
                      WHERE employee_id = $employeeId AND leave_type = '$leaveType'";
        $result = mysqli_query($this->conn, $checkQuery);
        
        if (!$result) {
            throw new Exception("Gagal semak rekod baki cuti: " . mysqli_error($this->conn));
        }
        
        if (mysqli_num_rows($result) == 0) {
            $quotaQuery = "SELECT quota FROM leave_types WHERE type_name = '$leaveType'";
            $quotaResult = mysqli_query($this->conn, $quotaQuery);
            
            if (!$quotaResult) {
                $quotaQuery = "SELECT days_quota as quota FROM leave_types WHERE type_name = '$leaveType'";
                $quotaResult = mysqli_query($this->conn, $quotaQuery);
                
                if (!$quotaResult) {
                    $quotaQuery = "SELECT days_allowed as quota FROM leave_types WHERE leave_name = '$leaveType'";
                    $quotaResult = mysqli_query($this->conn, $quotaQuery);
                }
            }
            
            if (!$quotaResult) {
                throw new Exception("Gagal mendapatkan quota untuk jenis cuti ini: " . mysqli_error($this->conn));
            }
            
            $quotaRow = mysqli_fetch_assoc($quotaResult);
            if (!$quotaRow) {
                $defaultQuotas = [
                    'Annual Leave' => 14,
                    'Medical Leave' => 14,
                    'Emergency Leave' => 3,
                    'Maternity Leave' => 60,
                    'Paternity Leave' => 7
                ];
                
                $quota = $defaultQuotas[$leaveType] ?? 0;
                
                if ($quota == 0) {
                    throw new Exception("Jenis cuti '$leaveType' tidak wujud dalam sistem dan tiada quota default");
                }
            } else {
                $quota = (float) $quotaRow['quota'];
            }
            
            $insertQuery = "INSERT INTO leave_balances 
                           (employee_id, leave_type, quota, used, in_process, available_balance, year) 
                           VALUES 
                           ($employeeId, '$leaveType', $quota, 0, 0, $quota, YEAR(CURDATE()))";
            
            if (!mysqli_query($this->conn, $insertQuery)) {
                throw new Exception("Gagal membuat rekod baki cuti: " . mysqli_error($this->conn));
            }
        }
    }
}