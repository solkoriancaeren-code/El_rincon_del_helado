<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Usuario.php';

session_start();

$database = new Database();
$db = $database->getConnection();
$usuario = new Usuario($db);

$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';
    
    $user = $usuario->authenticate($email, $password);
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nombre'] = $user['nombre'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_rol'] = $user['rol'];
        
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Credenciales incorrectas']);
    }
}
?>