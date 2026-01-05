<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

try {
    $pdo = new PDO("mysql:host=localhost;dbname=qr_carta", "root", "root");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("No hay datos");
    }

    // Por ahora actualizamos el negocio con ID 1
    $stmt = $pdo->prepare("UPDATE negocios SET nombre = ?, slug = ?, logo_url = ? WHERE id = " . $data['id']);
    $stmt->execute([$data['nombre'], $data['slug'], $data['logo_url']]);

    echo json_encode(['status' => 'success', 'message' => 'Configuración actualizada']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
