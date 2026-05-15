// Crear burbujas animadas
function createBubbles() {
    const bubblesContainer = document.getElementById('bubbles');
    if (!bubblesContainer) return;

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

// Credenciales válidas
const USUARIOS = {
    'admin@heladeria.com': { password: 'admin123', nombre: 'Administrador', rol: 'admin' },
    'vendedor@heladeria.com': { password: 'vendedor123', nombre: 'Vendedor', rol: 'vendedor' }
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

// Validar email
function isValidEmail(email) {
    return email.includes('@') && email.includes('.');
}

// Inicializar login
document.addEventListener('DOMContentLoaded', () => {
    createBubbles();

    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const loginBtn = document.getElementById('loginBtn');

    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const email = emailInput.value.trim();
            const password = passwordInput.value;

            // Validaciones
            if (!email || !password) {
                showToast('Por favor, completa todos los campos', 'error');
                return;
            }

            if (!isValidEmail(email)) {
                showToast('Ingresa un email válido', 'error');
                return;
            }

            // Mostrar loading
            loginBtn.classList.add('loading');
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verificando...';

            // Simular delay para efecto
            setTimeout(() => {
                if (USUARIOS[email] && USUARIOS[email].password === password) {
                    // Guardar en localStorage
                    localStorage.setItem('user', JSON.stringify({
                        nombre: USUARIOS[email].nombre,
                        email: email,
                        rol: USUARIOS[email].rol
                    }));

                    showToast('¡Bienvenido ' + USUARIOS[email].nombre + '!', 'success');

                    setTimeout(() => {
                        window.location.href = '../../index.php';
                    }, 500);
                } else {
                    loginBtn.classList.remove('loading');
                    loginBtn.innerHTML = '<i class="fas fa-arrow-right"></i> Iniciar Sesión';
                    showToast('Email o contraseña incorrectos', 'error');

                    // Efecto de shake
                    const form = document.querySelector('.login-form');
                    form.classList.add('shake');
                    setTimeout(() => {
                        form.classList.remove('shake');
                    }, 300);
                }
            }, 800);
        });
    }

    // Enter key submit
    if (passwordInput) {
        passwordInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                loginForm.dispatchEvent(new Event('submit'));
            }
        });
    }
});