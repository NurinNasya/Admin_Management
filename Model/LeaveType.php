<?php
namespace App\Model;

use Exception;

class LeaveType
{
    private \mysqli $conn;
    private string $table = 'leave_types';

    public ?int $id = null;
    public string $type_name = '';
    public string $description = '';
    public int $max_days = 0;
    public int $is_active = 1;
    public ?string $created_at = null;
    public ?string $updated_at = null;

    public function __construct(\mysqli $database)
    {
        $this->conn = $database;
    }

    public function create(): array
    {
        if (empty($this->type_name)) {
            return ['success' => false, 'message' => 'Leave type name is required'];
        }

        if ($this->max_days <= 0) {
            return ['success' => false, 'message' => 'Max days must be greater than 0'];
        }

        try {
            $type_name = $this->conn->real_escape_string($this->type_name);
            $description = $this->conn->real_escape_string($this->description);
            $max_days = (int)$this->max_days;
            $is_active = (int)$this->is_active;

            $query = "INSERT INTO {$this->table} 
                      (type_name, description, max_days, is_active, created_at) 
                      VALUES ('$type_name', '$description', $max_days, $is_active, NOW())";

            if ($this->conn->query($query)) {
                $this->id = $this->conn->insert_id;
                return [
                    'success' => true,
                    'message' => 'Leave type created successfully',
                    'id' => $this->id
                ];
            }

            throw new Exception("Failed to create leave type: " . $this->conn->error);
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to create leave type: ' . $e->getMessage(),
                'id' => null
            ];
        }
    }

    public function read(): array
    {
        try {
            $query = "SELECT * FROM {$this->table} ORDER BY type_name ASC";
            $result = $this->conn->query($query);

            if (!$result) {
                throw new Exception("Query failed: " . $this->conn->error);
            }

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            return [
                'success' => true,
                'data' => $data,
                'message' => 'Leave types retrieved successfully',
                'total' => count($data)
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve leave types: ' . $e->getMessage(),
                'total' => 0
            ];
        }
    }

    public function readActive(): array
    {
        try {
            $query = "SELECT id, type_name FROM {$this->table} WHERE is_active = 1 ORDER BY type_name ASC";
            $result = $this->conn->query($query);

            if (!$result) {
                throw new Exception("Query failed: " . $this->conn->error);
            }

            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            return [
                'success' => true,
                'data' => $data,
                'message' => 'Active leave types retrieved successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve active leave types: ' . $e->getMessage()
            ];
        }
    }

    public function readSingle(): array
    {
        try {
            $id = (int)$this->id;
            $query = "SELECT * FROM {$this->table} WHERE id = $id LIMIT 1";
            $result = $this->conn->query($query);

            if (!$result) {
                throw new Exception("Query failed: " . $this->conn->error);
            }

            $row = $result->fetch_assoc();

            if ($row) {
                $this->id = (int) $row['id'];
                $this->type_name = $row['type_name'];
                $this->description = $row['description'];
                $this->max_days = (int) $row['max_days'];
                $this->is_active = (int) $row['is_active'];
                $this->created_at = $row['created_at'];
                $this->updated_at = $row['updated_at'];

                return [
                    'success' => true,
                    'data' => $row,
                    'message' => 'Leave type retrieved successfully'
                ];
            }

            return [
                'success' => false,
                'data' => null,
                'message' => 'Leave type not found'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to retrieve leave type: ' . $e->getMessage()
            ];
        }
    }

    public function update(): array
    {
        try {
            $id = (int)$this->id;
            $type_name = $this->conn->real_escape_string($this->type_name);
            $description = $this->conn->real_escape_string($this->description);
            $max_days = (int)$this->max_days;
            $is_active = (int)$this->is_active;

            $query = "UPDATE {$this->table} 
                      SET type_name = '$type_name', 
                          description = '$description', 
                          max_days = $max_days, 
                          is_active = $is_active, 
                          updated_at = NOW() 
                      WHERE id = $id";

            if ($this->conn->query($query)) {
                return [
                    'success' => true,
                    'message' => 'Leave type updated successfully'
                ];
            }

            throw new Exception("Failed to update leave type: " . $this->conn->error);
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to update leave type: ' . $e->getMessage()
            ];
        }
    }

    public function delete(): array
    {
        try {
            $id = (int)$this->id;

            if ($this->hasDependentRecords()) {
                throw new Exception("Cannot delete leave type - there are dependent records");
            }

            $query = "DELETE FROM {$this->table} WHERE id = $id";
            if ($this->conn->query($query)) {
                return [
                    'success' => true,
                    'message' => 'Leave type deleted successfully'
                ];
            }

            throw new Exception("Failed to delete leave type: " . $this->conn->error);
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete leave type: ' . $e->getMessage()
            ];
        }
    }

    private function hasDependentRecords(): bool
    {
        $id = (int) $this->id;

        $query1 = "SELECT COUNT(*) as count 
                   FROM leave_applications 
                   WHERE leave_type = (SELECT type_name FROM leave_types WHERE id = $id)";
        $result1 = $this->conn->query($query1);
        $row1 = $result1->fetch_assoc();

        if ($row1['count'] > 0) {
            return true;
        }

        $query2 = "SELECT COUNT(*) as count 
                   FROM user_leave_quotas 
                   WHERE leave_type_id = $id";
        $result2 = $this->conn->query($query2);
        $row2 = $result2->fetch_assoc();

        return $row2['count'] > 0;
    }

    public function bulkAllocateLeave(int $leaveTypeId, string $startDate, string $endDate): array
    {
        try {
            $leaveTypeId = (int) $leaveTypeId;
            $year = (int) date('Y', strtotime($startDate));

            $query = "INSERT INTO user_leave_quotas 
                     (user_id, leave_type_id, year, total_days, used_days, remaining_days, created_at)
                     SELECT 
                         u.id, $leaveTypeId, $year, lt.max_days, 0, lt.max_days, NOW()
                     FROM users u
                     CROSS JOIN leave_types lt
                     WHERE lt.id = $leaveTypeId
                       AND u.is_active = 1
                       AND NOT EXISTS (
                           SELECT 1 FROM user_leave_quotas ulq 
                           WHERE ulq.user_id = u.id 
                             AND ulq.leave_type_id = $leaveTypeId
                             AND ulq.year = $year
                       )";

            if ($this->conn->query($query)) {
                return [
                    'success' => true,
                    'message' => 'Leave allocated to users successfully',
                    'affected_rows' => $this->conn->affected_rows
                ];
            }

            throw new Exception("Failed to allocate leave: " . $this->conn->error);
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to allocate leave: ' . $e->getMessage(),
                'affected_rows' => 0
            ];
        }
    }
}
