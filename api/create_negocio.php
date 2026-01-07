<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

require_once 'db.php';

try {
    $data = json_decode(file_get_contents("php://input"), true);

    if (empty($data['nombre'])) {
        throw new Exception("El nombre es obligatorio");
    }

    $stmt = $pdo->prepare("INSERT INTO negocios (nombre, slug, logo_url) VALUES (?,?,?)");
    $stmt->execute([$data['nombre'], $data['slug'], $data['logo_url']]);
    
    $newId = $pdo->lastInsertId();

    echo json_encode(['status' => 'success', 'id' => $newId]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage(), 'nombre' => $data['nombre'] ?? null, 'slug' => $data['slug'] ?? null, 'logo_url' => $data['logo_url'] ?? null]);
}
