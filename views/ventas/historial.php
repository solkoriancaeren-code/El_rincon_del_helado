<?php
require_once __DIR__ . '/../../controllers/VentaController.php';
requireLogin();

$controller = new VentaController();
$ventas = $controller->index();
?>
<div class="table-container">
    <h2>Historial de Ventas</h2>
    <table>
        <thead>
            <tr>
                <th>Folio</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Subtotal</th>
                <th>IVA</th>
                <th>Total</th>
                <th>Método</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($ventas as $venta): ?>
            <tr>
                <td><?php echo $venta['folio']; ?></td>
                <td><?php echo $venta['cliente_nombre'] ?? 'Cliente General'; ?></td>
                <td><?php echo $venta['vendedor']; ?></td>
                <td>$<?php echo number_format($venta['subtotal'], 2); ?></td>
                <td>$<?php echo number_format($venta['iva'], 2); ?></td>
                <td><strong>$<?php echo number_format($venta['total'], 2); ?></strong></td>
                <td><?php echo ucfirst($venta['metodo_pago']); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($venta['fecha'])); ?></td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="verFactura(<?php echo $venta['id']; ?>)">Ver</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
function verFactura(id) {
    window.open(`factura.php?id=${id}`, '_blank');
}
</script>