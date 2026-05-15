<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $rol = $_POST['rol'] ?? 'vendedor';

    if ($email && $nombre) {
        $_SESSION['user_id'] = 1;
        $_SESSION['user_nombre'] = $nombre;
        $_SESSION['user_email'] = $email;
        $_SESSION['user_rol'] = $rol;
        $_SESSION['logged_in'] = true;

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
