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
        $applicationDate = $this->escape($data['application_date']);
        $startDate = $this->escape($data['start_date']);
        $endDate = $this->escape($data['end_date']);
        $totalDays = (float) $data['total_days'];
        $leaveType = $this->escape($data['leave_type']);
        $reason = $this->escape($data['reason']);

        // [1] Validasi baki cukup - Perbaikan disini
        $balanceCheck = "SELECT quota, used, in_process 
                        FROM leave_balances 
                        WHERE employee_id = $employeeId 
                        AND leave_type = '$leaveType'";
        $result = mysqli_query($this->conn, $balanceCheck);
        
        if (!$result || mysqli_num_rows($result) === 0) {
            // Jika tidak ada record, anggap quota penuh untuk Unpaid Leave
            if ($leaveType === 'Unpaid Leave') {
                $available = PHP_FLOAT_MAX; // Unpaid Leave tidak terbatas
            } else {
                throw new Exception("Rekod baki cuti tidak dijumpai untuk jenis ini");
            }
        } else {
            $balance = mysqli_fetch_assoc($result);
            $available = (float)$balance['quota'] - (float)$balance['used'] - (float)$balance['in_process'];
        }
        
        if ($available < $totalDays && $leaveType !== 'Unpaid Leave') {
            throw new Exception("Baki cuti tidak mencukupi. Baki tersedia: $available hari");
        }

        // [2] Simpan permohonan
        $query = "INSERT INTO leave_applications 
                 (employee_id, application_date, start_date, end_date, 
                  total_days, leave_type, reason, status) 
                 VALUES 
                 ($employeeId, '$applicationDate', '$startDate', '$endDate', 
                  $totalDays, '$leaveType', '$reason', 'pending')";

        if (!mysqli_query($this->conn, $query)) {
            throw new Exception("Gagal menyimpan permohonan cuti: " . mysqli_error($this->conn));
        }

        // [3] Jika record balance tidak ada, buat record baru
        if (!isset($balance)) {
            $initBalance = "INSERT INTO leave_balances 
                          (employee_id, leave_type, quota, used, in_process)
                          VALUES
                          ($employeeId, '$leaveType', 0, 0, $totalDays)";
            
            if (!mysqli_query($this->conn, $initBalance)) {
                throw new Exception("Gagal membuat rekod baki baru: " . mysqli_error($this->conn));
            }
        } else {
            // Update balance yang sudah ada
            $update_sql = "UPDATE leave_balances 
                          SET in_process = in_process + $totalDays
                          WHERE employee_id = $employeeId 
                          AND leave_type = '$leaveType'";

            if (!mysqli_query($this->conn, $update_sql)) {
                throw new Exception("Gagal mengemaskini baki cuti: " . mysqli_error($this->conn));
            }
        }

        // [4] Update available_balance
        if (!$this->updateAvailableBalance($employeeId, $leaveType)) {
            throw new Exception("Gagal mengemaskini baki tersedia");
        }

        mysqli_commit($this->conn);
        return mysqli_insert_id($this->conn);
    } catch (Exception $e) {
        mysqli_rollback($this->conn);
        error_log("[SaveLeave] " . $e->getMessage());
        $this->setMessage('error', $e->getMessage());
        return false;
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

        return mysqli_query($this->conn, $query);
    }

    public function approveApplication(int $application_id): bool
    {
        mysqli_begin_transaction($this->conn);

        try {
            $app = $this->getLeaveApplication($application_id);
            if (!$app) throw new Exception("Permohonan tidak dijumpai");

            // Update balance
            $sql = "UPDATE leave_balances 
                    SET 
                        in_process = in_process - {$app['total_days']},
                        used = used + {$app['total_days']}
                    WHERE employee_id = {$app['employee_id']} 
                    AND leave_type = '{$app['leave_type']}'";
            
            if (!mysqli_query($this->conn, $sql)) {
                throw new Exception("Gagal update baki cuti");
            }

            // Update available_balance
            if (!$this->updateAvailableBalance($app['employee_id'], $app['leave_type'])) {
                throw new Exception("Gagal mengemaskini baki tersedia");
            }

            // Update status
            $sql = "UPDATE leave_applications 
                    SET status = 'approved', approved_at = NOW() 
                    WHERE id = $application_id";
            if (!mysqli_query($this->conn, $sql)) {
                throw new Exception("Gagal update status permohonan");
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

            $sql = "UPDATE leave_balances 
                    SET in_process = in_process - {$app['total_days']} 
                    WHERE employee_id = {$app['employee_id']} 
                    AND leave_type = '{$app['leave_type']}'";
            if (!mysqli_query($this->conn, $sql)) throw new Exception("Gagal update baki cuti");

            // Update available_balance
            if (!$this->updateAvailableBalance($app['employee_id'], $app['leave_type'])) {
                throw new Exception("Gagal mengemaskini baki tersedia");
            }

            $sql = "UPDATE leave_applications SET status = 'rejected' WHERE id = $application_id";
            if (!mysqli_query($this->conn, $sql)) throw new Exception("Gagal update status permohonan");

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
            if (!$app) throw new Exception("Permohonan tidak dijumpai");

            if ($app['status'] === 'pending') {
                $sql = "UPDATE leave_balances 
                        SET in_process = in_process - {$app['total_days']} 
                        WHERE employee_id = {$app['employee_id']} 
                        AND leave_type = '{$app['leave_type']}'";
                if (!mysqli_query($this->conn, $sql)) throw new Exception("Gagal update baki cuti");

                // Update available_balance
                if (!$this->updateAvailableBalance($app['employee_id'], $app['leave_type'])) {
                    throw new Exception("Gagal mengemaskini baki tersedia");
                }
            }

            $sql = "DELETE FROM leave_applications WHERE id = $application_id";
            if (!mysqli_query($this->conn, $sql)) throw new Exception("Gagal hapus permohonan");

            mysqli_commit($this->conn);
            return true;
        } catch (Exception $e) {
            mysqli_rollback($this->conn);
            error_log($e->getMessage());
            return false;
        }
    }

    private function updateAvailableBalance(int $employeeId, string $leaveType): bool
{
    $sql = "UPDATE leave_balances 
            SET available_balance = (quota - used - in_process)
            WHERE employee_id = $employeeId 
            AND leave_type = '$leaveType'";
    
    return mysqli_query($this->conn, $sql);
}

    public function getLeaveApplication(int $id): array|false
    {
        $query = "SELECT * FROM leave_applications WHERE id = $id";
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
        while ($row = mysqli_fetch_assoc($result)) {
            $types[] = $row;
        }
        return $types;
    }

    public function getLeaveBalanceWithUsage(int $employeeId, string $year): array
    {
        $query = "SELECT lt.name as type_name, lt.quota,
                  IFNULL(SUM(la.total_days), 0) as usage
                  FROM leave_types lt
                  LEFT JOIN leave_applications la 
                  ON la.leave_type = lt.name 
                  AND la.employee_id = $employeeId 
                  AND YEAR(la.start_date) = '$year'
                  GROUP BY lt.name";
        $result = mysqli_query($this->conn, $query);
        $balances = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $balance = (float)$row['quota'] - (float)$row['usage'];
            $balances[$row['type_name']] = [
                'type_name' => $row['type_name'],
                'quota' => $row['quota'],
                'usage' => $row['usage'],
                'available' => max($balance, 0)
            ];
        }
        return $balances;
    }

    public function getLeaveApplicationsWithTypes(int $employeeId): array
    {
        $query = "SELECT la.*, lt.name AS type_name 
                  FROM leave_applications la 
                  LEFT JOIN leave_types lt ON la.leave_type = lt.name 
                  WHERE la.employee_id = $employeeId 
                  ORDER BY la.application_date DESC";
        $result = mysqli_query($this->conn, $query);
        $applications = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $row['start_date_formatted'] = date('d/m/Y', strtotime($row['start_date']));
            $row['end_date_formatted'] = date('d/m/Y', strtotime($row['end_date']));
            $applications[] = $row;
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
            throw new Exception('Permohonan tidak dijumpai');
        }

        $session['application_details'] = $application;
        header('Location: ../pages/leaveForm.php?view=application_info&id=' . $applicationId);
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

    private function escape(string $value): string
    {
        return mysqli_real_escape_string($this->conn, trim($value));
    }
}