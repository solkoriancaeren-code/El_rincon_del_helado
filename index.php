<?php
require_once 'config/Database.php';
requireLogin();

$page = $_GET['page'] ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>El Rincón del Helado - Sistema de Gestión</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/pos.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2>🍦 El Rincón</h2>
                <p>del Helado</p>
            </div>
            <?php include 'views/layouts/sidebar.php'; ?>
        </div>
        
        <div class="main-content">
            <?php include 'views/layouts/header.php'; ?>
            
            <div class="page-content">
                <?php
                switch($page) {
                    case 'dashboard':
                        include 'views/dashboard/index.php';
                        break;
                    case 'pos':
                        include 'views/ventas/pos.php';
                        break;
                    case 'productos':
                        include 'views/productos/index.php';
                        break;
                    case 'clientes':
                        include 'views/clientes/index.php';
                        break;
                    case 'ventas':
                        include 'views/ventas/historial.php';
                        break;
                    default:
                        include 'views/dashboard/index.php';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>