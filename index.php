<?php
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_nombre'])) {
    // Redirigir al login si no hay sesión
    header("Location: views/auth/login.php");
    exit();
}

// Si hay sesión pero no está completa, redirigir también
if (!isset($_SESSION['user_id']) && isset($_SESSION['user_nombre'])) {
    // Esto puede pasar si la sesión está corrupta
    session_destroy();
    header("Location: views/auth/login.php");
    exit();
}

// Datos del usuario logueado
$user_nombre = $_SESSION['user_nombre'] ?? 'Usuario';
$user_rol = $_SESSION['user_rol'] ?? 'vendedor';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - El Rincón del Helado</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="stylesheet" href="assets/css/pos.css">
    <style>
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #333;
            color: white;
            padding: 12px 24px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            transform: translateX(400px);
            transition: transform 0.3s;
            z-index: 1000;
            font-family: 'Poppins', sans-serif;
        }

        .toast.show {
            transform: translateX(0);
        }

        .toast.error {
            background: #ff4444;
        }

        .toast.success {
            background: #00C851;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo">🍦</div>
            <h3>El Rincón</h3>
            <p>del Helado</p>
        </div>
        <ul class="sidebar-menu">
            <li class="active" data-page="dashboard">
                <a href="javascript:void(0)">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li data-page="pos">
                <a href="javascript:void(0)">
                    <i class="fas fa-cash-register"></i>
                    <span>Punto de Venta</span>
                </a>
            </li>
            <li data-page="productos">
                <a href="javascript:void(0)">
                    <i class="fas fa-ice-cream"></i>
                    <span>Productos</span>
                </a>
            </li>
            <li data-page="clientes">
                <a href="javascript:void(0)">
                    <i class="fas fa-users"></i>
                    <span>Clientes</span>
                </a>
            </li>
            <li data-page="ventas">
                <a href="javascript:void(0)">
                    <i class="fas fa-receipt"></i>
                    <span>Ventas</span>
                </a>
            </li>
            <li>
                <a href="logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="top-bar">
            <div class="page-title">
                <h2 id="pageTitle">Dashboard</h2>
                <p id="pageSubtitle">Bienvenido de vuelta</p>
            </div>
            <div class="user-info">
                <div class="user-card">
                    <div class="user-avatar">
                        <?php echo substr($user_nombre, 0, 1); ?>
                    </div>
                    <div>
                        <strong><?php echo htmlspecialchars($user_nombre); ?></strong>
                        <small style="display: block; color: #666;"><?php echo htmlspecialchars($user_rol); ?></small>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Salir
                </a>
            </div>
        </div>

        <div id="pageContent">
            <!-- El contenido se carga dinámicamente -->
        </div>
    </div>

    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage">Mensaje</span>
    </div>

    <script>
        // Función para mostrar toast
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const toastMessage = document.getElementById('toastMessage');
            const icon = toast.querySelector('i');

            toastMessage.textContent = message;
            toast.classList.remove('error', 'success');
            toast.classList.add(type);

            if (type === 'error') {
                icon.className = 'fas fa-exclamation-circle';
            } else {
                icon.className = 'fas fa-check-circle';
            }

            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Datos de ejemplo
        const productosData = [{
                id: 1,
                nombre: 'Vainilla',
                precio: 35,
                stock: 100
            },
            {
                id: 2,
                nombre: 'Chocolate',
                precio: 38,
                stock: 85
            },
            {
                id: 3,
                nombre: 'Fresa',
                precio: 37,
                stock: 90
            },
            {
                id: 4,
                nombre: 'Menta',
                precio: 39,
                stock: 70
            },
            {
                id: 5,
                nombre: 'Limón',
                precio: 36,
                stock: 75
            }
        ];

        const clientesData = [{
                id: 1,
                nombre: 'Carlos Pérez',
                email: 'carlos@email.com',
                telefono: '555-0101',
                puntos: 150
            },
            {
                id: 2,
                nombre: 'Ana Gómez',
                email: 'ana@email.com',
                telefono: '555-0102',
                puntos: 230
            },
            {
                id: 3,
                nombre: 'Luis Martínez',
                email: 'luis@email.com',
                telefono: '555-0103',
                puntos: 80
            }
        ];

        const ventasData = [{
                folio: 'FAC001',
                cliente: 'Carlos Pérez',
                total: 81.20,
                fecha: '2024-01-15'
            },
            {
                folio: 'FAC002',
                cliente: 'Ana Gómez',
                total: 52.20,
                fecha: '2024-01-14'
            },
            {
                folio: 'FAC003',
                cliente: 'Luis Martínez',
                total: 104.40,
                fecha: '2024-01-13'
            }
        ];

        let ventasChart = null;
        let productosChart = null;

        function createVentasChart() {
            const canvas = document.getElementById('ventasChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            if (ventasChart) ventasChart.destroy();
            ventasChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                    datasets: [{
                        label: 'Ventas ($)',
                        data: [120, 200, 180, 250, 300, 450, 380],
                        borderColor: '#FF69B4',
                        backgroundColor: 'rgba(255, 105, 180, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }

        function createProductosChart() {
            const canvas = document.getElementById('productosChart');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            if (productosChart) productosChart.destroy();
            productosChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Vainilla', 'Chocolate', 'Fresa', 'Menta', 'Limón'],
                    datasets: [{
                        data: [35, 28, 25, 18, 15],
                        backgroundColor: ['#FFD700', '#FF69B4', '#87CEEB', '#FFA500', '#98FB98'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true
                }
            });
        }

        function renderUltimasVentas() {
            const tbody = document.querySelector('#ultimasVentasTable tbody');
            if (!tbody) return;
            if (ventasData.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center;">No hay ventas registradas</td></tr>';
                return;
            }
            tbody.innerHTML = ventasData.map(v => `
                <tr>
                    <td>${v.folio}</td>
                    <td>${v.cliente}</td>
                    <td>$${v.total.toFixed(2)}</td>
                    <td>${v.fecha}</td>
                </tr>
            `).join('');
        }

        // Variables para POS
        let cart = [];

        function addToCart(id, nombre, precio, stock) {
            const existing = cart.find(item => item.id === id);
            if (existing) {
                if (existing.cantidad + 1 <= stock) {
                    existing.cantidad++;
                    existing.subtotal = existing.cantidad * existing.precio;
                } else {
                    showToast('Stock insuficiente', 'error');
                    return;
                }
            } else {
                if (stock > 0) {
                    cart.push({
                        id,
                        nombre,
                        precio,
                        cantidad: 1,
                        subtotal: precio,
                        stock
                    });
                } else {
                    showToast('Producto sin stock', 'error');
                    return;
                }
            }
            updateCart();
        }

        function updateCart() {
            const cartItems = document.getElementById('cartItems');
            const subtotalSpan = document.getElementById('subtotal');
            const ivaSpan = document.getElementById('iva');
            const totalSpan = document.getElementById('total');

            if (!cartItems) return;

            if (cart.length === 0) {
                cartItems.innerHTML = '<div class="empty-cart">🛒 No hay productos en el carrito</div>';
                if (subtotalSpan) subtotalSpan.innerText = '$0.00';
                if (ivaSpan) ivaSpan.innerText = '$0.00';
                if (totalSpan) totalSpan.innerText = '$0.00';
                return;
            }

            let subtotal = 0;
            cartItems.innerHTML = cart.map((item, index) => {
                subtotal += item.subtotal;
                return `
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <h5>${item.nombre}</h5>
                            <p>$${item.precio.toFixed(2)} c/u</p>
                        </div>
                        <div class="cart-item-price">$${item.subtotal.toFixed(2)}</div>
                        <div class="cart-item-actions">
                            <button class="cart-qty-btn" onclick="updateQuantity(${index}, -1)">-</button>
                            <span>${item.cantidad}</span>
                            <button class="cart-qty-btn" onclick="updateQuantity(${index}, 1)">+</button>
                            <button class="cart-remove-btn" onclick="removeFromCart(${index})">×</button>
                        </div>
                    </div>
                `;
            }).join('');

            const iva = subtotal * 0.16;
            const total = subtotal + iva;

            if (subtotalSpan) subtotalSpan.innerText = `$${subtotal.toFixed(2)}`;
            if (ivaSpan) ivaSpan.innerText = `$${iva.toFixed(2)}`;
            if (totalSpan) totalSpan.innerText = `$${total.toFixed(2)}`;
        }

        window.updateQuantity = function(index, change) {
            const item = cart[index];
            const newQty = item.cantidad + change;
            if (newQty > 0 && newQty <= item.stock) {
                item.cantidad = newQty;
                item.subtotal = item.cantidad * item.precio;
                updateCart();
            } else if (newQty > item.stock) {
                showToast('Stock insuficiente', 'error');
            } else if (newQty === 0) {
                removeFromCart(index);
            }
        };

        window.removeFromCart = function(index) {
            cart.splice(index, 1);
            updateCart();
        };

        window.finalizarVenta = function() {
            if (cart.length === 0) {
                showToast('El carrito está vacío', 'error');
                return;
            }
            showToast('¡Venta registrada exitosamente!', 'success');
            cart = [];
            updateCart();
        };

        function searchProducts() {
            const searchInput = document.getElementById('searchProducto');
            if (!searchInput) return;
            const term = searchInput.value.toLowerCase();
            const cards = document.querySelectorAll('.product-card');
            cards.forEach(card => {
                const nombre = card.dataset.nombre.toLowerCase();
                card.style.display = nombre.includes(term) ? 'flex' : 'none';
            });
        }

        function loadPage(page) {
            const menuItems = document.querySelectorAll('.sidebar-menu li[data-page]');
            menuItems.forEach(item => item.classList.remove('active'));
            const activeItem = document.querySelector(`.sidebar-menu li[data-page="${page}"]`);
            if (activeItem) activeItem.classList.add('active');

            const pageTitle = document.getElementById('pageTitle');
            const pageSubtitle = document.getElementById('pageSubtitle');
            const pageContent = document.getElementById('pageContent');

            if (page === 'dashboard') {
                pageTitle.innerText = 'Dashboard';
                pageSubtitle.innerText = 'Bienvenido de vuelta';
                pageContent.innerHTML = `
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-info"><h4>Productos Totales</h4><div class="number">${productosData.length}</div></div>
                            <div class="stat-icon"><i class="fas fa-ice-cream"></i></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-info"><h4>Clientes Registrados</h4><div class="number">${clientesData.length}</div></div>
                            <div class="stat-icon"><i class="fas fa-users"></i></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-info"><h4>Ventas Hoy</h4><div class="number">3</div></div>
                            <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-info"><h4>Ingresos Hoy</h4><div class="number">$237.80</div></div>
                            <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                        </div>
                    </div>
                    <div class="charts-row">
                        <div class="chart-card"><h3><i class="fas fa-chart-line"></i> Ventas de la Semana</h3><canvas id="ventasChart" height="200"></canvas></div>
                        <div class="chart-card"><h3><i class="fas fa-chart-pie"></i> Productos Más Vendidos</h3><canvas id="productosChart" height="200"></canvas></div>
                    </div>
                    <div class="table-card"><h3><i class="fas fa-clock"></i> Últimas Ventas</h3><table id="ultimasVentasTable"><thead><tr><th>Folio</th><th>Cliente</th><th>Total</th><th>Fecha</th></tr></thead><tbody></tbody></table></div>
                `;
                createVentasChart();
                createProductosChart();
                renderUltimasVentas();
            } else if (page === 'productos') {
                pageTitle.innerText = 'Productos';
                pageSubtitle.innerText = 'Gestión de productos';
                pageContent.innerHTML = `
                    <div class="table-card">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                            <h3><i class="fas fa-ice-cream"></i> Lista de Productos</h3>
                            <button class="btn-primary" onclick="showToast('Formulario de producto', 'success')"><i class="fas fa-plus"></i> Nuevo Producto</button>
                        </div>
                        <table>
                            <thead><tr><th>Código</th><th>Nombre</th><th>Precio</th><th>Stock</th><th>Acciones</th></tr></thead>
                            <tbody>
                                ${productosData.map(p => `
                                    <tr>
                                        <td>HEL00${p.id}</td>
                                        <td><i class="fas fa-ice-cream" style="color:#FF69B4; margin-right:8px;"></i>${p.nombre}</td>
                                        <td>$${p.precio.toFixed(2)}</td>
                                        <td><span class="badge ${p.stock > 20 ? 'badge-success' : 'badge-warning'}">${p.stock} unidades</span></td>
                                        <td><button class="btn-icon btn-edit" onclick="showToast('Editar ${p.nombre}', 'success')"><i class="fas fa-edit"></i></button> <button class="btn-icon btn-delete" onclick="showToast('Eliminar ${p.nombre}', 'error')"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } else if (page === 'clientes') {
                pageTitle.innerText = 'Clientes';
                pageSubtitle.innerText = 'Gestión de clientes';
                pageContent.innerHTML = `
                    <div class="table-card">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                            <h3><i class="fas fa-users"></i> Lista de Clientes</h3>
                            <button class="btn-primary" onclick="showToast('Formulario de cliente', 'success')"><i class="fas fa-plus"></i> Nuevo Cliente</button>
                        </div>
                        <table>
                            <thead><tr><th>Código</th><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Puntos</th><th>Acciones</th></tr></thead>
                            <tbody>
                                ${clientesData.map(c => `
                                    <tr>
                                        <td>CLI00${c.id}</td>
                                        <td><i class="fas fa-user-circle" style="color:#87CEEB; margin-right:8px;"></i>${c.nombre}</td>
                                        <td>${c.email}</td>
                                        <td>${c.telefono}</td>
                                        <td><span class="badge badge-success">${c.puntos} pts</span></td>
                                        <td><button class="btn-icon btn-edit" onclick="showToast('Editar ${c.nombre}', 'success')"><i class="fas fa-edit"></i></button> <button class="btn-icon btn-delete" onclick="showToast('Eliminar ${c.nombre}', 'error')"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } else if (page === 'ventas') {
                pageTitle.innerText = 'Ventas';
                pageSubtitle.innerText = 'Historial de ventas';
                pageContent.innerHTML = `
                    <div class="table-card">
                        <h3><i class="fas fa-receipt"></i> Historial de Ventas</h3>
                        <table>
                            <thead><tr><th>Folio</th><th>Cliente</th><th>Vendedor</th><th>Subtotal</th><th>Total</th><th>Fecha</th><th>Acciones</th></tr></thead>
                            <tbody>
                                ${ventasData.map(v => `
                                    <tr>
                                        <td>${v.folio}</td>
                                        <td>${v.cliente}</td>
                                        <td>Admin</td>
                                        <td>$${(v.total / 1.16).toFixed(2)}</td>
                                        <td><strong>$${v.total.toFixed(2)}</strong></td>
                                        <td>${v.fecha}</td>
                                        <td><button class="btn-icon btn-edit" onclick="showToast('Ver factura ${v.folio}', 'success')"><i class="fas fa-print"></i> Ver</button></td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } else if (page === 'pos') {
                pageTitle.innerText = 'Punto de Venta';
                pageSubtitle.innerText = 'Registrar nueva venta';
                pageContent.innerHTML = `
                    <div class="pos-container">
                        <div class="pos-products">
                            <div class="pos-header">
                                <h3><i class="fas fa-search"></i> Productos</h3>
                                <input type="text" id="searchProducto" class="search-input" placeholder="Buscar producto...">
                            </div>
                            <div id="productosList" class="products-grid">
                                ${productosData.map(p => `
                                    <div class="product-card" data-id="${p.id}" data-nombre="${p.nombre}" data-precio="${p.precio}" data-stock="${p.stock}">
                                        <div class="product-icon"><i class="fas fa-ice-cream"></i></div>
                                        <h4>${p.nombre}</h4>
                                        <div class="product-price">$${p.precio.toFixed(2)}</div>
                                        <div class="product-stock">Stock: ${p.stock}</div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                        <div class="pos-cart">
                            <div class="cart-header"><h3><i class="fas fa-shopping-cart"></i> Carrito</h3></div>
                            <div id="cartItems" class="cart-items"></div>
                            <div class="cart-total">
                                <div class="total-line"><span>Subtotal:</span><span id="subtotal">$0.00</span></div>
                                <div class="total-line"><span>IVA (16%):</span><span id="iva">$0.00</span></div>
                                <div class="total-line total-final"><span>Total:</span><span id="total">$0.00</span></div>
                            </div>
                            <div class="client-section">
                                <label>Cliente:</label>
                                <select id="clienteId" class="client-select">
                                    <option value="">Cliente General</option>
                                    ${clientesData.map(c => `<option value="${c.id}">${c.nombre}</option>`).join('')}
                                </select>
                            </div>
                            <div class="payment-section">
                                <label>Método de Pago:</label>
                                <select id="metodoPago" class="payment-select">
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta">Tarjeta</option>
                                    <option value="transferencia">Transferencia</option>
                                </select>
                            </div>
                            <button class="btn-checkout" onclick="finalizarVenta()">Finalizar Venta</button>
                        </div>
                    </div>
                `;
                cart = [];
                updateCart();
                document.querySelectorAll('.product-card').forEach(card => {
                    card.addEventListener('click', () => {
                        const id = parseInt(card.dataset.id);
                        const nombre = card.dataset.nombre;
                        const precio = parseFloat(card.dataset.precio);
                        const stock = parseInt(card.dataset.stock);
                        addToCart(id, nombre, precio, stock);
                    });
                });
                const searchInput = document.getElementById('searchProducto');
                if (searchInput) searchInput.addEventListener('input', searchProducts);
            }
        }

        // Navegación
        document.querySelectorAll('.sidebar-menu li[data-page]').forEach(item => {
            item.addEventListener('click', () => loadPage(item.dataset.page));
        });

        // Cargar dashboard
        loadPage('dashboard');
    </script>
</body>

</html>