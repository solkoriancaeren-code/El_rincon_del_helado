<?php
$current_page = $_GET['page'] ?? 'dashboard';
?>
<ul class="sidebar-menu">
    <li class="<?php echo $current_page == 'dashboard' ? 'active' : ''; ?>">
        <a href="../../index.php?page=dashboard">
            <span class="icon">📊</span>
            <span>Dashboard</span>
        </a>
    </li>
    <li class="<?php echo $current_page == 'pos' ? 'active' : ''; ?>">
        <a href="../../index.php?page=pos">
            <span class="icon">🛒</span>
            <span>Punto de Venta</span>
        </a>
    </li>
    <li class="<?php echo $current_page == 'productos' ? 'active' : ''; ?>">
        <a href="../../index.php?page=productos">
            <span class="icon">🍦</span>
            <span>Productos</span>
        </a>
    </li>
    <li class="<?php echo $current_page == 'clientes' ? 'active' : ''; ?>">
        <a href="../../index.php?page=clientes">
            <span class="icon">👥</span>
            <span>Clientes</span>
        </a>
    </li>
    <li class="<?php echo $current_page == 'ventas' ? 'active' : ''; ?>">
        <a href="../../index.php?page=ventas">
            <span class="icon">💰</span>
            <span>Ventas</span>
        </a>
    </li>
    <li>
        <a href="../../logout.php">
            <span class="icon">🚪</span>
            <span>Cerrar Sesión</span>
        </a>
    </li>
</ul>