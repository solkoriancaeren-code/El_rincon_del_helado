<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Venta.php';

class DashboardController {
    private $producto;
    private $cliente;
    private $venta;
    
    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->producto = new Producto($db);
        $this->cliente = new Cliente($db);
        $this->venta = new Venta($db);
    }
    
    public function getStats() {
        return [
            'total_productos' => count($this->producto->getAll()),
            'total_clientes' => count($this->cliente->getAll()),
            'ventas_hoy' => $this->venta->getCountHoy(),
            'total_hoy' => $this->venta->getTotalHoy(),
            'total_mes' => $this->venta->getTotalMes(),
            'stock_bajo' => count($this->producto->getLowStock())
        ];
    }
    
    public function getUltimasVentas($limit = 10) {
        return $this->venta->getAll($limit);
    }
}
?>