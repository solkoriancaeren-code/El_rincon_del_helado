<?php
require_once __DIR__ . '/../../controllers/ProductoController.php';

$controller = new ProductoController();
$productos = $controller->index();
?>
<div class="table-container">
    <div class="table-header">
        <h2>Productos</h2>
        <button class="btn btn-primary" onclick="window.location.href='crear.php'">+ Nuevo Producto</button>
    </div>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos as $producto): ?>
                <tr>
                    <td><?php echo $producto['codigo']; ?></td>
                    <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                    <td><?php echo $producto['categoria_nombre']; ?></td>
                    <td>$<?php echo number_format($producto['precio_mediano'], 2); ?></td>
                    <td><?php echo $producto['stock']; ?></td>
                    <td>
                        <?php if ($producto['stock'] <= $producto['stock_minimo']): ?>
                            <span class="badge badge-danger">Stock Bajo</span>
                        <?php else: ?>
                            <span class="badge badge-success">Disponible</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editar(<?php echo $producto['id']; ?>)">Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="eliminar(<?php echo $producto['id']; ?>)">Eliminar</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    function editar(id) {
        window.location.href = `editar.php?id=${id}`;
    }

    function eliminar(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const response = await fetch(`../../api/productos.php?id=${id}`, {
                    method: 'DELETE'
                });
                const data = await response.json();
                if (data.success) {
                    Swal.fire('Eliminado', 'Producto eliminado', 'success');
                    location.reload();
                } else {
                    Swal.fire('Error', 'Error al eliminar', 'error');
                }
            }
        });
    }
</script>