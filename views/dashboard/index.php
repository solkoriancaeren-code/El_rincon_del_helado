<?php
require_once __DIR__ . '/../../controllers/DashboardController.php';

$dashboard = new DashboardController();
$stats = $dashboard->getStats();
$ultimasVentas = $dashboard->getUltimasVentas(10);
?>
<div class="stats-grid">
    <div class="stat-card">
        <h4>Productos</h4>
        <div class="number"><?php echo $stats['total_productos']; ?></div>
        <div class="trend">🍦 En inventario</div>
    </div>
    <div class="stat-card">
        <h4>Clientes</h4>
        <div class="number"><?php echo $stats['total_clientes']; ?></div>
        <div class="trend">👥 Registrados</div>
    </div>
    <div class="stat-card">
        <h4>Ventas Hoy</h4>
        <div class="number"><?php echo $stats['ventas_hoy']; ?></div>
        <div class="trend">📊 Ventas</div>
    </div>
    <div class="stat-card">
        <h4>Ingresos Hoy</h4>
        <div class="number">$<?php echo number_format($stats['total_hoy'], 2); ?></div>
        <div class="trend">💰 Total</div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <h4>Ingresos del Mes</h4>
        <div class="number">$<?php echo number_format($stats['total_mes'], 2); ?></div>
        <div class="trend">📅 Acumulado</div>
    </div>
    <div class="stat-card">
        <h4>Stock Bajo</h4>
        <div class="number"><?php echo $stats['stock_bajo']; ?></div>
        <div class="trend">⚠️ Alertas</div>
    </div>
</div>

<div class="table-container">
    <h3>Últimas Ventas</h3>
    <table>
        <thead>
            <tr>
                <th>Folio</th>
                <th>Cliente</th>
                <th>Total</th>
                <th>Método</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($ultimasVentas as $venta): ?>
            <tr>
                <td><?php echo $venta['folio']; ?></td>
                <td><?php echo $venta['cliente_nombre'] ?? 'Cliente General'; ?></td>
                <td>$<?php echo number_format($venta['total'], 2); ?></td>
                <td><?php echo ucfirst($venta['metodo_pago']); ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($venta['fecha'])); ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($ultimasVentas)): ?>
            <tr>
                <td colspan="5" style="text-align: center;">No hay ventas registradas</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>