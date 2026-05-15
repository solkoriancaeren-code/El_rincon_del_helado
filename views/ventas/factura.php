<?php
require_once __DIR__ . '/../../controllers/VentaController.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    die('ID de venta no especificado');
}

$controller = new VentaController();
$venta = $controller->show($id);

if (!$venta) {
    die('Venta no encontrada');
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura - El Rincón del Helado</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 20px;
        }

        .factura {
            max-width: 300px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 15px;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            margin-bottom: 15px;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            margin: 0;
        }

        .linea {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .total {
            font-weight: bold;
            border-top: 1px solid #000;
            margin-top: 10px;
            padding-top: 10px;
        }

        button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background: #4caf50;
            color: white;
            border: none;
            cursor: pointer;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="factura">
        <div class="header">
            <h1>🍦 El Rincón del Helado</h1>
            <p>Sistema de Gestión</p>
            <p>Folio: <?php echo $venta['folio']; ?></p>
            <p>Fecha: <?php echo date('d/m/Y H:i', strtotime($venta['fecha'])); ?></p>
        </div>

        <div>
            <p><strong>Cliente:</strong> <?php echo $venta['cliente_nombre'] ?? 'Cliente General'; ?></p>
            <p><strong>Vendedor:</strong> <?php echo $venta['vendedor']; ?></p>
        </div>

        <div class="linea"></div>

        <div>
            <?php foreach ($venta['detalles'] as $detalle): ?>
                <div class="item">
                    <span><?php echo $detalle['cantidad']; ?>x <?php echo $detalle['producto_nombre']; ?></span>
                    <span>$<?php echo number_format($detalle['subtotal'], 2); ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="linea"></div>

        <div class="item">
            <span>Subtotal:</span>
            <span>$<?php echo number_format($venta['subtotal'], 2); ?></span>
        </div>
        <div class="item">
            <span>IVA (16%):</span>
            <span>$<?php echo number_format($venta['iva'], 2); ?></span>
        </div>
        <div class="item total">
            <span>TOTAL:</span>
            <span>$<?php echo number_format($venta['total'], 2); ?></span>
        </div>

        <div class="linea"></div>

        <div style="text-align: center; margin-top: 15px;">
            <p>¡Gracias por su compra!</p>
            <p>Vuelva pronto</p>
        </div>
    </div>
    <button onclick="window.print()">Imprimir Factura</button>
</body>

</html>