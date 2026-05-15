<?php
require_once __DIR__ . '/../../config/Database.php';
require_once __DIR__ . '/../../models/Producto.php';
require_once __DIR__ . '/../../models/Cliente.php';
requireLogin();

$database = new Database();
$db = $database->getConnection();
$productoModel = new Producto($db);
$clienteModel = new Cliente($db);

$productos = $productoModel->getAll();
$clientes = $clienteModel->getAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Punto de Venta - El Rincón del Helado</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <link rel="stylesheet" href="../../assets/css/pos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="pos-container">
        <div class="pos-productos">
            <div class="pos-header">
                <h2>Productos</h2>
                <input type="text" id="buscar" class="form-control" placeholder="Buscar producto...">
            </div>
            <div class="productos-grid" id="productosGrid">
                <?php foreach($productos as $producto): ?>
                <div class="producto-card" 
                     data-id="<?php echo $producto['id']; ?>"
                     data-nombre="<?php echo htmlspecialchars($producto['nombre']); ?>"
                     data-precio="<?php echo $producto['precio_mediano']; ?>"
                     data-stock="<?php echo $producto['stock']; ?>">
                    <h4><?php echo htmlspecialchars($producto['nombre']); ?></h4>
                    <div class="precio">$<?php echo number_format($producto['precio_mediano'], 2); ?></div>
                    <div class="stock">Stock: <?php echo $producto['stock']; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="pos-carrito">
            <div class="carrito-header">
                <h3>Carrito de Compras</h3>
            </div>
            <div class="carrito-items" id="carritoItems">
                <div class="empty-cart">No hay productos en el carrito</div>
            </div>
            <div class="carrito-total">
                <div class="total-line">
                    <span>Subtotal:</span>
                    <span id="subtotal">$0.00</span>
                </div>
                <div class="total-line">
                    <span>IVA (16%):</span>
                    <span id="iva">$0.00</span>
                </div>
                <div class="total-line total-final">
                    <span>Total:</span>
                    <span id="total">$0.00</span>
                </div>
            </div>
            <div class="cliente-section">
                <label>Cliente:</label>
                <select id="clienteId" class="cliente-select">
                    <option value="">Cliente General</option>
                    <?php foreach($clientes as $cliente): ?>
                    <option value="<?php echo $cliente['id']; ?>">
                        <?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="metodo-pago">
                <label>Método de Pago:</label>
                <select id="metodoPago" class="cliente-select">
                    <option value="efectivo">Efectivo</option>
                    <option value="tarjeta">Tarjeta</option>
                    <option value="transferencia">Transferencia</option>
                </select>
            </div>
            <button class="btn-finalizar" onclick="finalizarVenta()">Finalizar Venta</button>
        </div>
    </div>
    
    <script>
        let carrito = [];
        
        // Agregar productos al carrito
        document.querySelectorAll('.producto-card').forEach(card => {
            card.addEventListener('click', () => {
                const id = parseInt(card.dataset.id);
                const nombre = card.dataset.nombre;
                const precio = parseFloat(card.dataset.precio);
                const stock = parseInt(card.dataset.stock);
                
                const existente = carrito.find(p => p.id === id);
                if (existente) {
                    if (existente.cantidad + 1 <= stock) {
                        existente.cantidad++;
                        existente.subtotal = existente.cantidad * existente.precio;
                    } else {
                        Swal.fire('Error', 'Stock insuficiente', 'error');
                    }
                } else {
                    if (stock > 0) {
                        carrito.push({ id, nombre, precio, cantidad: 1, subtotal: precio, stock });
                    } else {
                        Swal.fire('Error', 'Producto sin stock', 'error');
                    }
                }
                actualizarCarrito();
            });
        });
        
        // Buscar productos
        document.getElementById('buscar').addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            document.querySelectorAll('.producto-card').forEach(card => {
                const nombre = card.dataset.nombre.toLowerCase();
                card.style.display = nombre.includes(term) ? 'block' : 'none';
            });
        });
        
        function actualizarCarrito() {
            const container = document.getElementById('carritoItems');
            if (carrito.length === 0) {
                container.innerHTML = '<div class="empty-cart">No hay productos en el carrito</div>';
                document.getElementById('subtotal').innerText = '$0.00';
                document.getElementById('iva').innerText = '$0.00';
                document.getElementById('total').innerText = '$0.00';
                return;
            }
            
            let html = '';
            let subtotal = 0;
            
            carrito.forEach((item, index) => {
                subtotal += item.subtotal;
                html += `
                    <div class="carrito-item">
                        <div class="carrito-item-info">
                            <h5>${item.nombre}</h5>
                            <p>$${item.precio.toFixed(2)} c/u</p>
                        </div>
                        <div class="carrito-item-precio">
                            $${item.subtotal.toFixed(2)}
                        </div>
                        <div class="carrito-item-acciones">
                            <button onclick="cambiarCantidad(${index}, -1)">-</button>
                            <span>${item.cantidad}</span>
                            <button onclick="cambiarCantidad(${index}, 1)">+</button>
                            <button onclick="eliminarProducto(${index})" class="btn-eliminar">×</button>
                        </div>
                    </div>
                `;
            });
            
            const iva = subtotal * 0.16;
            const total = subtotal + iva;
            
            container.innerHTML = html;
            document.getElementById('subtotal').innerText = `$${subtotal.toFixed(2)}`;
            document.getElementById('iva').innerText = `$${iva.toFixed(2)}`;
            document.getElementById('total').innerText = `$${total.toFixed(2)}`;
        }
        
        function cambiarCantidad(index, cambio) {
            const item = carrito[index];
            const nuevaCantidad = item.cantidad + cambio;
            
            if (nuevaCantidad > 0 && nuevaCantidad <= item.stock) {
                item.cantidad = nuevaCantidad;
                item.subtotal = item.cantidad * item.precio;
                actualizarCarrito();
            } else if (nuevaCantidad > item.stock) {
                Swal.fire('Error', 'Stock insuficiente', 'error');
            } else if (nuevaCantidad === 0) {
                eliminarProducto(index);
            }
        }
        
        function eliminarProducto(index) {
            carrito.splice(index, 1);
            actualizarCarrito();
        }
        
        async function finalizarVenta() {
            if (carrito.length === 0) {
                Swal.fire('Error', 'El carrito está vacío', 'error');
                return;
            }
            
            const clienteId = document.getElementById('clienteId').value;
            const metodoPago = document.getElementById('metodoPago').value;
            const subtotal = parseFloat(document.getElementById('subtotal').innerText.replace('$', ''));
            const iva = parseFloat(document.getElementById('iva').innerText.replace('$', ''));
            const total = parseFloat(document.getElementById('total').innerText.replace('$', ''));
            
            const response = await fetch('../../api/ventas.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    cliente_id: clienteId || null,
                    metodo_pago: metodoPago,
                    subtotal: subtotal,
                    iva: iva,
                    total: total,
                    productos: carrito.map(p => ({
                        id: p.id,
                        cantidad: p.cantidad,
                        precio: p.precio,
                        subtotal: p.subtotal,
                        tamaño: 'mediano'
                    }))
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                Swal.fire({
                    title: '¡Venta registrada!',
                    text: `Folio: ${result.folio}`,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.open(`factura.php?id=${result.venta_id}`, '_blank');
                    location.reload();
                });
            } else {
                Swal.fire('Error', result.error || 'Error al registrar la venta', 'error');
            }
        }
    </script>
</body>
</html>