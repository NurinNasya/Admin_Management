<?php
require_once '../Model/leaveform.php';

class LeaveFormController {
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $model = new LeaveForm();
                
                // Validate required fields
                $required = ['start_date', 'end_date', 'leave_type', 'total_days'];
                foreach ($required as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("Missing required field: $field");
                    }
                }

                // Prepare data
                $data = [
                    'staff_id' => (int)$_POST['staff_id'],
                    'start_date' => $this->sanitizeInput($_POST['start_date']),
                    'end_date' => $this->sanitizeInput($_POST['end_date']),
                    'leave_type' => $this->sanitizeInput($_POST['leave_type']),
                    'total_days' => (int)$_POST['total_days'],
                    'description' => $this->sanitizeInput($_POST['description'] ?? ''),
                    'updated_by' => $this->sanitizeInput($_POST['updated_by']),
                    'remark' => $this->sanitizeInput($_POST['remark'] ?? '')
                ];

                // Handle file upload
                if (!empty($_FILES['document']['name'])) {
                    $uploadResult = $this->handleFileUpload();
                    if ($uploadResult['success']) {
                        $data['document_name'] = $uploadResult['filename'];
                        $data['document_size'] = $_FILES['document']['size'];
                    }
                }

                // Create leave form
                if (!$model->create($data)) {
                    throw new Exception('Failed to create leave form');
                }

                header('Location: index.php?action=leave_history');
                exit;
            } catch (Exception $e) {
                error_log('LeaveForm Create Error: ' . $e->getMessage());
                header('Location: index.php?action=leave_history&error=1');
                exit;
            }
        }
    }

    public function history() {
        try {
            $staff_id = 1; // Should come from session in real app
            $model = new LeaveForm();
            $leaves = $model->getByStaff($staff_id);
            
            require 'pages/leave_form.php';
        } catch (Exception $e) {
            error_log('Leave History Error: ' . $e->getMessage());
            $leaves = [];
            require 'pages/leave_form.php';
        }
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $required = ['id', 'field', 'status', 'updated_by'];
                foreach ($required as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("Missing required field: $field");
                    }
                }

                $model = new LeaveForm();
                $success = $model->updateStatus(
                    (int)$_POST['id'],
                    $this->sanitizeInput($_POST['field']),
                    $this->sanitizeInput($_POST['status']),
                    $this->sanitizeInput($_POST['updated_by'])
                );

                if (!$success) {
                    throw new Exception('Failed to update status');
                }

                header('Location: index.php?action=leave_history');
                exit;
            } catch (Exception $e) {
                error_log('Leave Status Update Error: ' . $e->getMessage());
                header('Location: index.php?action=leave_history&error=1');
                exit;
            }
        }
    }

    private function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    private function handleFileUpload() {
        $uploadDir = 'uploads/leaves/';
        $maxSize = 5 * 1024 * 1024; // 5MB
        $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];
        
        $file = $_FILES['document'];
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Validate file
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'File too large (max 5MB)'];
        }
        
        if (!in_array($fileExt, $allowedTypes)) {
            return ['success' => false, 'error' => 'Invalid file type'];
        }
        
        // Generate unique filename
        $filename = uniqid() . '.' . $fileExt;
        $targetPath = $uploadDir . $filename;
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'filename' => $filename];
        } else {
            return ['success' => false, 'error' => 'File upload failed'];
        }
    }
}