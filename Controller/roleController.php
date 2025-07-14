<?php
require_once __DIR__ . '/../Model/Role.php';

class RoleController
{
    private $role;
    private $user_id;

    public function __construct($db)
    {
        $this->role = new Role($db);
        $this->user_id = $_SESSION['user_id'] ?? 0;
    }

    public function getAllRoles()
    {
        return $this->role->getAllRoles();
    }

    public function handleRequest()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Invalid request method");
            }

            if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                throw new Exception("Invalid security token");
            }

            if (isset($_POST['add_role'])) {
                $this->addRole();
            } elseif (isset($_POST['update_role'])) {
                $this->updateRole();
            } elseif (isset($_POST['delete_role'])) {
                $this->deleteRole();
            }
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            error_log("RoleController Error: " . $e->getMessage());
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    private function addRole()
    {
        $role_name = trim($_POST['role_name']);
        $role_type = trim($_POST['role_type']);
        $status = (int)$_POST['status'];
        $user_id = $this->user_id;

        if ($error = $this->role->validate($role_name, $role_type)) {
            throw new Exception($error);
        }

        if ($this->role->existsByNameAndType($role_name, $role_type)) {
            throw new Exception("A role with this name and type already exists");
        }

        $new_id = $this->role->create($role_name, $role_type, $status, $user_id);
        
        if (!$new_id) {
            throw new Exception("Failed to create role");
        }

        $_SESSION['message'] = "Role created successfully!";
        header("Location: roles.php");
        exit();
    }

    private function updateRole() {
    $id = (int)$_POST['role_id'];
    $role_name = trim($_POST['role_name']);
    $role_type = trim($_POST['role_type']);
    $status = (int)$_POST['status'];
    
    // Validate input (fixed missing parentheses)
    if (empty($role_name)) throw new Exception("Role name cannot be empty");
    if (empty($role_type)) throw new Exception("Role type cannot be empty");
    
    if (!$this->role->existsById($id)) {
        throw new Exception("Role not found");
    }
    
    if ($this->role->existsByNameAndType($role_name, $role_type, $id)) {
        throw new Exception("Role name/type combination already exists");
    }
    
    if (!$this->role->update($id, $role_name, $role_type, $status, $this->user_id)) {
        throw new Exception("Failed to update role");
    }
    
    $_SESSION['message'] = "Role updated successfully";
    header("Location: roles.php");
    exit();
}

// In Controller/roleController.php
private function deleteRole() {
    try {
        // Validate input
        if (!isset($_POST['role_id']) || !is_numeric($_POST['role_id'])) {
            throw new Exception("Invalid role ID");
        }

        $id = (int)$_POST['role_id'];
        
        // Check existence
        if (!$this->role->existsById($id)) {
            throw new Exception("Role not found");
        }

        // Check assignments
        if ($this->role->isAssignedToUsers($id)) {
            throw new Exception("Cannot delete role: Currently assigned to users");
        }

        // Perform deletion
        if (!$this->role->delete($id)) {
            throw new Exception("Database error during deletion");
        }

        // Success
        $_SESSION['message'] = "Role deleted successfully";
        header("Location: roles.php");
        exit();

    } catch (Exception $e) {
        error_log("Delete Error: " . $e->getMessage());
        $_SESSION['error'] = $e->getMessage();
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
}