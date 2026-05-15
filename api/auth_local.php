<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['user_id'] = 1;
    $_SESSION['user_nombre'] = $_POST['nombre'];
    $_SESSION['user_email'] = $_POST['email'];
    $_SESSION['user_rol'] = $_POST['rol'];
    
    echo json_encode(['success' => true]);
}
?>