<?php
require_once __DIR__ . '/../model/Staff.php';
use Staff;

class StaffController
{
    private Staff $staffModel;

    public function __construct()
    {
        $this->staffModel = new Staff();
    }

    public function index(): void
    {
        $staffs = $this->staffModel->getAllStaff();
        require_once __DIR__ . '/../pages/staff.php';
    }

    public function create(): void
    {
        require_once __DIR__ . '/../pages/staff_create.php';
    }

    public function store(array $data, array $files): void
    {
        $data = $this->sanitizeData($data);
        $data['profile_pic'] = $this->handleUpload($files);

        if ($this->staffModel->insertStaff($data)) {
            header("Location: staff.php?action=index&success=created");
            return;
        }
        header("Location: staff.php?action=create&error=create_failed");
    }

    public function edit(int $id): void
    {
        $staff = $this->staffModel->getStaffById($id);
        if (!$staff) {
            header("Location: staff.php?action=index&error=not_found");
            return;
        }
        require_once __DIR__ . '/../pages/staff_edit.php';
    }

    public function update(int $id, array $data, array $files): void
    {
        $data = $this->sanitizeData($data);
        if (!empty($files['profile_pic']['tmp_name'])) {
            $data['profile_pic'] = $this->handleUpload($files);
        }

        if ($this->staffModel->updateStaff($id, $data)) {
            header("Location: staff.php?action=index&success=updated");
            return;
        }
        header("Location: staff.php?action=edit&id=$id&error=update_failed");
    }

    public function delete(int $id): void
    {
        if ($this->staffModel->deleteStaff($id)) {
            header("Location: staff.php?action=index&success=deleted");
            return;
        }
        header("Location: staff.php?action=index&error=delete_failed");
    }

    private function sanitizeData(array $data): array
    {
        return [
            'staff_no' => htmlspecialchars($data['staff_no'] ?? ''),
            'name' => htmlspecialchars($data['name'] ?? ''),
            'email' => filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL),
            'phone' => htmlspecialchars($data['phone'] ?? ''),
            'department' => htmlspecialchars($data['department'] ?? ''),
            'role' => htmlspecialchars($data['role'] ?? ''),
            'profile_pic' => $data['profile_pic'] ?? 'assets/img/default-avatar.png',
            'medical_leave_quota' => (int) ($data['medical_leave_quota'] ?? 14)
        ];
    }

    private function handleUpload(array $files): string
    {
        if (isset($files['profile_pic']) && $files['profile_pic']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../assets/img/profiles/';
            $filename = uniqid() . '_' . basename($files['profile_pic']['name']);
            if (move_uploaded_file($files['profile_pic']['tmp_name'], $uploadDir . $filename)) {
                return 'assets/img/profiles/' . $filename;
            }
        }
        return 'assets/img/default-avatar.png';
    }
}