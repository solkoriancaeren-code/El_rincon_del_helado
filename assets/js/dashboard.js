// Datos de ejemplo
const productosData = [
    { id: 1, nombre: 'Vainilla', precio: 35, stock: 100 },
    { id: 2, nombre: 'Chocolate', precio: 38, stock: 85 },
    { id: 3, nombre: 'Fresa', precio: 37, stock: 90 },
    { id: 4, nombre: 'Menta', precio: 39, stock: 70 },
    { id: 5, nombre: 'Limón', precio: 36, stock: 75 }
];

const clientesData = [
    { id: 1, nombre: 'Carlos Pérez', email: 'carlos@email.com', telefono: '555-0101', puntos: 150 },
    { id: 2, nombre: 'Ana Gómez', email: 'ana@email.com', telefono: '555-0102', puntos: 230 },
    { id: 3, nombre: 'Luis Martínez', email: 'luis@email.com', telefono: '555-0103', puntos: 80 }
];

const ventasData = [
    { folio: 'FAC001', cliente: 'Carlos Pérez', total: 81.20, fecha: '2024-01-15' },
    { folio: 'FAC002', cliente: 'Ana Gómez', total: 52.20, fecha: '2024-01-14' },
    { folio: 'FAC003', cliente: 'Luis Martínez', total: 104.40, fecha: '2024-01-13' }
];

// Variables para gráficos
let ventasChart = null;
let productosChart = null;

// Renderizar tabla de últimas ventas
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

// Crear gráfico de ventas
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
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
}

// Crear gráfico de productos
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
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
}

// Función para ver factura
window.viewFactura = function(folio) {
    alert('Ver factura: ' + folio);
};

// Inicializar dashboard
document.addEventListener('DOMContentLoaded', () => {
    // Actualizar estadísticas
    const totalProductos = document.getElementById('totalProductos');
    const totalClientes = document.getElementById('totalClientes');
    const ventasHoy = document.getElementById('ventasHoy');
    const ingresosHoy = document.getElementById('ingresosHoy');
    
    if (totalProductos) totalProductos.innerText = productosData.length;
    if (totalClientes) totalClientes.innerText = clientesData.length;
    if (ventasHoy) ventasHoy.innerText = '3';
    if (ingresosHoy) ingresosHoy.innerText = '$237.80';
    
    // Renderizar tabla
    renderUltimasVentas();
    
    // Crear gráficos
    createVentasChart();
    createProductosChart();
});