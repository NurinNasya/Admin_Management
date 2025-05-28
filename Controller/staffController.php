<?php
require_once '../Model/Staff.php';

class StaffController
{
    private Staff $staffModel;

    public function __construct()
    {
        $this->staffModel = new Staff();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index(): void
    {
        try {
            $staffs = $this->staffModel->getAllStaff();

            if (empty($staffs)) {
                $_SESSION['warning'] = "No staff records found";
            }

            require_once '../pages/staff.php';

        } catch (Exception $e) {
            $_SESSION['error'] = "Error loading staff data: " . $e->getMessage();
            header("Location: error.php");
            exit();
        }
    }

    public function showEmployeeInfo(int $id): void
    {
        try {
            // Verify ID is valid
            if ($id <= 0) {
                throw new Exception("Invalid employee ID");
            }

            // Get employee data
            $employee = $this->staffModel->getStaffById($id);
            
            if (!$employee) {
                throw new Exception("Employee not found");
            }

            // Get departments for dropdown
            // You'll need to add a getDepartments() method to your Staff model
            $departments = $this->staffModel->getDepartments();

            // Pass data to view
            require_once '../pages/employee_info.php';

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: ../pages/staff.php");
            exit();
        }
    }

    public function updateEmployeeInfo(array $data, array $files = []): void
    {
        try {
            if (!isset($data['id'])) {
                throw new Exception("Invalid employee ID");
            }

            $data = $this->sanitizeData($data);
            $id = $data['id'];
            $noic = $data['noic'] ?? '';
            $email = $data['email'] ?? '';

            // Duplicate check
            $result = $this->staffModel->findDuplicate($noic, $email, $id);
            if ($result && $result->num_rows > 0) {
                $existing = $result->fetch_assoc();
                if ($existing['noic'] === $ic && $existing['email'] === $email) {
                    $_SESSION['error'] = "IC and Email already exist.";
                } elseif ($existing['noic'] === $ic) {
                    $_SESSION['error'] = "IC already exists.";
                } elseif ($existing['email'] === $email) {
                    $_SESSION['error'] = "Email already exists.";
                }
                header("Location: ../pages/employee_info.php?id=" . $id);
                exit();
            }

            // Handle profile picture upload
            $profilePicPath = null;
            if (!empty($files['profile_pic']['name'])) {
                $targetDir = "../uploads/profile_pics/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                $filename = basename($files['profile_pic']['name']);
                $safeFilename = time() . "_" . preg_replace("/[^A-Za-z0-9_.]/", "_", $filename);
                $targetFile = $targetDir . $safeFilename;
                $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                if (in_array($fileType, ['jpg', 'jpeg', 'png']) && $files['profile_pic']['size'] <= 2 * 1024 * 1024) {
                    if (move_uploaded_file($files['profile_pic']['tmp_name'], $targetFile)) {
                        $profilePicPath = $targetFile;
                    } else {
                        throw new Exception("Failed to upload profile picture.");
                    }
                } else {
                    throw new Exception("Invalid image file. Please upload JPG or PNG under 2MB.");
                }
            }

            // Remove existing picture if requested
            if (!empty($data['remove_profile_pic'])) {
                $existing = $this->staffModel->getStaffById($id);
                if (!empty($existing['profile_pic']) && file_exists($existing['profile_pic'])) {
                    unlink($existing['profile_pic']);
                }
                $profilePicPath = ''; // Clear the photo
            }

            // Prepare final update data
            $updateData = [
                'id' => $id,
                'name' => $data['name'] ?? '',
                'noic' => $noic,
                'email' => $email,
                'pwd' => $data['pwd'] ?? null, // allow null if not changed
                'phone' => $data['phone'] ?? '',
                'gender' => $data['gender'] ?? '',
                'status_marital' => $data['status_marital'] ?? '',
                'dependent' => $data['dependent'] ?? 0,
                'roles' => $data['roles'] ?? '',
                'roles_status' => $data['roles _status'] ?? '',
                'staff_no' => $data['staff_no'] ?? '',
                'status' => $data['status'] ?? '',
                'departments_id' => $data['departments_id'] ?? '',
                'permenant_address' => $data['permenant_address'] ?? '',
                'mail_address' => $data['mail_address'] ?? '',
                'profile_pic' => $profilePicPath,
            ];

            $this->staffModel->updateStaff($updateData);

            $_SESSION['success'] = "Employee information updated successfully.";
            header("Location: ../pages/staff.php");
            exit();

        } catch (Exception $e) {
            $_SESSION['error'] = "Update failed: " . $e->getMessage();
            header("Location: ../pages/esi.php?id=" . $data['id']);
            exit();
        }
    }

    public function deleteStaff(int $id): void
    {
        try {
            if ($this->staffModel->deleteStaff($id)) {
                $_SESSION['success'] = "Staff deleted successfully.";
            } else {
                throw new Exception("Failed to delete staff.");
            }
        } catch (Exception $e) {
            $_SESSION['error'] = "Error deleting staff: " . $e->getMessage();
        }

        header("Location: ../pages/staff.php");
        exit();
    }

    private function sanitizeData(array $data): array
    {
        $sanitized = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = $this->sanitizeData($value);
            } else {
                $sanitized[$key] = htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
            }
        }
        return $sanitized;
    }
}
