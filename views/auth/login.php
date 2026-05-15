<?php
session_start();
// Si ya está logueado, redirigir al dashboard
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
    <title>El Rincón del Helado - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>

<body>
    <div id="bubbles"></div>

    <div class="login-container">
        <div class="login-wrapper">
            <div class="login-info">
                <div class="logo-icon">🍦</div>
                <h1>El Rincón del Helado</h1>
                <p>El sistema de gestión más completo para tu heladería. Controla ventas, inventario y clientes desde un solo lugar.</p>
                <ul class="features">
                    <li><i class="fas fa-chart-line"></i> Dashboard en tiempo real</li>
                    <li><i class="fas fa-cash-register"></i> Punto de venta intuitivo</li>
                    <li><i class="fas fa-boxes"></i> Control de inventario</li>
                    <li><i class="fas fa-users"></i> Gestión de clientes</li>
                    <li><i class="fas fa-chart-bar"></i> Reportes avanzados</li>
                </ul>
            </div>
            <div class="login-form">
                <div class="form-header">
                    <h2>Bienvenido 👋</h2>
                    <p>Ingresa tus credenciales para continuar</p>
                </div>
                <form id="loginForm">
                    <div class="input-group">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" placeholder="Correo electrónico" required>
                    </div>
                    <div class="input-group">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" placeholder="Contraseña" required>
                    </div>
                    <button type="submit" class="btn-login" id="loginBtn">
                        <i class="fas fa-arrow-right"></i> Iniciar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div id="toast" class="toast">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage">Mensaje</span>
    </div>

    <script>
        // Credenciales válidas
        const USUARIOS = {
            'admin@heladeria.com': {
                password: 'admin123',
                nombre: 'Administrador',
                rol: 'admin'
            },
            'vendedor@heladeria.com': {
                password: 'vendedor123',
                nombre: 'Vendedor',
                rol: 'vendedor'
            }
        };

        // Toast notification
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

        // Login form
        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const btn = document.getElementById('loginBtn');

            if (!email || !password) {
                showToast('Por favor, completa todos los campos', 'error');
                return;
            }

            // Mostrar loading
            btn.classList.add('loading');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando...';

            // Verificar credenciales
            if (USUARIOS[email] && USUARIOS[email].password === password) {
                try {
                    // Enviar al servidor para crear la sesión PHP
                    const response = await fetch('../../api/auth_local.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `email=${encodeURIComponent(email)}&nombre=${encodeURIComponent(USUARIOS[email].nombre)}&rol=${encodeURIComponent(USUARIOS[email].rol)}`
                    });

                    const result = await response.json();

                    if (result.success) {
                        showToast('¡Bienvenido ' + USUARIOS[email].nombre + '!', 'success');
                        setTimeout(() => {
                            window.location.href = '../../index.php';
                        }, 500);
                    } else {
                        throw new Error('Error al crear sesión');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Error al iniciar sesión', 'error');
                    btn.classList.remove('loading');
                    btn.innerHTML = '<i class="fas fa-arrow-right"></i> Iniciar Sesión';
                }
            } else {
                btn.classList.remove('loading');
                btn.innerHTML = '<i class="fas fa-arrow-right"></i> Iniciar Sesión';
                showToast('Email o contraseña incorrectos', 'error');

                // Efecto de shake
                const form = document.querySelector('.login-form');
                form.classList.add('shake');
                setTimeout(() => {
                    form.classList.remove('shake');
                }, 300);
            }
        });

        // Enter key submit
        document.getElementById('password').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                document.getElementById('loginForm').dispatchEvent(new Event('submit'));
            }
        });

        // Crear burbujas
        function createBubbles() {
            const bubblesContainer = document.getElementById('bubbles');
            for (let i = 0; i < 30; i++) {
                const bubble = document.createElement('div');
                bubble.classList.add('bubble');
                const size = Math.random() * 60 + 20;
                bubble.style.width = size + 'px';
                bubble.style.height = size + 'px';
                bubble.style.left = Math.random() * 100 + '%';
                bubble.style.animationDuration = Math.random() * 5 + 5 + 's';
                bubble.style.animationDelay = Math.random() * 5 + 's';
                bubblesContainer.appendChild(bubble);
            }
        }
        createBubbles();
    </script>
</body>

</html>