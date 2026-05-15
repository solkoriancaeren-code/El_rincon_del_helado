<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Venta.php';
require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Cliente.php';

class VentaController
{
    private $venta;
    private $producto;
    private $cliente;

    public function __construct()
    {
        $database = new Database();
        $db = $database->getConnection();
        $this->venta = new Venta($db);
        $this->producto = new Producto($db);
        $this->cliente = new Cliente($db);
    }

    public function index()
    {
        return $this->venta->getAll();
    }

    public function show($id)
    {
        return $this->venta->getById($id);
    }

    public function store($data)
    {
        if (empty($data['productos']) || count($data['productos']) == 0) {
            return ['success' => false, 'error' => 'Debe agregar productos a la venta'];
        }

        // Validar stock
        foreach ($data['productos'] as $producto) {
            $prod = $this->producto->getById($producto['id']);
            if (!$prod || $prod['stock'] < $producto['cantidad']) {
                return ['success' => false, 'error' => "Stock insuficiente para {$prod['nombre']}"];
            }
        }

        $result = $this->venta->create($data);
        return $result;
    }

    public function getEstadisticas()
    {
        return [
            'total_hoy' => $this->venta->getTotalHoy(),
            'ventas_hoy' => $this->venta->getCountHoy(),
            'total_mes' => $this->venta->getTotalMes()
        ];
    }
}
