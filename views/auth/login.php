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
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #FFD700 0%, #FF69B4 50%, #87CEEB 100%);
        }
        .login-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .login-header { text-align: center; margin-bottom: 30px; }
        .login-header h1 { color: #FF69B4; font-size: 28px; }
        .login-header p { color: #666; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #333; }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }
        .form-control:focus {
            outline: none;
            border-color: #FF69B4;
        }
        .btn {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #FF69B4 0%, #FFD700 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,105,180,0.3);
        }
        .error-message {
            background: #ff4444;
            color: white;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            display: none;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <h1>🍦 El Rincón del Helado</h1>
            <p>Sistema de Gestión</p>
        </div>
        <div id="errorMessage" class="error-message"></div>
        <form id="loginForm">
            <div class="form-group">
                <label>Email</label>
                <input type="email" id="email" class="form-control" placeholder="admin@heladeria.com" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" id="password" class="form-control" placeholder="admin123" required>
            </div>
            <button type="submit" class="btn">Iniciar Sesión</button>
        </form>
    </div>
    <script>
        // Credenciales válidas
        const USUARIOS = {
            'admin@heladeria.com': { password: 'admin123', nombre: 'Administrador', rol: 'admin' },
            'vendedor@heladeria.com': { password: 'vendedor123', nombre: 'Vendedor', rol: 'vendedor' }
        };
        
        document.getElementById('loginForm').addEventListener('submit', (e) => {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const errorDiv = document.getElementById('errorMessage');
            
            // Verificar credenciales
            if (USUARIOS[email] && USUARIOS[email].password === password) {
                // Guardar sesión
                const formData = new FormData();
                formData.append('email', email);
                formData.append('nombre', USUARIOS[email].nombre);
                formData.append('rol', USUARIOS[email].rol);
                
                fetch('../../api/auth_local.php', {
                    method: 'POST',
                    body: formData
                })
                .then(() => {
                    window.location.href = '../../index.php';
                })
                .catch(() => {
                    window.location.href = '../../index.php';
                });
            } else {
                errorDiv.style.display = 'block';
                errorDiv.textContent = 'Email o contraseña incorrectos';
                
                setTimeout(() => {
                    errorDiv.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</body>
</html>