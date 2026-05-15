<?php
class Producto
{
    private $conn;
    private $table = "productos";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAll()
    {
        $query = "SELECT p.*, c.nombre as categoria_nombre, c.color 
                  FROM " . $this->table . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.activo = 1 
                  ORDER BY p.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT p.*, c.nombre as categoria_nombre 
                  FROM " . $this->table . " p
                  LEFT JOIN categorias c ON p.categoria_id = c.id
                  WHERE p.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $query = "INSERT INTO " . $this->table . " 
                  (codigo, nombre, descripcion, categoria_id, precio_pequeño, precio_mediano, precio_grande, stock, stock_minimo)
                  VALUES (:codigo, :nombre, :descripcion, :categoria_id, :precio_pequeño, :precio_mediano, :precio_grande, :stock, :stock_minimo)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table . " 
                  SET nombre = :nombre, descripcion = :descripcion, categoria_id = :categoria_id,
                      precio_pequeño = :precio_pequeño, precio_mediano = :precio_mediano, 
                      precio_grande = :precio_grande, stock = :stock, stock_minimo = :stock_minimo
                  WHERE id = :id";
        $data['id'] = $id;
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($data);
    }

    public function delete($id)
    {
        $query = "UPDATE " . $this->table . " SET activo = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    public function updateStock($id, $cantidad)
    {
        $query = "UPDATE " . $this->table . " SET stock = stock - :cantidad WHERE id = :id AND stock >= :cantidad";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":cantidad", $cantidad);
        return $stmt->execute();
    }

    public function getLowStock()
    {
        $query = "SELECT * FROM " . $this->table . " WHERE stock <= stock_minimo AND activo = 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
