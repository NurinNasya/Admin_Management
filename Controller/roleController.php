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

    public function getRoleTypes()
    {
        return $this->role->getRoleTypes();
    }

    public function getRoleNamesByType($type)
    {
        return $this->role->getRoleNamesByType($type);
    }

    public function handleRequest() {
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
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }

    private function addRole() {
        $role_name = trim($_POST['role_name']);
        $role_type = trim($_POST['role_type']);
        $status = (int)$_POST['status'];

        if ($error = $this->role->validate($role_name, $role_type)) {
            throw new Exception($error);
        }

        if ($this->role->existsByNameAndType($role_name, $role_type)) {
            throw new Exception("A role with this name and type already exists");
        }

        if (!$this->role->create($role_name, $role_type, $status, $this->user_id)) {
            throw new Exception("Error creating role");
        }

        $_SESSION['message'] = "Role created successfully";
        header("Location: ../roles.php");
        exit();
    }

    /*public function handleRequest()
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
            } else {
                throw new Exception("Invalid request action");
            }

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header("Location: ../roles.php");
            exit();
        }
    }

    private function addRole()
    {
        $role_name = trim($_POST['role_name']);
        $role_type = trim($_POST['role_type']);
        $status = isset($_POST['status']) ? 1 : 0;

        if ($error = $this->role->validate($role_name, $role_type)) {
            throw new Exception($error);
        }

        if ($this->role->existsByNameAndType($role_name, $role_type)) {
            throw new Exception("A role with this name and type already exists");
        }

        if (!$this->role->create($role_name, $role_type, $status, $this->user_id)) {
            throw new Exception("Error creating role");
        }

        $_SESSION['message'] = "Role created successfully";
        header("Location: ../roles.php");
        exit();
    }*/

    private function updateRole()
    {
        $id = (int)$_POST['role_id'];
        $role_name = trim($_POST['role_name']);
        $role_type = trim($_POST['role_type']);
        $status = isset($_POST['status']) ? 1 : 0;

        if ($error = $this->role->validate($role_name, $role_type)) {
            throw new Exception($error);
        }

        if (!$this->role->existsById($id)) {
            throw new Exception("Role not found");
        }

        if ($this->role->existsByNameAndType($role_name, $role_type, $id)) {
            throw new Exception("Another role with this name and type already exists");
        }

        if (!$this->role->update($id, $role_name, $role_type, $status, $this->user_id)) {
            throw new Exception("Error updating role");
        }

        $_SESSION['message'] = "Role updated successfully";
        header("Location: ../roles.php");
        exit();
    }

    private function deleteRole()
    {
        $id = (int)$_POST['role_id'];

        if (!$this->role->existsById($id)) {
            throw new Exception("Role not found");
        }

        if ($this->role->isAssignedToUsers($id)) {
            throw new Exception("Cannot delete role assigned to users");
        }

        if (!$this->role->softDelete($id, $this->user_id)) {
            throw new Exception("Error deleting role");
        }

        $_SESSION['message'] = "Role deleted successfully";
        header("Location: ../roles.php");
        exit();
    }
}