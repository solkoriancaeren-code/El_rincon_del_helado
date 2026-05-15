<?php
class Venta
{
    private $conn;
    private $table = "ventas";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {
        try {
            $this->conn->beginTransaction();

            $folio = 'FAC' . date('YmdHis') . rand(100, 999);

            $query = "INSERT INTO " . $this->table . " 
                      (folio, usuario_id, cliente_id, subtotal, iva, total, metodo_pago)
                      VALUES (:folio, :usuario_id, :cliente_id, :subtotal, :iva, :total, :metodo_pago)";
            $stmt = $this->conn->prepare($query);

            $stmt->execute([
                ':folio' => $folio,
                ':usuario_id' => $_SESSION['user_id'],
                ':cliente_id' => $data['cliente_id'] ?? null,
                ':subtotal' => $data['subtotal'],
                ':iva' => $data['iva'],
                ':total' => $data['total'],
                ':metodo_pago' => $data['metodo_pago']
            ]);

            $venta_id = $this->conn->lastInsertId();

            $detalleQuery = "INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario, subtotal, tamaño)
                             VALUES (:venta_id, :producto_id, :cantidad, :precio_unitario, :subtotal, :tamaño)";
            $detalleStmt = $this->conn->prepare($detalleQuery);

            foreach ($data['productos'] as $producto) {
                $detalleStmt->execute([
                    ':venta_id' => $venta_id,
                    ':producto_id' => $producto['id'],
                    ':cantidad' => $producto['cantidad'],
                    ':precio_unitario' => $producto['precio'],
                    ':subtotal' => $producto['subtotal'],
                    ':tamaño' => $producto['tamaño'] ?? 'mediano'
                ]);

                $updateStock = "UPDATE productos SET stock = stock - :cantidad WHERE id = :id";
                $stockStmt = $this->conn->prepare($updateStock);
                $stockStmt->execute([
                    ':cantidad' => $producto['cantidad'],
                    ':id' => $producto['id']
                ]);
            }

            $this->conn->commit();
            return ['success' => true, 'venta_id' => $venta_id, 'folio' => $folio];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getAll($limit = 100)
    {
        $query = "SELECT v.*, u.nombre as vendedor, CONCAT(c.nombre, ' ', c.apellido) as cliente_nombre 
                  FROM " . $this->table . " v
                  LEFT JOIN usuarios u ON v.usuario_id = u.id
                  LEFT JOIN clientes c ON v.cliente_id = c.id
                  ORDER BY v.fecha DESC
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $query = "SELECT v.*, u.nombre as vendedor, CONCAT(c.nombre, ' ', c.apellido) as cliente_nombre,
                         c.telefono as cliente_telefono, c.email as cliente_email
                  FROM " . $this->table . " v
                  LEFT JOIN usuarios u ON v.usuario_id = u.id
                  LEFT JOIN clientes c ON v.cliente_id = c.id
                  WHERE v.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $venta = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($venta) {
            $detalleQuery = "SELECT dv.*, p.nombre as producto_nombre 
                            FROM detalle_ventas dv
                            JOIN productos p ON dv.producto_id = p.id
                            WHERE dv.venta_id = :venta_id";
            $detalleStmt = $this->conn->prepare($detalleQuery);
            $detalleStmt->bindParam(':venta_id', $id);
            $detalleStmt->execute();
            $venta['detalles'] = $detalleStmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $venta;
    }

    public function getTotalHoy()
    {
        $query = "SELECT COALESCE(SUM(total), 0) as total FROM " . $this->table . " WHERE DATE(fecha) = CURDATE() AND estado = 'completada'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getCountHoy()
    {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE DATE(fecha) = CURDATE() AND estado = 'completada'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getTotalMes()
    {
        $query = "SELECT COALESCE(SUM(total), 0) as total FROM " . $this->table . " WHERE MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE())";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
