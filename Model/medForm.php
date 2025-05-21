<?php
class medForm {
    private $conn;
    private $table = "med_form";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} 
            (staff_id, receipt_date, description, total_amount, document_name, document_size, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())");

        return $stmt->execute([
            $data['staff_id'], $data['receipt_date'], $data['description'], $data['total_amount'],
            $data['document_name'], $data['document_size']
        ]);
    }

    // Update, Delete methods can be added similarly...
}
?>
