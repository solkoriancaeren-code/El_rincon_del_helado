<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - El Rincón del Helado</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <h1>🍦 El Rincón del Helado</h1>
                <p>Sistema de Gestión</p>
            </div>
            <form id="loginForm">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" class="form-control" placeholder="admin@heladeria.com" required>
                </div>
                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" id="password" class="form-control" placeholder="admin123" required>
                </div>
                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            const response = await fetch('../../api/auth.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });
            
            const result = await response.json();
            
            if (result.success) {
                window.location.href = '../../index.php';
            } else {
                Swal.fire('Error', 'Credenciales incorrectas', 'error');
            }
        });
    </script>
</body>
</html>