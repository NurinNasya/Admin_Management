<?php
namespace App\Controller;

require_once __DIR__ . '/../Model/LeaveType.php';

use App\Model\LeaveType;

class LeaveTypeController
{
    private $leaveTypeModel;
    private $isAjax;

    public function __construct(LeaveType $leaveTypeModel, bool $isAjax = false)
    {
        $this->leaveTypeModel = $leaveTypeModel;
        $this->isAjax = $isAjax;
    }

    public function handleRequest(): array
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->verifyCsrfToken();
                return $this->handlePostRequest();
            }

            return $this->getAllLeaveTypes();
        } catch (\RuntimeException $e) {
            error_log('LeaveTypeController Error: ' . $e->getMessage());
            return $this->createErrorResponse($e->getMessage());
        } catch (\Exception $e) {
            error_log('LeaveTypeController Unexpected Error: ' . $e->getMessage());
            return $this->createErrorResponse('An unexpected error occurred');
        }
    }

    private function verifyCsrfToken(): void
    {
        if (empty($_SESSION['csrf_token'])) {
            throw new \RuntimeException('CSRF token not generated');
        }

        if (empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            throw new \RuntimeException('Invalid CSRF token');
        }
    }

    private function handlePostRequest(): array
    {
        if (empty($_POST['action'])) {
            return $this->createErrorResponse('No action specified');
        }

        $action = $_POST['action'];
        $data = $this->filterInput($_POST);

        switch ($action) {
            case 'create':
                return $this->createLeaveType($data);
            case 'edit':
                return $this->updateLeaveType($data);
            case 'delete':
                return $this->deleteLeaveType($data);
            case 'allocate_leave':
                return $this->bulkAllocateLeave($data);
            default:
                return $this->createErrorResponse('Invalid action');
        }
    }

    private function filterInput(array $data): array
    {
        $filtered = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $filtered[$key] = $this->filterInput($value);
            } else {
                $filtered[$key] = trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
            }
        }
        return $filtered;
    }

    public function getAllLeaveTypes(): array
    {
        $result = $this->leaveTypeModel->read();

        if (!$result['success']) {
            throw new \RuntimeException($result['message'] ?? 'Failed to retrieve leave types');
        }

        return [
            'success' => true,
            'data' => $result['data'],
            'total' => $result['total'] ?? count($result['data'])
        ];
    }

    public function getLeaveType(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $this->leaveTypeModel->id = $id;
        $result = $this->leaveTypeModel->readSingle();

        return $result['success'] ? $result['data'] : null;
    }

    public function getActiveLeaveTypes(): array
    {
        $result = $this->leaveTypeModel->readActive();
        
        if (!$result['success']) {
            error_log('Failed to get active leave types: ' . ($result['message'] ?? 'Unknown error'));
            return [];
        }

        return $result['data'] ?? [];
    }

    private function createLeaveType(array $data): array
    {
        $validation = $this->validateLeaveTypeData($data);
        if (!$validation['valid']) {
            return $validation;
        }

        $this->setModelProperties($data);
        $result = $this->leaveTypeModel->create();

        if (!$result['success']) {
            throw new \RuntimeException($result['message'] ?? 'Failed to create leave type');
        }

        error_log("Leave type created: ID {$result['id']}");
        return [
            'success' => true,
            'message' => 'Leave type created successfully',
            'id' => $result['id']
        ];
    }

    private function updateLeaveType(array $data): array
    {
        if (empty($data['id']) || !is_numeric($data['id'])) {
            return $this->createErrorResponse('Invalid leave type ID');
        }

        $validation = $this->validateLeaveTypeData($data);
        if (!$validation['valid']) {
            return $validation;
        }

        $this->setModelProperties($data);
        $result = $this->leaveTypeModel->update();

        if (!$result['success']) {
            throw new \RuntimeException($result['message'] ?? 'Failed to update leave type');
        }

        error_log("Leave type updated: ID {$data['id']}");
        return [
            'success' => true,
            'message' => 'Leave type updated successfully'
        ];
    }

    private function deleteLeaveType(array $data): array
    {
        if (empty($data['id']) || !is_numeric($data['id'])) {
            return $this->createErrorResponse('Invalid leave type ID');
        }

        $this->leaveTypeModel->id = (int)$data['id'];
        $result = $this->leaveTypeModel->delete();

        if (!$result['success']) {
            throw new \RuntimeException($result['message'] ?? 'Failed to delete leave type');
        }

        error_log("Leave type deleted: ID {$data['id']}");
        return [
            'success' => true,
            'message' => 'Leave type deleted successfully'
        ];
    }

    private function bulkAllocateLeave(array $data): array
    {
        $required = ['leave_type', 'start_date', 'end_date'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return $this->createErrorResponse("$field is required");
            }
        }

        // Validate dates
        $startDate = strtotime($data['start_date']);
        $endDate = strtotime($data['end_date']);
        
        if (!$startDate || !$endDate) {
            return $this->createErrorResponse('Invalid date format');
        }

        if ($startDate > $endDate) {
            return $this->createErrorResponse('Start date cannot be after end date');
        }

        $result = $this->leaveTypeModel->bulkAllocateLeave(
            (int)$data['leave_type'],
            date('Y-m-d', $startDate),
            date('Y-m-d', $endDate)
        );

        if (!$result['success']) {
            throw new \RuntimeException($result['message'] ?? 'Failed to allocate leave');
        }

        error_log("Leave allocated to {$result['affected_rows']} users");
        return [
            'success' => true,
            'message' => 'Leave allocated successfully',
            'affected_rows' => $result['affected_rows']
        ];
    }

    private function setModelProperties(array $data): void
    {
        $this->leaveTypeModel->type_name = $data['type_name'] ?? '';
        $this->leaveTypeModel->description = $data['description'] ?? '';
        $this->leaveTypeModel->max_days = (int)($data['max_days'] ?? 0);
        $this->leaveTypeModel->is_active = isset($data['is_active']) ? (int)$data['is_active'] : 0;

        if (!empty($data['id'])) {
            $this->leaveTypeModel->id = (int)$data['id'];
        }
    }

    private function validateLeaveTypeData(array $data): array
    {
        $errors = [];

        // Validate type_name
        if (empty($data['type_name'])) {
            $errors['type_name'] = 'Leave type name is required';
        } elseif (strlen($data['type_name']) > 100) {
            $errors['type_name'] = 'Name must be less than 100 characters';
        }

        // Validate description
        if (!empty($data['description']) && strlen($data['description']) > 255) {
            $errors['description'] = 'Description must be less than 255 characters';
        }

        // Validate max_days
        $maxDays = (int)($data['max_days'] ?? 0);
        if ($maxDays <= 0) {
            $errors['max_days'] = 'Max days must be positive';
        } elseif ($maxDays > 365) {
            $errors['max_days'] = 'Max days cannot exceed 365';
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'valid' => false,
                'message' => 'Please correct the errors',
                'errors' => $errors
            ];
        }

        return ['valid' => true];
    }

    private function createErrorResponse(string $message, array $errors = []): array
    {
        return [
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ];
    }
}