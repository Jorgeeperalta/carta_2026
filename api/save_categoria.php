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
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['negocio_id']) || !isset($data['nombre'])) {
        throw new Exception("Datos incompletos");
    }

    $stmt = $pdo->prepare("INSERT INTO categorias (negocio_id, nombre) VALUES (?, ?)");
    $stmt->execute([$data['negocio_id'], $data['nombre']]);

    echo json_encode(['status' => 'success', 'message' => 'Categoría creada', 'id' => $pdo->lastInsertId()]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
