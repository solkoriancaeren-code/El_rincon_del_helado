<div class="top-bar">
    <div class="welcome-text">
        <h3>Bienvenido, <?php echo htmlspecialchars($_SESSION['user_nombre'] ?? 'Usuario'); ?></h3>
        <p><?php echo date('l, d \d\e F \d\e Y H:i'); ?></p>
    </div>
    <div class="user-info">
        <span class="badge"><?php echo ucfirst($_SESSION['user_rol'] ?? 'vendedor'); ?></span>
        <a href="../../logout.php" class="logout-btn">Cerrar Sesión</a>
    </div>
</div>