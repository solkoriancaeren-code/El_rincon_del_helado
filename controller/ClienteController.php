<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Cliente.php';

class ClienteController {
    private $cliente;
    
    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->cliente = new Cliente($db);
    }
    
    public function index() {
        return $this->cliente->getAll();
    }
    
    public function show($id) {
        return $this->cliente->getById($id);
    }
    
    public function store($data) {
        if (empty($data['nombre']) || empty($data['apellido'])) {
            return ['success' => false, 'error' => 'Nombre y apellido son requeridos'];
        }
        
        $codigo = 'CLI' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        $result = $this->cliente->create([
            ':codigo' => $codigo,
            ':nombre' => $data['nombre'],
            ':apellido' => $data['apellido'],
            ':email' => $data['email'] ?? null,
            ':telefono' => $data['telefono'] ?? null,
            ':direccion' => $data['direccion'] ?? null
        ]);
        
        if ($result) {
            return ['success' => true, 'message' => 'Cliente creado correctamente'];
        }
        return ['success' => false, 'error' => 'Error al crear el cliente'];
    }
    
    public function update($id, $data) {
        $result = $this->cliente->update($id, [
            ':nombre' => $data['nombre'],
            ':apellido' => $data['apellido'],
            ':email' => $data['email'] ?? null,
            ':telefono' => $data['telefono'] ?? null,
            ':direccion' => $data['direccion'] ?? null
        ]);
        
        if ($result) {
            return ['success' => true, 'message' => 'Cliente actualizado correctamente'];
        }
        return ['success' => false, 'error' => 'Error al actualizar el cliente'];
    }
    
    public function destroy($id) {
        $result = $this->cliente->delete($id);
        if ($result) {
            return ['success' => true, 'message' => 'Cliente eliminado correctamente'];
        }
        return ['success' => false, 'error' => 'Error al eliminar el cliente'];
    }
    
    public function search($term) {
        return $this->cliente->search($term);
    }
}
?>