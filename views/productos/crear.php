<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Producto.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto - El Rincón del Helado</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="form-container">
        <div class="form-card">
            <h2>Nuevo Producto</h2>
            <form id="productoForm">
                <div class="form-group">
                    <label>Nombre *</label>
                    <input type="text" id="nombre" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea id="descripcion" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Precio Pequeño</label>
                        <input type="number" id="precio_pequeño" class="form-control" step="0.01">
                    </div>
                    <div class="form-group">
                        <label>Precio Mediano *</label>
                        <input type="number" id="precio_mediano" class="form-control" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label>Precio Grande</label>
                        <input type="number" id="precio_grande" class="form-control" step="0.01">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Stock Inicial</label>
                        <input type="number" id="stock" class="form-control" value="0">
                    </div>
                    <div class="form-group">
                        <label>Stock Mínimo</label>
                        <input type="number" id="stock_minimo" class="form-control" value="5">
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php'">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('productoForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const data = {
                nombre: document.getElementById('nombre').value,
                descripcion: document.getElementById('descripcion').value,
                precio_pequeño: document.getElementById('precio_pequeño').value || 0,
                precio_mediano: document.getElementById('precio_mediano').value,
                precio_grande: document.getElementById('precio_grande').value || 0,
                stock: document.getElementById('stock').value,
                stock_minimo: document.getElementById('stock_minimo').value
            };
            
            const response = await fetch('../../api/productos.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                Swal.fire('Éxito', 'Producto creado correctamente', 'success')
                    .then(() => { window.location.href = 'index.php'; });
            } else {
                Swal.fire('Error', result.error, 'error');
            }
        });
    </script>
</body>
</html>