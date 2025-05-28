<?php
require_once '../Model/medForm.php';

class MedFormController {
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate required fields
                $required = ['receipt_data', 'description', 'total_amount'];
                foreach ($required as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("Missing required field: $field");
                    }
                }

                // Initialize model
                $model = new medForm();

                // Prepare data with basic sanitization
                $data = [
                    'staff_id'       => (int)$_POST['staff_id'],
                    'receipt_data'   => $this->sanitizeInput($_POST['receipt_data']),
                    'description'    => $this->sanitizeInput($_POST['description']),
                    'total_amount'   => (float)$_POST['total_amount'],
                    'updated_by'     => $this->sanitizeInput($_POST['updated_by']),
                    'remark'         => $this->sanitizeInput($_POST['remark'] ?? '')
                ];

                // Handle file upload
                if (!empty($_FILES['document']['name'])) {
                    $uploadResult = $this->handleFileUpload();
                    if ($uploadResult['success']) {
                        $data['document_name'] = $uploadResult['filename'];
                        $data['document_size'] = $_FILES['document']['size'];
                    } else {
                        throw new Exception($uploadResult['error']);
                    }
                }

                // Insert data
                if (!$model->insert($data)) {
                    throw new Exception('Failed to save medical form');
                }

                // Redirect to history page
                header('Location: index.php?action=medical_history');
                exit;

            } catch (Exception $e) {
                error_log('Medical Form Error: ' . $e->getMessage());
                header('Location: index.php?action=medical_history&error=1');
                exit;
            }
        }
    }

    public function history() {
        try {
            $staff_id = 1; // In real app, get from session
            $model = new medForm();
            $forms = $model->getAll($staff_id);
            
            // Load view
            require 'pages/med_form.php';
            
        } catch (Exception $e) {
            error_log('Medical History Error: ' . $e->getMessage());
            $forms = [];
            require 'pages/med_form.php';
        }
    }

    private function sanitizeInput($data) {
        return htmlspecialchars(strip_tags(trim($data)));
    }

    private function handleFileUpload() {
        $uploadDir = 'uploads/';
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
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['success' => true, 'filename' => $filename];
        } else {
            return ['success' => false, 'error' => 'File upload failed'];
        }
    }
}