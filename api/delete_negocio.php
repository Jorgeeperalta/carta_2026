<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=qr_carta", "root", "root");
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['id'])) {
        throw new Exception("ID de negocio no proporcionado");
    }

    // Opcional: Eliminar en cascada si no está configurado en DB
    // $pdo->prepare("DELETE FROM productos WHERE categoria_id IN (SELECT id FROM categorias WHERE negocio_id = ?)")->execute([$data['id']]);
    // $pdo->prepare("DELETE FROM categorias WHERE negocio_id = ?")->execute([$data['id']]);
    
    $stmt = $pdo->prepare("DELETE FROM negocios WHERE id = ?");
    $stmt->execute([$data['id']]);

    echo json_encode(['status' => 'success', 'message' => 'Negocio eliminado']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
