<?php
class Shift {
    private $conn;

    // Constructor accepts the database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Add this inside the Shift class in Model/Shift.php
    public function getAllShifts() {
        $query = "SELECT * FROM shifts ORDER BY id DESC";
        $result = $this->conn->query($query);

        $shifts = [];
        while ($row = $result->fetch_assoc()) {
            $shifts[] = $row;
        }

        return $shifts;
    }


    // Method to check if a shift code already exists
    public function checkDuplicateCode($code) {
        $query = "SELECT id FROM shifts WHERE code = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0; // Returns true if duplicate
    }

    // Method to check if a shift start time already exists
    public function checkDuplicateStartTime($start_time) {
        $query = "SELECT id FROM shifts WHERE start_time = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $start_time);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0; // Returns true if duplicate
    }

    // Method to create a new shift
    public function createShift($code, $description, $start_time, $work_hour, $break_hour, $status) {
        $query = "INSERT INTO shifts (code, description, start_time, work_hour, break_hour, status) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssssss", $code, $description, $start_time, $work_hour, $break_hour, $status);
        return $stmt->execute();
    }

    // Method to update an existing shift
    public function updateShift($id, $description, $start_time, $work_hour, $break_hour, $status) {
        $query = "UPDATE shifts SET description = ?, start_time = ?, work_hour = ?, break_hour = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssssi", $description, $start_time, $work_hour, $break_hour, $status, $id);
        return $stmt->execute();
    }

    // Method to delete a shift
    public function deleteShift($code) {
        $query = "DELETE FROM shifts WHERE code = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $code);
        return $stmt->execute();
    }
}
?>
