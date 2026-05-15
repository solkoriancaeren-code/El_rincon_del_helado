<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Producto.php';

class ProductoController {
    private $producto;
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->producto = new Producto($this->db);
    }
    
    public function index() {
        return $this->producto->getAll();
    }
    
    public function show($id) {
        return $this->producto->getById($id);
    }
    
    public function store($data) {
        if (empty($data['nombre']) || empty($data['precio_mediano'])) {
            return ['success' => false, 'error' => 'Nombre y precio son requeridos'];
        }
        
        $codigo = 'HEL' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        $result = $this->producto->create([
            ':codigo' => $codigo,
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'] ?? '',
            ':categoria_id' => $data['categoria_id'] ?? 1,
            ':precio_pequeño' => $data['precio_pequeño'] ?? 0,
            ':precio_mediano' => $data['precio_mediano'],
            ':precio_grande' => $data['precio_grande'] ?? 0,
            ':stock' => $data['stock'] ?? 0,
            ':stock_minimo' => $data['stock_minimo'] ?? 5
        ]);
        
        if ($result) {
            return ['success' => true, 'message' => 'Producto creado correctamente'];
        }
        return ['success' => false, 'error' => 'Error al crear el producto'];
    }
    
    public function update($id, $data) {
        $result = $this->producto->update($id, [
            ':nombre' => $data['nombre'],
            ':descripcion' => $data['descripcion'] ?? '',
            ':categoria_id' => $data['categoria_id'] ?? 1,
            ':precio_pequeño' => $data['precio_pequeño'] ?? 0,
            ':precio_mediano' => $data['precio_mediano'],
            ':precio_grande' => $data['precio_grande'] ?? 0,
            ':stock' => $data['stock'] ?? 0,
            ':stock_minimo' => $data['stock_minimo'] ?? 5
        ]);
        
        if ($result) {
            return ['success' => true, 'message' => 'Producto actualizado correctamente'];
        }
        return ['success' => false, 'error' => 'Error al actualizar el producto'];
    }
    
    public function destroy($id) {
        $result = $this->producto->delete($id);
        if ($result) {
            return ['success' => true, 'message' => 'Producto eliminado correctamente'];
        }
        return ['success' => false, 'error' => 'Error al eliminar el producto'];
    }
}
?>