<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../controllers/ProductoController.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'No autorizado']);
    exit();
}

$controller = new ProductoController();
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $result = $controller->show($_GET['id']);
            echo json_encode($result);
        } else {
            $result = $controller->index();
            echo json_encode($result);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $controller->store($data);
        echo json_encode($result);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? null;
        if ($id) {
            $result = $controller->update($id, $data);
            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $result = $controller->destroy($id);
            echo json_encode($result);
        } else {
            echo json_encode(['success' => false, 'error' => 'ID no proporcionado']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Método no permitido']);
}
