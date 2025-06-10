<?php
session_start();
require_once __DIR__ . '/../model/Role.php';

class RoleController
{
    private $role;
    private $user_id;

    public function getAllRoles()
    {
        return $this->role->getAllRoles();
    }

    public function __construct($db)
    {
        $this->role = new Role($db);
        $this->user_id = $_SESSION['user_id'] ?? 0;
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
        $name = trim($_POST['name']);
        $status = isset($_POST['status']) ? 1 : 0;

        if ($error = $this->role->validate($name)) {
            throw new Exception($error);
        }

        if ($this->role->existsByName($name)) {
            throw new Exception("A role with this name already exists");
        }

        if (!$this->role->create($name, $status, $this->user_id)) {
            throw new Exception("Error creating role");
        }

        $_SESSION['message'] = "Role created successfully";
        header("Location: ../roles.php");
        exit();
    }

    private function updateRole()
    {
        $id = (int)$_POST['role_id'];
        $name = trim($_POST['name']);
        $status = isset($_POST['status']) ? 1 : 0;

        if ($error = $this->role->validate($name)) {
            throw new Exception($error);
        }

        if (!$this->role->existsById($id)) {
            throw new Exception("Role not found");
        }

        if ($this->role->existsByName($name, $id)) {
            throw new Exception("Another role with this name already exists");
        }

        if (!$this->role->update($id, $name, $status, $this->user_id)) {
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

        if (!$this->role->delete($id)) {
            throw new Exception("Error deleting role");
        }

        $_SESSION['message'] = "Role deleted successfully";
        header("Location: ../roles.php");
        exit();
    }
}
