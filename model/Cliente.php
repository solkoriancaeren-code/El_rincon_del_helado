<?php
class Cliente
{
    private $conn;
    private $table = "clientes";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE activo = 1 ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table . " 
                  (codigo, nombre, apellido, email, telefono, direccion)
                  VALUES (:codigo, :nombre, :apellido, :email, :telefono, :direccion)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, apellido = :apellido, email = :email, 
                      telefono = :telefono, direccion = :direccion
                  WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $query = "UPDATE " . $this->table . " SET activo = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function addPuntos($id, $puntos)
    {
        $query = "UPDATE " . $this->table . " SET puntos = puntos + :puntos WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':puntos', $puntos);
        return $stmt->execute();
    }

    public function search($term)
    {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE (nombre LIKE :term OR apellido LIKE :term OR email LIKE :term OR telefono LIKE :term)
                  AND activo = 1 LIMIT 10";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%$term%";
        $stmt->bindParam(':term', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
