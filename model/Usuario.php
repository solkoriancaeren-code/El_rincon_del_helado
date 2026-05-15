<?php
class Usuario {
    private $conn;
    private $table = "usuarios";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function authenticate($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email AND activo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            $updateQuery = "UPDATE " . $this->table . " SET ultimo_acceso = NOW() WHERE id = :id";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(':id', $user['id']);
            $updateStmt->execute();
            return $user;
        }
        return false;
    }

    public function getById($id) {
        $query = "SELECT id, nombre, email, rol FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>