// Carrito de compras
let cart = [];

// Función para agregar al carrito
function addToCart(id, nombre, precio, stock) {
    const existing = cart.find(item => item.id === id);
    if (existing) {
        if (existing.cantidad + 1 <= stock) {
            existing.cantidad++;
            existing.subtotal = existing.cantidad * existing.precio;
        } else {
            showPosToast('Stock insuficiente', 'error');
            return;
        }
    } else {
        if (stock > 0) {
            cart.push({ id, nombre, precio, cantidad: 1, subtotal: precio, stock });
        } else {
            showPosToast('Producto sin stock', 'error');
            return;
        }
    }
    updateCart();
}

// Actualizar carrito visual
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
                <div class="cart-item-price">
                    $${item.subtotal.toFixed(2)}
                </div>
                <div class="cart-item-actions">
                    <button class="cart-qty-btn" onclick="updateQuantity(${index}, -1)">-</button>
                    <span style="margin: 0 5px; min-width: 20px; text-align: center;">${item.cantidad}</span>
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

// Actualizar cantidad
window.updateQuantity = function (index, change) {
    const item = cart[index];
    const newQty = item.cantidad + change;

    if (newQty > 0 && newQty <= item.stock) {
        item.cantidad = newQty;
        item.subtotal = item.cantidad * item.precio;
        updateCart();
    } else if (newQty > item.stock) {
        showPosToast('Stock insuficiente', 'error');
    } else if (newQty === 0) {
        removeFromCart(index);
    }
};

// Eliminar producto
window.removeFromCart = function (index) {
    cart.splice(index, 1);
    updateCart();
};

// Buscar productos
function searchProducts() {
    const searchInput = document.getElementById('searchProducto');
    const productsGrid = document.getElementById('productosList');

    if (!searchInput || !productsGrid) return;

    const term = searchInput.value.toLowerCase();
    const cards = productsGrid.querySelectorAll('.product-card');

    cards.forEach(card => {
        const nombre = card.dataset.nombre.toLowerCase();
        card.style.display = nombre.includes(term) ? 'block' : 'none';
    });
}

// Finalizar venta
window.finalizarVenta = function () {
    if (cart.length === 0) {
        showPosToast('El carrito está vacío', 'error');
        return;
    }

    showPosToast('¡Venta registrada exitosamente!', 'success');
    cart = [];
    updateCart();
};

// Toast para POS
function showPosToast(message, type) {
    const toast = document.getElementById('posToast');
    if (!toast) return;

    const messageSpan = toast.querySelector('.toast-message');
    const icon = toast.querySelector('i');

    messageSpan.textContent = message;
    toast.classList.remove('error', 'success');
    toast.classList.add(type, 'show');

    if (type === 'error') {
        icon.className = 'fas fa-exclamation-circle';
    } else {
        icon.className = 'fas fa-check-circle';
    }

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// Inicializar POS
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchProducto');
    if (searchInput) {
        searchInput.addEventListener('input', searchProducts);
    }

    // Agregar event listeners a productos
    const productosList = document.getElementById('productosList');
    if (productosList) {
        const cards = productosList.querySelectorAll('.product-card');
        cards.forEach(card => {
            card.addEventListener('click', () => {
                const id = parseInt(card.dataset.id);
                const nombre = card.dataset.nombre;
                const precio = parseFloat(card.dataset.precio);
                const stock = parseInt(card.dataset.stock);
                addToCart(id, nombre, precio, stock);
            });
        });
    }
});