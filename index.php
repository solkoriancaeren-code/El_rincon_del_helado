<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Rincón del Helado - Sistema de Gestión</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
        }
        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #333 0%, #242424 100%);
            color: white;
            position: fixed;
            height: 100vh;
        }
        .sidebar-header {
            padding: 25px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-header h2 { color: #FFD700; font-size: 22px; }
        .sidebar-header p { color: #87CEEB; font-size: 14px; }
        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }
        .sidebar-menu li {
            padding: 12px 25px;
            transition: all 0.3s;
        }
        .sidebar-menu li:hover { background: rgba(255,255,255,0.1); }
        .sidebar-menu a {
            color: white;
            text-decoration: none;
            display: flex;
            gap: 12px;
        }
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 20px;
        }
        .top-bar {
            background: white;
            padding: 15px 25px;
            border-radius: 15px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .logout-btn {
            background: #ff4444;
            color: white;
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .stat-card h4 { color: #666; font-size: 14px; margin-bottom: 10px; }
        .stat-card .number { font-size: 32px; font-weight: bold; color: #FF69B4; }
        .table-container {
            background: white;
            border-radius: 15px;
            padding: 20px;
            overflow-x: auto;
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
        th { background: #f5f5f5; }
        .welcome-text h3 { color: #333; }
        .welcome-text p { color: #666; font-size: 14px; }
    </style>
</head>
<body>
    <?php if(!isset($_SESSION['user_id'])): ?>
        <div style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
            <div style="text-align: center; background: white; padding: 40px; border-radius: 20px;">
                <h1 style="color: #FF69B4;">🍦 El Rincón del Helado</h1>
                <p style="margin: 20px 0;">No has iniciado sesión</p>
                <a href="views/auth/login.php" style="background: #FF69B4; color: white; padding: 10px 20px; text-decoration: none; border-radius: 8px;">Iniciar Sesión</a>
            </div>
        </div>
    <?php else: ?>
        <div class="dashboard-container">
            <div class="sidebar">
                <div class="sidebar-header">
                    <h2>🍦 El Rincón</h2>
                    <p>del Helado</p>
                </div>
                <ul class="sidebar-menu">
                    <li><a href="index.php?page=dashboard">📊 Dashboard</a></li>
                    <li><a href="index.php?page=productos">🍦 Productos</a></li>
                    <li><a href="index.php?page=clientes">👥 Clientes</a></li>
                    <li><a href="index.php?page=ventas">💰 Ventas</a></li>
                    <li><a href="logout.php">🚪 Cerrar Sesión</a></li>
                </ul>
            </div>
            <div class="main-content">
                <div class="top-bar">
                    <div class="welcome-text">
                        <h3>Bienvenido, <?php echo $_SESSION['user_nombre']; ?></h3>
                        <p><?php echo date('d/m/Y H:i'); ?></p>
                    </div>
                    <div>
                        <span style="margin-right: 15px;">Rol: <?php echo $_SESSION['user_rol']; ?></span>
                        <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
                    </div>
                </div>
                
                <?php
                $page = $_GET['page'] ?? 'dashboard';
                
                if($page == 'dashboard'):
                ?>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h4>Productos</h4>
                        <div class="number">8</div>
                    </div>
                    <div class="stat-card">
                        <h4>Clientes</h4>
                        <div class="number">3</div>
                    </div>
                    <div class="stat-card">
                        <h4>Ventas Hoy</h4>
                        <div class="number">0</div>
                    </div>
                    <div class="stat-card">
                        <h4>Ingresos Hoy</h4>
                        <div class="number">$0.00</div>
                    </div>
                </div>
                <div class="table-container">
                    <h3>Bienvenido al Sistema</h3>
                    <p>El sistema "El Rincón del Helado" está funcionando correctamente.</p>
                    <br>
                    <p>Próximamente: Módulo de ventas, gestión de productos y más.</p>
                </div>
                <?php elseif($page == 'productos'): ?>
                <div class="table-container">
                    <h2>Productos</h2>
                    <table>
                        <thead>
                            <tr><th>Código</th><th>Nombre</th><th>Precio</th><th>Stock</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>HEL001</td><td>Vainilla</td><td>$35.00</td><td>100</td></tr>
                            <tr><td>HEL002</td><td>Chocolate</td><td>$38.00</td><td>85</td></tr>
                            <tr><td>HEL003</td><td>Fresa</td><td>$37.00</td><td>90</td></tr>
                        </tbody>
                    </table>
                </div>
                <?php elseif($page == 'clientes'): ?>
                <div class="table-container">
                    <h2>Clientes</h2>
                    <table>
                        <thead><tr><th>Código</th><th>Nombre</th><th>Email</th><th>Teléfono</th></tr></thead>
                        <tbody>
                            <tr><td>CLI001</td><td>Carlos Pérez</td><td>carlos@email.com</td><td>555-0101</td></tr>
                            <tr><td>CLI002</td><td>Ana Gómez</td><td>ana@email.com</td><td>555-0102</td></tr>
                        </tbody>
                    </table>
                </div>
                <?php elseif($page == 'ventas'): ?>
                <div class="table-container">
                    <h2>Ventas</h2>
                    <table>
                        <thead><tr><th>Folio</th><th>Cliente</th><th>Total</th><th>Fecha</th></tr></thead>
                        <tbody>
                            <tr><td>FAC001</td><td>Carlos Pérez</td><td>$81.20</td><td><?php echo date('d/m/Y'); ?></td></tr>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>